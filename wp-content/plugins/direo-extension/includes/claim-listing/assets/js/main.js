(function ($) {
    $(document).ready(function () {




        var set_plan = $('#pricing_plans');
        var claimed = $('#claimed_by_admin');
        var claim_charge = $('input[name="claim_charge"]');

        claim_charge.hide();
        if($("#clain_with_fee").is(":checked")){
            claim_charge.show();
        }
        $('input[name="claim_fee"]').on("change", function () {
            if($("#clain_with_fee").is(":checked")){
                claim_charge.show();
            }else{
                claim_charge.hide();
            }
        });

        if(claimed.is(":checked")){
            set_plan.hide();
        }
        claimed.on('click', function () {
            if($(this).is(":checked")){
                set_plan.hide();
            }else{
                set_plan.show();
            }
        });



        /*This function handles all ajax request*/
        function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
            var data;
            if (ActionName) data = "action=" + ActionName;
            if (arg) data = arg + "&action=" + ActionName;
            if (arg && !ActionName) data = arg;
            //data = data ;

            var n = data.search(atbdp_public_data.nonceName);
            if (n < 0) {
                data = data + "&" + atbdp_public_data.nonceName + "=" + atbdp_public_data.nonce;
            }

            jQuery.ajax({
                type: "post",
                url: dcl_main.ajaxurl,
                data: data,
                beforeSend: function () {
                    jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(ElementToShowLoadingIconAfter);
                },
                success: function (data) {
                    jQuery(".atbdp_ajax_loading").remove();
                    CallBackHandler(data);
                }
            });
        }

        //show plan allowance
        $('#dcl-plan-allowances').hide();
        $('#claimer_plan').on('change', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var data = {
                'action': 'dcl_plan_allowances',
                'author_id': $('#dcl-plan-allowances').data('author_id'),
                'plan_id': $(this).val()
            };
            $.post(dcl_main.ajaxurl, data, function (response) {
                if (response){
                    if (response != 0) {
                        $('#dcl-plan-allowances').show();
                        $('#dcl-plan-allowances').html(response.split('<!--end-->')[0]);
                    } else {
                        $('#dcl-plan-allowances').html(' ');
                    }
                }

            });
        });

        dcl_claim_submitter = false;
        var valuechange = '';
        $('body').on('change', '.listing_types', (e) => {
            e.preventDefault();
            valuechange += e.target.value;
        });
        $('#dcl-claim-listing-form').on('submit', function (e) {
            //e.preventDefault();
            // console.log($('.listing_types').val());

            if (dcl_claim_submitter) return false;
            dcl_claim_submitter = true;
            // Check for errors
            if (!e.isDefaultPrevented()) {
                e.preventDefault();

                // Post via AJAX

                var data = {
                    'action': 'dcl_submit_claim',
                    'post_id': $('#dcl-post-id').val(),
                    'claimer_name': $('#dcl_claimer_name').val(),
                    'claimer_phone': $('#dcl_claimer_phone').val(),
                    'claimer_details': $('#dcl_claimer_details').val(),
                    'claimer_plan': $('#claimer_plan').val(),
                    'listing_type': valuechange,
                };

                $.post(dcl_main.ajaxurl, data, function (response) {
                    if (response.take_payment === 'plan') {
                        window.location.href = response.checkout_url;
                    } else {
                        $('#dcl_claimer_name').val('');
                        $('#dcl_claimer_phone').val('');
                        $('#dcl_claimer_details').val('');
                        $('#dcl-claim-submit-notification').addClass('text-success').html(response.message);

                    }
                    if (response.duplicate_msg !== ''){
                        $('#dcl-claim-warning-notification').addClass('text-warning').html(response.duplicate_msg);
                    }
                    dcl_claim_submitter = false; // Re-enable the submit event
                }, 'json');
            }
        });

        //calim listng settings panel - set claim fee
        var claim_price = $("#claim_listing_price");
        claim_price.hide();

        $('select[name="claim_charge_by"]').on("change", function () {
            if($(this).val() == "static_fee"){
                claim_price.show();
            }else{
                claim_price.hide();
            }
        });
        if($('select[name="claim_charge_by"] option:selected').val() == "static_fee"){

            claim_price.show();
        }

    });

    var dcln = $('.dcl_login_notice');
    dcln.hide();
    $('.dcl_login_alert ').on('click', function (e) {
        e.preventDefault();
        dcln.slideDown();
    })


})(jQuery);

