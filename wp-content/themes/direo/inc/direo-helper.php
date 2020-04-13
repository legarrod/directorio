<?php

/*=====================================================
 Direo All Additional Function
=================================================*/

/*=====================================================
    Listing Post category
=================================================*/

if (!function_exists('direo_listing_category')) {
    function direo_listing_category()
    {
        $categories = get_terms('at_biz_dir-category');
        $cat = array();
        if ($categories) {
            foreach ($categories as $category) {
                $cat[$category->slug] = $category->name;
            }
        }
        return $cat;
    }
}

/* ==============  Listing Post Tag ============ */

if (!function_exists('direo_listing_tags')) {
    function direo_listing_tags()
    {
        $tags = get_terms('at_biz_dir-tags');
        $tag = array();
        if ($tags) {
            foreach ($tags as $s_tag) {
                $tag[$s_tag->slug] = $s_tag->name;
            }
        }
        return $tag;
    }
}

/* ==============Listing Post Locations============ */

if (!function_exists('direo_listing_locations')) {
    function direo_listing_locations()
    {
        $locations = get_terms('at_biz_dir-location');
        $loc = array();
        if (!empty($locations)) {
            foreach ($locations as $s_loc) {
                $loc[$s_loc->slug] = $s_loc->name;
            }
        }
        return $loc;
    }
}


/*=====================================================
    Pagination For Blog
=================================================*/
if (!function_exists('direo_pagination')) {

    function direo_pagination($wp_query = null)
    {

        if (!$wp_query) {
            $wp_query = $GLOBALS['wp_query'];
        }

        // Don't print empty markup if there's only one page.

        if ($wp_query->max_num_pages < 2) {
            return;
        }

        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $query_args = array();
        $url_parts = explode('?', $pagenum_link);

        if (isset($url_parts[1])) {
            wp_parse_str($url_parts[1], $query_args);
        }

        $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
        $pagenum_link = trailingslashit($pagenum_link) . '%_%';

        $format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';


        $links = paginate_links(array(
            'base' => $pagenum_link,
            'format' => $format,
            'total' => $wp_query->max_num_pages,
            'current' => $paged,
            'mid_size' => 3,
            'add_args' => array_map('urlencode', $query_args),
            'prev_text' => '<span class="la la-long-arrow-left"></span>',
            'next_text' => '<span class="la la-long-arrow-right"></span>',
        ));

        echo sprintf('<div class="m-top-50"><nav class="navigation pagination d-flex justify-content-center" role="navigation"><div class="nav-links">%s</div></nav></div>', wp_kses_post($links));

    }
}

/*=====================================================
    Site Logo And Title
=================================================*/
function direo_site_identity()
{
    $logo_id = get_theme_mod('custom_logo');
    $logo_id2 = get_theme_mod('footer_logo');
    if ('menu2' == direo_menu_style()) {
        $logo = $logo_id2 ? $logo_id2 : wp_get_attachment_image_src($logo_id, 'full')[0];
    } else {
        $logo = $logo_id ? wp_get_attachment_image_src($logo_id, 'full')[0] : $logo_id2;
    }

    if ($logo_id || $logo_id2) {
        echo sprintf('<div class="logo-wrapper order-lg-0 order-sm-1"><div class="logo logo-top">
                                    <a class="navbar-brand order-sm-1 order-1" href="%s"> <img src="%s" alt="%s"> </a>
                                </div> </div>', home_url('/'), esc_url($logo), direo_get_image_alt($logo_id));
    } elseif (get_bloginfo('name')) {
        echo sprintf('<div class="logo-wrapper order-lg-0 order-sm-1 site_title_tag"><div class="logo logo-top">
                                    <h1 class="m-0"><a id="site_title_color" href="%s">%s</a></h1>', home_url('/'), get_bloginfo('name'));
        echo sprintf('<p id="site_tagline_color" class="m-0">%s</p></div> </div>', get_bloginfo('description'));
    }
}

/*=====================================================
    Meta Info For Blog
=================================================*/

if (!function_exists('direo_blog_meta_info')) {
    function direo_blog_meta_info()
    {

        $blog_style = get_theme_mod('blog_style', 'default'); ?>
        <ul class="post-meta list-unstyled">
            <li><?php echo direo_time_link(); ?></li>

            <?php
            echo 'default' == $blog_style ? sprintf('<li>%s <a href="%s">%s</a></li>', esc_html__('by', 'direo'), get_author_posts_url(get_the_author_meta('ID')), get_the_author_meta('display_name')) : '';

            if (function_exists('direo_post_cats')) {
                direo_post_cats();
            }

            if ('default' == $blog_style) {
                if (!post_password_required() && (comments_open() || get_comments_number())) {
                    echo '<li>';
                    comments_popup_link(esc_html__('No comments yet', 'direo'), esc_html__('1 comment', 'direo'), esc_html__('% comments', 'direo'), 'comments-link', esc_html__('Comments are off', 'direo'));
                    echo '</li>';
                }
            } ?>

        </ul>
        <?php
    }
}

/*=====================================================
    Contact Form Title And Id
=================================================*/

if (!function_exists('mp_get_cf7_names')) {
    function mp_get_cf7_names()
    {

        global $wpdb;
        $cf7_list = $wpdb->get_results("SELECT ID, post_title
				FROM $wpdb->posts
				WHERE post_type = 'wpcf7_contact_form'");
        $cf7_val = array();
        if ($cf7_list) {
            $cf7_val[0] = esc_html__('Select a Contact Form', 'direo');
            foreach ($cf7_list as $value) {
                $cf7_val[$value->ID] = $value->post_title;
            }
        } else {
            $cf7_val[0] = esc_html__('No contact forms found', 'direo');
        }
        return $cf7_val;
    }
}

/*=====================================================
    Remove Contact Form 7 Auto <p> Tag
=================================================*/
add_filter('wpcf7_autop_or_not', '__return_false');

/*=====================================================
   Listing Reviews
=================================================*/
function direo_listing_review()
{
    $enable_review = get_directorist_option('enable_review', 1);
    if (!$enable_review) return;
    global $post;
    $average = ATBDP()->review->get_average($post->ID);
    echo sprintf('<span class="atbd_meta atbd_listing_rating">%s<i class="fa fa-star"></i></span>', wp_kses_post($average));
}


/*=====================================================
   Social Shares Buttons
=================================================*/
if (!function_exists('direo_social_sharing_buttons')) {
    function direo_social_sharing_buttons($name)
    {
        global $post;
        if (is_singular('at_biz_dir')) {
            $listingURL = urlencode(get_permalink());
            $listingTitle = str_replace(' ', '%20', get_the_title());

            $facebookURL = "https://www.facebook.com/share.php?u={$listingURL}&title={$listingTitle}";
            $twitterURL = "http://twitter.com/share?url={$listingURL}";
            $linkedin = "http://www.linkedin.com/shareArticle?mini=true&url={$listingURL}&title={$listingTitle}";
            if ($name == 'facebook') {
                return esc_url($facebookURL);
            }
            if ($name == 'twitter') {
                return esc_url($twitterURL);
            }
            if ($name == 'linkedin') {
                return esc_url($linkedin);
            }
        }
    }
}


/*=====================================================
   Social Shares Buttons Congifaretaion
=================================================*/

if (!function_exists('direo_sharing')) {
    function direo_sharing()
    { ?>
        <span class="dropdown-toggle" id="social-links" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false" role="menu">
            <span class="la la-share"></span>
            <?PHP esc_html_e('Share', 'direo'); ?>
        </span>

        <div class="atbd_director_social_wrap dropdown-menu" aria-labelledby="social-links">
            <ul class="list-unstyled">
                <li>
                    <a href="<?php echo direo_social_sharing_buttons('facebook'); ?>" target="_blank">
                        <span class="fab fa-facebook color-facebook"></span><?php esc_html_e('Facebook', 'direo'); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo direo_social_sharing_buttons('twitter'); ?>" target="_blank">
                        <!-- twitter icon by Icons8 -->
                        <span class="fab fa-twitter color-twitter"></span><?php esc_html_e('Twitter', 'direo'); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo direo_social_sharing_buttons('linkedin'); ?>" target="_blank">
                        <!-- linkedin icon by Icons8 -->
                        <span class="fab fa-linkedin color-linkedin"></span><?php esc_html_e('LinkedIn', 'direo'); ?>
                    </a>
                </li>
            </ul>
            <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
        </div>
        <?php
    }
}


/*=====================================================
     Convert col decimal format to class
    Replace for King Composer plugin class
=================================================*/

if (!function_exists('direo_column_width_class')) {
    function direo_column_width_class($width)
    {
        if (empty($width))
            return 'col-md-12 col-sm-12';

        if (strpos($width, '%') !== false) {
            $width = (float)$width;
            if ($width < 12) {
                return 'col-md-1 col-sm-6 col-xs-12';
            } else if ($width < 18) {
                return 'col-md-2 col-sm-6 col-xs-12';
            } else if ($width < 22.5) {
                return 'kc_col-of-5 float-none';
            } else if ($width < 29.5) {
                return 'col-md-3 col-sm-6 col-xs-12';
            } else if ($width < 37) {
                return 'col-md-4 col-sm-12';
            } else if ($width < 46) {
                return 'col-md-5 col-sm-12';
            } else if ($width < 54.5) {
                return 'col-md-6 col-sm-12';
            } else if ($width < 63) {
                return 'col-md-7 col-sm-12';
            } else if ($width < 71.5) {
                return 'col-md-8 col-sm-12';
            } else if ($width < 79.5) {
                return 'col-md-9 col-sm-12';
            } else if ($width < 87.5) {
                return 'col-md-10 col-sm-12';
            } else if ($width < 95.5) {
                return 'col-md-11 col-sm-12';
            } else {
                return 'col-md-12 col-sm-12';
            }
        }

        $matches = explode('/', $width);
        $width_class = '';
        $n = 12;
        $m = 12;

        if (isset($matches[0]) && !empty($matches[0]))
            $n = $matches[0];
        if (isset($matches[1]) && !empty($matches[1]))
            $m = $matches[1];

        if ($n == 2.4) {
            $width_class = 'kc_col-of-5';
        } else {
            if ($n > 0 && $m > 0) {
                $value = ceil(($n / $m) * 12);
                if ($value > 0 && $value <= 12) {
                    $width_class = 'col-md-' . $value;
                }
            }
        }

        return $width_class;
    }
}

/*=====================================================
     direo configuration
=================================================*/
if (!function_exists('direo_remove_kc_element')) {
    function direo_remove_kc_element()
    {

        /*=====================================================
            auto loader
        =================================================*/

        $modules_path = get_template_directory() . '/module';

        foreach (glob($modules_path . "/*.php") as $module) {
            load_template($module, true);
        }

        /*=====================================================
            Add custom icon pack [Line-awesome].
        =================================================*/

        if (function_exists('kc_add_icon')) {
            kc_add_icon(get_template_directory_uri() . '/vendor_assets/css/line-awesome.min.css');
        }

        /*=====================================================
           Removing direo default element
        =================================================*/

        if (function_exists('kc_remove_map')) {
            kc_remove_map('kc_accordion');
            kc_remove_map('kc_button');
            kc_remove_map('kc_call_to_action');
            kc_remove_map('kc_blog_posts');
            kc_remove_map('kc_carousel_post');
            kc_remove_map('kc_testimonial');
            kc_remove_map('kc_title');
            kc_remove_map('kc_contact_form7');
            kc_remove_map('kc_spacing');
            kc_remove_map('kc_icon');
            kc_remove_map('kc_counter_box');
            kc_remove_map('kc_divider');
            kc_remove_map('kc_column_text');
            kc_remove_map('kc_image_gallery');
            kc_remove_map('kc_flip_box');
            kc_remove_map('kc_google_maps');
            kc_remove_map('kc_pricing');
            kc_remove_map('kc_box');
            kc_remove_map('kc_progress_bars');
            kc_remove_map('kc_video_play');
            kc_remove_map('kc_pie_chart');
            kc_remove_map('kc_twitter_feed');
            kc_remove_map('kc_instagram_feed');
            kc_remove_map('kc_fb_recent_post');
            kc_remove_map('kc_team');
            kc_remove_map('kc_carousel_images');
            kc_remove_map('kc_post_type_list');
            kc_remove_map('kc_coundown_timer');
            kc_remove_map('kc_box_alert');
            kc_remove_map('kc_feature_box');
            kc_remove_map('kc_dropcaps');
            kc_remove_map('kc_image_fadein');
            kc_remove_map('kc_creative_button');
            kc_remove_map('kc_tooltip');
            kc_remove_map('kc_multi_icons');
            kc_remove_map('kc_nested');
            kc_remove_map('kc_image_hover_effects');
            kc_remove_map('kc_tabs');
        }
    }

    add_action('init', 'direo_remove_kc_element', 999);
}

/*=====================================================================
   Woocommerce Ajaxify cart
=======================================================================*/

if (!function_exists('direo_tiny_cart')) {
    function direo_tiny_cart()
    {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        ob_start(); ?>
        <div class="nav_right_module cart_module">
            <div class="cart__icon">
                <span class="la la-shopping-cart"></span>
                <span class="cart_count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
            </div>
            <div class="cart__items shadow-lg-2">
                <?php
                if (!empty(WC()->cart->get_cart_contents_count())) {
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key); ?>

                            <div class="items">
                                <div class="item_thumb">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(array(42, 48)), $cart_item, $cart_item_key);
                                    ?>
                                </div>
                                <div class="item_info">
                                    <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), esc_attr($_product->get_name())), $cart_item, $cart_item_key)); ?>
                                    <span class="color-primary">
                                        <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                                    </span>
                                </div>
                                <?php echo apply_filters('woocommerce_cart_item_remove_link item_remove', sprintf(
                                    '<a href="%s" class="item_remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><span class="la la-close"></span></a>',
                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                    esc_html__('Remove this item', 'direo'),
                                    esc_attr($product_id),
                                    esc_attr($cart_item_key),
                                    esc_attr($_product->get_sku())
                                ), $cart_item_key); ?>

                            </div>
                            <?php
                        }
                    }
                    wp_reset_postdata(); ?>

                    <div class="cart_info text-md-right">
                        <p><?php esc_html_e('Subtotal: ', 'direo') ?>
                            <span class="color-primary"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                        </p>
                        <?php do_action('woocommerce_widget_shopping_cart_buttons'); ?>
                    </div>

                <?php } else { ?>
                    <div class="cart_info text-md-right">
                        <p class="text-center">
                            <b><?php esc_html_e('No products in the cart.', 'direo') ?></b>
                        </p>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}


if (!function_exists('direo_tiny_cart_filter')) {
    function direo_tiny_cart_filter($fragments)
    {
        $fragments['.nav_right_module.cart_module'] = direo_tiny_cart();
        return $fragments;
    }

    add_filter('woocommerce_add_to_cart_fragments', 'direo_tiny_cart_filter');
}


function direo_woo_shopping_cart()
{
    if (class_exists('woocommerce')) {
        if (is_shop() || is_product() || is_cart() || is_checkout() || is_product_taxonomy() || is_account_page()) {
            return true;
        }
    }
}


/*=====================================================================
   wordpress default year
=======================================================================*/

if (!function_exists('direo_time_link')) {

    function direo_time_link()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        $time_string = sprintf($time_string,
            get_the_date(DATE_W3C),
            get_the_date(),
            get_the_modified_date(DATE_W3C),
            get_the_modified_date()
        );
        return sprintf('<a href="%s" rel="bookmark">%s</a>', esc_url(get_permalink()), $time_string);

    }
};


/*=====================================================================
   Direo all image alt text
=======================================================================*/

if (!function_exists('direo_get_image_alt')) {
    function direo_get_image_alt($id = NULL)
    {
        if (is_object($id) || is_array($id)) :

            if (isset($id['attachment_id'])) :
                $post = get_post($id['attachment_id']);
                if (is_object($post)) :
                    if ($post->post_excerpt) :
                        return esc_attr($post->post_excerpt);
                    else :
                        return esc_attr($post->post_title);
                    endif;
                endif;
            else :
                return false;
            endif;

        elseif ($id > 0) :

            $post = get_post($id);
            if (is_object($post)) :
                if ($post->post_excerpt) :
                    return esc_attr($post->post_excerpt);
                else :
                    return esc_attr($post->post_title);
                endif;
            endif;

        endif;

    }
}


/*=====================================================
 Login and Register Button
=================================================*/
if (!function_exists('direo_ajax_login_init')) {
    function direo_ajax_login_init()
    {

        wp_enqueue_script('ajax-login-script', get_theme_file_uri('theme_assets/js/ajax-login-register-script.js'), 'jquery', null, true);

        wp_localize_script('ajax-login-script', 'direo_ajax_login_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'redirecturl' => (class_exists('Directorist_Base')) ? ATBDP_Permalink::get_dashboard_page_link() : home_url('/'),
            'loadingmessage' => esc_html__('Sending user info, please wait...', 'direo'),
            'registration_confirmation' => esc_html__('Please check your email for your password!', 'direo'),
            'login_failed' => esc_html__('Sorry! Login failed.', 'direo'),
        ));

        add_action('wp_ajax_nopriv_direo_ajaxlogin', 'direo_ajax_login');
        add_action('wp_ajax_nopriv_direo_recovery_password', 'direo_recovery_password');
    }
}

if (function_exists('direo_ajax_login_init') && !is_user_logged_in()) {
    add_action('init', 'direo_ajax_login_init');
}

if (!function_exists('direo_recovery_password')) {
    function direo_recovery_password()
    {
        global $wpdb;
        $error = '';
        $success = '';
        $email = trim($_POST['user_login']);
        if (empty($email)) {
            $error = esc_html__('Enter a username or e-mail address..', 'direo');
        } else if (!is_email($email)) {
            $error = esc_html__('Invalid username or e-mail address.', 'direo');
        } else if (!email_exists($email)) {
            $error = esc_html__('There is no user registered with that email address.', 'direo');
        } else {
            $random_password = wp_generate_password(12, false);
            $user = get_user_by('email', $email);


            $update_user = wp_update_user(array(
                    'ID' => $user->ID,
                    'user_pass' => $random_password
                )
            );

            // if  update user return true then lets send user an email containing the new password
            if ($update_user) {
                $subject = esc_html__('Your new password', 'direo');
                $message = esc_html__('Your new password is: ', 'direo') . $random_password;

                $headers[] = 'Content-Type: text/html; charset=UTF-8';

                $mail = wp_mail($email, $subject, $message, $headers);
                if ($mail) {
                    $success = esc_html__('Check your email address for your new password.', 'direo');
                } else {
                    $error = esc_html__('Password updated! But something went wrong sending email.', 'direo');
                }

            } else {
                $error = esc_html__('Oops something went wrong updaing your account.', 'direo');
            }

        }


        if (!empty($error)) {
            echo json_encode(array('loggedin' => false, 'message' => $error));
        }

        if (!empty($success)) {
            echo json_encode(array('loggedin' => true, 'message' => $success));
        }

        die();
    }
}

if (!function_exists('direo_ajax_login')) {
    function direo_ajax_login()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-login-nonce', 'security');

        $username = $_POST['username'];
        $user_password = $_POST['password'];
        $keep_signed_in = !empty($_POST['rememberme']) ? true : false;
        $user = wp_authenticate($username, $user_password);
        if (is_wp_error($user)) {
            echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.', 'direo')));
        } else {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, $keep_signed_in);
            echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...', 'direo')));
        }
        exit();
    }
}

/*=====================================================
 Direo Page header image
=================================================*/
if (!function_exists('direo_header_background')) {
    function direo_header_background()
    {
        $header_img_id = get_post_meta(get_the_ID(), 'second_featured_img', true);
        $header_img = wp_get_attachment_image_src($header_img_id, array(1920, 500));
        if (class_exists('woocommerce') && is_shop()) {
            $post_thumbnail = get_the_post_thumbnail_url(get_option('woocommerce_shop_page_id'));
        } else {
            $post_thumbnail = get_the_post_thumbnail_url();
        }

        $header_bg = get_theme_mod('bread_c_image', get_template_directory_uri() . '/img/breadcrumb1.jpg');
        $home_page = get_the_post_thumbnail(get_option('page_for_posts'));

        if (is_home() || is_archive()) {
            if (!empty($home_page)) {
                $section_bg = $home_page;
            } else {
                $section_bg = sprintf('<img src="%s" alt="%s"/>', esc_url($header_bg), direo_get_image_alt(get_post_thumbnail_id(get_the_ID())));
            }
        } else {
            if (!empty($post_thumbnail) && !is_single()) {
                $section_bg = sprintf('<img src="%s" alt="%s"/>', esc_url($post_thumbnail), direo_get_image_alt(get_post_thumbnail_id(get_the_ID())));
            } elseif (!empty($header_img)) {
                $section_bg = sprintf('<img src="%s" alt="%s">', esc_url($header_img[0]), direo_get_image_alt(get_post_thumbnail_id(get_the_ID())));
            } else {
                $section_bg = sprintf('<img src="%s" alt="%s"/>', esc_url($header_bg), direo_get_image_alt(get_post_thumbnail_id(get_the_ID())));
            }
        }
        echo sprintf('<div class="bg_image_holder">%s</div>', $section_bg);
    }
}

/*=====================================================
 Direo single listing header image
=================================================*/
if (!function_exists('direo_single_listing_header_background')) {
    function direo_single_listing_header_background()
    {
        $header_img_id = get_post_meta(get_the_ID(), 'second_featured_img', true);
        $header_img = wp_get_attachment_image_src($header_img_id, 'full');
        $image_id = get_post_meta(get_the_ID(), '_listing_prv_img', true);
        $preview_image = wp_get_attachment_image_src($image_id, 'full');
        $header_bg = get_theme_mod('bread_c_image', get_template_directory_uri() . '/img/breadcrumb1.jpg');

        if (!empty($header_img)) {
            $section_bg = sprintf('<img src="%s" alt="%s">', esc_url($header_img[0]), direo_get_image_alt(get_post_thumbnail_id(get_the_ID())));
        } elseif (!empty($image_id)) {
            $section_bg = sprintf('<img src="%s" alt="%s">', esc_url($preview_image[0]), direo_get_image_alt(get_post_thumbnail_id(get_the_ID())));
        } else {
            $section_bg = sprintf('<img src="%s" alt="%s">', esc_url($header_bg), direo_get_image_alt(get_post_thumbnail_id(get_the_ID())));
        }
        echo wp_kses_post($section_bg);
    }
}

function direo_menu_style()
{

    $dashboardFileName = basename(get_page_template());
    $style = get_post_meta(direo_page_id(), 'menu_style', true);

    if ((changed_header_footer() || ($dashboardFileName == 'dashboard.php')) && ($style == 'menu1' || empty($style))) {
        $style = 'menu2';
    } elseif (is_single() && ($style == 'menu1' || empty($style))) {
        $style = get_theme_mod('menu_style', 'menu1');
    }

    return $style;
}

/*=====================================================
 Direo menu area
=================================================*/

function direo_menu_area()
{

    $author = get_current_user_id();
    $author_id = get_user_meta($author, 'pro_pic', true);
    $author_img = wp_get_attachment_image_src($author_id);

    $add_listing_btn = get_theme_mod('add_listing_btn', 'Add Listing');
    $add_btn_url = get_theme_mod('add_btn_url');
    $quick_log_reg = get_theme_mod('quick_log_reg', true);
    $login = get_theme_mod('login_btn', 'Login');
    $login_url = get_theme_mod('login_btn_url', false);
    $register = get_theme_mod('register_btn', 'Register');
    $register_url = get_theme_mod('register_btn_url', false);
    $dashboardFileName = basename(get_page_template());

    if (empty(direo_menu_style()) || 'menu1' == direo_menu_style()) { ?>
        <div class="mainmenu-wrapper">
            <div class="menu-area menu1 menu--light">
                <div class="top-menu-area">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="menu-fullwidth">
                                    <?php direo_site_identity(); ?>
                                    <div class="menu-container order-lg-1 order-sm-0">
                                        <div class="d_menu">
                                            <nav class="navbar navbar-expand-lg mainmenu__menu">
                                                <button class="navbar-toggler" type="button" data-toggle="collapse"
                                                        data-target="#direo-navbar-collapse"
                                                        aria-controls="direo-navbar-collapse" aria-expanded="false"
                                                        aria-label="Toggle navigation">
                                                        <span class="navbar-toggler-icon icon-menu">
                                                            <i class="la la-reorder"></i>
                                                        </span>
                                                </button>
                                                <div class="collapse navbar-collapse" id="direo-navbar-collapse">
                                                    <?php
                                                    wp_nav_menu(
                                                        array(
                                                            'theme_location' => 'primary',
                                                            'container' => false,
                                                            'fallback_cb' => false,
                                                            'menu_class' => 'navbar-nav',
                                                            'depth' => 3
                                                        )
                                                    ); ?>
                                                </div>
                                            </nav>
                                        </div>
                                    </div>

                                    <div class="menu-right order-lg-2 order-sm-2">
                                        <?php if (class_exists('Directorist_Base')) { ?>
                                            <div class="author-area">
                                                <div class="author__access_area">
                                                    <ul class="d-flex list-unstyled align-items-center author_access_list">
                                                        <?php
                                                        if (!direo_woo_shopping_cart() && $add_listing_btn) { ?>
                                                            <li>
                                                                <a href="<?php echo !empty($add_btn_url) ? esc_url($add_btn_url) : esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
                                                                   class="btn btn-xs btn-gradient btn-gradient-two">
                                                                    <span class="la la-plus"></span>
                                                                    <?php esc_attr_e($add_listing_btn, 'direo'); ?>
                                                                </a>
                                                            </li>
                                                            <?php
                                                        }
                                                        if (!is_user_logged_in() && $quick_log_reg) { ?>
                                                            <li>
                                                                <?php
                                                                if ($login_url) {
                                                                    echo sprintf('<a href="%s" class="access-link">%s</a>', esc_url($login_url), esc_attr($login));
                                                                } else {
                                                                    echo sprintf('<a href="#" class="access-link" data-toggle="modal" data-target="#login_modal">%s</a>', esc_attr($login));
                                                                }
                                                                echo sprintf('<span>%s</span>', esc_html__('or', 'direo'));

                                                                if ($register_url) {
                                                                    echo sprintf('<a href="%s" class="access-link">%s</a>', esc_url($register_url), esc_attr($register));
                                                                } else {
                                                                    echo sprintf('<a href="#" class="access-link" data-toggle="modal" data-target="#signup_modal">%s</a>', esc_attr($register));
                                                                } ?>
                                                            </li>
                                                            <?php
                                                        } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php
                                        }

                                        if (class_exists('woocommerce')) {
                                            echo direo_tiny_cart();
                                        }

                                        if (is_user_logged_in() && class_exists('Directorist_Base')) { ?>
                                            <div class="offcanvas-menu ">
                                                <?php
                                                $avatar_img = get_avatar($author, 40, null, null, array('class' => 'rounded-circle'));
                                                if (empty($author_img)) { ?>
                                                    <a href="#" class="offcanvas-menu__user" class="rounded-circle">
                                                        <?php echo wp_kses_post($avatar_img); ?>
                                                    </a>
                                                    <?php
                                                } else { ?>
                                                    <a href="#" class="offcanvas-menu__user">
                                                        <?php
                                                        echo sprintf('<img src="%s" alt="%s" class="rounded-circle"/>', esc_url($author_img[0]), direo_get_image_alt($author_id)); ?>
                                                    </a>
                                                    <?php
                                                } ?>

                                                <div class="offcanvas-menu__contents">
                                                    <a href="#" class="offcanvas-menu__close">
                                                        <i class="la la-times-circle"></i>
                                                    </a>
                                                    <div class="author-avatar">
                                                        <?php
                                                        $avatar_img = get_avatar($author, null, null, null, array('class' => 'rounded-circle'));;
                                                        if (empty($author_img)) {
                                                            echo wp_kses_post($avatar_img);
                                                        } else {
                                                            echo sprintf('<img src="%s" alt="%s" class="rounded-circle"/>', esc_url($author_img[0]), direo_get_image_alt($author_id));
                                                        } ?>
                                                    </div>
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_dashboard_page_link()) . '#v-active-tab'; ?>">
                                                                <?php esc_html_e('My Listing', 'direo'); ?>
                                                            </a>
                                                        </li>
                                                        <?php if (get_the_ID() != get_directorist_option('user_dashboard')) { ?>
                                                            <li>
                                                                <a href="<?php echo esc_url(ATBDP_Permalink::get_dashboard_page_link()) . '#v-profile-tab'; ?>">
                                                                    <?php esc_html_e('My Profile', 'direo'); ?>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo esc_url(ATBDP_Permalink::get_dashboard_page_link()) . '#v-bookmark-tab'; ?>">
                                                                    <?php esc_html_e('Favorite Listing', 'direo'); ?>
                                                                </a>
                                                            </li>
                                                            <?php
                                                        } ?>
                                                        <li>
                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>">
                                                                <?php esc_attr_e($add_listing_btn, 'direo'); ?>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href=" <?php echo esc_url(wp_logout_url(home_url())); ?> ">
                                                                <?php esc_html_e('Logout', 'direo'); ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        if (class_exists('Directorist_Base')) {
                                            if (!is_user_logged_in() && $quick_log_reg) { ?>
                                                <div class="mobile-login">
                                                    <a href="#" class="access-link" data-toggle="modal"
                                                       data-target="#login_modal">
                                                        <span class="la la-user"></span>
                                                    </a>
                                                </div>
                                                <?php
                                            }
                                            if ($add_listing_btn) { ?>
                                                <div class="mobile-add-listing">
                                                    <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>">
                                                        <span class="la la-plus"></span>
                                                    </a>
                                                </div>
                                                <?php
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else { ?>
        <div class="menu-area menu1 menu--<?php echo ('menu3' == direo_menu_style()) ? esc_html('light bg-dark') : esc_html('dark'); ?>">
            <div class="top-menu-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="menu-fullwidth">
                                <?php
                                if ($dashboardFileName == 'dashboard.php') { ?>
                                    <a id="show-sidebar" href="#" class="active">
                                        <span class="bar"></span>
                                        <span class="bar"></span>
                                        <span class="bar"></span>
                                    </a>
                                    <?php
                                }
                                direo_site_identity(); ?>
                                <div class="menu-container order-lg-1 order-sm-0">
                                    <div class="d_menu">
                                        <nav class="navbar navbar-expand-lg mainmenu__menu">
                                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                                    data-target="#direo-navbar-collapse"
                                                    aria-controls="direo-navbar-collapse" aria-expanded="false"
                                                    aria-label="Toggle navigation">
                                            <span class="navbar-toggler-icon icon-menu">
                                                <i class="la la-reorder"></i>
                                            </span>
                                            </button>
                                            <div class="collapse navbar-collapse" id="direo-navbar-collapse">
                                                <?php
                                                wp_nav_menu(
                                                    array(
                                                        'theme_location' => 'primary',
                                                        'container' => false,
                                                        'fallback_cb' => false,
                                                        'menu_class' => 'navbar-nav',
                                                        'depth' => 3
                                                    )
                                                ); ?>
                                            </div>
                                        </nav>
                                    </div>
                                </div>

                                <div class="menu-right order-lg-2 order-sm-2">
                                    <div class="author-area">
                                        <div class="author__access_area">
                                            <ul class="d-flex list-unstyled align-items-center ">
                                                <?php
                                                if (!direo_woo_shopping_cart() && class_exists('Directorist_Base') && $add_listing_btn) { ?>
                                                    <li class="nav_addlisting_btn">
                                                        <a href="<?php echo !empty($add_btn_url) ? esc_url($add_btn_url) : esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
                                                           class="btn btn-xs btn-gradient btn-gradient-two">
                                                            <span class="la la-plus"></span>
                                                            <?php esc_attr_e($add_listing_btn, 'direo'); ?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                }
                                                if (class_exists('woocommerce') && $dashboardFileName !== 'dashboard.php') {
                                                    echo sprintf('<li class="nav_woocart">%s</li>', direo_tiny_cart());
                                                }
                                                if (class_exists('Directorist_Base') && $dashboardFileName !== 'dashboard.php') {
                                                    if (is_user_logged_in()) { ?>
                                                        <li>
                                                            <div class="author-info">
                                                                <?php
                                                                $avatar_img = get_avatar($author, 40, null, null, array('class' => 'rounded-circle'));
                                                                if (empty($author_img)) {
                                                                    echo wp_kses_post($avatar_img);
                                                                } else { ?>
                                                                    <a href="#" class="offcanvas-menu__user">
                                                                        <?php echo sprintf('<img src="%s" alt="%s" class="rounded-circle"/>', esc_url($author_img[0]), direo_get_image_alt($author_id)); ?>
                                                                    </a>
                                                                    <?php
                                                                } ?>

                                                                <ul class="list-unstyled">
                                                                    <li>
                                                                        <a href="<?php echo esc_url(ATBDP_Permalink::get_dashboard_page_link()); ?>">
                                                                            <?php esc_html_e('My Listing', 'direo'); ?>
                                                                        </a>
                                                                    </li>
                                                                    <?php if (get_the_ID() != get_directorist_option('user_dashboard')) { ?>
                                                                        <li>
                                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_dashboard_page_link()) . '#active_profile'; ?>">
                                                                                <?php esc_html_e('My Profile', 'direo'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_dashboard_page_link()) . '#active_saved_items'; ?>">
                                                                                <?php esc_html_e('Favorite Listing', 'direo'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                    } ?>
                                                                    <li>
                                                                        <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>">
                                                                            <?php esc_attr_e($add_listing_btn, 'direo'); ?>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href=" <?php echo esc_url(wp_logout_url(home_url())); ?> ">
                                                                            <?php esc_html_e('Logout', 'direo'); ?>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    } else {
                                                        if ($quick_log_reg) { ?>
                                                            <li class="access-link-wrapper">
                                                                <?php
                                                                if ($login_url) {
                                                                    echo sprintf('<a href="%s" class="access-link">%s</a>', esc_url($login_url), esc_attr($login));
                                                                } else {
                                                                    echo sprintf('<a href="#" class="access-link" data-toggle="modal" data-target="#login_modal">%s</a>', esc_attr($login));
                                                                }
                                                                echo sprintf('<span>%s</span>', esc_html__('or', 'direo'));

                                                                if ($register_url) {
                                                                    echo sprintf('<a href="%s" class="access-link">%s</a>', esc_url($register_url), esc_attr($register));
                                                                } else {
                                                                    echo sprintf('<a href="#" class="access-link" data-toggle="modal" data-target="#signup_modal">%s</a>', esc_attr($register));
                                                                } ?>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                    if (class_exists('Directorist_Base')) {
                                        if (!is_user_logged_in() && $quick_log_reg) { ?>
                                            <div class="mobile-login">
                                                <a href="#" class="access-link" data-toggle="modal"
                                                   data-target="#login_modal">
                                                    <span class="la la-user"></span>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                        if ($add_listing_btn) { ?>
                                            <div class="mobile-add-listing">
                                                <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>">
                                                    <span class="la la-plus"></span>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

/*========================================================
   Directorist page id check
/*========================================================*/
if (!function_exists('direo_directorist_pages')) {
    function direo_directorist_pages($page_id)
    {
        $page_id = '';
        if (class_exists('Directorist_Base') && (is_page() && get_the_ID() == get_directorist_option($page_id))) {
            $page_id = true;
        }
        return $page_id;
    }
}
/*=====================================================
 direo Page ID
=================================================*/
function direo_page_id()
{
    $id = '';
    if (class_exists('woocommerce') && is_shop() || class_exists('woocommerce') && is_product_taxonomy()) {
        $id = wc_get_page_id('shop');
    } elseif (class_exists('woocommerce') && is_cart()) {
        $id = wc_get_page_id('cart');
    } elseif (class_exists('woocommerce') && is_checkout()) {
        $id = wc_get_page_id('checkout');
    } elseif (class_exists('woocommerce') && is_account_page()) {
        $id = wc_get_page_id('myaccount');
    } elseif (class_exists('woocommerce') && is_home() || is_archive()) {
        $id = get_option('page_for_posts');
    } else {
        $id = get_the_ID();
    }
    return $id;
}

/*=====================================================
    Count Popular Post
=================================================*/
if (!function_exists('setPostViews')) {
    function setPostViews($postID)
    {
        $countKey = 'post_views_count';
        $count = get_post_meta($postID, $countKey, true);
        if ($count == '') {
            $count = 0;
            delete_post_meta($postID, $countKey);
            add_post_meta($postID, $countKey, '0');
        } else {
            $count++;
            update_post_meta($postID, $countKey, $count);
        }
    }
}

/*=====================================================
    Deactivate external extension
=================================================*/
function deactivate_directorist_individual_extension()
{
    $plugins = get_option('active_plugins');
    foreach ($plugins as $plugin) {
        $pluginToDeactivate = array('directorist-business-hours/bd-business-hour.php', 'directorist-claim-listing/directorist-claim-listing.php', 'directorist-listings-faqs/directorist-listings-faqs.php', 'directorist-paypal/directorist-paypal.php', 'directorist-pricing-plans/directorist-pricing-plans.php', 'directorist-stripe/directorist-stripe.php', 'directorist-woocommerce-pricing-plans/directorist-woocommerce-pricing-plans.php', 'Directorist - WooCommerce Pricing Plans');
        if (in_array($plugin, $pluginToDeactivate)) {
            deactivate_plugins(plugin_basename($plugin));
        }
    }
}

add_action('admin_notices', 'deactivate_directorist_individual_extension');

/*=====================================================
    Check elementor is using
=================================================*/
function is_elements()
{
    global $post;
    $elementor_using = '';
    if (in_array('elementor/elementor.php', (array)get_option('active_plugins'))) {
        $elementor_using = Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID);
    }

    $builder_meta = get_post_meta(get_the_ID(), 'kc_data', true);
    $kc_using = ($builder_meta) ? $builder_meta['mode'] : '';
    if ('kc' == $kc_using) {
        return true;
    } else {
        return $elementor_using;
    }
}

/*=====================================================
    Pagination
=================================================*/
function direo_page_pagination()
{
    wp_link_pages(array(
        'before' => '<div class="m-top-50"><nav class="navigation pagination d-flex justify-content-center" role="navigation"><div class="nav-links">',
        'after' => '</div></nav></div>',
        'pagelink' => '<span class="page-numbers">%</span>',
    ));
}
