<?php
/*==========================================
    Shortcode : Need All Locations
    Author URI: https://aazztech.com
============================================*/

$title_wrap_class = $number_loc = $order_by = $order_list = $row = $slug = $web = '';

extract($atts);

$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);
$wrap_class[] = 'kc-title-wrap';
?>

<div class="<?php echo implode(' ', $wrap_class); ?>">
    <?php echo do_shortcode('[directorist_need_locations view="' . esc_attr($layout) . '" orderby="' . esc_attr($order_by) . '" order="' . esc_attr($order_list) . '" loc_per_page="' . esc_attr($number_loc) . '" columns="' . esc_attr($row) . '" slug="' . esc_attr($slug) . '" logged_in_user_only="' . esc_attr($user) . '" redirect_page_url="' . esc_attr($web) . '"]'); ?>
</div>