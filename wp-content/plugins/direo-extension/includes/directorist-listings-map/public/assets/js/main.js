(function ($) {
    jQuery(document).ready(function ($) {

        /*$.each($("input[name='view_as']:checked"), function(){
            view_as = $(this).val();
        });*/

        $(".ajax-search-result").hide();
        var nonce_get = $('#bdlm-search-area').attr('data-nonce');
        var view_columns = $('#directorist').attr('data-view-columns');
        var text_field = $('.search-text').attr('data-text');
        var category_field = $('.search-category').attr('data-cat');
        var location_field = $('.search-location').attr('data-loc');
        var address_field = $('.search-address').attr('data-address');
        var price_field = $('.range_single').attr('data-price');
        var price_range_field = $('.price-frequency').attr('data-price-range');
        var rating_field = $('.search-rating').attr('data-rating');
        var radius_field = $('.search-radius').attr('data-radius');
        var open_field = $('.search-open').attr('data-open');
        var tag_field = $('.ads-filter-tags').attr('data-tag');
        var custom_search_field = $('.atbdp-custom-fields-search').attr('data-custom-search-field');
        var website_field = $('.search-website').attr('data-website');
        var email_field = $('.search-email').attr('data-email');
        var phone_field = $('.search-phone').attr('data-phone');
        var fax_field = $('.search-fax').attr('data-fax');
        var zip_field = $('.search-zip').attr('data-zip');
        var reset_filters = $('.reset-filters').attr('data-reset');
        var apply_filter = $('.ajax-search').attr('data-apply');
        //ajax search
        $(".ajax-search").on("click", function () {
            var display_header = $('#display_header').val();
            var header_title = $('#header_title').val();
            var show_pagination = $('#show_pagination').val();
            var listings_per_page = $('#listings_per_page').val();
            var location_slug = $('#location_slug').val();
            var category_slug = $('#category_slug').val();
            var key = $('#search_q').val();
            var location = $('.bdas-location-search').val();
            var category = $('.bdas-category-search').val();
            var open_now = [];
            var price = [];
            var custom_field = {};
            var website = $('#website').val();
            var phone = $('#phone').val();
            var address = $('#address').val();
            var zip_code = $('#zip_code').val();
            var email = $('#email').val();
            var miles = $('#atbd_rs_value').val();
            var cityLat = $('#cityLat').val();
            var cityLng = $('#cityLng').val();
            var tag = "";
            var search_by_rating = "";
            var view_as = "";
            if($(".map-view-grid").hasClass("active")){
                view_as = "grid";
            } else if($(".map-view-list").hasClass("active")) {
                view_as = "list";
            }
            var sort_by = "";
            if($(".sort-title-asc").hasClass("active")){
                sort_by = "title-asc";
            } else if($(".sort-title-desc").hasClass("active")) {
                sort_by = "title-desc";
            } else if($(".sort-date-desc").hasClass("active")) {
                sort_by = "date-desc";
            } else if($(".sort-date-asc").hasClass("active")) {
                sort_by = "date-asc";
            } else if($(".sort-price-asc").hasClass("active")) {
                sort_by = "price-asc";
            } else if($(".sort-price-desc").hasClass("active")) {
                sort_by = "price-desc";
            } else if($(".sort-rand").hasClass("active")) {
                sort_by = "rand";
            }
            $(".ajax-search-result").addClass('loading');
            $(".bdmv-map-listing").addClass("loading");
            $('input[name^="price"]').each(function (index, el) {
                price.push($(el).val())
            });
            $('[name^="custom_field"]').each(function (index, el) {
                var test = $(el).attr('name');
                var type = $(el).attr('type');
                var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');
                if('radio' === type) {
                    $.each($("input[name='custom_field["+post_id+"]']:checked"), function () {
                        value = $(this).val();
                        custom_field[post_id] = value;
                    });
                } else if ('checkbox' === type) {
                    post_id = post_id.split('[]')[0];
                    $.each($("input[name='custom_field["+post_id+"][]']:checked"), function () {
                        var checkValue = [];
                        value = $(this).val();
                        checkValue.push(value);
                        custom_field[post_id] = checkValue;
                    });
                } else {
                    var value = $(el).val();
                    custom_field[post_id] = value;
                }
            });
            $.each($("input[name='open_now']:checked"), function () {
                open_now.push($(this).val());
            });
            $.each($("input[name='in_tag']:checked"), function () {
                tag = $(this).val();
            });
            $.each($("input[name='search_by_rating']:checked"), function () {
                search_by_rating = $(this).val();
            });
            $(".bdmv-columns-two .bdmv-search .bdmv-listing").fadeOut(1000);
            $.ajax({
                url: bdrr_submit.ajax_url,
                type: "POST",
                data: {
                    action: "ajax_search_listing",
                    display_header: display_header,
                    header_title: header_title,
                    show_pagination: show_pagination,
                    listings_per_page: listings_per_page,
                    location_slug: location_slug,
                    category_slug: category_slug,
                    key: key,
                    location: location,
                    category: category,
                    price: price,
                    open_now: open_now,
                    website: website,
                    phone: phone,
                    address: address,
                    zip_code: zip_code,
                    email: email,
                    miles: miles,
                    cityLat: cityLat,
                    cityLng: cityLng,
                    tag: tag,
                    search_by_rating: search_by_rating,
                    view_as: view_as,
                    sort_by: sort_by,
                    custom_field: custom_field,
                    nonce_get: nonce_get,
                    view_columns: view_columns,
                    text_field: text_field,
                    category_field: category_field,
                    location_field: location_field,
                    address_field: address_field,
                    price_field: price_field,
                    price_range_field: price_range_field,
                    rating_field: rating_field,
                    radius_field: radius_field,
                    open_field: open_field,
                    tag_field: tag_field,
                    custom_search_field: custom_search_field,
                    website_field: website_field,
                    email_field: email_field,
                    phone_field: phone_field,
                    fax_field: fax_field,
                    zip_field: zip_field,
                    reset_filters: reset_filters,
                    apply_filter: apply_filter,
                },
                success: function (html) {
                    $(".ajax-search-result").removeClass('loading');
                    if(html.split('-/')[1] !== 'error'){
                        $(".bdmv-listing").html("");
                        $(".bdmv-map-listing").remove();
                        $(".ajax-search-result").show();
                        $(".ajax-search-result").empty();
                        $(".ajax-search-result").append(html);
                        var _listing = $('.bdmv-columns-two .bdmv-listing');
                        $('.bdmv-columns-two .bdmv-search').append(_listing);

                    } else {
                        $(".bdmv-listing").html('<div class="atbd-ajax-404error">\n' +
                            '                    <span class="la la-frown-o"></span>\n' +
                            '                    <h3>Nothing Found</h3>\n' +
                            '                    <p>Please try to change your search settings</p>\n' +
                            '                </div>');
                        $('.bdmv-map').html(html.split('-/')[2]);
                        $(".bdmv-listing").addClass('bdmv-nolisting');
                        $('.bdmv-map-listing').removeClass('loading')
                    }
                }
            });

        });

        //ajax pagination
        $("body").on("click", ".bdmv-filter-pagination-ajx a.haspaglink",function () {
            var $this = jQuery(this);
            var skeyword 			= 	$this.data('skeyword');
            var pageno              = $(this).data('pageurl');
            var display_header = $('#display_header').val();
            var header_title = $('#header_title').val();
            var show_pagination = $('#show_pagination').val();
            var listings_per_page = $('#listings_per_page').val();
            var location_slug = $('#location_slug').val();
            var category_slug = $('#category_slug').val();
            var key = $('#search_q').val();
            var location = $('.bdas-location-search').val();
            var category = $('.bdas-category-search').val();
            var open_now = [];
            var price = [];
            var custom_field = {};
            var website = $('#website').val();
            var phone = $('#phone').val();
            var address = $('#address').val();
            var zip_code = $('#zip_code').val();
            var email = $('#email').val();
            var miles = $('#atbd_rs_value').val();
            var cityLat = $('#cityLat').val();
            var cityLng = $('#cityLng').val();
            var tag = "";
            var search_by_rating = "";
            var view_as = "";
            if($(".map-view-grid").hasClass("active")){
                view_as = "grid";
            } else if($(".map-view-list").hasClass("active")) {
                view_as = "list";
            }
            var sort_by = "";
            if($(".sort-title-asc").hasClass("active")){
                sort_by = "title-asc";
            } else if($(".sort-title-desc").hasClass("active")) {
                sort_by = "title-desc";
            } else if($(".sort-date-desc").hasClass("active")) {
                sort_by = "date-desc";
            } else if($(".sort-date-asc").hasClass("active")) {
                sort_by = "date-asc";
            } else if($(".sort-price-asc").hasClass("active")) {
                sort_by = "price-asc";
            } else if($(".sort-price-desc").hasClass("active")) {
                sort_by = "price-desc";
            } else if($(".sort-rand").hasClass("active")) {
                sort_by = "rand";
            }
            $(".ajax-search-result").addClass('loading');
            $(".bdmv-map-listing").addClass("loading");
            $('input[name^="price"]').each(function (index, el) {
                price.push($(el).val())
            });
            $.each($("input[name='open_now']:checked"), function () {
                open_now.push($(this).val());
            });
            $.each($("input[name='in_tag']:checked"), function () {
                tag = $(this).val();
            });
            $.each($("input[name='search_by_rating']:checked"), function () {
                search_by_rating = $(this).val();
            });
            $('[name^="custom_field"]').each(function (index, el) {
                var test = $(el).attr('name');
                var type = $(el).attr('type');
                var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');
                if('radio' === type) {
                    $.each($("input[name='custom_field["+post_id+"]']:checked"), function () {
                        value = $(this).val();
                        custom_field[post_id] = value;
                    });
                } else if ('checkbox' === type) {
                    post_id = post_id.split('[]')[0];
                    $.each($("input[name='custom_field["+post_id+"][]']:checked"), function () {
                        var checkValue = [];
                        value = $(this).val();
                        checkValue.push(value);
                        custom_field[post_id] = checkValue;
                    });
                } else {
                    var value = $(el).val();
                    custom_field[post_id] = value;
                }
            });
            $(".bdmv-columns-two .bdmv-search .bdmv-listing").fadeOut(1000);
            $.ajax({
                url: bdrr_submit.ajax_url,
                type: "POST",
                data: {
                    action: "ajax_search_listing",
                    view_as: view_as,
                    display_header: display_header,
                    header_title: header_title,
                    show_pagination: show_pagination,
                    listings_per_page: listings_per_page,
                    location_slug: location_slug,
                    category_slug : category_slug,
                    key: key,
                    location: location,
                    category: category,
                    custom_field: custom_field,
                    price: price,
                    open_now: open_now,
                    website: website,
                    phone: phone,
                    address: address,
                    zip_code: zip_code,
                    email: email,
                    miles: miles,
                    cityLat: cityLat,
                    cityLng: cityLng,
                    tag: tag,
                    search_by_rating: search_by_rating,
                    sort_by: sort_by,
                    skeyword : skeyword,
                    pageno : pageno,
                    nonce_get: nonce_get,
                    view_columns: view_columns,
                    text_field: text_field,
                    category_field: category_field,
                    location_field: location_field,
                    address_field: address_field,
                    price_field: price_field,
                    price_range_field: price_range_field,
                    rating_field: rating_field,
                    radius_field: radius_field,
                    open_field: open_field,
                    tag_field: tag_field,
                    custom_search_field: custom_search_field,
                    website_field: website_field,
                    email_field: email_field,
                    phone_field: phone_field,
                    fax_field: fax_field,
                    zip_field: zip_field,
                    reset_filters: reset_filters,
                    apply_filter: apply_filter,
                },
                success: function (html) {
                    $(".ajax-search-result").removeClass('loading');
                    if(html.split('-/')[1] !== 'error'){
                        $(".bdmv-listing").html("");
                        $(".bdmv-map-listing").remove();
                        $(".ajax-search-result").show();
                        $(".ajax-search-result").empty();
                        $(".ajax-search-result").append(html);
                        var _listing = $('.bdmv-columns-two .bdmv-listing');
                        $('.bdmv-columns-two .bdmv-search').append(_listing);

                    } else {
                        $(".bdmv-listing").html('<div class="atbd-ajax-404error">\n' +
                            '                    <span class="la la-frown-o"></span>\n' +
                            '                    <h3>Nothing Found</h3>\n' +
                            '                    <p>Please try to change your search settings</p>\n' +
                            '                </div>');
                        $('.bdmv-map').html(html.split('-/')[2]);
                        $(".bdmv-listing").addClass('bdmv-nolisting');
                        $('.bdmv-map-listing').removeClass('loading')
                    }
                }
            });
        });

        $(".bdmv-columns-two .bdmv-listing").appendTo(".bdmv-columns-two .bdmv-search");

        //All listing with map: filter search style(Slide / dropdown)
        //slide
        $(".dlm-filter-slide .atbd_more-filter-contents").hide();
        $(".dlm-filter-slide .dlm_filter-btn").on("click", function () {
            $(this).toggleClass("active");
            $(".dlm-filter-slide .atbd_more-filter-contents").slideToggle();
        });
        $(".dlm-filter-slide .ajax-search-filter").on("click", function (){
            setTimeout(function () {
                $(".dlm-filter-slide .dlm_filter-btn").removeClass("active");
                $(".dlm-filter-slide .atbd_more-filter-contents").slideUp();
            }, 1000);
        });

        //dropdown
        $(".dlm-filter-dropdown .dlm_filter-btn").on("click", function () {
            $(this).toggleClass("active");
            $(".dlm-filter-dropdown .atbd_more-filter-contents").toggleClass("active");
            //$(".bdmv-columns-two .bdmv-listing").toggleClass("dlm-filter-overlay");
        });

        $(".dlm-filter-dropdown .ajax-search-filter").on("click", function (){
            setTimeout(function () {
                $(".dlm-filter-dropdown .atbd_more-filter-contents, .dlm-filter-dropdown .dlm_filter-btn").removeClass("active");
                //$(".bdmv-columns-two .bdmv-listing").removeClass("dlm-filter-overlay");
            }, 1000);
        });

        //responsive fix
        $(".dlm-res-btn").on("click", function (e) {
            e.preventDefault();
            $(this).addClass("active");
            $(this).siblings().removeClass("active");
            if($(".bdmv-columns-two #js-dlm-search").hasClass("active")){
                $(".bdmv-map-listing, .bdmv-listing").hide();
                $(".bdmv-search-content, .bdmv-search").show();
            }else if($(".bdmv-columns-two #js-dlm-listings").hasClass("active")){
                $(".bdmv-search-content, .bdmv-map-listing, .ajax-search-result").hide();
                $(".bdmv-listing, .bdmv-search").show();
                if($(".bdmv-search .bdmv-listing").length === 2){
                    $(".bdmv-search-content + .bdmv-listing").hide();
                }
            }else if($(".bdmv-columns-two #js-dlm-map").hasClass("active")){
                $(".bdmv-search-content, .bdmv-listing, .bdmv-search").hide();
                $(".bdmv-map-listing, .ajax-search-result").show();
                if($(".ajax-search-result").is(":empty")){
                    $(".ajax-search-result").hide();
                }

                // three column
            }else if($(".bdmv-columns-three #js-dlm-search").hasClass("active")){
                $(".bdmv-map-listing, .ajax-search-result").hide();
                $(".bdmv-search").show();
            }else if($(".bdmv-columns-three #js-dlm-listings").hasClass("active")){
                $(".bdmv-search, .bdmv-map, .ajax-search-result .bdmv-map").hide();
                $(".bdmv-map-listing, .bdmv-listing, .ajax-search-result, .ajax-search-result .bdmv-listing").show();
                if($(".ajax-search-result").is(":empty")){
                    $(".ajax-search-result, .ajax-search-result .bdmv-listing").hide();
                }
            }else if($(".bdmv-columns-three #js-dlm-map").hasClass("active")){
                $(".bdmv-search, .bdmv-listing").hide();
                $(".bdmv-map-listing, .bdmv-map, .ajax-search-result, .ajax-search-result .bdmv-map").show();
                if($(".ajax-search-result").is(":empty")){
                    $(".ajax-search-result, .ajax-search-result .bdmv-map").hide();
                }
            }
        });
        if($(window).width() < 1200){
            if($(".bdmv-columns-two #js-dlm-listings").hasClass("active")){
                $(".bdmv-search-content, .bdmv-map-listing").hide();
            }
            if($(".bdmv-columns-three #js-dlm-listings").hasClass("active")){
                $(".bdmv-search, .bdmv-map").hide();
            }
            $(".ajax-search").on("click", function () {
                $("#js-dlm-listings").addClass("active");
                $("#js-dlm-listings").siblings().removeClass("active");
                setTimeout(function () {
                    $("#js-dlm-listings").click();
                },2000)
            });
        }


        //reset fields
        function resetFields(){
            var inputArray = document.querySelectorAll('.search-area input');
            inputArray.forEach(function (input){
                if(input.getAttribute("type") !== "hidden" || input.getAttribute("id") === "atbd_rs_value"){
                    input.value = "";
                }
            });

            var textAreaArray = document.querySelectorAll('.search-area textArea');
            textAreaArray.forEach(function (textArea){
                textArea.innerHTML = "";
            });

            var range = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-range");
            var rangePos = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-handle");
            var rangeAmount = document.querySelector(".atbdpr_amount");
            if(range){
                range.setAttribute("style", "width: 0;");
            }
            if(rangePos){
                rangePos.setAttribute("style", "left: 0;");
            }
            if(rangeAmount){
                rangeAmount.innerText = "0 Mile";
            }

            $('.search-area input:checkbox').attr('checked', false);
            $('input[type="radio"]').attr('checked', false);
            $('.search-area select').prop('selectedIndex',0);
            $("#at_biz_dir-location, #at_biz_dir-category").val('').trigger('change');

        }
        $(".submit_btn button[type='reset']").on("click", function () {
            resetFields();
        })
    });
})(jQuery);

