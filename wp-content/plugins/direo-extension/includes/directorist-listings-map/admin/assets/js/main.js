(function ($) {
    $(document).ready(function () {
        var lmvf = $("#listing_map_visible_fields");
        lmvf.hide();
        $('#bdmv_listings_with_map_columns select[name="bdmv_listings_with_map_columns"]').on("change", function () {
            if($(this).val() === "2"){
                lmvf.show();
            }else{
                lmvf.hide();
            }
        });
        if($('#bdmv_listings_with_map_columns select[name="bdmv_listings_with_map_columns"]').val() === "2"){
            lmvf.show();
        }
    });
})(jQuery);

