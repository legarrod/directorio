<?php

//===========Direo Color Control Customizer Panel=============

function direo_custom_style()
{

    $primary = get_theme_mod('p_color', '#f5548e');
    $primary_g = get_theme_mod('p_g_color', 'rgba(245, 84, 142, 0.85)');
    $secondary = get_theme_mod('s_color', '#903af9');
    $secondary_g = get_theme_mod('s_g_color', 'rgba(144, 58, 249, 0.85))');
    $success = get_theme_mod('su_color', '#32cc6f');
    $info = get_theme_mod('in_color', '#3a7dfd');
    $danger = get_theme_mod('dn_color', '#fd4868');
    $warnning = get_theme_mod('wr_color', '#fa8b0c'); ?>


    <style>
        <?php if('#f5548e' != $primary){?>
        .color-primary, #directorist.atbd_wrapper .btn-outline-primary, #directorist.atbd_wrapper .btn-outline.btn-outline-primary:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_info .atbd_listing_title a:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_bottom_content .atbd_content_right .atbd_save:hover, #directorist.atbd_wrapper .atbdp-text-list .atbd_category_wrapper a:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_location_grid_wrap a.atbd_location_grid:hover, .post--card .card-body h6 a:hover, .post--card .card-body .post-meta li a:hover, .post--card2 .card-body .post-meta li a:hover, .testimonial-carousel .owl-nav button:hover, .widget.widget--links li a:hover, .atbd_author_info_widget .atbd_widget_contact_info ul li span:first-child, .atbdp-widget-categories .atbdp_parent_category li > .cat-trigger:hover, .atbdp-widget-categories .atbdp_parent_category li a:hover, .atbd_categorized_listings .listings > li .listing_value span, .atbd_categorized_listings .listings > li .directory_tag span .atbd_cat_popup .atbd_cat_popup_wrapper span a:hover, .category-widget ul li a:hover, .widget-wrapper.widget_archive ul li a:hover, .widget-wrapper.widget_categories ul li a:hover, .widget-wrapper.widget_pages ul li a:hover, .widget-wrapper.widget_meta ul li a:hover, .widget.widget_rss ul li .rsswidget, .widget_tag_cloud .tagcloud a:hover, .widget_nav_menu ul li.menu-item a:hover, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_author_info_widget .atbd_widget_contact_info ul li span.fa, .footer-three .footer-bottom .footer-bottom--content p span, .footer-three .footer-bottom .footer-bottom--content p i, .listing-info .listing-info--meta .atbd_listing_category a:hover, .listing-info .atbd_listing_action_area .atbd_action:hover > a, .listing-info .atbd_listing_action_area .atbd_action:hover > span, .listing-info .atbd_listing_action_area .atbd_action .action_button > a:hover, .listing-info .atbd_listing_action_area .atbd_share .atbd_director_social_wrap a:hover, .menu-area.menu--light .mainmenu__menu .navbar-nav > li.has_dropdown .dropdown-menu .dropdown-menu--inner > ul li a:hover, .cart_module .cart__items .items .item_info > a:hover, .cart_module .cart__items .items .item_remove span, .cart_module .cart__items .cart_info p span, .author__access_area ul li .access-link:hover, .mainmenu__menu .navbar-nav > li:hover > a, .mainmenu__menu .navbar-nav > li.menu-item .sub-menu a:hover, .place-list-wrapper ul li a:hover, .social-list li a:hover, .social-list li span.mail i, .atbd_content_active #directorist.atbd_wrapper .pagination .nav-links .page-numbers:hover, .pagination .nav-links .page-numbers:hover, .price-range.rs-primary .amount, .price-range.rs-primary .amount-four, .range-slider.rs-primary .amount, .range-slider.rs-primary .amount-four, .contents-wrapper .contents h1 span, .list-features li .list-count span, .directory_content_area #directorist.atbd_wrapper .directory_home_category_area ul.categories li a span, #directorist.atbd_wrapper .directory_home_category_area ul.categories li a span, .section-title h2 span, #directorist.atbd_wrapper .atbd_listing_action_btn .view-mode .action-btn.active, #directorist.atbd_wrapper .atbd_listing_action_btn .dropdown .btn:focus, #directorist.atbd_wrapper .atbd_listing_action_btn .dropdown .btn:active, #directorist.atbd_wrapper .atbd_generic_header_title .btn:focus, #directorist.atbd_wrapper .atbd_generic_header_title .btn:active, .atbd_location_grid_wrap .atbd_location_grid:hover, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_user_dashboard_nav .nav .nav-link.active, .atbd_saved_items_wrapper .atbdb_content_module_contents .table tr td a:hover, .atbd_saved_items_wrapper .atbdb_content_module_contents .table tr td > span, .atbd_saved_items_wrapper .atbdb_content_module_contents .table tr td .remove-favorite, .post-details .post-content ol > li:before, .post-author .author-info h5 span, .post-pagination .prev-post .title:hover, .post-pagination .next-post .title:hover, .woocommerce ul.products li.product .price, .woocommerce .woocommerce-pagination ul.page-numbers li .page-numbers:hover, .woocommerce table.shop_table .product-name a:hover, .direo_product-details .product-info .price ins, .direo_product-details .product-info .price .woocommerce-Price-amount, .woocommerce div.product .direo_product-info-tab .woocommerce-tabs ul.tabs li.active a, .iborder-primary, .outline-primary, .circle-primary, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_data_list ul p span, .widget_recent_comments ul li > a, .widget_recent_entries ul li a:hover, .menu-area .mainmenu__menu .navbar-nav > li.current-menu-item > a, .menu-area .mainmenu__menu .navbar-nav > li.current-menu-parent > a, .menu-area .mainmenu__menu .navbar-nav > li.current-menu-item .current-menu-item > a, .menu-area .mainmenu__menu .navbar-nav > li.current-menu-parent .current-menu-item > a, .ads-advanced .more-less, .ads-advanced .more-or-less, .atbd_author_listings_area .btn-outline-primary, .atbd_content_active #directorist.atbd_wrapper .atbd_contact_info ul li .atbd_info_title span, .woocommerce .woocommerce-MyAccount-navigation ul li.is-active a, .atbd_author_info_widget .btn, .footer-bottom--content p a, .post--card2 .card-body h3 a, .comment-edit-link, .comment-respond, .comment_form_wrapper a, .direo_product-details .product-info .product_meta span.posted_in a, .direo_product-details .product-info .product_meta span.tagged_as a, .direo_product-details .product-info .woocommerce-product-rating .woocommerce-review-link, .woocommerce div.product form.cart .group_table td.woocommerce-grouped-product-list-item__label a, .woocommerce-cart .cart-collaterals .shipping-calculator-button, .woocommerce .woocommerce-info a.showcoupon, .woocommerce-checkout #payment .payment_method_paypal .about_paypal, .woocommerce .woocommerce-privacy-policy-text .woocommerce-privacy-policy-link, .woocommerce .woocommerce-MyAccount-content > p a, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories > ul.atbdp_parent_category > li > a span, .atbd_sidebar .atbd_widget #loginform + p a, .post--card2 .card-body h3 a, .sidebar-post .post-single .post-title:hover, .sidebar-post .post-single P span a:hover, #directorist.atbd_wrapper .directory_regi_btn a span, .atbdp_login_form_shortcode p a, .related-post .single-post p a:hover, .menu-area .mainmenu__menu .navbar-nav > li.menu-item-has-children .current-menu-item > a, #listing-grid .action-btn.ab-grid, #listing-list .action-btn.ab-list, #listing-map .action-btn.ab-map, .post-details .post-content .post-header ul li a:hover, #directorist.atbd_wrapper .atbd_notice a, .atbd_content_active #directorist.atbd_wrapper .atbd_user_dashboard_nav .nav_button a.btn-secondary:hover, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_single_saved_item .action > p .btn a, .atbd_content_active #directorist.atbd_wrapper .atbd_saved_items_wrapper .saved_item_category a span, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_user_dashboard_nav .atbdp_tab_nav--content .atbd_tn_link.tabItemActive, .atbd_seach_fields_wrapper .atbdp-search-form .atbd_submit_btn .more-filter, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_bottom_content .atbd_content_left .atbd_listting_category .atbd_cat_popup .atbd_cat_popup_wrapper span > span:hover i, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_bottom_content .atbd_content_left .atbd_listting_category .atbd_cat_popup .atbd_cat_popup_wrapper span > span:hover a, #category-style-two #directorist.atbd_wrapper .atbd_all_categories .atbd_category_single figure figcaption .cat-box .icon span, .blog-posts__single__contents h4 a:hover, .blog-posts__single__contents ul li a:hover, .atbd_google_map .map_info_window .miw-contents-footer a, .pricing.pricing--1 .pricing__features .price_action .price_action--btn, .atbd_content_active #directorist.atbd_wrapper .atbd_generic_header .atbd_generic_header_title .more-filter:hover, .map-icon-label i, .atbd_map_shape > span, #directorist.atbd_wrapper .atbd_listing_action_btn .view-mode-2 .action-btn-2.active, #directorist.atbd_wrapper .btn-bordered:hover, .media-body .map_addr span, .map_get_dir, .woocommerce button.button, .map-info-details .map_addr span, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_bottom_content .atbd_content_left .atbd_listting_category .atbd_cat_popup .atbd_cat_popup_wrapper span:hover i, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_bottom_content .atbd_content_left .atbd_listting_category .atbd_cat_popup .atbd_cat_popup_wrapper span:hover a, #map .leaflet-popup-content .media-body .osm-iw-location span, #gmap .leaflet-popup-content .media-body .osm-iw-location span, #map .leaflet-popup-content .media-body .osm-iw-get-location span, #gmap .leaflet-popup-content .media-body .osm-iw-get-location span, #address_result ul li:before, #address_result ul li:hover a, .atbdp-res-btns .dlm-res-btn.active span, .widget.woocommerce .product-title:hover, .widget.woocommerce span.woocommerce-Price-amount, #directorist.atbd_wrapper .atbd_listing_action_btn .dropdown > #dropdownMenuLink2:hover, .ads-advanced .more-less, .ads-advanced .more-or-less, .search-area .more-less, .search-area .more-or-less, #directorist.atbd_wrapper .btn-bordered:focus, #directorist.atbd_wrapper .dbh-tab__nav__item.active, .atbdb_content_module_contents .table-inner .table tbody tr td .atbd_listing_icon ul li span i, .direo_plane_name .atpp_change_plan, .tab-content .atbd_listting_category > span, .atbd_listting_category .atbd_cat_popup .atbd_cat_popup_wrapper span i, .chiller-theme .sidebar-wrapper .sidebar-menu ul li a .active, .chiller-theme .sidebar-wrapper .sidebar-menu ul li.active a i, .chiller-theme .sidebar-wrapper .sidebar-menu ul li.active a span:not(.badge), .chiller-theme .sidebar-wrapper ul li:hover a i, .chiller-theme .sidebar-wrapper ul li:hover a span, .chiller-theme .sidebar-wrapper .sidebar-dropdown .sidebar-submenu li a:hover, .chiller-theme .sidebar-wrapper .sidebar-dropdown .sidebar-submenu li a:hover:before, .chiller-theme .sidebar-wrapper .sidebar-search input.search-menu:focus + span, .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li .active, .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li .active:before, .chiller-theme .sidebar-wrapper .sidebar-menu ul li a#v-pills-packages-tab.active, .chiller-theme .sidebar-wrapper .sidebar-menu ul li a#v-pills-history-tab.active, .related-post .single-post h6 a:hover, .btn-outline-primary, .atbd_content_active #directorist.atbd_wrapper .atbd_custom_fields_contents .atbd_custom_fields .atbd_custom_field_content p a {
            color: <?php echo esc_attr($primary); ?> !important;
        }

        .bg-primary, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_price, .atbdp-widget-categories .atbdp_parent_category li a:hover span, .atbdp-widget-tags ul li a:hover, #directorist.atbd_wrapper .sort-rating .custom-control-label span, #directorist.atbd_wrapper .submit_btn .btn-primary, #directorist.atbd_wrapper .custom-control .custom-control-input:checked ~ .check--select, .tags-widget ul li a:hover, .widget_search .search-form .search-submit, .listing-info .listing-info--meta .atbd_listing_price, #directorist.atbd_wrapper .atbd_directry_gallery_wrapper .atbd_big_gallery .prev:hover, #directorist.atbd_wrapper .atbd_directry_gallery_wrapper .atbd_big_gallery .next:hover, .atbd_review_module .review_pagination .pagination .page-item .page-link:hover, .atbd_review_module .review_pagination .pagination .page-item.active .page-link, .widget form .search-submit, .cart_module .cart__items .items .item_remove:hover span, .cart_module .cart__items .cart_info a.button.checkout, .cart_module .cart_count, .social.social--small ul li a:hover, .select2-container--default .select2-results__option--highlighted[aria-selected], .select2-container--default .select2-selection--multiple .select2-selection__choice, .atbd_content_active #directorist.atbd_wrapper #atpp-plan-change-modal .modal-content .modal-footer .btn, .atbd_content_active #directorist.atbd_wrapper .pagination .nav-links .page-numbers.current, .pagination .nav-links .page-numbers.current, .atbd_content_active #directorist.atbd_wrapper .pagination .nav-links .post-page-numbers.current .page-numbers, .pagination .nav-links .post-page-numbers.current .page-numbers, .price-range.rs-primary .ui-slider-horizontal .ui-slider-range, .range-slider.rs-primary .ui-slider-horizontal .ui-slider-range, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_user_dashboard_nav .nav .nav-link.active:before, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_user_dashboard_nav .nav_button .btn.btn-primary, .atbd_saved_items_wrapper .atbdb_content_module_contents .table tr td .remove-favorite:hover, .atbd_add_listing_wrapper input[type="checkbox"]:checked + label:before, .keep_signed input[type="checkbox"]:checked + label:before, .atbd_add_listing_wrapper label input[type="checkbox"]:checked + span:before, .keep_signed label input[type="checkbox"]:checked + span:before, .post-details .post-content .post-body p label + input[type="submit"], .post-bottom .tags ul li a:hover, .post-bottom .social-share ul li a:hover, .comments-area .comment-lists ul .depth-1 .media .media-body .media_top .reply:hover, .woocommerce ul.products li.product a.button:hover, .woocommerce ul.products li.product a.added_to_cart, .woocommerce ul.products li.product a.added_to_cart:hover, .woocommerce .woocommerce-pagination ul.page-numbers li .page-numbers.current, .woocommerce .woocommerce-pagination ul.page-numbers li .page-numbers.current:hover, .woocommerce table.shop_table td.actions .coupon button.button, .woocommerce .cart_totals .wc-proceed-to-checkout a.checkout-button, .woocommerce form.checkout .woocommerce-checkout-payment#payment .place-order button.button, .woocommerce .woocommerce-MyAccount-content .woocommerce-EditAccountForm .woocommerce-Button, .woocommerce .woocommerce-form-login .woocommerce-form-login__submit, .direo_product-details .product-info .cart .single_add_to_cart_button, .active-color-primary label input:checked + span, .checkbox-primary .custom-control-label::before, .checkbox-primary .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-primary .custom-control-input:checked ~ .custom-control-label::before, .atbdp-widget-listing-contact #atbdp-contact-form .btn-primary, .atbd_author_listings_area .btn-outline-primary:hover, .woocommerce div.product .direo_product-info-tab .woocommerce-tabs .woocommerce-Tabs-panel .woocommerce-Reviews #review_form_wrapper .comment_form_wrapper .form-submit input.btn, .atbd_author_info_widget .btn:hover, .btn-primary, .blog-single.sticky .post--card figure:before, .woocommerce .woocommerce-MyAccount-content .woocommerce-address-fields button[name="save_address"], .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp.atbdp-widget-tags ul li a:hover, .atbd_content_active .widget.atbd_widget + #dcl-claim-modal .modal-footer .btn, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .directorist .dcl_promo-item_group .btn, #directorist.atbd_wrapper .btn-primary, .atbd_sidebar .atbd_widget #loginform .login-submit .button-primary, .woocommerce table.shop_table td .button.view, .woocommerce .woocommerce-pagination .button, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories > ul.atbdp_parent_category > li:hover > a span, .atbdp_login_form_shortcode #loginform p input[type="submit"], .blog-single.sticky .card .card-body h3:before, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_author_info_widget .atbd_social_wrap p a:hover, #directorist.atbd_wrapper .dropdown-item.active, .woocommerce form.lost_reset_password button.woocommerce-Button, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_single_saved_item .action > p .btn:hover, .widget.atbd_widget .default-ad-search .submit_btn .btn, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_user_dashboard_nav .atbdp_tab_nav--content .atbd_tn_link.tabItemActive:before, .atbd_seach_fields_wrapper .atbdp-search-form .atbd_submit_btn .more-filter:hover, #category-style-two #directorist.atbd_wrapper .atbd_all_categories .atbd_category_single:hover figure figcaption .cat-box .icon, .ads-advanced .price-frequency .pf-btn input:checked + span, .pricing.pricing--1.atbd_pricing_special .pricing__features label .price_action--btn, .pricing.pricing--1 .pricing__features .price_action .price_action--btn:hover, #directorist.atbd_wrapper .btn-outline-primary:hover, .listing-carousel .owl-dots .owl-dot.active, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic, .direo-btn, .modal-body .az-fb-login-btn:hover, .atbd_map_shape, #directorist .bdmv-pagination .page-numbers li span.current, .woocommerce button.button:hover, .atbdpr-range.rs-primary .ui-slider-horizontal .ui-slider-range, .atbd_content_active #directorist.atbd_wrapper .atbd_single_listing .atbdp_mark_as_fav.atbdp_fav_isActive, .leaflet-pane .marker-cluster-small > div, .mobile-login a, .mobile-add-listing a, .quick-search .btn_search, #atpp-plan-change-modal .atm-contents-inner .at-modal-close, #dwpp-plan-renew-modal .atm-contents-inner .at-modal-close, #atpp-plan-change-modal .atm-contents-inner .atbd_modal-footer .atbd_modal_btn, #dwpp-plan-renew-modal .atm-contents-inner .atbd_modal-footer .atbd_modal_btn, .marker-cluster-shape, .widget.woocommerce .woocommerce-product-search button, #directorist.atbd_wrapper .dropdown-item:active, #directorist.atbd_wrapper .ezmu__btn, .profile-img .choose_btn #upload_pro_pic, .atbd_author_info_widget .atbd_social_wrap p a:hover, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn,
        #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default,
        .atbdp_login_form_shortcode #loginform p input[type="submit"],
        .pricing .price_action .price_action--btn, #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic, #directorist.atbd_wrapper .at-modal .at-modal-close, .atbdp_login_form_shortcode #loginform p input[type="submit"], .atbd_manage_fees_wrapper .table tr .action p .btn-block:hover, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn, .atbdp_login_form_shortcode #login p input[type="submit"] {
            background: <?php echo esc_attr($primary); ?> !important;
        }

        .active-color-primary label input:checked + span, .atbdp-widget-listing-contact #atbdp-contact-form .btn-primary, #directorist.atbd_wrapper .custom-control .custom-control-input:checked ~ .radio--select,
        #directorist.atbd_wrapper .custom-control .custom-control-input:checked ~ .check--select, .tags-widget ul li a:hover, #directorist.atbd_wrapper .btn-primary, .atbd_content_active #directorist.atbd_wrapper #atpp-plan-change-modal .modal-content .modal-footer .btn, #directorist.atbd_wrapper .atbd_listing_action_btn .view-mode .action-btn.active, #directorist.atbd_wrapper .atbd_listing_action_btn .dropdown .btn:focus, #directorist.atbd_wrapper .atbd_listing_action_btn .dropdown .btn:active, #directorist.atbd_wrapper .atbd_generic_header_title .btn:focus, #directorist.atbd_wrapper .atbd_generic_header_title .btn:active, .atbd_author_listings_area .btn-outline-primary, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_user_dashboard_nav .nav_button .btn.btn-primary, .atbd_add_listing_wrapper input[type="checkbox"]:checked + label:before, .keep_signed input[type="checkbox"]:checked + label:before, .atbd_add_listing_wrapper label input[type="checkbox"]:checked + span:before, .keep_signed label input[type="checkbox"]:checked + span:before, .atbd_add_listing_wrapper label input[type="radio"]:checked + span.cf-select:after,
        .atbd_add_listing_wrapper label input[type="radio"]:checked + .atbdp_make_str_green + .cf-select:after, .keep_signed label input[type="radio"]:checked + span.cf-select:after,
        .keep_signed label input[type="radio"]:checked + .atbdp_make_str_green + .cf-select:after, .comments-area .comment-lists ul .depth-1 .media .media-body .media_top .reply:hover, .post-bottom .tags ul li a:hover, .woocommerce .woocommerce-pagination ul.page-numbers li .page-numbers.current, .woocommerce .woocommerce-pagination ul.page-numbers li .page-numbers:hover, .woocommerce .woocommerce-MyAccount-navigation ul li.is-active, .atbd_author_info_widget .btn:hover, .btn-primary, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp.atbdp-widget-tags ul li a:hover, .atbd_content_active .widget.atbd_widget + #dcl-claim-modal .modal-footer .btn:hover, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .directorist .dcl_promo-item_group .btn, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories > ul.atbdp_parent_category > li:hover > a span, #listing-grid .action-btn.ab-grid, #listing-list .action-btn.ab-list, #listing-map .action-btn.ab-map, .atbd_content_active #directorist.atbd_wrapper .atbd_user_dashboard_nav .nav_button a.btn-secondary:hover, .widget.atbd_widget .default-ad-search .submit_btn .btn, .ads-advanced .price-frequency .pf-btn input:checked + span, #directorist.atbd_wrapper .btn-outline-primary, #directorist.atbd_wrapper .btn-outline-primary:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_generic_header .atbd_generic_header_title .more-filter:hover, #directorist.atbd_wrapper .atbd_listing_action_btn .view-mode-2 .action-btn-2.active, .map_get_dir, #directorist .bdmv-pagination .page-numbers li span, .woocommerce button.button, .atbdpr-range.rs-primary .ui-slider-horizontal .ui-slider-handle, #directorist.atbd_wrapper .submit_btn .btn-default:hover, .atbdp-res-btns .dlm-res-btn.active, #atpp-plan-change-modal .atm-contents-inner .dcl_pricing_plan input:checked + label:before, #dwpp-plan-renew-modal .atm-contents-inner .dcl_pricing_plan input:checked + label:before, #directorist.atbd_wrapper .dbh-tab__nav__item.active, .dbh-checkbox input[type="radio"]:checked + label:before, .btn-outline-primary, .atbdp_login_form_shortcode #login p input[type="submit"] {
            border-color: <?php echo esc_attr($primary); ?> !important;
        }

        .border-primary, .atbdp-widget-tags ul li a:hover, .listing-info .atbd_listing_action_area .atbd_action:hover, .atbd_review_module .review_pagination .pagination .page-item .page-link:hover, .atbd_review_module .review_pagination .pagination .page-item.active .page-link, .select2-container--default .select2-selection--multiple .select2-selection__choice, .atbd_content_active #directorist.atbd_wrapper .pagination .nav-links .page-numbers:hover, .pagination .nav-links .page-numbers:hover, .atbd_content_active #directorist.atbd_wrapper .pagination .nav-links .page-numbers.current, .pagination .nav-links .page-numbers.current, .atbd_content_active #directorist.atbd_wrapper .pagination .nav-links .post-page-numbers.current .page-numbers, .pagination .nav-links .post-page-numbers.current .page-numbers, .outline-primary, .checkbox-primary .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-primary .custom-control-input:checked ~ .custom-control-label::before, #directorist.atbd_wrapper .submit_btn .btn-primary, .woocommerce ul.products li.product a.added_to_cart:hover, .woocommerce ul.products li.product a.button:hover, .woocommerce ul.products li.product a.added_to_cart, .post-details .post-content ul > li:before, .atbd_author_info_widget .btn, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_single_saved_item .action > p .btn, .atbd_seach_fields_wrapper .atbdp-search-form .atbd_submit_btn .more-filter, .pricing.pricing--1 .pricing__features .price_action .price_action--btn, .pricing.pricing--1.atbd_pricing_special .pricing__features label .price_action--btn, .atbd_map_shape, #directorist.atbd_wrapper .btn-outline.btn-outline-primary:hover {
            border: 1px solid <?php echo esc_attr($primary); ?> !important;
        }

        .woocommerce div.product .direo_product-info-tab .woocommerce-tabs ul.tabs li.active, #directorist.atbd_wrapper.atbd_add_listing_wrapper .select2-selection .select2-selection__rendered .select2-selection__choice {
            border-bottom: 1px solid <?php echo esc_attr($primary); ?> !important;
        }

        .outline-lg-primary {
            border: 2px solid<?php echo esc_attr($primary); ?>;
        }

        .atbd_content_active #directorist.atbd_wrapper #atpp-plan-change-modal .modal-content .dcl_pricing_plan input:checked + label:before {
            border: 5px solid<?php echo esc_attr($primary); ?>;
        }

        .atbd_map_shape:before, .atbd_map_shape:before {
            border-top-color: <?php echo esc_attr($primary); ?>;
        }

        .media-body blockquote {
            border-left: 2px solid<?php echo esc_attr($primary); ?>;
        }

        #directorist.atbd_wrapper .btn-bordered.active, #directorist.atbd_wrapper .btn-bordered:hover {
            border-color: <?php echo esc_attr($primary); ?> !important;
        }

        <?php
    }

    if('#903af9' != $secondary){?>
        /* Colors: Secondary */
        .color-secondary, .post--card .card-body .post-meta li a, .post--card2 .card-body h3 a:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_widget .atbd_widget_title .atbd_widget_title a, .atbd_categorized_listings .listings > li .cate_title h4 a:hover, .atbd_categorized_listings .listings > li .directory_tag span a:hover, .atbd_contact_information_module .atbd_contact_info ul .atbd_info_title span, .atbd_review_module #client_review_list .atbd_single_review .review_content .reply, .atbd_review_form .atbd_give_review_area .atbd_notice a, #atbdp_review_form .atbd_upload_btn_wrap .atbd_upload_btn span, .default-ad-search .filter-checklist .show-link:hover, .price-range .amount, .price-range .amount-four, .iborder-secondary, .outline-secondary, .circle-secondary, .outline-lg-secondary, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_bottom_content .atbd_content_left .atbd_listting_category a span, .blog-posts__single__contents ul li a[rel="category tag"] {
            color: <?php echo esc_attr($secondary); ?> !important;
        }

        .bg-secondary, .menu-area .author__notification_area ul li .notification_count.purch, .cart_module .cart__items .cart_info a.button, .author__access_area ul li .author-info ul li a:hover, .offcanvas-menu .offcanvas-menu__contents ul li a:hover, .price-range .ui-slider-horizontal .ui-slider-range, .range-slider .ui-slider-horizontal .ui-slider-range, #atbdp_socialInFo .dashicons.adl-move-icon:before, .atbdp_faqs_wrapper .dashicons.adl-move-icon:before, .woocommerce table.shop_table td.actions button[name="update_cart"], .woocommerce .woocommerce-shipping-calculator .shipping-calculator-form p button[name="calc_shipping"], .woocommerce form.checkout_coupon .form-row .button, .woocommerce .woocommerce-form-login .woocommerce-form-login__submit:hover, .active-color-secondary label input:checked + span, .checkbox-secondary .custom-control-label::before, .checkbox-secondary .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-secondary .custom-control-input:checked ~ .custom-control-label::before, #directorist.atbd_wrapper .btn-secondary, .direo_product-details .product-info form.variations_form .variations .reset_variations, .btn-secondary, .woocommerce .return-to-shop a.wc-backward {
            background: <?php echo esc_attr($secondary); ?> !important;
        }

        .active-color-secondary label input:checked + span, #directorist.atbd_wrapper .btn-secondary, #directorist.atbd_wrapper .btn-secondary {
            border-color: <?php echo esc_attr($secondary); ?> !important;
        }

        .border-secondary, .outline-secondary, .checkbox-secondary .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-secondary .custom-control-input:checked ~ .custom-control-label::before {
            border: 1px solid <?php echo esc_attr($secondary); ?> !important;
        }

        .outline-lg-secondary {
            border: 2px solid<?php echo esc_attr($secondary); ?>;
        }

        <?php }?>
        <?php if('#32cc6f' != $success){?>
        /* Colors: Success */
        .color-success, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_badge_open, .atbd_author_info_widget .atbd_avatar_wrapper .atbd_name_time .review_time, .directory_open_hours ul li.atbd_today span, .pricing.pricing--1 .pricing__features ul li > span.available:first-child, .pricing.pricing--1 .pricing__features ul li .atbd_color-success, #directorist.atbd_wrapper.dashboard_area .atbd_dashboard_wrapper .atbd_listing_bottom_content .listing-meta #atpp_change_plan, .woocommerce .woocommerce-message:before, .woocommerce ul.products li.product a.button.added, .woocommerce .woocommerce-order .woocommerce-thankyou-order-received, .iborder-success, .outline-success, .circle-success, .outline-lg-success, .pricing.pricing--1 .pricing__features ul li span.fa-check, .atbd_listing_bottom_content .listing-meta p span, #directorist.atbd_wrapper span a.atpp_change_plan {
            color: <?php echo esc_attr($success); ?> !important;
        }

        .bg-success, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_rating, .badge-verified:before, .atbd_author_info_widget .atbd_avatar_wrapper .atbd_name_time h4 .verified, .listing-info .listing-info--meta .atbd_listing_rating, .pricing.pricing--1 .pricing__title h4 .atbd_plan-active, #directorist.atbd_wrapper .atbd_auhor_profile_area .atbd_author_meta .atbd_listing_meta .atbd_listing_rating, .woocommerce ul.products li.product .onsale, .direo_product-details .gallery-image-view .onsale, .active-color-success label input:checked + span, .checkbox-outline-success .custom-control-input:checked ~ .custom-control-label::before, .checkbox-success .custom-control-input:checked ~ .custom-control-label::before, .checkbox-success .custom-control-label::before, .badge-success, .btn-success, .atbd_sidebar .widget.atbd_widget .atbd_widget_title span.atbd_badge_open, .listing-info .diero_single_listing_title .dcl_claimed .dcl_claimed--badge span, #directorist.atbd_wrapper .atbd_single_saved_item .package_name .atpp_plan_renew {
            background: <?php echo esc_attr($success); ?> !important;
        }

        .border-success, .listing-info .listing-info--meta .atbd_listing_rating, .outline-success, .checkbox-outline-success .custom-control-input:checked ~ .custom-control-label::before, .checkbox-success .custom-control-input:checked ~ .custom-control-label::before {
            border: 1px solid <?php echo esc_attr($success); ?> !important;
        }

        .active-color-success label input:checked + span, .btn-success, #directorist.atbd_wrapper .btn-success {
            border-color: <?php echo esc_attr($success); ?>;
        }

        .outline-lg-success {
            border: 2px solid<?php echo esc_attr($success); ?>;
        }

        .woocommerce .woocommerce-message {
            border-top-color: <?php echo esc_attr($success); ?>;
        }

        <?php } ?>

        <?php if('#3a7dfd' != $info){ ?>
        /* Colors: Info */
        .color-info, .post-pagination .prev-post p a, .post-pagination .next-post p a, .woocommerce .woocommerce-info:before, .iborder-info, .outline-info, .circle-info, .outline-lg-info, #directorist.dashboard_area .tab-content .db_btn_area .directory_edit_btn {
            color: <?php echo esc_attr($info); ?> !important;
        }

        .bg-info, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_thumbnail_area .atbd_upper_badge .atbd_badge.atbd_badge_new, .listing-info .listing-info--badges .atbd_badge.atbd_badge_new, .atbd_review_form .atbd_give_review_area .atbd_notice span, .woocommerce .woocommerce-order .woocommerce-thankyou-order-details + p:before, .active-color-info label input:checked + span, .checkbox-info .custom-control-label::before, .checkbox-info .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-info .custom-control-input:checked ~ .custom-control-label::before, .btn-info, #directorist.dashboard_area .tab-content .db_btn_area .directory_edit_btn:hover, .woocommerce .woocommerce-info .button, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_thumbnail_area .atbd_lower_badge .atbd_badge.atbd_badge_new {
            background: <?php echo esc_attr($info); ?> !important;
        }

        .outline-info, .border-info, .checkbox-info .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-info .custom-control-input:checked ~ .custom-control-label::before, #directorist.dashboard_area .tab-content .db_btn_area .directory_edit_btn {
            border: 1px solid <?php echo esc_attr($info); ?> !important;
        }

        .active-color-info label input:checked + span, .btn-info {
            border-color: <?php echo esc_attr($info); ?>;
        }

        .outline-lg-info {
            border: 2px solid<?php echo esc_attr($info); ?>;
        }

        .woocommerce .woocommerce-info {
            border-top-color: <?php echo esc_attr($info); ?>;
        }

        <?php }?>

        <?php if('#fa8b0c' != $warnning){?>
        /* Colors Info */
        .color-warning, .atbd_categorized_listings .listings > li .atbd_rated_stars ul li span.rate_active:before, .atbd_review_module #client_review_list .atbd_single_review .atbd_review_top .atbd_rated_stars ul li, .iborder-warning, .outline-warning, .circle-warning, .outline-lg-warning, .comments-area .comment-lists ul .depth-1 .media .media-body .comment-status-notice {
            color: <?php echo esc_attr($warnning); ?> !important;
        }

        .bg-warning, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_thumbnail_area .atbd_upper_badge .atbd_badge.atbd_badge_featured, .listing-info .listing-info--badges .atbd_badge.atbd_badge_featured, .active-color-warning label input:checked + span, .checkbox-warning .custom-control-label::before, .checkbox-warning .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-warning .custom-control-input:checked ~ .custom-control-label::before, .btn-warnning, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_featured {
            background: <?php echo esc_attr($warnning); ?> !important;
        }

        .outline-warning, .border-warning, .checkbox-warning .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-warning .custom-control-input:checked ~ .custom-control-label::before {
            border: 1px solid <?php echo esc_attr($warnning); ?> !important;
        }

        .active-color-warning label input:checked + span, .btn-warnning {
            border-color: <?php echo esc_attr($warnning); ?>;
        }

        .outline-lg-warning {
            border: 2px solid<?php echo esc_attr($warnning); ?>;
        }

        /* Ratings Color */
        .woocommerce ul.products li.product .star-rating span,
        .direo_product-details .product-info .woocommerce-product-rating .star-rating,
        .direo_product-details .product-info .woocommerce-product-rating .star-rating > span:before,
        .woocommerce div.product .direo_product-info-tab .woocommerce-tabs .woocommerce-Tabs-panel .woocommerce-Reviews .commentlist li .comment-text .star-rating, .woocommerce div.product .direo_product-info-tab .woocommerce-tabs .woocommerce-Tabs-panel .woocommerce-Reviews .commentlist li .comment-text .star-rating > span:before, .woocommerce div.product .direo_product-info-tab .woocommerce-tabs .woocommerce-Tabs-panel .woocommerce-Reviews #review_form_wrapper .comment_form_wrapper .comment-form-rating .stars span a, #directorist.atbd_wrapper .atbd_rated_stars ul li span.rate_active, .atbd_rating_color, #directorist.atbd_wrapper .atbd_rated_stars ul li span.rate_active, #directorist.atbd_wrapper .atbd_rating_stars .br-widget a.br-selected:before, #directorist.atbd_wrapper .atbd_rating_stars .br-widget a.br-active:before {
            color: <?php echo esc_attr($warnning); ?>;
        }

        <?php }?>

        <?php if('#fd4868' != $danger){?>
        /* Colors: Danger */
        .color-danger, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_badge_close, .directory_open_hours ul li.atbd_closed span, .pricing.pricing--1 .pricing__features ul li > span.unavailable:first-child, #login_modal .status span, .woocommerce .woocommerce-error:before, .iborder-danger, .outline-danger, .circle-danger, .outline-lg-primary, .outline-lg-danger, #directorist.atbd_wrapper .atbd_directory_open_hours ul li.atbd_closed, #directorist.dashboard_area .tab-content .db_btn_area .directory_remove_btn, .woocommerce a.remove, #directorist.atbd_wrapper .btn-outline-danger , .btn-outline-danger{
            color: <?php echo esc_attr($danger); ?> !important;
        }

        .bg-danger, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_thumbnail_area .atbd_upper_badge .atbd_badge.atbd_badge_popular, .post--card2 figure figcaption a, .listing-info .listing-info--badges .atbd_badge.atbd_badge_popular, .play-btn, #atbdp_socialInFo .dashicons.removeSocialField:before, .atbdp_faqs_wrapper .dashicons.removeFAQSField:before, .active-color-danger label input:checked + span, .checkbox-danger .custom-control-label::before, .checkbox-danger .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-danger .custom-control-input:checked ~ .custom-control-label::before, #directorist.atbd_wrapper .btn-danger, .btn-danger, #directorist.dashboard_area .tab-content .db_btn_area .directory_remove_btn:hover, .atbd_sidebar .widget.atbd_widget .atbd_widget_title span.atbd_badge_close, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module .atbd_badge.atbd_badge_close, .woocommerce a.remove:hover, #directorist.atbd_wrapper .btn-outline-danger:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_popular {
            background: <?php echo esc_attr($danger); ?> !important;
        }

        .outline-danger, .border-danger, .checkbox-danger .custom-control-input:checked ~ .custom-control-label::before, .checkbox-outline-danger .custom-control-input:checked ~ .custom-control-label::before, #directorist.dashboard_area .tab-content .db_btn_area .directory_remove_btn {
            border: 1px solid <?php echo esc_attr($danger); ?> !important;
        }

        .active-color-danger label input:checked + span, #directorist.atbd_wrapper .btn-danger, #directorist.atbd_wrapper .btn-outline-danger, .btn-outline-danger {
            border-color: <?php echo esc_attr($danger); ?> !important;
        }

        .outline-lg-danger {
            border: 2px solid<?php echo esc_attr($danger); ?>;
        }

        .woocommerce .woocommerce-error {
            border-top-color: <?php echo esc_attr($danger); ?>;
        }

        <?php }?>

        <?php if('#903af9' != $secondary || '#f5548e' != $primary){ ?>
        /* Gradient BG */
        .bg-gradient-ps, .atbdp_float_active {
            background: <?php echo esc_attr($secondary); ?>;
            background: -webkit-linear-gradient(to right, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: -o-linear-gradient(to right, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($secondary); ?>), to(<?php echo esc_attr($primary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr=<?php echo esc_attr($secondary); ?>, endColorstr=<?php echo esc_attr($primary); ?>);
        }

        .btn-gradient.btn-gradient-one {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($primary); ?>), to(<?php echo esc_attr($secondary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
        }

        .btn-gradient.btn-gradient-one:before {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($secondary); ?>), to(<?php echo esc_attr($primary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
        }

        blockquote {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($primary); ?>), to(<?php echo esc_attr($secondary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
        }

        blockquote.wp-block-quote {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($primary); ?>), to(<?php echo esc_attr($secondary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
        }

        #directorist.atbd_wrapper .atbd_submit_btn .btn_search {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($primary); ?>), to(<?php echo esc_attr($secondary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($secondary); ?>);
        }

        #directorist.atbd_wrapper .atbd_submit_btn .btn_search:active, #directorist.atbd_wrapper .atbd_submit_btn .btn_search:focus {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($secondary); ?>), to(<?php echo esc_attr($primary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
        }

        #directorist.atbd_wrapper .atbd_submit_btn .btn_search:hover:before {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($secondary); ?>), to(<?php echo esc_attr($primary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($secondary); ?>, <?php echo esc_attr($primary); ?>);
        }

        <?php }?>

        <?php if('#fa8b0c' != $warnning || '#f5548e' != $primary){ ?>
        .bg-gradient-pw, .pricing.pricing--1.atbd_pricing_special .atbd_popular_badge {
            background: <?php echo esc_attr($primary); ?>;
            background: -webkit-linear-gradient(to right, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($warnning); ?>);
            background: -o-linear-gradient(to right, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($warnning); ?>);
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($primary); ?>), to(<?php echo esc_attr($warnning); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($warnning); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($warnning); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($warnning); ?>);
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr=<?php echo esc_attr($primary); ?>, endColorstr=<?php echo esc_attr($warnning); ?>);
        }

        .btn-gradient.btn-gradient-two {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($primary); ?>), to(<?php echo esc_attr($warnning); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($warnning); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($warnning); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($primary); ?>, <?php echo esc_attr($warnning); ?>);
        }

        .btn-gradient.btn-gradient-two:before {
            background: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($warnning); ?>), to(<?php echo esc_attr($primary); ?>));
            background: -webkit-linear-gradient(left, <?php echo esc_attr($warnning); ?>, <?php echo esc_attr($primary); ?>);
            background: -o-linear-gradient(left, <?php echo esc_attr($warnning); ?>, <?php echo esc_attr($primary); ?>);
            background: linear-gradient(to right, <?php echo esc_attr($warnning); ?>, <?php echo esc_attr($primary); ?>);
        }

        <?php } ?>

        <?php if('rgba(144, 58, 249, 0.85))' != $secondary_g || 'rgba(245, 84, 142, 0.85)' != $primary_g){ ?>

        .atbd_content_active #directorist.atbd_wrapper .atbd_category_single figure figcaption:before {
            background: -webkit-linear-gradient(45deg, <?php echo esc_attr($primary_g); ?>, <?php echo esc_attr($secondary_g); ?>);
            background: -o-linear-gradient(45deg, <?php echo esc_attr($primary_g); ?>, <?php echo esc_attr($secondary_g); ?>);
            background: linear-gradient(45deg, <?php echo esc_attr($primary_g); ?>, <?php echo esc_attr($secondary_g); ?>);
        }

        .atbd_content_active #directorist.atbd_wrapper .atbd_location_grid_wrap .atbd_location_grid figure figcaption:before {
            background: -webkit-linear-gradient(45deg, <?php echo esc_attr($primary_g); ?> 0%, <?php echo esc_attr($secondary_g); ?> 100%);
            background: -ms-linear-gradient(45deg, <?php echo esc_attr($primary_g); ?> 0%, <?php echo esc_attr($secondary_g); ?> 100%);
        }
        .leaflet-pane .marker-cluster-small {
            background-color: <?php echo esc_attr($primary_g); ?> !important;
        }
        <?php  } ?>

    </style>

    <?php
}

add_action('wp_head', 'direo_custom_style');
