<?php
/*==========================================
    Shortcode : Clients Logo Carousel
    Author URI: https://aazztech.com
============================================*/
$clients_logo = '';

extract($atts);
$wrap_class = apply_filters('kc-el-class', $atts);
unset($wrap_class[0]);
$logos = explode(',', $clients_logo); ?>

<div class="logo-carousel owl-carousel <?php echo implode(' ', $wrap_class); ?>">
    <?php
    if ($logos) {
        foreach ($logos as $logo) {
            $logos = !empty($logo) ? wp_get_attachment_image_src($logo, 'full') : ''; ?>
            <div class="carousel-single">
                <img src="<?php echo esc_url($logos[0]); ?>" alt="<?php echo direo_get_image_alt($logo); ?>">
            </div>
            <?php
        }
        wp_reset_postdata();
    } ?>
</div>
