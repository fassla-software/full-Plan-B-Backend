(function($) {
    "use strict";

    $(document).ready(function() {

        /*
        ========================================
            input search open item
        ========================================
        */
        $(document).on('keyup change', '#search_form_input', function(event) {
            let input_values = $(this).val();
            if (input_values.length > 0) {
                $('.search-suggestions, .search-overlay').addClass("active");
            } else {
                $('.search-suggestions').removeClass("active");
            }
        });

        /* 
        ----------------------------------------
            SearchBar
        ----------------------------------------
        */
        $(document).ready(function() {
            $('.search-close, .search-overlay').on('click', function() {
                $('.search-bar, .search-overlay').removeClass('active');
            });
            $('.search-open').on('click', function() {
                $('.search-bar, .search-overlay').toggleClass('active');
            });
        });


        /* 
        ========================================
            Shop Responsive Sidebar
        ========================================
        */
        $(document).on('click', '.shop-close-content-icon, .close-chat, .responsive-overlay, .responsive-overlay-lg', function() {
            $('.shop-close-content, .chat-wrapper-contact-close, .responsive-overlay, .responsive-overlay-lg').removeClass('active');
        });
        $(document).on('click', '.shop-icon-sidebar, .chat-sidebar', function() {
            $('.shop-close-content, .chat-wrapper-contact-close, .responsive-overlay, .responsive-overlay-lg').addClass('active');
        });

        /* 
        ========================================
            Dashboard Tab
        ========================================
        */
        $(document).on('click', 'ul.dashboard-tabs li', function(e) {
            e.preventDefault();
            var tab_id = $(this).attr('data-tab');

            $('ul.dashboard-tabs li').removeClass('active');
            $('.dashboard-tab-content-item').removeClass('active');

            $(this).addClass('active');
            $("#" + tab_id).addClass('active');
        });

        /* 
        ========================================
            Dropdown Submenu
        ========================================
        */
        $(document).on('click', '.dashboard-list .has-children a', function(e) {
            var sh = $(this).parent('.has-children');
            if (sh.hasClass('open')) {
                sh.removeClass('open');
                sh.find('.submenu').children('.has-children').removeClass("open"); //2nd children remove 
                sh.find('.submenu').removeClass('open');
                sh.find('.submenu').slideUp(300);
            } else {
                sh.addClass('open');
                sh.children('.submenu').slideDown(300);
                sh.siblings('.has-children').children('.submenu').slideUp(300);
                sh.siblings('.has-children').removeClass('open');
                sh.siblings().find('.submenu').children('.has-children').removeClass('open'); //2nd Submenu children remove 
                sh.siblings().find('.submenu').slideUp(300); //2nd Submenu children Slide Up Down 
            }
        });

        /* 
        ========================================
            Dashboard JobFilter Sidebar
        ========================================
        */
        $(document).on('click', '.shop-close-content-icon, .dash-responsive-overlay, .dash-responsive-overlay-lg', function() {
            $('.shop-close-content, .dash-responsive-overlay, .dash-responsive-overlay-lg').removeClass('active');
        });
        $(document).on('click', '.shop-icon-sidebar', function() {
            $('.shop-close-content, .dash-responsive-overlay, .dash-responsive-overlay-lg').addClass('active');
        });
        /* 
        ========================================
            Dashboard Responsive Sidebar
        ========================================
        */
        $(document).on('click', '.close-bars, .body-overlay', function() {
            $('.dashboard-left-content, .body-overlay').removeClass('active');
        });
        $(document).on('click', '.sidebar-icon', function() {
            $('.dashboard-left-content, .body-overlay').toggleClass('active');
        });

        /* 
        ========================================
            Click Active Class
        ========================================
        */
        $(document).on('click', '.active-list .item', function() {
            $(this).siblings().removeClass('active');
            $(this).toggleClass('active');
        });

        $(document).on('click', '.click-notification', function() {
            $(this).addClass('active');
        });

        /* 
        ========================================
            Pagination On Click Js
        ========================================
        */
        $(document).on('click', '.pagination-list-item', function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });
        /* Next Previous btn Click */
        $(document).on('click', '.pagination-list-item-next', function() {
            $(this).parent().find('.pagination-list-item.active').next('.pagination-list-item').addClass('active');
            $(this).parent().find('.pagination-list-item.active').prev('.pagination-list-item').removeClass('active');
        });
        $(document).on('click', '.pagination-list-item-prev', function() {
            $(this).parent().find('.pagination-list-item.active').prev('.pagination-list-item').addClass('active');
            $(this).parent().find('.pagination-list-item.active').next('.pagination-list-item').removeClass('active');
        });
        /*      
        ========================================
            Flat Picker js
        ========================================
        */
        $(".date-picker").flatpickr({
            mode: "single",
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            altInput: true,
            altFormat: "F j, Y",
            time_12hr: true,
        });

        /*-------------------------------
            Click Slide Open Close
        ------------------------------*/
        $(document).on('click', '.single-shop-left-title .title', function(e) {
            var shopTitle = $(this).parent('.single-shop-left-title');
            if (shopTitle.hasClass('open')) {
                shopTitle.removeClass('open');
                shopTitle.find('.single-shop-left-inner').removeClass('open');
                shopTitle.find('.single-shop-left-inner').slideUp(300);
            } else {
                shopTitle.addClass('open');
                shopTitle.children('.single-shop-left-inner').slideDown(300);
                shopTitle.siblings('.single-shop-left-title').children('.single-shop-left-inner').slideUp(300);
                shopTitle.siblings('.single-shop-left-title').removeClass('open');
            }
        });

        /* 
        =========================================================
            Edit and Delete Popup Js
        =========================================================
        */
        $(document).on('click', '.edit-click', function() {
            let editParent = $(this).parent().find(".elipsis__wrap");
            editParent.toggleClass("show");
        });

        /* 
        ========================================
            Click Popup Add Topic Class
        ========================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.topic-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.topic-click', function() {
            $('.topic-popup, .popup-overlay').toggleClass('popup-active');
        });

        /* 
        ========================================
            Click Popup Add Article Class
        ========================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.article-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.article-click', function() {
            $('.article-popup, .popup-overlay').toggleClass('popup-active');
        });

        /* 
        ========================================
            Click Popup Add Faq Class
        ========================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.faq-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.faq-click', function() {
            $('.faq-popup, .popup-overlay').toggleClass('popup-active');
        });

        /* 
        ========================================
            Click Popup Delete Faq Class
        ========================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.faq-delete, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.deleteFaq-click', function() {
            $('.faq-delete, .popup-overlay').toggleClass('popup-active');
        });

        /* 
        ========================================
            Click Popup Edit Faq Class
        ========================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.faq-edit, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.editFaq-click', function() {
            $('.faq-edit, .popup-overlay').toggleClass('popup-active');
        });

        /* 
        ========================================
            Nice Select
        ========================================
        */
        $('.js_nice_select').niceSelect();

        /*
        ========================================
            Row Check All Add on Click
        ========================================
        */
        if ($('.check-all-row').length) {
            $(document).on("click", ".check-all-row", function() {
                if ($(".check-all-row").is(':checked')) {
                    $('.row-check').prop('checked', true);
                } else {
                    $('.row-check').prop('checked', false);
                }
            });
        }
        /*
        ========================================
            Click add Value text
        ========================================
        */
        $(document).on('click', '.status_dropdown .status_dropdown__click', function(event) {
            $(this).closest('.status_dropdown__list').slideDown(200);
        });
        $(document).on('click', '.status_dropdown .status_dropdown__item', function(event) {
            $(this).closest('.status_dropdown__list').slideUp(200);
            let el = $(this);
            let value = el.data('value');
            let parentWrap = el.parent().parent();
            parentWrap.find('.status_dropdown__click').text(value);
            parentWrap.find('.status_dropdown__click').attr('value', value);
            return false;
        });

        /* 
        ========================================
            Bootstrap Tooltip
        ========================================
        */
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        /* 
        ========================================
            Range Slider
        ========================================
        */
        var i = document.querySelector(".ui-range-slider, .ui-range-slider-two");
        if (void 0 !== i && null !== i) {
            var j = parseInt(i.parentNode.getAttribute("data-start-min"), 5),
                k = parseInt(i.parentNode.getAttribute("data-start-max"), 5),
                l = parseInt(i.parentNode.getAttribute("data-min"), 5),
                m = parseInt(i.parentNode.getAttribute("data-max"), 5),
                n = parseInt(i.parentNode.getAttribute("data-step"), 5),
                o = document.querySelector(".ui-range-value-min span"),
                p = document.querySelector(".ui-range-value-max span"),
                q = document.querySelector(".ui-range-value-min input"),
                r = document.querySelector(".ui-range-value-max input");
            noUiSlider.create(i, {
                start: [j, k],
                connect: !0,
                step: n,
                range: {
                    min: l,
                    max: m
                }
            }), i.noUiSlider.on("update", function(a, b) {
                var c = a[b];
                b ? (p.innerHTML = Math.round(c), r.value = Math.round(c)) : (o.innerHTML = Math.round(c), q.value = Math.round(c))
            })
        }

        /* 
        ========================================
            Password Show Hide On Click
        ========================================
        */
        $(document).on("click", ".toggle-password", function(e) {
            e.preventDefault();
            let inputPass = $(this).parent().find("input");
            $(this).toggleClass("show-pass");
            if (inputPass.attr("type") == "password") {
                inputPass.attr("type", "text");
            } else {
                inputPass.attr("type", "password");
            }
        });

        /* 
        ========================================
            Radio box active Class Js
        ========================================
        */
        $(document).on('click', '.custom-radio-single', function(e) {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });

        /*      
        ========================================
            Flat Picker js
        ========================================
        */
        $(".date-picker").flatpickr({
            mode: "single",
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            altInput: true,
            altFormat: "F j, Y",
            time_12hr: true,
        });

        /*
        ========================================
            Mouse Cursor Js
        ========================================
        */
        var myCursor = $('.mouse-move');
        if (myCursor.length) {
            if ($('body')) {
                const e = document.querySelector('.mouse-inner'),
                    t = document.querySelector('.mouse-outer');
                let n, i = 0,
                    o = !1;
                window.onmousemove = function(s) {
                    o || (t.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)"), e.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)", n = s.clientY, i = s.clientX
                }, $('body').on("mouseenter", "a, .cursor-pointer", function() {
                    e.classList.add('mouse-hover'), t.classList.add('mouse-hover')
                }), $('body').on("mouseleave", "a, .cursor-pointer", function() {
                    $(this).is("a") && $(this).closest(".cursor-pointer").length || (e.classList.remove('mouse-hover'), t.classList.remove('mouse-hover'))
                }), e.style.visibility = "visible", t.style.visibility = "visible"
            }
        }

    });

    /*-----------------
        Preloader
    ------------------*/
    $(window).on('load', function() {
        $('#preloader').delay(300).fadeOut('slow');
        $('body').delay(300).css({
            'overflow': 'visible'
        });
    });


})(jQuery);