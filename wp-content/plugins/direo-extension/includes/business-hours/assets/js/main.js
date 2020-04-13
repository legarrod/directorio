(function ($) {
    $(document).ready(function () {
        var alwaysOpen = $('#enable247hour');
        $(alwaysOpen).change(function () {
            if (this.checked) {
                $('#atbdp-hours-fields').hide();
            } else {
                $('#atbdp-hours-fields').show();
            }
        });
        if ($(alwaysOpen).attr('checked')) {
            $('#atbdp-hours-fields').hide();
        } else {
            $('#atbdp-hours-fields').show();
        }


        (function () {
            //business hours tabs
            let tab_nav = document.querySelectorAll(".dbh-tab__nav__item");
            tab_nav.forEach(function (elm) {
                elm.addEventListener('click', function (e) {
                    e.preventDefault();
                    tab_nav.forEach(function (itm) {
                        itm.classList.remove("active");
                    });
                    this.classList.add("active");

                    let data = this.getAttribute("data-target");
                    let tab_pans = document.querySelectorAll(".dbh-tab-panel");
                    tab_pans.forEach(function (p_elm) {
                        p_elm.classList.remove("active");
                        p_elm.classList.remove("dbh-in");
                    });
                    document.querySelector("#" + data).classList.add("active");
                    document.querySelector("#" + data).classList.add("dbh-in");
                })
            });

            //business hours hide show input fields based on hours type
            const dbh_checkbox = document.querySelectorAll(".dbh-checkbox input[type=radio]");

            dbh_checkbox.forEach(function (item) {
               item.addEventListener("change", function (e) {
                   if(e.target.value !== "time"){
                       this.closest('.dbh-tab-panel').querySelector(".dbh-select-hours--list").style.display = "none";
                   }else{
                       this.closest('.dbh-tab-panel').querySelector(".dbh-select-hours--list").style.display = "block";
                   }
               });
                /*if(item.value === "time"){
                    item.closest('.dbh-tab-panel').querySelector(".dbh-select-hours--list").style.display = "block";
                }else{
                    item.closest('.dbh-tab-panel').querySelector(".dbh-select-hours--list").style.display = "none";
                }*/
            });

            document.querySelectorAll(".dbh-tab-panel").forEach(function (e) {
               if(e.querySelector(".dbh-checkbox input.dbh-enter-hours").checked === true){
                   e.querySelector(".dbh-select-hours--list").style.display = "block";
               }else{
                   e.querySelector(".dbh-select-hours--list").style.display = "none";
               }
            });

            //business hour time input fields events
            function dbh_input() {
                let custom_select = document.querySelectorAll(".dbh-custom-select input");
                let custom_options = document.querySelectorAll(".dbh-default-times");
                let dbh_hours_list = document.querySelectorAll(".dbh-default-times li");

                //hide default time dropdown
                custom_options.forEach((elm) => {
                    elm.style.display = "none";
                });

                //show time dropdown on click
                custom_select.forEach((elm) => {
                    elm.addEventListener("click", function () {
                        custom_options.forEach((elm) => {
                            elm.style.display = "none";
                        });
                        this.nextElementSibling.style.display = "block";
                    });
                });

                //hide time dropdown when click other than the custom select wrapper
                document.addEventListener("click", function (e) {
                    if (!e.target.closest(".dbh-custom-select")) {
                        custom_options.forEach((elm) => {
                            elm.style.display = "none";
                        });
                    }
                });

                //get and show the value in input field from drop down
                dbh_hours_list.forEach(function (elm) {
                    elm.addEventListener("click", () => {
                        let child = elm.children;
                        elm.parentElement.previousElementSibling.value = child[0].innerText;
                        elm.parentElement.previousElementSibling.setAttribute('data-time', child[0].getAttribute('data-time'));
                        custom_options.forEach((elm) => {
                            elm.style.display = "none";
                        });
                    });
                    elm.children[0].addEventListener("click", (e) => {
                        e.preventDefault();
                    })
                });

                //remove all fields button
                const remove_btn = document.querySelectorAll(".dbh-remove");
                remove_btn.forEach(elm => {
                    elm.addEventListener("click", function (e) {
                        e.preventDefault();
                        this.parentElement.remove();
                    })
                });

                //filter dropdown items based on input text
                const time_input = document.querySelectorAll(".dbh-time-input");
                time_input.forEach(elm => {
                    elm.addEventListener("keyup", (e) => {
                        let value = e.target.value;
                        const li = e.target.closest(".dbh-custom-select").querySelectorAll(".dbh-default-times li");
                        const ul = e.target.closest(".dbh-custom-select").querySelector(".dbh-default-times");
                        li.forEach(itm => {
                            itm.style.display = "none";
                        });

                        let filter = Object.values(li).filter(item => {
                            return item.querySelector("a").innerText.startsWith(value);
                        });
                        filter.forEach(itm => {
                            itm.style.display = 'block';
                        });
                    })
                })
            }

            //callbacks all input fields events
            dbh_input();

            //clone time input fields
            let clone_btn = document.querySelector(".dbh-add-hours");
            let clone_elm = document.querySelector(".dbh-select-hours");
            if (clone_btn) {
                clone_btn.addEventListener("click", function (e) {
                    e.preventDefault();
                    let clone = clone_elm.cloneNode(true);
                    document.querySelector(".dbh-select-hours-wrapper").appendChild(clone);

                    //callbacks all input fields events
                    dbh_input();
                });
            }
        })();

        // timezone dropdown
        if ($('#dbh-select-timezone').length){
            $('#dbh-select-timezone').select2({
                //placeholder: "Timezone",
                allowClear: false,
            });
        }


        //hide time fields if 24/7 checked
        if ($('#enable247hour').length){
            document.querySelector('input#enable247hour').addEventListener('change', function (e) {
                if(this.checked){
                    document.querySelector('.dbh-wrapper__tab').style.display = "none";
                    document.querySelector('.dbh-timezone').style.display = "none";
                }else{
                    document.querySelector('.dbh-wrapper__tab').style.display = "block";
                    document.querySelector('.dbh-timezone').style.display = "block";
                }
            });
        }
        if($('#disable_bz_hour_listing').length){
            document.querySelector('input#disable_bz_hour_listing').addEventListener('change', function (e) {
                if(this.checked){
                    document.querySelector('.dbh-wrapper__tab').style.display = "none";
                    document.querySelector('.dbh-timezone').style.display = "none";
                    document.querySelector('.enable247hour').style.display = "none";
                }else{
                    document.querySelector('.dbh-wrapper__tab').style.display = "block";
                    document.querySelector('.dbh-timezone').style.display = "block";
                    document.querySelector('.enable247hour').style.display = "block";
                }
            });
        }

    });

})(jQuery);

