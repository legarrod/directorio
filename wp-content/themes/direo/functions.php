<?php

set_time_limit(120);

require_once('inc/customizer.php');
require_once('inc/comment_form.php');
require_once('inc/direo-helper.php');
require_once('lib/tgm/plugin_ac.php');

function direo_setup()
{
    load_theme_textdomain('direo', get_theme_file_path('/languages'));
    add_image_size('direo_blog', 730, 413, true);
    add_image_size('direo_blog_grid', 350, 224, true);
    add_image_size('direo_related_blog', 223, 136, true);

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('editor-styles');
    add_editor_style('style-editor.css');

    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'direo'),
    ));

    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    add_theme_support('custom-background', apply_filters('direo_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));

    add_theme_support('customize-selective-refresh-widgets');

    add_theme_support('custom-logo', array(
        'height' => 40,
        'width' => 140,
        'flex-width' => true,
        'flex-height' => true,
    ));

    // Editor Color Palette
    add_theme_support('editor-color-palette', array(
        array(
            'name' => __('Primary', 'direo'),
            'slug' => 'primary',
            'color' => '#f5548e',
        ),
        array(
            'name' => __('Secondary', 'direo'),
            'slug' => 'title',
            'color' => '#903af9',
        ),
        array(
            'name' => __('Heading', 'direo'),
            'slug' => 'subtitle',
            'color' => '#272b41',
        ),
        array(
            'name' => __('Text', 'direo'),
            'slug' => 'text',
            'color' => '#7a82a6',
        ),
    ));

}

add_action('after_setup_theme', 'direo_setup');

function direo_content_width()
{
    $GLOBALS['content_width'] = apply_filters('direo_content_width', 640);
}

add_action('after_setup_theme', 'direo_content_width', 0);


/**
 * Register widget area.
 */

if (!function_exists('direo_sidebar_register')) {
    function direo_sidebar_register()
    {
        register_sidebar(array(
            'name' => esc_html__('All Listing Widgets ', 'direo'),
            'id' => 'all_listing',
            'description' => esc_html__('It will display on the left side of the All Listing element.', 'direo'),
            'before_widget' => '<div class="widget atbd_widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-header atbd_widget_title"><h6 class="widget-title">',
            'after_title' => '</h6></div>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Shop Page Widgets', 'direo'),
            'id' => 'shop_sidebar',
            'description' => esc_html__('Appears in the shop page sidebar.', 'direo'),
            'before_widget' => '<div class="widget widget-wrapper %2$s"><div class="widget-default">',
            'after_widget' => '</div></div>',
            'before_title' => '<div class="widget-header"><h6 class="widget-title">',
            'after_title' => '</h6> </div>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Blog Widgets', 'direo'),
            'id' => 'blog_sidebar',
            'description' => esc_html__('Appears in the blog page sidebar.', 'direo'),
            'before_widget' => '<div class="widget widget-wrapper %2$s"><div class="widget-default">',
            'after_widget' => '</div></div>',
            'before_title' => '<div class="widget-header"><h6 class="widget-title">',
            'after_title' => '</h6> </div>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Page Widgets', 'direo'),
            'id' => 'page_sidebar',
            'description' => esc_html__('Appears in the page sidebar.', 'direo'),
            'before_widget' => '<div class="widget widget-wrapper %2$s"><div class="widget-default">',
            'after_widget' => '</div></div>',
            'before_title' => '<div class="widget-header"><h6 class="widget-title">',
            'after_title' => '</h6></div>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer Widgets 1', 'direo'),
            'id' => 'footer_sidebar_1',
            'description' => esc_html__('Appears in footer section. Every widget in own column.', 'direo'),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer Widgets 2', 'direo'),
            'id' => 'footer_sidebar_2',
            'description' => esc_html__('Appears in footer section. Every widget in own column.', 'direo'),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer Widgets 3', 'direo'),
            'id' => 'footer_sidebar_3',
            'description' => esc_html__('Appears in footer section. Every widget in own column.', 'direo'),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer Widgets 4', 'direo'),
            'id' => 'footer_sidebar_4',
            'description' => esc_html__('Appears in footer section. Every widget in own column.', 'direo'),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
    }

    add_action('widgets_init', 'direo_sidebar_register');
}

/*=====================================================
Register custom fonts.
=======================================================*/

function direo_fonts_url()
{
    $fonts_url = '';
    $fonts = array();
    $subsets = 'arabic';

    if ('off' !== _x('on', 'Muli font: on or off', 'direo')) {
        $fonts[] = 'Muli:400,400i,600,700';
    }
    if ($fonts) {
        $fonts_url = add_query_arg(array(
            'family' => implode('|', $fonts),
            'subset' => $subsets,
        ), 'https://fonts.googleapis.com/css');
    }
    return esc_url_raw($fonts_url);
}

/**
 * Enqueue scripts and styles.
 */

function direo_scripts()
{
    wp_enqueue_style('direo-fonts', direo_fonts_url(), array(), null);

    if (is_rtl()) {
        wp_enqueue_style('bootstrap-rtl', get_theme_file_uri('vendor_assets/css/bootstrap/bootstrap-rtl.css'), array(), null);
    } else {
        wp_enqueue_style('bootstrap', get_theme_file_uri('vendor_assets/css/bootstrap/bootstrap.css'), array(), null);
    }
    wp_enqueue_style('fontawesome', get_theme_file_uri('vendor_assets/css/fontawesome.min.css'), array(), null);
    wp_enqueue_style('line-awesome', get_theme_file_uri('vendor_assets/css/line-awesome.css'), array(), null);
    wp_enqueue_style('mCustomScrollbar', get_theme_file_uri('vendor_assets/css/jquery.mCustomScrollbar.css'), array(), null);
    wp_enqueue_style('owl-carousel', get_theme_file_uri('vendor_assets/css/owl.carousel.min.css'), array(), null);
    wp_enqueue_style('magnific-popup', get_theme_file_uri('vendor_assets/css/magnific-popup.css'), array(), null);
    wp_enqueue_style('select2', get_theme_file_uri('vendor_assets/css/select2.min.css'), array(), null);
    wp_enqueue_style('slick', get_theme_file_uri('vendor_assets/css/slick.css'), array(), null);
    wp_enqueue_style('direo-style', get_stylesheet_uri());
    wp_style_add_data('direo-style', 'rtl', 'replace');

    wp_enqueue_script('popper', get_theme_file_uri('vendor_assets/js/bootstrap/popper.js'), array('jquery'), null, false);
    wp_enqueue_script('bootstrap', get_theme_file_uri('vendor_assets/js/bootstrap/bootstrap.min.js'), array('jquery', 'popper'), null, false);
    wp_enqueue_script('jquery-ui-core', array('jquery'));
    wp_enqueue_script('waypoints', get_theme_file_uri('vendor_assets/js/jquery.waypoints.min.js'), array('jquery'), null, true);
    wp_enqueue_script('counterup', get_theme_file_uri('vendor_assets/js/jquery.counterup.min.js'), array('jquery'), null, true);
    wp_enqueue_script('magnific-popup', get_theme_file_uri('vendor_assets/js/jquery.magnific-popup.min.js'), array('jquery'), null, true);
    wp_enqueue_script('mCustomScrollbar', get_theme_file_uri('vendor_assets/js/jquery.mCustomScrollbar.concat.min.js'), array('jquery'), null, true);
    wp_enqueue_script('carousel', get_theme_file_uri('vendor_assets/js/owl.carousel.min.js'), array('jquery'), null, true);
    wp_enqueue_script('select2', get_theme_file_uri('vendor_assets/js/select2.full.min.js'), array('jquery'), null, true);
    wp_enqueue_script('direo-main', get_theme_file_uri('theme_assets/js/main.js'), array('jquery'), null, true);
    wp_enqueue_script('direo-atmodal', get_theme_file_uri('vendor_assets/js/atmodal.js'), array('jquery'), null, true);

    if (class_exists('Directorist_Base')) {
        $i18n_text = array(
            'confirmation_text' => esc_html__('Are you sure', 'direo'),
            'ask_conf_sl_lnk_del_txt' => esc_html__('Do you really want to remove this!', 'direo'),
            'confirm_delete' => esc_html__('Yes, Delete it!', 'direo'),
            'deleted' => esc_html__('Deleted!', 'direo'),
            'error' => esc_html__('Error!', 'direo'),
            'error_details' => esc_html__('Oops! Something Went Wrong.', 'direo'),
            'cancel' => esc_html__('Cancel', 'direo'),
            'added_favourite' => esc_html__('Added to Favourite', 'direo'),
            'please_login' => esc_html__('Please Login First', 'direo'),
        );
        $data = array(
            'nonce' => wp_create_nonce('atbdp_nonce_action_js'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonceName' => 'atbdp_nonce_js',
            'i18n_text' => $i18n_text,
            'uploadTitle' => esc_html__('Upload Profile Picture', 'direo'),
            'uploadBTN' => esc_html__('Upload', 'direo'),
            'public_class_path' => ATBDP_PUBLIC_ASSETS,
            'rtl' => is_rtl() ? 'true' : 'false',
            'requirements_label' => get_directorist_option('requirements_label', __('Requirements', 'direo')),
        );

        wp_localize_script('direo-main', 'direo_localize_data', $data);

        wp_localize_script('direo-search-listing', 'atbdp_search', array(
            'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'added_favourite' => esc_html__('Added to favorite', 'direo'),
            'please_login' => esc_html__('Please login first', 'direo')
        ));

        $select_listing_map = get_directorist_option('select_listing_map', 'google');
        wp_localize_script('atbdp-geolocation', 'adbdp_geolocation', array('select_listing_map' => $select_listing_map));
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_localize_script('direo-main', 'direo_rtl', array(
        'rtl' => is_rtl() ? 'true' : 'false',
    ));

}

add_action('wp_enqueue_scripts', 'direo_scripts');

/*=====================================================
 Admin Enqueue scripts and styles.
=================================================*/
function direo_admin_css_js()
{
    wp_enqueue_style('direo-admin-css', get_theme_file_uri('theme_assets/admin.css'), array(), null);
    wp_enqueue_script('direo-listing-image', get_theme_file_uri('theme_assets/listing-image.js'), array('jquery'), null, false);

}

add_action('admin_enqueue_scripts', 'direo_admin_css_js');

/*=====================================================
 Stopped auto page creation
=================================================*/
function direo_create_required_pages()
{
    return false;
}

add_filter('atbdp_create_required_pages', 'direo_create_required_pages');

/*=====================================================
    Checked listing with map view element
=================================================*/
function changed_header_footer()
{
    $checked_id_one = preg_match('/(listing-listings_with_map)/', get_post_field('post_content', get_the_ID()));
    $checked_one = ($checked_id_one == 1) ? true : false;
    $FileName = basename(get_page_template());
    $slug = $_SERVER["REQUEST_URI"];

    if (is_404() || is_search()) {
        return false;
    } elseif ($FileName == 'dashboard.php') {
        return true;
    } elseif ($checked_one || ($slug == '/map-view-i/') || ($slug == '/map-view-ii/')) {
        return true;
    } else {
        return false;
    }
}

function image_opacity()
{
    $opacity = get_theme_mod('bread_c_opacity', '8');
    if ($opacity != 0) { ?>
        <style>
            .overlay.overlay--dark:before {
                background: rgba(47, 38, 57, 0.<?php echo esc_attr($opacity);?>);
            }
        </style>
        <?php
    }
}

add_action('wp_head', 'image_opacity');
