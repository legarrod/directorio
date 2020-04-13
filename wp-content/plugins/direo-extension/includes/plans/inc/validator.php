<?php
defined('ABSPATH') || die('Direct access is not allowed.');

if (!class_exists('ATPP_Validator')):

    class ATPP_Validator
    {

        public function __construct()
        {
            add_action('atbdp_add_listing_after_tag_field', array($this, 'atpp_add_listing_after_tag'));
            add_action('atbdp_add_listing_after_price_field', array($this, 'atpp_add_listing_after_price'));
        }


        public function atpp_add_listing_after_price($listing_id)
        {
            $fm_plan = get_post_meta($listing_id, '_fm_plans', true);
            $selected_plan = selected_plan_id();
            $price_visable = get_directorist_option('display_price_for', 0);
            $price_display = get_directorist_option('display_pricing_field', 1);
            $planID = !empty($selected_plan) ? $selected_plan : $fm_plan;
            $plan_allow = is_plan_allowed_price($planID);
            //is allow price
            if (!empty($price_display) && $plan_allow && empty($price_visable)) {
                $price = is_plan_price_limit($planID);
                $currency_symbol = atbdp_get_payment_currency();
                $unlimited_price = is_plan_price_unlimited($planID);
                //is unlimited
                echo '<div class="atbd_priceValidate_note">';
                if ($unlimited_price) {
                    ?>
                    <span class="atbdp_make_str_green atpp_limit__notice"><?php _e("Unlimited price with this plan!", 'direo-extension'); ?></span>
                    <?php
                } else {
                    $part1 = __('You can set maximum', 'direo-extension');
                    $part2 = __('price with this plan!', 'direo-extension');
                    printf("<span class='atpp_limit__notice'>" . __('%s %s%s %s', 'direo-extension') . "<span>", $part1, $currency_symbol, $price, $part2);
                }
                echo '</div>';
            }
        }

        public function atpp_add_listing_after_tag($listing_id)
        {
            $fm_plan = get_post_meta($listing_id, '_fm_plans', true);
            $selected_plan = selected_plan_id();
            $planID = !empty($selected_plan) ? $selected_plan : $fm_plan;
            $tag_visable = get_directorist_option('display_tag_for', 0);
            $tag_allow = is_plan_allowed_tag($planID);
            //is allow price
            if ($tag_allow && empty($tag_visable)) {
                $tag = is_plan_tag_limit($planID);
                $tag = !empty($tag) ? $tag : '0';
                $is_plural = ($tag > 1 || $tag == 0) ? __('tags', 'directorist-pricing_plan') : __('tag', 'directorist-pricing_plan');
                $unlimited_tag = is_plan_tag_unlimited($planID);

                //is unlimited
                echo '<div class="atbd_tagvalidate_note">';
                if ($unlimited_tag) {
                    ?>
                    <span class="atbdp_make_str_green atpp_limit__notice"><?php _e("Unlimited tags with this plan!", 'direo-extension'); ?></span>
                    <?php
                } else {
                    $part1 = __('You can use', 'direo-extension');
                    $part2 = __('with this plan!', 'direo-extension');
                    printf("<span class='atpp_limit__notice'>" . __('%s %s %s %s', 'direo-extension') . "<span>", $part1, $tag, $is_plural, $part2);
                }
                echo '</div>';
            }
        }
    }

    new ATPP_Validator();
endif;
