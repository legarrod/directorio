jQuery( document ).ready( function() {
    jQuery( '#dwpp_woo_metaboxes' ).hide();
    jQuery( '.options_group.pricing' ).addClass( 'show_if_listing_pricing_plans' ).show();
    jQuery('._dwpp_listings_limit_field ').hide();
    jQuery('._dwpp_unl_listings_field').hide();
    jQuery('._dwpp_hide_listings_field').hide();
    jQuery('._dwpp_featured_limit_field').hide();
    jQuery('._dwpp_unl_featured_field').hide();
    jQuery('._dwpp_hide_featured_field').hide();

    jQuery('#product-type').on('change', function()
    {
        if( jQuery(this).val() == 'listing_pricing_plans')
        {
            jQuery('.inventory_options').addClass('show_if_listing_pricing_plans').show();
            jQuery('#inventory_product_data ._sold_individually_field').parent().addClass('show_if_listing_pricing_plans').show();
            jQuery('#inventory_product_data ._sold_individually_field').addClass('show_if_listing_pricing_plans').show();
            jQuery('#dwpp_woo_metaboxes').show();
        }
        else
        {
            jQuery( '#dwpp_woo_metaboxes' ).hide();
        }
    });
    jQuery('#product-type').trigger( 'change' );

    var selectedVal = jQuery('[name="_dwpp_plan_type"]:checked').val();
    //get the plan type
    jQuery('._dwpp_listings_limit_field').hide();
    jQuery('._dwpp_featured_limit_field').hide();
    jQuery('[name="_dwpp_plan_type"]').on('click', function () {

        //hide unnecessary fields if click on PPL
        if (jQuery(this).val() === 'package'){
            alert('Please Make sure to add number of Regular and Featured listings in this package');
            jQuery('._dwpp_listings_limit_field ').fadeIn(300);
            jQuery('._dwpp_unl_listings_field').fadeIn(300);
            jQuery('._dwpp_hide_listings_field').fadeIn(300);
            jQuery('._dwpp_featured_limit_field').fadeIn(300);
            jQuery('._dwpp_unl_featured_field').fadeIn(300);
            jQuery('._dwpp_hide_featured_field').fadeIn(300);
            jQuery('._dwpp_featured_field').fadeOut(300);
            jQuery('._dwpp_featured').hide();
            jQuery('._dwpp_hide_listing_featured_field').hide();


        }else {
            jQuery('._dwpp_listings_limit_field ').fadeOut(300);
            jQuery('._dwpp_unl_listings_field').fadeOut(300);
            jQuery('._dwpp_hide_listings_field').fadeOut(300);
            jQuery('._dwpp_featured_limit_field').fadeOut(300);
            jQuery('._dwpp_unl_featured_field').fadeOut(300);
            jQuery('._dwpp_hide_featured_field').fadeOut(300);
            jQuery('._dwpp_featured_field').fadeIn(300);
            jQuery('._dwpp_featured').fadeIn(300);
            jQuery('._dwpp_hide_listing_featured_field').fadeIn(300);
        }
    });

    if (selectedVal === 'pay_per_listing'){
        jQuery('._dwpp_listings_limit_field ').hide();
        jQuery('._dwpp_unl_listings_field').hide();
        jQuery('._dwpp_hide_listings_field').hide();
        jQuery('._dwpp_featured_limit_field').hide();
        jQuery('._dwpp_unl_featured_field').hide();
        jQuery('._dwpp_hide_featured_field').hide();
        jQuery('._dwpp_featured_field').fadeIn(300);
    }
    if (selectedVal === 'package'){
        jQuery('._dwpp_listings_limit_field ').show();
        jQuery('._dwpp_unl_listings_field').show();
        jQuery('._dwpp_hide_listings_field').show();
        jQuery('._dwpp_featured_limit_field').show();
        jQuery('._dwpp_unl_featured_field').show();
        jQuery('._dwpp_hide_featured_field').show();
        jQuery('._dwpp_featured_field').hide();
        jQuery('._dwpp_featured').hide();
        jQuery('._dwpp_hide_listing_featured_field').hide();
    }

});
