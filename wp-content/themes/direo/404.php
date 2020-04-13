<?php
get_header();

$title = get_theme_mod('404_title', __('Oops! That page can’t be found.', 'direo'));
$desc = get_theme_mod('404_desc'); ?>

    <section class="section-padding-strict">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error-contents text-center">
                        <figure>
                            <img src="<?php echo get_template_directory_uri() ?>/img/404.png"
                                 alt="<?php echo esc_attr('404', 'direo'); ?>">
                        </figure>
                        <h2 class="m-bottom-15">
                            <?php echo esc_attr($title); ?>
                        </h2>
                        <?php echo apply_filters('the_content', $desc); ?>
                        <div class="widget-wrapper m-top-50">
                            <div class="search-widget">
                                <div class="row">
                                    <div class="col-md-6 offset-md-3">
                                        <form role="search" method="get" action="<?php echo esc_url(home_url()); ?>">
                                            <div class="input-group">
                                                <input type="search" value="<?php echo esc_attr(get_search_query()); ?>"
                                                       name="s" class="fc--rounded"
                                                       placeholder="<?php echo esc_attr_x('Search', 'placeholder', 'direo'); ?>">
                                                <button value="search" type="submit"><i class="la la-search"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
get_footer();