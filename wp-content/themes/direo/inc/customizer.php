<?php
/*=====================================================
    Removing default color field from customizer
=================================================*/

function my_customize_register()
{
    global $wp_customize;
    $wp_customize->remove_section('colors');

    $wp_customize->selective_refresh->add_partial('footer', array(
        'selector' => '#footer_selector',
        'settings' => 'footer_area',
        'render_callback' => function () {
            return get_theme_mod('copy_right');
        }
    ));
}

add_action('customize_register', 'my_customize_register', 11);

/*=====================================================
    Checked for kirki
=================================================*/
if (!class_exists('Kirki')) {
    return;
}

/*=====================================================
    Section & Panel for customizer
=================================================*/
Kirki::add_field('direo_customizer', [
    'type' => 'image',
    'settings' => 'footer_logo',
    'label' => esc_html__('Logo For Light Background', 'direo'),
    'section' => 'title_tagline',
    'priority' => 9,
]);

Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'transport' => 'auto',
    'settings' => 'site_title_color',
    'label' => esc_html__('Title Color', 'direo'),
    'section' => 'title_tagline',
    'default' => '#f26d90',
    'priority' => 10,
    'output' => array(
        array(
            'element' => '#site_title_color',
            'property' => 'color'
        ),
    ),
]);


Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'transport' => 'auto',
    'settings' => 'tagline_color',
    'label' => esc_html__('Tagline Color', 'direo'),
    'section' => 'title_tagline',
    'default' => 'rgba(255, 255, 255, 0.7)',
    'output' => array(
        array(
            'element' => '#site_tagline_color',
            'property' => 'color'
        ),
    ),
    'choices' => [
        'alpha' => true,
    ],
]);

/*
 * Header Options
 */
Kirki::add_section('breadcrumb', array(
    'priority' => 35,
    'title' => esc_html__('Header Options', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'select',
    'settings' => 'menu_style',
    'label' => esc_html__('Menu Area', 'direo'),
    'section' => 'breadcrumb',
    'default' => 'menu1',
    'description' => esc_html__('Menu style of single listing and single post', 'direo'),
    'choices' => [
        'menu1' => esc_html__('Default', 'direo'),
        'menu2' => esc_html__('Light Background', 'direo'),
        'menu3' => esc_html__('Dark Background', 'direo'),
    ],
]);

Kirki::add_field('direo_customizer', [
    'type' => 'image',
    'settings' => 'bread_c_image',
    'label' => esc_html__('Header Background Image', 'direo'),
    'section' => 'breadcrumb',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 'header_bg_color',
    'label' => esc_html__('Header Background Color', 'direo'),
    'description' => esc_html__('Remove "Header Background Image" for using this field.', 'direo'),
    'section' => 'breadcrumb',
    'default' => '#232529',
    'output' => array(
        array(
            'element' => '#header-breadcrumb',
            'property' => 'background-color'
        ),
    ),
    'transport' => 'auto',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'slider',
    'settings' => 'bread_c_opacity',
    'label' => esc_html__('Header Background Opacity', 'direo'),
    'section' => 'breadcrumb',
    'default' => 8,
    'choices' => [
        'min' => 1,
        'max' => 9,
        'step' => 1,
    ],
]);

Kirki::add_field('direo_customizer', [
    'type' => 'text',
    'settings' => 'add_listing_btn',
    'label' => esc_html__('Add Listing Button Text', 'direo'),
    'section' => 'breadcrumb',
    'default' => 'Add Listing',
]);
Kirki::add_field('direo_customizer', [
    'type' => 'link',
    'settings' => 'add_btn_url',
    'label' => esc_html__('Add Listing Button Link', 'direo'),
    'description' => esc_html__('Leave it empty for default', 'direo'),
    'section' => 'breadcrumb',
]);
Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'quick_log_reg',
    'section' => 'breadcrumb',
    'label' => esc_html__('Show Quick Login/Reg Button?', 'direo'),
    'choices' => [
        'on' => esc_html__('Show', 'direo'),
        'off' => esc_html__('Hide', 'direo'),
    ],
    'default' => 'on',
]);
Kirki::add_field('direo_customizer', [
    'type' => 'text',
    'settings' => 'login_btn',
    'label' => esc_html__('Login Button Text', 'direo'),
    'section' => 'breadcrumb',
    'default' => 'Login',
    'active_callback' => [
        [
            'setting' => 'quick_log_reg',
            'operator' => '==',
            'value' => true,
        ]
    ],
]);
Kirki::add_field('direo_customizer', [
    'type' => 'link',
    'settings' => 'login_btn_url',
    'label' => esc_html__('Login Button Url', 'direo'),
    'description' => esc_html__('Leave it empty for default', 'direo'),
    'section' => 'breadcrumb',
    'active_callback' => [
        [
            'setting' => 'quick_log_reg',
            'operator' => '==',
            'value' => true,
        ]
    ],
]);
Kirki::add_field('direo_customizer', [
    'type' => 'text',
    'settings' => 'register_btn',
    'label' => esc_html__('Register Button Text', 'direo'),
    'section' => 'breadcrumb',
    'default' => 'Register',
    'active_callback' => [
        [
            'setting' => 'quick_log_reg',
            'operator' => '==',
            'value' => true,
        ]
    ],
]);
Kirki::add_field('direo_customizer', [
    'type' => 'link',
    'settings' => 'register_btn_url',
    'label' => esc_html__('Register Button Url', 'direo'),
    'description' => esc_html__('Leave it empty for default', 'direo'),
    'section' => 'breadcrumb',
    'active_callback' => [
        [
            'setting' => 'quick_log_reg',
            'operator' => '==',
            'value' => true,
        ]
    ],
]);

/*
 * Color Options Panel
 */
Kirki::add_panel('theme_colors', array(
    'priority' => '39',
    'title' => esc_html__('Color Options', 'direo'),
    'description' => esc_html__('Control your site color.', 'direo'),
));

/*
 * Theme Colors
 */
Kirki::add_section('theme_color', array(
    'panel' => 'theme_colors',
    'title' => esc_html__('Theme Color', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 'p_color',
    'label' => esc_html__('Primary Color', 'direo'),
    'section' => 'theme_color',
    'default' => '#f5548e',
]);
Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 's_color',
    'label' => esc_html__('Secondary Color', 'direo'),
    'section' => 'theme_color',
    'default' => '#903af9',
]);
Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 'p_g_color',
    'label' => esc_html__('Primary Gradient Color', 'direo'),
    'section' => 'theme_color',
    'default' => 'rgba(245, 84, 142, 0.85)',
    'choices' => [
        'alpha' => true,
    ],
]);
Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 's_g_color',
    'label' => esc_html__('Secondary Gradient Color', 'direo'),
    'section' => 'theme_color',
    'default' => 'rgba(144, 58, 249, 0.85))',
    'choices' => [
        'alpha' => true,
    ],
]);

/*
 * Additional Color
 */
Kirki::add_section('additional_theme_color', array(
    'panel' => 'theme_colors',
    'title' => esc_html__('Additional Color', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 'su_color',
    'label' => esc_html__('Success Color', 'direo'),
    'section' => 'additional_theme_color',
    'default' => '#32cc6f',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 'in_color',
    'label' => esc_html__('Info Color', 'direo'),
    'section' => 'additional_theme_color',
    'default' => '#3a7dfd',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 'dn_color',
    'label' => esc_html__('Danger Color', 'direo'),
    'section' => 'additional_theme_color',
    'default' => '#fd4868',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'color',
    'settings' => 'wr_color',
    'label' => esc_html__('Warning Color', 'direo'),
    'section' => 'additional_theme_color',
    'default' => '#fa8b0c',
]);

/*
 * Footer Options
 */
Kirki::add_section('footer_style', array(
    'priority' => '44',
    'title' => esc_html__('Copyright Area', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'editor',
    'settings' => 'copy_right',
    'label' => esc_html__('Copyright Text', 'direo'),
    'description' => esc_html__('Enter some description about copyright in your website', 'direo'),
    'section' => 'footer_style',
    'default' => esc_html__('&copy;2019 Direo. Made with <span class="la la-heart-o"></span> by <a href="#">AazzTech</a>', 'direo'),
    'partial_refresh' => [
        'copyright_text' => [
            'selector' => '#footer_text_color p',
            'render_callback' => function () {
                return get_theme_mod('copy_right');
            },
        ],
    ],
]);
/*
 * 404 Options
 */
Kirki::add_section('title', array(
    'priority' => '40',
    'title' => esc_html__('404 Page', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'text',
    'settings' => '404_title',
    'label' => esc_html__('404 title', 'direo'),
    'section' => 'title',
    'default' => esc_html__('Oops! That page can’t be found.', 'direo')
]);

Kirki::add_field('direo_customizer', [
    'type' => 'editor',
    'settings' => '404_desc',
    'label' => esc_html__('404 Description', 'direo'),
    'section' => 'title',
]);


/*
 * Blog Options
 */
Kirki::add_panel('blog_info', array(
    'priority' => '41',
    'title' => esc_html__('Blog', 'direo'),
));

Kirki::add_section('blog_page', array(
    'panel' => 'blog_info',
    'title' => esc_html__('Blog', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'radio',
    'settings' => 'blog_style',
    'section' => 'blog_page',
    'label' => esc_html__('Blog Style', 'direo'),
    'default' => 'default',
    'choices' => [
        'default' => esc_html__('Default', 'direo'),
        'grid' => esc_html__('Grid View', 'direo'),
    ],
]);

Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'blog_meta',
    'section' => 'blog_page',
    'label' => esc_html__('Show Post Meta?', 'direo'),
    'choices' => [
        'on' => esc_html__('Show', 'direo'),
        'off' => esc_html__('Hide', 'direo'),
    ],
    'default' => 'on',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'blog_fullwidth',
    'section' => 'blog_page',
    'label' => esc_html__('Use Full Width Page?', 'direo'),
    'choices' => [
        'on' => esc_html__('Enable', 'direo'),
        'off' => esc_html__('Disable', 'direo'),
    ],
    'default' => 'off',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'text',
    'settings' => 'blogs_page_title',
    'section' => 'blog_page',
    'label' => esc_html__('Blogs Page Title', 'direo'),
    'default' => esc_html__('Latest Blogs', 'direo')
]);


/*
 * About Options
 */
Kirki::add_section('about_page', array(
    'priority' => '42',
    'title' => esc_html__('About Page', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'text',
    'settings' => 'about_title',
    'section' => 'about_page',
    'label' => esc_html__('Header Title', 'direo'),
    'default' => esc_html__('Place your Business or Explore Anything what you want', 'direo')
]);

Kirki::add_field('direo_customizer', [
    'type' => 'text',
    'settings' => 'video',
    'section' => 'about_page',
    'label' => esc_html__('Popup Video Url', 'direo'),
    'description' => esc_html__('Insert youtube or vimeo video url for about page header.', 'direo'),
]);

Kirki::add_field('direo_customizer', [
    'type' => 'text',
    'settings' => 'btn',
    'section' => 'about_page',
    'label' => esc_html__('Video Button Text', 'direo'),
    'default' => esc_html__('Play our video', 'direo'),
]);

/*
 * Single Blog
 */
Kirki::add_section('blog', array(
    'panel' => 'blog_info',
    'title' => esc_html__('Single Blog', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'post_meta',
    'section' => 'blog',
    'label' => esc_html__('Show Post Meta?', 'direo'),
    'choices' => [
        'on' => esc_html__('Show', 'direo'),
        'off' => esc_html__('Hide', 'direo'),
    ],
    'default' => 'on',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'post_author',
    'section' => 'blog',
    'label' => esc_html__('Show Author Info Box?', 'direo'),
    'choices' => [
        'on' => esc_html__('Show', 'direo'),
        'off' => esc_html__('Hide', 'direo'),
    ],
    'default' => 'on',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'post_share',
    'section' => 'blog',
    'label' => esc_html__('Show Post Share?', 'direo'),
    'choices' => [
        'on' => esc_html__('Show', 'direo'),
        'off' => esc_html__('Hide', 'direo'),
    ],
    'default' => 'on',
]);
Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'post_pagination',
    'section' => 'blog',
    'label' => esc_html__('Show Post Pagination', 'direo'),
    'choices' => [
        'on' => esc_html__('Show', 'direo'),
        'off' => esc_html__('Hide', 'direo'),
    ],
    'default' => 'on',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'post_related',
    'section' => 'blog',
    'label' => esc_html__('Show Related Posts?', 'direo'),
    'choices' => [
        'on' => esc_html__('Show', 'direo'),
        'off' => esc_html__('Hide', 'direo'),
    ],
    'default' => 'on',
]);

Kirki::add_field('direo_customizer', [
    'type' => 'switch',
    'settings' => 'single_fullwidth',
    'section' => 'blog',
    'label' => esc_html__('Use Full Width Page?', 'direo'),
    'choices' => [
        'on' => esc_html__('Enable', 'direo'),
        'off' => esc_html__('Disable', 'direo'),
    ],
    'default' => 'off',
]);

/*
 * Sidebar
 */
Kirki::add_section('sidebar_alignment', array(
    'panel' => 'blog_info',
    'title' => esc_html__('Sidebar', 'direo'),
));

Kirki::add_field('direo_customizer', [
    'type' => 'radio',
    'settings' => 'sidebar',
    'section' => 'sidebar_alignment',
    'label' => esc_html__('Sidebar Alignment', 'direo'),
    'default' => 'right',
    'choices' => [
        'left' => esc_html__('Left Sidebar', 'direo'),
        'right' => esc_html__('Right Sidebar', 'direo'),
    ],
]);