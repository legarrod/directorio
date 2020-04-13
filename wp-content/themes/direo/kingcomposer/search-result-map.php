<?php
/*==========================================
    Shortcode : Latest Listings
    Author URI: https://aazztech.com
============================================*/

$header = $show_pagination = $title = $number_cat = $cat = $tag = $location = $featured = $popular = $order_by = $order_list = $preview = $user = $link = $web = $listing_map_style = '';

$wrap_class = [];

extract($atts);
$filter = !empty($filter) ? $filter : 'no';
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);
$class_title = array('kc_title');
$image = $preview ? 'yes' : 'no';

$link = ('||' === $link) ? '' : $link;
$link = kc_parse_link($link);

if (strlen($link['url']) > 0) {
    $web = $link['url'];
}?>

<div class="<?php echo implode(' ', $wrap_class); ?>" id='listing-listings_with_map'>
    <?php echo do_shortcode('[directorist_search_result view="listings_with_map" orderby="' . esc_attr($order_by) . '" order="' . esc_attr($order_list) . '" listings_per_page="' . esc_attr($number_cat) . '" category="' . esc_attr($cat) . '" tag="' . esc_attr($tag) . '" location="' . esc_attr($location) . '" featured_only="' . esc_attr($featured) . '" action_before_after_loop="no" popular_only="' . esc_attr($popular) . '" show_pagination="' . esc_attr($show_pagination) . '" display_preview_image="' . esc_attr($image) . '" logged_in_user_only="' . esc_attr($user) . '" redirect_page_url="' . esc_attr($web) . '" listings_with_map_columns="' . esc_attr($listing_map_style) . '" ]'); ?>
</div>