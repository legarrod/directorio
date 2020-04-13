<?php
if( ! isset( $_POST['post_type'] ) ) {
    return $post_id;
}


// If this is an autosave, our form has not been submitted, so we don't want to do anything
if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
    return $post_id;
}

// Check the logged in user has permission to edit this post
if( ! current_user_can( 'edit_post', $post_id ) ) {
    return $post_id;
}

// Check if "atbdp_fee_details_nonce" nonce is set
if( isset( $_POST['atbdp_fee_details_nonce'] ) ) {

    // Verify that the nonce is valid
    if( wp_verify_nonce( $_POST['atbdp_fee_details_nonce'], 'atbdp_save_fee_details' ) ) {


        // OK to save meta data
        $plan_type =  !empty($_POST['plan_type'])?$_POST['plan_type']:'';
        update_post_meta($post_id, 'plan_type', $plan_type);

        $fm_price =  !empty($_POST['fm_price'])?$_POST['fm_price']:'';
        update_post_meta($post_id, 'fm_price', $fm_price);

        $price_decimal = isset($_POST['price_decimal']) ? (int)$_POST['price_decimal'] : '0';
        update_post_meta($post_id, 'price_decimal', $price_decimal);

        $hide_description = isset($_POST['hide_description'])?sanitize_textarea_field($_POST['hide_description']):'';
        update_post_meta($post_id, 'hide_description', $hide_description);

        $fm_description = isset($_POST['fm_description'])?sanitize_textarea_field($_POST['fm_description']):'';
        update_post_meta($post_id, 'fm_description', $fm_description);

        $fm_length = isset($_POST['fm_length']) ? (int)$_POST['fm_length'] : '0';
        update_post_meta($post_id, 'fm_length', $fm_length);

        $fm_length_unl = isset($_POST['fm_length_unl'])?sanitize_text_field($_POST['fm_length_unl']):'';
        update_post_meta($post_id, 'fm_length_unl', $fm_length_unl);

        $fm_length_unl = isset($_POST['is_recurring'])?sanitize_text_field($_POST['is_recurring']):'';
        update_post_meta($post_id, '_atpp_recurring', $fm_length_unl);

       $recurrence_period_term = isset($_POST['recurrence_period_term'])?sanitize_text_field($_POST['recurrence_period_term']):'';
        update_post_meta($post_id, '_recurrence_period_term', $recurrence_period_term);

        $recurrence_time = isset($_POST['recurrence_time'])?sanitize_text_field($_POST['recurrence_time']):'';
        update_post_meta($post_id, '_recurrence_time', $recurrence_time);

        $is_featured_listing = isset($_POST['is_featured_listing'])?($_POST['is_featured_listing']):'';
        update_post_meta($post_id, 'is_featured_listing', $is_featured_listing);

        $hide_listing_featured = isset($_POST['hide_listing_featured'])?($_POST['hide_listing_featured']):'';
        update_post_meta($post_id, 'hide_listing_featured', $hide_listing_featured);

        $num_regular = isset($_POST['num_regular'])?(int)($_POST['num_regular']):'';
        update_post_meta($post_id, 'num_regular', $num_regular);

        $hide_listings = isset($_POST['hide_listings'])?sanitize_text_field($_POST['hide_listings']):'';
        update_post_meta($post_id, 'hide_listings', $hide_listings);

        $num_regular_unl = isset($_POST['num_regular_unl'])?sanitize_text_field($_POST['num_regular_unl']):'';
        update_post_meta($post_id, 'num_regular_unl', $num_regular_unl);

        $num_featured = isset($_POST['num_featured'])?(int)($_POST['num_featured']):'';
        update_post_meta($post_id, 'num_featured', $num_featured);

        $num_featured_unl = isset($_POST['num_featured_unl'])?sanitize_text_field($_POST['num_featured_unl']):'';
        update_post_meta($post_id, 'num_featured_unl', $num_featured_unl);

        $hide_featured = isset($_POST['hide_featured'])?sanitize_text_field($_POST['hide_featured']):'';
        update_post_meta($post_id, 'hide_featured', $hide_featured);

        $fm_allow_price = isset($_POST['fm_allow_price'])?sanitize_text_field($_POST['fm_allow_price']):'';
        update_post_meta($post_id, 'fm_allow_price', $fm_allow_price);

        $price_range = (int)$_POST['price_range'] ? (int)$_POST['price_range'] : '';
        update_post_meta($post_id, 'price_range', $price_range);

        $price_range_unl = isset($_POST['price_range_unl']) ? sanitize_text_field($_POST['price_range_unl']) : '';
        update_post_meta($post_id, 'price_range_unl', $price_range_unl);

        $fm_allow_price_range = isset($_POST['fm_allow_price_range'])?sanitize_text_field($_POST['fm_allow_price_range']):'';
        update_post_meta($post_id, 'fm_allow_price_range', $fm_allow_price_range);

        $hide_price_range = isset($_POST['hide_price_range']) ? sanitize_text_field($_POST['hide_price_range']) : '';
        update_post_meta($post_id, 'hide_price_range', $hide_price_range);

        $hide_price_limit = isset($_POST['hide_price_limit']) ? sanitize_text_field($_POST['hide_price_limit']) : '';
        update_post_meta($post_id, 'hide_price_limit', $hide_price_limit);

        $num_image = (int)($_POST['num_image']) ? (int)($_POST['num_image']) : '0';
        update_post_meta($post_id, 'num_image', $num_image);

        $num_image_unl = isset($_POST['num_image_unl']) ? sanitize_text_field($_POST['num_image_unl']) : '';
        update_post_meta($post_id, 'num_image_unl', $num_image_unl);

        $fm_allow_slider = isset($_POST['fm_allow_slider']) ? sanitize_text_field($_POST['fm_allow_slider']) : '';
        update_post_meta($post_id, 'fm_allow_slider', $fm_allow_slider);

        $hide_image = isset($_POST['hide_image']) ? sanitize_text_field($_POST['hide_image']) : '';
        update_post_meta($post_id, 'hide_image', $hide_image);

        $fm_allow_tag = isset($_POST['fm_allow_tag']) ? sanitize_text_field($_POST['fm_allow_tag']) : '';
        update_post_meta($post_id, 'fm_allow_tag', $fm_allow_tag);

        $fm_tag_limit = isset($_POST['fm_tag_limit']) ? (int)($_POST['fm_tag_limit']) : '0';
        update_post_meta($post_id, 'fm_tag_limit', $fm_tag_limit);

        $fm_tag_limit_unl = isset($_POST['fm_tag_limit_unl']) ? sanitize_text_field($_POST['fm_tag_limit_unl']) : '';
        update_post_meta($post_id, 'fm_tag_limit_unl', $fm_tag_limit_unl);

        $hide_tag_limit = isset($_POST['hide_tag_limit']) ? sanitize_text_field($_POST['hide_tag_limit']) : '';
        update_post_meta($post_id, 'hide_tag_limit', $hide_tag_limit);

        $business_hrs = isset($_POST['business_hrs'])?sanitize_text_field($_POST['business_hrs']):'';
        update_post_meta($post_id, 'business_hrs', $business_hrs);

        $hide_BH = isset($_POST['hide_BH'])?sanitize_text_field($_POST['hide_BH']):'';
        update_post_meta($post_id, 'hide_BH', $hide_BH);

        $default_pln = isset($_POST['default_pln'])?sanitize_text_field($_POST['default_pln']):'';
        update_post_meta($post_id, 'default_pln', $default_pln);

        $hide_from_plans = isset($_POST['hide_from_plans'])?sanitize_text_field($_POST['hide_from_plans']):'';
        update_post_meta($post_id, '_hide_from_plans', $hide_from_plans);

        $atfm_listing_gallery = isset($_POST['atfm_listing_gallery'])?sanitize_text_field($_POST['atfm_listing_gallery']):'';
        update_post_meta($post_id, 'atfm_listing_gallery', $atfm_listing_gallery);

        $hide_Lgallery = isset($_POST['hide_Lgallery'])?sanitize_text_field($_POST['hide_Lgallery']):'';
        update_post_meta($post_id, 'hide_Lgallery', $hide_Lgallery);

        $hide_gallery_image_limit = isset($_POST['hide_gallery_image_limit'])?sanitize_text_field($_POST['hide_gallery_image_limit']):'';
        update_post_meta($post_id, 'hide_gallery_image_limit', $hide_gallery_image_limit);

        $num_gallery_image = (int)($_POST['num_gallery_image']) ? (int)($_POST['num_gallery_image']) : '0';
        update_post_meta($post_id, 'num_gallery_image', $num_gallery_image);

        $num_gallery_image_unl = isset($_POST['num_gallery_image_unl']) ? sanitize_text_field($_POST['num_gallery_image_unl']) : '';
        update_post_meta($post_id, 'num_gallery_image_unl', $num_gallery_image_unl);

        $l_video = isset($_POST['l_video'])?sanitize_text_field($_POST['l_video']):'';
        update_post_meta($post_id, 'l_video', $l_video);

        $hide_video = isset($_POST['hide_video'])?sanitize_text_field($_POST['hide_video']):'';
        update_post_meta($post_id, 'hide_video', $hide_video);

        $cf_owner = isset($_POST['cf_owner'])?sanitize_text_field($_POST['cf_owner']):'';
        update_post_meta($post_id, 'cf_owner', $cf_owner);

        $hide_Cowner = isset($_POST['hide_Cowner'])?sanitize_text_field($_POST['hide_Cowner']):'';
        update_post_meta($post_id, 'hide_Cowner', $hide_Cowner);

        $fm_email = isset($_POST['fm_email'])?sanitize_text_field($_POST['fm_email']):'';
        update_post_meta($post_id, 'fm_email', $fm_email);

        $hide_email = isset($_POST['hide_email'])?sanitize_text_field($_POST['hide_email']):'';
        update_post_meta($post_id, 'hide_email', $hide_email);

        $fm_phone = isset($_POST['fm_phone'])?sanitize_text_field($_POST['fm_phone']):'';
        update_post_meta($post_id, 'fm_phone', $fm_phone);

        $hide_phone = isset($_POST['hide_phone'])?sanitize_text_field($_POST['hide_phone']):'';
        update_post_meta($post_id, 'hide_phone', $hide_phone);

        $fm_web_link = isset($_POST['fm_web_link'])?sanitize_text_field($_POST['fm_web_link']):'';
        update_post_meta($post_id, 'fm_web_link', $fm_web_link);

        $hide_webLink = isset($_POST['hide_webLink'])?sanitize_text_field($_POST['hide_webLink']):'';
        update_post_meta($post_id, 'hide_webLink', $hide_webLink);

        $fm_social_network = isset($_POST['fm_social_network'])?sanitize_text_field($_POST['fm_social_network']):'';
        update_post_meta($post_id, 'fm_social_network', $fm_social_network);

        $hide_Snetwork = isset($_POST['hide_Snetwork'])?sanitize_text_field($_POST['hide_Snetwork']):'';
        update_post_meta($post_id, 'hide_Snetwork', $hide_Snetwork);

        $fm_cs_review = isset($_POST['fm_cs_review'])?sanitize_text_field($_POST['fm_cs_review']):'';
        update_post_meta($post_id, 'fm_cs_review', $fm_cs_review);

        $hide_review = isset($_POST['hide_review'])?sanitize_text_field($_POST['hide_review']):'';
        update_post_meta($post_id, 'hide_review', $hide_review);

        $fm_listing_faq = isset($_POST['fm_listing_faq'])?sanitize_text_field($_POST['fm_listing_faq']):'';
        update_post_meta($post_id, 'fm_listing_faq', $fm_listing_faq);

        $hide_faqs = isset($_POST['hide_faqs'])?sanitize_text_field($_POST['hide_faqs']):'';
        update_post_meta($post_id, 'hide_faqs', $hide_faqs);

        $exclude_cat = isset($_POST['exclude_cat'])?atbdp_sanitize_array($_POST['exclude_cat']):array();
        update_post_meta($post_id, 'exclude_cat', $exclude_cat);

        $hide_categories = isset($_POST['hide_categories'])?sanitize_text_field($_POST['hide_categories']):'';
        update_post_meta($post_id, 'hide_categories', $hide_categories);

        $fm_custom_field = isset($_POST['fm_custom_field'])?sanitize_text_field($_POST['fm_custom_field']):'';
        update_post_meta($post_id, 'fm_custom_field', $fm_custom_field);

        $hide_custom_field = isset($_POST['hide_custom_field'])?sanitize_text_field($_POST['hide_custom_field']):'';
        update_post_meta($post_id, 'hide_custom_field', $hide_custom_field);

        $fm_claim = isset($_POST['fm_claim'])?sanitize_text_field($_POST['fm_claim']):'';
        update_post_meta($post_id, '_fm_claim', $fm_claim);

        $hide_claim = isset($_POST['hide_claim'])?sanitize_text_field($_POST['hide_claim']):'';
        update_post_meta($post_id, '_hide_claim', $hide_claim);

    }
}

return $post_id;
