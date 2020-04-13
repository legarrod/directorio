<!DOCTYPE html>
<html <?php language_attributes('/languages'); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php (direo_menu_style() && 'menu1' != direo_menu_style()) ? direo_menu_area() : ''; ?>

<section class="intro-wrapper bgimage overlay overlay--dark">

    <?php
    if (has_post_thumbnail()) { ?>
        <div class="bg_image_holder">
            <?php echo sprintf('<img src="%s" alt="%s">', esc_url(get_the_post_thumbnail_url()), direo_get_image_alt(get_post_thumbnail_id(get_the_ID()))); ?>
        </div>
        <?php
    }

    !direo_menu_style() || 'menu1' == direo_menu_style() ? direo_menu_area() : '';

    if (class_exists('Directorist_Base')) { ?>
        <div class="directory_content_area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <?php echo do_shortcode('[directorist_search_listing]'); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } ?>

</section>