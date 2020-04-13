<?php
$user_id = get_current_user_id();
$orders = new WP_Query(array(
    'post_type' => 'atbdp_orders',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'author'       => $user_id,
    'meta_key'   => '_payment_status',
    'meta_value' => 'completed',

));
$subscribed_package_ids = array();
$subscribed_package_dates = array();
$all_order_ids = array();
foreach ($orders->posts as $key => $val){
    $all_plan_ids = get_post_meta($val->ID, '_fm_plan_ordered', true); //data form order table
    $all_order_ids[] = $all_plan_ids;
    $plan_type = get_post_meta($all_plan_ids, 'plan_type', true); //data form Pricing Plans table
    if ('package' === $plan_type){
        $subscribed_package_ids[] = $all_plan_ids;
        $subscribed_package_dates[] = $val->post_date;
    }
}?>

    <div <?php echo apply_filters('atbdp_dashboard_package_content_div_attributes', 'class="atbd_tab_inner" id="manage_fees"'); ?>>
<?php
if (!empty($subscribed_package_ids)) {
    ?>
        <div class="atbd_manage_fees_wrapper">
           <?php
           /**
            * @since 1.5.3
            */
           do_action('atbdp_before_package_table');
           ?>
            <table class="table table-bordered atbd_single_saved_item table-responsive-sm">

                <thead>
                <tr>
                    <th><?php _e('Package Name', 'direo-extension') ?></th>
                    <th><?php _e('Amount', 'direo-extension') ?></th>
                    <th><?php _e('Remaining listings', 'direo-extension') ?></th>
                    <th><?php _e('Payment Gateway', 'direo-extension') ?></th>
                    <th><?php _e('Order Date', 'direo-extension') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($subscribed_package_ids as $subscribed_package_id) {
                    $subscribed_plan_info = subscribed_package_or_PPL_plans($user_id, 'completed', $subscribed_package_id);

                    $subscribed_date = $subscribed_plan_info[0]->post_date;
                    $order_id = $subscribed_plan_info[0]->ID;
                    $gateway = get_post_meta($order_id, '_payment_gateway', true);
                    $plan_meta = get_post_meta($subscribed_package_id);
                    $package_length = get_post_meta($subscribed_package_id, 'fm_length', true);
                    $is_never_expaired = get_post_meta($subscribed_package_id, 'fm_length_unl', true);
                    $fm_tag_limit = get_post_meta($subscribed_package_id, 'fm_tag_limit', true);
                    $fm_tag_limit_unl = get_post_meta($subscribed_package_id, 'fm_tag_limit_unl', true);
                    $package_length = $package_length ? $package_length : '1';
                    $plan_name = get_the_title($subscribed_package_id);
                    $plan_metas = get_post_meta($subscribed_package_id);
                    $num_regular_unl = esc_attr($plan_metas['num_regular_unl'][0]);
                    $num_featured_unl = esc_attr($plan_metas['num_featured_unl'][0]);
                    //calculate regular listing number
                    $user_featured_listing = listings_data_with_plan($user_id, '1', $subscribed_package_id, $order_id);
                    $user_regular_listing = listings_data_with_plan($user_id, '0', $subscribed_package_id, $order_id);
                    $num_regular = get_post_meta($subscribed_package_id, 'num_regular', true);
                    $num_featured = get_post_meta($subscribed_package_id, 'num_featured', true);
                    $listing_id = get_post_meta($subscribed_plan_info[0]->ID, '_listing_id', true);
                    $featured = get_post_meta($listing_id, '_featured', true);
                    $total_regular_listing = $num_regular - ('0' === $featured?$user_regular_listing+1:$user_regular_listing);
                    $total_featured_listing = $num_featured - ('1' === $featured?$user_featured_listing+1:$user_featured_listing);
                    //lets check how many listing already submitted by this user

                    // Current time
                    $start_date = !empty($subscribed_date) ? $subscribed_date : '';
                    // Calculate new date
                    $date = new DateTime($start_date);
                    $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                    $expired_date = $date->format('Y-m-d H:i:s');
                    $current_d = current_time('mysql');
                    $remaining_days = ($expired_date > $current_d) ? (floor(strtotime($expired_date) / (60 * 60 * 24)) - floor(strtotime($current_d) / (60 * 60 * 24))) : 0; //calculate the number of days remaining in a plan


                    printf(' <tr>
                                            <td class="package_name">
                                               <p>%s</p>
                                            </td>

                                            <td class="package_amount">
                                               <p>%s</p>
                                            </td>  
                                            
                                            <td class="package_remaining">
                                               <p>%s</p>
                                               <p>%s</p>
                                              
                                            </td>  
                                            
                                            <td class="package_gateway">
                                               <p>%s</p>
                                               
                                            </td>
                                            
                                            <td class="package_start">
                                               <p>%s</p>
                                            </td>
                                        </tr>',
                        //td 1
                        $plan_name,
                        //td 2
                        atbdp_get_payment_currency() . ' - ' . $plan_meta['fm_price'][0] . (!empty($plan_meta['price_decimal'][0]) ? '.' . $plan_meta['price_decimal'][0] : ''),
                        //td 3
                        !empty($num_regular_unl)?__('Unlimited Regular listing ', 'direo-extension'):__('Regular listing ', 'direo-extension') . esc_attr($total_regular_listing),
                        !empty($num_featured_unl)?__('Unlimited Featured listing ', 'direo-extension'):__('Featured listing ', 'direo-extension') . esc_attr($total_featured_listing),
                        //td 4
                        ('bank_transfer' === $gateway) ? __('Bank Transfer', 'direo-extension') : (('free' === $gateway ? __('Free', 'direo-extension') : (('paypal_gateway' == $gateway) ? __('PayPal', 'direo-extension') : (('stripe_gateway' === $gateway) ? __('Stripe', 'direo-extension') : __('Unknown', 'direo-extension'))))),
                        //td 5
                        date('Y-m-d', strtotime($start_date))

                    );
                }
                ?>
                </tbody>
            </table>
        </div>
    <?php
}else{
    $text = '<p>'.__("No package found!", 'direo-extension').'</p>';
    echo apply_filters('atbdp_no_package_found_text', $text);
}
?>
    </div>


<div <?php echo apply_filters('atbdp_dashboard_orderHistory_content_div_attributes', 'class="atbd_tab_inner" id="manage_invoices"'); ?>>
<?php
if (!empty($all_order_ids)){
    ?>
    <div class="atbd_manage_fees_wrapper">
        <?php
        /**
         * @since 1.5.3
         */
        do_action('atbdp_before_order_table');
        ?>
        <table class="table table-bordered atbd_single_saved_item table-responsive-sm">

            <thead>
            <tr>
                <th><?php _e('Order Number', 'direo-extension') ?></th>
                <th><?php _e('Amount', 'direo-extension') ?></th>
                <th><?php _e('Order Date', 'direo-extension') ?></th>
                <th><?php _e('Plan Name', 'direo-extension') ?></th>
                <th><?php _e('Payment Receipt', 'direo-extension') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($all_order_ids as $all_order_id) {
                $subscribed_plan_info = subscribed_package_or_PPL_plans($user_id, 'completed', $all_order_id);

                $subscribed_date = !empty($subscribed_plan_info[0]->post_date)?$subscribed_plan_info[0]->post_date:'';
                $order_id = !empty($subscribed_plan_info[0]->ID)?$subscribed_plan_info[0]->ID:'';
                $gateway = get_post_meta($order_id, '_payment_gateway',true);
                $plan_meta = get_post_meta($all_order_id);
                $package_length = get_post_meta($all_order_id, 'fm_length', true);
                $is_never_expaired = get_post_meta($all_order_id, 'fm_length_unl', true);
                $fm_tag_limit = get_post_meta($all_order_id, 'fm_tag_limit', true);
                $fm_tag_limit_unl = get_post_meta($all_order_id, 'fm_tag_limit_unl', true);
                $package_length = $package_length ? $package_length : '1';
                $plan_name = get_the_title($all_order_id);
                //calculate regular listing number
                $has_featured_type = get_user_meta($user_id, '_featured_type',true) ? (int) get_user_meta($user_id, '_featured_type',true) : 0;
                $has_general_type = get_user_meta($user_id, '_general_type',true) ? (int) get_user_meta($user_id, '_general_type',true) : 0;
                $num_regular = get_post_meta($all_order_id, 'num_regular', true);
                $num_featured = get_post_meta($all_order_id, 'num_featured', true);
                $total_regular_listing = (int)$num_regular - $has_general_type;
                $total_featured_listing = (int)$num_featured - $has_featured_type;
                //lets check how many listing already submitted by this user

                // Current time
                $start_date = !empty($subscribed_date) ? $subscribed_date : '';
                // Calculate new date
                $date = new DateTime( $start_date );
                $date->add( new DateInterval( "P{$package_length}D" ) ); // set the interval in days
                $expired_date = $date->format( 'Y-m-d H:i:s' );
                $current_d = current_time('mysql');
                $fm_price = !empty($plan_meta['fm_price'][0])?$plan_meta['fm_price'][0]:'';
                $remaining_days = ($expired_date > $current_d) ? (floor(strtotime($expired_date)/(60*60*24)) - floor(strtotime($current_d)/(60*60*24))) : 0; //calculate the number of days remaining in a plan


                printf(' <tr>
                                            <td class="order_no">
                                               <p>%s</p>
                                            </td>

                                            <td class="package_amount">
                                               <p>%s</p>
                                            </td>  
                                            
                                            <td class="date">
                                               <p>%s</p>
                                            </td>  
                                            
                                            <td class="name">
                                               <p>%s</p>
                                            </td>
                                            
                                            <td class="action">
                                               <p>%s</p>
                                            </td>
                                        </tr>',
                    //td 1
                    '#'.$order_id,
                    //td 2
                    atbdp_get_payment_currency().' - '.$fm_price.(!empty($plan_meta['price_decimal'][0])?'.'.$plan_meta['price_decimal'][0]:''),
                    //td 3
                    date('Y-m-d',strtotime($start_date)),
                    //td 4
                    $plan_name,
                    //td 5
                    '<span class="btn btn-block"><a href='.ATBDP_Permalink::get_payment_receipt_page_link($order_id).'>'.__('View', 'direo-extension').'</a></span>'

                );
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
}else{
    $text = '<p>'.__("No order found!", 'direo-extension').'</p>';
    echo apply_filters('atbdp_no_order_found_text', $text);
}
?>
</div>