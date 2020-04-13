<?php
// prevent direct access to the file
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
$direo_monetize_by = 'none';
if (class_exists('Directorist_Base')){
    $direo_monetize_by = get_directorist_option('direo_monetize_by','pricing_plan');
    $enable_monetization = get_directorist_option('enable_monetization',0);
}

if (empty($enable_monetization) || (('pricing_plan' !== $direo_monetize_by) || ('none' === $direo_monetize_by))){
    return;
} else {
    final class ATBDP_Pricing_Plans
    {


        /** Singleton *************************************************************/

        /**
         * @var ATBDP_Pricing_Plans The one true ATBDP_Pricing_Plans
         * @since 1.0
         */
        private static $instance;

        private static $plan_id;

        /**
         * Main ATBDP_Pricing_Plans Instance.
         *
         * Insures that only one instance of ATBDP_Pricing_Plans exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 1.0
         * @static
         * @static_var array $instance
         * @uses ATBDP_Pricing_Plans::setup_constants() Setup the constants needed.
         * @uses ATBDP_Pricing_Plans::includes() Include the required files.
         * @uses ATBDP_Pricing_Plans::load_textdomain() load the language files.
         * @see  ATBDP_Pricing_Plans()
         * @return object|ATBDP_Pricing_Plans The one true ATBDP_Pricing_Plans
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof ATBDP_Pricing_Plans)) {
                self::$instance = new ATBDP_Pricing_Plans;
                self::$instance->setup_constants();

               // add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));

                self::$instance->includes();
                new ATPP_Enqueuer();// enqueue required styles and scripts

                // Add Settings fields to the extension general fields
                add_filter('atbdp_extension_settings_fields', array(self::$instance, 'add_settings_for_fee_manager'));
                add_filter('atbdp_pages_settings_fields', array(self::$instance, 'plan_pages_settings_fields'));
                add_filter('atbdp_extension_settings_submenus', array(self::$instance, 'plan_settings_to_ext_general_fields'));
                add_shortcode('directorist_pricing_plans', array(self::$instance, 'directorist_fee_plane_page'));
                add_action('init', array(self::$instance, 'register_custom_post_type_for_FM'));
                add_action('admin_enqueue_scripts', array(self::$instance, 'register_necessary_scripts'));
                add_action('wp_enqueue_scripts', array(self::$instance, 'register_necessary_scripts_front'));
                add_action('add_meta_boxes', array(self::$instance, 'atpp_add_meta_boxes'));
                add_action('save_post', array(self::$instance, 'atpp_save_meta_data'));
                add_filter('manage_atbdp_pricing_plans_posts_columns', array(self::$instance, 'atpp_add_new_plan_columns'));
                add_action('manage_atbdp_pricing_plans_posts_custom_column', array(self::$instance, 'atpp_custom_field_column_content'), 10, 2);
                add_filter('post_row_actions', array(self::$instance, 'atpp_remove_row_actions_for_quick_view'), 10, 2);
                //add_action('atbdb_before_add_listing_from_frontend', array(self::$instance, 'atpp_fees_for_listing_submit_frontend'));
                add_action('atbdp_before_processing_listing_frontend', array(self::$instance, 'atpp_process_the_selected_plans'));
                add_action('template_redirect', array(self::$instance, 'atpp_front_end_enqueue_scripts'));
                add_filter('atbdp_checkout_form_data', array(self::$instance, 'atpp_checkout_form_data'), 11, 2);
                add_filter('atbdp_payment_receipt_data', array(self::$instance, 'atpp_payment_receipt_data'), 12, 3);
                add_filter('atbdp_order_details', array(self::$instance, 'atpp_order_details'), 11, 3);
                add_filter('atbdp_order_items', array(self::$instance, 'atpp_order_items'), 11, 4);
                add_filter('atbdp_monetization_settings_controls', array(self::$instance, 'atpp_monetization_settings_controls'));
                //add new order column and content
                add_filter('atbdp_add_new_order_column', array(self::$instance, 'atbdp_add_new_order_column'));
                add_action('atbdp_custom_order_column_content', array(self::$instance, 'atbdp_custom_order_column_content'), 10, 3);
                add_filter('atbdp_before_submitted_data', array(self::$instance, 'atpp_before_submitted_data'));
                add_action('atbdp_online_order_processed', array(self::$instance, 'atpp_online_order_processed'), 10, 2);
                add_action('atbdp_listing_form_after_add_listing_title', array(self::$instance, 'atpp_type_of_listing'));
                //add new listing column and content
                add_filter('atbdp_add_new_listing_column', array(self::$instance, 'atpp_add_new_listing_column'));
                add_filter('atbdp_add_new_listing_column_content', array(self::$instance, 'atpp_add_new_listing_column_content'), 10, 2);
                add_action('atbdp_tab_after_favorite_listings', array(self::$instance, 'atpp_tab_after_favorite_listings'));
                add_action('atbdp_tab_content_after_favorite', array(self::$instance, 'atpp_tab_content_after_favorite'));
                add_action('atbdp_after_video_metabox_backend_add_listing', array(self::$instance, 'atpp_admin_metabox'));
                add_action('edit_post', array(self::$instance, 'atpp_save_metabox'), 11, 2);
                //Renew plan
                add_action('atbdp_before_renewal', array(self::$instance, 'atpp_before_renewal'));
                //refresh listing on renewal or upgrade/downgrade
                add_action('atbdp_order_status_changed', array(self::$instance, 'atpp_order_status_changed'), 10, 3);
                //Changing plan
                add_action('atbdp_user_dashboard_listings_before_expiration', array(self::$instance, 'atpp_plan_change'));
                add_action('wp_footer', array(self::$instance, 'atpp_plan_change_modal'));
                add_action('wp_ajax_atpp_submit_changing_plan', array(self::$instance, 'atpp_submit_changing_plan'));
                add_action('wp_ajax_nopriv_atpp_submit_changing_plan', array(self::$instance, 'atpp_submit_changing_plan'));

                add_filter('atbdp_dashboard_field_setting', array(self::$instance, 'atpp_settings_to_ext_general_fields'));
                if (get_option('atbdp_plan_page_create') < 1) {
                    add_action('wp_loaded', array(self::$instance, 'add_custom_page'));
                }
                add_action('atbdp_before_pricing_plan_page_load', array(self::$instance, 'atbdp_add_listing_page_url'));
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
         * @since 1.0
         * @access protected
         * @return void
         */
        public function __clone()
        {
            // Cloning instances of the class is forbidden.
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
        }

        /**
         * Disable unserializing of the class.
         *
         * @since 1.0
         * @access protected
         * @return void
         */
        public function __wakeup()
        {
            // Unserializing instances of the class is forbidden.
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'direo-extension'), '1.0');
        }


        /**
         * @since 1.5.1
         */
        public function atbdp_add_listing_page_url()
        {
            $skip_plans = get_directorist_option('skip_plan_page', 0);
            if (empty($skip_plans)) return; // void if admin not to decide show plan page for active packaged user
            $orders = new WP_Query(array(
                'post_type' => 'atbdp_orders',
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'author' => get_current_user_id(),
                'meta_key' => '_payment_status',
                'meta_value' => 'completed',
                'compare' => '=',
            ));
            $oder_id = '';
            /**
             * @todo later tract number of active plan
             */
            foreach ($orders->posts as $key => $val) {
                $oder_id = $val->ID;
            }
            $plan_id = get_post_meta($oder_id, '_fm_plan_ordered', true);

            if (!empty($plan_id)) {
                if (('package' === package_or_PPL($plan_id))) {
                    echo '<script>window.location="' . esc_url(ATBDP_Permalink::get_add_listing_page_link() . "?plan=$plan_id") . '"</script>';
                }
            }
        }


        public function add_custom_page()
        {
            $create_permission = apply_filters('atbdp_create_required_pages', true);
            if ($create_permission) {
                atpp_create_required_pages();
            }
        }

        public function atpp_order_status_changed($new_status, $old_status, $post_id)
        {
            $user_id = get_current_user_id();
            $listing_id = get_post_meta($post_id, '_listing_id', true);
            $plan_id = get_post_meta($listing_id, '_fm_plans', true);
            if ('completed' === $new_status) {
                atpp_need_listing_to_refresh($user_id, 'completed', $plan_id);
            }
        }


        /**
         * @since 1.4.2
         */
        public function atpp_settings_to_ext_general_fields($settings_submenus)
        {
            /*lets add a submenu of our extension*/

            $setting1 = array(
                'type' => 'toggle',
                'name' => 'user_active_package',
                'label' => __('Display Packages Tab', 'direo-extension'),
                'default' => 1,
            );
            $setting2 = array(
                'type' => 'toggle',
                'name' => 'user_order_history',
                'label' => __('Display Order History Tab', 'direo-extension'),
                'default' => 1,
            );
            /*  $setting3 = array(
                  'type' => 'toggle',
                  'name' => 'user_subscription',
                  'label' => __('Display Subscription Tab', 'direo-extension'),
                  'default' => 1,
              );*/
            $setting4 = array(
                'type' => 'toggle',
                'name' => 'change_plan',
                'label' => __('Display Plan Change Link', 'direo-extension'),
                'default' => 1,
            );

            array_push($settings_submenus, $setting1, $setting2, $setting4);
            return $settings_submenus;
        }

        /**
         * @since 1.3.2
         */

        public function atpp_submit_changing_plan()
        {
            $data = array('error' => 0);
            $plan_id = isset($_POST["plan_id"]) ? (int)($_POST["plan_id"]) : '';
            $listing_id = (int)$_POST["post_id"];
            if (atpp_need_to_charge_with_plan()) {
                update_post_meta($listing_id, '_fm_plans', $plan_id);
                $data['message'] = __('Plan changed successfully!', 'direo-extension');
                $data['renew_info'] = __('Listing renewed successfully!', 'direo-extension');
            } else {
                //update_post_meta($listing_id, '_fm_changed_plans', $plan_id);
                update_post_meta($listing_id, '_fm_plans', $plan_id);
                $url = ATBDP_Permalink::get_checkout_page_link($listing_id);
                $data['checkout_url'] = $url;
                $data['take_payment'] = 'plan';
            }
            echo wp_json_encode($data);
            wp_die();
        }

        /**
         * 1.3.2
         * @param $listing_id
         */
        public function atpp_plan_change($listing_id)
        {
            $plan_id = get_post_meta($listing_id, '_fm_plans', true);
            $change_plan = get_directorist_option('change_plan', 1);
            $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id);
            $change_plan_link = apply_filters('atbdp_plan_change_link_in_user_dashboard', '<span><a data-target="'.$modal_id.'" class="atpp_change_plan" data-listing_id="' . $listing_id . '" href="">' . __('Change', 'direo-extension') . '</a></span>', $listing_id);

            $plan_name = !empty($plan_id) ? get_the_title($plan_id) : __('No Plan!', 'direo-extension');
            printf(__('<p><span>Plan Name:</span> %s %s</p>', 'direo-extension'), $plan_name, !empty($change_plan) ? $change_plan_link : '');
        }


        /**
         * @since 5.5.2
         *
         */
        public function atpp_plan_change_modal()
        {
            ?>
            <div class="at-modal atm-fade" id="atpp-plan-change-modal">
                <div class="at-modal-content at-modal-lg">
                    <div class="atm-contents-inner">
                        <a href="" class="at-modal-close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                        <div class="align-items-center">
                            <div class="">
                                <form id="atpp-change-plan-form" class="form-vertical" role="form">
                                    <div class="atbd_modal-header">
                                        <input type="hidden" value="" id="change_listing_id">
                                        <h3 class="atbd_modal-title"
                                            id="atpp-plan-label"><?php _e('Change Pricing Plan', 'direo-extension'); ?></h3>
                                        <?php
                                        $link = '<a href="' . ATBDP_Permalink::get_fee_plan_page_link() . '" target="_blank">' . __('Click Here', 'direo-extension') . '</a>';
                                        printf('<p>%s %s</p>', __('We recommend you check the details of Pricing Plans before changing.', 'direo-extension'), $link)
                                        ?>
                                    </div>
                                    <div class="atbd_modal-body">
                                        <div class="dcl_pricing_plan">
                                            <?php
                                            $meta_queries = array();
                                            $args = array(
                                                'post_type' => 'atbdp_pricing_plans',
                                                'posts_per_page' => -1,
                                                'status' => 'publish',
                                                $meta_queries[] = array(
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
                                            $meta_queries = apply_filters('atbdp_plan_meta_query', $meta_queries);
                                            $count_meta_queries = count($meta_queries);
                                            if ($count_meta_queries) {
                                                $args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
                                            }

                                            $atbdp_query = new WP_Query($args);

                                            if ($atbdp_query->have_posts()) {
                                                global $post;
                                                $plans = $atbdp_query->posts;
                                                printf('<label for="select_plans">%s</label><hr>', __('Select Plan', 'direo-extension'));
                                                foreach ($plans as $key => $value) {
                                                    $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $value->ID);
                                                    $plan_metas = get_post_meta($value->ID);
                                                    $unl = __('Unlimited', 'direo-extension');
                                                    $plan_type = esc_attr($plan_metas['plan_type'][0]);
                                                    $fm_price = esc_attr($plan_metas['fm_price'][0]);
                                                    $fm_length = esc_attr($plan_metas['fm_length'][0]);
                                                    $fm_length_unl = esc_attr($plan_metas['fm_length_unl'][0]);
                                                    $num_regular = esc_attr($plan_metas['num_regular'][0]);
                                                    $num_regular_unl = esc_attr($plan_metas['num_regular_unl'][0]);
                                                    $num_featured = esc_attr($plan_metas['num_featured'][0]);
                                                    $num_featured_unl = esc_attr($plan_metas['num_featured_unl'][0]);
                                                    $regular = (empty($num_regular_unl) ? $num_regular : $unl) . __(' regular', 'direo-extension');
                                                    $featured = (empty($num_featured_unl) ? $num_featured : $unl) . __(' featured listings', 'direo-extension');
                                                    $currency = atbdp_get_payment_currency();
                                                    $symbol = atbdp_currency_symbol($currency);
                                                    $allowances = sprintf('<p class="atbd_plan_core_features"><span class="apc_price">%s</span><span>%s%s</span><span>%s</span><span>%s & %s</span></p>', $symbol . $fm_price, empty($fm_length_unl) ? $fm_length : $unl, __(' days', 'direo-extension'), ($plan_type === 'package' ? __('Package', 'direo-extension') : __('Pay Per Listing', 'direo-extension')), $regular, $featured);
                                                    $active = '';
                                                    if ('package' === $plan_type && $active_plan) {
                                                        $active = ' <span class="atbd_badge atbd_badge_open">' . __('Active', 'direo-extension') . '</span>';
                                                    }
                                                    printf('<input type="radio" class="new_plan_id" id="%s" name="new_plan" value="%s"> <label for="%s"> %s</label>%s <br>%s<hr>', $value->ID, $value->ID, $value->ID, $value->post_title, $active, $allowances);
                                                }

                                            }

                                            ?>
                                        </div>
                                        <div id="atbdp-report-abuse-g-recaptcha"></div>
                                        <div id="dcl-claim-submit-notification"></div>
                                        <div id="dcl-claim-warning-notification"></div>
                                    </div>
                                    <div class="atbd_modal-footer">
                                        <button type="submit"
                                                class="atbd_modal_btn"><?php esc_html_e('Change', 'direo-extension'); ?></button>
                                        <span><i class="<?php atbdp_icon_type(true); ?>-lock"></i> <?php esc_html_e('Secure Payment Process', 'direo-extension'); ?></span>
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
         * @since 1.3.2
         */
        public function atpp_save_metabox($post_id, $post)
        {
            if (is_admin()) {
                $admin_plan = isset($_POST['admin_plan']) ? $_POST['admin_plan'] : '';
                $sub_plan_id = get_post_meta($post_id, '_fm_plans', true);
                if (($admin_plan != (int)$sub_plan_id)) {
                    $plan = !empty($admin_plan) ? $admin_plan : $sub_plan_id;
                    $is_never_expaired = get_post_meta($plan, 'fm_length_unl', true);
                    if (!empty($admin_plan)) {
                        $package_length = get_post_meta($plan, 'fm_length', true);
                        $package_length = $package_length ? $package_length : '1';
                        // Current time
                        $current_d = current_time('mysql');
                        // Calculate new date
                        $date = new DateTime($current_d);
                        $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                        $expired_date = $date->format('Y-m-d H:i:s');
                        if (($expired_date > $current_d) || $is_never_expaired) {
                            update_post_meta($post_id, '_listing_status', 'post_status');
                        }
                        if ($is_never_expaired) {
                            update_post_meta($post_id, '_never_expire', '1');
                        } elseif (!$is_never_expaired) {
                            update_post_meta($post_id, '_never_expire', 0);
                            update_post_meta($post_id, '_expiry_date', $expired_date);
                        }
                        update_post_meta($post_id, '_fm_plans', $plan);
                        update_post_meta($post_id, '_fm_plans_by_admin', 1);

                    }
                    if ('null' === $admin_plan) {
                        $p = $_POST; // save some character
                        $exp_dt = $p['exp_date'];
                        if (!is_empty_v($exp_dt) && !empty($exp_dt['aa'])) {
                            $exp_dt = array(
                                'year' => (int)$exp_dt['aa'],
                                'month' => (int)$exp_dt['mm'],
                                'day' => (int)$exp_dt['jj'],
                                'hour' => (int)$exp_dt['hh'],
                                'min' => (int)$exp_dt['mn']
                            );
                            $exp_dt = get_date_in_mysql_format($exp_dt);
                        } else {
                            $exp_dt = calc_listing_expiry_date(); // get the expiry date in mysql date format using the default expiration date.
                        }
                        update_post_meta($post_id, '_expiry_date', $exp_dt);
                    }

                }
            }
        }


        /**
         * @since 1.3.2
         */

        public function atpp_admin_metabox()
        {
            if (!get_directorist_option('fee_manager_enable', 1)) return; // vail if the business hour is not enabled
            add_meta_box('_listing_admin_plan',
                __('Belongs to Plan', 'direo-extension'),
                array($this, 'atpp_admin_plan'),
                ATBDP_POST_TYPE,
                'side', 'high');
        }

        /**
         * @since 1.3.2
         */
        public function atpp_admin_plan($post)
        {
            $current_val = get_post_meta($post->ID, '_fm_plans', true);
            $args = array(
                'post_type' => 'atbdp_pricing_plans',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            );

            $atbdp_query = new WP_Query($args);

            if ($atbdp_query->have_posts()) {
                global $post;
                $plans = $atbdp_query->posts;
                printf('<label for="select_plans">%s</label>', __('Select Plan', 'direo-extension'));
                printf('<select name="admin_plan">');
                echo '<option value="null">' . __('- Select -', 'direo-extension') . '</option>';
                foreach ($plans as $key => $value) {
                    $class = apply_filters('atbdp_admin_plan_select_option_class', 'listing_plan', $value->ID);
                    printf('<option class="%s" value="%s" %s>%s</option>',$class, $value->ID, selected($value->ID, $current_val), $value->post_title);
                }
                printf('</select>');
                printf('<a target="_blank" href="%s" class="atpp_plans">%s</a>', esc_url(ATBDP_Permalink::get_fee_plan_page_link()), __('Details', 'direo-extension'));
            }
        }


        /**
         * @since 1.3.2
         */

        public function atpp_before_renewal($listing_id)
        {

            update_post_meta($listing_id, '_renew_with_plan', 1);
            wp_safe_redirect(ATBDP_Permalink::get_checkout_page_link($listing_id));
            exit;

        }

        public function register_necessary_scripts_front()
        {
            wp_register_script('atpp-plan-validator', ATPP_ASSETS . '/js/plan-validator.js', array('jquery'), true);
            wp_enqueue_script('atpp-plan-validator');
            //get listing is if the screen in edit listing
            global $wp;
            global $pagenow;
            $current_url = home_url(add_query_arg(array(), $wp->request));
            $planID = '';
            if ((strpos($current_url, '/edit/') !== false) && ($pagenow = 'at_biz_dir')) {
                $listing_id = substr($current_url, strpos($current_url, "/edit/") + 6);
                $fm_plans = get_post_meta($listing_id, '_fm_plans', true);
                $selected_plan = selected_plan_id();
                $planID = !empty($selected_plan) ? $selected_plan : $fm_plans;
            }
            $price_limit = '99999999999999999999';
            $allow_price = is_plan_allowed_price($planID);
            $price_range_unl = is_plan_price_unlimited($planID);
            if ($allow_price && empty($price_range_unl)) {
                $price_limit = is_plan_price_limit($planID);
            }

            $allow_tag = is_plan_allowed_tag($planID);
            $unl_tag = is_plan_tag_unlimited($planID);
            $tag_limit = '99999999999999999999';
            if ($allow_tag && empty($unl_tag)) {
                $tag_limit = is_plan_tag_limit($planID);
            }

            $validator = array(
                'price_limit' => $price_limit,
                'tag_limit' => $tag_limit,
                'ajaxurl' => admin_url('admin-ajax.php'),
                'crossLimit' => __('You have crossed the limit!', 'direo-extension'),

            );

            wp_localize_script('atpp-plan-validator', 'plan_validator', $validator);
        }

        public function register_necessary_scripts()
        {
            wp_enqueue_script('atpp-admin-script', ATPP_ASSETS . '/js/main.js', array('jquery'), true);
            wp_enqueue_style('bdmi_main_css', ATPP_ASSETS . 'css/main.css', false, ATPP_VERSION);
            wp_enqueue_style('plan_custom_css');
        }

        public function atpp_tab_after_favorite_listings()
        {
            $user_order_history = get_directorist_option('user_order_history', 1);
            $user_active_package = get_directorist_option('user_active_package', 1);
            $user_subscription = 0;
            if (!empty($user_active_package)) {
                ?>
                <li <?php echo apply_filters('atbdp_li_attribute_in_dashboard_package_tab', 'class="atbd-packages"')?>><a <?php echo apply_filters('atbdp_attribute_in_dashboard_package_tab', 'href="" class="atbd_tn_link" target="manage_fees"'); ?>><?php echo apply_filters('atbdp_package_tab_text_in_dashboard', __('Packages', 'direo-extension')); ?></a>
                </li>
            <?php }
            if (!empty($user_order_history)) { ?>
                <li <?php echo apply_filters('atbdp_li_attribute_in_dashboard_order_tab', 'class="atbd-orderhisyory"')?>><a <?php echo apply_filters('atbdp_attribute_in_dashboard_order_history_tab', 'href="" '); ?> class="atbd_tn_link" target="manage_invoices"><?php echo apply_filters('atbdp_order_history_tab_text_in_dashboard', __('Order History', 'direo-extension')); ?></a>
                </li>
                <?php
            }
            if (!empty($user_subscription)) { ?>
                <li class="atbd-subscription"><a href="" <?php do_action('atbdp_attribute_in_dashboard_subscription_tab'); ?> class="atbd_tn_link"
                                                 target="manage_subscription"><?php echo apply_filters('atbdp_subscription_tab_text_in_dashboard', __('Subscription', 'direo-extension')); ?></a>
                </li>
                <?php
            }
        }

        public function atpp_tab_content_after_favorite()
        {

            $this->load_template('user-dashboard-data');
        }

        public function atpp_add_new_listing_column_content($column = null, $listing_id = null)
        {
            switch ($column) {
                case 'active_plan' :
                    $user_id = get_post_field('post_author', $listing_id);
                    $selected_plan_id = get_post_meta($listing_id, '_fm_plans', true);
                    $plans_by_admin = get_post_meta($listing_id, '_fm_plans_by_admin', true);
                    $active_package = get_post_meta($listing_id, '_plan_order_id', true);
                    $plan_details = subscribed_package_or_PPL_plans($user_id, 'completed', $selected_plan_id);
                    $listing_plan_details = package_or_PPL_with_listing($user_id, 'completed', $listing_id);
                    $plan_name = !empty($selected_plan_id) ? get_the_title($selected_plan_id) : __('No Plan!', 'direo-extension');
                    $active = '';
                    $order_id = !empty($listing_plan_details) ? $listing_plan_details[0]->ID : '';
                    $plan_type = package_or_PPL($selected_plan_id);
                    if (package_or_PPL($selected_plan_id) == 'package') {
                        if ($plan_details || !empty($plans_by_admin || $order_id || $active_package)) {
                            $plan_type = __('Package', 'direo-extension');
                            $active = 'green';
                        }
                    }
                    if (package_or_PPL($selected_plan_id) == 'pay_per_listng') {
                        $plan_type = __('Pay Per Listing', 'direo-extension');
                        if ($listing_plan_details || !empty($plans_by_admin)) {
                            $active = 'green';
                        }
                    }
                    ?>
                    <span style='color: <?php echo !empty($active) ? $active : 'red' ?>;'><?php echo !empty($plan_name) ? $plan_name : ''; ?></span>
                    <span><?php echo(!empty($selected_plan_id) ? ' - ' : '');
                        echo ucfirst($plan_type); ?></span>
                    <?php
                    //subscribed_package_or_PPL_plans($user_id, $order_status, $plan_id);
                    break;
            }
        }

        public function atpp_add_new_listing_column($column_name)
        {
            $column_name['active_plan'] = __('Payment Status', 'direo-extension');
            return $column_name;
        }

        public function atpp_type_of_listing($listing_info)
        {
            if (!is_fee_manager_active()) return false; //void if admin deactivated plan from settings panel
            if ((package_or_PPL($plan = null) === 'pay_per_listng') || !empty($listing_info)) return false;
            $user_id = get_current_user_id();
            $subscribed_package_id = selected_plan_id();
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
                $listing_id = get_post_meta($active_plan[0]->ID, '_listing_id', true);
                $featured = get_post_meta($listing_id, '_featured', true);
                $total_regular_listing = $num_regular - ('0' === $featured ? $user_regular_listing + 1 : $user_regular_listing);
                $featured_counted = ('1' === $featured ? $user_featured_listing + 1 : $user_featured_listing);
                $total_featured_listing = (int)$num_featured - (int)$featured_counted;
            }
            ?>
            <div class="atbd_listing_type">
                <?php
                $listing_type = !empty($listing_info['listing_type']) ? $listing_info['listing_type'] : '';
                ?>
                <h4><?php _e('Choose Listing Type', 'direo-extension') ?></h4>
                <div class="atbd_listing_type_list">
                    <?php
                    if (!empty($num_regular_unl)) {
                        ?>
                        <label for="regular"><input
                                    id="regular" <?php echo ($listing_type == 'regular') ? 'checked' : ''; ?>
                                    type="radio"
                                    name="listing_type" value="regular"
                                    checked><?php _e(' Regular listing', 'direo-extension') ?><span
                                    class="atbdp_make_str_green"><?php _e(" (Unlimited)", 'direo-extension') ?></span></label>
                    <?php } else { ?>
                        <label for="regular"><input
                                    id="regular" <?php echo ($listing_type == 'regular') ? 'checked' : ''; ?>
                                    type="radio"
                                    name="listing_type" value="regular"
                                    checked><?php _e(' Regular listing', 'direo-extension') ?><span
                                    class="<?php echo $total_regular_listing > 0 ? 'atbdp_make_str_green' : 'atbdp_make_str_red' ?>"><?php _e(" ($total_regular_listing Remaining)", 'direo-extension') ?></span></label>
                    <?php } ?>

                    <?php
                    if (!empty($num_featured_unl)) {
                        ?>
                        <label for="featured" class="featured_listing_type_select"><input id="featured"
                                                                                          type="radio" <?php echo ($listing_type == 'featured') ? 'checked' : ''; ?>
                                                                                          name="listing_type"
                                                                                          value="featured"><?php _e(' Featured listing', 'direo-extension') ?>
                            <span class="atbdp_make_str_green"><?php _e(" (Unlimited)", 'direo-extension') ?></span></label>
                        <?php
                    } else { ?>
                        <label for="featured" class="featured_listing_type_select"><input id="featured"
                                                                                          type="radio" <?php echo ($listing_type == 'featured') ? 'checked' : ''; ?>
                                                                                          name="listing_type"
                                                                                          value="featured"><?php _e(' Featured listing', 'direo-extension') ?>
                            <span class="<?php echo $total_featured_listing > 0 ? 'atbdp_make_str_green' : 'atbdp_make_str_red' ?>"><?php _e(" ($total_featured_listing Remaining)", 'direo-extension') ?></span></label>
                    <?php } ?>
                </div>
            </div>
            <?php
        }


        public function directorist_fee_plane_page($atts)
        {
            ob_start();
            $this->load_template('fee-plans', array('atts' => $atts));
            return ob_get_clean();
        }


        public function atpp_online_order_processed($order_id, $listing_id)
        {
            $plan_id = get_post_meta($listing_id, '_fm_plans', true);
            $plan_type = package_or_PPL($plan_id);
            $orders = new WP_Query(array(
                'post_type' => 'atbdp_orders',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'author' => $order_id,
                'meta_key' => '_payment_status',
                'meta_value' => 'completed',
            ));
            $all_ids = array();
            foreach ($orders->posts as $key => $val) {
                $all_ids[] = !empty($val) ? $val->ID : array();
            }
            ///$last_order_id =!empty($all_ids[0])?$all_ids[0]:'';
            if (count($all_ids) > 1) {
                array_shift($all_ids);
                $ids_need_to_change_status = !empty($all_ids) ? array_values($all_ids) : '';
                foreach ($ids_need_to_change_status as $index => $val) {
                    if (('pay_per_listng' != $plan_type)) {
                        update_post_meta($order_id, '_payment_status', 'cancelled');
                    }
                }
            }
        }


        public function atpp_before_submitted_data($metas = null)
        {

            $user_id = get_current_user_id();
            $subscribed_package_id = get_user_meta($user_id, '_subscribed_users_plan_id', true);

            $subscribed_date = get_user_meta($user_id, '_subscribed_time', true);
            $package_length = get_post_meta($subscribed_package_id, 'fm_length', true);
            $is_never_expaired = get_post_meta($subscribed_package_id, 'fm_length_unl', true);
            $package_length = $package_length ? $package_length : '1';

            // Current time
            $start_date = !empty($subscribed_date) ? $subscribed_date : '';
            // Calculate new date
            $date = new DateTime($start_date);
            $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
            $expired_date = $date->format('Y-m-d H:i:s');
            $current_d = current_time('mysql');
            $remaining_days = ($expired_date > $current_d) ? (floor(strtotime($expired_date) / (60 * 60 * 24)) - floor(strtotime($current_d) / (60 * 60 * 24))) : 0; //calculate the number of days remaining in a plan

        }


        /**
         * Add selected order to the checkout form.
         *
         * @since     1.0.0
         * @access   public
         *
         * @param     array $data An array of selected package.
         * @param     integer $listing_id Listing ID.
         * @return     array      $data               Show the data of the packages.
         */

        public function atpp_checkout_form_data($data, $listing_id)
        {

            //if claim listing is active and admin decided to monitize without plan
            $claim_fee = get_post_meta($listing_id, '_claim_fee', true);
            $admin_calim = get_directorist_option('claim_charge_by');
            $admin_calim_charge = !empty($admin_calim) ? $admin_calim : '';
            $charge_by = !empty($claim_fee) ? $claim_fee : $admin_calim_charge;
            $is_climer = get_post_meta($listing_id, '_claimer_plans', true);
            if (class_exists('DCL_Base') && ('pricing_plan' !== $charge_by) && (('static_fee' === $charge_by) && empty($is_climer))) {
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
                    'name' => '',
                    'value' => 1,
                    'selected' => 1,
                    'title' => __('Claim for ', 'direo-extension') . $p_title,
                    'desc' => __('Claiming charge for this listing ', 'direo-extension'),
                    'price' => $fm_price
                );
                return $data;
            } else {
                $claimer_plans = get_post_meta($listing_id, '_claimer_plans', true);
                if (!empty($_POST['fm_plans_updated'])) {
                    $selected_plan_id = !empty($_POST['fm_plan_id_updated']) ? (int)$_POST['fm_plan_id_updated'] : '';
                    update_user_meta(get_current_user_id(), '_plan_to_active', $selected_plan_id);
                } elseif (!empty($claimer_plans)) {
                    $selected_plan_id = $claimer_plans;
                } else {
                    $selected_plan_id = get_post_meta($listing_id, '_fm_plans', true);
                }
                $p_title = get_the_title($selected_plan_id);
                $p_description = get_post_meta($selected_plan_id, 'fm_description', true);
                $fm_price = get_post_meta($selected_plan_id, 'fm_price', true);
                $data[] = array(
                    'type' => 'header',
                    'title' => $p_title
                );

                $data[] = array(
                    'type' => 'checkbox',
                    'name' => $selected_plan_id,
                    'value' => 1,
                    'selected' => 1,
                    'title' => $p_title,
                    'desc' => $p_description,
                    'price' => $fm_price,
                );
                return $data;
            }
        }
        /*@todo later need to update the receipt content with the purchased packages dynamically e.g. remove Gold package*/
        /**
         * Add data to the customer receipt after completing an order.
         *
         * @since     1.0.0
         * @access   public
         *
         * @param     array $receipt_data An array of selected package.
         * @param     integer $order_id Order ID.
         * @param     integer $listing_id Listing ID.
         * @return     array      $receipt_data               Show the data of the packages.
         */
        public function atpp_payment_receipt_data($receipt_data, $order_id, $listing_id)
        {
            //if claim listing is active and admin decided to monitize without plan
            $claim_fee = get_post_meta($listing_id[0], '_claim_fee', true);
            $admin_calim = get_directorist_option('claim_charge_by');
            $admin_calim_charge = !empty($admin_calim) ? $admin_calim : '';
            $charge_by = !empty($claim_fee) ? $claim_fee : $admin_calim_charge;
            $is_climer = get_post_meta($listing_id[0], '_claimer_plans', true);

            if (class_exists('DCL_Base') && ('pricing_plan' !== $charge_by) && (('static_fee' === $charge_by) && empty($is_climer))) {
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

            } else {
                $claimer_plans = get_post_meta($listing_id[0], '_claimer_plans', true);
                if (!empty($claimer_plans)) {
                    $selected_plan_id = $claimer_plans;
                } else {
                    $selected_plan_id = get_post_meta($listing_id[0], '_fm_plans', true);
                }

                $p_title = get_the_title($selected_plan_id);
                $p_description = get_post_meta($selected_plan_id, 'fm_description', true);
                $fm_price = get_post_meta($selected_plan_id, 'fm_price', true);
                $receipt_data = array(
                    'title' => $p_title,
                    'desc' => $p_description,
                    'price' => $fm_price,
                );
                return $receipt_data;
            }

        }

        /**
         * Add order details.
         *
         * @since     1.0.0
         * @access   public
         *
         * @param     array $order_details An array of containing order details.
         * @param     integer $order_id Order ID.
         * @param     integer $listing_id Listing ID.
         * @return     array      $order_details    Push additional package to the mail array.
         */
        public function atpp_order_details($order_details, $order_id, $listing_id)
        {
            //if claim listing is active and admin decided to monitize without plan
            $claim_fee = get_post_meta($listing_id, '_claim_fee', true);
            $admin_calim = get_directorist_option('claim_charge_by');
            $admin_calim_charge = !empty($admin_calim) ? $admin_calim : '';
            $charge_by = !empty($claim_fee) ? $claim_fee : $admin_calim_charge;
            $is_climer = get_post_meta($listing_id, '_claimer_plans', true);
            if (class_exists('DCL_Base') && ('pricing_plan' !== $charge_by) && (('static_fee' === $charge_by) && empty($is_climer))) {
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
            } else {
                $claimer_plans = get_post_meta($listing_id, '_claimer_plans', true);
                if (!empty($claimer_plans)) {
                    $selected_plan_id = $claimer_plans;
                } else {
                    $selected_plan_id = get_post_meta($listing_id, '_fm_plans', true);
                }
                $updated_plan_id = get_user_meta(get_current_user_id(), '_plan_to_active', true);
                $_plan_id = !empty($updated_plan_id) ? $updated_plan_id : $selected_plan_id;
                $p_title = get_the_title($_plan_id);
                $p_description = get_post_meta($_plan_id, 'fm_description', true);
                $fm_price = get_post_meta($_plan_id, 'fm_price', true);
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

        /**
         * Add data to the customer receipt after completing an order.
         *
         * @since     1.0.0
         * @access   public
         *
         * @param     array $order_items An array of selected package.
         * @param     integer $listing_id Listing ID.
         * @return     array      $order_items               Show the data of the packages.
         */
        public function atpp_order_items($order_items = null, $order_id = null, $listing_id = null, $data = null)
        {

            //if claim listing is active and admin decided to monitize without plan
            $claim_fee = get_post_meta($listing_id[0], '_claim_fee', true);
            $admin_calim = get_directorist_option('claim_charge_by');
            $admin_calim_charge = !empty($admin_calim) ? $admin_calim : '';
            $charge_by = !empty($claim_fee) ? $claim_fee : $admin_calim_charge;
            $is_climer = get_post_meta($listing_id[0], '_claimer_plans', true);
            if (class_exists('DCL_Base') && ('pricing_plan' !== $charge_by) && (('static_fee' === $charge_by) && empty($is_climer))) {
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
                    'title' => __('Claim for ', 'direo-extension') . $p_title,
                    'desc' => $p_description,
                    'price' => $fm_price,
                );
                return $order_items;
            } else {
                $claimer_plans = get_post_meta($listing_id[0], '_claimer_plans', true);
                if (!empty($claimer_plans)) {
                    return array();
                } else {
                    $selected_plan_id = get_post_meta($listing_id[0], '_fm_plans', true);
                }
                $updated_plan_id = get_user_meta(get_current_user_id(), '_plan_to_active', true);
                $plan_id = !empty($data['o_metas']['_fm_plan_ordered'][0]) ? $data['o_metas']['_fm_plan_ordered'][0] : '';
                $_plan_ids = !empty($selected_plan_id) ? $selected_plan_id : $updated_plan_id;
                $_plan_id = !empty($plan_id) ? $plan_id : $_plan_ids;
                $p_title = get_the_title($_plan_id);
                $p_description = get_post_meta($_plan_id, 'fm_description', true);
                $fm_price = get_post_meta($_plan_id, 'fm_price', true);
                $order_items[] = array(
                    'title' => $p_title,
                    'desc' => $p_description,
                    'price' => $fm_price,
                );
                return $order_items;
            }

        }


        public function atbdp_custom_order_column_content($column, $post_id, $listing_id)
        {
            switch ($column) {
                case 'active_plan' :
                    $value = get_post_meta($post_id, '_payment_status', true);
                    $gateway = get_post_meta($post_id, '_payment_gateway', true);
                    $selected_plan_id = get_post_meta($listing_id, '_fm_plans', true);
                    $plan_name = get_the_title($selected_plan_id);
                    //is the payment type is free submission then activate the plan
                    if ('free' === $gateway && 'completed' === $value) {
                        printf('<span style="color: green">%s</span> %s', __('Activated', 'direo-extension'), !empty($plan_name) ? '(' . $plan_name . ')' : '');
                    } //is the payment type is offline payment lets admin need to define the status manually .... we active the plan depending on admin command
                    elseif (('bank_transfer' === $gateway) && 'completed' === $value) {
                        if (get_post_status($listing_id) != 'publish') {
                            $package_length = get_post_meta($selected_plan_id, 'fm_length', true);
                            $fm_length_unl = get_post_meta($selected_plan_id, 'fm_length_unl', true);
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

                                //lets update the plan
                                $changed_plan_id = get_post_meta($listing_id, '_fm_changed_plans', true);
                                if (!empty($changed_plan_id)) {
                                    update_post_meta($listing_id, '_fm_plans', $changed_plan_id);
                                }

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
                                $my_post['post_status'] = 'publish';
                                wp_update_post($my_post);
                            }

                        }
                        //lets update the
                        printf('<span style="color: green">%s</span> %s', __('Activated', 'direo-extension'), !empty($plan_name) ? '(' . $plan_name . ')' : '');
                    } else if ((('paypal_gateway' === $gateway) || ('stripe_gateway' === $gateway)) && 'completed' === $value) {

                        printf('<span style="color: green">%s</span> %s', __('Activated', 'direo-extension'), !empty($plan_name) ? '(' . $plan_name . ')' : '');
                    } else {
                        printf('<span style="color: red">%s</span> %s', __('Not Activated', 'direo-extension'), !empty($plan_name) ? '(' . $plan_name . ')' : '');
                    }
                    break;
            }
        }


        public function atbdp_add_new_order_column($column)
        {
            $column['active_plan'] = __('Plan', 'direo-extension');
            return $column;
        }


        public function atpp_monetization_settings_controls($settings)
        {
            unset($settings['featured_listing_section']);
            unset($settings['monetize_by_subscription']);
            return $settings;
        }


        public function atpp_front_end_enqueue_scripts()
        {
            if (is_rtl()){
                wp_enqueue_style('atpp_main_css_rtl', ATPP_ASSETS . 'css/main-rtl.css', false, ATPP_VERSION);

            }else{
                wp_enqueue_style('atpp_main_css', ATPP_ASSETS . 'css/main.css', false, ATPP_VERSION);
            }
        }

        /*
         * Update selected plan ID to DB.
         *
         * @since	 1.0.0
         * @param	 string      $listing_id    Contain ID for the selected listing.
         */
        public function atpp_process_the_selected_plans($listing_id)
        {
            $user_id = get_current_user_id();
            $fm_plans = selected_plan_id();
            update_post_meta($listing_id, '_fm_plans', $fm_plans);
            //lets check is the plan already purchased
            $plan_purchased = subscribed_package_or_PPL_plans($user_id, 'completed', $fm_plans);
            $package_id = $fm_plans;
            //calculate the expair date
            $package_length = get_post_meta($package_id, 'fm_length', true);
            $fm_claim = get_post_meta($package_id, '_fm_claim', true);
            $package_length = $package_length ? $package_length : '1';
            $listing_type = !empty($_POST['listing_type']) ? sanitize_text_field($_POST['listing_type']) : '';
            // if the selected plan is package type
            if ('package' === package_or_PPL($plan = null)) {
                if ($plan_purchased) {
                    $order_id = $plan_purchased[0]->ID;
                    update_post_meta($listing_id, '_plan_order_id', $order_id);
                }
            }
            // Current time
            $current_d = current_time('mysql');
            // Calculate new date
            $date = new DateTime($current_d);
            $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
            $expired_date = $date->format('Y-m-d H:i:s');
            $is_never_expaired = get_post_meta($package_id, 'fm_length_unl', true);
            if ($is_never_expaired) {
                update_post_meta($listing_id, '_never_expire', '1');
            } else {
                update_post_meta($listing_id, '_expiry_date', $expired_date);
            }
            if ('featured' == $listing_type) {
                update_post_meta($listing_id, '_featured', '1');
            }
            if ('pay_per_listng' === package_or_PPL($plan = null)) {
                if (PPL_with_featured()) {
                    update_post_meta($listing_id, '_featured', '1');
                }
            }

            $order_id = !empty($plan_purchased) ? (int)$plan_purchased[0]->ID : '';
            $user_featured_listing = listings_data_with_plan($user_id, '1', $package_id, $order_id);
            $user_regular_listing = listings_data_with_plan($user_id, '0', $package_id, $order_id);
            $num_regular = get_post_meta($package_id, 'num_regular', true);
            $num_featured = get_post_meta($package_id, 'num_featured', true);
            if ($plan_purchased) {
                $listing_id = get_post_meta($plan_purchased[0]->ID, '_listing_id', true);
                $plan_type = package_or_PPL($package_id);
                $featured = get_post_meta($listing_id, '_featured', true);
                $total_regular_listing = $num_regular - ('0' === $featured ? $user_regular_listing + 1 : $user_regular_listing);
                $total_featured_listing = $num_featured - ('1' === $featured ? $user_featured_listing + 1 : $user_featured_listing);
                $subscribed_date = $plan_purchased[0]->post_date;
                $package_length = get_post_meta($package_id, 'fm_length', true);
                $regular_unl = get_post_meta($package_id, 'num_regular_unl', true);
                $featured_unl = get_post_meta($package_id, 'num_featured_unl', true);
                $package_length = $package_length ? $package_length : '1';
                // Current time
                $start_date = !empty($subscribed_date) ? $subscribed_date : '';
                // Calculate new date
                $date = new DateTime($start_date);
                $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                $expired_date = $date->format('Y-m-d H:i:s');
                $current_d = current_time('mysql');
                $remaining_days = ($expired_date > $current_d) ? (floor(strtotime($expired_date) / (60 * 60 * 24)) - floor(strtotime($current_d) / (60 * 60 * 24))) : 0; //calculate the number of days remaining in a plan
                if ((((0 >= $total_regular_listing) && empty($regular_unl)) && ((0 >= $total_featured_listing)) && empty($featured_unl)) || ($remaining_days <= 0)) {
                    //if user exit the plan allowance the change the status of that order to cancelled
                    $order_id = $plan_purchased[0]->ID;
                    if (('pay_per_listng' != $plan_type)) {
                        update_post_meta($order_id, '_payment_status', 'cancelled');
                    }
                }
            }

            // update the claim status
            if (!empty($fm_claim)) {
                update_post_meta($listing_id, '_claimed_by_admin', 1);
            }
            $data = array();
            //if user has not a valid plan ...lets redirect to checkout page
            //is the plan type is Pay Per Lisiting
            if ('pay_per_listng' === package_or_PPL($plan = null)) {
                if (get_directorist_option('enable_monetization')) {
                    $data['redirect_url'] = ATBDP_Permalink::get_checkout_page_link($listing_id);
                    $data['success'] = true;
                }
            } else {
                // package plan
                if (!$plan_purchased) { //not purchased yet redirect to checkout page to purchase
                    if (get_directorist_option('enable_monetization')) {
                        $data['redirect_url'] =ATBDP_Permalink::get_checkout_page_link($listing_id);
                        $data['success'] = true;
                    }
                }
            }
            return $data;
        }

        /**
         * Remove quick edit.
         *
         * @since     1.0.0
         * @access   public
         *
         * @param     array $actions An array of row action links.
         * @param     WP_Post $post The post object.
         * @return     array      $actions    Updated array of row action links.
         */
        public function atpp_remove_row_actions_for_quick_view($actions, $post)
        {

            global $current_screen;

            if ($current_screen->post_type != 'atbdp_pricing_plans') return $actions;

            unset($actions['view']);
            unset($actions['inline hide-if-no-js']);

            return $actions;

        }

        /**
         * Retrieve the table columns.
         *
         * @since    1.0.0
         * @access   public
         * @param    array $column all the column
         * @param    array $post_id post id
         */

        public function atpp_custom_field_column_content($column, $post_id)
        {
            echo '</select>';
            switch ($column) {
                case 'price' :
                    $value = esc_attr(get_post_meta($post_id, 'fm_price', true));
                    $value2 = esc_attr(get_post_meta($post_id, 'price_decimal', true));
                    echo atbdp_get_payment_currency() . ' ' . $value;
                    if ($value2) echo '.' . $value2;
                    break;
                case 'length' :
                    $value = esc_attr(get_post_meta($post_id, 'fm_length', true));
                    echo $value . ' days';
                    break;
                case 'num_listing' :
                    $value = esc_attr(get_post_meta($post_id, 'num_regular', true));
                    $value2 = esc_attr(get_post_meta($post_id, 'num_featured', true));
                    echo 'Regular-' . $value . '<br>';
                    echo 'Featured-' . $value2;
                    break;
                case 'price_range' :
                    $value = esc_attr(get_post_meta($post_id, 'price_range', true));
                    echo 'Until-' . atbdp_get_payment_currency() . ' ' . $value;
                    break;
                case 'plan_type' :
                    $value = esc_attr(get_post_meta($post_id, 'plan_type', true));

                    echo ('package' === $value) ? __('Package', 'direo-extension') : __('Pay Per Listing', 'direo-extension');
                    break;
                case 'business_time' :
                    $value = esc_attr(get_post_meta($post_id, 'business_hrs', true));
                    echo '<span class="atbdp-tick-cross2">' . ($value ? '<span style="color: #4caf50;">&#x2713;</span>' : '<span style="color: red;">&#x2717;</span>') . '</span>';
                    break;
                case 'image' :
                    $value = esc_attr(get_post_meta($post_id, 'num_image', true));
                    echo $value . ' image(s)';
                    break;
                case 'web-link' :
                    $value = esc_attr(get_post_meta($post_id, 'fm_web_link', true));
                    echo '<span class="atbdp-tick-cross2">' . ($value ? '<span style="color: #4caf50;">&#x2713;</span>' : '<span style="color: red;">&#x2717;</span>') . '</span>';
                    break;
                /*case 'coupon' :
                    $value = esc_attr(get_post_meta( $post_id, 'fm_allow_price_range', true ));
                    echo '<span class="atbdp-tick-cross2">'.($value ? '<span style="color: #4caf50;">&#x2713;</span>' : '<span style="color: red;">&#x2717;</span>').'</span>';
                    break;*/

            }
        }


        /**
         * Retrieve the table columns.
         *
         * @since    1.0.0
         * @access   public
         * @param    array $columns
         *
         * @return   array    $columns    Array of all the list table columns.
         */
        public function atpp_add_new_plan_columns($columns)
        {

            $columns = array(
                'cb' => '<input type="checkbox" />', // Render a checkbox instead of text
                'title' => __('Title', 'direo-extension'),
                'price' => __('Price', 'direo-extension'),
                'length' => __('Length', 'direo-extension'),
                'num_listing' => __('Listing', 'direo-extension'),
                'price_range' => __('Price Range', 'direo-extension'),
                'plan_type' => __('Plan Type', 'direo-extension'),
                'business_time' => __('Business Time', 'direo-extension'),
                'image' => __('Image', 'direo-extension'),
                'web-link' => __('Web-link', 'direo-extension'),
                //'coupon'         => __( 'Coupon', 'direo-extension' ),
                'date' => __('Date', 'direo-extension')

            );

            return $columns;

        }


        /**
         * Register meta boxes for Pricing Plans.
         *
         * @since    1.0.0
         * @access   public
         */
        public function atpp_add_meta_boxes()
        {

            remove_meta_box('atbdp-plan-details', 'atbdp_pricing_plans', 'normal');
            remove_meta_box('slugdiv', 'atbdp_pricing_plans', 'normal');


            add_meta_box('atbdp-plan-details', __('Plan Details', 'direo-extension'), array($this, 'atbdp_meta_box_plan_details'), 'atbdp_pricing_plans', 'normal', 'high');
        }

        public function atbdp_meta_box_plan_details($post)
        {

            // Add a nonce field so we can check for it later
            wp_nonce_field('atbdp_save_fee_details', 'atbdp_fee_details_nonce');
            /**
             * Display the "Field Details" meta box.
             */
            $this->load_template('admin-meta-fields');

        }


        /*
            * save data to database from the metaboxes
            */

        public function atpp_save_meta_data($post_id)
        {

            /*
             * save all the metadata to option table
             */
            include(ATPP_INC_DIR . 'class-db.php');

        }


        /**
         * Register a custom post type "atbdp_pricing_plans".
         *
         * @since    3.1.0
         * @access   public
         */
        public function register_custom_post_type_for_FM()
        {

            $labels = array(
                'name' => _x('Pricing Plans', 'Post Type General Name', 'direo-extension'),
                'singular_name' => _x('Pricing Plans', 'Post Type Singular Name', 'direo-extension'),
                'menu_name' => __('Pricing Plans', 'direo-extension'),
                'name_admin_bar' => __('Pricing Plan', 'direo-extension'),
                'all_items' => __('Pricing Plans', 'direo-extension'),
                'add_new_item' => __('Add New Plan', 'direo-extension'),
                'add_new' => __('Add New Plan', 'direo-extension'),
                'new_item' => __('New Plan', 'direo-extension'),
                'edit_item' => __('Edit Plan', 'direo-extension'),
                'update_item' => __('Update Plan', 'direo-extension'),
                'view_item' => __('View Plan', 'direo-extension'),
                'search_items' => __('Search Plan', 'direo-extension'),
                'not_found' => __('No Plan found', 'direo-extension'),
                'not_found_in_trash' => __('No Plan found in Trash', 'direo-extension'),
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

            register_post_type('atbdp_pricing_plans', $args);

        }


        /**
         * It adds custom settings field of Directorist Pricing Plans to the General Settings Sections Under the Extension menu
         * of Directorist.
         * @param $fields array
         * @return array
         */
        public function add_settings_for_fee_manager($fields)
        {
            $permission = array(
                'type' => 'toggle',
                'name' => 'fee_manager_enable',
                'label' => __('Pricing Plans', 'direo-extension'),
                'description' => __('You can disable it for users.', 'direo-extension'),
                'default' => 1

            );

            // lets push our settings to the end of the other settings field and return it.
            array_push($fields, $permission);
            return $fields;
        }

        /**
         * @since 1.5.1
         */
        public function plan_settings_to_ext_general_fields($settings_submenus)
        {
            /*lets add a submenu of our extension*/
            $settings_submenus[] = array(
                'title' => __('Pricing Plans', 'direo-extension'),
                'name' => 'atpp_plan',
                'icon' => 'font-awesome:fa-money',
                'controls' => array(
                    'general_section' => array(
                        'type' => 'section',
                        'title' => __('Pricing Plans Settings', 'direo-extension'),
                        'description' => __('You can Customize all the settings of Pricing Plans Extension here', 'direo-extension'),
                        'fields' => array(
                            array(
                                'type' => 'toggle',
                                'name' => 'skip_plan_page',
                                'label' => __('Skip Plan Page for Paid Users', 'direo-extension'),
                            ),
                        ),// ends fields array
                    ), // ends general section array
                ), // ends controls array that holds an array of sections
            );
            return $settings_submenus;
        }


        public function plan_pages_settings_fields($fields)
        {

            $permission = array(
                'type' => 'select',
                'name' => 'pricing_plans',
                'label' => __('Pricing Plans Page', 'direo-extension'),
                'items' => $this->get_pages_vl_arrays(), // eg. array( array('value'=> 123, 'label'=> 'page_name') );
                'description' => sprintf(__('Following shortcode must be in the selected page %s', 'direo-extension'), '<strong style="color: #ff4500;">[directorist_pricing_plans]</strong>'),
                'default' => atbdp_get_option('pricing_plans', 'atbdp_general'),
                'validation' => 'numeric',

            );
            // lets push our settings to the end of the other settings field and return it.
            array_push($fields, $permission);
            return $fields;
        }

        function get_pages_vl_arrays()
        {
            $pages = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[] = array('value' => $page->ID, 'label' => $page->post_title);
                }
            }

            return $pages_options;
        }


        /**
         * It  loads a template file from the Default template directory.
         * @param string $name Name of the file that should be loaded from the template directory.
         * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
         */
        public function load_template($name, $args = array())
        {
            global $post;
            include(ATPP_TEMPLATES_DIR . $name . '.php');
        }

        /**
         * It register the text domain to the WordPress
         */
        public function load_textdomain()
        {
            load_plugin_textdomain('direo-extension', false, ATPP_LANG_DIR);
        }

        /**
         * It Includes and requires necessary files.
         *
         * @access private
         * @since 1.0
         * @return void
         */
        private function includes()
        {
            require_once ATPP_INC_DIR . 'helper-functions.php';
            require_once ATPP_INC_DIR . 'class-enqueuer.php';
            require_once ATPP_INC_DIR . 'validator.php';
        }


        /**
         * Setup plugin constants.
         *
         * @access private
         * @since 1.0
         * @return void
         */
        private function setup_constants()
        {
            require_once plugin_dir_path(__FILE__) . '/config.php'; // loads constant from a file so that it can be available on all files.
        }
    }
}
/**
 * The main function for that returns ATBDP_Pricing_Plans
 *
 * The main function responsible for returning the one true ATBDP_Pricing_Plans
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @since 1.0
 * @return object|ATBDP_Pricing_Plans The one true ATBDP_Pricing_Plans Instance.
 */
function ATBDP_Pricing_Plans(){
    return ATBDP_Pricing_Plans::instance();
}


ATBDP_Pricing_Plans(); // get the plugin running
