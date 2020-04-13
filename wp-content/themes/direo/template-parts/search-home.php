<?php
/*
 *Template Name: Search Home
 */

get_header('search');

if (is_elements()) {
    while (have_posts()) {
        the_post();
        the_content();
    }
    wp_reset_postdata();
} else {
    while (have_posts()) {
        the_post(); ?>
        <section class="search-home-area section-padding-strict">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        the_content();

                        direo_page_pagination();

                        if (comments_open()) {
                            comments_template();
                        } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
get_footer();
