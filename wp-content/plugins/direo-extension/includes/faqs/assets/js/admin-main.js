jQuery(document).ready(function ($) {

    /*This function handles all ajax request*/
    function atbdp_do_ajax( ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
        var data;
        if(ActionName) data = "action=" + ActionName;
        if(arg)    data = arg + "&action=" + ActionName;
        if(arg && !ActionName) data = arg;
        //data = data ;

        var n = data.search(atbdp_admin_data.nonceName);
        if(n<0){
            data = data + "&" + atbdp_admin_data.nonceName + "=" + atbdp_admin_data.nonce;
        }

        jQuery.ajax({
            type: "post",
            url: atbdp_admin_data.ajaxurl,
            data: data,
            beforeSend: function() { jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(ElementToShowLoadingIconAfter); },
            success: function( data ) {
                jQuery(".atbdp_ajax_loading").remove();
                CallBackHandler(data);
            }
        });
    }
// enable sorting if only the container has any social or skill field
    // enable sorting if only the container has any social or skill field
    var $s_wrap = $("#faqs_info_sortable_container");// cache it
    if(window.outerWidth > 1700) {
        if ($s_wrap.length) {
            $s_wrap.sortable(
                {
                    axis: 'y',
                    opacity: '0.7'
                }
            );
        }
    }

    // SOCIAL SECTION
    // Rearrange the IDS and Add new social field
    $("#addNewFAQS").on('click', function(){
        var currentItems = $('.atbdp_faqs_wrapper').length;
        var ID = "id="+currentItems; // eg. 'id=3'
        var iconBindingElement = jQuery('#addNewFAQS');
        // arrange names ID in order before adding new elements
        $('.atbdp_faqs_wrapper').each(function( index , element) {
            var e = $(element);
            //console.log(index);
            e.attr('id','faqsID-'+index);
            e.find('.atbdp_faqs_quez').attr('name', 'faqs['+index+'][quez]');
            e.find('.atbdp_faqs_input').attr('name', 'faqs['+index+'][ans]');
            e.find('.removeFAQSField').attr('data-id',index);
        });
        // now add the new elements. we could do it here without using ajax but it would require more markup here.
        atbdp_do_ajax( iconBindingElement, 'atbdp_faqs_handler', ID, function(data){
            //console.log(data);
            $s_wrap.append(data);
            tinymce.init({selector:'textarea'});
        });
    });


    // remove the social field and then reset the ids while maintaining position
    $(document).on('click', '.removeFAQSField', function(e){
        var id = $(this).data("id"),
            elementToRemove = $('div#faqsID-'+id);
        event.preventDefault();
        /* Act on the event */
        swal({
                title: atbdp_admin_data.i18n_text.confirmation_text,
                text: atbdp_admin_data.i18n_text.ask_conf_sl_lnk_del_txt,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: atbdp_admin_data.i18n_text.confirm_delete,
                closeOnConfirm: false },
            function(isConfirm) {
                if(isConfirm){
                    // user has confirmed, no remove the item and reset the ids
                    elementToRemove.slideUp( "fast", function(){
                        elementToRemove.remove();
                        // reorder the index
                        $('.atbdp_faqs_wrapper').each(function( index , element) {
                            var e = $(element);
                            e.attr('id','faqsID-'+index);
                            e.find('.atbdp_faqs_quez').attr('name', 'faqs['+index+'][quez]');
                            e.find('.atbdp_faqs_input').attr('name', 'faqs['+index+'][ans]');
                            e.find('.removeFAQSField').attr('data-id',index);
                        });
                    });

                    // show success message
                    swal({
                        title: atbdp_admin_data.i18n_text.deleted,
                        //text: "Item has been deleted.",
                        type:"success",
                        timer: 200,
                        showConfirmButton: false });
                }

            });


    });


});