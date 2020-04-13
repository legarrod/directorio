<?php
$search_result = esc_html__('Search: ', 'direo') . get_search_query();
$search_title = sprintf(esc_html__('Search Results for: "%s"', 'direo'), get_search_query());
$categories_list = get_the_category_list(esc_html__(', ', 'direo'));

$banner = get_post_meta(direo_page_id(), 'banner_style', true);
$blog_page_title = get_theme_mod('blogs_page_title', 'Latest Blogs');

if (class_exists('woocommerce')) {
    $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
    $product_cat_name = !empty($product_cats[0]->name) ? $product_cats[0]->name : '';
    $product_cat_id = !empty($product_cats[0]->term_id) ? $product_cats[0]->term_id : '';
    $product_cat_link = get_term_link($product_cat_id, 'product_cat');
} ?>

<div class="breadcrumb-wrapper content_above">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <?php
                if (class_exists('Directorist_Base') && ('search' == $banner)) {

                    echo '<h2 class="page-title m-bottom-30">';
                    if (direo_directorist_pages('search_result_page')) {
                        echo function_exists('direo_listing_search_title') ? direo_listing_search_title('Results For: ') : wp_title('');
                    } else {
                        wp_title('');
                    }
                    echo '</h2>';
                    echo do_shortcode('[direo_quick_search_form]'); ?>
                    <?php

                } else { ?>
                    <h1 class="page-title">
                        <?php
                        if (is_search()) {
                            echo wp_kses_post($search_title);
                        } elseif (class_exists('woocommerce') && is_shop() || class_exists('woocommerce') && is_product()) {
                            echo get_the_title(wc_get_page_id('shop'));
                        } elseif (is_archive()) {
                            echo get_the_archive_title();
                        } elseif (is_home()) {
                            echo !empty(wp_title('', false, '')) ? wp_title('', false, '') : esc_attr($blog_page_title);
                        } else {
                            wp_title('');
                        } ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?php echo esc_url(home_url()); ?>">
                                    <?php esc_html_e('Home', 'direo') ?>
                                </a>
                            </li>
                            <?php if (class_exists('woocommerce') && is_product()) { ?>
                                <li class="breadcrumb-item active">
                                    <a href="<?php echo esc_url($product_cat_link); ?>"><?php echo esc_attr($product_cat_name); ?></a>
                                </li>
                                <?php
                            }
                            if (is_home()) { ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo !empty(wp_title('', false, '')) ? wp_title('', false, '') : esc_attr($blog_page_title); ?>
                                </li>
                                <?php
                            }
                            if (is_page()) { ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php wp_title(''); ?>
                                </li>
                                <?php
                            }
                            if (is_search()) { ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo esc_attr($search_result); ?>
                                </li>
                                <?php
                            } elseif (class_exists('woocommerce') && is_shop()) { ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php wp_title(' ', true, ' '); ?>
                                </li>
                                <?php
                            } elseif (is_archive()) { ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php the_archive_title(); ?>
                                </li>
                                <?php
                            }
                            if (is_single()) {
                                if (!empty($categories_list) && function_exists('direo_post_cats')) { ?>
                                    <li class="breadcrumb-item">
                                        <?php echo wp_kses_post($categories_list); ?>
                                    </li>
                                    <?php
                                } ?>
                                <li class="breadcrumb-item active">
                                    <?php wp_title('', true, '') ?>
                                </li>
                                <?php
                            } ?>
                        </ol>
                    </nav>
                    <?php
                } ?>
            </div>
        </div>
    </div>
</div>