<?php
/*==========================================
    Shortcode : Single Location
    Author URI: https://aazztech.com
============================================*/

$header = $show_pagination = $sidebar = $filter = $title = $layout = $number_cat = $row = $cat = $tag = $location = $featured = $popular = $order_by = $order_list = $map_height = $preview = $user = $redirect = $link = $web = '';

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
} ?>

<div class="<?php echo implode(' ', $wrap_class); ?>" id="<?php echo esc_attr("listing-" . $layout); ?> checked-listings-general-view">
    <?php echo do_shortcode('[directorist_location view="' . esc_attr($layout) . '" orderby="' . esc_attr($order_by) . '" order="' . esc_attr($order_list) . '" listings_per_page="' . esc_attr($number_cat) . '" category="' . esc_attr($cat) . '" tag="' . esc_attr($tag) . '" location="' . esc_attr($location) . '" featured_only="' . esc_attr($featured) . '" popular_only="' . esc_attr($popular) . '" header="' . esc_attr($header) . '" header_title ="' . esc_attr($title) . '" columns="' . esc_attr($row) . '" action_before_after_loop="' . esc_attr($sidebar) . '" show_pagination="' . esc_attr($show_pagination) . '" advanced_filter="' . esc_attr($filter) . '" map_height="' . $map_height . '" display_preview_image="' . esc_attr($image) . '" logged_in_user_only="' . esc_attr($user) . '" redirect_page_url="' . esc_attr($web) . '" ]'); ?>
</div>