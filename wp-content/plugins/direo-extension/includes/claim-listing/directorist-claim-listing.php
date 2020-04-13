<?php

// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

final class DCL_Base
{

    /** Singleton *************************************************************/

    /**
     * @var DCL_Base The one true DCL_Base
     * @since 1.0
     */
    private static $instance;

    private static $plan_id;

    /**
     * Main DCL_Base Instance.
     *
     * Insures that only one instance of DCL_Base exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @return object|DCL_Base The one true DCL_Base
     * @uses DCL_Base::setup_constants() Setup the constants needed.
     * @uses DCL_Base::includes() Include the required files.
     * @uses DCL_Base::load_textdomain() load the language files.
     * @see  DCL_Base()
     * @since 1.0
     * @static
     * @static_var array $instance
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof DCL_Base)) {
            self::$instance = new DCL_Base;
            self::$instance->setup_constants();

           // add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));

            self::$instance->includes();
            new DCL_Enqueuer();// enqueue required styles and scripts

            // Add Settings fields to the extension general fields
            add_filter('atbdp_extension_settings_fields', array(self::$instance, 'add_settings_for_claim_listing'));
            add_filter('atbdp_default_notifiable_events', array(self::$instance, 'dcl_claim_notification'));
            add_filter('atbdp_default_events_to_notify_admin', array(self::$instance, 'dcl_default_events_to_notify_admin'));
            add_filter('atbdp_default_events_to_notify_user', array(self::$instance, 'dcl_default_events_to_notify_user'));
            add_filter('atbdp_only_user_notifiable_events', array(self::$instance, 'dcl_claim_confirmation_notification'));
            add_filter('atbdp_email_templates_settings_controls', array(self::$instance, 'dcl_email_templates'));
            //add_action('admin_notices', array(self::$instance, 'directorist_add_plan_pages'));
            add_action('admin_enqueue_scripts', array(self::$instance, 'register_necessary_scripts_front'));
            add_action('wp_enqueue_scripts', array(self::$instance, 'register_necessary_scripts_front'));
            add_action('init', array(self::$instance, 'register_claim_listing_post_type'));
            add_action('add_meta_boxes', array(self::$instance, 'atpp_add_meta_boxes'));
            add_action('save_post', array(self::$instance, 'dcl_save_meta_data'));
            add_filter('manage_dcl_claim_listing_posts_columns', array(self::$instance, 'atpp_add_new_plan_columns'));
            add_action('manage_dcl_claim_listing_posts_custom_column', array(self::$instance, 'atpp_custom_field_column_content'), 10, 2);
            add_filter('post_row_actions', array(self::$instance, 'atpp_remove_row_actions_for_quick_view'), 10, 2);
            add_action('atbdp_after_video_metabox_backend_add_listing', array(self::$instance, 'dcl_admin_metabox'));
            add_action('save_post', array(self::$instance, 'dcl_save_metabox'), 10, 2);
            add_action('atbdp_single_listing_after_title', array(self::$instance, 'verified_bedge_in_single_listing'));
            add_filter('atbdp_extension_settings_submenus', array(self::$instance, 'dcl_settings_to_ext_general_fields'));
            //register Claim widget
            add_action('widgets_init', array(self::$instance, 'register_widget'));
            add_action('atbdp_after_contact_listing_owner_section', array(self::$instance, 'dcl_listing_claim_now_button'));
            /*Submit claim*/
            add_action('wp_ajax_dcl_submit_claim', array(self::$instance, 'dcl_submit_claim'));
            add_action('wp_ajax_nopriv_dcl_submit_claim', array(self::$instance, 'dcl_submit_claim'));
            add_action('wp_ajax_dcl_plan_allowances', array(self::$instance, 'dcl_plan_allowances'));

            //stuff for individual price set for claimer
            if (!class_exists('ATBDP_Pricing_Plans' || 'DWPP_Pricing_Plans')) {
                add_filter('atbdp_checkout_form_data', array(self::$instance, 'dcl_checkout_form_data'), 10, 2);
                add_filter('atbdp_payment_receipt_data', array(self::$instance, 'dcl_payment_receipt_data'), 11, 3);
                add_filter('atbdp_order_details', array(self::$instance, 'dcl_order_details'), 10, 3);
                add_filter('atbdp_order_items', array(self::$instance, 'dcl_order_items'), 10, 4);
            }


        }


        return self::$instance;
    }


    private function __construct()
    {
        /*making it private prevents constructing the object*/
    }

    /**
     * Throw error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @return void
     * @since 1.0
     * @access protected
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
    }

    /**
     * Disable unserializing of the class.
     *
     * @return void
     * @since 1.0
     * @access protected
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
    }

    /**
     * @since 1.0.5
     */
    public function dcl_listing_claim_now_button()
    {

        if (!get_directorist_option('non_widger_claim_button', 0)) return;
        $listing_id = get_the_ID();
        if (!get_directorist_option('enable_claim_listing', 1)) return; // vail if the business hour is not enabled
        $claim_header = get_directorist_option('claim_widget_title', esc_html__('Is this your business?', 'direo-extension'));
        $claim_description = get_directorist_option('claim_widget_description', esc_html__('Claim listing is the best way to manage and protect your business.', 'direo-extension'));
        $claim_now = get_directorist_option('claim_now', esc_html__('Claim Now!', 'direo-extension'));
        $claimed_by_admin = get_post_meta($listing_id, '_claimed_by_admin', true);
        $claim_fee = get_post_meta($listing_id, '_claim_fee', true);
        if ($claimed_by_admin || ('claim_approved' === $claim_fee)) return;
        ?>
        <div class="directorist">
            <?php if (is_user_logged_in()) { ?>
                <div class="atbd_content_module dcl_promo-item_group">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4><span class="<?php atbdp_icon_type(true); ?>
-edit"></span><?php _e(' Claim', 'direo-extension') ?></h4>
                        </div>
                    </div>
                    <div class="atbdb_content_module_contents">
                        <h4 class="dcl_promo-item_title"><?php _e("$claim_header", 'direo-extension') ?></h4>
                        <p class="dcl_promo-item_description"><?php _e("$claim_description", 'direo-extension') ?></p>
                        <a href="" data-target="dcl-claim-modal"
                           class="<?= atbdp_directorist_button_classes(); ?>"><?php _e("$claim_now", 'direo-extension') ?></a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="atbd_content_module dcl_promo-item_group">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="<?php atbdp_icon_type(true); ?>-edit"></span><?php _e(" $claim_now", 'direo-extension') ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <h4 class="dcl_promo-item_title"><?php _e("$claim_header", 'direo-extension') ?></h4>
                        <p class="dcl_promo-item_description"><?php _e("$claim_description", 'direo-extension') ?></p>
                        <a href=""
                           class="dcl_login_alert <?= atbdp_directorist_button_classes(); ?>"><?php _e("$claim_now", 'direo-extension') ?></a>
                        <div class="dcl_login_notice atbd_notice alert alert-info" role="alert">
                            <span class="fa fa-info-circle" aria-hidden="true"></span>
                            <?php
                            // get the custom registration page id from the db and create a permalink
                            $reg_link_custom = ATBDP_Permalink::get_registration_page_link();
                            //if we have custom registration page, use it, else use the default registration url.
                            $reg_link = !empty($reg_link_custom) ? $reg_link_custom : wp_registration_url();

                            $login_url = '<a href="' . ATBDP_Permalink::get_login_page_link() . '">' . __('Login', 'direo-extension') . '</a>';
                            $register_url = '<a href="' . esc_url($reg_link) . '">' . __('Register', 'direo-extension') . '</a>';

                            printf(__('You need to %s or %s to claim this listing', 'direo-extension'), $login_url, $register_url);
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <input type="hidden" id="dcl-post-id" value="<?php echo get_the_ID(); ?>"/>
        </div>
        <div class="at-modal atm-fade" id="dcl-claim-modal">
            <div class="at-modal-content at-modal-lg">
                <div class="atm-contents-inner">
                    <a href="" class="at-modal-close"><span aria-hidden="true">&times;</span></a>
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <form id="dcl-claim-listing-form" class="form-vertical" role="form">
                                <div class="modal-header">
                                    <h3 class="modal-title"
                                        id="dcl-claim-label"><?php _e('Claim This Listing', 'direo-extension'); ?></h3>

                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="dcl_claimer_name"><?php _e('Full Name', 'direo-extension'); ?>
                                            <span class="atbdp_make_str_red">*</span></label>
                                        <input type="text" class="form-control" id="dcl_claimer_name"
                                               placeholder="<?php _e('Full Name', 'direo-extension'); ?>"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label for="dcl_claimer_phone"><?php _e('Phone', 'direo-extension'); ?>
                                            <span class="atbdp_make_str_red">*</span></label>
                                        <input type="tel" class="form-control" id="dcl_claimer_phone"
                                               placeholder="<?php _e('111-111-235', 'direo-extension'); ?>"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label for="dcl_claimer_details"><?php _e('Verification Details', 'direo-extension'); ?>
                                            <span class="atbdp_make_str_red">*</span></label>
                                        <textarea class="form-control" id="dcl_claimer_details"
                                                  rows="3"
                                                  placeholder="<?php _e('Details description about your business', 'direo-extension'); ?>..."
                                                  required></textarea>
                                    </div>
                                    <div class="form-group dcl_pricing_plan">
                                        <?php
                                        $claim_charge_by = get_directorist_option('claim_charge_by');
                                        $charged_by = get_post_meta($listing_id, '_claim_fee', true);
                                        $charged_by = ($charged_by !== '') ? $charged_by : $claim_charge_by;
                                        $has_plans = is_pricing_plans_active();
                                        if (!empty($has_plans) && ('pricing_plan' === $charged_by)) {
                                            if (class_exists('ATBDP_Pricing_Plans')) {
                                                $args = array(
                                                    'post_type' => 'atbdp_pricing_plans',
                                                    'posts_per_page' => -1,
                                                    'status' => 'publish',
                                                    'meta_query' => array(
                                                        'relation' => 'OR',
                                                        array(
                                                            'key' => '_hide_from_plans',
                                                            'compare' => 'NOT EXISTS',
                                                        ),
                                                        array(
                                                            'key' => '_hide_from_plans',
                                                            'value' => 1,
                                                            'compare' => '!=',
                                                        ),
                                                    ),
                                                );

                                                $atbdp_query = new WP_Query($args);

                                                if ($atbdp_query->have_posts()) {
                                                    global $post;

                                                    $plans = $atbdp_query->posts;
                                                    printf('<label for="select_plans">%s</label>', __('Select Plan', 'direo-extension'));
                                                    printf('<select id="claimer_plan">');
                                                    printf('<option>%s</option>', __('- Select Plan -', 'direo-extension'));
                                                    foreach ($plans as $key => $value) {
                                                        $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $value->ID);
                                                        $plan_type = get_post_meta($value->ID, 'plan_type', true);
                                                        printf('<option %s value="%s">%s %s</option>', (!empty($active_plan) && ('package' === $plan_type)) ? 'class="dcl_active_plan"' : '', $value->ID, $value->post_title, !empty($active_plan) && ('package' === $plan_type) ? '<span class="atbd_badge">' . __('- Active', 'direo-extension') . '</span>' : '');
                                                    }
                                                    printf('</select>');

                                                    ?>
                                                    <div id="dcl-plan-allowances"
                                                         data-author_id="<?php echo get_current_user_id(); ?>">
                                                        <?php
                                                        do_action('wp_ajax_dcl_plan_allowances', $listing_id); ?>
                                                    </div>
                                                    <?php

                                                    printf('<a target="_blank" href="%s" class="dcl_plans">%s</a>', esc_url(ATBDP_Permalink::get_fee_plan_page_link()), __('Show plan details', 'direo-extension'));
                                                }
                                            } else {
                                                global $product;
                                                $query_args = array(
                                                    'post_type' => 'product',
                                                    'tax_query' => array(
                                                        array(
                                                            'taxonomy' => 'product_type',
                                                            'field' => 'slug',
                                                            'terms' => 'listing_pricing_plans',
                                                        ),
                                                    ),
                                                    'meta_query' => array(
                                                        'relation' => 'OR',
                                                        array(
                                                            'key' => '_hide_from_plans',
                                                            'compare' => 'NOT EXISTS',
                                                        ),
                                                        array(
                                                            'key' => '_hide_from_plans',
                                                            'value' => 1,
                                                            'compare' => '!=',
                                                        ),
                                                    ),
                                                );

                                                $atbdp_query = new WP_Query($query_args);

                                                if ($atbdp_query->have_posts()) {
                                                    global $post;
                                                    $plans = $atbdp_query->posts;
                                                    printf('<label for="select_plans">%s</label>', __('Select Plan', 'direo-extension'));
                                                    printf('<select id="claimer_plan">');
                                                    printf('<option>%s</option>', __('- Select Plan -', 'direo-extension'));
                                                    foreach ($plans as $key => $value) {
                                                        $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $value->ID);
                                                        $plan_type = get_post_meta($value->ID, 'plan_type', true);
                                                        printf('<option %s value="%s">%s %s</option>', (!empty($active_plan) && ('package' === $plan_type)) ? 'class="dcl_active_plan"' : '', $value->ID, $value->post_title, !empty($active_plan) && ('package' === $plan_type) ? '<span class="atbd_badge">' . __('- Active', 'direo-extension') . '</span>' : '');
                                                    }
                                                    printf('</select>');
                                                    ?>
                                                    <div id="dcl-plan-allowances"
                                                         data-author_id="<?php echo get_current_user_id(); ?>">
                                                        <?php
                                                        do_action('wp_ajax_dcl_plan_allowances', $listing_id); ?>
                                                    </div>
                                                    <?php
                                                    printf('<a target="_blank" href="%s">%s</a>', esc_url(ATBDP_Permalink::get_fee_plan_page_link()), __(' Show plan details', 'direo-extension'));
                                                }
                                            }

                                        }
                                        ?>

                                    </div>
                                    <div id="dcl-claim-submit-notification"></div>
                                    <div id="dcl-claim-warning-notification"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit"
                                            class="btn btn-primary"><?php esc_html_e('Submit', 'direo-extension'); ?></button>
                                    <span><i class="<?php atbdp_icon_type(true); ?>-lock"></i><?php esc_html_e('Secure Claim Process', 'direo-extension'); ?></span>
                                </div>
                            </form>
                        </div><!-- ends: .col-lg-125 -->
                    </div>
                </div>
            </div>
        </div>
        <?php
    }


    /**
     * @since 1.2
     */
    public function dcl_plan_allowances($listing_id = 0)
    {
        $plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : '';

        ob_start();
        $user_id = get_current_user_id();
        $subscribed_package_id = $plan_id;
        $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $subscribed_package_id);
        $order_id = !empty($active_plan) ? (int)$active_plan[0]->ID : '';
        $user_featured_listing = listings_data_with_plan($user_id, '1', $subscribed_package_id, $order_id);
        $user_regular_listing = listings_data_with_plan($user_id, '0', $subscribed_package_id, $order_id);
        $num_regular = get_post_meta($subscribed_package_id, 'num_regular', true);
        $num_featured = get_post_meta($subscribed_package_id, 'num_featured', true);
        $num_featured_unl = get_post_meta($subscribed_package_id, 'num_featured_unl', true);
        $num_regular_unl = get_post_meta($subscribed_package_id, 'num_regular_unl', true);
        $total_regular_listing = $num_regular;
        $total_featured_listing = $num_featured;
        //get the active package info
        if ($active_plan) {
            $plan_type = package_or_PPL($subscribed_package_id);
            $listing_id = get_post_meta($active_plan[0]->ID, '_listing_id', true);
            $featured = get_post_meta($listing_id, '_featured', true);
            $total_regular_listing = $num_regular - ('0' === $featured ? $user_regular_listing + 1 : $user_regular_listing);
            $total_featured_listing = $num_featured - ('1' === $featured ? $user_featured_listing + 1 : $user_featured_listing);

            if ((0 === $total_featured_listing) && (0 === $total_regular_listing)) {
                if (('pay_per_listng' != $plan_type)) {
                    update_post_meta($order_id, '_payment_status', 'cancelled');
                }
            }
        }
        ?>
        <div class="atbd_listing_type">
            <?php

            $listing_type = !empty($listing_info['listing_type']) ? $listing_info['listing_type'] : '';
            ?>
            <h4><?php _e('Choose listing type', 'direo-extension') ?></h4>

            <?php
            if (!empty($num_regular_unl)) {
                ?>
                <!--end-->
                <label for="regular"><input
                            id="regular"
                            type="radio"
                            name="listing_type" class="listing_types"
                            value="regular"><?php _e(' Regular listing', 'direo-extension') ?><span
                            class="atbdp_make_str_green"><?php _e(" (Unlimited)", 'direo-extension') ?></span></label>
            <?php } else { ?>
                <label for="regular"><input
                            id="regular"
                            type="radio"
                            name="listing_type" value="regular"
                            class="listing_types"><?php _e(' Regular listing', 'direo-extension') ?><span
                            class="<?php echo $total_regular_listing > 0 ? 'atbdp_make_str_green' : 'atbdp_make_str_red' ?>"><?php _e(" ($total_regular_listing Remaining)", 'direo-extension') ?></span></label>
            <?php } ?>

            <?php
            if (!empty($num_featured_unl)) {
                ?>
                <label for="featured"><input id="featured"
                                             class="listing_types"
                                             type="radio"
                                             name="listing_type"
                                             value="featured"><?php _e(' Featured listing', 'direo-extension') ?>
                    <span class="atbdp_make_str_green"><?php _e(" (Unlimited)", 'direo-extension') ?></span></label>
                <?php
            } else { ?>
                <label for="featured"><input id="featured"
                                             class="listing_types"
                                             type="radio"
                                             name="listing_type"
                                             value="featured"><?php _e(' Featured listing', 'direo-extension') ?>
                    <span class="<?php echo $total_featured_listing > 0 ? 'atbdp_make_str_green' : 'atbdp_make_str_red' ?>"><?php _e(" ($total_featured_listing Remaining)", 'direo-extension') ?></span></label><!--end-->
            <?php } ?>

        </div>

        <?php
        $data = ob_get_clean();

        if (package_or_PPL($plan_id) === 'pay_per_listng') {
            echo $data = '';
        } else {
            echo $data;
        }
    }

    /**
     * @since 1.0.0
     */
    public function dcl_settings_to_ext_general_fields($settings_submenus)
    {
        /*lets add a submenu of our extension*/
        $settings_submenus[] = array(
            'title' => __('Claim Listing', 'direo-extension'),
            'name' => 'dcl_claim_listing',
            'icon' => 'font-awesome:fa-check',
            'controls' => array(
                'general_section' => array(
                    'type' => 'section',
                    'title' => __('Claim Settings', 'direo-extension'),
                    'description' => __('You can Customize all the settings of Claim Listing Extension here', 'direo-extension'),
                    'fields' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'claim_widget_title',
                            'label' => __('Claim Widget Title', 'direo-extension'),
                            'default' => __('Is this your business?', 'direo-extension'),
                        ),
                        array(
                            'type' => 'wpeditor',
                            'name' => 'claim_widget_description',
                            'label' => __('Description', 'direo-extension'),
                            'default' => __('Claim listing is the best way to manage and protect your business.', 'direo-extension'),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'claim_charge_by',
                            'label' => __('Method of Charging', 'direo-extension'),
                            'items' => array(
                                array(
                                    'value' => 'pricing_plan',
                                    'label' => __('Pricing Plans', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'free_claim',
                                    'label' => __('Claim for Free', 'direo-extension'),
                                ),
                                array(
                                    'value' => 'static_fee',
                                    'label' => __('Set a Claim Fee', 'direo-extension'),
                                ),
                            ),
                            'default' => array(
                                'value' => 'free_claim',
                                'label' => __('Claim for Free', 'direo-extension'),
                            ),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'claim_listing_price',
                            'label' => __('Claim Fee in ', 'direo-extension') . atbdp_get_payment_currency(),
                            'default' => 19.99,
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'claim_now',
                            'label' => __('Claim Now Button', 'direo-extension'),
                            'default' => __('Claim Now!', 'direo-extension'),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'non_widger_claim_button',
                            'label' => __('Display Claim Now Button on Listing Details', 'direo-extension'),
                            'default' => 0,
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'verified_badge',
                            'label' => __('Display Claimed Badge', 'direo-extension'),
                            'default' => 1,
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'verified_text',
                            'label' => __('Verified Text', 'direo-extension'),
                            'default' => __('Claimed', 'direo-extension'),
                        ),
                    ),// ends fields array
                ), // ends general section array
            ), // ends controls array that holds an array of sections
        );
        return $settings_submenus;
    }


    /**
     * @since 1.0.0
     *
     */
    public function dcl_submit_claim()
    {
        $data = array('error' => 0);
        $listing_id = (int)$_POST["post_id"];
        $manual_charge = need_claim_to_charge_manually();
        $already_claimed = dcl_tract_duplicate_claim(get_current_user_id(), $listing_id);
        $claimer_plan_id = isset($_POST["claimer_plan"]) ? (int)($_POST["claimer_plan"]) : '';
        if (dcl_need_to_charge_with_plan() && dcl_need_to_charge_without_plan()) {
            if (!empty($already_claimed)) {
                $data['duplicate_msg'] = __('Sorry! You have already requested for claim.', 'direo-extension');
            } else {

                if (dcl_claimed_plan_allowances($claimer_plan_id)) {
                    dcl_current_user();
                    // Send a copy to admin( only if applicable ).
                    dcl_email_admin_listing_claim();
                    dcl_new_claim($listing_id);
                    $data['message'] = __('Your claim submitted successfully.', 'direo-extension');
                } else {
                    $data['duplicate_msg'] = __('Sorry! Something wrong with your submission.', 'direo-extension');
                }

            }

        } else {
            if (!empty($already_claimed)) {
                $data['duplicate_msg'] = __('Sorry! You have already requested for claim.', 'direo-extension');
            } else {
                $listing_id = (int)$_POST["post_id"];
                if (class_exists('DWPP_Pricing_Plans') && empty($manual_charge)) {
                    update_post_meta($listing_id, '_claimer_plans', $claimer_plan_id);
                    dcl_current_user();
                    dcl_email_admin_listing_claim();
                    dcl_new_claim($listing_id);
                    global $woocommerce;
                    $woocommerce->cart->empty_cart();
                    $woocommerce->cart->add_to_cart($claimer_plan_id);
                    $url = wc_get_checkout_url();
                } elseif (class_exists('ATBDP_Pricing_Plans') && empty($manual_charge)) {
                    update_post_meta($listing_id, '_claimer_plans', $claimer_plan_id);
                    dcl_current_user();
                    dcl_new_claim($listing_id);
                    $url = ATBDP_Permalink::get_fee_renewal_checkout_page_link($listing_id);
                } else {
                    dcl_current_user();
                    dcl_email_admin_listing_claim();
                    dcl_new_claim($listing_id);
                    $url = ATBDP_Permalink::get_checkout_page_link($listing_id);
                }
                $data['checkout_url'] = $url;
                $data['take_payment'] = 'plan';
            }

        }

        echo wp_json_encode($data);
        wp_die();
    }

    /**
     * @since 1.0.0
     */
    public function register_widget()
    {
        register_widget('DCL_Claim_Now');
    }

    /**
     * @since 1.0.0
     */
    public function verified_bedge_in_single_listing($listing_id)
    {
        if (!get_directorist_option('enable_claim_listing', 1)) return; // vail if the business hour is not enabled
        if (!get_directorist_option('verified_badge', 1)) return; // vail if the business hour is not enabled
        $verified_text = get_directorist_option('verified_text', esc_html__('Claimed', 'direo-extension'));
        $claimed_by_admin = get_post_meta($listing_id, '_claimed_by_admin', true);
        if (!empty($claimed_by_admin)) {
            printf('<div class="dcl_claimed"><div class="dcl_claimed--badge"><span><i class="' . atbdp_icon_type() . '-check"></i></span> %s</div> <span class="dcl_claimed--tooltip">%s</span></div>', $verified_text, __('Verified by it\'s Owner', 'direo-extension'));
        }
    }

    /**
     * @param $post
     * @param $post_id
     * @return $post_id
     * @since 1.0.0
     */
    public function dcl_save_metabox($post_id, $post)
    {

        if (!isset($_POST['post_type'])) {
            return $post_id;
        }

// If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

// Check the logged in user has permission to edit this post
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        if (isset($_POST['dcl_listing_claim_details_nonce'])) {

            // Verify that the nonce is valid
            if (wp_verify_nonce($_POST['dcl_listing_claim_details_nonce'], 'dcl_save_listing_claim_details')) {
                $claim_fee = isset($_POST['claim_fee']) ? esc_attr($_POST['claim_fee']) : '';
                $claimed_by_admin = isset($_POST['claimed_by_admin']) ? esc_attr($_POST['claimed_by_admin']) : '';
                $claim_charge = isset($_POST['claim_charge']) ? (int)$_POST['claim_charge'] : '';
                update_post_meta($post_id, '_claim_fee', $claim_fee);
                update_post_meta($post_id, '_claim_charge', $claim_charge);
                update_post_meta($post_id, '_claimed_by_admin', $claimed_by_admin);
            }
        }

    }

    /**
     * @since 1.0.0
     */

    public function dcl_admin_metabox()
    {
        if (!get_directorist_option('enable_claim_listing', 1)) return; // vail if the business hour is not enabled
        add_meta_box('_listing_claim',
            __('Claim Details', 'direo-extension'),
            array($this, 'dcl_admin_claim'),
            ATBDP_POST_TYPE,
            'side', 'high');
    }

    /**
     * @param $post
     * @since 1.0.0
     */

    public function dcl_admin_claim($post)
    {
        $url = admin_url() . 'edit.php?post_type=at_biz_dir&page=atbdp-extension';
        $current_val = get_post_meta($post->ID, '_claim_fee', true);
        $claim_charge = get_post_meta($post->ID, '_claim_charge', true);
        $claimed_by_admin = get_post_meta($post->ID, '_claimed_by_admin', true);
        // Add a nonce field so we can check for it later
        wp_nonce_field('dcl_save_listing_claim_details', 'dcl_listing_claim_details_nonce');
        ?>
        <div>
            <input id="claimed_by_admin" type="checkbox" name="claimed_by_admin"
                   value="1" <?php checked('1', $claimed_by_admin) ?>>
            <strong><label
                        for="claimed_by_admin"><?php _e('Mark as Claimed', 'direo-extension') ?></label></strong>
        </div>
        <div class="dcl_admin_claim" id="pricing_plans">
            <div>
                <input id="claim_with_pricing" type="radio" name="claim_fee"
                       value="pricing_plan" <?php checked('pricing_plan', $current_val) ?>>
                <label for="claim_with_pricing"><?php _e('Charge claimer with <a href="' . $url . '" target="_blank">Pricing Plans</a>', 'direo-extension') ?></label>
            </div>
            <div>
                <input id="free_claim" type="radio" name="claim_fee"
                       value="free_claim" <?php checked('free_claim', $current_val) ?>>
                <label for="free_claim"><?php _e('Claim for Free', 'direo-extension') ?></label>
            </div>
            <div>
                <input id="clain_with_fee" type="radio" name="claim_fee"
                       value="static_fee" <?php checked('static_fee', $current_val) ?>>
                <label for="clain_with_fee"><?php _e('Set a claim fee', 'direo-extension') ?></label>
            </div>
            <input type="number" value="<?php echo !empty($claim_charge) ? $claim_charge : ''; ?>" name="claim_charge"
                   min="0">
        </div>
        <?php
    }

    public function register_necessary_scripts_front()
    {
        wp_enqueue_script('dcl-admin-script', DCL_ASSETS . '/js/main.js', array('jquery'), true);
        wp_enqueue_style('dcl_main_css', DCL_ASSETS . 'css/main.css', false, DCL_VERSION);
    }

    /*@todo later need to update the receipt content with the purchased packages dynamically e.g. remove Gold package*/
    /**
     * Add data to the customer receipt after completing an order.
     *
     * @param array $receipt_data An array of selected package.
     * @param integer $order_id Order ID.
     * @param integer $listing_id Listing ID.
     * @return     array      $receipt_data               Show the data of the packages.
     * @since     1.0.0
     * @access   public
     *
     */
    public function dcl_payment_receipt_data($receipt_data, $order_id, $listing_id)
    {
        $claim_fee = get_post_meta($listing_id[0], '_claim_fee', true);
        $admin_calim_charge = get_directorist_option('claim_charge_by');
        $charge_by = !empty($claim_fee) ? $claim_fee : $admin_calim_charge;
        if (class_exists('ATBDP_Pricing_Plans')) {
            return array();
        } else {
            if (('static_fee' === $charge_by)) {
                $claim_fee = get_post_meta($listing_id[0], '_claim_fee', true);
                if ($claim_fee !== 'static_fee') return array();
                $p_title = get_the_title($listing_id[0]);
                $fm_price = get_post_meta($listing_id[0], '_claim_charge', true);
                $admin_common_price = get_directorist_option('claim_listing_price');
                $fm_price = !empty($fm_price) ? $fm_price : $admin_common_price;
                $receipt_data = array(
                    'title' => $p_title,
                    'desc' => __('Claiming charge for this listing', 'direo-extension'),
                    'price' => $fm_price,
                );
                return $receipt_data;

            }
        }

    }

    /**
     * Add order details.
     *
     * @param array $order_details An array of containing order details.
     * @param integer $order_id Order ID.
     * @param integer $listing_id Listing ID.
     * @return     array      $order_details    Push additional package to the mail array.
     * @since     1.0.0
     * @access   public
     *
     */
    public function dcl_order_details($order_details, $order_id, $listing_id)
    {
        $claim_fee = get_post_meta($listing_id[0], '_claim_fee', true);
        $admin_calim_charge = get_directorist_option('claim_charge_by');
        $charge_by = !empty($claim_fee) ? $claim_fee : $admin_calim_charge;
        if (class_exists('ATBDP_Pricing_Plans')) {
            return array();
        } else {
            if (('static_fee' === $charge_by)) {
                $p_title = get_the_title($listing_id);
                $p_description = __('Claiming charge for this listing', 'direo-extension');
                $fm_price = get_post_meta($listing_id, '_claim_charge', true);
                $admin_common_price = get_directorist_option('claim_listing_price');
                $fm_price = !empty($fm_price) ? $fm_price : $admin_common_price;
                $order_details[] = array(
                    'active' => '1',
                    'label' => $p_title,
                    'desc' => $p_description,
                    'price' => $fm_price,
                    'show_ribbon' => '1',
                );
                return $order_details;
            }
        }


    }

    /**
     * Add data to the customer receipt after completing an order.
     *
     * @param array $order_items An array of selected package.
     * @param integer $listing_id Listing ID.
     * @return     array      $order_items               Show the data of the packages.
     * @since     1.0.0
     * @access   public
     *
     */
    public function dcl_order_items($order_items = null, $order_id = null, $listing_id = null, $data = null)
    {
        $claim_fee = get_post_meta($listing_id[0], '_claim_fee', true);
        $admin_calim_charge = get_directorist_option('claim_charge_by');
        $charge_by = !empty($claim_fee) ? $claim_fee : $admin_calim_charge;
        if (class_exists('ATBDP_Pricing_Plans')) {
            return array();
        } else {
            if (('static_fee' === $charge_by)) {
                $selected_plan_id = get_post_meta($listing_id[0], '_fm_plans', true);
                $updated_plan_id = get_user_meta(get_current_user_id(), '_plan_to_active', true);
                $plan_id = !empty($data['o_metas']['_fm_plan_ordered'][0]) ? $data['o_metas']['_fm_plan_ordered'][0] : '';
                $_plan_ids = !empty($selected_plan_id) ? $selected_plan_id : $updated_plan_id;
                $_plan_id = !empty($plan_id) ? $plan_id : $_plan_ids;
                $p_title = get_the_title($listing_id[0]);
                $p_description = get_post_meta($_plan_id, 'fm_description', true);
                $fm_price = get_post_meta($listing_id[0], '_claim_charge', true);
                $admin_common_price = get_directorist_option('claim_listing_price');
                $fm_price = !empty($fm_price) ? $fm_price : $admin_common_price;
                $order_items[] = array(
                    'title' => $p_title,
                    'desc' => $p_description,
                    'price' => $fm_price,
                );
                return $order_items;
            }
        }

    }

    /**
     * Add selected order to the checkout form.
     *
     * @param array $data An array of selected package.
     * @param integer $listing_id Listing ID.
     * @return     array      $data               Show the data of the packages.
     * @since     1.0.0
     * @access   public
     *
     */

    public function dcl_checkout_form_data($data, $listing_id)
    {
        $claim_fee = get_post_meta($listing_id, '_claim_fee', true);
        $admin_calim_charge = get_directorist_option('claim_charge_by');
        $charge_by = !empty($claim_fee) ? $claim_fee : $admin_calim_charge;
        if (class_exists('ATBDP_Pricing_Plans')) {
            return array();
        } else {
            if (('static_fee' === $charge_by)) {
                $selected_plan_id = get_post_meta($listing_id, '_fm_plans', true);
                $claim_charge = get_post_meta($listing_id, '_claim_charge', true);
                $admin_common_price = get_directorist_option('claim_listing_price');
                $fm_price = !empty($claim_charge) ? $claim_charge : $admin_common_price;
                $p_title = get_the_title($listing_id);
                $data[] = array(
                    'type' => 'header',
                    'title' => __('Claim for ', 'direo-extension') . $p_title
                );

                $data[] = array(
                    'type' => 'checkbox',
                    'name' => $selected_plan_id,
                    'value' => 1,
                    'selected' => 1,
                    'title' => __('Claim for ', 'direo-extension') . $p_title,
                    'desc' => __('Claiming charge for this listing ', 'direo-extension'),
                    'price' => $fm_price
                );
                return $data;
            } else {
                return array();
            }
        }

    }

    public function atpp_front_end_enqueue_scripts()
    {
        wp_enqueue_style('dcl_main_css', DCL_ASSETS . 'css/main.css', false, DCL_VERSION);
        wp_enqueue_style('atpp-bootstrap-style', DCL_ASSETS . 'css/atpp-bootstrap-grid.css', false, DCL_VERSION);

    }


    /**
     * Remove quick edit.
     *
     * @param array $actions An array of row action links.
     * @param WP_Post $post The post object.
     * @return     array      $actions    Updated array of row action links.
     * @since     1.0.0
     * @access   public
     *
     */
    public function atpp_remove_row_actions_for_quick_view($actions, $post)
    {

        global $current_screen;

        if ($current_screen->post_type != 'dcl_claim_listing') return $actions;

        unset($actions['view']);
        unset($actions['inline hide-if-no-js']);

        return $actions;

    }

    /**
     * Retrieve the table columns.
     *
     * @param array $column all the column
     * @param array $post_id post id
     * @since    1.0.0
     * @access   public
     */

    public function atpp_custom_field_column_content($column, $post_id)
    {
        echo '</select>';
        switch ($column) {
            case 'claim_for' :
                $post_meta = get_post_meta($post_id);
                $claimed_listing = isset($post_meta['_claimed_listing']) ? esc_attr($post_meta['_claimed_listing'][0]) : '';
                echo __('Claimed for ') . get_the_title($claimed_listing);
                break;

            case 'claimer' :
                $post_meta = get_post_meta($post_id);
                $current_author = isset($post_meta['_listing_claimer']) ? esc_attr($post_meta['_listing_claimer'][0]) : '';
                $user = get_user_by('id', $current_author);
                echo $user->display_name;
                break;
            case 'status' :
                $post_meta = get_post_meta($post_id);
                $current_status = isset($post_meta['_claim_status']) ? esc_attr($post_meta['_claim_status'][0]) : '';
                echo '<span class="atbdp-tick-cross2">' . ($current_status == 'approved' ? '<span style="color: #4caf50;">&#x2713;</span>' : '<span style="color: red;">&#x2717;</span>') . '</span>';
                echo ucwords($current_status);
                break;
            case 'details' :
                $post_meta = get_post_meta($post_id);
                $details = isset($post_meta['_claimer_details']) ? esc_textarea($post_meta['_claimer_details'][0]) : '';
                echo $details;
                break;

            case 'phone' :
                $post_meta = get_post_meta($post_id);
                echo !empty($post_meta['_claimer_phone'][0]) ? $post_meta['_claimer_phone'][0] : '';
                break;
        }
    }


    /**
     * Retrieve the table columns.
     *
     * @param array $columns
     *
     * @return   array    $columns    Array of all the list table columns.
     * @since    1.0.0
     * @access   public
     */
    public function atpp_add_new_plan_columns($columns)
    {

        $columns = array(
            'cb' => '<input type="checkbox" />', // Render a checkbox instead of text
            'claim_for' => __('Title', 'direo-extension'),
            'claimer' => __('Author', 'direo-extension'),
            'status' => __('Status', 'direo-extension'),
            'details' => __('Claimer Details', 'direo-extension'),
            'phone' => __('Claimer Phone', 'direo-extension'),
            'date' => __('Date', 'direo-extension')

        );

        return $columns;

    }


    /**
     * Register meta boxes for Claim Listing.
     *
     * @since    1.0.0
     * @access   public
     */
    public function atpp_add_meta_boxes()
    {

        remove_meta_box('dcl-claim-details', 'dcl_claim_listing', 'normal');
        remove_meta_box('slugdiv', 'dcl_claim_listing', 'normal');


        add_meta_box('dcl-claim-details', __('Claim Details', 'direo-extension'), array($this, 'dcl_meta_box_plan_details'), 'dcl_claim_listing', 'normal', 'high');
    }

    public function dcl_meta_box_plan_details($post)
    {

        // Add a nonce field so we can check for it later
        wp_nonce_field('dcl_save_claim_details', 'dcl_claim_details_nonce');
        /**
         * Display the "Field Details" meta box.
         */
        $this->load_template('admin-meta-fields');

    }


    /*
        * save data to database from the metaboxes
        */

    public function dcl_save_meta_data($post_id)
    {
        if (!isset($_POST['post_type'])) {
            return $post_id;
        }

// If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

// Check the logged in user has permission to edit this post
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        /*
         * save all the metadata to option table
         */
        // Check if "dcl_claim_details_nonce" nonce is set
        if (isset($_POST['dcl_claim_details_nonce'])) {
            // Verify that the nonce is valid
            if (wp_verify_nonce($_POST['dcl_claim_details_nonce'], 'dcl_save_claim_details')) {
                require_once DCL_INC_DIR . 'class-db.php';
            }
        }

    }


    /**
     * Register a custom post type "dcl_claim_listing".
     *
     * @since    3.1.0
     * @access   public
     */
    public function register_claim_listing_post_type()
    {

        $labels = array(
            'name' => _x('Claim Listing', 'Post Type General Name', 'direo-extension'),
            'singular_name' => _x('Claim Listing', 'Post Type Singular Name', 'direo-extension'),
            'menu_name' => __('Claim Listing', 'direo-extension'),
            'name_admin_bar' => __('Claim', 'direo-extension'),
            'all_items' => __('Claim Listing', 'direo-extension'),
            'add_new_item' => __('Add New Claim', 'direo-extension'),
            'add_new' => __('Add New Claim', 'direo-extension'),
            'new_item' => __('New Claim', 'direo-extension'),
            'edit_item' => __('Edit Claim', 'direo-extension'),
            'update_item' => __('Update Claim', 'direo-extension'),
            'view_item' => __('View Claim', 'direo-extension'),
            'search_items' => __('Search Claim', 'direo-extension'),
            'not_found' => __('No Claim found', 'direo-extension'),
            'not_found_in_trash' => __('No Claim found in Trash', 'direo-extension'),
        );

        $args = array(
            'labels' => $labels,
            'description' => __('This order post type will keep track of admin fee plans', 'direo-extension'),
            'supports' => array('title'),
            'taxonomies' => array(''),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => current_user_can('manage_atbdp_options') ? true : false, // show the menu only to the admin
            'show_in_menu' => current_user_can('manage_atbdp_options') ? 'edit.php?post_type=' . ATBDP_POST_TYPE : false,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'atbdp_order',
            'map_meta_cap' => true,
        );

        register_post_type('dcl_claim_listing', $args);

    }

    /**
     * @since 1.0.1
     */
    public function dcl_email_templates($settings)
    {
        $new_claim = array(
            'type' => 'section',
            'title' => __('For New Claim', 'direo-extension'),
            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'direo-extension'),
            'fields' => dcl_user_email_edit_tmpl_settings_fields(),
        );
        $approved_claim_confirmation = array(
            'type' => 'section',
            'title' => __('Approved Claim Confirmation', 'direo-extension'),
            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'direo-extension'),
            'fields' => dcl_user_approved_confirmation_email_edit_tmpl_settings_fields(),
        );
        $declined_claim_confirmation = array(
            'type' => 'section',
            'title' => __('Declined Claim Confirmation', 'direo-extension'),
            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'direo-extension'),
            'fields' => dcl_user_declined_confirmation_email_edit_tmpl_settings_fields(),
        );

        // lets push our settings to the end of the other settings field and return it.
        array_push($settings, $new_claim, $approved_claim_confirmation, $declined_claim_confirmation);
        return $settings;
    }


    /**
     * @since 1.0.1
     */
    public function dcl_claim_confirmation_notification($events)
    {
        $claim_event2 = array(
            'value' => 'claim_confirmation',
            'label' => __('Claim Confirmation', 'direo-extension'),

        );

        // lets push our settings to the end of the other settings field and return it.
        array_push($events, $claim_event2);
        return $events;
    }


    /**
     * @since 1.0.2
     */
    public function dcl_default_events_to_notify_user($events)
    {
        $claim_event = 'claim_confirmation';
        // lets push our settings to the end of the other settings field and return it.
        array_push($events, $claim_event);
        return $events;
    }

    /**
     * @since 1.0.2
     */
    public function dcl_default_events_to_notify_admin($events)
    {
        $claim_event = 'new_claim_submitted';
        // lets push our settings to the end of the other settings field and return it.
        array_push($events, $claim_event);
        return $events;
    }

    /**
     * @since 1.0.1
     */
    public function dcl_claim_notification($events)
    {
        $claim_event = array(
            'value' => 'new_claim_submitted',
            'label' => __('New Claim Submitted', 'direo-extension'),

        );


        // lets push our settings to the end of the other settings field and return it.
        array_push($events, $claim_event);
        return $events;
    }


    /**
     * It adds custom settings field of Directorist Claim Listing to the General Settings Sections Under the Extension menu
     * of Directorist.
     * @param $fields array
     * @return array
     */
    public function add_settings_for_claim_listing($fields)
    {
        $permission = array(
            'type' => 'toggle',
            'name' => 'enable_claim_listing',
            'label' => __('Claim Listing', 'direo-extension'),
            'description' => __('You can disable it for users.', 'direo-extension'),
            'default' => 1

        );

        // lets push our settings to the end of the other settings field and return it.
        array_push($fields, $permission);
        return $fields;
    }


    /**
     * It  loads a template file from the Default template directory.
     * @param string $name Name of the file that should be loaded from the template directory.
     * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
     */
    public function load_template($name, $args = array())
    {
        global $post;
        include(DCL_TEMPLATES_DIR . $name . '.php');
    }

    /**
     * It register the text domain to the WordPress
     */
    public function load_textdomain()
    {
        load_plugin_textdomain('direo-extension', false, DCL_LANG_DIR);
    }

    /**
     * It Includes and requires necessary files.
     *
     * @access private
     * @return void
     * @since 1.0
     */
    private function includes()
    {
        require_once DCL_INC_DIR . 'helper-functions.php';
        require_once DCL_INC_DIR . 'class-enqueuer.php';
        require_once DCL_INC_DIR . 'class-claim-now.php';
    }


    /**
     * Setup plugin constants.
     *
     * @access private
     * @return void
     * @since 1.0
     */
    private function setup_constants()
    {
        require_once plugin_dir_path(__FILE__) . '/config.php'; // loads constant from a file so that it can be available on all files.
    }
}

/**
 * The main function for that returns DCL_Base
 *
 * The main function responsible for returning the one true DCL_Base
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @return object|DCL_Base The one true DCL_Base Instance.
 * @since 1.0
 */
function DCL_Base()
{
    return DCL_Base::instance();
}


DCL_Base(); // get the plugin running
