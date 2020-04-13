(function ($) {
    $("#direo_monetize_by select[name='direo_monetize_by']").on("change", function () {
        if($(this).val() === "woo_pricing_plan"){
            $("#direo_monetize_by").after("<p class='monetize_ext_notice'>It requires WooCommerce Plugin to be installed and activated</p>");
        }else if($(this).val() !== "woo_pricing_plan"){
            $("#direo_monetize_by + .monetize_ext_notice").remove();
        }
    })
    if($("#direo_monetize_by select[name='direo_monetize_by']").val() === "woo_pricing_plan"){
        $("#direo_monetize_by").after("<p class='monetize_ext_notice'>It requires WooCommerce Plugin to be installed and activated</p>");
    }
})(jQuery);