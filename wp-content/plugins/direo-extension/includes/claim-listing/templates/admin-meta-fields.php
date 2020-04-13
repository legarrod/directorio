<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
$post_meta = get_post_meta( $post->ID ) ? get_post_meta( $post->ID ) : '' ;
$claimer_phone = get_post_meta($post->ID, '_claimer_phone',true);
?>
<table class="atbdp-input widefat" id="atbdp-field-details">

    <tbody>
    <tr class="field-type">
        <td class="label">
            <label for="dcl_select_listing" class="widefat"><?php _e( 'Select Listing for Claim', 'direo-extension' ); ?></label>
        </td>
        <td class="field_lable">
            <select id="dcl_select_listing" name="claimed_listing" class="atbdp-radio-list radio horizontal">
       <?php
                   $current_val = isset($post_meta['_claimed_listing']) ? esc_attr($post_meta['_claimed_listing'][0]) : '';

                    echo '<option>' . __("-Select a Listing-", 'direo-extension') . '</option>';
                       $args = array(
                       'post_type'      => ATBDP_POST_TYPE,
                       'post_status'    => 'publish',
                       'posts_per_page' => -1,
                       );
                       $listings = new WP_Query($args);
                       if ($listings->have_posts()){
                       while ( $listings->have_posts() ) {
                       $listings->the_post();
                    printf('<option value="%s" %s>%s</option>', get_the_ID(), selected(get_the_ID(), $current_val), get_the_title());
                       }
                       }
                       wp_reset_postdata();
                       ?>
            </select>
        </td>
    </tr>

    <tr class="field-type">
        <td class="label">
            <label for="dcl_listing_claimer" class="widefat"><?php _e( 'Claimed by', 'direo-extension' ); ?></label>
        </td>
        <td class="field_lable">
            <select id="dcl_listing_claimer" name="listing_claimer" class="atbdp-radio-list radio horizontal">
       <?php
                    $current_author = isset( $post_meta['_listing_claimer'] ) ? esc_attr($post_meta['_listing_claimer'][0]) : '';
                    $users = get_users();
                    echo '<option>' . __("-Select user-", 'direo-extension') . '</option>';
                    foreach($users as $user_id){
                    printf('<option value="%s" %s>%s</option>', $user_id->ID, selected($user_id->ID, $current_author), $user_id->display_name);
                    }?>
            </select>
        </td>
    </tr>

    <tr class="field-type">
        <td class="label">
            <label for="dcl_claim_status" class="widefat"><?php _e( 'Status', 'direo-extension' ); ?></label>
        </td>
        <td class="field_lable">
            <select id="dcl_claim_status" name="claim_status" class="atbdp-radio-list radio horizontal">
                <?php
                $current_status = isset( $post_meta['_claim_status'] ) ? esc_attr($post_meta['_claim_status'][0]) : '';
                $status = dcl_claim_status();
                foreach($status as $key => $value){
                    printf('<option value="%s" %s>%s</option>', $key, selected($key, $current_status), $value);
                }?>
            </select>
        </td>
    </tr>

    <tr class="field-instructions">
        <td class="label">
            <label for="dcl_claimer_details"><?php _e( 'Claimer Details', 'direo-extension' ); ?></label>
        </td>
        <td>
            <textarea id="dcl_claimer_details" class="textarea" name="claimer_details" rows="6" cols="64"><?php if( isset( $post_meta['_claimer_details'] ) ) echo esc_textarea( $post_meta['_claimer_details'][0] ); ?></textarea>
        </td>
    </tr>


    <tr class="field-instructions">
        <td class="label">
            <label for="dcl_claimer_phone"><?php _e( 'Claimer Phone', 'direo-extension' ); ?></label>
        </td>

        <td>
            <input id="dcl_claimer_phone" type="tel" value="<?php echo !empty($claimer_phone)?($claimer_phone):'';?>" name="claimer_phone">
        </td>
    </tr>
    </tbody>
</table>
