jQuery(document).ready(function ($) {

    // Show the login dialog box on click
    $('a#show_login').on('click', function (e) {
        $('body').prepend('<div class="login_overlay"></div>');
        $('form#direo-login').fadeIn(500);
        $('div.login_overlay, form#direo-login a.close').on('click', function () {
            $('div.login_overlay').remove();
            $('form#direo-login').hide();
        });
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('form#direo-login').on('submit', function (e) {
        e.preventDefault();
        $('form#direo-login p.status').show().html(direo_ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: direo_ajax_login_object.ajaxurl,
            data: {
                'action': 'direo_ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#direo-login #direo-username').val(),
                'password': $('form#direo-login #direo-password').val(),
                'security': $('form#direo-login #direo-security').val()
            },
            success: function (data) {
                $('form#direo-login p.status').html("<span class='color-success'>" + data.message + "</span>");
                if (data.loggedin == true) {
                    document.location.href = direo_ajax_login_object.redirecturl;
                }
            },
            error: function (data) {
                $('form#direo-login p.status').show().html('<span class="color-danger">' + direo_ajax_login_object.login_failed + '</span>');

            }
        });
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('form#direo_recovery_password').on('submit', function (e) {
        e.preventDefault();
        $('form#direo_recovery_password p.recovery_status').show().text(direo_ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: direo_ajax_login_object.ajaxurl,
            data: {
                'action': 'direo_recovery_password', //calls wp_ajax_nopriv_ajaxlogin
                'user_login': $('.direo_recovery_user').val()
            },
            success: function (data) {
                $('form#direo_recovery_password p.recovery_status').text(data.message);
                if (data.loggedin == true) {
                    $('form#direo_recovery_password p.recovery_status').show().text(data.message);
                }
            },
            error: function (data) {
                $('form#direo_recovery_password p.recovery_status').show().text(data.message);

            }
        });
        e.preventDefault();
    });


    /**
     * When user clicks on button...
     *
     */
    $('.indicator').hide();
    $('#btn-new-user').click(function (event) {

        /**
         * Prevent default action, so when user clicks button he doesn't navigate away from page
         *
         */
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }

        // Show 'Please wait' loader to user, so she/he knows something is going on
        $('.indicator').show();

        // If for some reason result field is visible hide it
        $('.result-message').hide();

        // Collect data from inputs
        var reg_nonce = $('#vb_new_user_nonce').val();
        var reg_user = $('#vb_username').val();
        var reg_pass = $('#vb_password').val();
        var reg_mail = $('#vb_email').val();

        /**
         * AJAX URL where to send data
         * (from localize_script)
         */
        var ajax_url = direo_ajax_login_object.ajaxurl;

        // Data to send
        data = {
            action: 'register_user',
            nonce: reg_nonce,
            user: reg_user,
            pass: reg_pass ? reg_pass : '',
            mail: reg_mail,
        };

        // Do AJAX request
        $.post(ajax_url, data, function (response) {
            // If we have response
            if (response) {

                // Hide 'Please wait' indicator
                $('.indicator').hide();

                if (response === '1') {
                    // If user is created
                    $('.result-message').removeClass('alert-danger'); // Add class success to results div
                    $('.result-message').addClass('alert-success'); // Add class success to results div
                    $('.result-message').html("<span>" + direo_ajax_login_object.registration_confirmation + "</span>"); // Add success message to results div
                    $('.result-message').show(); // Show results div
                } else {
                    $('.result-message').html("<span>" + response + "</span>"); // If there was an error, display it in results div
                    $('.result-message').addClass('alert-danger'); // Add class failed to results div
                    $('.result-message').show(); // Show results div
                }
            }
        });

    });

});