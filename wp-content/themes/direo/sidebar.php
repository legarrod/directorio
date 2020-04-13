<?php
$sidebar_name = $woo_sidebar ='';
if (class_exists('woocommerce') && (is_shop() || is_product_taxonomy())) {
    $sidebar_name = 'shop_sidebar';
    $woo_sidebar = 'order-1 order-sm-0';
} elseif (is_home()) {
    $sidebar_name = 'blog_sidebar';
} elseif (is_page()) {
    $sidebar_name = 'page_sidebar';
} elseif (is_singular('at_biz_dir')) {
    $sidebar_name = 'right-sidebar-listing';
} elseif (is_single()) {
    $sidebar_name = 'blog_sidebar';
} else {
    $sidebar_name = 'blog_sidebar';
}

if (is_active_sidebar($sidebar_name)) { ?>
    <div class="col-lg-4 mt-5 mt-md-0 <?php echo esc_attr($woo_sidebar); ?>">
        <div class="sidebar">
            <?php dynamic_sidebar(esc_html($sidebar_name)) ?>
        </div>
    </div>
    <?php
}
