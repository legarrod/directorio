(function ($) {

    setTimeout(function () {
        var selected_gateway = $( "input[name='payment_gateway']:checked").val();

        // Display stripe cc form if default gateway = stripe

        if( selected_gateway === 'stripe_gateway' ){
            $( '#atbdp_checkout_submit_btn' ).val( "Process Now" );
        }else {
            $( '#atbdp_checkout_submit_btn' ).val( "Pay Now" );
        }
    }, 2000);

    // Listen to payment gateway selection, display stripe cc form if applicable.

    $( "input[name='payment_gateway']" ).on( 'change', function() {
        if( 'stripe_gateway' === this.value ) {
            $( '#atbdp_checkout_submit_btn' ).val( "Process Now" );
        } else {
            $( '#atbdp_checkout_submit_btn' ).val( "Pay Now" );
        }
    });

    var atds_load_btn = $("button.atds_stripe_btn");
    atds_load_btn.on("click", function () {
       $(this).addClass("atds_stirpe_load");
       setTimeout(function () {
           atds_load_btn.removeClass("atds_stirpe_load");
       }, 5000)
    });

    // Create a Stripe client.
    var stripe = Stripe(atbdp_paMentObj.publish_key);

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    var cardElement = elements.create('card', {style: style});
    cardElement.mount('#card-element');


    var cardButton = document.getElementById('card-button');
    var clientSecret = cardButton.dataset.secret;
    var planActive = atbdp_paMentObj.recurring;

    cardButton.addEventListener('click', function (ev) {
        ev.preventDefault();
        if (planActive.length === 1){
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    // Inform the customer that there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    var data = {
                        'action': 'atbdp_stripe_payment_process',
                        'token_id': result.token.id,
                        'email': $('#email').val(),
                        'order_id': atbdp_paMentObj.order_id,
                    };
                    $.post(atbdp_paMentObj.ajax_url, data, function (response) {
                        if (response){
                            window.location.href = atbdp_paMentObj.redirect_url;
                        }else {
                            window.location.href = atbdp_paMentObj.payment_fail;
                        }
                    });
                }
            });
        }else {
            stripe.handleCardPayment(
                clientSecret, cardElement, {
                    payment_method_data: {
                        // latter to push any custom metadata
                    }
                }
            ).then(function (result) {
                if (result.error) {
                    window.location.href = atbdp_paMentObj.redirect_url;
                } else {
                    var data = {
                        'action': 'atbdp_stripe_payment_success',
                        'order_id': result.paymentIntent.description,
                        'tns_id': result.paymentIntent.id,
                    };
                    $.post(atbdp_paMentObj.ajax_url, data, function (response) {
                        if (response){
                            window.location.href = atbdp_paMentObj.redirect_url;
                        }
                    });
                    atds_load_btn.addClass("atds_stirpe_load");
                }
            });
        }
    });
})(jQuery);