<?php
!empty($args['fields_info']) ? extract($args['fields_info']) : array(); // extract fields
/**
 * Display the WooCommerce custom fields.
 */
/*echo '<pre>';
var_dump($args['fields_info']);
echo '</pre>';*/
//var_dump($plan_metas['_dwpp_plan_type'][0])

do_action('atbdp_field_before_plan_type_select', $args);
?>

<div class="options_group listing show_if_listing_pricing_plans" id="dwpp_woo_metaboxes">
    <?php
    // Plan type
    woocommerce_wp_radio(
        array(

            'id'            => '_dwpp_plan_type',
            'label'             => __( 'Plan Type', 'direo-extension' ),
            'options'       => array(
                'package'       => __( 'Package', 'direo-extension' ),
                'pay_per_listng'  => __( 'Pay Per Listing', 'direo-extension' ),
        ),
            'value'  => !empty($plan_metas['plan_type'][0])?esc_attr($plan_metas['plan_type'][0]):'pay_per_listng'
    ));
    echo '<hr>';
    // Listing Duration
    woocommerce_wp_text_input(
        array(
            'id'                => '_dwpp_listing_duration',
            'label'             => __( 'Listing Duration', 'direo-extension' ),
            'placeholder'       => '',
            'desc_tip'      	=> true,
            'description'       => __( 'Listing Duration (in days)', 'direo-extension' ),
            'type'              => 'number',
            'custom_attributes' => array(
                'step' 	=> 'any',
                'min'	=> '0'
            ),
            'value'             => !empty($plan_metas['fm_length'][0])?esc_attr($plan_metas['fm_length'][0]):''
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_never_expire',
            'label'         => __( 'Never Expire', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_length_unl'][0])?'yes':'no'
        )
    );

    echo '<hr>';
    // Listings Limit
    woocommerce_wp_text_input(
        array(
            'id'                => '_dwpp_listings_limit',
            'label'             => __( 'Number of Listings', 'direo-extension' ),
            'placeholder'       => '',
            'desc_tip'      	=> true,
            'description'       => __( 'Enter the number of listings this plan allows.', 'direo-extension' ),
            'type'              => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min'  => '0'
            ),
            'value'             => !empty($plan_metas['num_regular'][0])?esc_attr($plan_metas['num_regular'][0]):''
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_unl_listings',
            'label'         => __( 'Unlimited Listings', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['num_regular_unl'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_listings',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_listings'][0])?'yes':'no'
        )
    );
    echo '<hr class="_dwpp_hide_listings_field">';
    // Featured Listings Limit
    woocommerce_wp_text_input(
        array(
            'id'                => '_dwpp_featured_limit',
            'label'             => __( 'Number of Featured Listings', 'direo-extension' ),
            'placeholder'       => '',
            'desc_tip'      	=> true,
            'description'       => __( 'Enter the number of featured listings this plan allows.', 'direo-extension' ),
            'type'              => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min'  => '0'
            ),
            'value'             => !empty($plan_metas['num_featured'][0])?esc_attr($plan_metas['num_featured'][0]):''
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_unl_featured',
            'label'         => __( 'Unlimited Featured Listings', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['num_featured_unl'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_featured',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_featured'][0])?'yes':'no'
        )
    );
    echo '<hr class="_dwpp_hide_listings_field" id="featured_limit_hr">';

    // Featured
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_featured',
            'wrapper_class' => 'dwpp-featured',
            'label'         => __( 'Featured the Listing', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['is_featured_listing'][0])?'yes':'no'
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_listing_featured',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_listing_featured'][0])?'yes':'no'
        )
    );
    echo '<hr class="_dwpp_featured">';


    // Images Limit
     woocommerce_wp_checkbox(
    array(
        'id'            => 'fm_allow_slider',
        'label'         => __( 'Allow Images', 'direo-extension' ),
        'value'       	=> !empty($plan_metas['fm_allow_slider'][0])?'yes':'no'
    )
    );
    woocommerce_wp_text_input(
        array(
            'id'                => '_dwpp_images_limit',
            'label'             => apply_filters('atbdp_new_plan_slider_image_limit_label', __( 'Set Slider Image Limit', 'direo-extension' )),
            'placeholder'       => '',
            'desc_tip'      	=> true,
            'description'       => __( 'Enter the number of images the users can upload per listing.', 'direo-extension' ),
            'type'              => 'number',
            'custom_attributes' => array(
                'step' 	=> 'any',
                'min'	=> '0'
            ),
            'value'             => !empty($plan_metas['num_image'][0])?esc_attr($plan_metas['num_image'][0]):''
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_unl_image',
            'label'         => __( 'Unlimited Images', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['num_image_unl'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_image',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_image'][0])?'yes':'no'
        )
    );
    echo '<hr id="slider_hr">';


    // Tag Limit
   woocommerce_wp_checkbox(
    array(
        'id'            => 'fm_allow_tag',
        'label'         => __( 'Allow Tags', 'direo-extension' ),
        'value'       	=> !empty($plan_metas['fm_allow_tag'][0])?'yes':'no'
    )
    );
    woocommerce_wp_text_input(
        array(
            'id'                => '_dwpp_tag_limit',
            'label'             => __( 'Set Limit', 'direo-extension' ),
            'placeholder'       => '',
            'desc_tip'      	=> true,
            'description'       => __( 'Enter the number of tag the users can upload per listing.', 'direo-extension' ),
            'type'              => 'number',
            'custom_attributes' => array(
                'step' 	=> 'any',
                'min'	=> '0'
            ),
            'value'             => !empty($plan_metas['fm_tag_limit'][0])?esc_attr($plan_metas['fm_tag_limit'][0]):''
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_unl_tag',
            'label'         => __( 'Unlimited Tags', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_tag_limit_unl'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_tag',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_tag'][0])?'yes':'no'
        )
    );
    echo '<hr>';


    // Price Limit
    woocommerce_wp_checkbox(
    array(
        'id'            => 'fm_allow_price',
        'label'         => __( 'Allow Price', 'direo-extension' ),
        'value'       	=> !empty($plan_metas['fm_allow_price'][0])?'yes':'no'
    )
    );
    woocommerce_wp_text_input(
        array(
            'id'                => '_dwpp_price_limit',
            'label'             => __( 'Set Limit', 'direo-extension' ),
            'placeholder'       => '',
            'desc_tip'      	=> true,
            'description'       => __( 'Enter the number of price the users can upload per listing.', 'direo-extension' ),
            'type'              => 'number',
            'custom_attributes' => array(
                'step' 	=> 'any',
                'min'	=> '0'
            ),
            'value'             => !empty($plan_metas['price_range'][0])?esc_attr($plan_metas['price_range'][0]):''
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_unl_price',
            'label'         => __( 'Unlimited Price', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['price_range_unl'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_price',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_price'][0])?'yes':'no'
        )
    );
    echo '<hr>';

    // Price Range
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_price_range',
            'label'         => __( 'Price Range', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_allow_price_range'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_price_range',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_price_range'][0])?'yes':'no'
        )
    );
    echo '<hr id="price_range_hr">';

    // Business Hours
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_business_hours',
            'label'         => __( 'Business Hours (It requires <a target="_blank" href="https://aazztech.com/product/directorist-business-hours/">Business Hours</a> extension)', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['business_hrs'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_business_hours',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_business_hours'][0])?'yes':'no'
        )
    );
    echo '<hr id="business_hours_hr">';

    $show_gallery = apply_filters('atbdp_new_plan_gallery_image_fields', true);
    if ($show_gallery){
        //  Gallery Image limit
        woocommerce_wp_checkbox(
            array(
                'id'            => 'atfm_listing_gallery',
                'label'         => __( 'Allow Gallery', 'direo-extension' ),
                'value'       	=> !empty($plan_metas['atfm_listing_gallery'][0])?'yes':'no'
            )
        );
        woocommerce_wp_text_input(
            array(
                'id'                => 'num_gallery_image add_plan_image_gallery',
                'label'         => __( 'Gallery Image Limit (It requires <a target="_blank" href="https://aazztech.com/product/directorist-image-gallery/">Image Gallery</a> extension)', 'direo-extension' ),
                'placeholder'       => '',
                'desc_tip'      	=> true,
                'description'       => __( 'Enter the number of images the users can upload per listing.', 'direo-extension' ),
                'type'              => 'number',
                'custom_attributes' => array(
                    'step' 	=> 'any',
                    'min'	=> '0'
                ),
                'value'             => !empty($plan_metas['num_gallery_image'][0])?esc_attr($plan_metas['num_gallery_image'][0]):''
            )
        );
        woocommerce_wp_checkbox(
            array(
                'id'            => 'unl_gallery_image',
                'label'         => __( 'Unlimited Gallery Image', 'direo-extension' ),
                'value'       	=> !empty($plan_metas['unl_gallery_image'][0])?'yes':'no'
            )
        );
        woocommerce_wp_checkbox(
            array(
                'id'            => '_dwpp_hide_image_gallery',
                'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
                'value'       	=> !empty($plan_metas['_dwpp_hide_image_gallery'][0])?'yes':'no'
            )
        );

        echo '<hr id="image_gallery_hr">';
    }

    // video
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_video',
            'label'         => __( 'Video', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['l_video'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_video',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_video'][0])?'yes':'no'
        )
    );
    echo '<hr id="video_hr">';


    // Contact listing owner
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_cl_owner',
            'label'         => __( 'Contact Listing Owner', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['cf_owner'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_cl_owner',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_cl_owner'][0])?'yes':'no'
        )
    );
    echo '<hr id="contact_owner_hr">';


    // email
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_email',
            'label'         => __( 'Email', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_email'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_email',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_email'][0])?'yes':'no'
        )
    );
    echo '<hr id="email_hr">';

    // phone
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_phone',
            'label'         => __( 'Phone', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_phone'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_phone',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_phone'][0])?'yes':'no'
        )
    );
    echo '<hr id="phone_hr">';

    // web link
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_web_link',
            'label'         => __( 'Website Link', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_web_link'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_web_link',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_web_link'][0])?'yes':'no'
        )
    );
    echo '<hr id="web_link_hr">';


    // Social Media Links
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_sm_link',
            'label'         => __( 'Social Media Links', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_social_network'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_sm_link',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_sm_link'][0])?'yes':'no'
        )
    );
    echo '<hr id="sm_link_hr">';

    // Customer Reviews
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_customer_review',
            'label'         => __( 'Customer Reviews', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_cs_review'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_customer_review',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_customer_review'][0])?'yes':'no'
        )
    );
    echo '<hr id="customer_review_hr">';

    // Listing's FAQs
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_faqs',
            'label'         => __( 'FAQs (It requires <a target="_blank" href="https://aazztech.com/product/directorist-listing-faqs/">Listing\'s FAQs</a> extension)', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_listing_faq'][0])?'yes':'no'
        )
    );
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_faqs',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_faqs'][0])?'yes':'no'
        )
    );
    echo '<hr>';


    // Custom Fields
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_custom_fields',
            'label'         => __( 'Custom Fields', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['fm_custom_field'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_custom_fields',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_custom_fields'][0])?'yes':'no'
        )
    );
    echo '<hr id="custom_field_hr">';

    // Custom Fields
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_claim',
            'label'         => __( 'Claim Badge Included (It requires <a target="_blank" href="https://aazztech.com/product/directorist-claim-listing/">Claim Listing</a> extension)', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_fm_claim'][0])?'yes':'no'
        )
    );

    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_claim',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_claim'][0])?'yes':'no'
        )
    );
    echo '<hr id="claim_hr">';

    // Exclude Categories
/*    dwpp_woocommerce_multiselect(
        array(
            'id'      => '_dwpp_category',
            'name'    => '_dwpp_category[]',
            'label'   => __( 'Exclude Categories', 'direo-extension' ),
            'options' => $dwpp_categories,
            'value'   => !empty($plan_metas['_dwpp_category'][0])?esc_attr($plan_metas['_dwpp_category'][0]):''
        )
    );*/
   // var_dump($plan_metas['_dwpp_category'][0])

    printf('<span class="dwpp_ex_label">%s</span><div class="dwpp_ex_categories">', __( 'Exclude Categories', 'direo-extension' ));
    foreach ($dwpp_categories as $key => $cat_title){
        $checked = in_array($cat_title->term_id, $current_val) ? 'checked' : '';

        printf( '<input name="exclude_cat[]" id="%s" type="checkbox" value="%s" %s><span>%s</span><br>',$cat_title->term_id,$cat_title->term_id, $checked, $cat_title->name);
    }
    printf ('</div>');
    woocommerce_wp_checkbox(
        array(
            'id'            => '_dwpp_hide_category',
            'label'         => __( ' Hide this from pricing plans', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_dwpp_hide_category'][0])?'yes':'no'
        )
    );
    echo '<hr>';
    woocommerce_wp_checkbox(
        array(
            'id'            => 'default_pln',
            'label'         => __( ' Recommend this Plan ', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['default_pln'][0])?'yes':'no'
        )
    );
    echo '<hr>';
    woocommerce_wp_checkbox(
        array(
            'id'            => 'hide_from_plans',
            'label'         => __( ' Hide form All Plans ', 'direo-extension' ),
            'value'       	=> !empty($plan_metas['_hide_from_plans'][0])?'yes':'no'
        )
    );
    ?>

    <div class="atbdp_shortcode">
        <h2><?php esc_html_e('Shortcode', 'direo-extension'); ?> </h2>
        <p><?php esc_html_e('Use following shortcode to display the Plan anywhere:', 'direo-extension'); ?></p>
        <textarea cols="50" rows="1" onClick="this.select();" >[directorist_pricing_plans id=<?php echo $post->ID;?>]</textarea><br>

        <p><?php esc_html_e('If you need to put the shortcode inside php code/template file, use this:', 'direo-extension'); ?></p>
        <textarea cols="63" rows="1" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[directorist_pricing_plans id='; echo $post->ID."]"; echo '"); ?>'; ?></textarea>
    </div>
</div>