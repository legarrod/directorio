<?php
$address_label               = get_directorist_option('address_label',__('Address','direo-extension'));
$fax_label                   = get_directorist_option('fax_label',__('Fax','direo-extension'));
$email_label                 = get_directorist_option('email_label',__('Email','direo-extension'));
$website_label               = get_directorist_option('website_label',__('Website','direo-extension'));
$tag_label                   = get_directorist_option('tag_label',__('Tag','direo-extension'));
$zip_label                   = get_directorist_option('zip_label',__('Zip','direo-extension'));
$listing_filters_icon        = get_directorist_option('listing_filters_icon',1);
$listing_map_viewas          = get_directorist_option('listings_map_viewas',1);
$listings_map_sortby         = get_directorist_option('listings_map_sortby',1);
$sort_by_text                = get_directorist_option('sort_by_text', __('Sort By', 'direo-extension'));
$view_as_text                = get_directorist_option('view_as_text', __('View As', 'direo-extension'));
$listings_with_map_columns   = !empty($listings_with_map_columns) ? $listings_with_map_columns : '';
$view_columns                = isset($_POST['view_columns']) ? $_POST['view_columns'] : $listings_with_map_columns;
$text_field                  = !empty($_POST['text_field']) ? $_POST['text_field'] : '';
$category_field              = !empty($_POST['category_field']) ? $_POST['category_field'] : '';
$location_field              = !empty($_POST['location_field']) ? $_POST['location_field'] : '';
$address_field               = !empty($_POST['address_field']) ? $_POST['address_field'] : '';
$price_field                 = !empty($_POST['price_field']) ? $_POST['price_field'] : '';
$price_range_field           = !empty($_POST['price_range_field']) ? $_POST['price_range_field'] : '';
$rating_field                = !empty($_POST['rating_field']) ? $_POST['rating_field'] : '';
$radius_field                = !empty($_POST['radius_field']) ? $_POST['radius_field'] : '';
$open_field                  = !empty($_POST['open_field']) ? $_POST['open_field'] : '';
$tag_field                   = !empty($_POST['tag_field']) ? $_POST['tag_field'] : '';
$custom_search_field         = !empty($_POST['custom_search_field']) ? $_POST['custom_search_field'] : '';
$website_field               = !empty($_POST['website_field']) ? $_POST['website_field'] : '';
$email_field                 = !empty($_POST['email_field']) ? $_POST['email_field'] : '';
$phone_field                 = !empty($_POST['phone_field']) ? $_POST['phone_field'] : '';
$fax_field                   = !empty($_POST['fax_field']) ? $_POST['fax_field'] : '';
$zip_field                   = !empty($_POST['zip_field']) ? $_POST['zip_field'] : '';
$reset_filters               = !empty($_POST['reset_filters']) ? $_POST['reset_filters'] : '';
$apply_filter                = !empty($_POST['apply_filter']) ? $_POST['apply_filter'] : '';
$listings_with_map_filter_fields = !empty($listings_with_map_filter_fields) ? $listings_with_map_filter_fields : array();
$search_filters = !empty($search_filters) ? $search_filters : array();
$search_fields              = array(
        "search_text" => in_array('search_text', $listings_with_map_filter_fields) ? "yes" : $text_field,
        "search_category" => in_array('search_category', $listings_with_map_filter_fields) ? "yes" : $category_field,
        "search_location" => in_array('search_location', $listings_with_map_filter_fields) ? "yes" : $location_field,
        "search_price" => in_array('search_price', $listings_with_map_filter_fields) ? "yes" : $price_field,
        "search_price_range" => in_array('search_price_range', $listings_with_map_filter_fields) ? "yes" : $price_range_field,
        "search_rating" => in_array('search_rating', $listings_with_map_filter_fields) ? "yes" : $rating_field,
        "radius_search" => in_array('radius_search', $listings_with_map_filter_fields) ? "yes" : $radius_field,
        "search_open_now" => in_array('search_open_now', $listings_with_map_filter_fields) ? "yes" : $open_field,
        "search_tag" => in_array('search_tag', $listings_with_map_filter_fields) ? "yes" : $tag_field,
        "search_custom_fields" => in_array('search_custom_fields', $listings_with_map_filter_fields) ? "yes" : $custom_search_field,
        "search_website" => in_array('search_website', $listings_with_map_filter_fields) ? "yes" : $website_field,
        "search_email" => in_array('search_email', $listings_with_map_filter_fields) ? "yes" : $email_field,
        "search_phone" => in_array('search_phone', $listings_with_map_filter_fields) ? "yes" : $phone_field,
        "search_fax" => in_array('search_fax', $listings_with_map_filter_fields) ? "yes" : $fax_field,
        "search_zip_code" => in_array('search_zip_code', $listings_with_map_filter_fields) ? "yes" : $zip_field,
);

$filters_button = array(
    "search_reset_filters" => in_array('search_reset_filters', $search_filters) ? "yes" : $reset_filters,
    "search_apply_filters" => in_array('search_apply_filters', $search_filters) ? "yes" : $apply_filter,
);
$query_args = array(
    'parent'             => 0,
    'term_id'            => 0,
    'hide_empty'         => 0,
    'orderby'            => 'name',
    'order'              => 'asc',
    'show_count'         => 0,
    'single_only'        => 0,
    'pad_counts'         => true,
    'immediate_category' => 0,
    'active_term_id'     => 0,
    'ancestors'          => array()
);
$categories_fields = search_category_location_filter( $query_args, ATBDP_CATEGORY );
$locations_fields  = search_category_location_filter( $query_args, ATBDP_LOCATION );
if ($display_header == 'yes') { ?>
    <div class="atbd_header_bar">
        <div class="<?php echo !empty($header_container_fluid) ? $header_container_fluid : ''; ?>">
            <div class="row">
                <div class="col-md-12">

                    <div class="dlm_header atbd_generic_header">
                        <?php
                        if (!empty($header_title)) { ?>
                            <div class="atbd_generic_header_title">
                                <?php
                                /**
                                 * @since 5.4.0
                                 */
                             if ("1" == $view_columns || "2-style-2" == $view_columns) { ?>
                                <button class="more-filter btn btn-outline btn-outline-primary">
                                        <span class="<?php atbdp_icon_type(true); ?>-filter"></span>
                                    <?php _e("All Filters","directorist_listings_map"); ?>
                                </button>
                                <?php }
                                do_action('bdmv_after_filter_button_in_listings_header');
                                if (!empty($header_title)) {
                                    echo apply_filters('atbdp_total_listings_found_text',"<h3>{$header_title}</h3>", $header_title);
                                }
                                ?>
                            </div>
                            <?php
                        }
                        /**
                         * @since 5.4.0
                         */
                        do_action('bdmv_after_total_listing_found_in_listings_header', $header_title);

                        if ($listing_map_viewas || $listings_map_sortby) { ?>
                            <div class="dlm_action_btns atbd_listing_action_btn btn-toolbar" role="toolbar">
                                <!-- Views dropdown -->
                                <?php if (!empty($listing_map_viewas)) {
                                    $view_as = isset($_POST['view_as']) ? $_POST['view_as'] : '';
                                    $grid_active = ('grid' == $view_as) ? "active" : '';
                                    $list_active = ('list' == $view_as) ? "active" : '';
                                    $html = '<div class="dropdown">';
                                    $html .= '<a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ' . $view_as_text . '<span class="caret"></span>
                                    </a>';
                                    $html .= '<div class="dropdown-menu view-as" aria-labelledby="dropdownMenuLink">';

                                    $html .= sprintf('<a data-view="grid" class="dropdown-item map-view-grid %s">%s</a>',$grid_active, __('Grid','direo-extension'));
                                    $html .= sprintf('<a data-view="list" class="dropdown-item map-view-list %s">%s</a>',$list_active,__('list','direo-extension'));
                                    $html .= '</div>';
                                    $html .= '</div>';
                                    echo apply_filters('bdmv_view_as',$html);
                                } ?>
                                <!-- Orderby dropdown -->
                                <?php
                                $sort_html = '';
                                if ($listings_map_sortby) {
                                    $sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : '';
                                    $title_asc_active = ('title-asc' == $sort_by) ? "active" : '';
                                    $title_desc_active = ('title-desc' == $sort_by) ? "active" : '';
                                    $date_desc_active = ('date-desc' == $sort_by) ? "active" : '';
                                    $date_asc_active = ('date-asc' == $sort_by) ? "active" : '';
                                    $price_asc_active = ('price-asc' == $sort_by) ? "active" : '';
                                    $price_desc_active = ('price-desc' == $sort_by) ? "active" : '';
                                    $rand_active = ('rand' == $sort_by) ? "active" : '';
                                    $sort_html .= '<div class="dropdown">
                                        <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">' .
                                        $sort_by_text . ' <span class="caret"></span>
                                        </a>';
                                    $sort_html .= '<div class="dropdown-menu dropdown-menu-right sort-by"
                                             aria-labelledby="dropdownMenuLink2">';

                                    $sort_html .= sprintf('<a class="dropdown-item sort-title-asc %s" data-sort="title-asc">%s</a>', $title_asc_active, __("A to Z ( title )", 'direo-extension'));
                                    $sort_html .= sprintf('<a class="dropdown-item sort-title-desc %s" data-sort="title-desc">%s</a>', $title_desc_active,  __("Z to A ( title )", 'direo-extension'));
                                    $sort_html .= sprintf('<a class="dropdown-item sort-date-desc %s" data-sort="date-desc">%s</a>', $date_desc_active, __("Latest listings", 'direo-extension'));
                                    $sort_html .= sprintf('<a class="dropdown-item sort-date-asc %s" data-sort="date-asc">%s</a>', $date_asc_active, __("Oldest listings", 'direo-extension'));
                                    $sort_html .= sprintf('<a class="dropdown-item sort-price-asc %s" data-sort="price-asc">%s</a>',$price_asc_active, __("Price ( low to high )", 'direo-extension'));
                                    $sort_html .= sprintf('<a class="dropdown-item sort-price-desc %s" data-sort="price-desc">%s</a>', $price_desc_active, __("Price ( high to low )", 'direo-extension'));
                                    $sort_html .= sprintf('<a class="dropdown-item sort-rand %s" data-sort="rand">%s</a>',$rand_active, __("Random listings", 'direo-extension'));
                                    $sort_html .= ' </div>';
                                    $sort_html .= ' </div>';
                                    /**
                                     * @since 5.4.0
                                     */
                                    echo apply_filters('atbdp_listings_with_map_header_sort_by_button', $sort_html);
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <!--ads advance search-->

                    <?php
                    if("1" == $view_columns || "2-style-2" == $view_columns) {
                        $hidden_var = array(
                                "display_header"=> !empty($display_header) ? $display_header : '',
                                "header_title"=> !empty($header_title_for_search) ? $header_title_for_search : '',
                                "show_pagination"=> !empty($show_pagination) ? $show_pagination : 'yes',
                                "listings_per_page"=> !empty($listings_per_page) ? $listings_per_page : 6,
                                "location_slug"=> !empty($location_slug) ? $location_slug : '',
                                "category_slug"=> !empty($category_slug) ? $category_slug : '',
                        );
                        bdmv_header_advance_search($search_fields, $filters_button,$hidden_var);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>