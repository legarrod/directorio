<?php get_header('at_biz_dir'); ?>
    <section class="directory_listing_detail_area single_area section-bg section-padding-strict">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if (have_posts()) {
                        the_post();
                        the_content();
                    } else {
                        get_template_part('template-parts/content', 'none');
                    } ?>
                </div>
            </div>
        </div>
    </section>
<?php
get_footer();