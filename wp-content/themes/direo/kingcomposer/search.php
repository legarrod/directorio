<?php
/*==========================================
    Shortcode : Search
    Author URI: https://aazztech.com
============================================*/
if (!class_exists('Directorist_Base')) {
    return false;
}

$text_field = $category_field = $location_field = $advance = $popular = $popular_count = $submit = $border = '';
extract($atts);
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]); ?>

<div id="directorist"
           class="atbd_wrapper directory_search_area ads-advaced--wrapper <?php echo implode(' ', $wrap_class); ?>">
    <div class="row">
        <div class="col-md-12">
            <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
                <?php if (function_exists('direo_search_form_element')) { ?>
                    <div class="atbd_seach_fields_wrapper <?php echo !$border ? esc_html('border-0') : ''; ?>">
                        <div class="row atbdp-search-form">
                            <?php direo_search_form_element($text = $text_field, $cat = $category_field, $loc = $location_field, $more = $advance) ?>
                        </div>
                    </div>
                    <?php
                }
                if ('yes' == $advance && function_exists('direo_more_filter_search_form')) {
                    direo_more_filter_search_form();
                } ?>
            </form>
        </div>
    </div>
    <?php if ('yes' == $popular) { ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $args = array(
                    'type' => ATBDP_POST_TYPE,
                    'parent' => 0,
                    'orderby' => 'count',
                    'order' => 'desc',
                    'hide_empty' => 1,
                    'number' => (int)$popular_count,
                    'taxonomy' => ATBDP_CATEGORY,
                    'no_found_rows' => true,

                );
                $top_categories = get_categories($args);
                if ($top_categories) { ?>
                    <div class="directory_home_category_area">
                        <ul class="categories">
                            <?php
                            foreach ($top_categories as $cat) {
                                $icon = get_cat_icon($cat->term_id);
                                $icon_type = substr($icon, 0, 2);
                                $icon = 'la' === $icon_type ? $icon_type . ' ' . $icon : 'fa ' . $icon;

                                echo sprintf('<li><a href="%s"><span class="%s" aria-hidden="true"></span> <p>%s</p></a></li>', esc_url(ATBDP_Permalink::atbdp_get_category_page($cat)), esc_attr($icon), esc_attr($cat->name));
                            }
                            wp_reset_postdata(); ?>
                        </ul>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
        <?php
    } ?>
</div>