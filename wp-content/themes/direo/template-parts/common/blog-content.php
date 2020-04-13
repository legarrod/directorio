<?php
$show_excerpt = get_query_var('post_excerpt', false);
$blog_meta = get_theme_mod('blog_meta', 'on');
$blog_style = get_theme_mod('blog_style', 'default');

if ('default' == $blog_style) { ?>

    <div class="card-body">
        <?php the_title(sprintf('<h3><a href="%s">', get_the_permalink()), '</a></h3>');

        if (function_exists('direo_blog_meta_info')) {
            if(true == $blog_meta){
                direo_blog_meta_info();
            }
        }

        if ($show_excerpt) {
            the_excerpt();
        } else {
            if (!has_excerpt()) {
                $excerpt = substr(get_the_excerpt(), 0, 138);
                echo wpautop(strip_shortcodes($excerpt));
            } else {
                the_excerpt();
            }
        } ?>
    </div>
<?php } else { ?>
    <div class="card-body">
        <?php the_title(sprintf('<h6><a href="%s">', get_the_permalink()), '</a></h6>');

        if (function_exists('direo_blog_meta_info')) {
            if(true == $blog_meta){
                direo_blog_meta_info();
            }
        }

        if ($show_excerpt) {
            the_excerpt();
        } else {
            if (!has_excerpt()) {
                $excerpt = substr(get_the_excerpt(), 0, 138);
                echo wpautop(strip_shortcodes($excerpt));
            } else {
                the_excerpt();
            }
        } ?>
    </div>
<?php } ?>