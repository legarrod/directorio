<?php
/**
 * This template displays the Directorist listings in map view.
 */
if (is_rtl()){
    wp_enqueue_style('atbdp-search-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/search-style-rtl.css');
}else{
    wp_enqueue_style('atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
}
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings               = !empty($all_listings) ? $all_listings : new WP_Query;
$is_disable_price           = get_directorist_option('disable_list_price');
$display_sortby_dropdown    = get_directorist_option('display_sort_by',1);
$display_viewas_dropdown    = get_directorist_option('display_view_as',1);
$select_listing_map         = get_directorist_option('select_listing_map','google');
$zoom                       = get_directorist_option('map_view_zoom_level', 1);
$container                  = 'container-fluid';
$map_container              = apply_filters('atbdp_map_container',$container);

?>
<div id="directorist" class="atbd_wrapper">
    <div class="atbdp-divider"></div>
    <!-- the loop -->
    <?php
    $listings_map_height = !empty($map_height) ? $map_height : 350;
    ?>
    <div class="<?php echo !empty($map_container) ? $map_container : '';?>">
        <?php if('google' == $select_listing_map) {
            include BDM_TEMPLATES_DIR . 'view/maps/google/google-map.php';
        } else {
            include BDM_TEMPLATES_DIR . 'view/maps/openstreet/openstreet-map.php';
        } ?>

    </div>
    <!-- Use reset postdata to restore orginal query -->
    <?php wp_reset_postdata(); ?>
</div>



