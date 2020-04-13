<?php
// prevent direct access to the file
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
$direo_monetize_by = 'none';
if (class_exists('Directorist_Base')){
    $direo_monetize_by = get_directorist_option('direo_monetize_by','pricing_plan');
    $enable_monetization = get_directorist_option('enable_monetization',0);
}
if (empty($enable_monetization) || (('woo_pricing_plan' !== $direo_monetize_by) || ('none' === $direo_monetize_by))){
    return;
}else {
    final class DWPP_Pricing_Plans
    {


        /** Singleton *************************************************************/

        /**
         * @var DWPP_Pricing_Plans The one true DWPP_Pricing_Plans
         * @since 1.0
         */
        private static $instance;

        private static $plan_id;

        /**
         * Main DWPP_Pricing_Plans Instance.
         *
         * Insures that only one instance of DWPP_Pricing_Plans exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @return object|DWPP_Pricing_Plans The one true DWPP_Pricing_Plans
         * @uses DWPP_Pricing_Plans::setup_constants() Setup the constants needed.
         * @uses DWPP_Pricing_Plans::includes() Include the required files.
         * @uses DWPP_Pricing_Plans::load_textdomain() load the language files.
         * @see  DWPP_Pricing_Plans()
         * @since 1.0
         * @static
         * @static_var array $instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof DWPP_Pricing_Plans)) {
                self::$instance = new DWPP_Pricing_Plans;
                self::$instance->setup_constants();

                //add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
                add_action('wp_enqueue_scripts', array(self::$instance, 'register_necessary_scripts'));
                add_action('admin_enqueue_scripts', array(self::$instance, 'admin_register_necessary_scripts'));


                self::$instance->includes();
                new DWPP_Enqueuer();// enqueue required styles and scripts
                //admin notice for activating the woocommerce main plugin first
                add_action('init', array(self::$instance, 'dwpp_load_classes'), 9);
                add_filter('atbdp_pages_settings_fields', array(self::$instance, 'plan_pages_settings_fields'));
                add_filter('atbdp_extension_settings_submenus', array(self::$instance, 'plan_settings_to_ext_general_fields'));
                add_filter('atbdp_monetization_settings_controls', array(self::$instance, 'dwpp_monetization_settings_controls'));
                add_shortcode('directorist_pricing_plans', array(self::$instance, 'directorist_fee_plane_page'));
                add_filter('product_type_selector', array(self::$instance, 'dwpp_register_product_type'));
                add_filter('woocommerce_product_class', array(self::$instance, 'dwpp_woocommerce_product_class'), 10, 2);
                add_action('woocommerce_product_options_general_product_data', array(self::$instance, 'dwpp_display_custom_fields'));
                add_action('woocommerce_process_product_meta', array(self::$instance, 'dwpp_woocommerce_process_product_meta'), 11, 2);
                add_filter('woocommerce_product_data_tabs', array(self::$instance, 'dwpp_woocommerce_product_data_tabs'), 2);
                add_filter('atbdp_extension_settings_fields', array(self::$instance, 'add_settings_for_woo_pricing_plans'));
                add_action('woocommerce_checkout_order_processed', array(self::$instance, 'dwpp_checkout_order_processed'));
                add_action('woocommerce_order_status_completed', array(self::$instance, 'dwpp_order_status_completed'));
                add_action('woocommerce_single_product_summary', array(self::$instance, 'dwpp_single_product_summary'), 60);
                add_filter('woocommerce_get_price_html', array(self::$instance, 'dwpp_change_product_price_display'));
                add_filter('woocommerce_cart_item_price', array(self::$instance, 'dwpp_change_product_price_display'));

                add_action('woocommerce_after_order_notes', array(self::$instance, 'checkout_field'));

                add_action('atbdp_listing_form_after_add_listing_title', array(self::$instance, 'dwpp_type_of_listing'));

                //update necessary listing meta with the plan
                add_action('atbdp_before_processing_listing_frontend', array(self::$instance, 'dwpp_process_the_selected_plans'));

                //add new listing column and content
                add_filter('atbdp_add_new_listing_column', array(self::$instance, 'dwpp_add_new_listing_column'));
                add_filter('atbdp_add_new_listing_column_content', array(self::$instance, 'dwpp_add_new_listing_column_content'), 10, 2);
                add_action('atbdp_tab_after_favorite_listings', array(self::$instance, 'dwpp_tab_after_favorite_listings'));
                add_action('atbdp_tab_content_after_favorite', array(self::$instance, 'dwpp_tab_content_after_favorite'));

                add_action('atbdp_after_video_metabox_backend_add_listing', array(self::$instance, 'dwpp_admin_metabox'));
                add_action('edit_post', array(self::$instance, 'dwpp_save_metabox'), 13, 2);

                //Changing plan
                add_action('atbdp_user_dashboard_listings_before_expiration', array(self::$instance, 'dwpp_plan_change'));

                add_action('wp_footer', array(self::$instance, 'dwpp_plan_change_modal'));

                add_action('wp_ajax_dwpp_submit_changing_plan', array(self::$instance, 'dwpp_submit_changing_plan'));
                add_action('wp_ajax_nopriv_dwpp_submit_changing_plan', array(self::$instance, 'dwpp_submit_changing_plan'));

                //Renew plan
                add_action('atbdp_before_renewal', array(self::$instance, 'dwpp_before_renewal'));

                add_filter('atbdp_dashboard_field_setting', array(self::$instance, 'dwpp_settings_to_ext_general_fields'));
                if (get_option('atbdp_plan_page_create') < 1) {
                    add_action('wp_loaded', array(self::$instance, 'add_custom_page'));
                }
                add_action('atbdp_before_pricing_plan_page_load', array(self::$instance, 'atbdp_add_listing_page_url'));

            }

            return self::$instance;
        }

        /**
         * @since 1.2.1
         */
        public function atbdp_add_listing_page_url()
        {
            $skip_plans = get_directorist_option('skip_plan_page', 0);
            if (empty($skip_plans)) return; // void if admin not to decide show plan page for active packaged user
            $user_id = get_current_user_id();
            $args = array(
                'post_type' => 'shop_order',
                'post_status' => "wc-completed",
                'numberposts' => -1,
                'meta_key' => '_customer_user',
                'meta_value' => $user_id,
                'compare' => '='
            );
            $orders = new WP_Query($args);
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
                dwpp_create_required_pages();
            }
        }

        /**
         * @since 1.1.9
         */

        public function dwpp_order_status_completed($order_id)
        {

            $order = wc_get_order($order_id);
            $items = $order->get_items();

            foreach ($items as $key => $item) {

                $plan_id = $item['product_id'];
                $product = wc_get_product($plan_id);

                if ($product->is_type('listing_pricing_plans')) {
                    $user_id = get_current_user_id();
                    dwpp_need_listing_to_refresh($user_id, $order_id, $plan_id);

                }

            }

        }


        /**
         * Add a hidden field (listing_id) in the WooCommerce checkout page.
         *
         * @param array $checkout WooCommerce checkout page values.
         * @since     1.0.0
         * @access    public
         *
         */
        public function checkout_field($checkout)
        {

            $listing_id = isset($_GET['atbdp_listing_id']) ? $_GET['atbdp_listing_id'] : '';

            if (!empty($listing_id)) {

                echo '<div id="atbdp_listing_id_checkout_field">';
                echo '<input type="hidden" name="listing_id" value="' . $listing_id . '">';
                echo '</div>';

            }

        }


        /**
         * @since 1.1.2
         */
        public function dwpp_settings_to_ext_general_fields($settings_submenus)
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
            $setting3 = array(
                'type' => 'toggle',
                'name' => 'change_plan',
                'label' => __('Display Plan Change Link', 'direo-extension'),
                'default' => 1,
            );

            array_push($settings_submenus, $setting1, $setting2, $setting3);
            return $settings_submenus;
        }


        /**
         * @since 1.3.2
         */

        public function dwpp_before_renewal($listing_id)
        {

            update_post_meta($listing_id, '_renew_with_plan', 1);
            $plan_id = get_post_meta($listing_id, '_fm_plans', true);
            global $woocommerce;
            $woocommerce->cart->empty_cart();
            $woocommerce->cart->add_to_cart($plan_id);
            wp_safe_redirect(add_query_arg('atbdp_listing_id', $listing_id, wc_get_checkout_url()));
            exit;

        }

        /**
         * @since 1.3.2
         */

        public function dwpp_submit_changing_plan()
        {
            $data = array('error' => 0);
            $plan_id = isset($_POST["plan_id"]) ? (int)($_POST["plan_id"]) : '';
            $listing_id = (int)$_POST["post_id"];
            if (dwpp_need_to_charge_with_plan()) {
                update_post_meta($listing_id, '_fm_plans', $plan_id);
                $data['message'] = __('Plan changed successfully!', 'direo-extension');
                $data['renew_info'] = __('Listing renewed successfully!', 'direo-extension');
            } else {
                update_post_meta($listing_id, '_fm_plans', $plan_id);
                global $woocommerce;
                $woocommerce->cart->empty_cart();
                $woocommerce->cart->add_to_cart($plan_id);
                $url = add_query_arg('atbdp_listing_id', $listing_id, wc_get_checkout_url());
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
        public function dwpp_plan_change($listing_id)
        {
            $plan_id = get_post_meta($listing_id, '_fm_plans', true);
            $change_plan = get_directorist_option('change_plan', 1);
            $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id);
            $change_plan_link = apply_filters('atbdp_plan_change_link_in_user_dashboard', '<span><a data-target="' . $modal_id . '" class="dwpp_change_plan" data-listing_id="' . $listing_id . '" href="javascript:void(0)">' . __('Change', 'direo-extension') . '</a></span>', $listing_id);

            $plan_name = !empty($plan_id) ? get_the_title($plan_id) : __('No Plan!', 'direo-extension');
            printf(__('<p><span>Plan Name:</span> %s %s</p>', 'direo-extension'), $plan_name, !empty($change_plan) ? $change_plan_link : '');
        }

        /**
         * @since 1.1.6
         */
        public function dwpp_plan_change_modal()
        {
            ?>
            <div class="at-modal atm-fade" id="atpp-plan-change-modal">
                <div class="at-modal-content at-modal-lg">
                    <div class="atm-contents-inner">
                        <a href="" class="at-modal-close">
                            <span aria-hidden="true">×</span>
                        </a>
                        <div class="align-items-center">
                            <div class="">
                                <form id="dwpp-change-plan-form" class="form-vertical" role="form">
                                    <div class="atbd_modal-header">
                                        <input type="hidden" value="" id="change_listing_id">
                                        <h3 class="atbd_modal-title"
                                            id="dwpp-plan-label"><?php _e('Change Pricing Plan', 'direo-extension'); ?></h3>
                                        <?php
                                        $link = '<a href="' . ATBDP_Permalink::get_fee_plan_page_link() . '" target="_blank">' . __('Click Here', 'direo-extension') . '</a>';
                                        printf('<p>%s %s</p>', __('We recommend you check the details of Pricing Plans before changing.', 'direo-extension'), $link)
                                        ?>
                                    </div>
                                    <div class="atbd_modal-body">
                                        <div class="dcl_pricing_plan">
                                            <?php
                                            global $product;
                                            $meta_queries = array();
                                            $query_args = array(
                                                'post_type' => 'product',
                                                'tax_query' => array(
                                                    array(
                                                        'taxonomy' => 'product_type',
                                                        'field' => 'slug',
                                                        'terms' => 'listing_pricing_plans',
                                                    ),
                                                ),
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
                                                $query_args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
                                            }

                                            $atbdp_query = new WP_Query($query_args);

                                            if ($atbdp_query->have_posts()) {
                                                global $post;
                                                $plans = $atbdp_query->posts;
                                                printf('<label for="select_plans">%s</label><hr>', __('Select Plan', 'direo-extension'));
                                                foreach ($plans as $key => $value) {
                                                    $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $value->ID);
                                                    $plan_metas = get_post_meta($value->ID);
                                                    $unl = __('Unlimited', 'direo-extension');
                                                    $plan_type = esc_attr($plan_metas['plan_type'][0]);
                                                    $fm_price = !empty($plan_metas['_sale_price'][0]) ? esc_attr($plan_metas['_sale_price'][0]) : '';
                                                    $fm_length = esc_attr($plan_metas['fm_length'][0]);
                                                    $fm_length_unl = esc_attr($plan_metas['fm_length_unl'][0]);
                                                    $num_regular = esc_attr($plan_metas['num_regular'][0]);
                                                    $num_regular_unl = esc_attr($plan_metas['num_regular_unl'][0]);
                                                    $num_featured = esc_attr($plan_metas['num_featured'][0]);
                                                    $num_featured_unl = esc_attr($plan_metas['num_featured_unl'][0]);
                                                    $regular = (empty($num_regular_unl) ? $num_regular : $unl) . __(' regular', 'direo-extension');
                                                    $featured = (empty($num_featured_unl) ? $num_featured : $unl) . __(' featured listings', 'direo-extension');
                                                    $allowances = sprintf('<p class="atbd_plan_core_features"><span class="apc_price">%s</span><span>%s%s</span><span>%s</span><span>%s & %s</span></p>', atbdp_get_payment_currency() . $fm_price, empty($fm_length_unl) ? $fm_length : $unl, __(' days', 'direo-extension'), ($plan_type === 'package' ? __('Package', 'direo-extension') : __('Pay Per Listing', 'direo-extension')), $regular, $featured);
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
                                        <span><i class="fa fa-lock"></i> <?php esc_html_e('Secure Payment Process', 'direo-extension'); ?></span>
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
         * @since 1.2.0
         */
        public function dwpp_save_metabox($post_id, $post)
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
         * @since 1.2.0
         */

        public function dwpp_admin_metabox()
        {
            if (!get_directorist_option('fee_manager_enable', 1)) return; // vail if the business hour is not enabled
            add_meta_box('_listing_admin_plan',
                __('Belongs to Plan', 'direo-extension'),
                array($this, 'dwpp_admin_plan'),
                ATBDP_POST_TYPE,
                'side', 'high');
        }

        /**
         * @since 1.2.0
         */
        public function dwpp_admin_plan($post)
        {
            $current_val = get_post_meta($post->ID, '_fm_plans', true);
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
            );

            $atbdp_query = new WP_Query($query_args);

            if ($atbdp_query->have_posts()) {
                global $post;
                $plans = $atbdp_query->posts;
                printf('<label for="select_plans">%s</label>', __('Select Plan', 'direo-extension'));
                printf('<select name="admin_plan">');
                echo '<option value="null">' . __('- Select -', 'direo-extension') . '</option>';
                foreach ($plans as $key => $value) {
                    $class = apply_filters('atbdp_admin_plan_select_option_class', 'listing_plan', $value->ID);
                    printf('<option class="%s" value="%s" %s>%s</option>', $class, $value->ID, selected($value->ID, $current_val), $value->post_title);
                }
                printf('</select>');
                printf('<a target="_blank" href="%s" class="atpp_plans">%s</a>', esc_url(ATBDP_Permalink::get_fee_plan_page_link()), __('Details', 'direo-extension'));
            }
        }


        public function dwpp_monetization_settings_controls($settings)
        {
            unset($settings['featured_listing_section']);
            unset($settings['monetize_by_subscription']);
            return $settings;
        }


        public function dwpp_change_product_price_display($price)
        {
            global $product;
            if ($product instanceof WC_Product && $product->is_type('listing_pricing_plans')) {
                $plan_type = get_post_meta(get_the_ID(), 'plan_type', true);
                if ('package' === $plan_type) {
                    $price .= __('/per package', 'direo-extension');
                    return $price;
                } else {
                    $price .= __('/per listing', 'direo-extension');
                    return $price;
                }
            } else {
                return $price;
            }

        }

        public function dwpp_single_product_summary($product_id)
        {
            global $product;
            if ($product instanceof WC_Product && $product->is_type('listing_pricing_plans')) {

                $plan_id = get_the_ID();
                $plan_metas = get_post_meta($plan_id);
                $unl = __('Unlimited', 'direo-extension');
                $fm_length = esc_attr($plan_metas['fm_length'][0]);
                $fm_length_unl = esc_attr($plan_metas['fm_length_unl'][0]);
                $num_regular = esc_attr($plan_metas['num_regular'][0]);
                $num_regular_unl = esc_attr($plan_metas['num_regular_unl'][0]);
                $num_featured = esc_attr($plan_metas['num_featured'][0]);
                $num_featured_unl = esc_attr($plan_metas['num_featured_unl'][0]);
                $price_range = esc_attr($plan_metas['price_range'][0]);
                $price_range_unl = esc_attr($plan_metas['price_range_unl'][0]);
                $num_image = esc_attr($plan_metas['num_image'][0]);
                $num_image_unl = esc_attr($plan_metas['num_image_unl'][0]);
                $num_gallery_image = !empty($plan_metas['num_gallery_image'][0]) ? esc_attr($plan_metas['num_gallery_image'][0]) : '';
                $num_gallery_image_unl = !empty($plan_metas['num_gallery_image_unl'][0]) ? esc_attr($plan_metas['num_gallery_image_unl'][0]) : '';
                $fm_tag_limit = esc_attr($plan_metas['fm_tag_limit'][0]);
                $fm_tag_limit_unl = esc_attr($plan_metas['fm_tag_limit_unl'][0]);
                $business_hrs = esc_attr($plan_metas['business_hrs'][0]);
                $l_video = esc_attr($plan_metas['l_video'][0]);
                $cf_owner = esc_attr($plan_metas['cf_owner'][0]);
                $fm_email = esc_attr($plan_metas['fm_email'][0]);
                $fm_phone = esc_attr($plan_metas['fm_phone'][0]);
                $fm_web_link = esc_attr($plan_metas['fm_web_link'][0]);
                $fm_social_network = esc_attr($plan_metas['fm_social_network'][0]);
                $fm_cs_review = esc_attr($plan_metas['fm_cs_review'][0]);
                $fm_listing_faq = esc_attr($plan_metas['fm_listing_faq'][0]);
                $fm_custom_field = esc_attr($plan_metas['fm_custom_field'][0]);
                $fm_claim = esc_attr($plan_metas['_fm_claim'][0]);
                $fm_allow_price_range = esc_attr($plan_metas['fm_allow_price_range'][0]);
                ?>
                <div class="atbd_woo_plans_product">
                    <div class="pricing pricing--1 <?php echo !empty($plan_metas['default_pln'][0]) ? 'atbd_pricing_special' : ''; ?>">
                        <?php echo !empty($plan_metas['default_pln'][0]) ? __(' <span class="atbd_popular_badge">Recommended</span>', 'direo-extension') : ''; ?>

                        <div class="pricing__features">
                            <ul>
                                <li><span class="fa fa-<?php if (($fm_length > 0) || $fm_length_unl) {
                                        echo 'check';
                                    } else {
                                        echo 'times';
                                    } ?>"></span><?php echo $fm_length_unl ? '<span class="atbd_color-success">' . _e('No', 'direo-extension') . '</span>' : $fm_length; ?><?php _e(' Days Limit', 'direo-extension') ?>
                                </li>
                                <?php if (($plan_metas['plan_type'][0] == 'pay_per_listng') && empty($plan_metas['_dwpp_hide_listing_featured'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($plan_metas['is_featured_listing'][0]) ? 'check' : 'times'; ?>"> </span><?php _e('Listing as featured', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if ($plan_metas['plan_type'][0] != 'pay_per_listng') { ?>
                                    <?php if (empty($plan_metas['_dwpp_hide_listings'][0])) { ?>
                                        <li><span class="fa fa-<?php if (($num_regular > 0) || $num_regular_unl) {
                                                echo 'check';
                                            } else {
                                                echo 'times';
                                            } ?>"></span><?php echo $num_regular_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __(' Regular Listings', 'direo-extension') . '' : $num_regular . __(' Regular Listings', 'direo-extension'); ?>
                                        </li>
                                    <?php }
                                    if (empty($plan_metas['_dwpp_hide_featured'][0])) { ?>
                                        <li><span class="fa fa-<?php if (($num_featured > 0) || $num_featured_unl) {
                                                echo 'check';
                                            } else {
                                                echo 'times';
                                            } ?>"></span><?php echo $num_featured_unl ? '<span class="atbd_color-success">' . $unl . '</span>' : $num_featured . __(' Featured Listings', 'direo-extension'); ?>
                                        </li>
                                    <?php }
                                }
                                if (empty($plan_metas['_dwpp_hide_price'][0])) { ?>
                                    <li><span class="fa fa-<?php if (($price_range > 0) || $price_range_unl) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"></span><?php echo $price_range_unl ? '<span class="atbd_color-success">' . _e('No', 'direo-extension') . '</span>' : $price_range; ?><?php _e(' Price Limit', 'direo-extension') ?>
                                    </li>

                                <?php }
                                if (empty($plan_metas['_dwpp_hide_price_range'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_allow_price_range) ? 'check' : 'times'; ?>"> </span><?php _e('Average Price Range', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_image'][0])) { ?>
                                    <li><span class="fa fa-<?php if (($num_image > 0) || $num_image_unl) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"></span><?php echo $num_image_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __(' Listing Image', 'direo-extension') . '' : $num_image . __(' Listing Image ', 'direo-extension'); ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_tag'][0])) { ?>
                                    <li><span class="fa fa-<?php if (($fm_tag_limit > 0) || $fm_tag_limit_unl) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"></span><?php echo $fm_tag_limit_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __('   Tags limit', 'direo-extension') . '' : $fm_tag_limit . __('   Tags limit', 'direo-extension'); ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_business_hours'][0])) { ?>
                                    <?php
                                    if (class_exists('BD_Business_Hour')) {
                                        ?>
                                        <li>
                                            <span class="fa fa-<?php echo !empty($business_hrs) ? 'check' : 'times'; ?>"> </span><?php _e('Business Hours', 'direo-extension') ?>
                                        </li>
                                    <?php } ?>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_image_gallery'][0])) { ?>
                                    <li><span class="fa fa-<?php if (($num_gallery_image > 0) || $num_gallery_image_unl) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"></span><?php echo $num_gallery_image_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __(' Gallery Image Limit', 'direo-extension') . '' : $num_gallery_image . __(' Gallery Image Limit', 'direo-extension'); ?>
                                    </li>

                                <?php }
                                if (empty($plan_metas['_dwpp_hide_video'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($l_video) ? 'check' : 'times'; ?>"> </span><?php _e('Video', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_cl_owner'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($cf_owner) ? 'check' : 'times'; ?>"> </span><?php _e('Contact Owner', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_email'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_email) ? 'check' : 'times'; ?>"> </span><?php _e('Show Email', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_phone'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_phone) ? 'check' : 'times'; ?>"> </span><?php _e('Show Contact Number', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_web_link'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_web_link) ? 'check' : 'times'; ?>"> </span><?php _e('Show Web Link', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_sm_link'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_social_network) ? 'check' : 'times'; ?>"> </span><?php _e('Show Social Network', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_customer_review'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_cs_review) ? 'check' : 'times'; ?>"> </span><?php _e('Allow Customer Review', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_faqs'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_listing_faq) ? 'check' : 'times'; ?>"> </span><?php _e('Listing FAQs', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_category'][0])) {
                                    $is_cat = selected_plan_meta($plan_id, 'exclude_cat');
                                    ?>
                                    <li><span class="fa fa-<?php if (empty($is_cat)) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"> </span><?php _e('All Categories', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_custom_fields'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_custom_field) ? 'check' : 'times'; ?>"> </span><?php _e('Custom Fields', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['_dwpp_hide_claim'][0])) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_claim) ? 'check' : 'times'; ?>"> </span><?php _e('Claim Badge Included', 'direo-extension') ?>
                                    </li>
                                <?php } ?>
                                <?php
                                /*
                                 * @since 1.0.0
                                 * Fires in plan compare page
                                 * hook for future dev
                                 */
                                do_action('atfm_fee_plan_after_custom_field');
                                ?>

                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            }
        }


        /**
         * Instantiate classes when woocommerce is activated
         */
        public function dwpp_load_classes()
        {
            if (!class_exists('WooCommerce')) {
                add_action('admin_notices', array($this, 'need_woocommerce'));
                return;
            }
            if (!class_exists('Directorist_Base')) {
                add_action('admin_notices', array($this, 'need_directorist'));
                return;
            }
        }

        /**
         * WooCommerce not active notice.
         *
         * @return string Fallack notice.
         */

        public function need_directorist()
        {
            $error = sprintf(__('Directorist - WooCommerce Pricing Plans requires %sDirectorist%s to be installed & activated!', 'direo-extension'), '<a target="_blank" href="http://wordpress.org/extend/plugins/directorist/">', '</a>');

            $message = '<div class="error notice is-dismissible"><p>' . $error . '</p></div>';
            //deactivate_plugins(plugin_basename( __FILE__ ));

            echo $message;
        }


        /**
         * WooCommerce not active notice.
         *
         * @return string Fallack notice.
         */

        public function need_woocommerce()
        {
            $error = sprintf(__('Directorist - WooCommerce Pricing Plans requires %sWooCommerce%s to be installed & activated!', 'direo-extension'), '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>');

            $message = '<div class="error notice is-dismissible"><p>' . $error . '</p></div>';
            //deactivate_plugins(plugin_basename( __FILE__ ));

            echo $message;
        }


        public function directorist_fee_plane_page($atts)
        {
            ob_start();
            $this->load_template('fee-plans', array('atts' => $atts));
            return ob_get_clean();
        }

        /**
         * @since 1.2.1
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
                                'default' => 0,
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


        public function dwpp_type_of_listing($listing_info)
        {
            if (!is_fee_manager_active()) return false; //void if admin deactivated plan from settings panel
            $plan_support = is_plan_allowed_featured_listing();
            $user_id = get_current_user_id();
            $subscribed_package_id = selected_plan_id();
            $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $subscribed_package_id);
            $order_id = !empty($active_plan) ? (int)$active_plan->ID : '';
            $user_featured_listing = listings_data_with_plan($user_id, '1', $subscribed_package_id, $order_id);
            $user_regular_listing = listings_data_with_plan($user_id, '0', $subscribed_package_id, $order_id);
            $num_regular = get_post_meta($subscribed_package_id, 'num_regular', true);
            $num_featured = get_post_meta($subscribed_package_id, 'num_featured', true);
            $num_featured_unl = get_post_meta($subscribed_package_id, 'num_featured_unl', true);
            $num_regular_unl = get_post_meta($subscribed_package_id, 'num_regular_unl', true);
            $total_regular_listing = $num_regular - $user_regular_listing;
            $total_featured_listing = $num_featured - $user_featured_listing;
            if ((!$plan_support) || (package_or_PPL($plan = null) === 'pay_per_listng')) return false;

            //get the active package info
            if ($active_plan) {
                $listing_id = get_post_meta($order_id, '_listing_id', true);
                $featured = get_post_meta($listing_id, '_featured', true);
                $total_regular_listing = $num_regular - ('0' === $featured ? $user_regular_listing + 1 : $user_regular_listing);
                $total_featured_listing = $num_featured - ('1' === $featured ? $user_featured_listing + 1 : $user_featured_listing);
                //cancelled the active plan if allowance exits
                $subscribed_date = $active_plan->post_date;
                $package_length = get_post_meta($subscribed_package_id, 'fm_length', true);
                $plan_type = package_or_PPL($subscribed_package_id);
                $package_length = $package_length ? $package_length : '1';
                // Current time
                $start_date = !empty($subscribed_date) ? $subscribed_date : '';
                // Calculate new date
                $date = new DateTime($start_date);
                $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                $expired_date = $date->format('Y-m-d H:i:s');
                $current_d = current_time('mysql');
                $remaining_days = ($expired_date > $current_d) ? (floor(strtotime($expired_date) / (60 * 60 * 24)) - floor(strtotime($current_d) / (60 * 60 * 24))) : 0; //calculate the number of days remaining in a plan
                if (((0 >= $total_regular_listing) && (0 >= $total_featured_listing)) || ($remaining_days <= 0)) {
                    if (('pay_per_listng' != $plan_type)) {
                        $order = new WC_Order($order_id);
                        $order->update_status('cancelled', 'order_note');
                    }
                }
            }

            ?>
            <div class="atbd_listing_type">
                <?php
                $listing_type = !empty($listing_info['listing_type']) ? $listing_info['listing_type'] : '';
                ?>
                <h4><?php _e('Choose Listing Type', 'direo-extension') ?></h4>
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
            <?php
        }

        public function dwpp_tab_content_after_favorite()
        {

            $this->load_template('user-dashboard-data');
        }

        public function dwpp_tab_after_favorite_listings()
        {
            $package_tab = get_directorist_option('user_active_package', 1);
            $order_histroy = get_directorist_option('user_order_history', 1);
            if (!empty($package_tab)) {
                ?>
                <li <?php echo apply_filters('atbdp_li_attribute_in_dashboard_package_tab', 'class="atbd-packages"') ?>>
                    <a <?php echo apply_filters('atbdp_attribute_in_dashboard_package_tab', 'href="" class="atbd_tn_link" target="manage_fees"'); ?>><?php echo apply_filters('atbdp_package_tab_text_in_dashboard', __('Packages', 'direo-extension')); ?></a>
                </li>
                <?php
            }
            if (!empty($order_histroy)) {
                ?>
                <li <?php echo apply_filters('atbdp_li_attribute_in_dashboard_order_tab', 'class="atbd-orderhisyory"') ?>>
                    <a <?php echo apply_filters('atbdp_attribute_in_dashboard_order_history_tab', 'href="" '); ?>
                            class="atbd_tn_link"
                            target="manage_invoices"><?php echo apply_filters('atbdp_order_history_tab_text_in_dashboard', __('Order History', 'direo-extension')); ?></a>
                </li>
            <?php }
        }

        public function dwpp_add_new_listing_column_content($column = null, $listing_id = null)
        {
            switch ($column) {
                case 'active_plan' :
                    $user_id = get_post_field('post_author', $listing_id);
                    $selected_plan_id = get_post_meta($listing_id, '_fm_plans', true);
                    $plan_details = subscribed_package_or_PPL_plans($user_id, 'completed', $selected_plan_id);
                    $plan_name = get_the_title($selected_plan_id);
                    $active = '';
                    $plan_type = '';
                    if (package_or_PPL($selected_plan_id) == 'package') {
                        if ($plan_details) {
                            $plan_type = __('Package', 'direo-extension');
                            //$active = 'green';
                        }

                    }
                    if (package_or_PPL($selected_plan_id) == 'pay_per_listng') {
                        $plan_type = __('Pay Per Listing', 'direo-extension');
                        if ($plan_details) {
                            //$active ='green';
                        }
                    }
                    ?>
                    <span style='color: <?php echo !empty($active) ? $active : '' ?>;'><?php echo !empty($plan_name) ? $plan_name : ''; ?></span>
                    <span><?php echo ' - ' . $plan_type ?></span>
                    <?php
                    //subscribed_package_or_PPL_plans($user_id, $order_status, $plan_id);
                    break;
            }
        }

        public function dwpp_add_new_listing_column($column_name)
        {
            $column_name['active_plan'] = __('Plan Status', 'direo-extension');
            return $column_name;
        }

        /*
       * Update selected plan ID to DB.
       *
       * @since	 1.0.0
       * @param	 string      $listing_id    Contain ID for the selected listing.
       */
        public function dwpp_process_the_selected_plans($listing_id)
        {
            $user_id = get_current_user_id();
            $fm_plans = selected_plan_id();
            update_post_meta($listing_id, '_fm_plans', $fm_plans);
            //lets check is the plan already purchased
            $plan_purchased = subscribed_package_or_PPL_plans($user_id, 'completed', $fm_plans);
            $subscribed_package_id = $fm_plans;
            //calculate the expair date
            $package_length = get_post_meta($subscribed_package_id, 'fm_length', true);
            $fm_claim = get_post_meta($subscribed_package_id, '_fm_claim', true);
            $package_length = $package_length ? $package_length : '1';
            $subscribed_plan_info = subscribed_package_or_PPL_plans($user_id, 'completed', $subscribed_package_id);
            $purchase_date = !empty($subscribed_plan_info->post_date) ? $subscribed_plan_info->post_date : '';
            $subscribed_date = $purchase_date;
            // if the selected plan is package type
            if ('package' === package_or_PPL($plan = null)) {
                if ($plan_purchased) {
                    $order_id = $plan_purchased->ID;
                    update_post_meta($listing_id, '_plan_order_id', $order_id);
                }
            }
            // Subscribe date
            $start_date = !empty($subscribed_date) ? $subscribed_date : '';
            $current_d = current_time('mysql');
            // Calculate new date
            $date = new DateTime($current_d);
            $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
            $expired_date = $date->format('Y-m-d H:i:s');
            $remaining_days = ($expired_date > $current_d) ? (floor(strtotime($expired_date) / (60 * 60 * 24)) - floor(strtotime($current_d) / (60 * 60 * 24))) : 0; //calculate the number of days remaining in a plan
            $listing_type = !empty($_POST['listing_type']) ? sanitize_text_field($_POST['listing_type']) : '';

            $is_never_expaired = get_post_meta($subscribed_package_id, 'fm_length_unl', true);
            if ($is_never_expaired) {
                update_post_meta($listing_id, '_never_expire', '1');
            } else {
                update_post_meta($listing_id, '_expiry_date', $expired_date);
            }
            if ('featured' == $listing_type) {
                update_post_meta($listing_id, '_featured', '1');
            }
            if ('pay_per_listng' === package_or_PPL($subscribed_package_id)) {
                if (PPL_with_featured()) {
                    update_post_meta($listing_id, '_featured', '1');
                }
            }
            //update the claim status
            if (!empty($fm_claim)) {
                update_post_meta($listing_id, '_claimed_by_admin', 1);
            }
        }

        public function dwpp_checkout_order_processed($order_id)
        {
            $order = wc_get_order($order_id);
            $items = $order->get_items();

            foreach ($items as $key => $item) {

                $plan_id = $item['product_id'];
                $product = wc_get_product($plan_id);
                if ($product->is_type('listing_pricing_plans')) {
                    $listing_id = isset($_POST['listing_id']) ? (int)$_POST['listing_id'] : 0;
                    update_post_meta($order_id, '_fm_plan_ordered', $plan_id);
                    update_post_meta($order_id, '_listing_id', $listing_id);
                    update_post_meta($order_id, '_fm_plans', $plan_id);
                }
            }
        }

        public function add_settings_for_woo_pricing_plans($fields)
        {
            $permission = array(
                'type' => 'toggle',
                'name' => 'woo_pricing_plans_enable',
                'label' => __('WooCommerce Pricing Plans', 'direo-extension'),
                'description' => __('You can disable it for users', 'direo-extension'),
                'default' => 1

            );

            // lets push our settings to the end of the other settings field and return it.
            array_push($fields, $permission);
            return $fields;
        }


        public function dwpp_woocommerce_product_data_tabs($tabs)
        {
            $tabs['attribute']['class'][] = 'hide_if_listing_pricing_plans';
            $tabs['shipping']['class'][] = 'hide_if_listing_pricing_plans';
            $tabs['linked_product']['class'][] = 'hide_if_listing_pricing_plans';
            $tabs['advanced']['class'][] = 'hide_if_listing_pricing_plans';
            return $tabs;
        }

        public function dwpp_woocommerce_product_class($classname, $product_type)
        {
            if ($product_type == 'listing_pricing_plans') { // notice the checking here.
                $classname = 'DWPP_Product_Listings_Package';
            }
            return $classname;
        }


        /**
         * Save WooCommerce meta data.
         *
         * @param int $post_id Post ID
         * @return    bool       true    If the save was successful or not.
         * @since      1.0.0
         * @access    public
         *
         */
        public function dwpp_woocommerce_process_product_meta($post_id, $post)
        {

            // Listings limit
            $plan_type = isset($_POST['_dwpp_plan_type']) ? $_POST['_dwpp_plan_type'] : 'pay_per_listng';
            update_post_meta($post_id, 'plan_type', $plan_type);

            // Listing duration
            $listing_duration = isset($_POST['_dwpp_listing_duration']) ? (int)$_POST['_dwpp_listing_duration'] : 0;
            $never_expire = isset($_POST['_dwpp_never_expire']) ? 1 : 0;
            update_post_meta($post_id, 'fm_length', $listing_duration);
            update_post_meta($post_id, 'fm_length_unl', $never_expire);

            // Listings limit
            $listings_limit = isset($_POST['_dwpp_listings_limit']) ? (int)$_POST['_dwpp_listings_limit'] : 0;
            $unl_listings = isset($_POST['_dwpp_unl_listings']) ? 1 : 0;
            $hide_listings = isset($_POST['_dwpp_hide_listings']) ? 1 : 0;
            update_post_meta($post_id, '_dwpp_hide_listings', $hide_listings);
            update_post_meta($post_id, 'num_regular', $listings_limit);
            update_post_meta($post_id, 'num_regular_unl', $unl_listings);

            //Featured Listings limit
            $featured_limit = isset($_POST['_dwpp_featured_limit']) ? (int)$_POST['_dwpp_featured_limit'] : 0;
            $unl_featured = isset($_POST['_dwpp_unl_featured']) ? 1 : 0;
            $hide_featured = isset($_POST['_dwpp_hide_featured']) ? 1 : 0;
            update_post_meta($post_id, 'num_featured', $featured_limit);
            update_post_meta($post_id, 'num_featured_unl', $unl_featured);
            update_post_meta($post_id, '_dwpp_hide_featured', $hide_featured);

            // Featured
            $featured = isset($_POST['_dwpp_featured']) ? 1 : 0;
            $hide_featured = isset($_POST['_dwpp_hide_listing_featured']) ? 1 : 0;
            update_post_meta($post_id, '_dwpp_hide_listing_featured', $hide_featured);
            update_post_meta($post_id, 'is_featured_listing', $featured);

            // Images limit
            $images_limit = isset($_POST['_dwpp_images_limit']) ? (int)$_POST['_dwpp_images_limit'] : 0;
            $unl_image = isset($_POST['_dwpp_unl_image']) ? 1 : 0;
            $allow_image = isset($_POST['fm_allow_slider']) ? 1 : 0;
            $hide_image = isset($_POST['_dwpp_hide_image']) ? 1 : 0;
            update_post_meta($post_id, 'num_image', $images_limit);
            update_post_meta($post_id, 'fm_allow_slider', $allow_image);
            update_post_meta($post_id, 'num_image_unl', $unl_image);
            update_post_meta($post_id, '_dwpp_hide_image', $hide_image);

            // tag limit
            $tag_limit = isset($_POST['_dwpp_tag_limit']) ? (int)$_POST['_dwpp_tag_limit'] : 0;
            $unl_tag = isset($_POST['_dwpp_unl_tag']) ? 1 : 0;
            $allow_tag = isset($_POST['fm_allow_tag']) ? 1 : 0;
            $hide_tag = isset($_POST['_dwpp_hide_tag']) ? 1 : 0;
            update_post_meta($post_id, 'fm_tag_limit', $tag_limit);
            update_post_meta($post_id, 'fm_allow_tag', $allow_tag);
            update_post_meta($post_id, 'fm_tag_limit_unl', $unl_tag);
            update_post_meta($post_id, '_dwpp_hide_tag', $hide_tag);

            // Price limit
            $price_limit = isset($_POST['_dwpp_price_limit']) ? (int)$_POST['_dwpp_price_limit'] : 0;
            $allow_price = isset($_POST['fm_allow_price']) ? 1 : 0;
            $unl_price = isset($_POST['_dwpp_unl_price']) ? 1 : 0;
            $hide_price = isset($_POST['_dwpp_hide_price']) ? 1 : 0;
            update_post_meta($post_id, 'price_range', $price_limit);
            update_post_meta($post_id, 'fm_allow_price', $allow_price);
            update_post_meta($post_id, 'price_range_unl', $unl_price);
            update_post_meta($post_id, '_dwpp_hide_price', $hide_price);

            // Price Range
            $price_range = isset($_POST['_dwpp_price_range']) ? 1 : 0;
            $hide_price_range = isset($_POST['_dwpp_hide_price_range']) ? 1 : 0;
            update_post_meta($post_id, 'fm_allow_price_range', $price_range);
            update_post_meta($post_id, '_dwpp_hide_price_range', $hide_price_range);

            // Business Hours
            $business_hours = isset($_POST['_dwpp_business_hours']) ? 1 : 0;
            $hide_business_hours = isset($_POST['_dwpp_hide_business_hours']) ? 1 : 0;
            update_post_meta($post_id, 'business_hrs', $business_hours);
            update_post_meta($post_id, '_dwpp_hide_business_hours', $hide_business_hours);

            // Image gallery
            $num_gallery_image = isset($_POST['num_gallery_image_add_plan_image_gallery']) ? (int)$_POST['num_gallery_image_add_plan_image_gallery'] : 0;
            $unl_gallery_image = isset($_POST['unl_gallery_image']) ? 1 : 0;
            $atfm_listing_gallery = isset($_POST['atfm_listing_gallery']) ? 1 : 0;
            $hide_image_gallery = isset($_POST['_dwpp_hide_image_gallery']) ? 1 : 0;
            update_post_meta($post_id, 'num_gallery_image', $num_gallery_image);
            update_post_meta($post_id, 'unl_gallery_image', $unl_gallery_image);
            update_post_meta($post_id, 'atfm_listing_gallery', $atfm_listing_gallery);
            update_post_meta($post_id, '_dwpp_hide_image_gallery', $hide_image_gallery);

            // video
            $video = isset($_POST['_dwpp_video']) ? 1 : 0;
            $hide_video = isset($_POST['_dwpp_hide_video']) ? 1 : 0;
            update_post_meta($post_id, 'l_video', $video);
            update_post_meta($post_id, '_dwpp_hide_video', $hide_video);

            // Contact listing owner
            $cl_owner = isset($_POST['_dwpp_cl_owner']) ? 1 : 0;
            $hide_cl_owner = isset($_POST['_dwpp_hide_cl_owner']) ? 1 : 0;
            update_post_meta($post_id, 'cf_owner', $cl_owner);
            update_post_meta($post_id, '_dwpp_hide_cl_owner', $hide_cl_owner);

            // Email
            $email = isset($_POST['_dwpp_email']) ? 1 : 0;
            $hide_email = isset($_POST['_dwpp_hide_email']) ? 1 : 0;
            update_post_meta($post_id, 'fm_email', $email);
            update_post_meta($post_id, '_dwpp_hide_email', $hide_email);

            // Phone
            $phone = isset($_POST['_dwpp_phone']) ? 1 : 0;
            $hide_phone = isset($_POST['_dwpp_hide_phone']) ? 1 : 0;
            update_post_meta($post_id, 'fm_phone', $phone);
            update_post_meta($post_id, '_dwpp_hide_phone', $hide_phone);

            // Website
            $web_link = isset($_POST['_dwpp_web_link']) ? 1 : 0;
            $hide_web_link = isset($_POST['_dwpp_hide_web_link']) ? 1 : 0;
            update_post_meta($post_id, 'fm_web_link', $web_link);
            update_post_meta($post_id, '_dwpp_hide_web_link', $hide_web_link);

            // Social link
            $sm_link = isset($_POST['_dwpp_sm_link']) ? 1 : 0;
            $hide_sm_link = isset($_POST['_dwpp_hide_sm_link']) ? 1 : 0;
            update_post_meta($post_id, 'fm_social_network', $sm_link);
            update_post_meta($post_id, '_dwpp_hide_sm_link', $hide_sm_link);

            // Review
            $customer_review = isset($_POST['_dwpp_customer_review']) ? 1 : 0;
            $hide_customer_review = isset($_POST['_dwpp_hide_customer_review']) ? 1 : 0;
            update_post_meta($post_id, 'fm_cs_review', $customer_review);
            update_post_meta($post_id, '_dwpp_hide_customer_review', $hide_customer_review);

            // FAQs
            $faqs = isset($_POST['_dwpp_faqs']) ? 1 : 0;
            $hide_faqs = isset($_POST['_dwpp_hide_faqs']) ? 1 : 0;
            update_post_meta($post_id, 'fm_listing_faq', $faqs);
            update_post_meta($post_id, '_dwpp_hide_faqs', $hide_faqs);

            // Custom fields
            $custom_fields = isset($_POST['_dwpp_custom_fields']) ? 1 : 0;
            $hide_custom_fields = isset($_POST['_dwpp_hide_custom_fields']) ? 1 : 0;
            update_post_meta($post_id, 'fm_custom_field', $custom_fields);
            update_post_meta($post_id, '_dwpp_hide_custom_fields', $hide_custom_fields);

            // Claim Badge
            $claim = isset($_POST['_dwpp_claim']) ? 1 : 0;
            $hide_claim = isset($_POST['_dwpp_hide_claim']) ? 1 : 0;
            update_post_meta($post_id, '_fm_claim', $claim);
            update_post_meta($post_id, '_dwpp_hide_claim', $hide_claim);


            // Categories
            $categories = isset($_POST['exclude_cat']) ? array_map('esc_attr', $_POST['exclude_cat']) : array();
            update_post_meta($post_id, 'exclude_cat', $categories);
            $hide_category = isset($_POST['_dwpp_hide_category']) ? 1 : 0;
            update_post_meta($post_id, '_dwpp_hide_category', $hide_category);

            //Recomannand this plan
            $default_pln = isset($_POST['default_pln']) ? 1 : 0;
            update_post_meta($post_id, 'default_pln', $default_pln);

            //Hide this plan
            $hide_from_plans = isset($_POST['hide_from_plans']) ? 1 : 0;
            update_post_meta($post_id, '_hide_from_plans', $hide_from_plans);
            return true;

        }


        /**
         * Display custom woocommerce fields.
         *
         * @since     1.0.0
         * @access    public
         */
        public function dwpp_display_custom_fields()
        {

            global $woocommerce, $post;

            $fields_info['plan_metas'] = get_post_meta($post->ID);

            // Categories
            $fields_info['dwpp_categories'] = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0, 'parent' => 0));

            $fields_info['current_val'] = !empty(get_post_meta($post->ID, 'exclude_cat', true)) ? get_post_meta($post->ID, 'exclude_cat', true) : array();

            // ...
            $this->load_template('woo-custom-fields', array('fields_info' => $fields_info));
            //require_once dwpp_WC_PLANS_PLUGIN_DIR . 'admin/templates/listings-package.php';

        }


        /**
         * @since 1.0.0
         * @access  public
         * register woo product type
         */
        public function dwpp_register_product_type($types)
        {
            $types['listing_pricing_plans'] = __('Listing Pricing Plan', 'direo-extension');
            return $types;
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

        public function register_necessary_scripts()
        {
            if (is_rtl()) {
                wp_enqueue_style('dwpp_main_css_rtl', DWPP_ASSETS . 'css/main-rtl.css', false, DWPP_VERSION);
            } else {
                wp_enqueue_style('dwpp_main_css', DWPP_ASSETS . 'css/main.css', false, DWPP_VERSION);
            }
            wp_register_script('atpp-plan-validator', DWPP_ASSETS . '/js/plan-validator.js', array('jquery'), true);
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

            );

            wp_localize_script('atpp-plan-validator', 'plan_validator', $validator);
        }

        public function admin_register_necessary_scripts()
        {
            wp_enqueue_script('dwpp-admin-script', DWPP_ASSETS . '/js/main.js', array('jquery'), true);
            wp_enqueue_style('dwpp_main_css', DWPP_ASSETS . 'css/main.css', false, DWPP_VERSION);

        }


        /**
         * It  loads a template file from the Default template directory.
         * @param string $name Name of the file that should be loaded from the template directory.
         * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
         */
        public function load_template($name, $args = array())
        {
            global $post;
            require_once(DWPP_TEMPLATES_DIR . $name . '.php');
        }

        /**
         * It register the text domain to the WordPress
         */
        public function load_textdomain()
        {
            load_plugin_textdomain('direo-extension', false, DWPP_LANG_DIR);

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
            require_once DWPP_INC_DIR . 'helper-functions.php';
            require_once DWPP_INC_DIR . 'class-enqueuer.php';
            require_once DWPP_INC_DIR . 'class-woo-admin.php';
            require_once DWPP_INC_DIR . 'validator.php';
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
}
/**
 * The main function for that returns DWPP_Pricing_Plans
 *
 * The main function responsible for returning the one true DWPP_Pricing_Plans
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @since 1.0
 * @return object|DWPP_Pricing_Plans The one true DWPP_Pricing_Plans Instance.
 */
function DWPP_Pricing_Plans(){
    return DWPP_Pricing_Plans::instance();
}


DWPP_Pricing_Plans(); // get the plugin running

/**
 * Register a custom WooCommerce product type.
 *
 * @since     1.0.0
 * @access    public
 */
function dwpp_register_woocommerce_product_type() {

    /**
     * DWPP_Product_Listings_Package Class
     *
     * @since    1.0.0
     * @access   public
     */
    if (class_exists('WooCommerce')){
        class DWPP_Product_Listings_Package extends WC_Product {

            public function __construct( $product ) {

                $this->product_type = 'listing_pricing_plans';
                parent::__construct( $product );


            }


        }
    }


}

add_action( 'plugins_loaded', 'dwpp_register_woocommerce_product_type');