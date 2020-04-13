<?php
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');
class BDMV_Ajax {
    public function __construct()
    {
        add_action('wp_ajax_ajax_search_listing',array($this, 'ajax_search_listing'));
        add_action('wp_ajax_nopriv_ajax_search_listing',array($this, 'ajax_search_listing'));
    }

    public function ajax_search_listing() {
        if(wp_verify_nonce($_POST['nonce_get'],'bdlm_ajax_nonce')) :
            $paged = atbdp_get_paged_num();
            $category_slug = isset($_POST['category_slug']) ? $_POST['category_slug'] : '';
            $location_slug = isset($_POST['location_slug']) ? $_POST['location_slug'] : '';
            if (isset($_POST['pageno'])) {
                $paged = $_POST['pageno'];
            }
            $show_pagination = isset($_POST['show_pagination']) ? esc_html($_POST['show_pagination']) : 'yes';
            $listings_per_page = isset($_POST['listings_per_page']) ? esc_html($_POST['listings_per_page']) : 6;
            $args = array(
                'post_type' => ATBDP_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => $listings_per_page,
            );
            if ('yes' == $show_pagination) {
                $args['paged'] = $paged;
            }
            if (isset($_POST['key']) && !empty($_POST['key'])){
                $args['s'] = $_POST['key'];
            }
            if(!empty($category_slug)) {
                $tax_queries[] = array(
                    'taxonomy' => ATBDP_CATEGORY,
                    'field' => 'slug',
                    'terms' => $category_slug,
                    'include_children' => true,
                );
                $args['tax_query'] = $tax_queries;
            }
            if(!empty($location_slug)) {
                $tax_queries[] = array(
                    'taxonomy' => ATBDP_LOCATION,
                    'field' => 'slug',
                    'terms' => $location_slug,
                    'include_children' => true,
                );
                $args['tax_query'] = $tax_queries;
            }


            $tax_queries = array(); // initiate the tax query var to append to it different tax query
            if (isset($_POST['location']) && (int)$_POST['location'] > 0) {

                $tax_queries[] = array(
                    'taxonomy' => ATBDP_LOCATION,
                    'field' => 'term_id',
                    'terms' => (int)$_POST['location'],
                    'include_children' => true,
                );

            }
            if (isset($_POST['category']) && (int)$_POST['category'] > 0) {
                $tax_queries[] = array(
                    'taxonomy' => ATBDP_CATEGORY,
                    'field' => 'term_id',
                    'terms' => (int)$_POST['category'],
                    'include_children' => true,
                );

            }
            if (isset($_POST['tag']) && (int)$_POST['tag'] > 0) {
                $tax_queries[] = array(
                    'taxonomy' => ATBDP_TAGS,
                    'field' => 'term_id',
                    'terms' => (int)$_POST['tag'],
                );

            }
            $count_tax_queries = count($tax_queries);
            if ($count_tax_queries) {
                $args['tax_query'] = ($count_tax_queries > 1) ? array_merge(array('relation' => 'AND'), $tax_queries) : $tax_queries;
            }
            $meta_queries = array();
            if (isset($_POST['price'])) {
                $price = array_filter($_POST['price']);

                if ($n = count($price)) {

                    if (2 == $n) {
                        $meta_queries[] = array(
                            'key' => '_price',
                            'value' => array_map('intval', $price),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN'
                        );
                    } else {
                        if (empty($price[0])) {
                            $meta_queries[] = array(
                                'key' => '_price',
                                'value' => (int)$price[1],
                                'type' => 'NUMERIC',
                                'compare' => '<='
                            );
                        } else {
                            $meta_queries[] = array(
                                'key' => '_price',
                                'value' => (int)$price[0],
                                'type' => 'NUMERIC',
                                'compare' => '>='
                            );
                        }
                    }

                }

            }// end price
            // for d-service
            $meta_queries[] = array(
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
            );
            if (isset($_POST['price_range']) && 'none' != $_POST['price_range']) {
                $price_range = $_POST['price_range'];
                $meta_queries[] = array(
                    'key' => '_price_range',
                    'value' => $price_range,
                    'compare' => 'LIKE'
                );
            }
            if (isset($_POST['custom_field'])) {
                $cf = array_filter($_POST['custom_field']);
                foreach ($cf as $key => $values) {

                    if (is_array($values)) {

                        if (count($values) > 1) {

                            $sub_meta_queries = array();

                            foreach ($values as $value) {
                                $sub_meta_queries[] = array(
                                    'key' => $key,
                                    'value' => sanitize_text_field($value),
                                    'compare' => 'LIKE'
                                );
                            }

                            $meta_queries[] = array_merge(array('relation' => 'OR'), $sub_meta_queries);

                        } else {

                            $meta_queries[] = array(
                                'key' => $key,
                                'value' => sanitize_text_field($values[0]),
                                'compare' => 'LIKE'
                            );
                        }

                    } else {

                        $field_type = get_post_meta($key, 'type', true);
                        $operator = ('text' == $field_type || 'textarea' == $field_type || 'url' == $field_type) ? 'LIKE' : '=';
                        $meta_queries[] = array(
                            'key' => $key,
                            'value' => sanitize_text_field($values),
                            'compare' => $operator
                        );

                    }

                }

            } // end post['cf']

            // search by rating
            if (isset($_POST['search_by_rating'])) {
                $q_rating = $_POST['search_by_rating'];
                $listings = get_atbdp_listings_ids();
                $rated = array();
                if ($listings->have_posts()) {
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $listing_id = get_the_ID();
                        $average = ATBDP()->review->get_average($listing_id);
                        if ($q_rating === '5') {
                            if ( ($average == '5')) {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ($q_rating === '4') {
                            if ($average >= '4') {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ($q_rating === '3') {
                            if ($average >= '3') {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ($q_rating === '2') {
                            if ($average >= '2') {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ($q_rating === '1') {
                            if ($average >= '1') {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ('' === $q_rating) {
                            if ($average === '') {
                                $rated[] = get_the_ID();
                            }
                        }
                    }
                    $rating_id = array(
                        'post__in' => !empty($rated) ? $rated : array()
                    );
                    $args = array_merge($args, $rating_id);
                }


            }

            if (isset($_POST['website'])) {
                $website = $_POST['website'];
                $meta_queries[] = array(
                    'key' => '_website',
                    'value' => $website,
                    'compare' => 'LIKE'
                );
            }

            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $meta_queries[] = array(
                    'key' => '_email',
                    'value' => $email,
                    'compare' => 'LIKE'
                );
            }

            if (isset($_POST['phone'])) {
                $phone = $_POST['phone'];
                $meta_queries[] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_phone2',
                        'value' => $phone,
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => '_phone',
                        'value' => $phone,
                        'compare' => 'LIKE'
                    )
                );
            }

            if (!empty($_POST['fax'])) {
                $fax = $_POST['fax'];
                $meta_queries[] = array(
                    'key' => '_fax',
                    'value' => $fax,
                    'compare' => 'LIKE'
                );
            }
            if (!empty($_POST['miles']) && $_POST['miles'] > 0 && !empty($_POST['cityLat']) && !empty($_POST['cityLng'])) {
                $args['atbdp_geo_query'] = array(
                    'lat_field' => '_manual_lat',  // this is the name of the meta field storing latitude
                    'lng_field' => '_manual_lng', // this is the name of the meta field storing longitude
                    'latitude' => $_POST['cityLat'],    // this is the latitude of the point we are getting distance from
                    'longitude' => $_POST['cityLng'],   // this is the longitude of the point we are getting distance from
                    'distance' => $_POST['miles'],           // this is the maximum distance to search
                    'units' => 'miles'       // this supports options: miles, mi, kilometers, km
                );
            } elseif (isset($_POST['address'])) {
                $address = $_POST['address'];
                $meta_queries[] = array(
                    'key' => '_address',
                    'value' => $address,
                    'compare' => 'LIKE'
                );
            }

            if (isset($_POST['zip_code'])) {
                $zip_code = $_POST['zip_code'];
                $meta_queries[] = array(
                    'key' => '_zip',
                    'value' => $zip_code,
                    'compare' => 'LIKE'
                );
            }
            $args['meta_query'] = $meta_queries;
            $current_order = isset($_POST['sort_by']) ? $_POST['sort_by'] : '';
            $listing_orderby = get_directorist_option('order_listing_by');
            $listing_order = get_directorist_option('sort_listing_by');
            if(empty($current_order)) {
                if('rand' == $listing_orderby) {
                    $current_order = atbdp_get_listings_current_order($listing_orderby);
                } else {
                    $current_order = atbdp_get_listings_current_order($listing_orderby . '-' .$listing_order );
                }
            }
            $listings = get_atbdp_listings_ids();
            $rated = array();
            $listing_popular_by = get_directorist_option('listing_popular_by');
            $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
            $view_to_popular = get_directorist_option('views_for_popular');
            $has_featured = get_directorist_option('enable_featured_listing');
            switch ($current_order) {
                case 'title-asc' :
                    if ($has_featured) {
                        $args['meta_key'] = '_featured';
                        $args['orderby'] = array(
                            'meta_value_num' => 'DESC',
                            'title' => 'ASC',
                        );
                    } else {
                        $args['orderby'] = 'title';
                        $args['order'] = 'ASC';
                    };
                    break;
                case 'title-desc' :
                    if ($has_featured) {
                        $args['meta_key'] = '_featured';
                        $args['orderby'] = array(
                            'meta_value_num' => 'DESC',
                            'title' => 'DESC',
                        );
                    } else {
                        $args['orderby'] = 'title';
                        $args['order'] = 'DESC';
                    };
                    break;
                case 'date-asc' :
                    if ($has_featured) {
                        $args['meta_key'] = '_featured';
                        $args['orderby'] = array(
                            'meta_value_num' => 'DESC',
                            'date' => 'ASC',
                        );
                    } else {
                        $args['orderby'] = 'date';
                        $args['order'] = 'ASC';
                    };
                    break;
                case 'date-desc' :
                    if ($has_featured) {
                        $args['meta_key'] = '_featured';
                        $args['orderby'] = array(
                            'meta_value_num' => 'DESC',
                            'date' => 'DESC',
                        );
                    } else {
                        $args['orderby'] = 'date';
                        $args['order'] = 'DESC';
                    };
                    break;
                case 'price-asc' :
                    if ($has_featured) {
                        $meta_queries['price'] = array(
                            'key' => '_price',
                            'type' => 'NUMERIC',
                            'compare' => 'EXISTS',
                        );

                        $args['orderby'] = array(
                            '_featured' => 'DESC',
                            'price' => 'ASC',
                        );
                    } else {
                        $args['meta_key'] = '_price';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'ASC';
                    };
                    break;
                case 'price-desc' :
                    if ($has_featured) {
                        $meta_queries['price'] = array(
                            'key' => '_price',
                            'type' => 'NUMERIC',
                            'compare' => 'EXISTS',
                        );

                        $args['orderby'] = array(
                            '_featured' => 'DESC',
                            'price' => 'DESC',
                        );
                    } else {
                        $args['meta_key'] = '_price';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'DESC';
                    };
                    break;
                case 'rand' :
                    if ($has_featured) {
                        $args['meta_key'] = '_featured';
                        $args['orderby'] = 'meta_value_num rand';
                    } else {
                        $args['orderby'] = 'rand';
                    };
                    break;
            }

            $all_listings = new WP_Query(apply_filters('atbdp_listing_search_query_argument', $args));


            if ($all_listings->have_posts()) {
                $cat_id = !empty($_POST['category']) ? $_POST['category'] : '';
                $loc_id = !empty($_POST['location']) ? $_POST['location'] : '';
                $cat_name = get_term_by('id', $cat_id, ATBDP_CATEGORY);
                $loc_name = get_term_by('id', $loc_id, ATBDP_LOCATION);
                $for_cat = !empty($cat_name) ? sprintf(__('for %s', 'direo-extension'), $cat_name->name) : '';
                if(isset($_POST['in_loc']) && (int)$_POST['in_loc'] > 0) {
                    $in_loc = !empty($loc_name) ? sprintf(__('in %s', 'direo-extension'), $loc_name->name) : '';
                }else{
                    $in_loc = !empty($_POST['address']) ? sprintf(__('in %s', 'direo-extension'), $_POST['address']) : '';
                }
                $_s = (1 < count($all_listings->posts)) ? 's' : '';
                $listing_count = '<span>' . count($all_listings->posts) . '</span>';
                $display_header = isset($_POST['display_header']) ? $_POST['display_header'] : '';
                $header_title = sprintf(__('%d result%s %s %s', 'direo-extension'), $all_listings->found_posts, $_s, $for_cat, $in_loc);
                $sort_by_text = get_directorist_option('sort_by_text', __('Sort By', 'direo-extension'));
                $view_as_text = get_directorist_option('view_as_text', __('View As', 'direo-extension'));
                $view_as_items = get_directorist_option('listings_view_as_items', array('listings_grid', 'listings_list', 'listings_map'));
                $sort_by_items = get_directorist_option('listings_sort_by_items', array('a_z', 'z_a', 'latest', 'oldest', 'popular', 'price_low_high', 'price_high_low', 'random'));
                $listing_header_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
                $header_container_fluid = apply_filters('atbdp_search_result_header_container_fluid', $listing_header_container_fluid);
                $listing_grid_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
                $grid_container_fluid = apply_filters('atbdp_search_result_grid_container_fluid', $listing_grid_container_fluid);
                $view_columns = !empty($_POST['view_columns']) ? $_POST['view_columns'] : '3';
                ob_start();
                if("1" == $view_columns) {
                    include BDM_TEMPLATES_DIR . 'all-listings/columns-one/map-listing.php';
                }elseif ("2-style-2" == $view_columns) {
                    include BDM_TEMPLATES_DIR . 'all-listings/columns-two-(style-two)/map-listing.php';
                } else {
                    include BDM_TEMPLATES_DIR . 'all-listings/columns-three/map-listing.php';
                }
                wp_reset_postdata(); // Restore global post data stomped by the_post()
                $output = ob_get_clean();

                echo $output;
                die();
            }else{
                echo "-/error-/";
                ?>
                <div class="atbd-ajax-null-map">
                    <?php
                    include BDM_TEMPLATES_DIR . 'view/map.php';
                    ?>
                </div>
<?php
                die();
            }
        endif; // end ajax nonce
        exit;
    }

} //end class