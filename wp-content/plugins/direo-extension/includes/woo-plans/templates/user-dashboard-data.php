<?php
$user_id = get_current_user_id();
if (!class_exists('WooCommerce')) return false;
$filters = array(
    'post_type' => 'shop_order',
    'post_status'    => 'wc-completed',
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
            'compare' => 'EXISTS'
        )
    ),
);


$loop = new WP_Query($filters);
$plan_type = array();
while ($loop->have_posts()) {
$loop->the_post();
$all_order_ids[] = $loop->post->ID;
$order = new WC_Order($loop->post->ID);
foreach ($order->get_items() as $key => $lineItem) {
    $order_data[] = $order->get_items();
    $subscribed_package_id = $lineItem->get_data()['product_id'];
    $plan_type[] = get_post_meta($subscribed_package_id, 'plan_type', true);
    }
}
?>

<div <?php echo apply_filters('atbdp_dashboard_package_content_div_attributes', 'class="atbd_tab_inner" id="manage_fees"'); ?>>
<?php
if (in_array('package', $plan_type)) {
    ?>
        <div class="atbd_manage_fees_wrapper">
            <?php
            /**
             * @since 1.2.3
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
                $all_order_ids = array();
                $order_data = array();
                while ($loop->have_posts()) {
                $loop->the_post();
                $all_order_ids[] = $loop->post->ID;
                $order = new WC_Order($loop->post->ID);
                foreach ($order->get_items() as $key => $lineItem) {
                    $order_data[] = $order->get_items();
                    $subscribed_package_id = $lineItem->get_data()['product_id'];

                    $plan_type = get_post_meta($subscribed_package_id, 'plan_type', true);
                    if ($plan_type === 'package') {
                        //$subscribed_date = $subscribed_plan_info[0]->post_date;
                        //$order_id = $subscribed_plan_info[0]->ID;
                        $gateway = $order->get_payment_method_title();
                        $subscribed_package_dates = $order->get_date_completed();
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
                        $user_featured_listing = listings_data_with_plan($user_id, '1', $subscribed_package_id);
                        $user_regular_listing = listings_data_with_plan($user_id, '0', $subscribed_package_id);
                        $num_regular = get_post_meta($subscribed_package_id, 'num_regular', true);
                        $num_featured = get_post_meta($subscribed_package_id, 'num_featured', true);
                        $total_regular_listing = (int)$num_regular - $user_regular_listing;
                        $total_featured_listing = (int)$num_featured - $user_featured_listing;
                        //lets check how many listing already submitted by this user

                        // Current time
                        $start_date = !empty($subscribed_package_dates) ? $subscribed_package_dates : '';
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
                           /* esc_url(ATBDP_Permalink::get_fee_renewal_checkout_page_link($subscribed_package_id)),
                            __('Renew', 'direo-extension'),*/
                            //td 2
                            get_woocommerce_currency_symbol() . ' - ' . $lineItem->get_data()["total"],
                            //td 3
                            !empty($num_regular_unl)?__('Unlimited Regular listing ', 'direo-extension'):__('Regular listing ', 'direo-extension') . esc_attr($total_regular_listing),
                            !empty($num_featured_unl)?__('Unlimited Featured listing ', 'direo-extension'):__('Featured listing ', 'direo-extension') . esc_attr($total_featured_listing),
                            //td 4
                            $gateway,
                            //td 5
                            date('Y-m-d', strtotime($start_date))

                        );
                    }
                }//end foreach
                }//end while
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
         * @since 1.2.3
         */
        do_action('atbdp_before_order_table');
        ?>
        <table class="table table-bordered atbd_single_saved_item table-responsive-sm">

            <thead>
            <tr>
                <th><?php _e('Order Number', 'direo-extension') ?></th>
                <th><?php _e('Amount', 'direo-extension') ?></th>
                <th><?php _e('Oder Date', 'direo-extension') ?></th>
                <th><?php _e('Plan Name', 'direo-extension') ?></th>
                <th><?php _e('Payment Receipt', 'direo-extension') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($loop->have_posts()) {
            $loop->the_post();
            $all_order_ids[] = $loop->post->ID;
            $order = new WC_Order($loop->post->ID);
            foreach ($order->get_items() as $key => $lineItem) {
                $subscribed_package_id = $lineItem->get_data()['product_id'];
                $subscribed_date = $order->get_date_completed();
                $plan_name = $lineItem->get_name();
                // Current time
                $start_date = !empty($subscribed_date) ? $subscribed_date : '';

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
                    '#' . $order->get_order_number(),
                    //td 2
                    get_woocommerce_currency_symbol() . ' - ' . $order->get_total(),
                    //td 3
                    date('Y-m-d', strtotime($start_date)),
                    //td 4
                    $plan_name,
                    //td 5
                    '<span class="btn btn-block"><a href=' . $order->get_view_order_url() . '>' . __('View', 'direo-extension') . '</a></span>'

                );
            }
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