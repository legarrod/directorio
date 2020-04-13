<?php

/*==========================================
    Shortcode : Testimonial Carousel
    Author URI: https://aazztech.com
============================================*/
$class = '';
$testimonials = [];

extract($atts);
$el_class = apply_filters('kc-el-class', $atts);
unset($el_class[0]);
$el_class[] = 'testimonial-carousel owl-carousel ' . $class; ?>

<div class="<?php echo implode(' ', $el_class); ?>">
    <?php
    if ($testimonials) {
        foreach ($testimonials as $test) {
            $id = $test->image ? wp_get_attachment_image_src($test->image, array(80, 80)) : '';
            $image = $id[0] ? $id[0] : '';
            $name = $test->name ? $test->name : '';
            $position = $test->position ? $test->position : '';
            $desc = $test->desc ? $test->desc : ''; ?>

            <div class="carousel-single">
                <?php echo !empty($image) ? sprintf('<div class="author-thumb"><img src="%s" alt="%s" class="rounded-circle"></div>', esc_url($image), direo_get_image_alt($test->image)) : ''; ?>
                <div class="author-info">
                    <?php echo sprintf('<h4>%s</h4>', esc_attr($name));
                    echo sprintf('<span>%s</span>', esc_attr($position)); ?>
                </div>
                <?php echo sprintf('<p class="author-comment">%s</p>', esc_attr($desc)); ?>
            </div>

            <?php
        }
    } ?>
</div>