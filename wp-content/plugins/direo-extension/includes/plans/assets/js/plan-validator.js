jQuery(document).ready(function ($) {
    function to_top(top) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(top).offset().top
        }, 1000);
    }
    $('.listing_submit_btn').on('click', function () {
        $('.atpp_required').css({ display: "none" });
        var error_messege = '<span class="atpp_required atbdp_make_str_red"><i class="fa fa-exclamation-triangle"></i> '+plan_validator.crossLimit+'</span>';

        //Price
        var pynPrice = $("input[name='price']");
        var price_limit = plan_validator.price_limit;
        price_limit = parseFloat(price_limit);
        if (pynPrice.length>0){
            var price = pynPrice.val();
            price = parseFloat(price);
            if (price > price_limit) {
                pynPrice.after(error_messege);
                to_top(pynPrice);
                return false;
            }
        }

        //tag
        var tag = $("#at_biz_dir-tags").val();
        var tag_number = $(tag).length;
        var tag_limit = plan_validator.tag_limit;
        if (tag_number > tag_limit) {
            $('.atbd_tagvalidate_note').css({ display: "none" });
            $("#atbdp_tags").after(error_messege);
            to_top('#atbdp_tags');
            return false;
        }

    });

    //staff to change the plan



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
            url: plan_validator.ajaxurl,
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


    $('.atpp_change_plan').on('click',function () {
        var listingID = $(this).attr('data-listing_id');
        $('#change_listing_id').val(listingID);
    });

  atpp_plan_submitter = false;
    $('#atpp-change-plan-form').on('submit', function (e) {
        if (atpp_plan_submitter) return false;
        atpp_plan_submitter = true;
        // Check for errors
        if (!e.isDefaultPrevented()) {
            e.preventDefault();

            // Post via AJAX
            var data = {
                'action': 'atpp_submit_changing_plan',
                'post_id': $('#change_listing_id').val(),
                'plan_id' : $("input[name='new_plan']:checked").val(),
            };

            $.post(plan_validator.ajaxurl, data, function (response) {
                if (response.take_payment === 'plan') {
                    window.location.href = response.checkout_url;
                } else {
                    $('#dcl-claim-submit-notification').addClass('text-success').html(response.message);

                }
                if (response.duplicate_msg !== ''){
                    $('#dcl-claim-warning-notification').addClass('text-warning').html(response.duplicate_msg);
                }
                atpp_plan_submitter = false; // Re-enable the submit event
            }, 'json');
        }
    });

    $('.atbdp_renew_with_plan').on('click',function () {
        var listingID = $(this).attr('data-listing_id');
        $('#change_listing_id').val(listingID);
    });

    //renewing listing
    atpp_renew_plan_submitter = false;
    $('#atpp-renew-plan-form').on('submit', function (e) {
        if (atpp_renew_plan_submitter) return false;
        atpp_renew_plan_submitter = true;
        // Check for errors
        if (!e.isDefaultPrevented()) {
            e.preventDefault();
            // Post via AJAX
            var data = {
                'action': 'atpp_submit_changing_plan',
                'post_id': $('#change_listing_id').val(),
                'plan_id' : $("input[name='new_plan']:checked").val(),
            };
            console.log(data);
            $.post(plan_validator.ajaxurl, data, function (response) {
                console.log(response);
                if (response.take_payment === 'plan') {
                    //window.location.href = response.checkout_url;
                } else {
                    $('#dcl-plan-renew-notification').addClass('text-success').html(response.renew_info);
                }
                if (response.duplicate_msg !== ''){
                    $('#dcl-claim-warning-notification').addClass('text-warning').html(response.duplicate_msg);
                }
                atpp_renew_plan_submitter = false; // Re-enable the submit event
            }, 'json');
        }
    });

});