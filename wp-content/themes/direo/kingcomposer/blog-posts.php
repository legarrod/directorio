<?php
/*==========================================
    Shortcode : Text Block
    Author URI: https://aazztech.com
============================================*/
$number_post = $order_by = $order_list = '';

extract($atts);

$output = '';
$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);

$args = array(
    'post_type' => 'post',
    'posts_per_page' => esc_attr($number_post),
    'order' => esc_attr($order_list),
    'orderby ' => esc_attr($order_by)
);
$posts = new WP_Query($args); ?>

<div class="blog-posts row <?php echo esc_attr(implode(' ', $el_class)); ?>" data-uk-grid>
    <?php while ($posts->have_posts()) {
        $posts->the_post(); ?>
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="blog-posts__single">
                <?php the_post_thumbnail('direo_blog_grid'); ?>
                <div class="blog-posts__single__contents">
                    <?php the_title(sprintf('<h4><a href="%s">', get_the_permalink()), '</a></h4>'); ?>
                    <ul>
                        <li><?php echo direo_time_link(); ?></li>
                        <?php if (function_exists('direo_post_cats')) {
                            direo_post_cats();
                        } ?>
                    </ul>
                    <?php the_excerpt(); ?>
                </div>
            </div>
        </div>
        <?php
    }
    wp_reset_postdata(); ?>
</div>
