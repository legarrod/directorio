<?php
    $listings_with_map_columns = !empty($atts['listings_with_map_columns']) ? $atts['listings_with_map_columns'] : '';
    $listings_with_map_filter_fields = !empty($atts['listings_with_map_filter_fields']) ? $atts['listings_with_map_filter_fields'] : '';
    $filter_buttons = !empty($atts['filter_buttons']) ? $atts['filter_buttons'] : '';
    if(!is_array($listings_with_map_filter_fields)) {
        $listings_with_map_filter_fields = explode(',',$listings_with_map_filter_fields);
    }
    if(!is_array($filter_buttons)) {
        $filter_buttons = explode(',',$filter_buttons);
    }
    $visible_fields = get_directorist_option('listing_map_visible_fields',array('search_text','search_category','search_location'));
    $search_filters = get_directorist_option('bdmv_search_filters',array('search_reset_filters','search_apply_filters'));
?>
<?php if('3' == $listings_with_map_columns) { ?>
    <div id="directorist" class="atbd_wrapper" data-view-columns="<?php echo $listings_with_map_columns;?>">
<div class="bdmv_wrapper bdmv-columns-three">
    <?php if (in_array('search_text', $listings_with_map_filter_fields) || in_array('search_category', $listings_with_map_filter_fields) || in_array('search_location', $listings_with_map_filter_fields) || in_array('search_price', $listings_with_map_filter_fields) || in_array('search_price_range', $listings_with_map_filter_fields) || in_array('search_rating', $listings_with_map_filter_fields) || in_array('search_tag', $listings_with_map_filter_fields) || in_array('search_open_now', $listings_with_map_filter_fields) || in_array('search_custom_fields', $listings_with_map_filter_fields) || in_array('search_website', $listings_with_map_filter_fields) || in_array('search_email', $listings_with_map_filter_fields) || in_array('search_phone', $listings_with_map_filter_fields) || in_array('search_fax', $listings_with_map_filter_fields) || in_array('search_zip_code', $listings_with_map_filter_fields) || in_array('radius_search', $listings_with_map_filter_fields)) { ?>
    <div class="bdmv-search">
        <?php
        include BDM_TEMPLATES_DIR . '/all-listings/columns-three/search.php';
        ?>
    </div>
    <?php } ?>
    <div class="bdmv-map-listing">
        <?php
        include BDM_TEMPLATES_DIR . '/all-listings/columns-three/map-listing.php';
        ?>
    </div>
    <div class="ajax-search-result"></div>
    <!--responsive buttons-->
    <div class="atbdp-res-btns">
        <a href="" class="dlm-res-btn" id="js-dlm-search"><span class="la la-search"></span></a>
        <a href="" class="dlm-res-btn active" id="js-dlm-listings"><span class="la la-list-ul"></span></a>
        <a href="" class="dlm-res-btn" id="js-dlm-map"><span class="la la-map-o"></span></a>
    </div>
</div>
    </div>
<?php } elseif('2' == $listings_with_map_columns) { ?>
<div id="directorist" class="atbd_wrapper" data-view-columns="<?php echo $listings_with_map_columns;?>">
    <div class="bdmv_wrapper bdmv-columns-two">
    <div class="bdmv-search">
        <?php if (in_array('search_text', $listings_with_map_filter_fields) || in_array('search_category', $listings_with_map_filter_fields) || in_array('search_location', $listings_with_map_filter_fields) || in_array('search_price', $listings_with_map_filter_fields) || in_array('search_price_range', $listings_with_map_filter_fields) || in_array('search_rating', $listings_with_map_filter_fields) || in_array('search_tag', $listings_with_map_filter_fields) || in_array('search_open_now', $listings_with_map_filter_fields) || in_array('search_custom_fields', $listings_with_map_filter_fields) || in_array('search_website', $listings_with_map_filter_fields) || in_array('search_email', $listings_with_map_filter_fields) || in_array('search_phone', $listings_with_map_filter_fields) || in_array('search_fax', $listings_with_map_filter_fields) || in_array('search_zip_code', $listings_with_map_filter_fields) || in_array('radius_search', $listings_with_map_filter_fields)) { ?>
        <div class="bdmv-search-content">
            <?php
            include BDM_TEMPLATES_DIR . '/all-listings/columns-two/search.php';
            ?>
        </div>
        <?php } ?>
    </div>
    <div class="bdmv-map-listing">
        <?php
        include BDM_TEMPLATES_DIR . '/all-listings/columns-three/map-listing.php';
        ?>
    </div>
    <div class="ajax-search-result"></div>
        <!--responsive buttons-->
        <div class="atbdp-res-btns">
            <a href="" class="dlm-res-btn" id="js-dlm-search"><span class="la la-search"></span></a>
            <a href="" class="dlm-res-btn active" id="js-dlm-listings"><span class="la la-list-ul"></span></a>
            <a href="" class="dlm-res-btn" id="js-dlm-map"><span class="la la-map-o"></span></a>
        </div>
    </div>
</div>
<?php } elseif('2-style-2' == $listings_with_map_columns) { ?>
    <div id="directorist" class="atbd_wrapper" data-view-columns="<?php echo $listings_with_map_columns;?>">
        <div class="bdmv-map-listing">
            <?php
            include BDM_TEMPLATES_DIR . '/all-listings/columns-two-(style-two)/map-listing.php';
            ?>
        </div>
        <div class="ajax-search-result"></div>
        <!--responsive buttons-->
        <div class="atbdp-res-btns">
            <a href="" class="dlm-res-btn" id="js-dlm-search"><span class="la la-search"></span></a>
            <a href="" class="dlm-res-btn active" id="js-dlm-listings"><span class="la la-list-ul"></span></a>
            <a href="" class="dlm-res-btn" id="js-dlm-map"><span class="la la-map-o"></span></a>
        </div>
    </div>
<?php }elseif("1" == $listings_with_map_columns) { ?>
    <div id="directorist" class="atbd_wrapper" data-view-columns="<?php echo $listings_with_map_columns;?>">
            <div class="bdmv-map-listing">
            <?php
            include BDM_TEMPLATES_DIR . '/all-listings/columns-one/map-listing.php';
            ?>
            </div>
        <div class="ajax-search-result"></div>
        <!--responsive buttons-->
        <div class="atbdp-res-btns">
            <a href="" class="dlm-res-btn" id="js-dlm-search"><span class="la la-search"></span></a>
            <a href="" class="dlm-res-btn active" id="js-dlm-listings"><span class="la la-list-ul"></span></a>
            <a href="" class="dlm-res-btn" id="js-dlm-map"><span class="la la-map-o"></span></a>
        </div>
    </div>

<?php } ?>
<style>
    .bdmv_wrapper,
    .bdmv-columns-three .bdmv-map-listing .bdmv-listing,
    .bdmv-columns-three .ajax-search-result .bdmv-listing,
    .bdmv-columns-two .bdmv-map-listing{
        height: <?php echo !empty($map_height) ? $map_height : '800';?>px;
    }
</style>