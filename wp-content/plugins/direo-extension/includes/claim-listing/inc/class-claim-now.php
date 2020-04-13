<?php
/**
 * Adds DCL_Claim_Now widget.
 */
class DCL_Claim_Now extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_options = array(
            'classname' => 'atbd_widget',
            'description' => __('You can show Claim Now button on the sidebar of every single listing ( listing details page ) by this widget ', 'direo-extension'),
        );
        parent::__construct(
            'dcl_widget', // Base ID, must be unique
            __( 'Directorist - Claim Listing', 'direo-extension' ), // Name
            $widget_options // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        global $post;
        $listing_id = $post->ID;
        if (!get_directorist_option('enable_claim_listing',1)) return; // vail if the business hour is not enabled
        if (get_directorist_option('non_widger_claim_button',0)) return; // vail if the business hour is not enabled
        $claim_header = get_directorist_option('claim_widget_title',esc_html__('Is this your business?', 'direo-extension'));
        $claim_description = get_directorist_option('claim_widget_description',esc_html__('Claim listing is the best way to manage and protect your business.', 'direo-extension'));
        $claim_now = get_directorist_option('claim_now',esc_html__('Claim Now!', 'direo-extension'));
        $claimed_by_admin = get_post_meta($listing_id, '_claimed_by_admin',true);
        $claim_fee = get_post_meta($listing_id, '_claim_fee',true);
        if ($claimed_by_admin || ('claim_approved' === $claim_fee))return;
        if( is_singular(ATBDP_POST_TYPE)) {
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Title', 'direo-extension');
            echo $args['before_widget'];
            echo '<div class="atbd_widget_title">';
            echo $args['before_title'] . esc_html(apply_filters('widget_submit_item_title', $title)) . $args['after_title'];
            echo '</div>';
            $claim_class = apply_filters('atbdp_claim_data_target_id', 'dcl-claim-modal');
            ?>
            <div class="directorist">
                <?php if (is_user_logged_in()) { ?>
                    <div class="dcl_promo-item_group">
                        <h4 class="dcl_promo-item_title"><?php _e("$claim_header", 'direo-extension')?></h4>
                        <p class="dcl_promo-item_description"><?php _e("$claim_description", 'direo-extension')?></p>
                        <a href="<?php do_action('atbdp_claim_now_link', $listing_id); ?>" data-target="<?php echo $claim_class; ?>"
                           class="<?= atbdp_directorist_button_classes(); ?>"><i class="<?php atbdp_icon_type(true);?>
-check-square-o"></i>&nbsp; <?php _e("$claim_now", 'direo-extension')?></a>
                    </div>
                <?php } else { ?>
                    <div class="dcl_promo-item_group">
                        <h3 class="dcl_promo-item_title"><?php _e("$claim_header", 'direo-extension')?></h3>
                        <p class="dcl_promo-item_description"><?php _e("$claim_description", 'direo-extension')?></p>
                        <a href="" class="dcl_login_alert <?= atbdp_directorist_button_classes(); ?>"><?php _e("$claim_now", 'direo-extension')?></a>
                        <div class="dcl_login_notice atbd_notice alert alert-info" role="alert">
                            <span class="fa fa-info-circle" aria-hidden="true"></span>
                            <?php
                            // get the custom registration page id from the db and create a permalink
                            $reg_link_custom = ATBDP_Permalink::get_registration_page_link();
                            //if we have custom registration page, use it, else use the default registration url.
                            $reg_link = !empty($reg_link_custom) ? $reg_link_custom : wp_registration_url();

                            $login_url = apply_filters('atbdp_claim_now_login_link', '<a href="' . ATBDP_Permalink::get_login_page_link() . '">' . __('Login', 'direo-extension') . '</a>');
                            $register_url = apply_filters('atbdp_claim_now_registration_link', '<a href="' . esc_url($reg_link) . '">' . __('Register', 'direo-extension') . '</a>');

                            printf(__('You need to %s or %s to claim this listing', 'direo-extension'), $login_url, $register_url);
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <input type="hidden" id="dcl-post-id" value="<?php echo get_the_ID(); ?>"/>
            </div>
            <?php
            echo $args['after_widget'];
            ?>
            <div class="at-modal atm-fade" id="dcl-claim-modal">
                <div class="at-modal-content at-modal-lg">
                    <div class="atm-contents-inner">
                        <a href="" class="at-modal-close"><span aria-hidden="true">&times;</span></a>
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <form id="dcl-claim-listing-form" class="form-vertical" role="form">
                                    <div class="modal-header">
                                        <h3 class="modal-title"
                                            id="dcl-claim-label"><?php _e('Claim This Listing', 'direo-extension'); ?></h3>

                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="dcl_claimer_name"><?php _e('Full Name', 'direo-extension'); ?>
                                                <span class="atbdp_make_str_red">*</span></label>
                                            <input type="text" class="form-control" id="dcl_claimer_name"  placeholder="<?php _e('Full Name', 'direo-extension'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="dcl_claimer_phone"><?php _e('Phone', 'direo-extension'); ?>
                                                <span class="atbdp_make_str_red">*</span></label>
                                            <input type="tel" class="form-control" id="dcl_claimer_phone"  placeholder="<?php _e('111-111-235', 'direo-extension'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="dcl_claimer_details"><?php _e('Verification Details', 'direo-extension'); ?>
                                                <span class="atbdp_make_str_red">*</span></label>
                                            <textarea class="form-control" id="dcl_claimer_details"
                                                      rows="3"
                                                      placeholder="<?php _e('Details description about your business', 'direo-extension'); ?>..."
                                                      required></textarea>
                                        </div>
                                        <div class="form-group dcl_pricing_plan">
                                            <?php
                                            $claim_charge_by = get_directorist_option('claim_charge_by');
                                            $charged_by = get_post_meta($listing_id, '_claim_fee', true);
                                            $charged_by = ($charged_by !== '')?$charged_by:$claim_charge_by;
                                            $has_plans = is_pricing_plans_active();
                                            if (!empty($has_plans) && ('pricing_plan' === $charged_by)){
                                                if (class_exists('ATBDP_Pricing_Plans')){
                                                    $args = array(
                                                        'post_type'      => 'atbdp_pricing_plans',
                                                        'posts_per_page' => -1,
                                                        'status'         => 'publish',
                                                        'meta_query' => array(
                                                            'relation' => 'OR',
                                                            array(
                                                                'key' => '_hide_from_plans',
                                                                'compare' => 'NOT EXISTS',
                                                            ),
                                                            array(
                                                                'key' => '_hide_from_plans',
                                                                'value'   => 1,
                                                                'compare' => '!=',
                                                            ),
                                                        ),
                                                    );

                                                    $atbdp_query = new WP_Query( $args );

                                                    if ($atbdp_query->have_posts()){
                                                        global $post;

                                                        $plans = $atbdp_query->posts;
                                                        printf('<label for="select_plans">%s</label>', __('Select Plan', 'direo-extension'));
                                                        printf('<select id="claimer_plan">');
                                                        printf('<option>%s</option>',__('- Select Plan -', 'direo-extension'));
                                                        foreach ($plans as $key => $value) {
                                                            $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed',$value->ID);
                                                            $plan_type = get_post_meta($value->ID, 'plan_type', true);
                                                            printf('<option %s value="%s">%s %s</option>', (!empty($active_plan) && ('package' === $plan_type))?'class="dcl_active_plan"':'', $value->ID, $value->post_title, !empty($active_plan)&& ('package' === $plan_type)?'<span class="atbd_badge">'.__('- Active', 'direo-extension').'</span>':'');
                                                        }
                                                        printf('</select>');

                                                        ?>
                                                        <div id="dcl-plan-allowances" data-author_id="<?php echo get_current_user_id(); ?>">
                                                            <?php
                                                            do_action('wp_ajax_dcl_plan_allowances', $listing_id); ?>
                                                        </div>
                                                        <?php

                                                        printf('<a target="_blank" href="%s" class="dcl_plans">%s</a>',esc_url(ATBDP_Permalink::get_fee_plan_page_link()), __('Show plan details', 'direo-extension'));
                                                    }
                                                }else{
                                                    global $product;
                                                    $query_args = array(
                                                        'post_type' => 'product',
                                                        'tax_query' => array(
                                                            array(
                                                                'taxonomy' => 'product_type',
                                                                'field'    => 'slug',
                                                                'terms'    => 'listing_pricing_plans',
                                                            ),
                                                        ),
                                                        'meta_query' => array(
                                                            'relation' => 'OR',
                                                            array(
                                                                'key' => '_hide_from_plans',
                                                                'compare' => 'NOT EXISTS',
                                                            ),
                                                            array(
                                                                'key' => '_hide_from_plans',
                                                                'value'   => 1,
                                                                'compare' => '!=',
                                                            ),
                                                        ),
                                                    );

                                                    $atbdp_query = new WP_Query( $query_args );

                                                    if ($atbdp_query->have_posts()){
                                                        global $post;
                                                        $plans = $atbdp_query->posts;
                                                        printf('<label for="select_plans">%s</label>', __('Select Plan', 'direo-extension'));
                                                        printf('<select id="claimer_plan">');
                                                        printf('<option>%s</option>',__('- Select Plan -', 'direo-extension'));
                                                        foreach ($plans as $key => $value) {
                                                            $active_plan = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed',$value->ID);
                                                            $plan_type = get_post_meta($value->ID, 'plan_type', true);
                                                            printf('<option %s value="%s">%s %s</option>',(!empty($active_plan) && ('package' === $plan_type))?'class="dcl_active_plan"':'', $value->ID, $value->post_title, !empty($active_plan) && ('package' === $plan_type)?'<span class="atbd_badge">'.__('- Active', 'direo-extension').'</span>':'');
                                                        }
                                                        printf('</select>');
                                                        ?>
                                                        <div id="dcl-plan-allowances" data-author_id="<?php echo get_current_user_id(); ?>">
                                                            <?php
                                                            do_action('wp_ajax_dcl_plan_allowances', $listing_id); ?>
                                                        </div>
                                                        <?php
                                                        printf('<a target="_blank" href="%s">%s</a>',esc_url(ATBDP_Permalink::get_fee_plan_page_link()), __(' Show plan details', 'direo-extension'));
                                                    }
                                                }

                                            }
                                            ?>

                                        </div>
                                        <div id="dcl-claim-submit-notification"></div>
                                        <div id="dcl-claim-warning-notification"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit"
                                                class="btn btn-primary"><?php esc_html_e('Submit', 'direo-extension'); ?></button>
                                        <span><i class="<?php atbdp_icon_type(true);?>
-lock"></i><?php esc_html_e('Secure Claim Process', 'direo-extension'); ?></span>
                                    </div>
                                </form>
                            </div><!-- ends: .col-lg-125 -->
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }}

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? esc_html($instance['title']) : esc_html__( 'Claim Now', 'direo-extension' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'direo-extension' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class DCL_Claim_Now