<!DOCTYPE html>
<html <?php language_attributes('/languages'); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>

<?php
global $post;
$listing_id = $post->ID;
$tagline = get_post_meta(get_the_ID(), '_tagline', true);
$enable_new_listing = get_directorist_option('display_new_badge_cart', 1);
$display_feature_badge_single = get_directorist_option('display_feature_badge_cart', 1);
$display_popular_badge_single = get_directorist_option('display_popular_badge_cart', 1);
$popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
$feature_badge_text = get_directorist_option('feature_badge_text', 'Feature');
$new_badge_text = get_directorist_option('new_badge_text', 'New');

$display_tagline_field = get_directorist_option('display_tagline_field', 0);
$enable_social_share = get_directorist_option('enable_social_share', 1);
$enable_favourite = get_directorist_option('enable_favourite', 1);
$enable_report_abuse = get_directorist_option('enable_report_abuse', 1);
$is_disable_price = get_directorist_option('disable_list_price');
$display_pricing_field = get_directorist_option('display_pricing_field', 1);
$enable_review = get_directorist_option('enable_review', 'yes');
$reviews_count = ATBDP()->review->db->count(array('post_id' => $listing_id));

$listing_info['featured'] = get_post_meta($post->ID, '_featured', true);
$listing_info['tagline'] = get_post_meta($post->ID, '_tagline', true);
$listing_info['price'] = get_post_meta($post->ID, '_price', true);
$listing_info['price_range'] = get_post_meta($post->ID, '_price_range', true);
$listing_info['atbd_listing_pricing'] = get_post_meta($post->ID, '_atbd_listing_pricing', true);
extract($listing_info); ?>

<body <?php body_class(); ?>>

<?php

(direo_menu_style() && 'menu1' != direo_menu_style()) ? direo_menu_area() : ''; ?>

<section class="listing-details-wrapper bgimage">

    <div class="bg_image_holder">
        <?php direo_single_listing_header_background(); ?>
    </div>

    <?php (empty(direo_menu_style()) || 'menu1' == direo_menu_style()) ? direo_menu_area() : ''; ?>

    <div class="listing-info content_above">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <?php
                    if (!empty($enable_new_listing || $display_feature_badge_single || $display_popular_badge_single)) { ?>
                        <ul class="list-unstyled listing-info--badges">
                            <?php
                            $popular_listing_id = atbdp_popular_listings(get_the_ID());
                            if (!empty(new_badge())) {
                                echo '<li>' . new_badge() . '</li>';
                            }
                            if ($featured && !empty($display_feature_badge_single)) {
                                printf('<li><span class="atbd_badge atbd_badge_featured">%s</span></li>', esc_attr($feature_badge_text));
                            }

                            if ($popular_listing_id === get_the_ID()) {
                                echo '<li><span class="atbd_badge atbd_badge_popular">' . esc_attr($popular_badge_text) . '</span></li>';
                            } ?>
                        </ul>
                        <?php
                    } ?>
                    <ul class="list-unstyled listing-info--meta">
                        <?php
                        $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';

                        if (empty($is_disable_price)) {
                            if (!empty($display_pricing_field)) {
                                if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
                                    echo atbdp_display_price_range($price_range);
                                } else {
                                    if (!empty($price)) {
                                        echo '<li>';
                                        atbdp_display_price($price, $is_disable_price);
                                        echo '</li>';
                                    }
                                }
                            }
                        }
                        if ($enable_review) { ?>
                            <li>
                                <div class="average-ratings">
                                    <?php direo_listing_review(); ?>
                                    <span>
                                        <strong><?php echo wp_kses_post($reviews_count); ?></strong>
                                        <?php echo 1 == $reviews_count ? esc_html__(' Review', 'direo') : esc_html__(' Reviews', 'direo'); ?>
                                    </span>

                                </div>
                            </li>
                            <?php
                        }
                        $categories = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                        if (!empty($categories)) { ?>
                            <li>
                                <div class="atbd_listing_category">
                                    <?php
                                    foreach ($categories as $category) {
                                        $category_icon = !empty($category) ? get_cat_icon($category->term_id) : atbdp_icon_type() . '-tags';
                                        $icon_type = substr($category_icon, 0, 2);
                                        $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;

                                        echo sprintf('<span class="%s direo-cats-icon"></span> <a href="%s">%s </a>', esc_attr($icon), esc_url(ATBDP_Permalink::atbdp_get_category_page($category)), esc_attr($category->name));
                                    } ?>
                                </div>
                            </li>
                            <?php
                        }
                        do_action('direo_single_listing_after_category');
                        ?>
                    </ul>
                    <div class="diero_single_listing_title">

                        <?php the_title('<h1>', '</h1>');

                        if (get_directorist_option('enable_claim_listing', 1) && get_directorist_option('verified_badge', 1)) {

                            $claimed_by_admin = get_post_meta($listing_id, '_claimed_by_admin', true);
                            $claimed_text = esc_html__('Claimed', 'direo');
                            if (!empty($claimed_by_admin)) {
                                printf('<div class="dcl_claimed"><div class="dcl_claimed--badge"><span><i class="fa fa-check"></i></span>%s</div> <span class="dcl_claimed--tooltip">%s</span></div>', get_directorist_option('verified_text', $claimed_text), esc_html__("Verified by it's Owner", 'direo'));
                            }
                        } ?>
                    </div>

                    <?php
                    if (!empty($tagline && $display_tagline_field)) { ?>
                        <p class="atbd_sub_title subtitle"><?php echo !empty($tagline) ? esc_html(stripslashes($tagline)) : ''; ?></p>
                        <?php
                    } ?>
                </div>
                <div class="col-lg-4 col-md-5 d-flex align-items-end justify-content-start justify-content-md-end">
                    <div class="atbd_listing_action_area">
                        <?php
                        do_action('direo_listing_detail_before_favourite');

                        if ($enable_favourite) { ?>
                            <div class="atbd_action atbd_save" id="atbdp-favourites">
                                <?php echo the_atbdp_favourites_link(); ?>
                            </div>
                            <?php
                        }

                        if ($enable_social_share) { ?>
                            <div class="atbd_action atbd_share dropdown">
                                <?php direo_sharing(); ?>
                            </div>
                            <?php
                        }

                        if ($enable_report_abuse) { ?>
                            <div class="atbd_action atbd_report">
                                <?php if (is_user_logged_in()) { ?>

                                    <span class="<?php echo atbdp_icon_type(); ?>-flag"> </span>
                                    <a href="javascript:void(0)" data-toggle="modal"
                                       data-target="atbdp-report-abuse-modal">
                                        <?php esc_html_e('Report', 'direo'); ?>
                                    </a>
                                <?php } else { ?>
                                    <a href="javascript:void(0)" class="atbdp-require-login">
                                        <span class="fa fa-flag"></span>
                                        <?php esc_html_e('Report', 'direo'); ?>
                                    </a>
                                <?php } ?>
                                <input type="hidden" id="atbdp-post-id" value="<?php echo get_the_ID(); ?>"/>
                            </div>
                            <?php
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>