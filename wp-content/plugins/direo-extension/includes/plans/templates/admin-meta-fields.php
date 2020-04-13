<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;
$post_meta = get_post_meta($post->ID) ? get_post_meta($post->ID) : '';;
?>
<table class="atbdp-input widefat" id="atbdp-field-details">

    <tbody>
    <tr class="field-type">
        <td class="label">
            <label class="widefat"><?php _e('Select Plan Type', 'direo-extension'); ?></label>
        </td>
        <td class="field_lable">
            <?php $selected_plan_type = isset($post_meta['plan_type']) ? esc_attr($post_meta['plan_type'][0]) : ''; ?>
            <ul class="atbdp-radio-list radio horizontal">
                <li>
                    <label><input id="pay_per_listng" type="radio" name="plan_type"
                                  value="pay_per_listng" <?php echo checked($selected_plan_type, 'pay_per_listng', false); ?>><?php _e('Pay Per Listing', 'direo-extension'); ?>
                    </label>
                </li>
                <li>
                    <label><input id="package" type="radio" name="plan_type"
                                  value="package" <?php echo checked($selected_plan_type, 'package', false); ?>><?php _e('Package', 'direo-extension'); ?>
                    </label>
                </li>
            </ul>
        </td>
    </tr>


    <tr class="field-type">
        <td class="label">
            <label class="widefat"><?php _e('Price', 'direo-extension'); ?></label>
        </td>
        <td class="field_lable">
            <input type="number" step="any" name="fm_price"
                   value="<?php if (isset($post_meta['fm_price'])) echo esc_attr($post_meta['fm_price'][0]); ?>"
                   placeholder="<?php _e('Plan price', 'direo-extension'); ?>">
        </td>
    </tr>

    <tr class="field-instructions">
        <td class="label">
            <label><?php _e('Plan Short Description', 'direo-extension'); ?></label>
            <p class="atbd_hide">
                <input id="hide_description" type="checkbox" value="1"
                       name="hide_description" <?php echo (!empty($post_meta['hide_description'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_description"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>

        </td>
        <td>
            <textarea class="textarea" name="fm_description" rows="6"
                      cols="64"><?php if (isset($post_meta['fm_description'])) echo esc_textarea($post_meta['fm_description'][0]); ?></textarea>
        </td>
    </tr>
    <style>
        .fm_unlimited {
            font-size: 13px
        }
    </style>

    <tr class="field-instructions">
        <td class="label">
            <label><?php _e('Duration (in days)', 'direo-extension'); ?></label>
        </td>
        <td>
            <input type="number"
                   value="<?php if (isset($post_meta['fm_length'])) echo esc_attr($post_meta['fm_length'][0]); ?>"
                   name="fm_length" placeholder="<?php _e('Example 360', 'direo-extension'); ?>">

            <p class="option_two">
                <input id="fm_length_unl" type="checkbox" value="1"
                       name="fm_length_unl" <?php echo (!empty($post_meta['fm_length_unl'][0])) ? 'checked' : ''; ?>><label
                        class="fm_unlimited"
                        for="fm_length_unl"><?php _e('Or mark as unlimited ', 'direo-extension'); ?></label>
            </p>
        </td>
    </tr>

    <tr class="field-instructions">
        <td class="label">
            <label for="is_recurring"><?php _e('Recurring Payment', 'direo-extension'); ?></label>
        </td>
        <td>
            <p class="option_two">
                <input id="is_recurring" type="checkbox" value="1"
                       name="is_recurring" <?php echo (!empty($post_meta['_atpp_recurring'][0])) ? 'checked' : ''; ?>>
                <label for="is_recurring"><?php _e('Should the plan auto-renew at the end of the term?', 'direo-extension'); ?></label>
            </p>
            <p class="recurring_time_period">
                <label for="recurring_period"><?php _e('After Every', 'direo-extension'); ?></label>
                <?php
                $period_term = !empty($post_meta['_recurrence_period_term']) ? $post_meta['_recurrence_period_term'][0] : 'day';
                ?>
                <input type="number" name="recurrence_time" id="recurring_period"
                       value="<?php echo !empty($post_meta['_recurrence_time']) ? (int)$post_meta['_recurrence_time'][0] : 30; ?>">
                <select name="recurrence_period_term">
                    <option value="day" <?php echo 'day' === $period_term ? 'selected' : ''; ?>><?php _e('Day(s)', 'direo-extension'); ?></option>
                    <option value="week" <?php echo 'week' === $period_term ? 'selected' : ''; ?>><?php _e('Week(s)', 'direo-extension'); ?></option>
                    <option value="month" <?php echo 'month' === $period_term ? 'selected' : ''; ?>><?php _e('Month(s)', 'direo-extension'); ?></option>
                    <option value="year" <?php echo 'year' === $period_term ? 'selected' : ''; ?>><?php _e('Year(s)', 'direo-extension'); ?></option>
                </select>
            <?php _e('<p>(PayPal Allowed Range: Days 1-90, Weeks 1-52, Months 1-24, Years 1-5)<br>(Stripe Allowed Range: Days 1-365, Weeks 1-52, Months 1-12, Year 1)</p>', 'direo-extension'); ?>

            </p>

        </td>
    </tr>

    <tr id="is_listing_featured" class="field-instructions">
        <td class="label">
            <label for="is_featured_listing"><?php _e('Featured the Listing', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_listing_featured" type="checkbox" value="1"
                                        name="hide_listing_featured" <?php echo (!empty($post_meta['hide_listing_featured'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_listing_featured"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td class="label">
            <input id="is_featured_listing" type="checkbox" value="1"
                   name="is_featured_listing" <?php echo (!empty($post_meta['is_featured_listing'][0])) ? 'checked' : ''; ?>>
        </td>
    </tr>
    <tr id="regular_listing" class="field-instructions">
        <td class="label">
            <label><?php _e('Number of Listings', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_listings" type="checkbox" value="1"
                                        name="hide_listings" <?php echo (!empty($post_meta['hide_listings'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_listings"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td>
            <input type="number" name="num_regular"
                   value="<?php if (isset($post_meta['num_regular'])) echo esc_attr($post_meta['num_regular'][0]); ?>"
                   placeholder="<?php _e('Example 100', 'direo-extension'); ?>">
            <p class="option_two">
                <input id="num_regular_unl" type="checkbox" value="1"
                       name="num_regular_unl" <?php echo (!empty($post_meta['num_regular_unl'][0])) ? 'checked' : ''; ?>><label
                        class="fm_unlimited"
                        for="num_regular_unl"><?php _e('Or mark as unlimited ', 'direo-extension'); ?></label>
            </p>
        </td>
    </tr>

    <tr id="featured_listing" class="field-instructions">
        <td class="label">
            <label><?php _e('Number of Featured Listings', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_featured" type="checkbox" value="1"
                                        name="hide_featured" <?php echo (!empty($post_meta['hide_featured'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_featured"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td>
            <input type="number" name="num_featured"
                   value="<?php if (isset($post_meta['num_featured'])) echo esc_attr($post_meta['num_featured'][0]); ?>"
                   placeholder="<?php _e('Example 5', 'direo-extension'); ?>">
            <p class="option_two">
                <input id="num_featured_unl" type="checkbox" value="1"
                       name="num_featured_unl" <?php echo (!empty($post_meta['num_featured_unl'][0])) ? 'checked' : ''; ?>><label
                        class="fm_unlimited"
                        for="num_featured_unl"><?php _e('Or mark as unlimited ', 'direo-extension'); ?></label>
            </p>
        </td>

    </tr>

    <tr class="field-instructions" id="new_plan_gallery_area">
        <td class="label">
            <input id="fm_allow_slider" type="checkbox" value="1"
                   name="fm_allow_slider" <?php echo (!empty($post_meta['fm_allow_slider'][0])) ? 'checked' : ''; ?>>
            <label for="fm_allow_slider"><?php echo apply_filters('atbdp_new_plan_slider_image_limit_label', __('Listing Slider Image Limit', 'direo-extension')); ?></label>
            <p class="atbd_hide"><input id="hide_image" type="checkbox" value="1"
                                        name="hide_image" <?php echo (!empty($post_meta['hide_image'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_image"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td>
            <input type="number" name="num_image"
                   value="<?php if (isset($post_meta['num_image'])) echo esc_attr($post_meta['num_image'][0]); ?>"
                   placeholder="<?php _e('Example 5', 'direo-extension'); ?>">
            <p class="option_two">
                <input id="num_image_unl" type="checkbox" value="1"
                       name="num_image_unl" <?php echo (!empty($post_meta['num_image_unl'][0])) ? 'checked' : ''; ?>><label
                        class="fm_unlimited"
                        for="num_image_unl"><?php _e('Or mark as unlimited ', 'direo-extension'); ?></label>
            </p>
        </td>
    </tr>

    <tr class="field-instructions">
        <td class="label">
            <input id="fm_allow_tag" type="checkbox" value="1"
                   name="fm_allow_tag" <?php echo (!empty($post_meta['fm_allow_tag'][0])) ? 'checked' : ''; ?>>
            <label for="fm_allow_tag"><?php _e('Allow Tag and set Limit', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_tag_limit" type="checkbox" value="1"
                                        name="hide_tag_limit" <?php echo (!empty($post_meta['hide_tag_limit'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_tag_limit"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td>
            <input type="number" name="fm_tag_limit"
                   value="<?php if (isset($post_meta['fm_tag_limit'])) echo esc_attr($post_meta['fm_tag_limit'][0]); ?>"
                   placeholder="<?php _e('Example 140', 'direo-extension'); ?>">
            <p class="option_two">
                <input id="fm_tag_limit_unl" type="checkbox" value="1"
                       name="fm_tag_limit_unl" <?php echo (!empty($post_meta['fm_tag_limit_unl'][0])) ? 'checked' : ''; ?>><label
                        class="fm_unlimited"
                        for="fm_tag_limit_unl"><?php _e('Or mark as unlimited ', 'direo-extension'); ?></label>
            </p>
        </td>
    </tr>

    <tr class="field-instructions">
        <td class="label">
            <input id="fm_allow_price" type="checkbox" value="1"
                   name="fm_allow_price" <?php echo (!empty($post_meta['fm_allow_price'][0])) ? 'checked' : ''; ?>>
            <label for="fm_allow_price"><?php _e('Allow Price and set Limit', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_price_limit" type="checkbox" value="1"
                                        name="hide_price_limit" <?php echo (!empty($post_meta['hide_price_limit'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_price_limit"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td>
            <input type="number" name="price_range"
                   value="<?php if (isset($post_meta['price_range'])) echo esc_attr($post_meta['price_range'][0]); ?>"
                   placeholder="<?php _e('Example 500', 'direo-extension'); ?>">
            <p class="option_two">
                <input id="price_range_unl" type="checkbox" value="1"
                       name="price_range_unl" <?php echo (!empty($post_meta['price_range_unl'][0])) ? 'checked' : ''; ?>><label
                        class="fm_unlimited"
                        for="price_range_unl"><?php _e('Or mark as unlimited ', 'direo-extension'); ?></label>
            </p>
        </td>
    </tr>

    <tr class="field-instructions" id="new_plan_pRange_area">
        <td class="label">
            <input id="fm_allow_price_range" type="checkbox" value="1"
                   name="fm_allow_price_range" <?php echo (!empty($post_meta['fm_allow_price_range'][0])) ? 'checked' : ''; ?>>
            <label for="fm_allow_price_range"><?php _e('Price Range', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_price_range" type="checkbox" value="1"
                                        name="hide_price_range" <?php echo (!empty($post_meta['hide_price_range'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_price_range"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_BH_area">
        <td class="label"><input id="business_hrs" type="checkbox" value="1"
                                 name="business_hrs" <?php echo (!empty($post_meta['business_hrs'][0])) ? 'checked' : ''; ?>>
            <label for="business_hrs"><?php _e('Business Hours (It requires <a style="text-decoration: underline" href="https://aazztech.com/product/directorist-business-hours/" target="_blank">Business Hours</a> extension)', 'direo-extension'); ?></label>

            <p class="atbd_hide">
                <input id="hide_BH" type="checkbox" value="1"
                       name="hide_BH" <?php echo (!empty($post_meta['hide_BH'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_BH"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions add_plan_image_gallery">
        <td class="label">
            <input id="atfm_listing_gallery" type="checkbox" value="1"
                   name="atfm_listing_gallery" <?php echo (!empty($post_meta['atfm_listing_gallery'][0])) ? 'checked' : ''; ?>>
            <label for="atfm_listing_gallery"><?php _e('Image Gallery (It requires <a style="text-decoration: underline" href="https://aazztech.com/product/directorist-image-gallery" target="_blank">Image Gallery</a> extension)', 'direo-extension'); ?></label>

            <p class="atbd_hide"><input id="hide_Lgallery" type="checkbox" value="1"
                                        name="hide_Lgallery" <?php echo (!empty($post_meta['hide_Lgallery'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_Lgallery"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td class="gallery_image_limit">
            <label for="num_gallery_image"><?php _e('Gallery Image Limit ', 'direo-extension'); ?></label>
            <input type="number" name="num_gallery_image"
                   value="<?php if (isset($post_meta['num_gallery_image'])) echo esc_attr($post_meta['num_gallery_image'][0]); ?>"
                   placeholder="<?php _e('Example 5', 'direo-extension'); ?>">
            <p class="option_two">
                <input id="num_gallery_image_unl" type="checkbox" value="1"
                       name="num_gallery_image_unl" <?php echo (!empty($post_meta['num_gallery_image_unl'][0])) ? 'checked' : ''; ?>><label
                        class="fm_unlimited"
                        for="num_gallery_image_unl"><?php _e('Or mark as unlimited ', 'direo-extension'); ?></label>
            </p>
        </td>
    </tr>

    <tr class="field-instructions" id="new_plan_video_area">
        <td class="label"><input id="l_video" type="checkbox" value="1"
                                 name="l_video" <?php echo (!empty($post_meta['l_video'][0])) ? 'checked' : ''; ?>>
            <label for="l_video"><?php _e('Video', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_video" type="checkbox" value="1"
                                        name="hide_video" <?php echo (!empty($post_meta['hide_video'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_video"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions">
        <td class="label"><input id="cf_owner" type="checkbox" value="1"
                                 name="cf_owner" <?php echo (!empty($post_meta['cf_owner'][0])) ? 'checked' : ''; ?>>
            <label for="cf_owner"><?php _e('Contact Listing Owner', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_Cowner" type="checkbox" value="1"
                                        name="hide_Cowner" <?php echo (!empty($post_meta['hide_Cowner'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_Cowner"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_email_area">
        <td class="label"><input id="fm_email" type="checkbox" value="1"
                                 name="fm_email" <?php echo (!empty($post_meta['fm_email'][0])) ? 'checked' : ''; ?>>
            <label for="fm_email"><?php _e('Email Address', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_email" type="checkbox" value="1"
                                        name="hide_email" <?php echo (!empty($post_meta['hide_email'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_email"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_phone_area">
        <td class="label"><input id="fm_phone" type="checkbox" value="1"
                                 name="fm_phone" <?php echo (!empty($post_meta['fm_phone'][0])) ? 'checked' : ''; ?>>
            <label for="fm_phone"><?php _e('Phone Number', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_phone" type="checkbox" value="1"
                                        name="hide_phone" <?php echo (!empty($post_meta['hide_phone'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_phone"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_webLink_area">
        <td class="label"><input id="fm_web_link" type="checkbox" value="1"
                                 name="fm_web_link" <?php echo (!empty($post_meta['fm_web_link'][0])) ? 'checked' : ''; ?>>
            <label for="fm_web_link"><?php _e('Website Link', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_webLink" type="checkbox" value="1"
                                        name="hide_webLink" <?php echo (!empty($post_meta['hide_webLink'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_webLink"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_sMedia_area">
        <td class="label"><input id="fm_social_network" type="checkbox" value="1"
                                 name="fm_social_network" <?php echo (!empty($post_meta['fm_social_network'][0])) ? 'checked' : ''; ?>>
            <label for="fm_social_network"><?php _e('Social Media Links', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_Snetwork" type="checkbox" value="1"
                                        name="hide_Snetwork" <?php echo (!empty($post_meta['hide_Snetwork'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_Snetwork"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_review_area">
        <td class="label"><input id="fm_cs_review" type="checkbox" value="1"
                                 name="fm_cs_review" <?php echo (!empty($post_meta['fm_cs_review'][0])) ? 'checked' : ''; ?>>
            <label for="fm_cs_review"><?php _e('Customer Reviews', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_review" type="checkbox" value="1"
                                        name="hide_review" <?php echo (!empty($post_meta['hide_review'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_review"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_faq_area">
        <td class="label"><input id="fm_listing_faq" type="checkbox" value="1"
                                 name="fm_listing_faq" <?php echo (!empty($post_meta['fm_listing_faq'][0])) ? 'checked' : ''; ?>>
            <label for="fm_listing_faq"><?php _e('FAQs (It requires <a style="text-decoration: underline" href="https://aazztech.com/product/directorist-listing-faqs/" target="_blank">Listing\'s FAQs</a> extension)', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_faqs" type="checkbox" value="1"
                                        name="hide_faqs" <?php echo (!empty($post_meta['hide_faqs'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_faqs"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_cField_area">
        <td class="label"><input id="fm_custom_field" type="checkbox" value="1"
                                 name="fm_custom_field" <?php echo (!empty($post_meta['fm_custom_field'][0])) ? 'checked' : ''; ?>>
            <label for="fm_custom_field"><?php _e('Custom Fields', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_custom_field" type="checkbox" value="1"
                                        name="hide_custom_field" <?php echo (!empty($post_meta['hide_custom_field'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_custom_field"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions" id="new_plan_claim_area">
        <td class="label"><input id="fm_claim" type="checkbox" value="1"
                                 name="fm_claim" <?php echo (!empty($post_meta['_fm_claim'][0])) ? 'checked' : ''; ?>>
            <label for="fm_claim"><?php _e('Claim Badge Included (It requires <a style="text-decoration: underline" href="https://aazztech.com/product/directorist-claim-listing/" target="_blank">Claim Listing</a> extension)', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_claim" type="checkbox" value="1"
                                        name="hide_claim" <?php echo (!empty($post_meta['_hide_claim'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_claim"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions">
        <td class="label">
            <label><?php _e('Exclude Categories', 'direo-extension'); ?></label>
            <p class="atbd_hide"><input id="hide_categories" type="checkbox" value="1"
                                        name="hide_categories" <?php echo (!empty($post_meta['hide_categories'][0])) ? 'checked' : ''; ?>>
                <label class="fm_hide_option"
                       for="hide_categories"><?php _e('Hide this from pricing plan page ', 'direo-extension'); ?></label>
            </p>
        </td>
        <td>
            <?php
            $current_val = !empty(get_post_meta(get_the_ID(), 'exclude_cat', true)) ? get_post_meta(get_the_ID(), 'exclude_cat', true) : array();

            $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0, 'parent' => 0));
            foreach ($categories as $key => $cat_title) {
                $checked = in_array($cat_title->term_id, $current_val) ? 'checked' : '';
                printf('<input name="exclude_cat[]" type="checkbox" value="%s" %s><span class="fm_unlimited">%s</span><br>', $cat_title->term_id, $checked, $cat_title->name);
            }
            ?>
        </td>
    </tr>

    <tr class="field-instructions">
        <td class="label"><input id="default_pln" type="checkbox" value="1"
                                 name="default_pln" <?php echo (!empty($post_meta['default_pln'][0])) ? 'checked' : ''; ?>>
            <label for="default_pln"><?php _e('Recommend this Plan', 'direo-extension'); ?></label>
        </td>
        <td></td>
    </tr>

    <tr class="field-instructions">
        <td class="label"><input id="hide_from_plans" type="checkbox" value="1"
                                 name="hide_from_plans" <?php echo (!empty($post_meta['_hide_from_plans'][0])) ? 'checked' : ''; ?>>
            <label for="hide_from_plans"><?php _e('Hide form All Plans', 'direo-extension'); ?></label>
        </td>
        <td></td>
    </tr>

    </tbody>
</table>

<div class="atbdp_shortcode">
    <h2><?php esc_html_e('Shortcode', 'direo-extension'); ?> </h2>
    <p><?php esc_html_e('Use following shortcode to display the Plan anywhere:', 'direo-extension'); ?></p>
    <textarea cols="50" rows="1" onClick="this.select();">[directorist_pricing_plans id=<?php echo $post->ID; ?>]</textarea> <br/>

    <p><?php esc_html_e('If you need to put the shortcode inside php code/template file, use this:', 'direo-extension'); ?></p>
    <textarea cols="63" rows="1"
              onClick="this.select();"><?php echo '<?php echo do_shortcode("[directorist_pricing_plans id=';
        echo $post->ID . "]";
        echo '"); ?>'; ?></textarea>
</div>

