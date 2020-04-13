<?php
/*==========================================
    Shortcode : All Needs
    Author URI: https://aazztech.com
============================================*/
$avatar = $budget = $columns = $order = $order_list = $number = $pagination = '';
extract($atts);
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]); ?>

<div class="<?php echo implode(' ', $wrap_class); ?>">
    <?php echo do_shortcode('[directorist_all_needs display_author="' . esc_attr($avatar) . '" display_category="' . esc_attr($avatar) . '" display_budget="' . esc_attr($budget) . '" columns="' . esc_attr($columns) . '" show_pagination="' . esc_attr($pagination) . '" posts_per_page="' . esc_attr($number) . '" order_by="' . esc_attr($order) . '" sort_by="' . esc_attr($order_list) . '"]'); ?>
</div>