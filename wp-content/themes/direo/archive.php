<?php get_header();
$sidebar = get_theme_mod('sidebar','right');  ?>

    <section class="archive-area section-padding-strict">
        <div class="container">
            <div class="row">
                <?php 'left' == $sidebar ? get_sidebar() : ''; ?>
                <div class="<?php echo is_active_sidebar('blog_sidebar') ? esc_html('col-lg-8') : esc_html('col-lg-8 offset-lg-2')?>">
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
                        wp_reset_query(); ?>

                    </div>

                    <?php direo_pagination(); ?>
                </div>
                <?php 'right' == $sidebar ? get_sidebar() : ''; ?>
            </div>
        </div>
    </section>

<?php
get_footer();