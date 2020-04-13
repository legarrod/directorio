<?php

/*=====================================================
   Listings view as
=================================================*/
function direo_listings_view_as()
{ ?>
    <div class="view-mode">
        <a class="action-btn ab-grid" href="<?php echo add_query_arg('view', 'grid'); ?>">
            <span class="la la-th-large"></span>
        </a>
        <a class="action-btn ab-list" href="<?php echo add_query_arg('view', 'list'); ?>">
            <span class="la la-th-list"></span>
        </a>
        <a class="action-btn ab-map" href="<?php echo add_query_arg('view', 'map'); ?>">
            <span class="la la-map"></span>
        </a>
    </div>
    <?php
}

add_filter('atbdp_listings_view_as', 'direo_listings_view_as', 10, 3);

/*=====================================================
   View as of "listing with map view"
=================================================*/
function direo_listings_map_view_as()
{
    $listing_map_view = class_exists('Directorist_Base') ? get_directorist_option('listing_map_view', 'grid') : '';
    $view_as = isset($_POST['view_as']) ? $_POST['view_as'] : $listing_map_view; ?>
    <div class="view-mode-2 view-as">
        <a data-view="grid"
           class="action-btn-2 ab-grid map-view-grid <?php echo 'grid' == $view_as ? esc_html('active') : ''; ?>">
            <span class="la la-th-large"></span>
        </a>
        <a data-view="list"
           class="action-btn-2 ab-list map-view-list <?php echo 'list' == $view_as ? esc_html('active') : ''; ?>">
            <span class="la la-list"></span>
        </a>
    </div>
    <?php
}

add_filter('bdmv_view_as', 'direo_listings_map_view_as');

/*=====================================================
   Author avatar URL
=================================================*/
function direo_get_avatar_url($author_id, $size)
{
    $match = '';
    $get_avatar = get_avatar($author_id, $size);
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    if ($matches) {
        if (array_key_exists("1", $matches)) {
            $match = ($matches[1]);
        }
    }
    return $match;
}

/*=====================================================
   Search Element
=================================================*/
function direo_search_form_element($text = '', $cat = '', $loc = '', $more = '')
{
    if (!class_exists('Directorist_Base')) {
        return;
    }
    $require_text = get_directorist_option('require_search_text') ? "required" : "";
    $require_cat = get_directorist_option('require_search_category') ? "required" : "";
    $require_loc = get_directorist_option('require_search_location') ? "required" : "";

    $search_location_address = get_directorist_option('search_location_address', 'address');
    $search_placeholder = get_directorist_option('search_placeholder', esc_attr_x('What are you looking for?', 'placeholder', 'direo-core'));
    $search_category_placeholder = get_directorist_option('search_category_placeholder', esc_html__('Select a category', 'direo-core'));
    $search_location_placeholder = get_directorist_option('search_location_placeholder', esc_html__('Select a location', 'direo-core'));
    $search_listing_text = get_directorist_option('search_listing_text', esc_html__('Search', 'direo-core'));

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

    if ($text) { ?>
        <div class="single_search_field search_query">
            <input class="form-control search_fields" type="text" name="q" <?php echo esc_attr($require_text); ?>
                   autocomplete="off"
                   placeholder="<?php echo esc_html($search_placeholder); ?>">
        </div>
        <?php
    }

    if ($cat) { ?>
        <div class="single_search_field search_category">
            <select <?php echo esc_attr($require_cat); ?>
                    name="in_cat" class="search_fields form-control" id="at_biz_dir-category">
                <option value=""><?php echo esc_html($search_category_placeholder); ?></option>
                <?php echo search_category_location_filter($query_args, ATBDP_CATEGORY); ?>
            </select>
        </div>
        <?php
        do_action('atbdp_search_listing_after_category_field');
    }

    if ($loc) {
        if ('listing_location' == $search_location_address) { ?>
            <div class="single_search_field search_location">
                <select <?php echo esc_attr($require_loc); ?>
                        name="in_loc" class="search_fields form-control" id="at_biz_dir-location">
                    <option value=""><?php echo esc_html($search_location_placeholder); ?></option>
                    <?php echo search_category_location_filter($query_args, ATBDP_LOCATION); ?>
                </select>
            </div>
            <?php
        } else {
            wp_enqueue_script('atbdp-geolocation');
            $address = !empty($_GET['address']) ? $_GET['address'] : ''; ?>
            <div class="single_search_field atbdp_map_address_field">
                <div class="atbdp_get_address_field">
                    <input type="text" id="address" name="address"
                           autocomplete="off"
                           value="<?php echo esc_attr($address); ?>"
                           placeholder="<?php echo esc_html($search_location_placeholder); ?>"
                        <?php echo esc_attr($require_loc); ?>
                           class="form-control location-name">
                    <span class="atbd_get_loc la la-crosshairs"></span>
                </div>
                <?php
                $select_listing_map = get_directorist_option('select_listing_map', 'google');
                if ('google' != $select_listing_map) {
                    echo '<div class="address_result"></div>';
                } ?>

                <input type="hidden" id="cityLat" name="cityLat" value=""/>
                <input type="hidden" id="cityLng" name="cityLng" value=""/>
            </div>
            <?php
        }
    } ?>

    <div class="atbd_submit_btn">
        <button type="submit" class="btn_search">
            <?php echo esc_attr($search_listing_text); ?>
        </button>
        <?php
        if ('yes' == $more) { ?>
            <button class="more-filter">
                <span class="<?php atbdp_icon_type(true); ?>-filter"></span>
            </button>
            <?php
        } ?>
    </div>
    <?php
}

/*=====================================================
   More filter search field
=================================================*/
function direo_more_filter_search_form()
{
    if (!class_exists('Directorist_Base')) {
        return;
    }
    $search_filters = get_directorist_option('search_filters', array('search_reset_filters', 'search_apply_filters'));
    $reset_filters_text = get_directorist_option('sresult_reset_text', esc_html__('Reset Filters', 'direo'));
    $apply_filters_text = get_directorist_option('sresult_apply_text', esc_html__('Apply Filters', 'direo'));
    $search_more_filters_fields = get_directorist_option('search_more_filters_fields', array('search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search'));

    $tag_label = get_directorist_option('tag_label', esc_html__('Tag', 'directorist'));
    $address_label = get_directorist_option('address_label', esc_html__('Address', 'directorist'));
    $fax_label = get_directorist_option('fax_label', esc_html__('Fax', 'directorist'));
    $email_label = get_directorist_option('email_label', esc_html__('Email', 'directorist'));
    $website_label = get_directorist_option('website_label', esc_html__('Website', 'directorist'));
    $zip_label = get_directorist_option('zip_label', esc_html__('Zip', 'directorist'));
    $currency = get_directorist_option('g_currency', 'USD');
    $c_symbol = atbdp_currency_symbol($currency);
    $search_location_address = get_directorist_option('search_location_address', 'address'); ?>

    <div class="ads_float">
        <div class="ads-advanced">
            <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>"
                  role="form">
                <?php
                if (in_array('search_price', $search_more_filters_fields) || in_array('search_price_range', $search_more_filters_fields)) { ?>
                    <div class="form-group ">
                        <label class=""><?php esc_html_e('Price Range', 'direo'); ?></label>
                        <div class="price_ranges">
                            <?php if (in_array('search_price', $search_more_filters_fields)) { ?>
                                <div class="range_single">
                                    <input type="text" name="price[0]" class="form-control"
                                           placeholder="<?php esc_html_e('Min Price', 'directorist'); ?>"
                                           value="<?php if (isset($_GET['price'])) echo esc_attr($_GET['price'][0]); ?>">
                                </div>
                                <div class="range_single">
                                    <input type="text" name="price[1]" class="form-control"
                                           placeholder="<?php esc_html_e('Max Price', 'directorist'); ?>"
                                           value="<?php if (isset($_GET['price'])) echo esc_attr($_GET['price'][1]); ?>">
                                </div>
                                <?php
                            }
                            if (in_array('search_price_range', $search_more_filters_fields)) { ?>
                                <div class="price-frequency">
                                    <label class="pf-btn">
                                        <input type="radio" name="price_range"
                                               value="bellow_economy"<?php if (isset($_GET['price_range']) && 'bellow_economy' == isset($_GET['price_range'])) {
                                            echo esc_html("checked='checked'");
                                        } ?>>
                                        <span><?php echo esc_attr($c_symbol); ?></span>
                                    </label>
                                    <label class="pf-btn">
                                        <input type="radio" name="price_range"
                                               value="economy" <?php if (isset($_GET['price_range']) && 'economy' == isset($_GET['price_range'])) {
                                            echo esc_html("checked='checked'");
                                        } ?>>
                                        <span><?php echo esc_attr($c_symbol . $c_symbol); ?></span>
                                    </label>
                                    <label class="pf-btn">
                                        <input type="radio" name="price_range"
                                               value="moderate" <?php if (isset($_GET['price_range']) && 'moderate' == isset($_GET['price_range'])) {
                                            echo esc_html("checked='checked'");
                                        } ?>>
                                        <span><?php echo esc_attr($c_symbol . $c_symbol . $c_symbol); ?></span>
                                    </label>
                                    <label class="pf-btn">
                                        <input type="radio" name="price_range"
                                               value="skimming" <?php if (isset($_GET['price_range']) && 'skimming' == isset($_GET['price_range'])) {
                                            echo esc_html("checked='checked'");
                                        } ?>>
                                        <span><?php echo esc_attr($c_symbol . $c_symbol . $c_symbol . $c_symbol); ?></span>
                                    </label>
                                </div>
                                <?php
                            } ?>
                        </div>
                    </div>
                    <?php
                }
                if (in_array('search_rating', $search_more_filters_fields)) { ?>
                    <div class="form-group">
                        <label for="filter-ratings"><?php esc_html_e('Filter by Ratings', 'directorist'); ?></label>
                        <select id="filter-ratings" name='search_by_rating' class="select-basic form-control">
                            <option value=""><?php esc_html_e('Select Ratings', 'directorist'); ?></option>
                            <option value="5" <?php if (isset($_GET['search_by_rating']) && '5' == isset($_GET['search_by_rating'])) {
                                echo esc_html("selected");
                            } ?>>
                                <?php esc_html_e('5 Star', 'directorist'); ?>
                            </option>
                            <option value="4" <?php if (isset($_GET['search_by_rating']) && '4' == isset($_GET['search_by_rating'])) {
                                echo esc_html("selected");
                            } ?>>
                                <?php esc_html_e('4 Star & Up', 'directorist'); ?>
                            </option>
                            <option value="3" <?php if (isset($_GET['search_by_rating']) && '3' == isset($_GET['search_by_rating'])) {
                                echo esc_html("selected");
                            } ?>>
                                <?php esc_html_e('3 Star & Up', 'directorist'); ?>
                            </option>
                            <option value="2" <?php if (isset($_GET['search_by_rating']) && '2' == isset($_GET['search_by_rating'])) {
                                echo esc_html("selected");
                            } ?>>
                                <?php esc_html_e('2 Star & Up', 'directorist'); ?>
                            </option>
                            <option value="1" <?php if (isset($_GET['search_by_rating']) && '1' == isset($_GET['search_by_rating'])) {
                                echo esc_html("selected");
                            } ?>>
                                <?php esc_html_e('1 Star & Up', 'directorist'); ?>
                            </option>
                        </select>
                    </div>
                    <?php
                }

                if (in_array('search_open_now', $search_more_filters_fields) && in_array('directorist-business-hours/bd-business-hour.php', apply_filters('active_plugins', get_option('active_plugins')))) { ?>
                    <div class="form-group">
                        <label><?php esc_html_e('Open Now', 'directorist'); ?></label>
                        <div class="check-btn">
                            <div class="btn-checkbox">
                                <label>
                                    <input type="checkbox" name="open_now"
                                           value="open_now" <?php if (isset($_GET['open_now']) && 'open_now' == isset($_GET['open_now'])) {
                                        echo esc_html("checked='checked'");
                                    } ?>>
                                    <span><i class="fa fa-clock-o"></i><?php esc_html_e('Open Now', 'directorist'); ?> </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                if ('map_api' == $search_location_address && in_array('radius_search', $search_more_filters_fields)) {
                    $default_radius_distance = get_directorist_option('search_default_radius_distance', 0); ?>
                    <div class="form-group">
                        <div class="atbdpr-range rs-primary">
                            <span><?php esc_html_e('Radius Search', 'directorist'); ?></span>
                            <div class="atbd_slider-range-wrapper">
                                <div class="atbd_slider-range"></div>
                                <p class="d-flex justify-content-between">
                                    <span class="atbdpr_amount"></span>
                                </p>
                                <input type="hidden" id="atbd_rs_value" name="miles"
                                       value="<?php echo $default_radius_distance ? esc_attr($default_radius_distance) : 0; ?>">
                            </div>
                        </div>
                    </div>
                    <?php
                }

                if (in_array('search_tag', $search_more_filters_fields)) {
                    $terms = get_terms(ATBDP_TAGS);
                    if ($terms) { ?>
                        <div class="form-group ads-filter-tags">
                            <label><?php echo $tag_label ? esc_attr($tag_label) : esc_html__('Tags', 'directorist'); ?></label>
                            <div class="bads-custom-checks">
                                <?php
                                $rand = rand();
                                foreach ($terms as $term) { ?>
                                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                                        <input type="checkbox" class="custom-control-input"
                                               name="in_tag" value="<?php echo esc_attr($term->term_id); ?>"
                                               id="<?php echo esc_attr($rand . $term->term_id); ?>">
                                        <span class="check--select"></span>
                                        <label for="<?php echo esc_attr($rand . $term->term_id); ?>"
                                               class="custom-control-label">
                                            <?php echo esc_attr($term->name); ?>
                                        </label>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                            <a href="#" class="more-or-less sml">
                                <?php esc_html_e('Show More', 'directorist'); ?>
                            </a>
                        </div>
                        <?php
                    }
                }

                if (in_array('search_custom_fields', $search_more_filters_fields)) { ?>
                    <div id="atbdp-custom-fields-search" class="form-group ads-filter-tags atbdp-custom-fields-search">
                        <?php do_action('wp_ajax_atbdp_custom_fields_search', isset($_GET['in_cat']) ? $_GET['in_cat'] : 0); ?>
                    </div>
                    <?php
                }

                if (in_array('search_website', $search_more_filters_fields) || in_array('search_email', $search_more_filters_fields) || in_array('search_phone', $search_more_filters_fields) || in_array('search_fax', $search_more_filters_fields) || in_array('search_address', $search_more_filters_fields) || in_array('search_zip_code', $search_more_filters_fields)) { ?>

                    <div class="form-group">
                        <div class="bottom-inputs">
                            <?php if (in_array('search_website', $search_more_filters_fields)) { ?>
                                <div>
                                    <input type="text" name="website"
                                           placeholder="<?php echo $website_label ? esc_attr($website_label) : esc_html__('Website', 'directorist'); ?>"
                                           value="<?php echo isset($_GET['website']) ? $_GET['website'] : ''; ?>"
                                           class="form-control">
                                </div>
                                <?php
                            }
                            if (in_array('search_email', $search_more_filters_fields)) { ?>
                                <div>
                                    <input type="text" name="email"
                                           placeholder="<?php echo $email_label ? esc_attr($email_label) : esc_html__('Email', 'directorist'); ?>"
                                           value="<?php echo isset($_GET['email']) ? esc_attr($_GET['email']) : ''; ?>"
                                           class="form-control">
                                </div>
                                <?php
                            }
                            if (in_array('search_phone', $search_more_filters_fields)) { ?>
                                <div>
                                    <input type="text" name="phone"
                                           placeholder="<?php esc_html_e('Phone Number', 'directorist'); ?>"
                                           value="<?php echo isset($_GET['phone']) ? esc_attr($_GET['phone']) : ''; ?>"
                                           class="form-control">
                                </div>
                                <?php
                            }
                            if (in_array('search_fax', $search_more_filters_fields)) { ?>
                                <div>
                                    <input type="text" name="fax"
                                           placeholder="<?php echo $fax_label ? esc_attr($fax_label) : esc_html__('Fax', 'directorist'); ?>"
                                           value="<?php echo isset($_GET['fax']) ? esc_attr($_GET['fax']) : ''; ?>"
                                           class="form-control">
                                </div>
                                <?php
                            }
                            if (in_array('search_address', $search_more_filters_fields)) { ?>
                                <div class="atbdp_map_address_field">
                                    <input type="text" name="address" id="address"
                                           value="<?php echo isset($_GET['address']) ? esc_attr($_GET['address']) : ''; ?>"
                                           placeholder="<?php echo $address_label ? esc_attr($address_label) : esc_html__('Address', 'directorist'); ?>"
                                           class="form-control location-name">
                                    <div id="address_result">
                                        <ul></ul>
                                    </div>
                                    <input type="hidden" id="cityLat" name="cityLat"/>
                                    <input type="hidden" id="cityLng" name="cityLng"/>
                                </div>
                                <?php
                            }
                            if (in_array('search_zip_code', $search_more_filters_fields)) { ?>
                                <div>
                                    <input type="text" name="zip_code"
                                           placeholder="<?php echo $zip_label ? esc_attr($zip_label) : esc_html__('Zip/Post Code', 'directorist'); ?>"
                                           value="<?php echo isset($_GET['zip_code']) ? esc_attr($_GET['zip_code']) : ''; ?>"
                                           class="form-control">
                                </div>
                                <?php
                            } ?>
                        </div>
                    </div>

                    <?php
                }
                if (in_array('search_reset_filters', $search_filters) || in_array('search_apply_filters', $search_filters)) { ?>
                    <div class="bdas-filter-actions">
                        <?php if (in_array('search_reset_filters', $search_filters)) { ?>
                            <button type="reset" class="btn btn-outline btn-outline-primary btn-sm">
                                <?php echo $reset_filters_text ? esc_attr($reset_filters_text) : esc_html__('Reset Filters', 'direo'); ?>
                            </button>
                            <?php
                        }
                        if (in_array('search_apply_filters', $search_filters)) { ?>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <?php echo $apply_filters_text ? esc_attr($apply_filters_text) : esc_html__('Apply Filters', 'direo'); ?>
                            </button>
                            <?php
                        } ?>
                    </div>
                    <?php
                } ?>
            </form>
        </div>
    </div>
    <?php
}

/*=====================================================
   Listing Home Search Button Add
=================================================*/
function direo_search_form_fields($more = 'yes')
{
    if (!class_exists('Directorist_Base')) {
        return;
    }
    $require_text = get_directorist_option('require_search_text') ? "required" : "";
    $require_cat = get_directorist_option('require_search_category') ? "required" : "";
    $require_loc = get_directorist_option('require_search_location') ? "required" : "";

    $search_location_address = get_directorist_option('search_location_address', 'address');

    $search_fields = get_directorist_option('search_tsc_fields', array('search_text', 'search_category', 'search_location'));
    $search_placeholder = get_directorist_option('search_placeholder', esc_attr_x('What are you looking for?', 'placeholder', 'direo-core'));
    $search_category_placeholder = get_directorist_option('search_category_placeholder', esc_html__('Select a category', 'direo-core'));
    $search_location_placeholder = get_directorist_option('search_location_placeholder', esc_html__('Select a location', 'direo-core'));
    $search_listing_text = get_directorist_option('search_listing_text', esc_html__('Search', 'direo-core'));
    $display_more_filter_search = get_directorist_option('search_more_filter', 1);

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

    if (in_array('search_text', $search_fields)) { ?>
        <div class="single_search_field search_query">
            <input class="form-control search_fields" type="text" name="q" <?php echo esc_attr($require_text); ?>
                   autocomplete="off"
                   placeholder="<?php echo esc_html($search_placeholder); ?>">
        </div>
        <?php
    }

    if (in_array('search_category', $search_fields)) { ?>
        <div class="single_search_field search_category">
            <select <?php echo esc_attr($require_cat); ?>
                    name="in_cat" class="search_fields form-control" id="at_biz_dir-category">
                <option value=""><?php echo esc_html($search_category_placeholder); ?></option>
                <?php echo search_category_location_filter($query_args, ATBDP_CATEGORY); ?>
            </select>
        </div>
        <?php
    }

    if (in_array('search_location', $search_fields)) {
        if ('listing_location' == $search_location_address) { ?>
            <div class="single_search_field search_location">
                <select <?php echo esc_attr($require_loc); ?>
                        name="in_loc" class="search_fields form-control" id="at_biz_dir-location">
                    <option value=""><?php echo esc_html($search_location_placeholder); ?></option>
                    <?php echo search_category_location_filter($query_args, ATBDP_LOCATION); ?>
                </select>
            </div>
            <?php
        } else {
            wp_enqueue_script('atbdp-geolocation');
            $address = !empty($_GET['address']) ? $_GET['address'] : ''; ?>
            <div class="single_search_field atbdp_map_address_field">
                <div class="atbdp_get_address_field">
                    <input type="text" id="address" name="address"
                           autocomplete="off"
                           value="<?php echo esc_attr($address); ?>"
                           placeholder="<?php echo esc_html($search_location_placeholder); ?>"
                        <?php echo esc_attr($require_loc); ?>
                           class="form-control location-name">
                    <span class="atbd_get_loc la la-crosshairs"></span>
                </div>
                <?php
                $select_listing_map = get_directorist_option('select_listing_map', 'google');
                if ('google' != $select_listing_map) {
                    echo '<div class="address_result"></div>';
                } ?>

                <input type="hidden" id="cityLat" name="cityLat" value=""/>
                <input type="hidden" id="cityLng" name="cityLng" value=""/>
            </div>
            <?php
        }
    } ?>

    <div class="atbd_submit_btn">
        <button type="submit" class="btn_search">
            <?php echo esc_attr($search_listing_text); ?>
        </button>
        <?php
        if ($more && $display_more_filter_search) { ?>
            <button class="more-filter">
                <span class="<?php atbdp_icon_type(true); ?>-filter"></span>
            </button>
            <?php
        } ?>
    </div>
    <?php
}

add_action('atbdp_search_form_fields', 'direo_search_form_fields');

/*========================================================
   Directorist quick search
/*========================================================*/
function direo_quick_search()
{
    ob_start(); ?>

    <div class="row">
        <div class="col-lg-10 offset-lg-1 quick-search">
            <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form"
                  class="breadcrumb_quick_search">
                <div class="atbd_seach_fields_wrapper">
                    <div class="atbdp-search-form">
                        <?php direo_search_form_fields($more = ''); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('direo_quick_search_form', 'direo_quick_search');

/*=====================================================
   Listing Business Hour badge Move
=================================================*/

function direo_listings_review_price()
{
    if (!class_exists('Directorist_Base')) {
        return;
    }

    $price = get_post_meta(get_the_ID(), '_price', true);
    $is_disable_price = get_directorist_option('disable_list_price');
    $display_review = get_directorist_option('enable_review', 1);
    $display_price = get_directorist_option('display_price', 1);
    $atbd_listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
    $display_pricing_field = get_directorist_option('display_pricing_field', 1);
    $price_range = get_post_meta(get_the_ID(), '_price_range', true);
    $post_id = get_the_ID();
    $bdbh = get_post_meta($post_id, '_bdbh', true);
    $enable247hour = get_post_meta($post_id, '_enable247hour', true);
    $disable_bz_hour_listing = get_post_meta($post_id, '_disable_bz_hour_listing', true);
    $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
    $plan_hours = true; ?>
    <div class="atbd_listing_meta">
        <?php
        if ($display_review || $display_price && ($price || $price_range)) {

            if ($display_review) {
                $average = ATBDP()->review->get_average(get_the_ID());
                echo sprintf('<span class="atbd_meta atbd_listing_rating">%s<i class="%s-star"></i></span>', esc_attr($average), atbdp_icon_type());
            }

            $atbd_listing_pricing = $atbd_listing_pricing ? $atbd_listing_pricing : '';

            if ($display_price && $display_pricing_field) {
                if ($price_range && ('range' === $atbd_listing_pricing)) {
                    echo atbdp_display_price_range($price_range);
                } else {
                    echo atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                }
            }

        } ?>

        <span class="atbd_upper_badge">
            <?php
            if (is_fee_manager_active()) {
                $plan_hours = is_plan_allowed_business_hours(get_post_meta($post_id, '_fm_plans', true));
            }
            if (is_business_hour_active() && $plan_hours && !$disable_bz_hour_listing) {
                $open = get_directorist_option('open_badge_text', esc_html__('Open Now', 'direo-core'));
                if ($enable247hour) {
                    echo sprintf(' <span class="atbd_badge atbd_badge_open">%s</span>', esc_attr($open));
                } else {
                    echo BD_Business_Hour()->show_business_open_close($business_hours);
                }
            } ?>
        </span>

    </div>
    <?php
}

add_filter('atbdp_listings_review_price', 'direo_listings_review_price');
add_filter('atbdp_listings_list_review_price', 'direo_listings_review_price');


/*=====================================================
   Listing Business Featured badge Move
======================================================*/

function direo_upper_badge()
{
    if (!class_exists('Directorist_Base')) {
        return;
    }

    $featured = get_post_meta(get_the_ID(), '_featured', true);
    $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
    $feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');
    $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
    $popular_listing_id = atbdp_popular_listings(get_the_ID()); ?>

    <span class="atbd_upper_badge">
        <?php
        echo new_badge();
        if ($featured && !empty($display_feature_badge_cart)) {
            echo sprintf('<span class="atbd_badge atbd_badge_featured">%s</span>', esc_attr($feature_badge_text));
        }
        if ($popular_listing_id === get_the_ID()) {
            echo sprintf('<span class="atbd_badge atbd_badge_popular">%s</span>', esc_attr($popular_badge_text));
        } ?>
    </span>
    <?php
}

add_filter('atbdp_upper_badges', 'direo_upper_badge', 10, 1);

/*=====================================================
       Sidebar Name
=================================================*/

function direo_right_sidebar_name()
{
    return esc_html__('Single Listing Widgets', 'direo-core');
}

add_filter('atbdp_right_sidebar_name', 'direo_right_sidebar_name');


/*=====================================================
       All listing sidebar
=================================================*/

if (is_active_sidebar('all_listing')) {

    function direo_before_grid_listings_loop()
    {
        echo wp_kses_post('<div class="row"><div class="col-lg-4 order-lg-0 order-1 mt-5 mt-lg-0 atbd_sidebar">');
        dynamic_sidebar('all_listing');
        echo wp_kses_post('</div><div class="col-lg-8 col-md-12">');
    }

    add_action('atbdp_before_grid_listings_loop', 'direo_before_grid_listings_loop');


    /*=====================================================
       Close listing grid vew sidebar div
    =================================================*/
    function direo_after_grid_listings_loop()
    {
        echo wp_kses_post('</div></div>');
    }

    add_action('atbdp_after_grid_listings_loop', 'direo_after_grid_listings_loop');


    /*=====================================================
       Add Sidebar In Listing List View
    =================================================*/
    function direo_before_list_listings_loop()
    {
        echo wp_kses_post('<div class="listing-list-views"><div class="row"><div class="col-lg-4 order-lg-0 order-1 mt-5 mt-lg-0 atbd_sidebar">');
        dynamic_sidebar('all_listing');
        echo wp_kses_post('</div><div class="col-lg-8 col-md-12">');
    }

    add_action('atbdp_before_list_listings_loop', 'direo_before_list_listings_loop');
}


/*=====================================================
   Close listing grid vew sidebar div
=================================================*/
function direo_after_list_listings_loop()
{
    echo wp_kses_post('</div></div></div>');
}

add_action('atbdp_after_list_listings_loop', 'direo_after_list_listings_loop');

/*=====================================================
   Add Single Listing Description Content
=================================================*/
function direo_before_listing_section()

{
    global $post;
    $image_links_thumbnails = [];
    $listing_prv_img = $listing_img = '';

    $listing_info['listing_prv_img'] = get_post_meta($post->ID, '_listing_prv_img', true);
    $listing_info['listing_img'] = get_post_meta($post->ID, '_listing_img', true);
    extract($listing_info);
    $display_prv_image = get_directorist_option('dsiplay_prv_single_page', 1);
    $display_slider_image = get_directorist_option('dsiplay_slider_single_page', 1);
    $gallery_cropping = get_directorist_option('gallery_cropping', 1);
    $custom_gl_width = get_directorist_option('gallery_crop_width', 670);
    $custom_gl_height = get_directorist_option('gallery_crop_height', 750);
    $listing_imgs = $listing_img && $display_slider_image ? $listing_img : array();
    $image_links = [];

    foreach ($listing_imgs as $id) {
        if ($gallery_cropping) {
            $image_links[$id] = atbdp_image_cropping($id, $custom_gl_width, $custom_gl_height, true, 100)['url'];
        } else {
            $image_links[$id] = wp_get_attachment_image_src($id, 'large')[0];
        }

        $image_links_thumbnails[$id] = wp_get_attachment_image_src($id, 'thumbnail')[0]; // store the attachment id and url
    }
    wp_reset_postdata();

    $title = get_directorist_option('direo_details_text');

    if ($image_links) {
        if ($listing_prv_img && $display_prv_image) {
            if ($gallery_cropping) {
                $listing_prv_imgurl = atbdp_image_cropping($listing_prv_img, $custom_gl_width, $custom_gl_height, true, 100)['url'];
            } else {
                $listing_prv_imgurl = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
            }
            array_unshift($image_links, $listing_prv_imgurl);
        } ?>
        <div class="atbd_content_module atbd_listing_details atbd_listing_gallery">
            <?php
            if ($title) { ?>
                <div class="atbd_content_module__tittle_area">
                    <div class="atbd_area_title">
                        <h4><span class="la la-image"></span> <?php echo esc_attr($title); ?> </h4>
                    </div>
                </div>
                <?php
            } ?>
            <div class="atbdb_content_module_contents">
                <div class="atbd_directry_gallery_wrapper">
                    <div class="atbd_big_gallery">
                        <div class="atbd_directory_gallery">
                            <?php
                            if ($image_links) {
                                foreach ($image_links as $image_link) { ?>
                                    <div class="single_image">
                                        <?php
                                        $image_link = $image_link ? $image_link : '';
                                        echo sprintf('<img src="%s" alt="%s">', esc_url($image_link), esc_attr(get_the_title())); ?>
                                    </div>
                                    <?php
                                }
                                wp_reset_postdata();
                            } ?>
                        </div>
                        <?php
                        if (count($image_links) > 1) {
                            echo sprintf('<span class="prev %s-angle-left"></span>', atbdp_icon_type(false));
                            echo sprintf('<span class="next %s-angle-right"></span>', atbdp_icon_type(false));
                        } ?>
                    </div>
                    <div class="atbd_directory_image_thumbnail">
                        <?php
                        $listing_prv_imgurl_thumb = wp_get_attachment_image_src($listing_prv_img, 'thumbnail')['0'];
                        if ($listing_prv_imgurl_thumb && $display_prv_image) {
                            array_unshift($image_links_thumbnails, $listing_prv_imgurl_thumb);
                        }
                        foreach ($image_links_thumbnails as $image_links_thumbnail) {
                            sprintf('<div class="single_thumbnail"><img src="%s" alt="%s"></div>', esc_url($image_links_thumbnail), esc_attr(get_the_title()));
                            if (!is_multiple_images_active()) break;
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

add_action('atbdp_after_single_listing_details_section', 'direo_before_listing_section');


/*=====================================================
   Directorist single listing details title
=================================================*/

function direo_single_listings_settings_fields($settings)
{
    $new_setting = array(
        'type' => 'textbox',
        'name' => 'direo_details_text',
        'label' => esc_html__('Section Title of Listing Gallery', 'direo-core'),
        'default' => esc_html__('Gallery', 'direo-core'),
    );
    array_push($settings, $new_setting);
    return $settings;
}

add_filter('atbdp_single_listings_settings_fields', 'direo_single_listings_settings_fields');

/*=====================================================
   Directorist all listing cat name
=================================================*/
function direo_all_categories_after_category_name($html, $term)
{
    $count = atbdp_listings_count_by_category($term->term_id);

    $expired_listings = atbdp_get_expired_listings(ATBDP_CATEGORY, $term->term_id);
    $number_of_expired = $expired_listings->post_count;
    $number_of_expired = !empty($number_of_expired) ? $number_of_expired : '0';
    $total = ($count) ? ($count - $number_of_expired) : $count;

    $categories_settings['show_count'] = get_directorist_option('display_listing_count', 1);
    if (!empty($categories_settings['show_count'])) {
        if (1 < $total) {
            return sprintf('<span class="badge badge-pill badge-success">%s</span>', esc_attr($total) . esc_html__(' Listings', 'direo-core'));
        } else {
            return sprintf('<span class="badge badge-pill badge-success">%s</span>', esc_attr($total) . esc_html__(' Listing', 'direo-core'));
        }
    }
}

add_filter('atbdp_all_categories_after_category_name', 'direo_all_categories_after_category_name', 10, 2);

/*=====================================================
   Directorist all location name
=================================================*/
function direo_all_locations_after_location_name($html, $term)
{
    $count = atbdp_listings_count_by_location($term->term_id);

    $expired_listings = atbdp_get_expired_listings(ATBDP_LOCATION, $term->term_id);
    $number_of_expired = $expired_listings->post_count;
    $number_of_expired = !empty($number_of_expired) ? $number_of_expired : '0';
    $total = ($count) ? ($count - $number_of_expired) : $count;

    $locations_settings['show_count'] = get_directorist_option('display_location_listing_count', 1);
    if (!empty($locations_settings['show_count'])) {
        return 1 < $total ? sprintf('<p>%s</p>', $total . esc_html__(' Listings', 'direo-core')) : sprintf('<p>%s</p>', $total . esc_html__(' Listing', 'direo-core'));
    }

}

add_filter('atbdp_all_locations_after_location_name', 'direo_all_locations_after_location_name', 10, 2);

/*========================================================
   Directorist atbdp_search_listing dependency maintain
=========================================================*/
function direo_search_listing_jquery_dependency($search_dependency)
{
    $dependency = array('bootstrap');
    array_push($search_dependency, $dependency);

    return $dependency;
}

add_filter('atbdp_search_listing_jquery_dependency', 'direo_search_listing_jquery_dependency');


/*========================================================
   Directorist atbdp_search_listing dependency maintain
=========================================================*/
function my_login_fail()
{
    $referrer = $_SERVER['HTTP_REFERER'];
    if ($referrer && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
        wp_redirect($referrer . '?login=failed');
        exit;
    }
}

add_action('wp_login_failed', 'my_login_fail');

/*========================================================
    Login and Register popup
/*========================================================*/
$quick_log_reg = get_theme_mod('quick_log_reg', true);
if ($quick_log_reg) {

    function direo_listing_form_login_link()
    {
        $login_url = get_theme_mod('login_btn_url', false);
        $login = get_theme_mod('login_btn', 'Sign in');
        if ($login_url) {
            return sprintf('<a href="%s" class="access-link">%s</a>', esc_url($login_url), esc_attr($login));
        } else {
            return sprintf('<a href="#" class="access-link" data-toggle="modal" data-target="#login_modal">%s</a>', esc_attr($login));
        }
    }

    add_filter('atbdp_listing_form_login_link', 'direo_listing_form_login_link');
    add_filter('atbdp_user_dashboard_login_link', 'direo_listing_form_login_link');
    add_filter('atbdp_review_login_link', 'direo_listing_form_login_link');
    add_filter('atbdp_claim_now_login_link', 'direo_listing_form_login_link');
    add_filter('atbdp_login_page_link', 'direo_listing_form_login_link');

    function direo_listing_form_signup_link()
    {
        $register_url = get_theme_mod('register_btn_url', false);
        $register = get_theme_mod('register_btn', 'Sign Up');

        if ($register_url) {
            return sprintf('<a href="%s" class="access-link">%s</a>', esc_url($register_url), esc_attr($register));
        } else {
            return sprintf('<a href="#" class="access-link" data-toggle="modal"  data-target="#signup_modal">%s</a>', esc_attr($register));
        }
    }

    add_filter('atbdp_listing_form_signup_link', 'direo_listing_form_signup_link');
    add_filter('atbdp_user_dashboard_signup_link', 'direo_listing_form_signup_link');
    add_filter('atbdp_review_signup_link', 'direo_listing_form_signup_link');
    add_filter('atbdp_claim_now_registration_link', 'direo_listing_form_signup_link');
    add_filter('atbdp_signup_page_link', 'direo_listing_form_signup_link');
}


function replace_in_content($content, $order_id = 0, $listing_id = 0, $user = null)
{
    if (!$listing_id) {
        $listing_id = (int)get_post_meta($order_id, '_listing_id', true);
    }
    if (!$user) {
        $post_author_id = get_post_field('post_author', $listing_id);
        $user = get_userdata($post_author_id);
    } else {
        if (!$user instanceof WP_User) {
            $user = get_userdata((int)$user);
        }
    }

    $site_name = get_option('blogname');
    $site_url = site_url();
    $date_format = get_option('date_format');
    $time_format = get_option('time_format');
    $current_time = current_time('timestamp');
    $find_replace = array(
        '==NAME==' => !empty($user->display_name) ? $user->display_name : '',
        '==USERNAME==' => !empty($user->user_login) ? $user->user_login : '',
        '==SITE_NAME==' => $site_name,
        '==SITE_LINK==' => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
        '==SITE_URL==' => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
        '==TODAY==' => date_i18n($date_format, $current_time),
        '==NOW==' => date_i18n($date_format . ' ' . $time_format, $current_time),
    );
    $c = nl2br(strtr($content, $find_replace));

    return $c;

}

function custom_wp_new_user_notification_email($wp_new_user_notification_email, $user, $blogname)
{
    $user_password = get_user_meta($user->ID, '_atbdp_generated_password', true);

    $sub = get_directorist_option('email_sub_registration_confirmation', __('Registration Confirmation!', 'direo-core'));
    $body = get_directorist_option('email_tmpl_registration_confirmation', __("
Dear User,

Congratulations! Your registration is completed!

This email is sent automatically for information purpose only. Please do not respond to this.
You can login now using the below credentials:

", 'direo-core'));
    $body = replace_in_content($body, null, null, $user);
    $wp_new_user_notification_email['subject'] = sprintf('%s', $sub);
    $wp_new_user_notification_email['message'] = preg_replace("/<br \/>/", "", $body) . "
                
" . __('Username:', 'direo-core') . " $user->user_login
" . __('Password:', 'direo-core') . " $user_password";
    return $wp_new_user_notification_email;

}

function atbdp_wp_mail_from_name()
{
    $site_name = get_option('blogname');
    return $site_name;
}

add_filter('wp_new_user_notification_email', 'custom_wp_new_user_notification_email', 10, 3);
add_filter('wp_mail_from_name', 'atbdp_wp_mail_from_name');


/*========================================================
    All Listing Location and Category image size
/*========================================================*/


function direo_location_image_size()
{
    return array(545, 270);
}

add_filter('atbdp_location_image_size', 'direo_location_image_size');

function direo_category_image_size()
{
    return array(350, 280);
}

add_filter('atbdp_category_image_size', 'direo_category_image_size');

/*========================================================
    replace list view container class
/*========================================================*/
function direo_list_view_container()
{
    return esc_html('list_view_container');
}

add_filter('list_view_container', 'direo_list_view_container');

/*========================================================
    Remove category container fluid
/*========================================================*/


function direo_cat_container_fluid()
{
    return esc_html('row');
}

add_filter('atbdp_cat_container_fluid', 'direo_cat_container_fluid');


/*========================================================
    removed unnecessary hook
/*========================================================*/

function direo_remove_unnecessary_hook()
{
    return;
}

add_filter('atbdp_search_home_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_extension_license_active_submenu_permission', 'direo_remove_unnecessary_hook');
add_filter('atbdp_public_profile_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_public_profile_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_login_message_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_add_listing_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_payment_receipt_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_registration_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_deshboard_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_listings_header_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_listings_grid_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_single_cat_header_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_single_cat_grid_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_single_cat_grid_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_single_loc_header_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_single_loc_grid_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_single_tag_header_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_single_tag_header_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_single_tag_grid_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_search_result_header_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_search_result_grid_container_fluid', 'direo_remove_unnecessary_hook');
add_filter('atbdp_map_container', 'direo_remove_unnecessary_hook');
add_filter('atbdp_search_listing_button', 'direo_remove_unnecessary_hook');
add_filter('atbdp_grid_lower_badges', 'direo_remove_unnecessary_hook', 10, 1);
add_filter('atbdp_single_lower_badges', 'direo_remove_unnecessary_hook', 10, 1);
add_filter('atbdp_single_listing_gallery_section', 'direo_remove_unnecessary_hook');
add_filter('atbdp_header_before_image_slider', 'direo_remove_unnecessary_hook');
add_filter('atbdp_before_listing_title', 'direo_remove_unnecessary_hook');
add_filter('atbdp_listing_title', 'direo_remove_unnecessary_hook');
add_filter('atbdp_listing_tagline', 'direo_remove_unnecessary_hook');
add_filter('include_style_settings', 'direo_remove_unnecessary_hook');

/*========================================================
    removed all unnecessary options
/*========================================================*/

function direo_remove_gateway_settings($settings)
{
    unset($settings['gateway_promotion']);
    return $settings;
}

add_filter('atbdp_gateway_settings_fields', 'direo_remove_gateway_settings');

function direo_remove_extension_promotion_settings($settings)
{
    unset($settings['extension_promotion_set']);
    return $settings;
}

add_filter('atbdp_extension_settings_fields', 'direo_remove_extension_promotion_settings');


function direo_search_result_settings_fields($settings)
{
    unset($settings['search_view_as']);
    unset($settings['search_view_as_items']);
    unset($settings['search_sort_by']);
    return $settings;
}

add_filter('atbdp_search_result_settings_fields', 'direo_search_result_settings_fields');

function direo_pages_settings_fields($settings)
{
    unset($settings['single_listing_page']);
    return $settings;
}

add_filter('atbdp_pages_settings_fields', 'direo_pages_settings_fields');

function direo_create_custom_pages($settings)
{
    unset($settings['single_listing_page']);
    return $settings;
}

add_filter('atbdp_create_custom_pages', 'direo_create_custom_pages');

function direo_remove_custom_pages($settings)
{
    unset($settings['single_listing_page']);
    return $settings;
}

add_filter('atbdp_pages_settings_fields', 'direo_remove_custom_pages');

function direo_general_listings_submenus($settings)
{
    unset($settings['style_setting']);
    return $settings;
}

add_filter('atbdp_general_listings_submenus', 'direo_general_listings_submenus');


function direo_listings_settings_fields($settings)
{
    unset($settings['listings_display_filter']);
    return $settings;
}

add_filter('atbdp_search_settings_fields', 'direo_listings_settings_fields');

function direo_search_settings_fields($settings)
{
    unset($settings['search_home_background']);
    return $settings;
}

add_filter('atbdp_search_settings_fields', 'direo_search_settings_fields');

function direo_atbdp_settings_menus($settings)
{
    unset($settings['style_settings_menu']);
    return $settings;
}

add_filter('atbdp_settings_menus', 'direo_atbdp_settings_menus');


function single_listing_template($settings)
{
    unset($settings['single_listing_template']);
    return $settings;
}

//add_filter('atbdp_single_listings_settings_fields', 'single_listing_template');

/*=====================================================
   improved Listing cart bottom area
=================================================*/
function direo_listing_cat()
{
    $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
    $display_category = get_directorist_option('display_category', 1);
    if ($display_category) {
        if ($cats) { ?>
            <div class="atbd_content_left">
                <div class="atbd_listting_category">
                    <?php
                    $cat_icon = '';
                    $category_icon = !empty($cats) ? get_cat_icon($cats[0]->term_id) : atbdp_icon_type() . '-tags';
                    $icon_type = substr($category_icon, 0, 2);
                    $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                    if ('no' != $icon_type) {
                        $cat_icon = sprintf('<span class="%s"></span>', esc_attr($icon));
                    }
                    echo sprintf('<a href="%s">%s %s</a>', ATBDP_Permalink::atbdp_get_category_page($cats[0]), $cat_icon, $cats[0]->name);

                    $totalTerm = count($cats);
                    if ($totalTerm > 1) { ?>
                        <div class="atbd_cat_popup">
                            <?php echo sprintf('<span>%s%s</span>', esc_html('+'), esc_attr($totalTerm - 1)) ?>
                            <div class="atbd_cat_popup_wrapper">
                                <?php
                                $output = array();
                                foreach (array_slice($cats, 1) as $cat) {
                                    $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                    $space = str_repeat(' ', 1);

                                    $category_icon = !empty($cats) ? get_cat_icon($cat->term_id) : atbdp_icon_type() . '-tags';
                                    $icon_type = substr($category_icon, 0, 2);
                                    $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;

                                    $output[] = sprintf("%s<span><i class='%s'></i><a href='%s'>%s<span>,</span></a></span>", esc_attr($space), esc_attr($icon), esc_url($link), esc_attr($cat->name));

                                }
                                wp_reset_postdata();
                                echo join($output); ?>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
            <?php
        } else { ?>
            <div class="atbd_content_left">
                <div class="atbd_listting_category">
                    <?php echo sprintf('<a href="#"> <span class="%s-tags"></span>%s</a>', atbdp_icon_type(false), esc_html__('Uncategorized', 'direo-core')) ?>
                </div>
            </div>
            <?php
        }
    }
}

add_filter('atbdp_grid_footer_catViewCount', 'direo_listing_cat');


/*=====================================================
  Add author section before listing list view title
=================================================*/
function direo_list_view_author()
{
    $display_author_image = get_directorist_option('display_author_image', 1);
    if ($display_author_image) {
        $author_id = get_the_author_meta('ID');
        $author = get_userdata($author_id);
        $u_pro_pic_id = get_user_meta($author_id, 'pro_pic', true);
        $u_pro_pic = wp_get_attachment_image_src($u_pro_pic_id, 'thumbnail');
        $avatar_img = get_avatar($author_id, 32); ?>
        <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link($author_id); ?>" class="atbd_tooltip"
           aria-label="<?php echo $author->first_name . ' ' . $author->last_name; ?>">
            <?php
            if ($u_pro_pic) {
                echo sprintf('<img class="c_tooltip" src="%s" alt="%s">', esc_url($u_pro_pic[0]), direo_get_image_alt($u_pro_pic_id));
            } else {
                echo wp_kses_post($avatar_img);
            } ?>
        </a>
        <?php
    }
}

/*=====================================================
   Listing grid view and list view footer section
=================================================*/
function direo_listing_grid_footer_content()
{
    $display_view_count = get_directorist_option('display_view_count', 1);
    $post_view = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
    $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
    $display_category = get_directorist_option('display_category', 1);
    if ($cats && $display_category) { ?>
        <div class="atbd_listing_bottom_content">
            <?php direo_listing_cat();
            if ($display_view_count) { ?>
                <ul class="atbd_content_right">
                    <li class="atbd_count">
                        <span class="<?php echo atbdp_icon_type() ?>-eye"></span>
                        <?php echo $post_view ? esc_attr($post_view) : 0; ?>
                    </li>
                </ul>
                <?php
            } ?>
        </div>
        <?php
    }
}

add_filter('atbdp_listings_grid_cat_view_count', 'direo_listing_grid_footer_content');

function direo_listing_grid_list_footer_content()
{
    $display_view_count = get_directorist_option('display_view_count', 1); ?>
    <div class="atbd_listing_bottom_content">
        <?php direo_listing_cat();
        $post_view = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
        if ($display_view_count) { ?>
            <ul class="atbd_content_right">
                <li class="atbd_count">
                    <?php
                    echo sprintf('<span class="%s-eye"></span>', atbdp_icon_type());
                    echo $post_view ? esc_attr($post_view) : 0; ?>
                </li>
                <li class="atbd_author"><?php direo_list_view_author() ?></li>
            </ul>
            <?php
        } ?>
    </div>
    <?php
}

add_filter('atbdp_listings_list_cat_view_count_author', 'direo_listing_grid_list_footer_content');


/*=====================================================
   listing with map view copyright section
========================================================*/
function direo_footer_listing_with_map()
{
    $footer_style = get_post_meta(get_the_ID(), 'footer_style', true);
    $default = '©' . date('Y') . ' Direo. Made with <span class="la la-heart-o"></span> by <a href="#">AazzTech</a>';
    $copy_right = get_theme_mod('copy_right', $default);

    echo sprintf('<div class="listing_map_footer bg-%s">%s</div>', esc_attr($footer_style), apply_filters('get_the_content', $copy_right));

}

add_action('bdmv-after-listing', 'direo_footer_listing_with_map');

/*=====================================================
   Search Listing found title
========================================================*/
function direo_listing_search_title($result_title)
{
    $title = '';
    $query = (isset($_GET['q']) && ('' !== $_GET['q'])) ? ucfirst($_GET['q']) : '';
    $category = (isset($_GET['in_cat']) && ('' !== $_GET['in_cat'])) ? ucfirst($_GET['in_cat']) : '';
    $location = (isset($_GET['in_loc']) && ('' !== $_GET['in_loc'])) ? ucfirst($_GET['in_loc']) : '';
    $category = get_term_by('id', $category, ATBDP_CATEGORY);
    $location = get_term_by('id', $location, ATBDP_LOCATION);

    $in_s_string_text = !empty($query) ? sprintf(esc_html__('%s', 'direo-core'), $query) : '';
    $in_cat_text = !empty($category) ? sprintf(esc_html__(' %s %s ', 'direo-core'), !empty($query) ? '<span>' . esc_html__('from', 'direo-core') . '</span>' : '', $category->name) : '';
    $in_loc_text = !empty($location) ? sprintf(esc_html__('%s %s', 'direo-core'), !empty($query) ? '<span>' . esc_html__('in', 'direo-core') . '</span>' : '', $location->name) : '';

    if ($query || $category || $location) {
        $title = $in_s_string_text . $in_cat_text . $in_loc_text;
    }

    return sprintf(esc_html__($result_title, 'direo-core') . '%s', wp_kses_post($title));
}

/*=====================================================
   Listing header title
========================================================*/
function direo_after_filter_button_in_listings_header($html, $ex_title)
{
    if (function_exists('direo_directorist_pages') && direo_directorist_pages('search_result_page')) {
        return sprintf('<div class="listing-header"><h4>%s</h4>%s</div>', direo_listing_search_title(false), $ex_title);
    } else {
        return sprintf('<div class="listing-header"><h4>%s</h4>%s</div>', esc_html__('All Items', 'direo-core'), $ex_title);
    }
}

add_action('atbdp_total_listings_found_text', 'direo_after_filter_button_in_listings_header', 10, 2);

/*=====================================================
   Author avatar size of author page
========================================================*/
function direo_avatar_size()
{
    return 120;
}

add_filter('atbdp_avatar_size', 'direo_avatar_size');


/*========================================================
    User Dashboard page
/*========================================================*/
function direo_update_user_profile()
{

    // process the data and the return a success

    if (valid_js_nonce()) {
        // passed the security
        // update the user data and also its meta
        $success = ATBDP()->user->update_profile($_POST['user']); // update_profile() will handle sanitisation, so we can just the pass the data through it
        if ($success) {
            wp_send_json_success(array('message' => esc_html__('Profile updated successfully', 'directorist')));
        } else {
            wp_send_json_error(array('message' => esc_html__('Ops! something went wrong. Try again.', 'directorist')));
        };
    }
    wp_die();
}

add_action('wp_ajax_update_user_profile', 'direo_update_user_profile');

function direo_remove_listing()
{
    // delete the listing from here. first check the nonce and then delete and then send success.
    // save the data if nonce is good and data is valid
    if (valid_js_nonce() && !empty($_POST['listing_id'])) {
        $pid = (int)$_POST['listing_id'];
        // Check if the current user is the owner of the post
        $listing = get_post($pid);
        // delete the post if the current user is the owner of the listing
        if (get_current_user_id() == $listing->post_author || current_user_can('delete_at_biz_dirs')) {

            $success = ATBDP()->listing->db->delete_listing_by_id($pid);
            if ($success) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    } else {

        echo 'error';
        // show error message
    }

    wp_die();
}

add_action('wp_ajax_direo_remove_listing', 'direo_remove_listing');

/**
 * Add or Remove favourites.
 */
function direo_public_add_remove_favorites_all()
{
    $user_id = get_current_user_id();
    $post_id = (int)$_POST['post_id'];

    if (!$user_id) {
        $data = "login_required";
        echo esc_attr($data);
        wp_die();
    }

    $favourites = (array)get_user_meta($user_id, 'atbdp_favourites', true);

    if (in_array($post_id, $favourites)) {
        if (($key = array_search($post_id, $favourites)) !== false) {
            unset($favourites[$key]);
        }
    } else {
        $favourites[] = $post_id;
    }

    $favourites = array_filter($favourites);
    $favourites = array_values($favourites);

    delete_user_meta($user_id, 'atbdp_favourites');
    update_user_meta($user_id, 'atbdp_favourites', $favourites);

    $favourites = (array)get_user_meta(get_current_user_id(), 'atbdp_favourites', true);
    if (in_array($post_id, $favourites)) {
        $data = $post_id;
    } else {
        $data = false;
    }
    echo wp_json_encode($data);
    wp_die();
}

add_action('wp_ajax_direo_public_add_remove_favorites', 'direo_public_add_remove_favorites_all');
add_action('wp_ajax_nopriv_direo_public_add_remove_favorites', 'direo_public_add_remove_favorites_all');

//Dashboard
function direo_attribute_in_dashboard_package_tab()
{
    echo wp_kses_post('id="v-pills-packages-tab" data-toggle="pill" href="#v-packages-tab" role="tab" aria-controls="v-packages-tab" aria-selected="false"');
}

add_action('atbdp_attribute_in_dashboard_package_tab', 'direo_attribute_in_dashboard_package_tab');

function direo_package_tab_text_in_dashboard()
{
    return sprintf('<i class="la la-money-bill"></i>%s', esc_html__('Packages', 'direo-core'));
}

add_filter('atbdp_package_tab_text_in_dashboard', 'direo_package_tab_text_in_dashboard');

function direo_dashboard_package_content_div_attributes()
{
    return wp_kses_post('class="tab-pane fade" id="v-packages-tab" role="tabpanel" aria-labelledby="v-pills-packages-tab"');
}

add_filter('atbdp_dashboard_package_content_div_attributes', 'direo_dashboard_package_content_div_attributes');

//=============================================

function direo_attribute_in_dashboard_order_history_tab()
{
    echo wp_kses_post('id="v-pills-history-tab" data-toggle="pill" href="#v-history-tab" role="tab" aria-controls="v-history-tab" aria-selected="false"');
}

add_action('atbdp_attribute_in_dashboard_order_history_tab', 'direo_attribute_in_dashboard_order_history_tab');

function direo_order_history_tab_text_in_dashboard()
{
    return sprintf('<i class="la la-history"></i>%s', esc_html__('Order History', 'direo-core'));
}

add_filter('atbdp_order_history_tab_text_in_dashboard', 'direo_order_history_tab_text_in_dashboard');

// Fires when user have not active package
function direo_no_package_found_text()
{
    return '<main class="page-content">
    <div class="container-fluid">
        <div class="page-content-header">
            <h2>' . esc_html__('Packages', 'direo') . '</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">' . esc_html__('Home', 'direo') . '</a> </li>
                    <li class="breadcrumb-item active" aria-current="page">' . esc_html__('Packages', 'direo') . '</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="atbdb_content_module_contents">
                    <div class="table-inner">
                        <p class="atbdp_nlf direo-dashboard-no-listing">' . esc_html__('Looks like you have not any active package yet!', 'direo') . '</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>';
}

add_filter('atbdp_no_package_found_text', 'direo_no_package_found_text');

// Fires when user have not active package
function direo_no_order_found_text()
{
    return '<main class="page-content">
    <div class="container-fluid">
        <div class="page-content-header">
            <h2>' . esc_html__('Order History', 'direo') . '</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">' . esc_html__('Home', 'direo') . '</a> </li>
                    <li class="breadcrumb-item active" aria-current="page">' . esc_html__('Order History', 'direo') . '</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="atbdb_content_module_contents">
                    <div class="table-inner">
                        <p class="atbdp_nlf direo-dashboard-no-listing">' . esc_html__('Looks like you have not any order yet!', 'direo') . '</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>';
}

add_filter('atbdp_no_order_found_text', 'direo_no_order_found_text');

function direo_dashboard_orderHistory_content_div_attributes()
{
    return wp_kses_post('class="tab-pane fade" id="v-history-tab" role="tabpanel" aria-labelledby="v-pills-history-tab"');
}

add_filter('atbdp_dashboard_orderHistory_content_div_attributes', 'direo_dashboard_orderHistory_content_div_attributes');

function direo_li_attribute_in_dashboard_package_tab()
{
    return esc_html('class=sidebar-dropdown');
}

add_filter('atbdp_li_attribute_in_dashboard_package_tab', 'direo_li_attribute_in_dashboard_package_tab');
add_filter('atbdp_li_attribute_in_dashboard_order_tab', 'direo_li_attribute_in_dashboard_package_tab');

function direo_plan_change_link_in_user_dashboard($link, $listing_id)
{
    $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id);
    return sprintf('<span><a data-target="' . $modal_id . '" class="atpp_change_plan" data-listing_id="%s" href="">%s</a></span>', esc_attr($listing_id), esc_html__('Change', 'direo-core'));
}

add_filter('atbdp_plan_change_link_in_user_dashboard', 'direo_plan_change_link_in_user_dashboard', 10, 2);

// payment receipt link
function direo_payment_receipt_button_link($link, $order_id)
{
    $new_l_status = get_directorist_option('new_listing_status', 'pending');
    $listing_id = get_post_meta($order_id, '_listing_id', true);
    $need_post = get_post_meta($listing_id, '_need_post', true);
    if ('yes' === $need_post) {
        if ('pending' === $new_l_status) {
            return $link . '/#n-active-tab';
        } else {
            return $link . '/#n-pending-tab';
        }
    } else {
        if ('pending' === $new_l_status) {
            return $link . '/#v-active-tab';
        } else {
            return $link . '/#v-pending-tab';
        }
    }
}

add_action('atbdp_payment_receipt_button_link', 'direo_payment_receipt_button_link', 10, 2);

function direo_before_order_table()
{
    echo sprintf('<h2>%s</h2>', __('Order History', 'direo'));
}

add_action('atbdp_before_order_table', 'direo_before_order_table');

function direo_before_package_table()
{
    echo sprintf('<h2>%s</h2>', __('Packages', 'direo'));
}

add_action('atbdp_before_package_table', 'direo_before_package_table');

function direo_set_user_dashboard_page($page_template)
{
    $dashboardPageId = class_exists('Directorist_Base') ? get_directorist_option('user_dashboard') : '';
    global $post;
    $page_id = $post->ID;
    switch ($page_id) {
        case $dashboardPageId:
            $args = array(
                'ID' => $page_id,
                'post_content' => '',
            );
            update_post_meta($page_id, '_wp_page_template', 'template-parts/dashboard.php');
            wp_update_post($args);
            $page_template = get_template_directory() . '/template-parts/dashboard.php';
            break;
    }
    return $page_template;
}

add_action('page_template', 'direo_set_user_dashboard_page');

/*=====================================================
   Demo importing
========================================================*/
function direo_page_creation()
{
    if (isset($_GET['direo_create_page'])) {
        atbdp_create_required_pages();
        update_user_meta(get_current_user_id(), '_atbdp_shortcode_regenerate_notice', 'false');
        if (class_exists('ATBDP_Pricing_Plans')) {
            atpp_create_required_pages();
        }
        if (class_exists('DWPP_Pricing_Plans')) {
            dwpp_create_required_pages();
        }
        set_transient('direo-page-creation-notice', true, 2);
    }
    if (isset($_GET['direo_demo_import'])) {
        update_option('direo_demo_import', 1);
    }
}

add_action('init', 'direo_page_creation', 100);

function direo_page_creation_notice()
{
    if ((get_option('atbdp_pages_version') < 1) && (get_option('direo_demo_import') < 1)) {
        $link = add_query_arg('direo_demo_import', 'true', admin_url() . '/themes.php?page=pt-one-click-demo-import');
        $link2 = add_query_arg('direo_create_page', 'true', $_SERVER["REQUEST_URI"]);
        echo '<div class="notice notice-warning is-dismissible direo_importer_notice"><p><a href="' . esc_url($link) . '">' . __('Import Demo', 'direo') . '</a> or <a href="' . esc_url($link2) . '">' . __('Generate', 'direo') . '</a>' . __(' Required Pages') . '</p></div>';
    }
    if (get_transient('direo-page-creation-notice')) { ?>
        <div class="updated notice is-dismissible">
            <p><?php _e('Page created successfully!', 'direo') ?></p>
        </div>
        <?php
        delete_transient('direo-page-creation-notice');
    }
}

add_action('admin_notices', 'direo_page_creation_notice');

function direo_select_pages()
{
    $updatePage = get_option('direo_page_init');
    if (empty($updatePage)) {
        $options = get_option('atbdp_option');
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'numberposts' => -1
        );
        $pages = get_posts($args);
        if (!empty($pages)) {
            foreach ($pages as $page) {
                $page_slug = $page->post_name;
                $pageId = $page->ID;
                switch ($page_slug) {
                    //All category page
                    case 'categories';
                        $options['all_categories_page'] = $pageId;
                        break;
                    //Single category page
                    case 'single-category';
                        $options['single_category_page'] = $pageId;
                        break;
                    //All locations page
                    case 'locations';
                        $options['all_locations_page'] = $pageId;
                        break;
                    //Single location page
                    case 'single-location';
                        $options['single_location_page'] = $pageId;
                        break;
                    //All listing page
                    case 'map-view-i';
                        $options['all_listing_page'] = $pageId;
                        break;
                    //Single tag page
                    case 'single-tag';
                        $options['single_tag_page'] = $pageId;
                        break;
                    //Search result page
                    case 'search-result';
                        $options['search_result_page'] = $pageId;
                        break;
                    //Checkout page
                    case 'checkout';
                        $options['checkout_page'] = $pageId;
                        break;
                    //Registration page
                    case 'registration';
                        $options['custom_registration'] = $pageId;
                        break;
                    //Login page
                    case 'login';
                        $options['user_login'] = $pageId;
                        break;
                    //Add listing page
                    case 'add-listing';
                        $options['add_listing_page'] = $pageId;
                        break;
                    //Payment receipt page
                    case 'payment-receipt';
                        $options['payment_receipt_page'] = $pageId;
                        break;
                    //Tr page
                    case 'transaction-failure';
                        $options['transaction_failure_page'] = $pageId;
                        break;
                    //Plan page
                    case 'pricing-plan';
                        $options['pricing_plans'] = $pageId;
                        break;
                    //Plan page
                    case 'dashboard';
                        $options['user_dashboard'] = $pageId;
                        break;
                    //Plan page
                    case 'author-profile';
                        $options['author_profile_page'] = $pageId;
                        break;

                    case 'privacy-and-policy';
                        $options['privacy_policy'] = $pageId;
                        break;

                    case 'terms-conditions';
                        $options['terms_conditions'] = $pageId;
                        break;
                }
                $pageUpdate = update_option('atbdp_option', $options);
                if ($pageUpdate) {
                    update_option('direo_page_init', 1);
                }
            }
        }
    }
}

if (get_option('direo_demo_import')) {
    add_action('wp_loaded', 'direo_select_pages');
}

/*Set default listing detail template*/
function direo_single_template($template)
{
    $template = 'current_theme_template';
    return $template;
}

add_filter('atbdp_single_template', 'direo_single_template');

/*=====================================================
    Dashboard Pagination for listing
=================================================*/
add_action('wp_ajax_user_dashboard_active_listings', 'direo_user__dashboard_listings_pagination');
add_action('wp_ajax_nopriv_user_dashboard_active_listings', 'direo_user__dashboard_listings_pagination');

function direo_user__dashboard_listings_pagination()
{
    if (!isset($_POST['page'])) {
        die();
    }
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $cur_page = $page;
    $page -= 1;
    // Set the number of results to display
    $per_page = get_directorist_option('user_listings_per_page', 5);
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;
    $args = array(
        'author' => get_current_user_id(),
        'post_type' => ATBDP_POST_TYPE,
        'posts_per_page' => (int)$per_page,
        'order' => 'DESC',
        'offset' => $start,
        'orderby' => 'date',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_need_post',
                'value' => 'no',
                'compare' => '=',
            ),
            array(
                'key' => '_need_post',
                'compare' => 'NOT EXISTS',
            )
        ),
    );
    $posts = new WP_Query($args);
    $all_listings = $posts->posts;

    $args2 = array(
        'author' => get_current_user_id(),
        'post_type' => ATBDP_POST_TYPE,
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'date',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_need_post',
                'value' => 'no',
                'compare' => '=',
            ),
            array(
                'key' => '_need_post',
                'compare' => 'NOT EXISTS',
            )
        ),
    );

    $posts = new WP_Query($args2);
    $count = $posts->post_count;
    // This is where the magic happens
    $no_of_paginations = ceil($count / $per_page);

    if ($cur_page >= 5) {
        $start_loop = $cur_page - 2;
        if ($no_of_paginations > (int)$cur_page + 2)
            $end_loop = (int)$cur_page + 2;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 4) {
            $start_loop = $no_of_paginations - 4;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 5)
            $end_loop = 5;
        else
            $end_loop = $no_of_paginations;
    }

    // Pagination Buttons logic
    $pag_container = '';
    // Pagination Buttons logic
    $pag_container .= "
        <div class='atbdp-universal-pagination'>
            <ul>";

    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $pag_container .= "<li data-page='" . esc_attr($pre) . "' class='atbd-active'><i class='la la-angle-left'></i></li>";
    } else if ($previous_btn) {
        $pag_container .= "<li class='atbd-inactive'><i class='la la-angle-left'></i></li>";
    }
    $first_class = '';
    if ($first_btn && $cur_page > 1) {
        $first_class = 'atbd-active';
    } elseif ($first_btn) {
        $first_class = 'atbd-selected';
    }
    $pag_container .= "<li data-page='1' class='" . esc_html($first_class) . "'>1</li>";
    for ($i = $start_loop; $i <= $end_loop; $i++) {
        if ($i === 1 || $i === $no_of_paginations) continue;
        if (($no_of_paginations <= 5) && ($no_of_paginations == $i)) continue;
        $dot_ = (int)$cur_page + 2;
        $backward = ($cur_page == $no_of_paginations) ? 4 : (($cur_page == $no_of_paginations - 1) ? 3 : 2);
        $dot__ = (int)$cur_page - $backward;
        // show dot if current page say 'i have some neighbours left form mine'
        if ($cur_page > 4) {
            if (($dot__ == $i)) {
                $jump = $i - 5;
                $jump = $jump < 1 ? 1 : $jump;
                $pag_container .= "<li data-page='" . esc_attr($jump) . "' class='atbd-page-jump-back atbd-active' title='" . __('Previous 5 Pages', 'direo-core') . "'><i class='la la-ellipsis-h la_d'></i> <i class='la la-angle-double-left la_h'></i></li>";
            }
        }
        if ($cur_page == $i) {
            $pag_container .= "<li data-page='" . esc_attr($i) . "' class = 'atbd-selected' >" . esc_attr($i) . "</li>";
        } else {
            $pag_container .= "<li data-page='" . esc_attr($i) . "' class='atbd-active'>" . esc_attr($i) . "</li>";
        }
        // show dot if current page say 'i have some neighbours right form mine'
        if (($cur_page > 4)) {
            if (($dot_ == $i)) {
                $jump = $i + 5;
                $jump = $jump > $no_of_paginations ? $no_of_paginations : $jump;
                $pag_container .= "<li data-page='" . esc_attr($jump) . "' class='atbd-page-jump-up atbd-active' title='" . __('Next 5 Pages', 'direo-core') . "'><i class='la la-ellipsis-h la_d'></i> <i class='la la-angle-double-right la_h'></i></li>";
            }
        }
        // show dot after first 5
        if ((($cur_page == 1) || ($cur_page == 2) || ($cur_page == 3) || ($cur_page == 4)) && ($no_of_paginations > 5)) {
            $jump = $i + 5;
            $jump = $jump > $no_of_paginations ? $no_of_paginations : $jump;
            if ($i == 5) {
                $pag_container .= "<li data-page='" . esc_attr($jump) . "' class='atbd-page-jump-up atbd-active' title='" . __('Next 5 Pages', 'direo-core') . "'><i class='la la-ellipsis-h la_d'></i> <i class='la la-angle-double-right la_h'></i></li>";
            }
        }

    }

    $last_class = '';
    if ($last_btn && $cur_page < $no_of_paginations) {
        $last_class = 'atbd-active';
    } else if ($last_btn) {
        $last_class = 'atbd-selected';
    }
    $pag_container .= "<li data-page='" . esc_attr($no_of_paginations) . "' class='" . esc_html($last_class) . "'>" . esc_attr($no_of_paginations) . "</li>";

    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = (int)$cur_page + 1;
        $pag_container .= "<li data-page='" . esc_attr($nex) . "' class='atbd-active'><i class='la la-angle-right'></i></li>";
    } else if ($next_btn) {
        $pag_container .= "<li class='atbd-inactive'><i class='la la-angle-right'></i></li>";
    }

    $pag_container = $pag_container . "
                
            </ul>
        </div>";

    // We echo the final output
    ob_start();
    foreach ($all_listings as $key => $post) {
        $listing_id = $post->ID;
        $date_format = get_option('date_format');
        $featured_active = get_directorist_option('enable_featured_listing');
        $post_status = get_post_status_object($post->post_status)->label;

        $featured = get_post_meta($post->ID, '_featured', true);

        $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
        $listing_prv_img_link = wp_get_attachment_image_src($listing_prv_img, array(60, 60), false);

        $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
        $cats = $cats ? $cats : [];

        $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID));
        $display_review = get_directorist_option('enable_review', 1);

        $exp_date = get_post_meta($post->ID, '_expiry_date', true);
        $never_exp = get_post_meta($post->ID, '_never_expire', true);
        $l_status = get_post_meta($post->ID, '_listing_status', true);
        $exp_text = $never_exp ? esc_html__('Never Expires', 'direo-core') : date_i18n($date_format, strtotime($exp_date)); ?>

        <tr data-expanded="<?php echo (0 === $key) ? esc_html('true') : ''; ?>"
            class="listing_id_<?php echo esc_attr($post->ID); ?>">
            <td class="dl-title">
                    <span class="atbd_footable">
                        <?php
                        $prv_img_link = $listing_prv_img_link ? esc_url($listing_prv_img_link[0]) : '';
                        echo sprintf('<a href="#" class="atbd_footable_img"><img src="%s" alt="%s"/></a> ', esc_url($prv_img_link), esc_attr(direo_get_image_alt($listing_prv_img)));
                        $p_title = $post->post_title ? esc_html(stripslashes($post->post_title)) : '';
                        echo sprintf('<h6><a href="%s">%s</h6>', esc_url(get_post_permalink($post->ID)), esc_attr($p_title)); ?>
                    </span>
            </td>
            <?php
            if ($display_review) {
                $average = ATBDP()->review->get_average(get_the_ID()); ?>
                <td class="dl-review">
                    <ul class="rating">
                        <?php
                        $star = '<li><span class="la la-star rate_active"></span></li>';
                        $half_star = '<li><span class="la la-star-half-o rate_active"></span></li>';
                        $none_star = '<li><span class="la la-star-o"></span></li>';

                        if (is_int($average)) {
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $average) {
                                    echo wp_kses_post($star);
                                } else {
                                    echo wp_kses_post($none_star);
                                }
                            }
                            wp_reset_postdata();
                        } elseif (!is_int($average)) {
                            $exp = explode('.', $average);
                            $float_num = $exp[0];
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $average) {
                                    echo wp_kses_post($star);
                                } elseif (!empty($average) && $i > $average && $i <= $float_num + 1) {
                                    echo wp_kses_post($half_star);
                                } else {
                                    echo wp_kses_post($none_star);
                                }
                            }
                            wp_reset_postdata();
                        }
                        $review_title = '';
                        if ($reviews_count) {
                            if (1 < $reviews_count) {
                                $review_title = $reviews_count . esc_html__(' Reviews', 'direo-core');
                            } else {
                                $review_title = $reviews_count . esc_html__(' Review', 'direo-core');
                            }
                        }
                        echo sprintf('<li class="reviews"><span class="atbd_count">(<b>%s</b> %s )</span></li>', esc_attr($average . '/5'), esc_attr($review_title)); ?>
                    </ul>
                </td>
                <?php
            } ?>

            <td class="atbd_listting_category dl-cat">
                <div class="atbd_listing_icon">
                    <ul>
                        <?php
                        if ($cats) {
                            foreach ($cats as $cat) {
                                $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                $space = str_repeat(' ', 1);
                                $category_icon = $cats ? get_cat_icon($cat->term_id) : atbdp_icon_type() . '-tags';
                                $icon_type = substr($category_icon, 0, 2);
                                $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                echo sprintf('<li>%s<span><i class="%s"></i><a href="%s">%s</a></span></li>', esc_attr($space), esc_attr($icon), esc_url($link), esc_attr($cat->name));
                            }
                            wp_reset_postdata();
                        } ?>
                    </ul>
                </div>
            </td>
            <?php if (is_fee_manager_active()) { ?>
                <td class="direo_plane_name dl-plan">
                    <?php do_action('atbdp_user_dashboard_listings_before_expiration', $listing_id); ?>
                </td>
                <?php
            } ?>
            <td class="dl-expired">
                <?php echo ('expired' == $l_status) ? esc_html__('Expired', 'direo-core') : esc_attr($exp_text); ?>
            </td>

            <td class="dl-status">
                <span class="badge badge-light active"><?php echo esc_attr($post_status); ?></span>
            </td>

            <td class="edit_btn_wrap dl-action">

                <div class="action_button">
                    <?php if (('renewal' == $l_status || 'expired' == $l_status)) {
                        $can_renew = get_directorist_option('can_renew_listing');

                        if (!$can_renew) return false;

                        if (is_fee_manager_active()) {
                            $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id); ?>
                            <a href="javascript:void(0)"
                               data-toggle="modal"
                               data-target="<?php echo esc_attr($modal_id); ?>"
                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                               class="directory_btn btn btn-outline-success atbdp_renew_with_plan">
                                <?php esc_html_e('Renew', 'direo-core'); ?>
                            </a>
                            <?php
                        } else { ?>
                            <a href="<?php echo esc_url(ATBDP_Permalink::get_renewal_page_link($listing_id)) ?>"
                               id="directorist-renew"
                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                               class="directory_btn btn text-success">
                                <?php esc_html_e('Renew', 'direo-core'); ?>
                            </a>
                            <?php
                        }
                    } else {
                        if ($featured_active && empty($featured) && !is_fee_manager_active()) { ?>
                            <a href="<?php echo esc_url(ATBDP_Permalink::get_checkout_page_link($listing_id)) ?>"
                               id="directorist-promote"
                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                               class="directory_btn btn text-primary">
                                <?php esc_html_e('Promote Your listing', 'direo-core'); ?>
                            </a>
                            <?php
                        }
                    } ?>

                    <a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($listing_id)); ?>"
                       class="btn text-primary">
                        <?php esc_html_e(' Edit', 'direo-core') ?>
                    </a>

                    <a href="listing-del"
                       id="direo_remove_listing"
                       data-listing_id="<?php echo esc_attr($listing_id); ?>"
                       class="directory_remove_btn text-danger">
                        <?php esc_html_e('Delete', 'direo-core'); ?>
                    </a>
                </div>


                <div class="responsive_dropdown">
                    <button class="action-btn" type="button"
                            id="dropdownMenuButton"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="la la-circle"></i>
                        <i class="la la-circle"></i>
                        <i class="la la-circle"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($listing_id)); ?>"
                           class="btn text-primary">
                            <?php esc_html_e(' Edit', 'direo-core'); ?>
                        </a>

                        <a href="#" id="direo_remove_listing"
                           data-listing_id="<?php echo esc_attr($listing_id); ?>"
                           class="directory_remove_btn btn text-danger">
                            <?php esc_html_e('Delete', 'direo-core'); ?>
                        </a>
                    </div>
                </div>
            </td>
        </tr>

        <?php
    }
    wp_reset_postdata(); // Restore global post data stomped by the_post()

    $colspan = 6;
    if (is_fee_manager_active()) {
        $colspan = $colspan + 1;
    }
    if ($no_of_paginations > 1) { ?>
        <tr>
            <td style="display: table-cell" colspan="<?php echo esc_attr($colspan); ?>">
                <div class="atbdp-pagination-navagination-nav">
                    <?php echo wp_kses_post($pag_container); ?>
                </div>
            </td>
        </tr>
        <?php
    }

    echo ob_get_clean();

    // Always exit to avoid further execution
    exit();
}

/*=====================================================
    Dashboard Pagination for need listing
=================================================*/
add_action('wp_ajax_user_dashboard_active_needs', 'direo_user__dashboard_needs_pagination');
add_action('wp_ajax_nopriv_user_dashboard_active_needs', 'direo_user__dashboard_needs_pagination');

function direo_user__dashboard_needs_pagination()
{
    if (!isset($_POST['page'])) {
        die();
    }
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $cur_page = $page;
    $page -= 1;
    // Set the number of results to display
    $per_page = get_directorist_option('user_listings_per_page', 5);
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;
    $args = array(
        'author' => get_current_user_id(),
        'post_type' => ATBDP_POST_TYPE,
        'posts_per_page' => (int)$per_page,
        'order' => 'DESC',
        'offset' => $start,
        'orderby' => 'date',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_need_post',
                'value' => 'yes',
                'compare' => '=',
            ),
            array(
                'key' => '_need_post',
                'compare' => 'EXISTS',
            )
        ),
    );
    $posts = new WP_Query($args);
    $all_needs = $posts->posts;

    $args2 = array(
        'author' => get_current_user_id(),
        'post_type' => ATBDP_POST_TYPE,
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'date',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_need_post',
                'value' => 'yes',
                'compare' => '=',
            ),
            array(
                'key' => '_need_post',
                'compare' => 'EXISTS',
            )
        ),
    );
    $posts = new WP_Query($args2);

    $count = $posts->post_count;
    // This is where the magic happens
    $no_of_paginations = ceil($count / $per_page);

    if ($cur_page >= 5) {
        $start_loop = $cur_page - 2;
        if ($no_of_paginations > (int)$cur_page + 2)
            $end_loop = (int)$cur_page + 2;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 4) {
            $start_loop = $no_of_paginations - 4;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 5)
            $end_loop = 5;
        else
            $end_loop = $no_of_paginations;
    }

    // Pagination Buttons logic
    $pag_container = '';
    // Pagination Buttons logic
    $pag_container .= "
        <div class='atbdp__user__needs'>
            <ul>";

    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $pag_container .= "<li data-page='$pre' class='atbd-active'><i class='la la-angle-left'></i></li>";
    } else if ($previous_btn) {
        $pag_container .= "<li class='atbd-inactive'><i class='la la-angle-left'></i></li>";
    }
    $first_class = '';
    if ($first_btn && $cur_page > 1) {
        $first_class = 'atbd-active';
    } else if ($first_btn) {
        $first_class = 'atbd-selected';
    }
    $pag_container .= "<li data-page='1' class='" . esc_attr($first_class) . "'>1</li>";
    for ($i = $start_loop; $i <= $end_loop; $i++) {
        if ($i === 1 || $i === $no_of_paginations) continue;
        if (($no_of_paginations <= 5) && ($no_of_paginations == $i)) continue;
        $dot_ = (int)$cur_page + 2;
        $backward = ($cur_page == $no_of_paginations) ? 4 : (($cur_page == $no_of_paginations - 1) ? 3 : 2);
        $dot__ = (int)$cur_page - $backward;
        // show dot if current page say 'i have some neighbours left form mine'
        if ($cur_page > 4) {
            if (($dot__ == $i)) {
                $jump = $i - 5;
                $jump = $jump < 1 ? 1 : $jump;
                $pag_container .= "<li data-page='" . esc_attr($jump) . "' class='atbd-page-jump-back atbd-active' title='" . __('Previous 5 Pages', 'direo-core') . "'><i class='la la-ellipsis-h la_d'></i> <i class='la la-angle-double-left la_h'></i></li>";
            }
        }
        if ($cur_page == $i) {
            $pag_container .= "<li data-page='" . esc_attr($i) . "' class = 'atbd-selected' >" . esc_attr($i) . "</li>";
        } else {
            $pag_container .= "<li data-page='" . esc_attr($i) . "' class='atbd-active'>" . esc_attr($i) . "</li>";
        }

        // show dot if current page say 'i have some neighbours right form mine'
        if (($cur_page > 4)) {
            if (($dot_ == $i)) {
                $jump = $i + 5;
                $jump = $jump > $no_of_paginations ? $no_of_paginations : $jump;
                $pag_container .= "<li data-page='" . esc_attr($jump) . "' class='atbd-page-jump-up atbd-active' title='" . __('Next 5 Pages', 'direo-core') . "'><i class='la la-ellipsis-h la_d'></i> <i class='la la-angle-double-right la_h'></i></li>";
            }
        }
        // show dot after first 5
        if ((($cur_page == 1) || ($cur_page == 2) || ($cur_page == 3) || ($cur_page == 4)) && ($no_of_paginations > 5)) {
            $jump = $i + 5;
            $jump = $jump > $no_of_paginations ? $no_of_paginations : $jump;
            if ($i == 5) {
                $pag_container .= "<li data-page='" . esc_attr($jump) . "' class='atbd-page-jump-up atbd-active' title='" . __('Next 5 Pages', 'direo-core') . "'><i class='la la-ellipsis-h la_d'></i> <i class='la la-angle-double-right la_h'></i></li>";
            }
        }

    }
    $last_class = '';
    if ($last_btn && $cur_page < $no_of_paginations) {
        $last_class = 'atbd-active';
    } else if ($last_btn) {
        $last_class = 'atbd-selected';
    }
    $pag_container .= "<li data-page='" . esc_attr($no_of_paginations) . "' class='" . esc_html($last_class) . "'>" . esc_attr($no_of_paginations) . "</li>";

    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = (int)$cur_page + 1;
        $pag_container .= "<li data-page='" . esc_attr($nex) . "' class='atbd-active'><i class='la la-angle-right'></i></li>";
    } else if ($next_btn) {
        $pag_container .= "<li class='atbd-inactive'><i class='la la-angle-right'></i></li>";
    }

    $pag_container = $pag_container . "
            </ul>
        </div>";

    // We echo the final output
    ob_start();
    foreach ($all_needs as $key => $post) {
        $date_format = get_option('date_format');
        $featured_active = get_directorist_option('enable_featured_listing');
        $listing_id = $post->ID;
        $post_status = get_post_status_object($post->post_status)->label;
        $featured = get_post_meta($post->ID, '_featured', true);
        $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
        $cats = $cats ? $cats : [];
        $exp_date = get_post_meta($post->ID, '_expiry_date', true);
        $never_exp = get_post_meta($post->ID, '_never_expire', true);
        $l_status = get_post_meta($post->ID, '_listing_status', true);
        $exp_text = $never_exp ? esc_html__('Never Expires', 'direo-core') : date_i18n($date_format, strtotime($exp_date)); ?>

        <tr data-expanded="<?php echo (0 === $key) ? esc_html("true") : ''; ?>"
            class="listing_id_<?php echo esc_attr($post->ID); ?>">

            <td class="dn-title">
                <span class="atbd_footable">
                    <?php
                    $p_title = $post->post_title ? esc_html(stripslashes($post->post_title)) : '';
                    echo sprintf('<h6><a href="%s">%s</h6>', esc_url(get_post_permalink($post->ID)), esc_attr($p_title)); ?>
                </span>
            </td>

            <td class="empty"></td>
            <td class="atbd_listting_category dn-cat">
                <div class="atbd_listing_icon">
                    <ul>
                        <?php
                        if ($cats) {
                            foreach ($cats as $cat) {
                                $link = pny_get_category_page($cat);
                                $space = str_repeat(' ', 1);

                                $category_icon = $cats ? get_cat_icon($cat->term_id) : atbdp_icon_type() . '-tags';
                                $icon_type = substr($category_icon, 0, 2);
                                $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                echo sprintf('<li>%s<span><i class="%s"></i><a href="%s">%s</a></span></li>', esc_attr($space), esc_attr($icon), esc_url($link), esc_attr($cat->name));
                            }
                            wp_reset_postdata();
                        } ?>
                    </ul>
                </div>
            </td>

            <?php
            if (is_fee_manager_active()) { ?>
                <td class="direo_plane_name dn-plan">
                    <?php do_action('atbdp_user_dashboard_listings_before_expiration', $listing_id); ?>
                </td>
                <?php
            } ?>
            <td class="dn-expired">
                <?php echo ('expired' == $l_status) ? esc_html__('Expired', 'direo-core') : esc_attr($exp_text); ?>
            </td>

            <td class="dn-status">
                <span class="badge badge-light active"><?php echo esc_attr($post_status); ?></span>
            </td>

            <td class="edit_btn_wrap dn-action">

                <div class="action_button">
                    <?php if (('renewal' == $l_status || 'expired' == $l_status)) {
                        $can_renew = get_directorist_option('can_renew_listing');

                        if (!$can_renew) return false;

                        if (is_fee_manager_active()) {
                            $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id); ?>
                            <a data-toggle="modal"
                               data-target="<?php echo esc_attr($modal_id); ?>"
                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                               class="directory_btn btn btn-outline-success atbdp_renew_with_plan">
                                <?php esc_html_e('Renew', 'direo-core'); ?>
                            </a>
                            <?php
                        } else { ?>
                            <a href="<?php echo esc_url(ATBDP_Permalink::get_renewal_page_link($listing_id)) ?>"
                               id="directorist-renew"
                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                               class="directory_btn btn btn-outline-success">
                                <?php esc_html_e('Renew', 'direo-core'); ?>
                            </a>
                            <?php
                        }
                    } else {
                        if ($featured_active && !$featured && !is_fee_manager_active()) { ?>
                            <a href="<?php echo esc_url(ATBDP_Permalink::get_checkout_page_link($listing_id)) ?>"
                               id="directorist-promote"
                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                               class="directory_btn btn btn-outline-primary">
                                <?php esc_html_e('Promote Your listing', 'direo-core'); ?>
                            </a>
                            <?php
                        }
                    } ?>

                    <a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($listing_id)); ?>"
                       class="btn text-primary">
                        <?php esc_html_e(' Edit', 'direo-core') ?>
                    </a>
                    <a href="listing-del" id="direo_remove_listing"
                       data-listing_id="<?php echo esc_attr($listing_id); ?>"
                       class="directory_remove_btn btn text-danger">
                        <?php esc_html_e('Delete', 'direo-core'); ?>
                    </a>
                </div>

                <div class="responsive_dropdown">
                    <button class="action-btn" type="button"
                            id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <i class="la la-circle"></i>
                        <i class="la la-circle"></i>
                        <i class="la la-circle"></i>
                    </button>
                    <div class="dropdown-menu"
                         aria-labelledby="dropdownMenuButton">
                        <a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($listing_id)); ?>"
                           class="btn btn-outline-primary">
                            <?php esc_html_e(' Edit', 'direo-core'); ?>
                        </a>

                        <a href="#" id="direo_remove_listing"
                           data-listing_id="<?php echo esc_attr($post->ID); ?>"
                           class="directory_remove_btn btn btn-outline-danger">
                            <?php esc_html_e('Delete', 'direo-core'); ?>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <?php
    }
    wp_reset_postdata(); // Restore global post data stomped by the_post()

    $colspan = 6;
    if (is_fee_manager_active()) {
        $colspan = $colspan + 1;
    }
    if ($no_of_paginations > 1) { ?>
        <tr>
            <td style="display: table-cell" colspan="<?php echo esc_attr($colspan); ?>">
                <div class="atbdp-pagination-navagination-nav">
                    <?php echo wp_kses_post($pag_container); ?>
                </div>
            </td>
        </tr>
        <?php
    }
    echo ob_get_clean();

    // Always exit to avoid further execution
    exit();
}