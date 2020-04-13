(function ($) {
    $(document).ready(function () {
        // activate license and set up updated
        $('#recaptcha_activated input[name="recaptcha_activated"]').on('change', function (event) {
            event.preventDefault();
            var form_data = new FormData();
            var recaptcha_license = $('#recaptcha_license input[name="recaptcha_license"]').val();
            form_data.append('action', 'atbdp_recaptcha_license_activation');
            form_data.append('recaptcha_license', recaptcha_license);
            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                url: recaptcha_js_obj.ajaxurl,
                data: form_data,
                success: function (response) {
                    if (response.status === true) {
                        $('#success_msg').remove();
                        $('#recaptcha_activated').after('<p id="success_msg">' + response.msg + '</p>');
                        location.reload();
                    } else {
                        $('#error_msg').remove();
                        $('#recaptcha_activated').after('<p id="error_msg">' + response.msg + '</p>');
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        });
        // deactivate license
        $('#recaptcha_deactivated input[name="recaptcha_deactivated"]').on('change', function (event) {
            event.preventDefault();
            var form_data = new FormData();
            var recaptcha_license = $('#recaptcha_license input[name="recaptcha_license"]').val();
            form_data.append('action', 'atbdp_recaptcha_license_deactivation');
            form_data.append('recaptcha_license', recaptcha_license);
            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                url: recaptcha_js_obj.ajaxurl,
                data: form_data,
                success: function (response) {
                    if (response.status === true) {
                        $('#success_msg').remove();
                        $('#recaptcha_deactivated').after('<p id="success_msg">' + response.msg + '</p>');
                        location.reload();
                    } else {
                        $('#error_msg').remove();
                        $('#recaptcha_deactivated').after('<p id="error_msg">' + response.msg + '</p>');
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        });
    })

})(jQuery);

