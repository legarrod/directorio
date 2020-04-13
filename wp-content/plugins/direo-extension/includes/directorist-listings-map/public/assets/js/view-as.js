(function ($) {
    jQuery(document).ready(function ($) {
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
        $("body").on("click", '.view-as a', function () {
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
            var view_as = $(this).attr('data-view');
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
        $("body").on("click", '.sort-by a', function () {
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
            var sort_by = $(this).attr('data-sort');
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
    });
})(jQuery);

