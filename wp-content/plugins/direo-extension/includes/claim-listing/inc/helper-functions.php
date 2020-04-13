<?php
// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );



if (!function_exists('atbdp_get_option')){

    /**
     * @return array    It returns the role of the users
     */
    function all_rules(){
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
     * @param mixed $default    Default value for the option key if the option does not have value then default will be returned
     * @return mixed    It returns the value of the $name option if it exists in the option $group in the database, false otherwise.
     */

    function atbdp_get_option( $name, $group, $default=false ) {
        // at first get the group of options from the database.
        // then check if the data exists in the array and if it exists then return it
        // if not, then return false
        if (empty($name) || empty($group)) {
            if (!empty($default)) return $default;
            return false;
        } // vail if either $name or option $group is empty
        $options_array = (array) get_option($group);
        if (array_key_exists( $name, $options_array) ) {
            return $options_array[$name];
        }else{
            if (!empty($default)) return $default;
            return false;
        }
    }
}




if (!function_exists('atbdp_sanitize_array')){
    /**
     * It sanitize a multi-dimensional array
     * @param array &$array The array of the data to sanitize
     * @return mixed
     */
    function atbdp_sanitize_array(&$array ) {

        foreach ($array as &$value) {

            if( !is_array($value) ) {

                // sanitize if value is not an array
                $value = sanitize_text_field($value);

            }else {

                // go inside this function again
                atbdp_sanitize_array($value);
            }

        }

        return $array;

    }
}

if (!function_exists('is_directoria_active')){
    /**
     * It checks if the Directorist theme is installed currently.
     * @return bool It returns true if the directorist theme is active currently. False otherwise.
     */
    function  is_directoria_active(){
        return wp_get_theme()->get_stylesheet() === 'directoria';
    }
}


/// claim listing

if (!function_exists('dcl_claim_status')){
    function dcl_claim_status(){
        $status = array(
            'pending' => __('Pending', 'direo-extension'),
            'approved' => __('Approved', 'direo-extension'),
            'declined' => __('Decline', 'direo-extension'),
        );
        return $status;
    }
}

if (!function_exists('is_pricing_plans_active')){
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_pricing_plans_active(){
        $FM_disabled_byAdmin = get_directorist_option('fee_manager_enable', 1);
        $WFM_disabled_byAdmin = get_directorist_option('woo_pricing_plans_enable', 1);
        if (class_exists('ATBDP_Pricing_Plans') && $FM_disabled_byAdmin){
            return true;
        }elseif(class_exists('DWPP_Pricing_Plans') && $WFM_disabled_byAdmin){
            return true;
        }else{
            return false;
        }

    }
}

if (!function_exists('is_pricing_plans_active_with_dcl')){
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_pricing_plans_active_with_dcl(){
        $FM_disabled_byAdmin = atbdp_get_option('fee_manager_enable', 1);
        $WFM_disabled_byAdmin = atbdp_get_option('woo_pricing_plans_enable', 1);
        if (class_exists('ATBDP_Pricing_Plans') && $FM_disabled_byAdmin){
            $claim = atbdp_get_option('claim_charge_by');
            if ($claim === 'pricing_plan'){
                return true;
            }
        }elseif(class_exists('DWPP_Pricing_Plans') && $WFM_disabled_byAdmin){
            $claim = atbdp_get_option('claim_charge_by');
            if ($claim === 'pricing_plan'){
                return true;
            }
        }else{
            return false;
        }

    }
}

/**
 * Send confermation to the listing owner.
 *
 * @since    1.0.0
 *
 * @return   string    $result    Message based on the result.
 */
if (!function_exists('dcl_current_user')){
    function dcl_current_user() {
        // sanitize form values
        $post_id = (int) $_POST["post_id"];
        $claimer_name    = sanitize_text_field( $_POST["claimer_name"] );
        $claimer_phone    = (int)( $_POST["claimer_phone"] );
        $claimer_plan_id    = isset($_POST["claimer_plan"])?(int)( $_POST["claimer_plan"] ):'';
        $claimer_details = stripslashes( esc_textarea( $_POST["claimer_details"] ) );

        //lets update some necessery data
        update_post_meta($post_id, '_claimer_name', $claimer_name);
        update_post_meta($post_id, '_claimer_phone', $claimer_phone);
        update_post_meta($post_id, '_claimer_details', $claimer_details);
        update_post_meta($post_id, '_claimer_plans', $claimer_plan_id);

        if (get_directorist_option('disable_email_notification')) return false;
        if(! in_array( 'new_claim_submitted', get_directorist_option('notify_user', array('new_claim_submitted')) )) return false;
        // vars
        $post_author_id         = get_post_field( 'post_author', $post_id );
        $user                   = get_userdata( $post_author_id );
        $site_name              = get_bloginfo( 'name' );
        $site_url               = get_bloginfo( 'url' );
        $site_email		        = get_bloginfo( 'admin_email' );
        $currennt_user          = wp_get_current_user();
        $current_user_email     = $currennt_user->user_email;
        $listing_title          = get_the_title( $post_id );
        $listing_url            = get_permalink( $post_id );
        $date_format            = get_option( 'date_format' );
        $time_format            = get_option( 'time_format' );
        $current_time           = current_time( 'timestamp' );
        $contact_email_subject  = get_directorist_option('email_sub_new_claim');
        $contact_email_body     = get_directorist_option('email_tmpl_new_claim');

        $placeholders = array(
            '==NAME=='            => $user->display_name,
            '==USERNAME=='        => $user->user_login,
            '==SITE_NAME=='       => $site_name,
            '==SITE_LINK=='       => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
            '==SITE_URL=='        => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
            '==LISTING_TITLE=='   => $listing_title,
            '==LISTING_LINK=='    => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
            '==LISTING_URL=='     => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
            '==SENDER_NAME=='     => $claimer_name,
            '==SENDER_PHONE=='    => $claimer_phone,
            '==MESSAGE=='         => $claimer_details,
            '==TODAY=='           => date_i18n( $date_format, $current_time ),
            '==NOW=='             => date_i18n( $date_format . ' ' . $time_format, $current_time )
        );

        $to      = $current_user_email;

        $subject = strtr( $contact_email_subject, $placeholders );

        $message = strtr( $contact_email_body, $placeholders );
        $message = nl2br( $message );

        $headers  = "From: {$claimer_name} <{$current_user_email}>\r\n";


        $is_mail_send = get_post_meta($post_id, '_claimed_primary_mail', true);
        if (empty($is_mail_send)){
            $success = ATBDP()->email->send_mail( $to, $subject, $message, $headers );
            if ($success){
                update_post_meta($post_id, '_claimed_primary_mail', 1);
            }
        }

    }
}/**
 * Send confermation to the listing owner.
 *
 * @since    1.0.0
 *
 * @return   string    $result    Message based on the result.
 */
if (!function_exists('dcl_email_admin_listing_claim')){
    function dcl_email_admin_listing_claim() {

        // sanitize form values
        $post_id = (int) $_POST["post_id"];
        $claimer_name    = sanitize_text_field( $_POST["claimer_name"] );
        $claimer_phone    = (int)( $_POST["claimer_phone"] );
        $claimer_details = stripslashes( esc_textarea( $_POST["claimer_details"] ) );
        //


        if (get_directorist_option('disable_email_notification')) return false;
        if(! in_array( 'new_claim_submitted', get_directorist_option('notify_admin', array('new_claim_submitted')) )) return false;
        // vars
        $site_name              = get_bloginfo( 'name' );
        $site_url               = get_bloginfo( 'url' );
        $currennt_user          = wp_get_current_user();
        $current_user_email     = $currennt_user->user_email;
        $listing_title          = get_the_title( $post_id );
        $listing_url            = get_permalink( $post_id );
        $date_format            = get_option( 'date_format' );
        $time_format            = get_option( 'time_format' );
        $current_time           = current_time( 'timestamp' );

        $placeholders = array(
            '{site_name}'     => $site_name,
            '{site_link}'     => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
            '{site_url}'      => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
            '{listing_title}' => $listing_title,
            '{listing_link}'  => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
            '{listing_url}'   => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
            '{sender_name}'   => $claimer_name,
            '{sender_email}'   => $current_user_email,
            '{sender_phone}'  => $claimer_phone,
            '{message}'       => $claimer_details,
            '{today}'         => date_i18n( $date_format, $current_time ),
            '{now}'           => date_i18n( $date_format . ' ' . $time_format, $current_time )
        );
        $send_emails = ATBDP()->email->get_admin_email_list();
        $to = !empty($send_emails) ? $send_emails : get_bloginfo('admin_email');

        $subject = __( '[{site_name}] Contact via "{listing_title}"', 'direo-extension' );
        $subject = strtr( $subject, $placeholders );

        $message =  __( "Dear Administrator,<br /><br />An user on your website {site_name} submit a claim for this listing .<br /><br />Listing URL: {listing_url}<br /><br />Name: {sender_name}<br />Email: {sender_email}<br />Claim Details: {message}<br />Time: {now}", 'direo-extension' );
        $message = strtr( $message, $placeholders );

        $headers  = "From: {$claimer_name} <{$current_user_email}>\r\n";

        $is_mail_send = get_post_meta($post_id, '_claimed_admin_primary_mail', true);
        if (empty($is_mail_send)){
            $success = ATBDP()->email->send_mail( $to, $subject, $message, $headers );
            if ($success){
                update_post_meta($post_id, '_claimed_admin_primary_mail', 1);
            }
        }

    }
}


/**
 * @since 1.0.0
 */
if (!function_exists('dcl_need_to_charge_with_plan')) {
    function dcl_need_to_charge_with_plan()
    {
        // sanitize form values
        $post_id = (int)$_POST["post_id"];
        $claimer_plan_id = isset($_POST["claimer_plan"]) ? (int)($_POST["claimer_plan"]) : '';
        $claim_charge_by = get_directorist_option('claim_charge_by');
        $charged_by = get_post_meta($post_id, '_claim_fee', true);
        $charged_by = ($charged_by !== '')?$charged_by:$claim_charge_by;
        $has_plans = is_pricing_plans_active();
        //check is pricing plan active and admin decided to charge claimer by plans
        if (!empty($has_plans)) {
            $activated_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $claimer_plan_id);
            if (class_exists('ATBDP_Pricing_Plans')){
                $order_id = !empty($activated_plan)?(int)$activated_plan[0]->ID:'';
            }else{
                $order_id = !empty($activated_plan)?(int)$activated_plan->ID:'';
            }
            $user_regular_listing = listings_data_with_plan(get_current_user_id(), '0', $claimer_plan_id, $order_id);
            $num_regular = get_post_meta($claimer_plan_id, 'num_regular', true);
            $featured_listing = get_post_meta($claimer_plan_id, 'is_featured_listing', true);
            $plan_type = package_or_PPL($claimer_plan_id);
            //is charged by plans checked
            if ('pricing_plan' === $charged_by){
                // ok lets check is user selected plan is package
                if ('package' === $plan_type) {
                    if (empty($activated_plan)) {
                        //need to collect money form claimer
                        return false;
                    } else {
                            update_post_meta($post_id, '_plan_order_id', $order_id);
                            update_post_meta($post_id, '_fm_plans', $claimer_plan_id);
                            //ok user has activated package
                            return true;
                    }
                } else {
                    //pay per listing so collect money
                    $package_length = get_post_meta($claimer_plan_id, 'fm_length', true);
                    $package_length = $package_length ? $package_length : '1';
                    // Current time
                    $current_d = current_time('mysql');
                    // Calculate new date
                    $date = new DateTime( $current_d );
                    $date->add( new DateInterval( "P{$package_length}D" ) ); // set the interval in days
                    $expired_date = $date->format( 'Y-m-d H:i:s' );
                    $is_never_expaired = get_post_meta($claimer_plan_id, 'fm_length_unl', true);
                    if ($is_never_expaired){
                        update_post_meta( $post_id, '_never_expire', '1' );
                    }else{
                        update_post_meta( $post_id, '_expiry_date', $expired_date );
                    }
                    update_post_meta($post_id, '_claimer_plans', $claimer_plan_id);

                    if (!empty($featured_listing)){
                        update_post_meta($_POST['post_id'], '_need_featured', 1);
                    }
                    return false;
                }
            }else{
                //through fee plan active but admin decided to charge or not by others
                return true;
            }
        }else{
            //plans extension is not active
            return true;
        }
    }
}
if (!function_exists('dcl_need_to_charge_without_plan')){
    function dcl_need_to_charge_without_plan(){
        $manual_charge = need_claim_to_charge_manually();
        if (!empty($manual_charge)){
            return false;
        }else{
            return true;
        }
    }
}


/**
 * @since 1.0.0
 */
if (!function_exists('need_claim_to_charge_manually')){
    function need_claim_to_charge_manually(){
        $post_id = (int)$_POST["post_id"];
        $claim_charge_by = get_directorist_option('claim_charge_by');
        $charged_by = get_post_meta($post_id, '_claim_fee', true);
        $charged_by = ($charged_by !== '')?$charged_by:$claim_charge_by;
        if ('static_fee' === $charged_by){
            return true;
        }else{
            return false;
        }
    }
}
/**
 * @since 1.0.0
 */
if (!function_exists('non_paid_claim')){
    function non_paid_claim(){
        $post_id = (int)$_POST["post_id"];
        $charged_by = get_post_meta($post_id, '_claim_fee', true);
        if ('free_claim' === $charged_by){
            return true;
        }else{
            return false;
        }
    }
}

/**
 * @since 1.0.0
 */
if (!function_exists('dcl_new_claim')){
    function dcl_new_claim($listing_id){
        $claim_id = wp_insert_post( array(
            'post_content' => '',
            'post_title' => get_the_title($listing_id),
            'post_status' => 'publish',
            'post_type' => 'dcl_claim_listing',
            'comment_status' => false,
        ) );

        $claimer_phone = get_post_meta($listing_id, '_claimer_phone', true);
        $claimer_name =  get_post_meta($listing_id, '_claimer_name', true);
        $claimer_detail =  get_post_meta($listing_id, '_claimer_details', true);
        $claimer_details = "This is $claimer_name, $claimer_detail";

        update_post_meta( $claim_id, '_listing_claimer', get_current_user_id());
        update_post_meta( $claim_id, '_claimed_listing', $listing_id);
        update_post_meta( $claim_id, '_claim_status', 'pending' );
        update_post_meta( $claim_id, '_claimer_details', $claimer_details );
        update_post_meta( $claim_id, '_claimer_phone', $claimer_phone );
    }
}

/**
 * @since 1.0.0
 * check is user already submitted claim for this listing
 */

if (!function_exists('dcl_tract_duplicate_claim')){
    function dcl_tract_duplicate_claim($claimer, $listing){
        $claims = new WP_Query(array(
            'post_type' => 'dcl_claim_listing',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query'  => array(
                'relation' => 'AND',
                array(
                    'key'   => '_listing_claimer',
                    'value' => $claimer,
                ),
                array(
                    'key'   => '_claimed_listing',
                    'value' => $listing,
                ),
                array(
                    'key'   => '_claim_status',
                    'value' => 'pending',
                )
            )
        ));
        $claim_meta = array();
        foreach ($claims->posts as $key => $val){
            $claim_meta[] = !empty($val)?$val:array();
        }
        return ($claim_meta) ? $claim_meta : false;
    }
}

/**
 * @since 1.2
 * @package Dirctorist
 * @param integer   $plan_id - The selected plan id
 */

if (!function_exists('dcl_claimed_plan_allowances')){
    function dcl_claimed_plan_allowances($plan_id)
    {
        $has_plans = is_pricing_plans_active();
        //check is pricing plan active and admin decided to charge claimer by plans
        if (!empty($has_plans)) {
            $plan_type = package_or_PPL($plan_id);
            $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $plan_id);
            $order_id = !empty($active_plan) ? (int)$active_plan[0]->ID : '';
            $user_id = get_current_user_id();
            $user_featured_listing = listings_data_with_plan($user_id, '1', $plan_id, $order_id);
            $user_regular_listing = listings_data_with_plan($user_id, '0', $plan_id, $order_id);
            $num_regular = get_post_meta($plan_id, 'num_regular', true);
            $num_featured = get_post_meta($plan_id, 'num_featured', true);
            $num_featured_unl = get_post_meta($plan_id, 'num_featured_unl', true);
            $num_regular_unl = get_post_meta($plan_id, 'num_regular_unl', true);
            $total_regular_listing = $num_regular;
            $total_featured_listing = $num_featured;
            //get the active package info

            if ($active_plan) {
                $listing_id = get_post_meta($active_plan[0]->ID, '_listing_id', true);
                $featured = get_post_meta($listing_id, '_featured', true);
                $total_regular_listing = $num_regular - ('0' === $featured ? $user_regular_listing + 1 : $user_regular_listing);
                $total_featured_listing = $num_featured - ('1' === $featured ? $user_featured_listing + 1 : $user_featured_listing);
            }
            $listing_type = !empty($_POST['listing_type']) ? sanitize_text_field($_POST['listing_type']) : '';

            if (('regular' === $listing_type) && ('package' === $plan_type)) {

                if ((($num_regular < $total_regular_listing) || (0 >= $total_regular_listing)) && empty($num_regular_unl)) {
                    return false;
                } else {
                    $package_length = get_post_meta($plan_id, 'fm_length', true);
                    $package_length = $package_length ? $package_length : '1';
                    // Current time
                    $current_d = current_time('mysql');
                    // Calculate new date
                    $date = new DateTime( $current_d );
                    $date->add( new DateInterval( "P{$package_length}D" ) ); // set the interval in days
                    $expired_date = $date->format( 'Y-m-d H:i:s' );
                    $is_never_expaired = get_post_meta($plan_id, 'fm_length_unl', true);
                    if ($is_never_expaired){
                        update_post_meta( $_POST['post_id'], '_never_expire', '1' );
                    }else{
                        update_post_meta( $_POST['post_id'], '_expiry_date', $expired_date );
                    }
                    update_post_meta($_POST['post_id'], '_fm_plans', $plan_id);
                    return true;
                }
            }
            elseif (('featured' === $listing_type) && ('package' === $plan_type)) {
                if ((($num_featured < $total_featured_listing) || (0 === $total_featured_listing)) && empty($num_featured_unl)) {
                    return false;
                } else {
                    $package_length = get_post_meta($plan_id, 'fm_length', true);
                    $package_length = $package_length ? $package_length : '1';
                    // Current time
                    $current_d = current_time('mysql');
                    // Calculate new date
                    $date = new DateTime( $current_d );
                    $date->add( new DateInterval( "P{$package_length}D" ) ); // set the interval in days
                    $expired_date = $date->format( 'Y-m-d H:i:s' );
                    $is_never_expaired = get_post_meta($plan_id, 'fm_length_unl', true);
                    if ($is_never_expaired){
                        update_post_meta( $_POST['post_id'], '_never_expire', '1' );
                    }else{
                        update_post_meta( $_POST['post_id'], '_expiry_date', $expired_date );
                    }
                    // lets update the listing as featured
                    update_post_meta($_POST['post_id'], '_need_featured', 1);
                    return true;
                }
            }else{
                return true;
            }
        }else{
            return true;
        }
    }
}



/**
 * Get all the settings fields for the edited listing email template section
 * @since 3.1.0
 * @return array
 */
function dcl_user_email_edit_tmpl_settings_fields()
{

    // let's define default data
    $sub = __('[==SITE_NAME==] : Your Claim for (#==LISTING_TITLE==) Received.', 'direo-extension');
    $tmpl = sprintf(__("
Dear ==NAME==,

Thank you very much for your claim.
This email is to notify you that your claim for (#==LISTING_TITLE==) has been received.

%s

Please wait for the confirmation by administrator.
Listing Details Page: ==LISTING_URL==


Thanks,
The Administrator of ==SITE_NAME==
", 'direo-extension'), '');

    return apply_filters('dcl_new_claim_tmpl_settings_fields', array(
        array(
            'type' => 'textbox',
            'name' => 'email_sub_new_claim',
            'label' => __('Email Subject', 'direo-extension'),
            'description' => __('Edit the subject for sending to the user when a claim is submitted.', 'direo-extension'),
            'default' => $sub,
        ),
        array(
            'type' => 'textarea',
            'name' => 'email_tmpl_new_claim',
            'label' => __('Email Body', 'direo-extension'),
            'description' => __('Edit the email template for sending to the user when a claim is submitted.', 'direo-extension'),
            'default' => $tmpl,
        ),


    ));
}

/**
 * Get all the settings fields for the edited listing email template section
 * @since 3.1.0
 * @return array
 */
function dcl_user_approved_confirmation_email_edit_tmpl_settings_fields()
{
    // let's define default data
    $sub = __('[==SITE_NAME==] : Claim Confirmation for Claim for (#==LISTING_TITLE==)', 'direo-extension');
    $tmpl = __("
Dear ==NAME==,
Congratulations! Your Claim for '==LISTING_TITLE==' has been confirmed. You can now edit it from your Dashboard ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", 'direo-extension');

    return apply_filters('atbdp_email_approved_tmpl_settings_fields', array(
        array(
            'type' => 'textbox',
            'name' => 'email_sub_approved_claim',
            'label' => __('Email Subject', 'direo-extension'),
            'description' => __('Edit the subject of approved claim notification for user.', 'direo-extension'),
            'default' => $sub,
        ),
        array(
            'type' => 'textarea',
            'name' => 'email_tmpl_approved_claim',
            'label' => __('Email Body', 'direo-extension'),
            'description' => __('Edit the email template of approved claim notification for user. HTML content is allowed too.', 'direo-extension'),
            'default' => $tmpl,
        ),
    ));
}
/**
 * Get all the settings fields for the edited listing email template section
 * @since 3.1.0
 * @return array
 */
function dcl_user_declined_confirmation_email_edit_tmpl_settings_fields()
{
    // let's define default data
    $sub = __('[==SITE_NAME==] : Claim Confirmation for Claim for (#==LISTING_TITLE==)', 'direo-extension');
    $tmpl = __("
Dear ==NAME==,
Your Claim for '==LISTING_TITLE==' has been declined. Please contact with administrator.

Thanks,
The Administrator of ==SITE_NAME==
", 'direo-extension');

    return apply_filters('atbdp_email_declined_tmpl_settings_fields', array(
        array(
            'type' => 'textbox',
            'name' => 'email_sub_declined_claim',
            'label' => __('Email Subject', 'direo-extension'),
            'description' => __('Edit the subject of declined claim notification for user.', 'direo-extension'),
            'default' => $sub,
        ),
        array(
            'type' => 'textarea',
            'name' => 'email_tmpl_declined_claim',
            'label' => __('Email Body', 'direo-extension'),
            'description' => __('Edit the email template of declined claim notification for user. HTML content is allowed too.', 'direo-extension'),
            'default' => $tmpl,
        ),
    ));
}