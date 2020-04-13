<?php
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2018 AazzTech.com.
*/
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

if (!class_exists('Directorist_Paypal_Gateway')):
    final class Directorist_Paypal_Gateway
    {
        /**
         * @var Directorist_Paypal_Gateway The one true Directorist_Paypal_Gateway
         * @since 1.0.0
         */
        private static $instance;

        /**
         * If true, the paypal sandbox URI www.sandbox.paypal.com is used for the
         * post back. If false, the live URI www.paypal.com is used. Default false.
         *
         * @since    1.0.0
         * @access   private
         * @var      bool
         */
        private $use_sandbox = false;

        /**
         * Main Directorist_Paypal_Gateway Instance.
         *
         * Insures that only one instance of Directorist_Paypal_Gateway exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         * @since 1.0.0
         * @return Directorist_Paypal_Gateway
         */
        public static function instance()
        {
            // if no object is created, then create it and return it. Else return the old object of our class
            if (!isset(self::$instance) && !(self::$instance instanceof self)) {
                self::$instance = new self; // create an instance of Directorist_Paypal_Gateway
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->use_sandbox = get_directorist_option('gateway_test_mode', true); //@todo; is it good to make the sandbox var private???
                // enable translation
                //add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
                // add new settings
                add_filter('atbdp_monetization_settings_submenus', array(self::$instance, 'paypal_gateway_settings_submenu'), 11, 1);
                // Add paypal gateway to the active gateway & default gateways selections
                add_filter('atbdp_active_gateways', array(self::$instance, 'default_active_gateways'));
                add_filter('atbdp_default_gateways', array(self::$instance, 'default_active_gateways'));
                // Process payment
                add_action('atbdp_process_paypal_gateway_payment', array(self::$instance, 'process_payment'));
                //Process IPN
                add_action('parse_request', array(self::$instance, 'parse_request'));

            }
            return self::$instance;
        }

        /**
         * Setup Directorist Paypal's plugin constants.
         *
         * @access private
         * @since 1.0.0
         * @return void
         */
        private function setup_constants()
        {
            require_once plugin_dir_path(__FILE__) . 'constants.php'; // loads constant from a file so that it can be available on all files.
        }

        /**
         *It includes required files and library needed by our class
         * @since 1.0.0
         */
        private function includes()
        {
            if (!class_exists('PaypalIPN')) {
                require_once plugin_dir_path(__FILE__) . 'library/PaypalIPN.php';
            }
            require_once plugin_dir_path(__FILE__) . 'helper.php';
        }

        /**
         * It loads plugin text domain
         * @since 1.0.0
         */
        public function load_textdomain()
        {
            load_plugin_textdomain('direo-extension', false, DT_PAYPAL_LANG_DIR);
        }

        /**
         * It adds our gateways to the active and default gateways list
         * @param array $gateways Arrays of all old gateways
         * @since 1.0.0
         * @return array It returns the new gateways list after adding paypal gateways
         */
        public function default_active_gateways($gateways)
        {
            /**
             * @todo latter PayPal option hide if shop currency doesn't support by PayPal
             */
            $gateways[] = array(
                'value' => 'paypal_gateway',
                'label' => __('Paypal', 'direo-extension'),
            );
            return $gateways;
        }


        /**
         * It adds a submenu of paypal gateway settings
         * @param array $submenus
         * @since 1.0.0
         * @return array
         */
        public function paypal_gateway_settings_submenu($submenus)
        {
            $submenus['paypal_gateway_submenu'] = array(
                'title' => __('Paypal Gateway', 'direo-extension'),
                'name' => 'paypal_gateway_menu',
                'icon' => 'font-awesome:fa-paypal',
                'controls' => apply_filters('atbdp_paypal_gateway_settings_controls', array(
                    'gateways' => array(
                        'type' => 'section',
                        'title' => __('Paypal Gateway Settings', 'direo-extension'),
                        'description' => __('You can customize all the settings related to your paypal gateway. After switching any option, Do not forget to save the changes.', 'direo-extension'),
                        'fields' => $this->get_paypal_gateway_settings_fields(),
                    ),
                )),
            );
            return $submenus;
        }


        /**
         * It register the settings fields of paypal gateway
         * @since 1.0.0
         * @return array It returns an array of paypal settings fields array
         */
        public function get_paypal_gateway_settings_fields()
        {
            $gsp = sprintf("<a target='_blank' href='%s'>%s</a>", esc_url(admin_url('edit.php?post_type=at_biz_dir&page=aazztech_settings#_gateway_general')), __('Gateway Settings Page', 'direo-extension'));

            return apply_filters('atbdp_paypal_gateway_settings_fields', array(
                    array(
                        'type' => 'notebox',
                        'name' => 'paypal_gateway_note',
                        'label' => __('Note About Paypal Gateway:', 'direo-extension'),
                        'description' => sprintf(__('If you want to use PayPal for a testing purpose, you should set Test MODE to Yes on The %s.', 'direo-extension'), $gsp),
                        'status' => 'info',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'paypal_gateway_email',
                        'label' => __('Your Business Email', 'direo-extension'),
                        'description' => __('Enter your PayPal business email', 'direo-extension'),
                        'default' => '',
                    ),

                    array(
                        'type' => 'textbox',
                        'name' => 'paypal_gateway_title',
                        'label' => __('Gateway Title', 'direo-extension'),
                        'description' => __('Enter the title of this gateway that should be displayed to the user on the front end.', 'direo-extension'),
                        'default' => esc_html__('PayPal', 'direo-extension'),
                    ),

                    array(
                        'type' => 'textarea',
                        'name' => 'paypal_gateway_description',
                        'label' => __('Gateway Description', 'direo-extension'),
                        'description' => __('Enter some description for your user to make payment using paypal.', 'direo-extension'),
                        'default' => __('You can make payment using paypal if you choose this payment gateway.', 'direo-extension')
                    ),


                )
            );
        }

        /**
         * It process the payment of the given order
         * @see for sending more than one custom var https://stackoverflow.com/questions/11631926/paypal-ipn-process-more-than-one-custom-variable
         * @param int $order_id
         * @since 1.0.0
         */
        public function process_payment($order_id)
        {
            $redirect_url = apply_filters('atbdp_payment_receipt_page_link', ATBDP_Permalink::get_payment_receipt_page_link( $order_id ), $order_id);
            $currency = get_directorist_option('payment_currency', get_directorist_option('g_currency', 'USD'));
            $business = get_directorist_option('paypal_gateway_email');
            $listing_id = get_post_meta($order_id, '_listing_id', true);
            $amount = get_post_meta($order_id, '_amount', true);
            $host = $this->use_sandbox ? 'www.sandbox.paypal.com' : 'www.paypal.com';
            $cmd = '_xclick';
            $plan = '';
            if (class_exists('ATBDP_Pricing_Plans')) {
                $plan = get_post_meta($listing_id, '_fm_plans', true);
                $is_recurring = get_post_meta($plan, '_atpp_recurring', true);
                if (!empty($is_recurring)) {
                    $cmd = '_xclick-subscriptions';
                }
            }
            ?>
            <p><?php esc_html_e('Please DO NOT close this window. Now you will be redirected to paypal.com for completing your purchase.', 'direo-extension'); ?></p>

            <form id="directorist-paypal-form" name="directorist-paypal-form"
                  action="<?php echo esc_url("https://{$host}/cgi-bin/webscr"); ?>" method="post">
                <input type="hidden" name="cmd" value="<?php echo $cmd; ?>">
                <input type="hidden" name="custom" value="<?php echo $listing_id; ?>">
                <!--if we need to send more than one custom var, -->
                <!--Business email is the email of the seller who will received the money from the paypal -->
                <input type="hidden" name="business" value="<?php echo esc_attr($business) ?>">
                <input type="hidden" name="currency_code" value="<?php echo esc_attr($currency); ?>">
                <input type="hidden" name="item_name"
                       value="<?php echo esc_attr(get_the_title(!empty($plan) ? $plan : $listing_id)); ?>">
                <input type="hidden" name="item_number" value="<?php echo esc_attr($order_id); ?>">
                <input type="hidden" name="amount" value="<?php echo esc_attr($amount); ?>">
                <input type="hidden" name="cancel_return"
                       value="<?php echo ATBDP_Permalink::get_transaction_failure_page_link(); ?>">
                <input type="hidden" name="notify_url"
                       value="<?php echo ATBDP_Permalink::get_ipn_notify_page_link($order_id); ?>">
                <input type="hidden" name="return"
                       value="<?php echo $redirect_url; ?>">
                <input type="hidden" name="no_shipping" value="0">
                <?php
                if (class_exists('ATBDP_Pricing_Plans')) {
                    $plan = get_post_meta($listing_id, '_fm_plans', true);
                    $is_recurring = get_post_meta($plan, '_atpp_recurring', true);
                    $recurrence_period_term = get_post_meta($plan, '_recurrence_period_term', true);
                    $recurrence_time = get_post_meta($plan, '_recurrence_time', true);
                    $time_period = '';
                    if ('day' === $recurrence_period_term){
                        $time_period = 'D';
                    }
                    if ('week' === $recurrence_period_term){
                        $time_period = 'W';
                    }
                    if ('month' === $recurrence_period_term){
                        $time_period = 'M';
                    }
                    if ('year' === $recurrence_period_term){
                        $time_period = 'Y';
                    }
                    if (!empty($is_recurring)) {
                            ?>
                            <input type="hidden" name="a3" value="<?php echo esc_attr($amount); ?>">
                            <input type="hidden" name="p3" value="<?php echo (int)$recurrence_time; ?>">
                            <input type="hidden" name="t3" value="<?php echo ($time_period)?>">
                            <input type="hidden" name="src" value="1">
                            <input type="hidden" name="sra" value="1">
                            <?php
                    }
                }
                ?>

            </form>

            <script type="text/javascript">
                document.getElementById('directorist-paypal-form').submit();
            </script>
            <?php

        }

        /**
         *It process Paypal Instant Payment Notification
         * @see https://github.com/paypal/ipn-code-samples/blob/master/php/example_usage.php
         * @param  int $order_id
         * @since 1.0.0
         */
        private function process_paypal_ipn($order_id)
        {
            $error = 0;

            $ipn = new PaypalIPN();
            // Use the sandbox endpoint during testing.
            if ($this->use_sandbox) {
                $ipn->useSandbox();
            }
            try {
                $verified = $ipn->verifyIPN();
            } catch (Exception $e) {
                $this->log_custom_error($e->getMessage());
                //$this->write_log_to_file($e->getMessage());
                exit;
            }

            if ($verified) {
                $currency = get_directorist_option('payment_currency', get_directorist_option('g_currency', 'USD'));
                $transaction_id = get_post_meta($order_id, '_transaction_id', true);
                $amount = get_post_meta($order_id, '_amount', true);
                $business = get_directorist_option('paypal_gateway_email');

                if ($_POST['receiver_email'] != $business) {
                    $this->log_custom_error('Receiver Business Email mismatch : ' . $_POST['receiver_email']);
                    //$this->write_log_to_file('Receiver Business Email mismatch : ' . $_POST['receiver_email']);

                    $error++;
                }

                if ($_POST['mc_gross'] != $amount) {
                    $this->log_custom_error('Amount mismatch : ' . $_POST['mc_gross']);
                    //$this->write_log_to_file('Amount mismatch : ' . $_POST['mc_gross']);
                    $error++;
                }

                if ($_POST['mc_currency'] != $currency) {
                    $this->log_custom_error('Payment Currency mismatch : ' . $_POST['mc_currency']);
                    //$this->write_log_to_file('Payment Currency mismatch : ' . $_POST['mc_currency']);
                    $error++;
                }

                if ($_POST['txn_id'] == $transaction_id) {
                    $this->log_custom_error('Duplicate Transaction ID : ' . $_POST['txn_id']);
                    //$this->write_log_to_file('Duplicate Transaction : '.$_POST['txn_id']);
                    $error++;
                }

                if (!$error) {

                    $status = strtolower($_POST['payment_status']); // payment_status sent by paypal IPN
                    if ('completed' == $status || ($this->use_sandbox && 'pending' == $status)) {

                        $this->complete_order(
                            array(
                                'ID' => $order_id,
                                'transaction_id' => $_POST['txn_id'],
                            )
                        );
                    }

                }

            } else {
                $this->log_custom_error('IPN Verification Failed.');
                //$this->write_log_to_file('verification failed');

            }
            // Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
            header("HTTP/1.1 200 OK");

        }


        /**
         * It completes order
         * @param $order_data
         * @since 1.0.0
         * @todo; think if it is better to move this to Order Class later
         */
        private function complete_order($order_data)
        {
            // add payment status, tnx_id etc.
            update_post_meta($order_data['ID'], '_payment_status', 'completed');
            update_post_meta($order_data['ID'], '_transaction_id', $order_data['transaction_id']);
            // If the order has featured, make the related listing featured.
            $featured = get_post_meta($order_data['ID'], '_featured', true);
            // use given listing id or fetch the ID
            $listing_id = !empty($order_data['listing_id']) ? $order_data['listing_id'] : get_post_meta($order_data['ID'], '_listing_id', true);

            if (!empty($featured)) {
                update_post_meta($listing_id, '_featured', 1);
            }
            if (get_post_status($listing_id) != 'publish') {
                if (is_fee_manager_active()):
                    $user_id = get_current_user_id();
                    $plan_id = get_post_meta($listing_id, '_fm_plans', true);
                    $package_length = get_post_meta($plan_id, 'fm_length', true);
                    $fm_length_unl = get_post_meta($plan_id, 'fm_length_unl', true);
                    $package_length = $package_length ? $package_length : '1';
                    atpp_need_listing_to_refresh($user_id, 'completed', $plan_id);
                    //if plan has
                    // Current time
                    $current_d = current_time('mysql');
                    // Calculate new date
                    $date = new DateTime($current_d);
                    $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                    $expired_date = $date->format('Y-m-d H:i:s');
                    // is it renewal order? yes, lets update the listing according to plan
                    $is_renewal = get_post_meta($listing_id, '_renew_with_plan', true);
                    $new_l_status = get_directorist_option('new_listing_status', 'pending');
                    //order comes from renewal so update the listing if payment is completed
                    if (!empty($is_renewal)) {
                        $time = current_time('mysql');
                        $post_array = array(
                            'ID' => $listing_id,
                            'post_status' => 'publish',
                            'post_date' => $time,
                            'post_date_gmt' => get_gmt_from_date($time)
                        );
                        //Updating listing
                        wp_update_post($post_array);

                        // Update the post_meta into the database && update related post metas
                        if (!empty($fm_length_unl)) {
                            update_post_meta($listing_id, '_never_expire', 1);
                        } else {

                            update_post_meta($listing_id, '_expiry_date', $expired_date);
                        }
                        update_post_meta($listing_id, '_listing_status', 'post_status');
                    } else {
                        $my_post = array();
                        $my_post['ID'] = $listing_id;
                        $my_post['post_status'] = $new_l_status;
                        wp_update_post($my_post);
                    }
                endif;

            }

            // Order has been completed. Let's fire a hook for a developer to extend if they wish
            do_action('atbdp_order_completed', $order_data['ID'], $listing_id);
        }

        /**
         * Parse request to process Paypal IPN.
         *
         * @since    1.0.0
         * @access   public
         *
         * @param     WP_Query $query WordPress Query object.
         */
        public function parse_request($query)
        {

            if (array_key_exists('atbdp_action', $query->query_vars) && 'paypal-ipn' == $query->query_vars['atbdp_action'] && array_key_exists('atbdp_order_id', $query->query_vars)) {
                $this->process_paypal_ipn($query->query_vars['atbdp_order_id']);
                exit();

            }
        }

        /**
         * It logs error message in the sandbox mode
         * @param $message
         * @since 1.0.0
         */
        private function log_custom_error($message)
        {
            if ($this->use_sandbox) error_log($message);
        }

        /**
         * It logs error message in a local file
         * @param $message
         * @since 1.0.0
         */
        private function write_log_to_file($message)
        {
            file_put_contents(dirname(__FILE__) . '/error_log.txt', $message, FILE_APPEND);
        }


    }

endif;


/**
 * The main function for that returns Directorist_Paypal_Gateway
 * @return Directorist_Paypal_Gateway
 */
function Directorist_Paypal()
{
    return Directorist_Paypal_Gateway::instance();
}

// Instantiate Directorist Paypal gateway only if our directorist plugin is active
if (in_array('directorist/directorist-base.php', (array)get_option('active_plugins'))) {
    Directorist_Paypal();
}