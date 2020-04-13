<!DOCTYPE html>
<html <?php language_attributes('/languages'); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php wp_head(); ?>
</head>

<?php
$header_title = get_theme_mod('about_title', esc_html__('Place your Business or Explore Anything what you want', 'direo'));
$video = get_theme_mod('video', 'https://www.youtube.com/watch?v=0C4fX_x_Vsg');
$btn = get_theme_mod('btn', esc_html__('Play our video', 'direo')); ?>

<body <?php body_class(); ?>>

<?php (direo_menu_style() && 'menu1' != direo_menu_style()) ? direo_menu_area() : ''; ?>

<section class="about-wrapper bg-gradient-ps">

    <?php (empty(direo_menu_style()) || 'menu1' == direo_menu_style()) ? direo_menu_area() : ''; ?>

    <div class="about-intro content_above">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-6">
                    <?php
                    echo !empty($header_title) ? sprintf('<h1 id="header_title">%s</h1>', esc_attr($header_title)) : '';
                    echo !empty($btn) && $video ? sprintf('<a href="%s" class="video-iframe play-btn-two"> <span class="icon"><i class="la la-youtube-play"></i></span> <span>%s</span> </a>', esc_url($video), esc_attr($btn)) : ''; ?>
                </div>
                <?php
                echo has_post_thumbnail() ? sprintf('<div class="col-lg-6 offset-lg-1 col-md-6 offset-md-0 col-sm-8 offset-sm-2">%s</div>', get_the_post_thumbnail()) : ''; ?>
            </div>
        </div>
    </div>

</section>