(function ($) {

    var apiKey = atbdp_commonObj.publish_key;
    var submitBtn = $( '#atbdp_checkout_submit_btn' );
    setTimeout(function () {
        var selected_gateway = $( "input[name='payment_gateway']:checked").val();

        // Display stripe cc form if default gateway = stripe

        if( selected_gateway === 'stripe_gateway' ){
            if (!apiKey){
                submitBtn.attr("disabled", true);
            }
            submitBtn.val( "Process Now" );
        }else {
            submitBtn.attr("disabled", false);
            submitBtn.val( "Pay Now" );
        }
    }, 2000);

    // Listen to payment gateway selection, display stripe cc form if applicable.

    $( "input[name='payment_gateway']" ).on( 'change', function() {
        if( 'stripe_gateway' === this.value ) {
            if (!apiKey){
                submitBtn.attr("disabled", true);
            }
            submitBtn.val( "Process Now" );
        } else {
            submitBtn.attr("disabled", false);
            submitBtn.val( "Pay Now" );
        }
    });

})(jQuery);