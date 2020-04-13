<?php
/*
 *Template Name: Page With Sidebar
 */
get_header();

while (have_posts()) {
    the_post(); ?>

    <section class="page-area section-padding-strict">
        <div class="container">
            <div class="row">
                <div class="<?php echo is_active_sidebar('page_sidebar') ? esc_html('col-lg-8') : esc_html('col-md-12') ?>">
                    <?php
                    the_content();
                    wp_link_pages(array(
                        'before' => '<div class="m-top-50"><nav class="navigation pagination d-flex justify-content-center" role="navigation"><div class="nav-links">',
                        'after' => '</div></nav></div>',
                        'pagelink' => '<span class="page-numbers">%</span>',
                    ));
                    if (comments_open()) {
                        comments_template();
                    }
                    ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </section>

<?php }
get_footer();