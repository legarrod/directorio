<?php
/* Template Name: Dashboard
 * Author: Aazztech
 */

get_header();

if (!class_exists('Directorist_Base')) {
    get_template_part('404');
    return false;
}

if (is_user_logged_in()) {
    /*Popup Modal*/
    wp_enqueue_media();
    wp_enqueue_script('sweetalert');
    wp_enqueue_style('sweetalertcss');

    global $post;
    $date_format = get_option('date_format');
    $my_needs_tab = '';
    $listingS_per_page = -1;
    $featured_active = get_directorist_option('enable_featured_listing');

    /*My Listings*/
    $args = array(
        'author' => get_current_user_id(),
        'post_type' => ATBDP_POST_TYPE,
        'posts_per_page' => (int)$listingS_per_page,
        'order' => 'DESC',
        'orderby' => 'date',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_need_post',
                'value' => 'no',
                'compare' => '=',
            ),
            array(
                'key' => '_need_post',
                'compare' => 'NOT EXISTS',
            )
        ),
    );
    $active_listings = new WP_Query($args);

    $args2 = array(
        'author' => get_current_user_id(),
        'post_type' => ATBDP_POST_TYPE,
        'posts_per_page' => (int)$listingS_per_page,
        'order' => 'DESC',
        'orderby' => 'date',
        'post_status' => 'pending',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_need_post',
                'value' => 'no',
                'compare' => '=',
            ),
            array(
                'key' => '_need_post',
                'compare' => 'NOT EXISTS',
            )
        ),
    );
    $pending_listings = new WP_Query($args2);

    $args3 = array(
        'author' => get_current_user_id(),
        'post_type' => ATBDP_POST_TYPE,
        'posts_per_page' => (int)$listingS_per_page,
        'order' => 'DESC',
        'orderby' => 'date',
        'post_status' => 'private',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_need_post',
                'value' => 'no',
                'compare' => '=',
            ),
            array(
                'key' => '_need_post',
                'compare' => 'NOT EXISTS',
            )
        ),
    );
    $expired_listings = new WP_Query($args3);

    $my_listing_tab = get_directorist_option('my_listing_tab', 1);
    $active_post = $active_listings->post_count;
    $expired_post = $expired_listings->post_count;
    $pending_post = $pending_listings->post_count;

    if (class_exists('Post_Your_Need')) {
        /*Need Posts*/
        $needArgs = array(
            'author' => get_current_user_id(),
            'post_type' => ATBDP_POST_TYPE,
            'posts_per_page' => (int)$listingS_per_page,
            'order' => 'DESC',
            'orderby' => 'date',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_need_post',
                    'value' => 'yes',
                    'compare' => '=',
                ),
                array(
                    'key' => '_need_post',
                    'compare' => 'EXISTS',
                )
            ),
        );
        $active_needs = new WP_Query($needArgs);

        $needArgs2 = array(
            'author' => get_current_user_id(),
            'post_type' => ATBDP_POST_TYPE,
            'posts_per_page' => (int)$listingS_per_page,
            'order' => 'DESC',
            'orderby' => 'date',
            'post_status' => 'pending',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_need_post',
                    'value' => 'yes',
                    'compare' => '=',
                ),
                array(
                    'key' => '_need_post',
                    'compare' => 'EXISTS',
                )
            ),
        );
        $pending_needs = new WP_Query($needArgs2);

        $needArgs3 = array(
            'author' => get_current_user_id(),
            'post_type' => ATBDP_POST_TYPE,
            'posts_per_page' => (int)$listingS_per_page,
            'order' => 'DESC',
            'orderby' => 'date',
            'post_status' => 'private',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_need_post',
                    'value' => 'yes',
                    'compare' => '=',
                ),
                array(
                    'key' => '_need_post',
                    'compare' => 'EXISTS',
                )
            ),
        );
        $expired_needs = new WP_Query($needArgs3);

        $my_needs_tab = get_directorist_option('display_user_need_tab', 1);
        $active_need = $active_needs->post_count;
        $expired_need = $expired_needs->post_count;
        $pending_need = $pending_needs->post_count;
    }

    /*Dashboard Profile*/
    $uid = get_current_user_id();
    $my_profile_tab = get_directorist_option('my_profile_tab', 1);
    $fav_listings_tab = get_directorist_option('fav_listings_tab', 1);
    $u_pro_pic_id = get_user_meta($uid, 'pro_pic', true);
    $u_pro_pic = wp_get_attachment_image_src($u_pro_pic_id, array(120, 120));

    $c_user = get_userdata($uid);
    $u_phone = get_user_meta($uid, 'atbdp_phone', true);
    $u_website = $c_user->user_url;
    $u_address = get_user_meta($uid, 'address', true);
    $facebook = get_user_meta($uid, 'atbdp_facebook', true);
    $twitter = get_user_meta($uid, 'atbdp_twitter', true);
    $linkedIn = get_user_meta($uid, 'atbdp_linkedin', true);
    $youtube = get_user_meta($uid, 'atbdp_youtube', true);
    $bio = get_user_meta($uid, 'description', true); ?>

    <div id="wrapper" class="page-wrapper chiller-theme toggled">
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <div class="sidebar-menu atbd_tab_nav">
                    <ul class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php echo sprintf('<li class="header-menu"> <span>%s</span> </li>', esc_html__('LISTING', 'direo'));

                        /*My listings*/
                        if ($my_listing_tab) {
                            $list_found = ($active_listings->found_posts > 0) ? $active_listings->found_posts : '0'; ?>
                            <li class="sidebar-dropdown dashboard_listing">
                                <a href="#v-active-tab" class="sidebar-dropdown-icon">
                                    <i class="la la-list"></i>
                                    <span> <?php esc_html_e('Business Listings', 'direo'); ?> </span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul class="nav flex-column">
                                        <li>
                                            <a class="active direo_dash_submenu" id="v-pills-active-tab"
                                               data-toggle="pill"
                                               href="#v-active-tab"
                                               role="tab" aria-controls="v-active-tab" aria-selected="true">
                                                <?php esc_html_e('Active', 'direo'); ?>
                                                <span class="badge badge-pill badge-success"> <?php echo esc_attr($active_post); ?> </span>
                                            </a>
                                        </li>

                                        <li>
                                            <a id="v-pills-pending-tab" class="direo_dash_submenu" data-toggle="pill"
                                               href="#v-pending-tab"
                                               role="tab"
                                               aria-controls="v-pending-tab" aria-selected="false">
                                                <?php esc_html_e('Pending', 'direo'); ?>
                                                <span class="badge badge-pill badge-warning"><?php echo esc_attr($pending_post); ?></span>
                                            </a>
                                        </li>

                                        <li>
                                            <a id="v-pills-expired-tab" class="direo_dash_submenu" data-toggle="pill"
                                               href="#v-expired-tab"
                                               role="tab"
                                               aria-controls="v-expired-tab" aria-selected="false">
                                                <?php esc_html_e('Expired', 'direo'); ?>
                                                <span class="badge badge-pill badge-danger"><?php echo esc_attr($expired_post); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <?php
                        }

                        /*My Needs*/
                        if ($my_needs_tab) { ?>
                            <li class="sidebar-dropdown dashboard_need">
                                <a href="#n-active-tab" class="sidebar-dropdown-icon">
                                    <i class="la la-tools"></i>
                                    <span class=""> <?php esc_html_e('Enquiry Listings', 'direo'); ?> </span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul class="nav flex-column">
                                        <li>
                                            <a id="n-pills-active-tab" data-toggle="pill"
                                               class="direo_dash_submenu"
                                               href="#n-active-tab"
                                               role="tab" aria-controls="n-active-tab" aria-selected="true">
                                                <?php esc_html_e('Active', 'direo'); ?>
                                                <span class="badge badge-pill badge-success"> <?php echo esc_attr($active_need); ?> </span>
                                            </a>
                                        </li>

                                        <li>
                                            <a id="n-pills-pending-tab" data-toggle="pill" href="#n-pending-tab"
                                               role="tab"
                                               class="direo_dash_submenu"
                                               aria-controls="n-pending-tab" aria-selected="false">
                                                <?php esc_html_e('Pending', 'direo'); ?>
                                                <span class="badge badge-pill badge-warning"><?php echo esc_attr($pending_need); ?></span>
                                            </a>
                                        </li>

                                        <li>
                                            <a id="n-pills-expired-tab" data-toggle="pill" href="#n-expired-tab"
                                               role="tab"
                                               class="direo_dash_submenu"
                                               aria-controls="n-expired-tab" aria-selected="false">
                                                <?php esc_html_e('Expired', 'direo'); ?>
                                                <span class="badge badge-pill badge-danger"><?php echo esc_attr($expired_need); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <?php
                        }

                        /*My Favourite*/
                        if ($fav_listings_tab) {
                            $fav_listings = get_user_meta(get_current_user_id(), 'atbdp_favourites', true);
                            $fav_post = !empty($fav_listings) ? count($fav_listings) : ''; ?>
                            <li class="sidebar-dropdown">
                                <a id="v-pills-bookmark-tab" data-toggle="pill" href="#v-bookmark-tab" role="tab"
                                   aria-controls="v-bookmark-tab" aria-selected="false">
                                    <i class="la la-heart-o"></i>
                                    <?php
                                    echo sprintf('<span>%s</span>', esc_html__('Bookmark', 'direo'));
                                    echo !empty($fav_post) ? sprintf('<span class="badge badge-pill badge-success">%s</span>', esc_attr($fav_post)) : ''; ?>
                                </a>
                            </li>
                            <?php
                        }

                        /*Packages*/
                        do_action('atbdp_tab_after_favorite_listings');

                        /*My Profile*/
                        if ($my_profile_tab) { ?>
                            <li class="header-menu">
                                <span><?php esc_html_e('ACCOUNT', 'direo'); ?></span>
                            </li>
                            <li class="sidebar-dropdown">
                                <a id="v-pills-profile-tab" data-toggle="pill" href="#v-profile-tab" role="tab"
                                   aria-controls="v-profile-tab" aria-selected="false" class="atbd_tn_link">
                                    <i class="la la-user"></i>
                                    <span><?php esc_html_e('My Profile', 'direo'); ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">
                                    <i class="la la-power-off"></i>
                                    <span><?php esc_html_e('Logout', 'direo'); ?></span>
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="tab-content" id="v-pills-tabContent">

            <!-- ============  Listing post  ===========-->
            <?php
            if ($my_listing_tab) { ?>
                <!--Active Listing-->
                <div class="tab-pane fade show active" id="v-active-tab" role="tabpanel"
                     aria-labelledby="v-pills-active-tab">
                    <main class="page-content">
                        <div class="container-fluid">
                            <div class="page-content-header">
                                <h2><?php esc_html_e('Active Listing', 'direo'); ?></h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="#"><?php esc_html_e('Business Listings', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php esc_html_e('Active', 'direo'); ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="atbdb_content_module_contents tab-content">
                                        <div class="table-inner tab-pane active" id="home" role="tabpanel"
                                             aria-labelledby="home-tab">
                                            <div class="table-responsive">
                                                <?php if ($active_listings->have_posts()) { ?>
                                                    <table class="table dash-table">
                                                        <thead>
                                                        <tr>
                                                            <th><?php esc_html_e('Listing Name', 'direo'); ?> </th>
                                                            <th><?php esc_html_e('Review', 'direo'); ?></th>
                                                            <th><?php esc_html_e('Category', 'direo'); ?></th>
                                                            <?php if (is_fee_manager_active()) { ?>
                                                                <th><?php esc_html_e('Plan Name', 'direo'); ?></th>
                                                                <?php
                                                            } ?>
                                                            <th><?php esc_html_e('Expiration Date', 'direo'); ?></th>
                                                            <th><?php esc_html_e('Status', 'direo'); ?></th>
                                                            <th><?php esc_html_e('Actions', 'direo'); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="users-active__listings"></tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    echo sprintf('<p class="atbdp_nlf direo-dashboard-no-listing">%s</p>', esc_html__("Looks like you have not created any listing yet!", 'direo'));
                                                } ?>
                                            </div>
                                        </div>

                                        <?php
                                        $pagination = get_directorist_option('user_listings_pagination', 1);
                                        $paged = atbdp_get_paged_num();
                                        $paged = $paged ? $paged : '';
                                        if ($pagination) { ?>
                                            <nav aria-label="Page navigation example">
                                                <?php echo atbdp_pagination($active_listings, $paged); ?>
                                            </nav>
                                            <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>

                <!--Pending Listing-->
                <div class="tab-pane fade" id="v-pending-tab" role="tabpanel" aria-labelledby="v-pills-pending-tab">
                    <main class="page-content">
                        <div class="container-fluid">
                            <div class="page-content-header">
                                <h2><?php echo esc_html__('Pending Listing', 'direo'); ?></h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="#"><?php echo esc_html__('Business Listings', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php echo esc_html__('Pending', 'direo'); ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="atbdb_content_module_contents">
                                        <div class="table-inner table-responsive">
                                            <?php if ($pending_post) { ?>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo esc_html__('Listing Name', 'direo'); ?></th>
                                                        <th data-breakpoints="xs"><?php echo esc_html__('Review', 'direo'); ?></th>
                                                        <th data-breakpoints="xs"><?php echo esc_html__('Category', 'direo'); ?></th>
                                                        <?php if (is_fee_manager_active()) { ?>
                                                            <th data-breakpoints="xs sm"><?php esc_html_e('Plan Name', 'direo'); ?></th>
                                                            <?php
                                                        } ?>
                                                        <th data-breakpoints="xs sm"><?php echo esc_html__('Expiration Date', 'direo'); ?></th>
                                                        <th data-breakpoints="xs sm md"> <?php echo esc_html__('Status', 'direo'); ?></th>
                                                        <th data-breakpoints="xs sm md"> <?php echo esc_html__('Actions', 'direo'); ?></th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <?php
                                                    foreach ($pending_listings->posts as $key => $post) {
                                                        $listing_id = $post->ID;
                                                        $post_status = get_post_status_object($post->post_status)->label;
                                                        $sts = get_post_meta($post->ID, '_listing_status', true);
                                                        if ('Pending' == $post_status && 'expired' != $sts) {
                                                            $listing_img = get_post_meta($post->ID, '_listing_img', true);
                                                            $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                                            $listing_prv_img_link = wp_get_attachment_image_src($listing_prv_img, array(60, 60), false);

                                                            $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                                                            $cats = $cats ? $cats : [];
                                                            $category = get_post_meta($post->ID, '_admin_category_select', true);
                                                            $category_name = $cats ? $cats[0]->name : 'Uncategorized';
                                                            $category_icon = $cats ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type() . '-tags';
                                                            $icon_type = substr($category_icon, 0, 2);
                                                            $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                            $category_link = $cats ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';

                                                            $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID));
                                                            $display_review = get_directorist_option('enable_review', 1);

                                                            $exp_date = get_post_meta($post->ID, '_expiry_date', true);
                                                            $never_exp = get_post_meta($post->ID, '_never_expire', true);
                                                            $l_status = get_post_meta($post->ID, '_listing_status', true);
                                                            $exp_text = !empty($never_exp) ? __('Never Expires', 'direo') : date_i18n($date_format, strtotime($exp_date)); ?>

                                                            <tr data-expanded="<?php echo (0 === $key) ? esc_html('true') : ''; ?>"
                                                                class="listing_id_<?php echo esc_attr($post->ID); ?>">
                                                                <td>
                                                                <span class="atbd_footable">
                                                                    <a class="atbd_footable_img"
                                                                       href="<?php echo get_post_permalink($post->ID); ?>"><img
                                                                                src="<?php echo !empty($listing_prv_img_link) ? esc_url($listing_prv_img_link[0]) : ''; ?>"
                                                                                alt="<?php echo esc_attr(direo_get_image_alt($listing_prv_img)); ?>">
                                                                        </a>
                                                                    <h6>
                                                                        <a href="<?php echo get_post_permalink($post->ID); ?>">
                                                                            <?php echo !empty($post->post_title) ? esc_html(stripslashes($post->post_title)) : ''; ?>
                                                                        </a>
                                                                    </h6>
                                                                </span>

                                                                </td>
                                                                <?php
                                                                if ($display_review) { ?>
                                                                    <td>
                                                                        <ul class="rating">
                                                                            <?php
                                                                            $average = ATBDP()->review->get_average(get_the_ID());
                                                                            $star = '<li><span class="la la-star rate_active"></span></li>';
                                                                            $half_star = '<li><span class="la la-star-half-o rate_active"></span></li>';
                                                                            $none_star = '<li><span class="la la-star-o"></span></li>';

                                                                            if (is_int($average)) {
                                                                                for ($i = 1; $i <= 5; $i++) {

                                                                                    if ($i <= $average) {
                                                                                        echo wp_kses_post($star);
                                                                                    } else {
                                                                                        echo wp_kses_post($none_star);
                                                                                    }
                                                                                }
                                                                                wp_reset_postdata();
                                                                            } elseif (!is_int($average)) {
                                                                                $exp = explode('.', $average);
                                                                                $float_num = $exp[0];

                                                                                for ($i = 1; $i <= 5; $i++) {
                                                                                    if ($i <= $average) {
                                                                                        echo wp_kses_post($star);
                                                                                    } elseif (!empty($average) && $i > $average && $i <= $float_num + 1) {
                                                                                        echo wp_kses_post($half_star);
                                                                                    } else {
                                                                                        echo wp_kses_post($none_star);
                                                                                    }
                                                                                }
                                                                                wp_reset_postdata();
                                                                            }

                                                                            $review_title = '';
                                                                            if ($reviews_count) {
                                                                                if (1 < $reviews_count) {
                                                                                    $review_title = $reviews_count . esc_html__(' Reviews', 'direo');
                                                                                } else {
                                                                                    $review_title = $reviews_count . esc_html__(' Review', 'direo');
                                                                                }
                                                                            } ?>

                                                                            <li class="reviews">
                                                                                <span class="atbd_count">
                                                                                    <?php echo sprintf('(<b>%s</b> %s )', esc_attr($average . '/5'), esc_attr($review_title)); ?>
                                                                                </span>
                                                                            </li>

                                                                        </ul>
                                                                    </td>
                                                                    <?php
                                                                } ?>
                                                                <td class="atbd_listting_category">
                                                                    <div class="atbd_listing_icon">
                                                                        <ul>
                                                                            <li>
                                                                                <?php
                                                                                if ($cats) {
                                                                                    foreach ($cats as $cat) {
                                                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                                        $space = str_repeat(' ', 1);
                                                                                        $category_icon = $cats ? get_cat_icon($cat->term_id) : atbdp_icon_type() . '-tags';
                                                                                        $icon_type = substr($category_icon, 0, 2);
                                                                                        $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                                                        echo sprintf('%s<span><i class="%s"></i><a href="%s">%s</a></span>', esc_attr($space), esc_attr($icon), esc_url($link), esc_attr($cat->name));
                                                                                    }
                                                                                    wp_reset_postdata();
                                                                                } ?>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <?php if (is_fee_manager_active()) { ?>
                                                                    <td class="direo_plane_name">
                                                                        <?php do_action('atbdp_user_dashboard_listings_before_expiration', $listing_id); ?>
                                                                    </td>
                                                                    <?php
                                                                } ?>

                                                                <td>
                                                                    <?php echo ('expired' == $l_status) ? esc_html__('Expired', 'direo') : $exp_text; ?>
                                                                </td>

                                                                <td>
                                                                    <span class="badge badge-light pending"><?php echo esc_html__('Pending', 'direo'); ?></span>
                                                                </td>

                                                                <td class="edit_btn_wrap">
                                                                    <?php if (('renewal' == $l_status || 'expired' == $l_status)) {
                                                                        $can_renew = get_directorist_option('can_renew_listing');
                                                                        if (!$can_renew) return false;

                                                                        if (is_fee_manager_active()) {
                                                                            $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id); ?>
                                                                            <a data-toggle="modal"
                                                                               data-target="<?php echo esc_attr($modal_id); ?>"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn btn text-success atbdp_renew_with_plan">
                                                                                <?php esc_html_e('Renew', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        } else { ?>
                                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_renewal_page_link($listing_id)) ?>"
                                                                               id="directorist-renew"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn btn text-success">
                                                                                <?php esc_html_e('Renew', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        if ($featured_active && empty($featured) && !is_fee_manager_active()) { ?>
                                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_checkout_page_link($listing_id)); ?>"
                                                                               id="directorist-promote"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn btn text-primary">
                                                                                <?php esc_html_e('Promote Your listing', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        }
                                                                    } ?>

                                                                    <a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($listing_id)); ?>"
                                                                       class="btn text-primary">
                                                                        <?php esc_html_e(' Edit', 'direo'); ?>
                                                                    </a>

                                                                    <a href="listing-del" id="direo_remove_listing"
                                                                       data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                       class="directory_remove_btn btn text-danger">
                                                                        <?php esc_html_e('Delete', 'direo'); ?>
                                                                    </a>

                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    wp_reset_postdata(); ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo sprintf('<p class="atbdp_nlf direo-dashboard-no-listing">%s</p>', esc_html__("Looks like you have not created any listing yet!", 'direo'));
                                            } ?>
                                        </div>
                                        <?php
                                        $pagination = get_directorist_option('user_listings_pagination', 1);
                                        $paged = atbdp_get_paged_num();
                                        $paged = $paged ? $paged : '';
                                        if ($pagination) { ?>
                                            <nav aria-label="Page navigation example">
                                                <?php echo atbdp_pagination($pending_listings, $paged); ?>
                                            </nav>
                                            <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <!--Expired Listing-->
                <div class="tab-pane fade" id="v-expired-tab" role="tabpanel" aria-labelledby="v-pills-expired-tab">
                    <main class="page-content">
                        <div class="container-fluid">
                            <div class="page-content-header">
                                <h2><?php echo esc_html__('Expired Listing', 'direo'); ?></h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="#"><?php echo esc_html__('Business Listings', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php echo esc_html__('Expired', 'direo'); ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="atbdb_content_module_contents">
                                        <div class="table-inner table-responsive">
                                            <?php if (!empty($expired_post)) { ?>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo esc_html__('Listing Name', 'direo'); ?></th>
                                                        <th data-breakpoints="xs"><?php echo esc_html__('Review', 'direo'); ?></th>
                                                        <th data-breakpoints="xs"><?php echo esc_html__('Category', 'direo'); ?></th>
                                                        <?php if (is_fee_manager_active()) { ?>
                                                            <th data-breakpoints="xs sm"><?php esc_html_e('Plan Name', 'direo'); ?></th>
                                                            <?php
                                                        } ?>
                                                        <th data-breakpoints="xs sm"><?php echo esc_html__('Expiration Date', 'direo'); ?></th>
                                                        <th data-breakpoints="xs sm md"><?php echo esc_html__('Status', 'direo'); ?></th>
                                                        <th data-breakpoints="xs sm md"><?php echo esc_html__('Actions', 'direo'); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($expired_listings->posts as $key => $post) {
                                                        $listing_id = $post->ID;
                                                        $sts = get_post_meta($post->ID, '_listing_status', true);
                                                        $listing_img = get_post_meta($post->ID, '_listing_img', true);
                                                        $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                                        $listing_prv_img_link = wp_get_attachment_image_src($listing_prv_img, array(60, 60), false);

                                                        $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                                                        $cats = $cats ? $cats : [];
                                                        $category = get_post_meta($post->ID, '_admin_category_select', true);
                                                        $category_name = $cats ? $cats[0]->name : 'Uncategorized';
                                                        $category_icon = $cats ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type() . '-tags';
                                                        $icon_type = substr($category_icon, 0, 2);
                                                        $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                        $category_link = $cats ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';

                                                        $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID));
                                                        $display_review = get_directorist_option('enable_review', 1);

                                                        $exp_date = get_post_meta($post->ID, '_expiry_date', true);
                                                        $never_exp = get_post_meta($post->ID, '_never_expire', true);
                                                        $l_status = get_post_meta($post->ID, '_listing_status', true);
                                                        $exp_text = !empty($never_exp) ? __('Never Expires', 'direo') : date_i18n($date_format, strtotime($exp_date)); ?>


                                                        <tr data-expanded="<?php echo (0 === $key) ? esc_html('true') : ''; ?>"
                                                            class="listing_id_<?php echo esc_attr($post->ID); ?>">
                                                            <td>
                                                                <span class="atbd_footable">
                                                                    <a class="atbd_footable_img"
                                                                       href="<?php echo get_post_permalink($post->ID); ?>"><img
                                                                                src="<?php echo !empty($listing_prv_img_link) ? esc_url($listing_prv_img_link[0]) : ''; ?>"
                                                                                alt="<?php echo esc_attr(direo_get_image_alt($listing_prv_img)) ?>"></a>
                                                                    <h6>
                                                                        <a href="<?php echo get_post_permalink($post->ID); ?>">
                                                                            <?php echo !empty($post->post_title) ? esc_html(stripslashes($post->post_title)) : ''; ?>
                                                                        </a>
                                                                    </h6>
                                                                </span>
                                                            </td>
                                                            <?php
                                                            if ($display_review) { ?>
                                                                <td>
                                                                    <ul class="rating">
                                                                        <?php
                                                                        $average = ATBDP()->review->get_average(get_the_ID());
                                                                        $star = '<li><span class="la la-star rate_active"></span></li>';
                                                                        $half_star = '<li><span class="la la-star-half-o rate_active"></span></li>';
                                                                        $none_star = '<li><span class="la la-star-o"></span></li>';

                                                                        if (is_int($average)) {
                                                                            for ($i = 1; $i <= 5; $i++) {

                                                                                if ($i <= $average) {
                                                                                    echo wp_kses_post($star);
                                                                                } else {
                                                                                    echo wp_kses_post($none_star);
                                                                                }
                                                                            }
                                                                            wp_reset_postdata();
                                                                        } elseif (!is_int($average)) {
                                                                            $exp = explode('.', $average);
                                                                            $float_num = $exp[0];
                                                                            for ($i = 1; $i <= 5; $i++) {
                                                                                if ($i <= $average) {
                                                                                    echo wp_kses_post($star);
                                                                                } elseif (!empty($average) && $i > $average && $i <= $float_num + 1) {
                                                                                    echo wp_kses_post($half_star);
                                                                                } else {
                                                                                    echo wp_kses_post($none_star);
                                                                                }
                                                                            }
                                                                            wp_reset_postdata();
                                                                        }

                                                                        $review_title = '';
                                                                        if ($reviews_count) {
                                                                            if (1 < $reviews_count) {
                                                                                $review_title = $reviews_count . esc_html__(' Reviews', 'direo');
                                                                            } else {
                                                                                $review_title = $reviews_count . esc_html__(' Review', 'direo');
                                                                            }
                                                                        } ?>

                                                                        <li class="reviews">
                                                                            <span class="atbd_count">
                                                                                <?php echo sprintf('(<b>%s</b> %s )', esc_attr($average . '/5'), esc_attr($review_title)); ?>
                                                                            </span>
                                                                        </li>

                                                                    </ul>
                                                                </td>
                                                                <?php
                                                            } ?>
                                                            <td class="atbd_listting_category">
                                                                <div class="atbd_listing_icon">
                                                                    <ul>
                                                                        <li>
                                                                            <?php
                                                                            if ($cats) {
                                                                                foreach ($cats as $cat) {
                                                                                    $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                                    $space = str_repeat(' ', 1);
                                                                                    $category_icon = $cats ? get_cat_icon($cat->term_id) : atbdp_icon_type() . '-tags';
                                                                                    $icon_type = substr($category_icon, 0, 2);
                                                                                    $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                                                    echo sprintf('%s<span><i class="%s"></i><a href="%s">%s</a></span>', esc_attr($space), esc_attr($icon), esc_url($link), esc_attr($cat->name));
                                                                                }
                                                                                wp_reset_postdata();
                                                                            } ?>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                            <?php if (is_fee_manager_active()) { ?>
                                                                <td class="direo_plane_name">
                                                                    <?php do_action('atbdp_user_dashboard_listings_before_expiration', $listing_id); ?>
                                                                </td>
                                                                <?php
                                                            } ?>

                                                            <td>
                                                                <?php echo ('expired' == $l_status) ? esc_html__('Expired', 'direo') : $exp_text; ?>
                                                            </td>

                                                            <td>
                                                                <span class="badge badge-light expired">
                                                                    <?php echo esc_html__('Expired', 'direo') ?>
                                                                </span>
                                                            </td>

                                                            <td class="edit_btn_wrap">
                                                                <div class="action_button">
                                                                    <?php if (('renewal' == $l_status || 'expired' == $l_status)) {
                                                                        $can_renew = get_directorist_option('can_renew_listing');
                                                                        if (!$can_renew) return false;

                                                                        if (is_fee_manager_active()) {
                                                                            $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id); ?>
                                                                            <a data-toggle="modal"
                                                                               data-target="<?php echo esc_attr($modal_id); ?>"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn btn text-success atbdp_renew_with_plan">
                                                                                <?php esc_html_e('Renew', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        } else { ?>
                                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_renewal_page_link($listing_id)) ?>"
                                                                               id="directorist-renew"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn text-success">
                                                                                <?php esc_html_e('Renew', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        if ($featured_active && empty($featured) && !is_fee_manager_active()) { ?>
                                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_checkout_page_link($listing_id)) ?>"
                                                                               id="directorist-promote"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn btn text-primary">
                                                                                <?php esc_html_e('Promote Your listing', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        }
                                                                    } ?>

                                                                    <a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($listing_id)); ?>"
                                                                       class="btn text-primary">
                                                                        <?php esc_html_e(' Edit', 'direo') ?>
                                                                    </a>

                                                                    <a href="listing-del" id="direo_remove_listing"
                                                                       data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                       class="directory_remove_btn btn text-danger">
                                                                        <?php esc_html_e('Delete', 'direo'); ?>
                                                                    </a>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    wp_reset_postdata(); ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo sprintf('<p class="atbdp_nlf direo-dashboard-no-listing">%s</p>', esc_html__("Looks like you have not created any listing yet!", 'direo'));
                                            } ?>
                                        </div>
                                        <?php
                                        $pagination = get_directorist_option('user_listings_pagination', 1);
                                        $paged = atbdp_get_paged_num();
                                        $paged = $paged ? $paged : '';
                                        if ($pagination) { ?>
                                            <nav aria-label="Page navigation example">
                                                <?php echo atbdp_pagination($expired_listings, $paged); ?>
                                            </nav>
                                            <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <?php
            }

            /*============  Needs post  ===========*/
            if ($my_needs_tab) { ?>
                <!--Active Enquiry-->
                <div class="tab-pane fade" id="n-active-tab" role="tabpanel" aria-labelledby="n-pills-active-tab">
                    <main class="page-content">
                        <div class="container-fluid">
                            <div class="page-content-header">
                                <h2><?php esc_html_e('Active Enquiry', 'direo'); ?></h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="#"><?php esc_html_e('Enquiry Listings', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php esc_html_e('Active', 'direo'); ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="atbdb_content_module_contents tab-content">

                                        <div class="table-inner tab-pane active table-responsive" id="home"
                                             role="tabpanel"
                                             aria-labelledby="home-tab">
                                            <?php if ($active_needs->have_posts()) { ?>
                                                <table class="table dash-table dash-table-needs">
                                                    <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Listing Name', 'direo'); ?> </th>
                                                        <th></th>
                                                        <th><?php esc_html_e('Category', 'direo'); ?></th>
                                                        <?php if (is_fee_manager_active()) { ?>
                                                            <th><?php esc_html_e('Plan Name', 'direo'); ?></th>
                                                            <?php
                                                        } ?>
                                                        <th><?php esc_html_e('Expiration Date', 'direo'); ?></th>
                                                        <th><?php esc_html_e('Status', 'direo'); ?></th>
                                                        <th><?php esc_html_e('Actions', 'direo'); ?></th>
                                                    </tr>
                                                    </thead>

                                                    <tbody class="users-active__needs"></tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo sprintf('<p class="atbdp_nlf direo-dashboard-no-listing">%s</p>', esc_html__("Looks like you have not created any listing yet!", 'direo'));
                                            } ?>
                                        </div>

                                        <?php
                                        $pagination = get_directorist_option('user_listings_pagination', 1);
                                        $paged = atbdp_get_paged_num();
                                        $paged = $paged ? $paged : '';
                                        if ($pagination) { ?>
                                            <nav aria-label="Page navigation example">
                                                <?php echo atbdp_pagination($active_listings, $paged); ?>
                                            </nav>
                                            <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <!--Pending Enquiry-->
                <div class="tab-pane fade" id="n-pending-tab" role="tabpanel" aria-labelledby="n-pills-pending-tab">
                    <main class="page-content">
                        <div class="container-fluid">
                            <div class="page-content-header">
                                <h2><?php echo esc_html__('Pending Enquiry', 'direo'); ?></h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="#"><?php echo esc_html__('Enquiry Listings', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php echo esc_html__('Pending', 'direo'); ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="atbdb_content_module_contents">
                                        <div class="table-inner table-responsive">
                                            <?php if ($pending_need) { ?>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo esc_html__('Listing Name', 'direo'); ?>
                                                        </th>
                                                        <th data-breakpoints="xs">
                                                        </th>
                                                        <th data-breakpoints="xs">
                                                            <?php echo esc_html__('Category', 'direo'); ?></th>
                                                        <?php if (is_fee_manager_active()) { ?>
                                                            <th data-breakpoints="xs sm"><?php esc_html_e('Plan Name', 'direo'); ?></th>
                                                            <?php
                                                        } ?>
                                                        <th data-breakpoints="xs sm">
                                                            <?php echo esc_html__('Expiration Date', 'direo'); ?></th>
                                                        <th data-breakpoints="xs sm md">
                                                            <?php echo esc_html__('Status', 'direo'); ?></th>
                                                        <th data-breakpoints="xs sm md">
                                                            <?php echo esc_html__('Actions', 'direo'); ?></th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <?php
                                                    foreach ($pending_needs->posts as $key => $post) {
                                                        $listing_id = $post->ID;
                                                        $post_status = get_post_status_object($post->post_status)->label;
                                                        $sts = get_post_meta($post->ID, '_listing_status', true);
                                                        if ('Pending' == $post_status && 'expired' != $sts) {
                                                            $listing_img = get_post_meta($post->ID, '_listing_img', true);
                                                            $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                                            $listing_prv_img_link = wp_get_attachment_image_src($listing_prv_img, array(60, 60), false);

                                                            $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                                                            $cats = $cats ? $cats : [];
                                                            $category = get_post_meta($post->ID, '_admin_category_select', true);
                                                            $category_name = $cats ? $cats[0]->name : 'Uncategorized';
                                                            $category_icon = $cats ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type() . '-tags';
                                                            $icon_type = substr($category_icon, 0, 2);
                                                            $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                            $category_link = $cats ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';

                                                            $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID));
                                                            $display_review = get_directorist_option('enable_review', 1);

                                                            $exp_date = get_post_meta($post->ID, '_expiry_date', true);
                                                            $never_exp = get_post_meta($post->ID, '_never_expire', true);
                                                            $l_status = get_post_meta($post->ID, '_listing_status', true);
                                                            $exp_text = !empty($never_exp) ? __('Never Expires', 'direo') : date_i18n($date_format, strtotime($exp_date)); ?>
                                                            <tr data-expanded="<?php echo (0 === $key) ? esc_html('true') : ''; ?>"
                                                                class="listing_id_<?php echo esc_attr($post->ID); ?>">
                                                                <td>
                                                                <span class="atbd_footable">
                                                                <h6>
                                                                        <a href="<?php echo get_post_permalink($post->ID); ?>">
                                                                            <?php echo !empty($post->post_title) ? esc_html(stripslashes($post->post_title)) : ''; ?>
                                                                        </a>
                                                                    </h6>
                                                                </span>
                                                                </td>
                                                                <td></td>
                                                                <td class="atbd_listting_category">
                                                                    <div class="atbd_listing_icon">
                                                                        <ul>
                                                                            <li>
                                                                                <?php
                                                                                if ($cats) {
                                                                                    foreach ($cats as $cat) {
                                                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                                        $space = str_repeat(' ', 1);
                                                                                        $category_icon = $cats ? get_cat_icon($cat->term_id) : atbdp_icon_type() . '-tags';
                                                                                        $icon_type = substr($category_icon, 0, 2);
                                                                                        $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                                                        echo sprintf('%s<span><i class="%s"></i>%s</span>', esc_attr($space), esc_attr($icon), esc_attr($cat->name));
                                                                                    }
                                                                                    wp_reset_postdata();
                                                                                } ?>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <?php if (is_fee_manager_active()) { ?>
                                                                    <td class="direo_plane_name">
                                                                        <?php do_action('atbdp_user_dashboard_listings_before_expiration', $listing_id); ?>
                                                                    </td>
                                                                    <?php
                                                                } ?>

                                                                <td>
                                                                    <?php echo ('expired' == $l_status) ? esc_html__('Expired', 'direo') : $exp_text; ?>
                                                                </td>

                                                                <td>
                                                                    <span class="badge badge-light pending">
                                                                        <?php echo esc_html__('Pending', 'direo'); ?>
                                                                    </span>
                                                                </td>

                                                                <td class="edit_btn_wrap">
                                                                    <?php if (('renewal' == $l_status || 'expired' == $l_status)) {
                                                                        $can_renew = get_directorist_option('can_renew_listing');
                                                                        if (!$can_renew) return false;
                                                                        if (is_fee_manager_active()) {
                                                                            $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id); ?>
                                                                            <a data-toggle="modal"
                                                                               data-target="<?php echo esc_attr($modal_id); ?>"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn btn text-success atbdp_renew_with_plan">
                                                                                <?php esc_html_e('Renew', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        } else { ?>
                                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_renewal_page_link($listing_id)) ?>"
                                                                               id="directorist-renew"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn btn text-success">
                                                                                <?php esc_html_e('Renew', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        if ($featured_active && empty($featured) && !is_fee_manager_active()) { ?>
                                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_checkout_page_link($listing_id)) ?>"
                                                                               id="directorist-promote"
                                                                               data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                               class="directory_btn btn text-primary">
                                                                                <?php esc_html_e('Promote Your listing', 'direo'); ?>
                                                                            </a>
                                                                            <?php
                                                                        }
                                                                    } ?>

                                                                    <a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($listing_id)); ?>"
                                                                       class="btn text-primary">
                                                                        <?php esc_html_e(' Edit', 'direo') ?>
                                                                    </a>

                                                                    <a href="listing-del" id="direo_remove_listing"
                                                                       data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                       class="directory_remove_btn btn text-danger">
                                                                        <?php esc_html_e('Delete', 'direo'); ?>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    wp_reset_postdata(); ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo sprintf('<p class="atbdp_nlf direo-dashboard-no-listing">%s</p>', esc_html__("Looks like you have not created any listing yet!", 'direo'));
                                            } ?>
                                        </div>

                                        <?php
                                        $pagination = get_directorist_option('user_listings_pagination', 1);
                                        $paged = atbdp_get_paged_num();
                                        $paged = $paged ? $paged : '';
                                        if ($pagination) { ?>
                                            <nav aria-label="Page navigation example">
                                                <?php echo atbdp_pagination($pending_listings, $paged); ?>
                                            </nav>
                                            <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <!--Expired Enquiry-->
                <div class="tab-pane fade" id="n-expired-tab" role="tabpanel" aria-labelledby="n-pills-expired-tab">
                    <main class="page-content">
                        <div class="container-fluid">
                            <div class="page-content-header">
                                <h2><?php echo esc_html__('Expired Enquiry', 'direo'); ?></h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="#"><?php echo esc_html__('Enquiry Listings', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php echo esc_html__('Expired', 'direo'); ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="atbdb_content_module_contents">
                                        <div class="table-inner table-responsive">
                                            <?php if ($expired_need) { ?>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo esc_html__('Listing Name', 'direo'); ?></th>
                                                        <th data-breakpoints="xs">
                                                        </th>
                                                        <th data-breakpoints="xs">
                                                            <?php echo esc_html__('Category', 'direo'); ?></th>
                                                        <?php if (is_fee_manager_active()) { ?>
                                                            <th data-breakpoints="xs sm"><?php esc_html_e('Plan Name', 'direo'); ?></th>
                                                        <?php } ?>
                                                        <th data-breakpoints="xs sm">
                                                            <?php echo esc_html__('Expiration Date', 'direo'); ?></th>
                                                        <th data-breakpoints="xs sm md">
                                                            <?php echo esc_html__('Status', 'direo'); ?></th>
                                                        <th data-breakpoints="xs sm md">
                                                            <?php echo esc_html__('Actions', 'direo'); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    foreach ($expired_needs->posts as $key => $post) {
                                                        $listing_id = $post->ID;
                                                        $sts = get_post_meta($post->ID, '_listing_status', true);
                                                        if ('expired' === $sts) {
                                                            $listing_img = get_post_meta($post->ID, '_listing_img', true);
                                                            $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                                            $listing_prv_img_link = wp_get_attachment_image_src($listing_prv_img, array(60, 60), false);

                                                            $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                                                            $cats = $cats ? $cats : [];
                                                            $category = get_post_meta($post->ID, '_admin_category_select', true);
                                                            $category_name = $cats ? $cats[0]->name : 'Uncategorized';
                                                            $category_icon = $cats ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type() . '-tags';
                                                            $icon_type = substr($category_icon, 0, 2);
                                                            $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                            $category_link = $cats ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';

                                                            $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID));
                                                            $display_review = get_directorist_option('enable_review', 1);

                                                            $exp_date = get_post_meta($post->ID, '_expiry_date', true);
                                                            $never_exp = get_post_meta($post->ID, '_never_expire', true);
                                                            $l_status = get_post_meta($post->ID, '_listing_status', true);
                                                            $exp_text = !empty($never_exp) ? __('Never Expires', 'direo') : date_i18n($date_format, strtotime($exp_date)); ?>


                                                            <tr data-expanded="<?php echo (0 === $key) ? esc_html('true') : ''; ?>"
                                                                class="listing_id_<?php echo esc_attr($post->ID); ?>">
                                                                <td>
                                                                    <span class="atbd_footable">
                                                                    <h6>
                                                                        <a href="<?php echo get_post_permalink($post->ID); ?>">
                                                                            <?php echo !empty($post->post_title) ? esc_html(stripslashes($post->post_title)) : ''; ?></a>
                                                                    </h6>
                                                                    </span>
                                                                </td>
                                                                <td></td>
                                                                <td class="atbd_listting_category">
                                                                    <div class="atbd_listing_icon">
                                                                        <ul>
                                                                            <li>
                                                                                <?php
                                                                                if ($cats) {
                                                                                    foreach ($cats as $cat) {
                                                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                                        $space = str_repeat(' ', 1);

                                                                                        $category_icon = $cats ? get_cat_icon($cat->term_id) : atbdp_icon_type() . '-tags';
                                                                                        $icon_type = substr($category_icon, 0, 2);
                                                                                        $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                                                        echo sprintf('%s<span><i class="%s"></i>%s</span>', esc_attr($space), esc_attr($icon), esc_attr($cat->name));
                                                                                    }
                                                                                    wp_reset_postdata();
                                                                                } ?>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <?php if (is_fee_manager_active()) { ?>
                                                                    <td class="direo_plane_name">
                                                                        <?php do_action('atbdp_user_dashboard_listings_before_expiration', $listing_id); ?>
                                                                    </td>
                                                                <?php } ?>

                                                                <td>
                                                                    <?php echo ('expired' == $l_status) ? esc_html__('Expired', 'direo') : $exp_text; ?>
                                                                </td>

                                                                <td>
                                                                    <span class="badge badge-light expired">
                                                                        <?php echo esc_html__('Expired', 'direo') ?>
                                                                    </span>
                                                                </td>

                                                                <td class="edit_btn_wrap">
                                                                    <div class="action_button">
                                                                        <?php if (('renewal' == $l_status || 'expired' == $l_status)) {
                                                                            $can_renew = get_directorist_option('can_renew_listing');
                                                                            if (!$can_renew) return false;

                                                                            if (is_fee_manager_active()) {
                                                                                $modal_id = apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $listing_id); ?>
                                                                                <a data-toggle="modal"
                                                                                   data-target="<?php echo esc_attr($modal_id); ?>"
                                                                                   data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                                   class="directory_btn btn text-success atbdp_renew_with_plan">
                                                                                    <?php esc_html_e('Renew', 'direo'); ?>
                                                                                </a>

                                                                                <?php
                                                                            } else { ?>
                                                                                <a href="<?php echo esc_url(ATBDP_Permalink::get_renewal_page_link($listing_id)) ?>"
                                                                                   id="directorist-renew"
                                                                                   data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                                   class="directory_btn btn text-success">
                                                                                    <?php esc_html_e('Renew', 'direo'); ?>
                                                                                </a>
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            if ($featured_active && empty($featured) && !is_fee_manager_active()) { ?>
                                                                                <a href="<?php echo esc_url(ATBDP_Permalink::get_checkout_page_link($listing_id)) ?>"
                                                                                   id="directorist-promote"
                                                                                   data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                                   class="directory_btn btn text-primary">
                                                                                    <?php esc_html_e('Promote Your listing', 'direo'); ?>
                                                                                </a>
                                                                                <?php
                                                                            }
                                                                        } ?>
                                                                        <a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($listing_id)); ?>"
                                                                           class="btn text-primary">
                                                                            <?php esc_html_e(' Edit', 'direo') ?>
                                                                        </a>

                                                                        <a href="listing-del"
                                                                           id="direo_remove_listing"
                                                                           data-listing_id="<?php echo esc_attr($listing_id); ?>"
                                                                           class="directory_remove_btn btn text-danger">
                                                                            <?php esc_html_e('Delete', 'direo'); ?>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    wp_reset_postdata(); ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo sprintf('<p class="atbdp_nlf direo-dashboard-no-listing">%s</p>', esc_html__("Looks like you have not created any listing yet!", 'direo'));
                                            } ?>
                                        </div>

                                        <?php
                                        $pagination = get_directorist_option('user_listings_pagination', 1);
                                        $paged = atbdp_get_paged_num();
                                        $paged = $paged ? $paged : '';
                                        if ($pagination) { ?>
                                            <nav aria-label="Page navigation example">
                                                <?php echo atbdp_pagination($expired_listings, $paged); ?>
                                            </nav>
                                            <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <?php
            }

            /*Bookmark Listing*/
            if ($fav_listings_tab) { ?>
                <div class="tab-pane fade" id="v-bookmark-tab" role="tabpanel" aria-labelledby="v-pills-bookmark-tab">
                    <main class="page-content">
                        <div class="container-fluid">
                            <div class="page-content-header">
                                <h2><?php esc_html_e('Bookmark', 'direo'); ?></h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php echo esc_html__('Bookmarks', 'direo'); ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="atbdb_content_module_contents">
                                        <div class="table-inner table-responsive">
                                            <?php
                                            if ($fav_listings) { ?>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col"> <?php echo esc_html__('Listing Name', 'direo'); ?>
                                                        </th>
                                                        <th data-breakpoints="xs"> <?php echo esc_html__('Categoriy', 'direo'); ?></th>
                                                        <th data-breakpoints="xs"
                                                            scope="col"> <?php echo class_exists('Post_Your_Need') ? esc_html__('Type', 'direo') : ''; ?></th>
                                                        <th data-breakpoints="xs sm"></th>
                                                        <th data-breakpoints="xs sm"></th>
                                                        <th data-breakpoints="xs sm"></th>
                                                        <th data-breakpoints="xs sm"></th>
                                                        <th data-breakpoints="xs sm"></th>
                                                        <th data-breakpoints="xs sm"></th>
                                                        <th data-breakpoints="xs sm"></th>
                                                        <th data-breakpoints="xs sm md"><?php echo esc_html__('Actions', 'direo'); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($fav_listings as $key => $value) {
                                                        $post = get_post($value);
                                                        $listing_id = $post->ID;
                                                        $title = !empty($post->post_title) ? $post->post_title : esc_html__('Untitled', 'direo');
                                                        $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                                                        $cats = $cats ? $cats : [];
                                                        $category_name = $cats ? $cats[0]->name : 'Uncategorized';
                                                        $category_icon = $cats ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type() . '-tags';
                                                        $icon_type = substr($category_icon, 0, 2);
                                                        $category_link = $cats ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';
                                                        $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                        $post_link = esc_url(get_post_permalink($post->ID));
                                                        $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                                                        $img_link = wp_get_attachment_image_src($listing_prv_img, array(60, 60), false);
                                                        $type = get_post_meta($listing_id, '_need_post', true); ?>
                                                        <tr class="listing-ID-<?php echo esc_attr($post->ID); ?>">
                                                            <td>
                                                                <span class="atbd_footable">
                                                                <?php
                                                                echo !empty($img_link) ? sprintf('<a class="atbd_footable_img" href="%s"><img src="%s" alt="%s"/></a>', esc_url($post_link), esc_url($img_link[0]), direo_get_image_alt($listing_prv_img)) : '';
                                                                echo sprintf('<h6> <a href="%s">%s</a> </h6>', esc_url($post_link), esc_attr($title)); ?>
                                                                </span>

                                                            </td>
                                                            <td class="atbd_listting_category">
                                                                <?php
                                                                $totalTerm = count($cats);
                                                                echo sprintf('<span class="%s"></span><a href="%s">%s</a>', esc_attr($icon), esc_url($category_link), esc_attr($category_name));
                                                                if ($totalTerm > 1) { ?>
                                                                    <div class="atbd_cat_popup">
                                                                        <span>+<?php echo esc_attr($totalTerm - 1); ?></span>
                                                                        <div class="atbd_cat_popup_wrapper">
                                                                            <?php
                                                                            $output = array();
                                                                            foreach (array_slice($cats, 1) as $cat) {
                                                                                $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                                $space = str_repeat(' ', 1);
                                                                                $category_icon = $cats ? get_cat_icon($cat->term_id) : atbdp_icon_type() . '-tags';
                                                                                $icon_type = substr($category_icon, 0, 2);
                                                                                $icon = 'la' === $icon_type ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                                                                                $output [] = sprintf('%s<span class="ctbd_popup_icon-style"><i class="%s"></i><a href="%s">%s<span>,</span></a></span>', esc_attr($space), esc_attr($icon), esc_url($link), esc_attr($cat->name));
                                                                            }
                                                                            wp_reset_postdata(); ?>
                                                                            <span class="ctbd_popup_icon">
                                                                                <?php echo join($output); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                } ?>

                                                            </td>
                                                            <td><?php echo class_exists('Post_Your_Need') ? ('yes' === $type) ? __('Enquiry', 'direo') : __('Listing', 'direo') : ''; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                <?php
                                                                if (class_exists('Directorist_Base')) {
                                                                    echo atbdp_listings_mark_as_favourite($listing_id);
                                                                } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    wp_reset_postdata(); ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo sprintf('<p class="atbdp_nlf direo-dashboard-no-listing">%s</p>', esc_html__("Looks like you have not saved any listing yet!", 'direo'));
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <?php
            }

            do_action('atbdp_tab_content_after_favorite');

            /*Profile*/
            if ($my_profile_tab) { ?>
                <div class="tab-pane fade" id="v-profile-tab" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <main class="page-content">
                        <div class="container-fluid">
                            <div class="page-content-header">
                                <h2><?php echo esc_html__('My Profile', 'direo') ?></h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'direo'); ?></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php echo esc_html__('My Profile', 'direo'); ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class=" atbd_tab-content">
                                <div class="atbd_tab_inner" id="profile">
                                    <form action="#" id="user_profile_form" method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="user_pro_img_area">
                                                    <div class="user_img dashboard-content-box profile-img"
                                                         id="profile_pic_container">
                                                        <div class="choose_btn">
                                                            <input type="hidden" name="user[pro_pic]" id="pro_pic"
                                                                   value="<?php echo !empty($u_pro_pic_id) ? esc_attr($u_pro_pic_id) : ''; ?>">
                                                            <label for="pro_pic" id="upload_pro_pic">
                                                                <?php esc_html_e('Change', 'direo'); ?>
                                                            </label>
                                                        </div>

                                                        <div class="pro_img_wrapper">
                                                            <img src="<?php echo !empty($u_pro_pic) ? esc_url($u_pro_pic[0]) : esc_url(ATBDP_PUBLIC_ASSETS . 'images/no-image.jpg'); ?>"
                                                                 id="pro_img"
                                                                 alt="<?php echo direo_get_image_alt($uid) ?>"/>
                                                            <div class="cross" id="remove_pro_pic">
                                                                <span class="fa fa-times"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="dashboard-content-box change-pass">
                                                    <?php echo sprintf('<h6>%s</h6>', esc_html__('Change Password', 'direo')); ?>
                                                    <div class="form-group">
                                                        <input id="new_pass" class="form-control" type="password"
                                                               name="user[new_pass]"
                                                               value="<?php echo !empty($new_pass) ? esc_attr($new_pass) : ''; ?>"
                                                               placeholder="<?php esc_html_x('Enter a new password', 'placeholder', 'direo'); ?>">

                                                        <input id="confirm_pass" class="form-control" type="password"
                                                               name="user[confirm_pass]"
                                                               value="<?php echo !empty($confirm_pass) ? esc_attr($confirm_pass) : ''; ?>"
                                                               placeholder="<?php esc_html_x('Confirm your new password', 'placeholder', 'direo'); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-9">
                                                <div class="atbd_user_profile_edit">
                                                    <?php echo sprintf('<div class="profile_title"><h4>%s</h4></div>', esc_html__('Profile Details', 'direo')); ?>
                                                    <div class="user_info_wrap">
                                                        <!--hidden inputs-->
                                                        <input type="hidden"
                                                               name="ID"
                                                               value="<?php echo get_current_user_id(); ?>">
                                                        <!--Full name-->
                                                        <div class="row row_fu_name">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="full_name">
                                                                        <?php esc_html_e('Full Name', 'direo'); ?>
                                                                    </label>
                                                                    <input class="form-control" type="text"
                                                                           name="user[full_name]"
                                                                           value="<?php echo !empty($c_user->display_name) ? esc_attr($c_user->display_name) : ''; ?>"
                                                                           placeholder="<?php esc_html_x('Enter your full name', 'placeholder', 'direo'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="user_name">
                                                                        <?php esc_html_e('User Name', 'direo'); ?>
                                                                    </label>
                                                                    <input class="form-control" id="user_name"
                                                                           type="text"
                                                                           disabled="disabled" name="user[user_name]"
                                                                           value="<?php echo !empty($c_user->user_login) ? esc_attr($c_user->user_login) : ''; ?>">
                                                                    <?php esc_html_e('(username can not be changed)', 'direo'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--ends .row-->
                                                        <!--First Name-->
                                                        <div class="row row_fl_name">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="first_name">
                                                                        <?php esc_html_e('First Name', 'direo'); ?>
                                                                    </label>
                                                                    <input class="form-control" id="first_name"
                                                                           type="text"
                                                                           name="user[first_name]"
                                                                           value="<?php echo !empty($c_user->first_name) ? esc_attr($c_user->first_name) : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="last_name">
                                                                        <?php esc_html_e('Last Name', 'direo'); ?>
                                                                    </label>
                                                                    <input class="form-control" id="last_name"
                                                                           type="text"
                                                                           name="user[last_name]"
                                                                           value="<?php echo !empty($c_user->last_name) ? esc_attr($c_user->last_name) : ''; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--ends .row-->
                                                        <!--Email-->
                                                        <div class="row row_email_cell">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <?php echo sprintf('<label for="req_email">%s</label>', esc_html__('Email (required)', 'direo')) ?>
                                                                    <input class="form-control" id="req_email"
                                                                           type="text"
                                                                           name="user[user_email]"
                                                                           value="<?php echo !empty($c_user->user_email) ? esc_attr($c_user->user_email) : ''; ?>"
                                                                           required/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="phone">
                                                                        <?php esc_html_e('Cell Number', 'direo'); ?>
                                                                    </label>
                                                                    <input class="form-control" type="tel"
                                                                           name="user[phone]"
                                                                           value="<?php echo !empty($u_phone) ? esc_attr($u_phone) : ''; ?>"
                                                                           placeholder="<?php esc_html_x('Enter your phone number', 'placeholder', 'direo'); ?>"
                                                                           id="phone"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--ends .row-->
                                                        <!--Website-->
                                                        <div class="row row_site_addr">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="website">
                                                                        <?php esc_html_e('Website', 'direo'); ?>
                                                                    </label>
                                                                    <input class="form-control" id="website" type="text"
                                                                           name="user[website]"
                                                                           value="<?php echo !empty($u_website) ? esc_url($u_website) : ''; ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="address">
                                                                        <?php esc_html_e('Address', 'direo'); ?>
                                                                    </label>
                                                                    <input class="form-control" id="address" type="text"
                                                                           name="user[address]"
                                                                           value="<?php echo !empty($u_address) ? esc_attr($u_address) : ''; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--ends .row-->
                                                        <!--social info-->
                                                        <div class="row row_socials">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="facebook">
                                                                        <?php esc_html_e('Facebook', 'direo'); ?>
                                                                    </label>
                                                                    <p><?php esc_html_e('Leave it empty to hide', 'direo') ?></p>
                                                                    <input id="facebook" class="form-control" type="url"
                                                                           name="user[facebook]"
                                                                           value="<?php echo !empty($facebook) ? esc_attr($facebook) : ''; ?>"
                                                                           placeholder="<?php esc_html_x('Enter your facebook url', 'placeholder', 'direo'); ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="twitter"><?php esc_html_e('Twitter', 'direo'); ?></label>
                                                                    <p><?php esc_html_e('Leave it empty to hide', 'direo') ?></p>
                                                                    <input id="twitter" class="form-control" type="url"
                                                                           name="user[twitter]"
                                                                           value="<?php echo !empty($twitter) ? esc_attr($twitter) : ''; ?>"
                                                                           placeholder="<?php esc_html_x('Enter your twitter url', 'placeholder', 'direo'); ?>"/>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="linkedIn"><?php esc_html_e('LinkedIn', 'direo'); ?></label>
                                                                    <p><?php esc_html_e('Leave it empty to hide', 'direo') ?></p>
                                                                    <input id="linkedIn" class="form-control" type="url"
                                                                           name="user[linkedIn]"
                                                                           value="<?php echo !empty($linkedIn) ? esc_attr($linkedIn) : ''; ?>"
                                                                           placeholder="<?php esc_html_x('Enter linkedIn url', 'placeholder', 'direo'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="youtube"><?php esc_html_e('Youtube', 'direo'); ?></label>
                                                                    <p><?php esc_html_e('Leave it empty to hide', 'direo') ?></p>
                                                                    <input id="youtube" class="form-control" type="url"
                                                                           name="user[youtube]"
                                                                           value="<?php echo !empty($youtube) ? esc_attr($youtube) : ''; ?>"
                                                                           placeholder="<?php esc_html_x('Enter youtube url', 'placeholder', 'direo'); ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="bio"><?php esc_html_e('About Author', 'direo'); ?></label>
                                                                    <textarea class="wp-editor-area form-control"
                                                                              style="height: 200px" autocomplete="off"
                                                                              cols="40"
                                                                              name="user[bio]"
                                                                              id="bio"><?php echo !empty($bio) ? esc_attr($bio) : ''; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit"
                                                                class="btn btn-primary"
                                                                id="update_user_profile">
                                                            <?php esc_html_e('Save Changes', 'direo'); ?>
                                                        </button>
                                                        <div id="pro_notice"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <?php
            } ?>
        </div>
    </div>
    <?php
} else {
    echo "<script>window.location='" . esc_url(ATBDP_Permalink::get_login_page_url()) . "'</script>";
}

get_footer(); ?>

<style>
    @media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 991px) {
        .dash-table td.dl-title:before {
            content: '<?php echo esc_html__('Listing Name','direo')?>';
        }

        .dash-table td.dl-review:before {
            content: '<?php echo esc_html__('Review','direo')?>';
        }

        .dash-table td.dl-cat:before {
            content: '<?php echo esc_html__('Category','direo')?>';
        }

        .dash-table td.dl-expired:before {
            content: '<?php echo esc_html__('Expiration Date','direo')?>';
        }

        .dash-table td.dl-plan:before {
            content: '<?php echo esc_html__('Plan Name','direo')?>';
        }

        .dash-table td.dl-status:before {
            content: '<?php echo esc_html__('Status','direo')?>';
        }

        .dash-table td.dl-action:before {
            content: '<?php echo esc_html__('Action','direo')?>';
        }

        .dash-table tr:last-child td:before {
            content: ''
        }

        .dash-table-needs td.dn-title:before {
            content: '<?php echo esc_html__('Listing Name','direo')?>';
        }

        .dash-table-needs td.dn-cat:before {
            content: '<?php echo esc_html__('Category','direo')?>';
        }

        .dash-table-needs td.dn-expired:before {
            content: '<?php echo esc_html__('Expiration Date','direo')?>';
        }

        .dash-table-needs td.dn-plan:before {
            content: '<?php echo esc_html__('Plan','direo')?> Name';
        }

        .dash-table-needs td.dn-status:before {
            content: '<?php echo esc_html__('Status','direo')?>';
        }

        .dash-table-needs td.dn-action:before {
            content: '<?php echo esc_html__('Action','direo')?>';
        }

        .dash-table-needs tr:last-child td:before {
            content: ' ';
        }
    }
</style>