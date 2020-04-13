<?php

get_header();
if (function_exists('setPostViews')) {
    setPostViews(get_the_ID());
}
$sidebar = get_theme_mod('sidebar', 'right');
$post_meta = get_theme_mod('post_meta', 'on');
$post_author = get_theme_mod('post_author', 'on');
$post_share = get_theme_mod('post_share', 'on');
$post_pagination = get_theme_mod('post_pagination', 'on');
$post_related = get_theme_mod('post_related', 'on');
$single_fullwidth = get_theme_mod('single_fullwidth', false);
?>

    <section class="single-area section-padding-strict">
        <div class="<?php echo !empty($single_fullwidth) ? esc_html('container-fulid') : esc_html('container'); ?>">
            <div class="row">
                <?php 'left' == $sidebar ? get_sidebar() : '';
                if (have_posts()) {
                    while (have_posts()) {
                        the_post(); ?>
                        <div class="<?php echo is_active_sidebar('blog_sidebar') ? esc_html('col-lg-8') : esc_html('col-lg-8 offset-lg-2') ?>">
                            <div class="post-details">

                                <?php get_template_part('template-parts/single-post/post'); ?>

                                <div class="post-content">
                                    <div class="post-header">

                                        <?php
                                        if (function_exists('direo_blog_meta_info')) {
                                            if ($post_meta == 'on') {
                                                direo_blog_meta_info();
                                            }
                                        } ?>

                                    </div>
                                    <div class="post-body">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (function_exists('direo_post_tags') && !empty(get_the_tags())) { ?>
                                <div class="post-bottom d-flex justify-content-between">
                                    <?php
                                    if (function_exists('direo_post_tags')) {
                                        direo_post_tags();
                                    }
                                    if (function_exists('direo_share_post') && $post_share == 'on') {
                                        direo_share_post();
                                    } ?>

                                </div>
                                <?php
                            }

                            if (!post_password_required()) {
                                if ($post_author == 'on') {
                                    get_template_part('template-parts/post-feature/author', 'bio');
                                }
                            } ?>

                            <?php if (function_exists('direo_post_navigation') && $post_pagination == 'on') {
                                direo_post_navigation();
                            } ?>

                            <?php
                            if (!post_password_required() && wp_get_post_categories(get_the_ID()) != '') {
                                if (function_exists('direo_related_post') && $post_related == 'on') {
                                    direo_related_post();
                                }
                            };

                            if (comments_open() || get_comments_number()) {
                                comments_template();
                            } ?>

                        </div>
                        <?php
                    }
                    wp_reset_query();
                } else {
                    get_template_part('template-parts/content', 'none');
                }

                'right' == $sidebar ? get_sidebar() : ''; ?>

            </div>
        </div>
    </section>
<?php
get_footer();