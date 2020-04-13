<?php
/*==========================================
    Shortcode : Need All Categories
    Author URI: https://aazztech.com
============================================*/
$title_wrap_class = $number_cat = $order_by = $order_list = $row = $slug = $cat_style = $layout =  $link = $web = '';

extract($atts);
$slug = ('slug' == $order_by) ? $slug : '';

$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);
$class_title = array('kc_title');

$wrap_class[] = 'kc-title-wrap';

$link = ('||' === $link) ? '' : $link;
$link = kc_parse_link($link);

if (strlen($link['url']) > 0) {
    $web = $link['url'];
}?>

<div class="<?php echo implode(' ', $wrap_class); ?>" id="<?php echo esc_attr($cat_style); ?>">
    <?php echo do_shortcode('[directorist_need_categories view="' . esc_attr($layout) . '" orderby="' . esc_attr($order_by) . '" order="' . esc_attr($order_list) . '" cat_per_page="' . esc_attr($number_cat) . '" columns="' . esc_attr($row) . '" slug="' . esc_attr($slug) . '" logged_in_user_only="' . esc_attr($user) . '" redirect_page_url="' . esc_attr($web) . '"]'); ?>
</div>