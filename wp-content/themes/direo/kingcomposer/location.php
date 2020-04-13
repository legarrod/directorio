<?php
/*==========================================
    Shortcode : Listings Locations
    Author URI: https://aazztech.com
============================================*/

$title_wrap_class = $number_loc = $order_by = $order_list = $row = $slug = '';

extract($atts);

$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);
$wrap_class[] = 'kc-title-wrap';
?>

<div class="<?php echo implode(' ', $wrap_class); ?>">
    <?php echo do_shortcode('[directorist_all_locations view="' . esc_attr($layout) . '" orderby="' . esc_attr($order_by) . '" order="' . esc_attr($order_list) . '" loc_per_page="' . esc_attr($number_loc) . '" columns="' . esc_attr($row) . '" slug="' . esc_attr($slug) . '"]'); ?>
</div>
