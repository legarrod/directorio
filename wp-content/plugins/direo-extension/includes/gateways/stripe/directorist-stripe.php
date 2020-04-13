<?php

// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

if (!class_exists('Directorist_Stripe_Gateway')):
    final class Directorist_Stripe_Gateway
    {
        /**
         * @var Directorist_Stripe_Gateway The one true Directorist_Stripe_Gateway
         * @since 1.0.0
         */
        private static $instance;

        /**
         * If true, the stripe test keys are used. and otherwise stripe live keys are used. Default false.
         *
         * @since    1.0.0
         * @access   private
         * @var      bool
         */
        private $use_sandbox = false;

        /**
         * Main Directorist_Stripe_Gateway Instance.
         *
         * Insures that only one instance of Directorist_Stripe_Gateway exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         * @since 1.0.0
         * @return Directorist_Stripe_Gateway
         */
        public static function instance()
        {
            // if no object is created, then create it and return it. Else return the old object of our class
            if (!isset(self::$instance) && !(self::$instance instanceof self)) {
                self::$instance = new self; // create an instance of Directorist_Stripe_Gateway
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->use_sandbox = get_directorist_option('gateway_test_mode', true); //@todo; is it good to make the sandbox var private???
                // enable translation
                //add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
                // add new settings
                add_filter('atbdp_monetization_settings_submenus', array(self::$instance, 'stripe_gateway_settings_submenu'), 11, 1);
                // Add stripe gateway to the active gateway & default gateways selections
                add_filter('atbdp_active_gateways', array(self::$instance, 'default_active_gateways'));
                add_filter('atbdp_default_gateways', array(self::$instance, 'default_active_gateways'));
                // Process payment
                add_action('atbdp_process_stripe_gateway_payment', array(self::$instance, 'process_payment'),11, 1);
                // register our scripts and styles
                add_action('wp_enqueue_scripts', array(self::$instance, 'register_styles_scripts'));
                // enqueue scripts only on our checkout form
                add_action('atbdp_before_checkout_form_start', array(self::$instance, 'enqueue_styles_scripts'));
                // out put the cc form
                add_action('wp_ajax_atbdp_stripe_payment_success', array(self::$instance, 'atbdp_stripe_payment_success'));
                add_action('wp_ajax_nopriv_atbdp_stripe_payment_success', array(self::$instance, 'atbdp_stripe_payment_success'));

                add_action('wp_ajax_atbdp_stripe_payment_process', array(self::$instance, 'atbdp_stripe_payment_process'));
                add_action('wp_ajax_nopriv_atbdp_stripe_payment_process', array(self::$instance, 'atbdp_stripe_payment_process'));

            }
            return self::$instance;
        }



        public function atbdp_stripe_payment_process(){
            // payment succeeded, redirect to success page and create order
            $token_id = isset($_POST['token_id']) ? $_POST['token_id']:'';
            $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id']:'';
            $transaction_id = isset($_POST['tns_id'])?$_POST['tns_id']:'';
            $currency = get_directorist_option('payment_currency', get_directorist_option('g_currency', 'usd'));
            $tex_rate = get_directorist_option('tex_rate');
            $listing_id = get_post_meta($order_id, '_listing_id', true);
            $plan = get_post_meta($listing_id, '_fm_plans', true);
            $recurrence_period_term = get_post_meta($plan, '_recurrence_period_term', true);
            $recurrence_time = get_post_meta($plan, '_recurrence_time', true);
            $plan_name = get_the_title($plan);
            $amount = get_post_meta($order_id, '_amount', true);
            $email = isset($_POST['email']) ? $_POST['email']:'';
            if ( $this->use_sandbox ) {
                $apiKey = get_directorist_option('stripe_test_sk');
            }else{
                $apiKey = get_directorist_option('stripe_live_sk');
            }
            if (!class_exists('Stripe\Stripe')){
                require_once DT_STRIPE_LIB_DIR . 'init.php';
            }
            //create customer
            \Stripe\Stripe::setApiKey($apiKey);
           $customer =  \Stripe\Customer::create([
                'email' => $email,
                'source' => $token_id,
            ]);

           // create pricing plan
            \Stripe\Stripe::setApiKey($apiKey);
           $pricing_plan =  \Stripe\Plan::create([
                "amount" => $amount*100,
                "interval" => $recurrence_period_term,
                "interval_count" => $recurrence_time,
                "product" => [
                    "name" => $plan_name
                ],
                "currency" => $currency,
            ]);
           // create tax or VAT
               \Stripe\Stripe::setApiKey($apiKey);
               $tax_rate = \Stripe\TaxRate::create([
                   'display_name' => 'VAT',
                   'percentage' => $tex_rate,
                   'inclusive' => false,
               ]);

            // finally create subscription
            \Stripe\Stripe::setApiKey($apiKey);
            $subscription = \Stripe\Subscription::create([
                'customer' => $customer->id,
                'items' => [
                    [
                        'plan' => $pricing_plan->id,
                    ],
                    ],
                    'default_tax_rates' => [
                    [
                        $tax_rate->id
                    ],
                ],
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            if ($subscription){
                $this->complete_order(
                    array(
                        'ID' => $order_id,
                        'transaction_id' => $transaction_id,
                    )
                );
                $result = array('result'=> true);
                wp_send_json_success( $result );
            }else{
                wp_send_json_error( $subscription );
            }
        }

        /**
         * It process the payment of the given order
         * @param int $order_id
         * @since 1.0.0
         */
        public function process_payment($order_id)
        {
            wp_enqueue_script('directorist-stripe-js');
            wp_enqueue_script('stripe-js-v3');
            wp_enqueue_style('dt-stripe-style');

            $amount     = get_post_meta( $order_id, '_amount', true );
            $currency = get_directorist_option( 'payment_currency', get_directorist_option('g_currency', 'usd') );
            // get proper secret & publishable key based on the environment
            $listing_id = get_post_meta($order_id, '_listing_id', true);
            $recurring = false;
            if (class_exists('ATBDP_Pricing_Plans')) {
                $plan = get_post_meta($listing_id, '_fm_plans', true);
                $is_recurring = get_post_meta($plan, '_atpp_recurring', true);
                if (!empty($is_recurring)) {
                    $recurring = true;
                }
            }
            if ( $this->use_sandbox ) {
                $pk = get_directorist_option('stripe_test_pk');
                $apiKey = get_directorist_option('stripe_test_sk');
            }else{
                $pk = get_directorist_option('stripe_live_pk');
                $apiKey = get_directorist_option('stripe_live_sk');
            }
            if (!class_exists('Stripe\Stripe')){
                require_once DT_STRIPE_LIB_DIR . 'init.php';
            }
            \Stripe\Stripe::setApiKey($apiKey);
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'description' => $order_id,
            ]);

            $data = array(
                'redirect_url' => ATBDP_Permalink::get_payment_receipt_page_link( $order_id ),
                'payment_fail' => ATBDP_Permalink::get_transaction_failure_page_link(),
                'publish_key' => $pk,
                'order_id' => $order_id,
                'recurring' => $recurring,
                'ajax_url' =>  admin_url('admin-ajax.php'),
            );
            wp_localize_script('directorist-stripe-js', 'atbdp_paMentObj', $data);
            ?>
            <div class="atds_stripe_wrapper">
                <form action="#" id="payment-form">
                    <div class="form-group-wrapper">
                        <input id="email" name="email" type="email" placeholder="<?php echo __('Email', 'direo-extension'); ?>" required>
                        <!-- placeholder for Elements -->
                        <div id="card-element"></div>
                    </div>
                    <div class="text-center">
                        <button id="card-button" class="atds_stripe_btn"
                                data-secret="<?= $intent->client_secret ?>">
                            <?php echo __('Pay Now', 'direo-extension'); ?>
                        </button>
                    </div>
                </form>
            </div>
            <?php
        }

        public function atbdp_stripe_payment_success(){
            // payment succeeded, redirect to success page and create order
            $order_id = isset($_POST['order_id'])?(int)$_POST['order_id']:'';
            $transaction_id = isset($_POST['tns_id'])?$_POST['tns_id']:'';
                $this->complete_order(
                    array(
                        'ID' => $order_id,
                        'transaction_id' => $transaction_id,
                    )
                );
                $result = array('result'=> true);
//                return json_encode($result);
                wp_send_json_success( $result );
        }

        /**
         * Setup Directorist Stripe's plugin constants.
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
            require_once plugin_dir_path(__FILE__) . 'helper.php';
        }

        /**
         * It loads plugin text domain
         * @since 1.0.0
         */
        public function load_textdomain()
        {
            load_plugin_textdomain('direo-extension', false, DT_STRIPE_LANG_DIR);
        }

        /**
         * It adds our gateways to the active and default gateways list
         * @param array $gateways Arrays of all old gateways
         * @since 1.0.0
         * @return array It returns the new gateways list after adding stripe gateways
         */
        public function default_active_gateways($gateways)
        {
            $gateways[] = array(
                'value' => 'stripe_gateway',
                'label' => __('Stripe', 'direo-extension'),
            );
            return $gateways;
        }


        /**
         * It adds a submenu of stripe gateway settings
         * @param array $submenus
         * @since 1.0.0
         * @return array
         */
        public function stripe_gateway_settings_submenu($submenus)
        {
            $submenus['stripe_gateway_submenu'] = array(
                'title' => __('Stripe Gateway', 'direo-extension'),
                'name' => 'stripe_gateway_menu',
                'icon' => 'font-awesome:fa-cc-stripe',
                'controls' => apply_filters('atbdp_stripe_gateway_settings_controls', array(
                    'gateways' => array(
                        'type' => 'section',
                        'title' => __('Stripe Gateway Settings', 'direo-extension'),
                        'description' => __('You can customize all the settings related to your stripe gateway. After switching any option, Do not forget to save the changes.', 'direo-extension'),
                        'fields' => $this->get_stripe_gateway_settings_fields(),
                    ),
                )),
            );
            return $submenus;
        }


        /**
         * It register the settings fields of stripe gateway
         * @since 1.0.0
         * @return array It returns an array of stripe settings fields array
         */
        public function get_stripe_gateway_settings_fields()
        {
            $gsp = sprintf("<a target='_blank' href='%s'>%s</a>", esc_url(admin_url('edit.php?post_type=at_biz_dir&page=aazztech_settings#_gateway_general')), __('Gateway Settings Page', 'direo-extension'));
            $stripe_url = sprintf("<a target='_blank' href='%s'>%s</a>", esc_url("https://dashboard.stripe.com/account/apikeys"), __('Get your Stripe API keys', 'direo-extension'));

            return apply_filters('atbdp_stripe_gateway_settings_fields', array(
                    array(
                        'type' => 'notebox',
                        'name' => 'stripe_gateway_note',
                        'label' => __('Note About Stripe Gateway:', 'direo-extension'),
                        'description' => sprintf(__('If you want to use Stripe for a testing purpose, you should set Test MODE to Yes on The %s.', 'direo-extension'), $gsp),
                        'status' => 'info',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'stripe_gateway_title',
                        'label' => __('Gateway Title', 'direo-extension'),
                        'description' => __('Enter the title of this gateway that should be displayed to the user on the front end.', 'direo-extension'),
                        'default' => esc_html__('Stripe', 'direo-extension'),
                    ),
                    array(
                        'type' => 'textarea',
                        'name' => 'stripe_gateway_description',
                        'label' => __('Gateway Description', 'direo-extension'),
                        'description' => __('Enter some description for your user to make payment using stripe.', 'direo-extension'),
                        'default' => __('You can make payment using your credit card using stripe if you choose this payment gateway.', 'direo-extension')
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'stripe_live_pk',
                        'label' => __('Live Publishable Key', 'direo-extension'),
                        'description' => sprintf(__('Enter your Stripe Live Publishable Key Here. You can find your API key on your Stripe Dashboard Under Developers > API section. %s', 'direo-extension'), $stripe_url),
                        'default' => '',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'stripe_live_sk',
                        'label' => __('Live Secret Key', 'direo-extension'),
                        'description' => sprintf(__('Enter your Stripe Live Secret Key Here. You can find your API key on your Stripe Dashboard Under Developers > API section. %s', 'direo-extension'), $stripe_url),
                        'default' => '',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'stripe_test_pk',
                        'label' => __('Test Publishable Key', 'direo-extension'),
                        'description' => sprintf(__('Enter your Stripe Test Publishable Key Here. You can find your API key on your Stripe Dashboard Under Developers > API section. %s', 'direo-extension'), $stripe_url),
                        'default' => '',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'stripe_test_sk',
                        'label' => __('Test Secret Key', 'direo-extension'),
                        'description' => sprintf(__('Enter your Stripe Test Secret Key Here. You can find your API key on your Stripe Dashboard Under Developers > API section. %s', 'direo-extension'), $stripe_url),
                        'default' => '',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'tex_rate',
                        'label' => __('Default Vat Rate', 'direo-extension'),
                        'min' => '0',
                        'max' => '100',
                        'step' => '.5',
                        'default' => '0',
                    ),
                )
            );
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
            $new_l_status = get_directorist_option('new_listing_status', 'pending');

            if (!empty($featured)) {
                update_post_meta($listing_id, '_featured', 1);
            }
            if (get_post_status($listing_id) != 'publish') {
                $plan_id = get_post_meta($listing_id, '_fm_plans', true);
                $package_length = get_post_meta($plan_id, 'fm_length', true);
                $fm_length_unl = get_post_meta($plan_id, 'fm_length_unl', true);
                $package_length = $package_length ? $package_length : '1';
                // Current time
                $current_d = current_time('mysql');
                // Calculate new date
                $date = new DateTime($current_d);
                $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                $expired_date = $date->format('Y-m-d H:i:s');
                // is it renewal order? yes, lets update the listing according to plan
                $is_renewal = get_post_meta($listing_id, '_renew_with_plan', true);
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

            }
            // Order has been completed. Let's fire a hook for a developer to extend if they wish
            do_action('atbdp_order_completed', $order_data['ID'], $listing_id);
        }


        /**
         * It registers scripts and styles needed for directorist stripe extension
         */
        public function register_styles_scripts()
        {
            wp_register_script('stripe-js-v3', esc_url('https://js.stripe.com/v3/'), null, DT_STRIPE_VERSION, true);
            wp_register_script('directorist-stripe-js', DT_STRIPE_URL . 'assets/js/directorist-stripe.js', array('jquery', 'stripe-js-v3'), DT_STRIPE_VERSION, true);
            wp_register_script('directorist-stripe-common-js', DT_STRIPE_URL . 'assets/js/common.js', array('jquery', 'stripe-js-v3'), DT_STRIPE_VERSION, true);

            wp_register_style('dt-stripe-style', DT_STRIPE_URL . 'assets/css/directorist-stripe.css', null, DT_STRIPE_VERSION);
        }

        /**
         * It enqueues our scripts and styles
         */
        public function enqueue_styles_scripts()
        {
            wp_enqueue_style('dt-stripe-style');
            wp_enqueue_script('directorist-stripe-common-js');
            if ( $this->use_sandbox ) {
                $pk = get_directorist_option('stripe_test_pk');
            }else{
                $pk = get_directorist_option('stripe_live_pk');
            }
            wp_localize_script('directorist-stripe-common-js', 'atbdp_commonObj', array('publish_key'=>$pk));
        }

    }

endif;

/**
 * The main function for that returns Directorist_Stripe_Gateway
 * @return Directorist_Stripe_Gateway
 */
function Directorist_Stripe()
{
    return Directorist_Stripe_Gateway::instance();
}

// Instantiate Directorist Stripe gateway only if our directorist plugin is active
if (in_array('directorist/directorist-base.php', (array)get_option('active_plugins'))) {
    Directorist_Stripe();
}