<?php

/*==========================================
    Shortcode : Accordion
    Author URI: https://aazztech.com
============================================*/

$list_type = $featured = $list_num = $is_disable_price = $order = $contact = $phone = $date = '';
$list_types[] = $wrap_class[] = '';

extract($atts);
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);

if (class_exists('Directorist_Base')) {

    $has_featured = get_directorist_option('enable_featured_listing');
    if ($has_featured || is_fee_manager_active()) {
        $has_featured = true;
    }
    $args = array(
        'post_type' => ATBDP_POST_TYPE,
        'post_status' => 'publish',
        'posts_per_page' => $list_num,
    );
    $meta_queries = array();

    if ($has_featured) {
        $args['meta_key'] = '_featured';
        $args['orderby'] = array(
            'meta_value_num' => 'DESC',
            'date' => 'DESC',
        );
    }
    if ('yes' == $featured) {
        $meta_queries['_featured'] = array(
            'key' => '_featured',
            'value' => 1,
            'type' => 'NUMERIC',
            'compare' => 'EXISTS',
        );
        $meta_queries['need_post'] = array(
            array(
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
            )
        );
    } else {
        $meta_queries['need_post'] = array(
            array(
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
            )
        );
    }

    $count_meta_queries = count($meta_queries);
    if ($count_meta_queries) {
        $args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
    }

    $all_listings = new WP_Query($args); ?>

    <div id="directorist" class="listing-carousel-wrapper atbd_wrapper <?php echo implode(' ', $wrap_class); ?>">
        <div class="listing-carousel owl-carousel">
            <?php
            if ($all_listings->have_posts()) {
                while ($all_listings->have_posts()) {
                    $all_listings->the_post();

                    $locs = get_the_terms(get_the_ID(), ATBDP_LOCATION);
                    $featured = get_post_meta(get_the_ID(), '_featured', true);
                    $address = get_post_meta(get_the_ID(), '_address', true);
                    $phone_number = get_post_meta(get_the_Id(), '_phone', true);
                    $display_title = get_directorist_option('display_title', 1);
                    $display_review = get_directorist_option('enable_review', 1);
                    $display_price = get_directorist_option('display_price', 1);
                    $display_mark_as_fav = get_directorist_option('display_mark_as_fav', 1);
                    $display_author_image = get_directorist_option('display_author_image', 1);
                    $display_publish_date = get_directorist_option('display_publish_date', 1);
                    $display_contact_info = get_directorist_option('display_contact_info', 1);
                    $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
                    $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
                    $feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');
                    $address_location = get_directorist_option('address_location', 'location');
                    /*Code for Business Hour Extensions*/
                    $author_id = get_the_author_meta('ID');
                    $u_pro_pic_id = get_user_meta($author_id, 'pro_pic', true);
                    $u_pro_pic = wp_get_attachment_image_src($u_pro_pic_id, 'thumbnail');
                    $display_address_field = get_directorist_option('display_address_field', 1);
                    $display_phone_field = get_directorist_option('display_phone_field', 1); ?>

                    <div class="atbdp_column_carousel">
                        <div class="atbd_single_listing atbd_listing_card ">

                            <article
                                    class="atbd_single_listing_wrapper <?php echo ('yes' == $featured) ? 'directorist-featured-listings' : ''; ?>">

                                <figure class="atbd_listing_thumbnail_area">

                                    <div class="atbd_listing_image">
                                        <?php
                                        the_thumbnail_card();
                                        if ($display_author_image) {
                                            $author = get_userdata($author_id);
                                            $author_avatar = $u_pro_pic ? sprintf('<img src="%s" alt="%s">', esc_url($u_pro_pic[0]), direo_get_image_alt($u_pro_pic_id)) : get_avatar($author_id, 32);

                                            echo sprintf('<div class="atbd_author"> <a href="%s" aria-label="%s" class="atbd_tooltip">%s</a> </div>', esc_url(ATBDP_Permalink::get_user_profile_page_link($author_id)), esc_attr($author->first_name . ' ' . $author->last_name), $author_avatar);
                                        } ?>
                                    </div>

                                    <span class="atbd_lower_badge">
                                            <?php
                                            if ('yes' == $featured && $display_feature_badge_cart) {
                                                echo sprintf('<span class="atbd_badge atbd_badge_featured">%s</span>', esc_attr($feature_badge_text));
                                            }

                                            $popular_listing_id = atbdp_popular_listings(get_the_ID());

                                            if ($popular_listing_id === get_the_ID()) {
                                                echo sprintf('<span class="atbd_badge atbd_badge_popular">%s</span>', esc_attr($popular_badge_text));
                                            }

                                            echo new_badge(); ?>
                                        </span>

                                    <?php echo !empty($display_mark_as_fav) ? atbdp_listings_mark_as_favourite(get_the_ID()) : ''; ?>

                                </figure>

                                <div class="atbd_listing_info">
                                    <?php if ($display_title || $display_review || $display_price) { ?>
                                        <div class="atbd_content_upper">

                                            <?php
                                            $listing_title = sprintf('<a href="%s">%s</a>', esc_url(get_post_permalink(get_the_ID())), stripslashes(get_the_title()));

                                            echo !empty($display_title) ? sprintf('<h4 class="atbd_listing_title">%s</h4>', wp_kses_post($listing_title)) : '';

                                            function_exists('direo_listings_review_price') ? direo_listings_review_price() : '';

                                            if ($display_contact_info || $display_publish_date || $display_phone_field) { ?>
                                                <div class="atbd_listing_data_list">
                                                    <ul>
                                                        <?php
                                                        if ($display_contact_info) {
                                                            if ($address && ('contact' == $address_location) && $display_address_field && $contact) {
                                                                echo sprintf('<li> <p> <span class="%s-map-marker"></span>%s</p> </li>', atbdp_icon_type(false), stripslashes($address));
                                                            } elseif ($locs && ('location' == $address_location) && $contact) {
                                                                $output = $link = [];
                                                                foreach ($locs as $loc) {
                                                                    $link = ATBDP_Permalink::atbdp_get_location_page($loc);
                                                                    $space = str_repeat(' ', 1);
                                                                    $output [] = sprintf('%s<a href=%s>%s</a>', esc_attr($space), esc_url($link), esc_attr($loc->name));
                                                                }

                                                                echo sprintf('<li><p><span class=%s-map-marker></span>%s</span></p></li>', atbdp_icon_type(), join(',', $output));
                                                            }
                                                            if ($phone_number && $display_phone_field && $phone) {
                                                                echo sprintf('<li> <p> <span class="%s-phone"></span> <a href="tel:%s">%s</a> </p> </li>', atbdp_icon_type(), stripslashes($phone_number), stripslashes($phone_number));
                                                            }
                                                        }

                                                        if ($display_publish_date && $date) { ?>
                                                            <li>
                                                                <p>
                                                                    <span class="<?php atbdp_icon_type(true); ?>-clock-o"></span>
                                                                    <?php $publish_date_format = get_directorist_option('publish_date_format', 'time_ago');
                                                                    if ('time_ago' === $publish_date_format) {
                                                                        printf(__('Posted %s ago', 'direo'), human_time_diff(get_the_time('U'), current_time('timestamp')));
                                                                    } else {
                                                                        echo get_the_date();
                                                                    } ?>
                                                                </p>
                                                            </li>
                                                            <?php
                                                        } ?>
                                                    </ul>
                                                </div>
                                                <?php
                                            } ?>
                                        </div>
                                        <?php
                                    }

                                    function_exists('direo_listing_grid_footer_content') ? direo_listing_grid_footer_content() : ''; ?>

                            </article>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            } else { ?>
                <p class="atbdp_nlf">
                    <?php esc_html_e('No listing found.', 'direo'); ?>
                </p>
                <?php
            } ?>
        </div>
    </div>
    <?php
}