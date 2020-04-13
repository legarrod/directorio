<div class="container <?php echo (is_search()) ? esc_html('section-padding') : ''; ?>">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="error-contents text-center">
                <?php if (is_home() && current_user_can('publish_posts')) { ?>

                    <h2><?php esc_html_e('Nothing Found', 'direo'); ?></h2>
                    <p>
                        <?php esc_html_e('Ready to publish your first post?', ''); ?>
                        <?php printf('<a href="%s">%s</a>.', esc_url(admin_url('post-new.php')), esc_html__('Get started here', 'direo')); ?>
                    </p>

                    <?php
                } elseif (is_search()) { ?>

                    <h2><?php esc_html_e('Nothing exists here', 'direo'); ?></h2>
                    <p class="m-top-15">
                        <?php esc_html_e("We couldn't find any results for your search. Try clearing some filters and try again.", 'direo'); ?>
                    </p>
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
                <?php } else { ?>
                    <h2><?php esc_html_e('Nothing Found', 'direo'); ?></h2>
                    <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'direo'); ?></p>
                    <div class="widget-wrapper m-top-60">
                        <div class="search-widget">
                            <form role="search" method="get" action="<?php echo esc_url(home_url()); ?>">
                                <div class="input-group">
                                    <input type="search" value="<?php echo esc_attr(get_search_query()); ?>" name="s"
                                           class="fc--rounded"
                                           placeholder="<?php echo esc_attr_x('Search', 'placeholder', 'direo'); ?>">
                                    <button value="search" type="submit"><i class="la la-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>