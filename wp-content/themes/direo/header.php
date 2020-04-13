<!DOCTYPE html>
<html <?php language_attributes('/languages'); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >

<?php

if (function_exists('changed_header_footer') && changed_header_footer()){
    direo_menu_area();
}else{

    direo_menu_style() && ('menu1' != direo_menu_style()) ? direo_menu_area() : ''; ?>

    <section id="header-breadcrumb" class="header-breadcrumb bgimage overlay overlay--dark">
        <?php
        direo_header_background();

        !direo_menu_style() || 'menu1' == direo_menu_style() ? direo_menu_area() : '';

        $banner = get_post_meta(direo_page_id(), 'banner_style', true);

        if ('banner_off' != $banner || empty($banner) || is_search()) {
            get_template_part('template-parts/common/breadcrumb');
        } ?>
    </section>

    <?php
}