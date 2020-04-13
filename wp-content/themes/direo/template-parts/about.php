<?php
/*
 *Template Name: About Page
 */
get_header('about');

if (is_elements()) {
    while (have_posts()) {
        the_post();
        the_content();
    }
    wp_reset_postdata();
} else {
    while (have_posts()) {
        the_post(); ?>
        <section class="blog-area section-padding-strict">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        the_content();
                        wp_link_pages(array(
                            'before' => '<div class="m-top-50"><nav class="navigation pagination d-flex justify-content-center" role="navigation"><div class="nav-links">',
                            'after' => '</div></nav></div>',
                            'pagelink' => '<span class="page-numbers">%</span>',
                        ));
                        if (comments_open()) {
                            comments_template();
                        } ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
get_footer();