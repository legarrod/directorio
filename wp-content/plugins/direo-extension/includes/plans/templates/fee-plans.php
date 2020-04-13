<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;
/**
 * Fire before pricing plan loaded
 */
do_action('atbdp_before_plan_page_loaded');
$atts = !empty($args['atts']) ? $args['atts'] : '';
$atts = shortcode_atts(
    array(
        'id' => '',
        'columns' => 3
    ), $atts);
$post_id = !empty($atts['id']) ? explode(',', $atts['id']) : '';
$shortcode_id = !empty($atts['id']) ? $atts['id'] : '';
$columns = !empty($atts['columns']) ? $atts['columns'] : 3;
$private_plan = !empty($shortcode_id) ? 'EXISTS' : 'NOT EXISTS';
$price_column_width = 100 / $columns . '%';
?>
<div id="fm_plans_container" <?php do_action('atbdp_plans_container_div_attribute');?>>
    <div class="atbd_plans_row">
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
                    'compare' => $private_plan,
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
        if (!empty($post_id)) {
            $args['post__in'] = $post_id;
        }
        $atbdp_query = new WP_Query($args);
        if ($atbdp_query->have_posts()) {
            global $post;
            $plans = $atbdp_query->posts;
            foreach ($plans as $key => $value) {
                $plan_id = $value->ID;
                $plan_id = !empty($shortcode_id) ? $shortcode_id : $plan_id;
                $plan_metas = get_post_meta($plan_id);
                $unl = __('Unlimited', 'direo-extension');
                $fm_price = esc_attr($plan_metas['fm_price'][0]);
                $price_decimal = esc_attr($plan_metas['price_decimal'][0]);
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
                $atfm_listing_gallery = esc_attr($plan_metas['atfm_listing_gallery'][0]);
                $l_video = esc_attr($plan_metas['l_video'][0]);
                $cf_owner = esc_attr($plan_metas['cf_owner'][0]);
                $fm_email = esc_attr($plan_metas['fm_email'][0]);
                $fm_phone = esc_attr($plan_metas['fm_phone'][0]);
                $fm_web_link = esc_attr($plan_metas['fm_web_link'][0]);
                $fm_social_network = esc_attr($plan_metas['fm_social_network'][0]);
                $fm_cs_review = esc_attr($plan_metas['fm_cs_review'][0]);
                $fm_listing_faq = esc_attr($plan_metas['fm_listing_faq'][0]);
                $fm_custom_field = esc_attr($plan_metas['fm_custom_field'][0]);
                $fm_allow_price_range = esc_attr($plan_metas['fm_allow_price_range'][0]);
                $default_pln = esc_attr($plan_metas['default_pln'][0]);
                $fm_claim = !empty($plan_metas['_fm_claim'][0]) ? esc_attr($plan_metas['_fm_claim'][0]) : '';
                $hide_claim = !empty($plan_metas['_hide_claim'][0]) ? esc_attr($plan_metas['_hide_claim'][0]) : '';
                $hide_featured = !empty($plan_metas['hide_listing_featured'][0]) ? esc_attr($plan_metas['hide_listing_featured'][0]) : '';
                if (is_user_logged_in()) {
                    $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $plan_id);
                } else {
                    $active_plan = false;
                }
                $currency = atbdp_get_payment_currency();
                $symbol = atbdp_currency_symbol($currency);
                $c_position = get_directorist_option('payment_currency_position');
                $before = '';
                $after = '';
                ('after' == $c_position) ? $after = $symbol : $before = $symbol;
                $columns_class = 'atbd_plan_col atbd_plan_col' . $columns;
                do_action('atbdp_after_start_plans_loop', $plan_id);
                ?>
                <div class="<?php echo $columns_class; ?>">
                    <div class="pricing pricing--1 <?php echo !empty($plan_metas['default_pln'][0]) ? 'atbd_pricing_special' : ''; ?>">
                        <?php echo !empty($plan_metas['default_pln'][0]) ? __(' <span class="atbd_popular_badge">Recommended</span>', 'direo-extension') : ''; ?>
                        <div class="pricing__title">
                            <h4><?php echo $value->post_title; ?><?php echo ($active_plan && ($plan_metas['plan_type'][0] != 'pay_per_listng')) ? __(' <span class="atbd_plan-active">Active</span>', 'direo-extension') : ''; ?></h4>

                        </div>

                        <div class="pricing__price rounded">
                            <p class="pricing_value">
                                <sup><?php echo $before ?></sup><?php echo !empty($fm_price) ? $fm_price : '0'; ?>
                                <sup><?php echo $after ?></sup>
                                <small>/<?php echo ($fm_length_unl) ? $unl : $fm_length;
                                    _e(' days', 'direo-extension') ?></small>
                            </p>
                            <p class="pricing_subtitle"><?php echo ($plan_metas['plan_type'][0] == 'pay_per_listng') ? __('Per Listing', 'direo-extension') : __('Per Package', 'direo-extension') ?></p>
                            <?php
                            if (empty($plan_metas['hide_description'][0])) {
                                ?>
                                <p class="pricing_description"><?php echo !empty($plan_metas['fm_description'][0]) ? $plan_metas['fm_description'][0] : ''; ?></p>
                            <?php } ?>
                        </div>
                        <div class="pricing__features">
                            <ul>
                                <?php if (($plan_metas['plan_type'][0] == 'pay_per_listng') && empty(apply_filters('atbdp_plan_featured_compare', $hide_featured))) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($plan_metas['is_featured_listing'][0]) ? 'check' : 'times'; ?>"> </span><?php _e('Listing as featured', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if ($plan_metas['plan_type'][0] != 'pay_per_listng') { ?>
                                    <?php if (empty($plan_metas['hide_listings'][0])) { ?>
                                        <li><span class="fa fa-<?php if (($num_regular > 0) || $num_regular_unl) {
                                                echo 'check';
                                            } else {
                                                echo 'times';
                                            } ?>"></span><?php echo $num_regular_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __(' Regular Listings', 'direo-extension') . '' : $num_regular . __(' Regular Listings', 'direo-extension'); ?>
                                        </li>
                                    <?php }
                                    if (empty(apply_filters('atbdp_plan_featured_compare',$plan_metas['hide_featured'][0]))) { ?>
                                        <li><span class="fa fa-<?php if (($num_featured > 0) || $num_featured_unl) {
                                                echo 'check';
                                            } else {
                                                echo 'times';
                                            } ?>"></span><?php echo $num_featured_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __(' Featured Listings', 'direo-extension') . '' : $num_featured . __(' Featured Listings', 'direo-extension'); ?>
                                        </li>
                                    <?php }
                                }
                                if (empty($plan_metas['hide_price_limit'][0])) { ?>
                                    <li><span class="fa fa-<?php if (($price_range > 0) || $price_range_unl) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"></span><?php echo $price_range_unl ? '<span class="atbd_color-success">' . _e('No', 'direo-extension') . '</span>' : $price_range; ?><?php _e(' Price Limit', 'direo-extension') ?>
                                    </li>

                                <?php }
                                    if (empty(apply_filters('atbdp_plan_price_range_compare', $plan_metas['hide_price_range'][0]))) { ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_allow_price_range) ? 'check' : 'times'; ?>"> </span><?php _e('Average Price Range', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_listing_image_compare', $plan_metas['hide_image'][0]))) { ?>
                                    <li><span class="fa fa-<?php if (($num_image > 0) || $num_image_unl) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"></span><?php echo $num_image_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __(' Listing Image', 'direo-extension') . '' : $num_image . __(' Listing Image ', 'direo-extension'); ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['hide_tag_limit'][0])) { ?>
                                    <li><span class="fa fa-<?php if (($fm_tag_limit > 0) || $fm_tag_limit_unl) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"></span><?php echo $fm_tag_limit_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __('   Tags limit', 'direo-extension') . '' : $fm_tag_limit . __('   Tags limit', 'direo-extension'); ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_business_hour_compare', $plan_metas['hide_BH'][0]))) {
                                    if (class_exists('BD_Business_Hour')) {
                                        ?>
                                        <li>
                                            <span class="fa fa-<?php echo !empty($business_hrs) ? 'check' : 'times'; ?>"> </span><?php _e('Business Hours', 'direo-extension') ?>
                                        </li>
                                    <?php } ?>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_gallery_compare', $plan_metas['hide_Lgallery'][0]))) {
                                    ?>
                                    <li class="plan_page_gallery">
                                        <span class="fa fa-<?php if (($num_gallery_image > 0) || $num_gallery_image_unl) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"></span><?php echo $num_gallery_image_unl ? '<span class="atbd_color-success">' . $unl . '</span>' . __(' Gallery Image Limit', 'direo-extension') . '' : $num_gallery_image . __(' Gallery Image Limit', 'direo-extension'); ?>
                                    </li>

                                <?php }
                                if (empty(apply_filters('atbdp_plan_video_compare', $plan_metas['hide_video'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($l_video) ? 'check' : 'times'; ?>"> </span><?php _e('Video', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_contact_owner_compare', $plan_metas['hide_Cowner'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($cf_owner) ? 'check' : 'times'; ?>"> </span><?php _e('Contact Owner', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_email_compare', $plan_metas['hide_email'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_email) ? 'check' : 'times'; ?>"> </span><?php _e('Show Email', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_phone_compare', $plan_metas['hide_phone'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_phone) ? 'check' : 'times'; ?>"> </span><?php _e('Show Contact Number', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_webLink_compare', $plan_metas['hide_webLink'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_web_link) ? 'check' : 'times'; ?>"> </span><?php _e('Show Web Link', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_social_network_compare', $plan_metas['hide_Snetwork'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_social_network) ? 'check' : 'times'; ?>"> </span><?php _e('Show Social Network', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_review_compare', $plan_metas['hide_review'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_cs_review) ? 'check' : 'times'; ?>"> </span><?php _e('Allow Customer Review', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_faqs_compare', $plan_metas['hide_faqs'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_listing_faq) ? 'check' : 'times'; ?>"> </span><?php _e('Listing FAQs', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty($plan_metas['hide_categories'][0])) {
                                    $is_cat = selected_plan_meta($plan_id, 'exclude_cat');
                                    ?>
                                    <li><span class="fa fa-<?php if (empty($is_cat)) {
                                            echo 'check';
                                        } else {
                                            echo 'times';
                                        } ?>"> </span><?php _e('All Categories', 'direo-extension') ?></li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_custom_field_compare', $plan_metas['hide_custom_field'][0]))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_custom_field) ? 'check' : 'times'; ?>"> </span><?php _e('Custom Fields', 'direo-extension') ?>
                                    </li>
                                <?php }
                                if (empty(apply_filters('atbdp_plan_claim_compare', $hide_claim))) {
                                    ?>
                                    <li>
                                        <span class="fa fa-<?php echo !empty($fm_claim) ? 'check' : 'times'; ?>"> </span><?php _e('Claim Badge Included', 'direo-extension') ?>
                                    </li>
                                <?php }
                                /*
                                 * @since 1.0.0
                                 * Fires in plan compare page
                                 * hook for future dev
                                 */
                                do_action('atpp_after_pricing_plans_compare_fields', $value->ID);
                                ?>

                            </ul>
                            <div class="price_action">
                                <?php
                                $used_free_plan = atpp_get_used_free_plan($value->ID, get_current_user_id());
                                $url = apply_filters('atbdp_pricing_plan_to_checkout_url', ATBDP_Permalink::get_add_listing_page_link_with_plan($value->ID), $value->ID);
                                ?>
                                <input id="fee_plans[<?php echo $value->ID; ?>]" type="hidden"
                                       value="<?php echo $value->ID; ?>" name="fm_plans">
                                <label for="fee_plans[<?php echo $value->ID; ?>]"><a
                                            href="<?= esc_url($url); ?>"
                                            onclick="return <?php echo !$used_free_plan ? 'false' : 'true' ?>;"
                                            class="btn btn-block price_action--btn"><?php !$used_free_plan ? _e('Already Used!', 'direo-extension') : _e('Continue', 'direo-extension') ?></a></label>

                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="col-md-12">
                <div class="atbd_pricing_status">
                    <?php printf('<p>%s</p>', __('There is no Plan available right now. Please contact with administrator.', 'direo-extension')); ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div> <!--ends. row-->
</div> <!--ends. fm_plans_container-->