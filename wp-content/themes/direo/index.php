<?php
get_header();

$sidebar = get_theme_mod('sidebar', 'right');
$blog_fullwidth = get_theme_mod('blog_fullwidth', false);

$blog_style = get_theme_mod('blog_style', 'default'); ?>

    <section class="blog-area section-padding-strict <?php if ('grid' == $blog_style) echo esc_html('section-bg'); ?>">
        <div class="<?php echo !empty($blog_fullwidth) ? esc_html('container-fulid') : esc_html('container'); ?>">
            <?php if ('default' == $blog_style) { ?>
                <div class="row">

                    <?php 'left' == $sidebar ? get_sidebar() : ''; ?>

                    <div class="<?php echo is_active_sidebar('blog_sidebar') ? esc_html('col-lg-8') : esc_html('col-lg-8 offset-lg-2') ?>">
                        <div class="blog-posts">

                            <?php
                            if (have_posts()) :

                                while (have_posts()) : the_post();

                                    get_template_part('template-parts/post');

                                endwhile;
                                wp_reset_postdata();

                            else :

                                get_template_part('template-parts/content', 'none');

                            endif;
                            wp_reset_query();
                            ?>

                        </div>

                        <?php direo_pagination(); ?>
                    </div>
                    <?php 'right' == $sidebar ? get_sidebar() : ''; ?>
                </div>
            <?php } else { ?>
                <div class="row">
                    <?php
                    if (have_posts()) :

                        while (have_posts()) : the_post();

                            get_template_part('template-parts/post');

                        endwhile;
                        wp_reset_postdata();

                    else :

                        get_template_part('template-parts/content', 'none');

                    endif;
                    wp_reset_query();
                    ?>
                </div>
                <?php direo_pagination(); ?>
            <?php } ?>
        </div>
    </section>
<?php
get_footer();
