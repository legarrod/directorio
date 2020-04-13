(function ($) {
    $(document).ready(function () {
        $('#is_listing_featured').hide();
        var package = $('#package');
        var pay_per_listing = $('#pay_per_listng');

        var selectedVal = "";
        var selected = $("input[type='radio'][name='plan_type']:checked");
        if (selected.length > 0) {
            selectedVal = selected.val();
        }
        if (selectedVal === 'pay_per_listng'){
            $('#regular_listing').fadeOut();
            $('#featured_listing').fadeOut();
            $('#is_listing_featured').fadeIn(300);
        }if(selectedVal === 'package'){
            $('#regular_listing').show();
            $('#featured_listing').show();
            $('#is_listing_featured').hide();
        }
        $(pay_per_listing).on( 'click', function() {
            $('#regular_listing').fadeOut(300);
            $('#featured_listing').fadeOut(300);
            $('#is_listing_featured').fadeIn(300);
        });
        $(package).on( 'click', function() {
            alert('Please Make sure to add number of Regular and Featured listings in this package');
            $('#regular_listing').fadeIn(300);
            $('#featured_listing').fadeIn(300);
            $('#is_listing_featured').fadeOut(300);
        });

    });

})(jQuery);

