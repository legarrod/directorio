<?php
// Prohibit direct script loading.
defined('ABSPATH') || die('No direct script access allowed!');


if (!function_exists('atbdp_get_option')) {

    /**
     * @return array    It returns the role of the users
     */
    function all_rules()
    {
        return apply_filters('dgrc_default_user_roles', array(
            array(
                'value' => 'administrator',
                'label' => __('Administrator', 'direo-extension'),
            ),
            array(
                'value' => 'editor',
                'label' => __('Editor', 'direo-extension'),
            ),
            array(
                'value' => 'author',
                'label' => __('Author', 'direo-extension'),
            ),
            array(
                'value' => 'contributor',
                'label' => __('Contributor', 'direo-extension'),
            ),
            array(
                'value' => 'subscriber',
                'label' => __('Subscriber', 'direo-extension'),
            )
        ));
    }


    /**
     * It retrieves an option from the database if it exists and returns false if it is not exist.
     * It is a custom function to get the data of custom setting page
     * @param string $name The name of the option we would like to get. Eg. map_api_key
     * @param string $group The name of the group where the option is saved. eg. general_settings
     * @param mixed $default Default value for the option key if the option does not have value then default will be returned
     * @return mixed    It returns the value of the $name option if it exists in the option $group in the database, false otherwise.
     */

    function atbdp_get_option($name, $group, $default = false)
    {
        // at first get the group of options from the database.
        // then check if the data exists in the array and if it exists then return it
        // if not, then return false
        if (empty($name) || empty($group)) {
            if (!empty($default)) return $default;
            return false;
        } // vail if either $name or option $group is empty
        $options_array = (array)get_option($group);
        if (array_key_exists($name, $options_array)) {
            return $options_array[$name];
        } else {
            if (!empty($default)) return $default;
            return false;
        }
    }
}


if (!function_exists('atbdp_sanitize_array')) {
    /**
     * It sanitize a multi-dimensional array
     * @param array &$array The array of the data to sanitize
     * @return mixed
     */
    function atbdp_sanitize_array(&$array)
    {

        foreach ($array as &$value) {

            if (!is_array($value)) {

                // sanitize if value is not an array
                $value = sanitize_text_field($value);

            } else {

                // go inside this function again
                atbdp_sanitize_array($value);
            }

        }

        return $array;

    }
}

if (!function_exists('is_directoria_active')) {
    /**
     * It checks if the Directorist theme is installed currently.
     * @return bool It returns true if the directorist theme is active currently. False otherwise.
     */
    function is_directoria_active()
    {
        return wp_get_theme()->get_stylesheet() === 'directoria';
    }
}

if (!function_exists('is_plan_allowed_business_hours')) {
    /**
     * It checks is user activated business hours and is the purchased plan included that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_business_hours($plan_id)
    {
        //return true;
        //check is BH activated
        if (class_exists('BD_Business_Hour')) {
            // lets check the plan allowances
            $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
            $business_hrs = selected_plan_meta($selected_plan_id, 'business_hrs');
            return ($business_hrs) ? true : false;
        } else {
            return false;
        }

    }
}

if (!function_exists('is_plan_allowed_listing_video')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_listing_video($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $l_video = selected_plan_meta($selected_plan_id, 'l_video');
        return ($l_video) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_owner_contact_widget')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_owner_contact_widget($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $cf_owner = selected_plan_meta($selected_plan_id, 'cf_owner');
        return ($cf_owner) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_listing_email')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_listing_email($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_email = selected_plan_meta($selected_plan_id, 'fm_email');
        return ($fm_email) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_listing_phone')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_listing_phone($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_phone = selected_plan_meta($selected_plan_id, 'fm_phone');
        return ($fm_phone) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_listing_webLink')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_listing_webLink($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_web_link = selected_plan_meta($selected_plan_id, 'fm_web_link');
        return ($fm_web_link) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_listing_social_networks')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_listing_social_networks($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_social_network = selected_plan_meta($selected_plan_id, 'fm_social_network');
        return ($fm_social_network) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_listing_review')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_listing_review($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_cs_review = selected_plan_meta($selected_plan_id, 'fm_cs_review');
        return ($fm_cs_review) ? true : false;
    }
}
if (!function_exists('is_plan_allowed_listing_faqs')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_listing_faqs($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_listing_faq = selected_plan_meta($selected_plan_id, 'fm_listing_faq');
        return ($fm_listing_faq) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_category')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_category($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $exclude_cat = selected_plan_meta($selected_plan_id, 'exclude_cat');
        return ($exclude_cat) ? $exclude_cat : false;
    }
}

if (!function_exists('is_plan_allowed_custom_fields')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_custom_fields($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_custom_field = selected_plan_meta($selected_plan_id, 'fm_custom_field');
        return ($fm_custom_field) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_average_price_range')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_average_price_range($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_allow_price_range = selected_plan_meta($selected_plan_id, 'fm_allow_price_range');
        return ($fm_allow_price_range) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_listing_gallery')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_listing_gallery($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $atfm_listing_gallery = selected_plan_meta($selected_plan_id, 'atfm_listing_gallery');
        return ($atfm_listing_gallery) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_featured_listing')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_featured_listing()
    {
        $selected_plan_id = selected_plan_id();
        $num_featured = selected_plan_meta($selected_plan_id, 'num_featured');
        $unlimited_featured = selected_plan_meta($selected_plan_id, 'num_featured_unl');
        return ($num_featured || $unlimited_featured) ? true : false;
    }
}

if (!function_exists('selected_plan_id')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function selected_plan_id()
    {
        if (ATBDP_VERSION < '6.3.0'){ // add compatibility with directorist version bellow 6.3.0
            if (!empty($_GET['plan'])) {
                $plan_id = $_GET['plan'];
                return $plan_id;
            } else {
                return false;
            }
        }else{
            if (!empty($_POST['plan'])) {
                $plan_id = $_POST['plan'];
                return $plan_id;
            } else {
                return isset($_GET['plan'])?$_GET['plan']:'';
            }
        }

    }
}

if (!function_exists('selected_plan_meta')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function selected_plan_meta($plan_id, $meta_key)
    {

        $plan_meta = get_post_meta($plan_id, $meta_key, true);
        return $plan_meta;
    }
}

if (!function_exists('package_or_PPL')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function package_or_PPL($plan_id)
    {
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $plan_type = selected_plan_meta($selected_plan_id, 'plan_type');
        return ($plan_type) ? $plan_type : '';
    }
}

if (!function_exists('PPL_with_featured')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function PPL_with_featured()
    {
        $selected_plan_id = selected_plan_id();
        $is_featured_listing = selected_plan_meta($selected_plan_id, 'is_featured_listing');
        return ($is_featured_listing) ? true : false;
    }
}

if (!function_exists('subscribed_package_or_PPL_plans')) {
    /**
     * Get the order of current author for ATBDP order table.
     * @param $plan_id
     * @param $order_status
     * @param $user_id
     * @return true It returns all the subscribed plan id.
     */
    function subscribed_package_or_PPL_plans($user_id, $order_status, $plan_id)
    {

        $args = array(
            'post_type' => 'shop_order',
            'post_status' => "wc-$order_status",
            'numberposts' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_customer_user',
                    'value' => $user_id,
                    'compare' => '='
                ),
                array(
                    'key' => '_fm_plan_ordered',
                    'value' => $plan_id,
                    'compare' => '='
                )
            )
        );

        $active_plan = new WP_Query($args);

        if ($active_plan->have_posts()) :
            global $post;
            while ($active_plan->have_posts()) :
                $active_plan->the_post();
            endwhile;
        endif;
        return ($active_plan->have_posts()) ? $post : false;
    }
}

if (!function_exists('package_or_PPL_with_listing')) {
    /**
     * Get the order of current author for ATBDP order table.
     * @param $listing_id
     * @param $order_status
     * @param $user_id
     * @return true It returns all the subscribed plan id.
     */
    function package_or_PPL_with_listing($user_id, $order_status, $listing_id)
    {
        $args = array(
            'post_type' => 'shop_order',
            'post_status' => "wc-$order_status",
            'numberposts' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_customer_user',
                    'value' => $user_id,
                    'compare' => '='
                ),
                array(
                    'key' => '_listing_id',
                    'value' => $listing_id,
                    'compare' => '='
                )
            )
        );

        $active_plan = new WP_Query($args);

        if ($active_plan->have_posts()) :
            global $post;
            while ($active_plan->have_posts()) :
                $active_plan->the_post();
            endwhile;
        endif;
        return ($active_plan->have_posts()) ? $post : false;
    }
}


if (!function_exists('listings_data_with_plan')) {
    /**
     * Get the order of current author for ATBDP order table.
     * @param $plan_id
     * @param $user_id
     * @param $featured
     * @return true It returns all the listings under a subscribed plan id.
     */
    function listings_data_with_plan($user_id, $featured, $plan_id, $order_id = null)
    {
        $orders = new WP_Query(array(
            'post_type' => 'at_biz_dir',
            'posts_per_page' => -1,
            'post_status' => array('publish', 'pending'),
            'author' => $user_id,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_featured',
                    'value' => $featured,
                    'compare' => '='
                ),
                array(
                    'key' => '_fm_plans',
                    'value' => $plan_id,
                    'compare' => '='
                ),
                array(
                    'key' => '_plan_order_id',
                    'value' => $order_id,
                    'compare' => '='
                ),
            )
        ));
        return ($orders->post_count) ? $orders->post_count : false;
    }
}

if (!function_exists('is_plan_slider_limit')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_slider_limit($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $slider_range = selected_plan_meta($selected_plan_id, 'num_image');
        return ($slider_range) ? $slider_range : false;
    }
}

if (!function_exists('is_plan_allowed_price')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_price($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_allow_price = selected_plan_meta($selected_plan_id, 'fm_allow_price');
        return ($fm_allow_price) ? true : false;
    }
}


if (!function_exists('is_plan_allowed_tag')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_tag($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_allow_tag = selected_plan_meta($selected_plan_id, 'fm_allow_tag');
        return ($fm_allow_tag) ? true : false;
    }
}
if (!function_exists('is_plan_slider_unlimited')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_slider_unlimited($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $slider_range_unl = selected_plan_meta($selected_plan_id, 'num_image_unl');
        return ($slider_range_unl) ? true : false;
    }
}
if (!function_exists('is_plan_tag_limit')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_tag_limit($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $tag_range = selected_plan_meta($selected_plan_id, 'fm_tag_limit');
        return ($tag_range) ? $tag_range : false;
    }
}
if (!function_exists('is_plan_price_limit')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_price_limit($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $price_range = selected_plan_meta($selected_plan_id, 'price_range');
        return ($price_range) ? $price_range : false;
    }
}
if (!function_exists('is_plan_tag_unlimited')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_tag_unlimited($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $tag_range_unl = selected_plan_meta($selected_plan_id, 'fm_tag_limit_unl');
        return ($tag_range_unl) ? true : false;
    }
}
if (!function_exists('is_plan_price_unlimited')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_price_unlimited($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $price_range_unl = selected_plan_meta($selected_plan_id, 'price_range_unl');
        return ($price_range_unl) ? true : false;
    }
}

if (!function_exists('is_plan_allowed_slider')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_plan_allowed_slider($plan_id)
    {
        // lets check the plan allowances
        $selected_plan_id = empty(selected_plan_id()) ? $plan_id : selected_plan_id();
        $fm_allow_slider = selected_plan_meta($selected_plan_id, 'fm_allow_slider');
        return ($fm_allow_slider) ? true : false;
    }
}


/**
 * Output a select input box.
 *
 * @param array $field
 * @since    1.0.0
 *
 */
function dwpp_woocommerce_multiselect($field)
{

    global $thepostid, $post;

    $thepostid = empty($thepostid) ? $post->ID : $thepostid;
    $field['class'] = isset($field['class']) ? $field['class'] : 'select short';
    $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
    $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
    $field['value'] = isset($field['value']) ? $field['value'] : (get_post_meta($thepostid, $field['id'], true) ? get_post_meta($thepostid, $field['id'], true) : array());

    echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label><select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '" class="' . esc_attr($field['class']) . '" multiple="multiple">';

    foreach ($field['options'] as $key => $value) {
        echo '<option value="' . esc_attr($key) . '" ' . (in_array($key, $field['value']) ? 'selected="selected"' : '') . '>' . esc_html($value) . '</option>';
    }

    echo '</select> ';

    if (!empty($field['description'])) {
        if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
            echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
        } else {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }
    }

    echo '</p>';

}

/**
 * @return boolean      return true means need not to collect money
 * @since 1.4.0
 */
if (!function_exists('dwpp_need_to_charge_with_plan')) {
    function dwpp_need_to_charge_with_plan()
    {
        // sanitize form values
        $post_id = (int)$_POST["post_id"];
        $plan_id = isset($_POST["plan_id"]) ? (int)($_POST["plan_id"]) : '';
        $activated_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $plan_id);

        $order_id = !empty($activated_plan) ? (int)$activated_plan->ID : '';

        $user_regular_listing = listings_data_with_plan(get_current_user_id(), '0', $plan_id, $order_id);
        $num_regular = get_post_meta($plan_id, 'num_regular', true);
        $featured_listing = get_post_meta($plan_id, 'is_featured_listing', true);
        $plan_type = package_or_PPL($plan_id);
        // ok lets check is user selected plan is package
        if ('package' === $plan_type) {
            if (empty($activated_plan)) {
                update_post_meta($post_id, '_need_to_refresh', 1);
                //need to collect money form claimer
                return false;
            } else {
                update_post_meta($post_id, '_plan_order_id', $order_id);
                update_post_meta($post_id, '_listing_status', 'post_status');
                $package_length = get_post_meta($plan_id, 'fm_length', true);
                $package_length = $package_length ? $package_length : '1';
                // Current time
                $current_d = current_time('mysql');
                // Calculate new date
                $date = new DateTime($current_d);
                $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                $expired_date = $date->format('Y-m-d H:i:s');
                $is_never_expaired = get_post_meta($plan_id, 'fm_length_unl', true);
                if ($is_never_expaired) {
                    update_post_meta($post_id, '_never_expire', '1');
                } else {
                    update_post_meta($post_id, '_expiry_date', $expired_date);
                }

                if (!empty($featured_listing)) {
                    update_post_meta($_POST['post_id'], '_need_featured', 1);
                }
                //ok user has change the plan package
                return true;
            }
        } else {
            //pay per listing so collect money
            update_post_meta($post_id, '_need_to_refresh', 1);
            if (!empty($featured_listing)) {
                update_post_meta($post_id, '_need_featured', 1);
            }
            return false;
        }

    }
}

/**
 * @param $order_id     WooCommerce order id
 * @param $plan_id      Order carried the plan
 *
 * @since 1.1.9
 * //get the listings with the order meta '_need_to_refresh' and if found any order referring the same listing. Yep, refresh it
 * @package Directorist
 */
if (!function_exists('dwpp_need_listing_to_refresh')) {
    function dwpp_need_listing_to_refresh($user_id = null, $order_id = null, $plan_id = null)
    {
        // get the listings id that contains the meta value
        $listings = new WP_Query(array(
            'post_type' => 'at_biz_dir',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'meta_key' => '_need_to_refresh',
            'meta_value' => 1,
            'compare' => '='
        ));
        $post_id = '';
        if (!empty($listings->have_posts())) {
            foreach ($listings->posts as $post) {
                $id = $post->ID;
                $get_listings = package_or_PPL_with_listing($user_id, 'completed', $id);
                if (!empty($get_listings)) {
                    $post_id = $post->ID;
                }
            }
        }
        update_post_meta($post_id, '_plan_order_id', $order_id);
        update_post_meta($post_id, '_listing_status', 'post_status');
        $package_length = get_post_meta($plan_id, 'fm_length', true);
        $package_length = $package_length ? $package_length : '1';
        // Current time
        $current_d = current_time('mysql');
        // Calculate new date
        $date = new DateTime($current_d);
        $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
        $expired_date = $date->format('Y-m-d H:i:s');
        $is_never_expaired = get_post_meta($plan_id, 'fm_length_unl', true);
        if ($is_never_expaired) {
            update_post_meta($post_id, '_never_expire', '1');
        } else {
            update_post_meta($post_id, '_expiry_date', $expired_date);
        }

        if (!empty($featured_listing)) {
            update_post_meta($_POST['post_id'], '_need_featured', 1);
        }
    }
}

if (!function_exists('dwpp_get_used_free_plan')) {
    function dwpp_get_used_free_plan($plan_id, $user_id)
    {
        if (!$user_id) return true;
        $used_free_plan = get_user_meta(get_current_user_id(), '_used_free_plan', true);
        $plan = !empty($used_free_plan) ? (int)$used_free_plan[0] : '';
        if ($plan === $plan_id) {
            $list_status = get_post_status($used_free_plan[1]);
            if (($list_status === 'trash') || !$list_status) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }

    }
}

function dwpp_create_required_pages(){
    $options = get_option('atbdp_option');
    $page_exists = get_option('atbdp_plan_page_create');
    // $op_name is the page option name in the database.
    // if we do not have the page id assigned in the settings with the given page option name, then create an page
    // and update the option.
    $id = array();
    if (!$page_exists) {
        $id = wp_insert_post(
            array(
                'post_title' => 'Select Your Plan',
                'post_content' => '[directorist_pricing_plans]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => 'closed'
            )
        );
    }
    // if we have new options then lets update the options with new option values.
    if ($id) {
        update_option('atbdp_plan_page_create', 1);
        $options['pricing_plans'] = (int)$id;
        update_option('atbdp_option', $options);

    };
}