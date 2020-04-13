<?php
$tag_label                           = get_directorist_option('tag_label',__('Tag','direo-extension'));
$address_label                       = get_directorist_option('address_label',__('Address','direo-extension'));
$fax_label                           = get_directorist_option('fax_label',__('Fax','direo-extension'));
$email_label                         = get_directorist_option('email_label',__('Email','direo-extension'));
$website_label                       = get_directorist_option('website_label',__('Website','direo-extension'));
$zip_label                           = get_directorist_option('zip_label',__('Zip','direo-extension'));
$listing_map_location_address        = get_directorist_option('listing_map_location_address','map_api');
$query_args = array(
    'parent' => 0,
    'term_id' => 0,
    'hide_empty' => 0,
    'orderby' => 'name',
    'order' => 'asc',
    'show_count' => 0,
    'single_only' => 0,
    'pad_counts' => true,
    'immediate_category' => 0,
    'active_term_id' => 0,
    'ancestors' => array()
);
$categories_fields                  = search_category_location_filter($query_args, ATBDP_CATEGORY);
$locations_fields                   = search_category_location_filter($query_args, ATBDP_LOCATION);
$custom_field_post                  = bdmv_custom_field_post()->post_count;
?>
<div class="atbdp search-area default-ad-search" data-nonce="<?php echo wp_create_nonce('bdlm_ajax_nonce')?>" id="bdlm-search-area">
    <?php if (in_array('search_text', $listings_with_map_filter_fields)) { ?>
            <div class="form-group">
                <input type="text" name="q" placeholder="<?php _e('What are you looking for?','direo-extension');?>" value="<?php echo !empty($_GET['q']) ? $_GET['q'] : ''; ?>" class="form-control" id="search_q">
            </div><!-- ends: .form-group -->
    <?php } ?>
    <?php if (in_array('search_location', $listings_with_map_filter_fields)) {
    if('listing_location' == $listing_map_location_address) {
        ?>
            <div class="form-group">
                <select name="in_loc" class="search_fields form-control bdas-location-search select-basic" id="at_biz_dir-location">
                    <option value=""><?php _e('Select a location');?></option>
                    <?php
                    echo $locations_fields;
                    ?>
                </select>
            </div>
    <?php } else {
        $select_listing_map = get_directorist_option('select_listing_map','google');
        wp_enqueue_script('bdm-current-js');
        wp_localize_script('bdm-current-js', 'adbdp_geolocation', array('select_listing_map'=> $select_listing_map));
        $geo_loc = ('google' == $select_listing_map) ? '<span class="bdmv_get_loc la la-crosshairs"></span>' : '<span class="bdmv_get_loc la la-crosshairs"></span>';
        ?>
        <div class="form-group">
            <div class="position-relative">
                <input type="text" name="address" autocomplete="off" id="address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>" placeholder="<?php  _e('Location','direo-extension');?>"
                       class="form-control location-name"><?php echo $geo_loc; ?>
            </div>
            <?php $select_listing_map = get_directorist_option('select_listing_map', 'google');
            if ('google' != $select_listing_map) {
                echo '<div class="address_result"></div>';
            } ?>
            <input type="hidden" id="cityLat" name="cityLat" value="<?php if (isset($_GET['cityLat'])) echo esc_attr($_GET['cityLat']); ?>" />
            <input type="hidden" id="cityLng" name="cityLng" value="<?php if (isset($_GET['cityLng'])) echo esc_attr($_GET['cityLng']); ?>" />
        </div><!-- ends: .form-group -->
    <?php }

    } ?>
    <?php if (in_array('search_category', $listings_with_map_filter_fields)) { ?>
            <div class="form-group">
                <select name="in_cat" class="search_fields form-control bdas-category-search select-basic" id="at_biz_dir-category">
                    <option value=""><?php _e('Select a category');?></option>
                    <?php
                    echo $categories_fields;
                    ?>
                </select>
            </div>
    <?php } ?>
    <?php if (in_array('search_custom_fields', $listings_with_map_filter_fields) && 0 < $custom_field_post) { ?>
            <div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
                <?php do_action( 'wp_ajax_atbdp_custom_fields_search', isset( $_GET['in_cat'] ) ? $_GET['in_cat'] : 0 ); ?>
            </div>
    <?php } ?>
    <?php if (in_array('search_price', $listings_with_map_filter_fields)) { ?>
            <div class="form-group ">
                <div class="price_ranges">
                    <div>
                        <input type="text" name="price[0]" class="form-control price" placeholder="<?php _e( 'Min Price', 'direo-extension' ); ?>" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][0] ); ?>">
                    </div>
                    <div>
                        <input type="text" name="price[1]" class="form-control price" placeholder="<?php _e( 'Max Price', 'direo-extension' ); ?>" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][1] ); ?>">
                    </div>
                </div>
            </div>
    <?php } ?>
    <?php if (in_array('search_price_range', $listings_with_map_filter_fields)) { ?>
            <div class="form-group">
                <div class="select-basic">
                    <select name="price_range" class="form-control">
                        <option value="none">Price Range</option>
                        <option value="skimming" <?php if(!empty($_GET['price_range']) && 'skimming' == $_GET['price_range']) { echo 'selected';}?>>Ultra High ($$$$)</option>
                        <option value="moderate" <?php if(!empty($_GET['price_range']) && 'moderate' == $_GET['price_range']) { echo 'selected';}?>>Expensive ($$$)</option>
                        <option value="economy" <?php if(!empty($_GET['price_range']) && 'economy' == $_GET['price_range']) { echo 'selected';}?>>Moderate ($$)</option>
                        <option value="bellow_economy" <?php if(!empty($_GET['price_range']) && 'bellow_economy' == $_GET['price_range']) { echo 'selected';}?> >Cheap ($)</option>
                    </select>
                </div>
            </div><!-- ends: .form-group -->
    <?php } ?>
    <?php if ('map_api' == $listing_map_location_address && in_array('radius_search', $listings_with_map_filter_fields)) {
        $default_radius_distance = get_directorist_option('listing_default_radius_distance', 0);
        $ajax_result   = !empty($_POST['miles']) ? $_POST['miles'] : $default_radius_distance;
        ?>
            <div class="form-group">
                <div class="atbdpr-range rs-primary">
                    <div class="atbdp-labels">
                        <label><?php _e('Distance:','direo-extension'); ?></label>
                        <span class="atbdpr_amount"></span>
                    </div>
                    <div class="atbd_slider-range-wrapper">
                        <div class="atbd_slider-range"></div>
                        <input type="hidden" id="atbd_rs_value" name="miles" value="<?php echo !empty($_GET['miles']) ? $_GET['miles'] : $ajax_result;?>">
                    </div>
                </div>
            </div>
    <?php } ?>
    <?php if (in_array('search_open_now', $listings_with_map_filter_fields) && in_array('directorist-business-hours/bd-business-hour.php', apply_filters('active_plugins', get_option('active_plugins')))) { ?>
            <div class="check-btn">
                <div class="btn-checkbox active-color-secondary">
                    <label>
                        <input type="checkbox" name="open_now" id="open_now" value="open_now" <?php if(!empty($_GET['open_now']) && 'open_now' == $_GET['open_now']) { echo 'checked';}?>><span class="text-success"><i
                                class="fa fa-clock-o"></i> Open Now</span>
                    </label>
                </div>
            </div>
    <?php } ?>
    <?php if (in_array('search_website', $listings_with_map_filter_fields)) { ?>
            <div class="form-group">
                <input type="text" name="website" id="website" placeholder="<?php echo !empty($website_label) ? $website_label : __('Website','direo-extension');?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
            </div><!-- ends: .form-group -->
    <?php } ?>
    <?php if (in_array('search_email', $listings_with_map_filter_fields)) { ?>
            <div class="form-group">
                <input type="text" name="email" id="email" placeholder="<?php echo !empty($email_label) ? $email_label : __('Email','direo-extension');?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
            </div><!-- ends: .form-group -->
    <?php } ?>
    <?php if (in_array('search_phone', $listings_with_map_filter_fields)) { ?>
            <div class="form-group">
                <input type="text" name="phone" id="phone" placeholder="<?php _e('Phone Number','direo-extension');?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
            </div><!-- ends: .form-group -->
    <?php } ?>
    <?php if (in_array('search_zip_code', $listings_with_map_filter_fields)) { ?>
            <div class="form-group">
                <div class="position-relative">
                    <input type="text" name="zip_code" id="zip_code" placeholder="<?php echo !empty($zip_label) ? $zip_label : __('zip','direo-extension');?>"
                           value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                </div>
            </div><!-- ends: .form-group -->
    <?php } ?>
        <?php
        if (in_array('search_tag', $listings_with_map_filter_fields)) {
        $terms = get_terms(ATBDP_TAGS);
            ?>
            <div class="form-group filter-checklist">
                <label><?php  _e('Filter by Tags','direo-extension');?></label>
                <div class="checklist-items">
                    <?php
                    if(!empty($terms)) {
                        foreach($terms as $term) {
                            ?>
                            <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                                <input type="checkbox" class="custom-control-input" id="<?php echo $term->term_id;?>" name="in_tag" value="<?php echo $term->term_id;?>" <?php if(!empty($_GET['in_tag']) && $term->term_id == $_GET['in_tag']) { echo "checked";}?>>
                                <span class="check--select"></span>
                                <label class="custom-control-label" for="<?php echo $term->term_id;?>"><?php echo $term->name;?></label>
                            </div>
                        <?php } } ?>
                </div>
            </div><!-- ends: .filter-checklist -->
        <?php } ?>
    <?php if (in_array('search_phone', $listings_with_map_filter_fields)) { ?>
            <div class="form-group filter-checklist">
                <label><?php _e('Filter by Ratings','direo-extension'); ?></label>
                <div class="sort-rating">
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="5" name="search_by_rating" class="custom-control-input" id="customCheck7" <?php if(!empty($_GET['search_by_rating']) && '5' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck7">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="4" name="search_by_rating" class="custom-control-input" id="customCheck8" <?php if(!empty($_GET['search_by_rating']) && '4' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck8">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="3" name="search_by_rating" class="custom-control-input" id="customCheck9" <?php if(!empty($_GET['search_by_rating']) && '3' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck9">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="2" name="search_by_rating" class="custom-control-input" id="customCheck10" <?php if(!empty($_GET['search_by_rating']) && '2' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck10">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="1" name="search_by_rating" class="custom-control-input" id="customCheck11" <?php if(!empty($_GET['search_by_rating']) && '1' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck11">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" name="search_by_rating" value="0" class="custom-control-input" id="customCheck12" <?php if(!empty($_GET['search_by_rating']) && '0' == $_GET['search_by_rating']) { echo 'none';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck12">
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                </div>
            </div><!-- ends: .filter-checklist -->
    <?php } ?>
        <div class="form-group submit_btn">
            <?php if (in_array('search_reset_filters', $search_filters)) { ?>
            <button type="reset" class="btn btn-default"><?php _e( 'Reset Filters', 'direo-extension' ); ?></button>
            <?php } ?>
            <?php if (in_array('search_apply_filters', $search_filters)) { ?>
            <button type="submit" class="btn btn-primary btn-icon icon-right ajax-search"><?php _e( 'Apply Filters', 'direo-extension' ); ?></button>
            <?php } ?>
        </div>
    <input type="hidden" id="display_header" value="<?php echo !empty($display_header) ? $display_header : ''; ?>">
    <input type="hidden" id="header_title" value="<?php echo !empty($header_title_for_search) ? $header_title_for_search : ''; ?>">
    <input type="hidden" id="show_pagination" value="<?php echo !empty($show_pagination) ? $show_pagination : 'yes'; ?>">
    <input type="hidden" id="listings_per_page" value="<?php echo !empty($atts['listings_per_page']) ? $atts['listings_per_page'] : 6 ; ?>">
    <input type="hidden" id="location_slug" value="<?php echo !empty($term_slug) ? $term_slug : ''; ?>">
    <input type="hidden" id="category_slug" value="<?php echo !empty($category_slug) ? $category_slug : ''; ?>">
</div><!-- ends: .default-ad-search -->