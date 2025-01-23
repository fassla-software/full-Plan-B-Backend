(function($) {
    "use strict";

    $(document).ready(function() {

        /*
        ========================================
            Otp input Js
        ========================================
        */
        let inputOtp = $(".OTP-input");
        inputOtp.on({
            input(eve) { // Value typing
                const inpCount = inputOtp.index(this);
                if (this.value) inputOtp.eq(inpCount + 1).focus();
            },
            keydown(eve) { // Value Deleting
                const inpCount = inputOtp.index(this);
                if (!this.value && eve.key === "Backspace" && inpCount) inputOtp.eq(inpCount - 1).focus();
            }
        });

        /* Resend Code  */
        function sendCode() {
            let interval;
            clearInterval(interval);
            interval = setInterval(function() {
                let timer = $('.time-countdown').html();
                timer = timer.split(':');
                let minutes = timer[0];
                let seconds = timer[1];
                seconds -= 1;
                if (minutes < 0) return;
                else if (seconds < 0 && minutes != 0) {
                    minutes -= 1;
                    seconds = 59;
                } else if (seconds < 10 && length.seconds != 2) seconds = '0' + seconds;

                $('.time-countdown').html(minutes + ':' + seconds);

                if (minutes == 0 && seconds == 0) clearInterval(interval);
            }, 1000);
        }
        $(document).on("click", "#click-timer-code", function(e) {
            e.preventDefault();
            $('.time-countdown').text("00:29");
            sendCode();
        });



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
            Navbar Toggler
        ========================================
        */
        $(document).on('click', '.navbar-toggler', function() {
            $(".navbar-toggler").toggleClass("active");
        });

        $(document).on('click', '.click-nav-right-icon', function() {
            $(".show-nav-content").toggleClass("show");
        });

        /* 
        ========================================
            Show nav right content
        ========================================
        */
        $(document).on('click', '.click-content-show', function() {
            $(".right-contents-show").toggleClass("show");
        });

        /* 
        ========================================
            Shop Responsive Sidebar
        ========================================
        */
        $(document).on('click', '.shop-close-content-icon, .close-chat, .responsive-overlay, .responsive-overlay-lg, .dash-responsive-overlay, .dash-responsive-overlay-lg', function() {
            $('.shop-close-content, .chat-wrapper-contact-close, .responsive-overlay, .responsive-overlay-lg, .dash-responsive-overlay, .dash-responsive-overlay-lg').removeClass('active');
        });
        $(document).on('click', '.shop-icon-sidebar, .chat-sidebar', function() {
            $('.shop-close-content, .chat-wrapper-contact-close, .responsive-overlay, .responsive-overlay-lg, .dash-responsive-overlay, .dash-responsive-overlay-lg').addClass('active');
        });

        /* 
        ========================================
            Tab
        ========================================
        */
        $(document).on('click', 'ul.tabs li, .tab-parents .tab-list', function(e) {
            e.preventDefault();
            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li, .tab-parents .tab-list').removeClass('active');
            $('.tab-content-item').removeClass('active');

            $(this).addClass('active');
            $("#" + tab_id).addClass('active');
        });
        /* tab Two */
        $(document).on('click', 'ul.tabs-two li', function(e) {
            e.preventDefault();
            var tab_id2 = $(this).attr('data-tab');

            $(' ul.tabs-two li').removeClass('active');
            $('.tab-content-item-two').removeClass('active');

            $(this).addClass('active');
            $("#" + tab_id2).addClass('active');

        });
        /* tab Three */
        $(document).on('click', 'ul.tabs-three li', function(e) {
            e.preventDefault();
            var tab_id3 = $(this).attr('data-tab');

            $(' ul.tabs-three li').removeClass('active');
            $('.tab-content-item-three').removeClass('active');

            $(this).addClass('active');
            $("#" + tab_id3).addClass('active');

        });
        /* tab Four */
        $(document).on('click', 'ul.tabs-four li', function(e) {
            e.preventDefault();
            var tab_id4 = $(this).attr('data-tab');

            $(' ul.tabs-four li').removeClass('active');
            $('.tab-content-item-four').removeClass('active');

            $(this).addClass('active');
            $("#" + tab_id4).addClass('active');

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
            Slider Active In Tab
        ========================================
        */
        $(document).on('click', 'ul.tabs li', function(e) {
            $('.global-slick-init').slick('setPosition');
        })

        /* 
        ========================================
            Pricing Switch 
        ========================================
        */

        $(document).on("click", ".tab-monthly", function() {
            $('.input-switch').prop('checked', false);
        });
        $(document).on("click", ".tab-yearly", function() {
            $('.input-switch').prop('checked', true);
        });

        $(document).on("click", ".input-switch", function() {
            if ($(".input-switch:checked").length) {
                $(".pricing-tabs-switch .tab-yearly").click();
            } else {
                $(".pricing-tabs-switch .tab-monthly").click();
            }

        });

        /* 
        ========================================
            Product Quantity js
        ========================================
        */
        $(function() {

            $(document).on('click', '.plus', function() {
                var selectedInput = $(this).prev('.quantity-input');
                // if (selectedInput.val() < 50) {
                selectedInput[0].stepUp(1);
                // }
            });
            $(document).on('click', '.minus', function() {
                var selectedInput = $(this).next('.quantity-input');
                if (selectedInput.val() > 1) {
                    selectedInput[0].stepDown(1);
                }
            });

        });

        /* 
        ========================================
            Click Wishlist Active Class
        ========================================
        */

        $(document).on('click', '.click-wishlist', function() {
            $(this).toggleClass('active');
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
            Chat Click and Active Class
        ========================================
        */
        $(document).on('click', '.chat-wrapper-contact-list-item', function() {
            $(this).siblings().removeClass('active');
            $(this).toggleClass('active');
        });

        /* 
        ================================================
            Token Radio input Class add remove
        ================================================
        */
        $(document).on('click', '.token-item-list, .identity-verifying-list', function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            if ($('.token-radio, .verify-radio').is(':checked')) {
                $('.token-item-list.active, .identity-verifying-list.active').find('.token-radio, .verify-radio').prop('checked', true);
            } else {
                $('.token-item-list, .identity-verifying-list').prop('checked', false);
            }
        });

        /* 
        ===================================================
            Time Tracker Js Start
        ===================================================
        */
        let elTime = document.querySelector("#time"),
            elStart = document.querySelector("#start"),
            elPause = document.querySelector("#pause"),
            elStop = document.querySelector("#stop");

        // VALUES
        let timeArr = [0, 0, 0],
            timeStr = '00:00:00',
            timer;

        // STATE
        let STATE = 'new'; // 'new', 'play', 'pause', 'stop'; 
        if (elStart != null) {
            elStart.addEventListener('click', function() {
                if (STATE === 'play') {
                    return false;
                }
                STATE = 'play';
                timer = setInterval(function() {
                    timeArr = getTime();
                    incrSeconds();
                    setTime();
                }, 1000);
                glowButton();
                document.querySelector('#stop').classList.add('active');
            });
        }
        if (elPause != null) {
            elPause.addEventListener('click', function() {
                STATE = 'pause';
                clearInterval(timer);
                glowButton();
            });
        }
        if (elStop != null) {
            elStop.addEventListener('click', function() {
                STATE = 'stop';
                clearInterval(timer);
                elTime.textContent = timeStr;
                glowButton();
            });
        }

        function getTime() {
            let str = elTime.textContent,
                frmtRgx = /(?:\d{2}:)?\d{2}:\d{2}:\d{2}/;

            str = frmtRgx.test(str) ? str : timeStr;

            let arr = str.split(':'),
                len = arr.length;
            for (let i = 0; i < len; i++) {
                arr[i] = parseInt(arr[i]);
            }

            return arr;
        }

        function setTime() {
            let tmpArr = pad(timeArr.slice(0));
            elTime.textContent = tmpArr.join(':');

            function pad(tmpArr) {
                for (let i = 0; i < tmpArr.length; i++) {
                    tmpArr[i] = String(tmpArr[i]);
                    if (tmpArr[i].length < 2) {
                        tmpArr[i] = '0' + tmpArr[i];
                    }
                }
                return tmpArr;
            }
        }

        function incrSeconds() {
            let secIndex = timeArr.length - 1;
            timeArr[secIndex]++;
            if (timeArr[secIndex] >= 60) {
                timeArr[secIndex] = 0;
                incrMinutes();
            }
        }

        function incrMinutes() {
            let minIndex = timeArr.length - 2;
            timeArr[minIndex]++;
            if (timeArr[minIndex] >= 60) {
                timeArr[minIndex] = 0;
                incrHours();
            }
        }

        function incrHours() {
            let hrIndex = timeArr.length - 3;
            timeArr[hrIndex]++;
            if (timeArr[hrIndex] >= 24) {
                timeArr[hrIndex] = 0;
                incrDay();
            }
        }

        function incrDay() {
            if (timeArr.length < 4) {
                timeArr.unshift(0);
            }
            timeArr[0]++;
        }

        function glowButton() {
            elStart.classList.remove('active');
            elPause.classList.remove('active');
            if (STATE === 'play') {
                elStart.classList.add('active');
            } else {
                if (STATE === 'pause') {
                    elPause.classList.add('active');
                }
            }
        }

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

        /*-------------------------------
            Dashboard ReservationIcon
        ------------------------------*/
        $(document).on('click', '.single-reservation-expandIcon', function(e) {
            var shopTitle = $(this).parent('.single-reservation');
            if (shopTitle.hasClass('open')) {
                shopTitle.removeClass('open');
                shopTitle.find('.single-reservation-inner').removeClass('open');
                shopTitle.find('.single-reservation-inner').slideUp(600);
            } else {
                shopTitle.addClass('open');
                shopTitle.children('.single-reservation-inner').slideDown(600);
                shopTitle.siblings('.single-reservation').find('.single-reservation-inner').slideUp(600);
                shopTitle.siblings('.single-reservation').removeClass('open');
            }
        });

        /* 
        ========================================
            Nice Select
        ========================================
        */
        $('.js_nice_select').niceSelect();

        /* 
        ========================================
           Work & Skill Active Remove Class
        ========================================
        */
        $(document).on('click', '.setup-work-child', function() {
            $(this).siblings().find('.setup-wrapper-work-single').removeClass('active');
            $(this).find('.setup-wrapper-work-single').addClass('active');
        });
        $(document).on('click', '.setup-wrapper-work-list-item', function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });

        /* 
        ========================================
            Click Popup Experience Class
        ========================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.experience-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.experience-click', function() {
            $('.experience-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        ========================================
            Click Popup Education Class
        ========================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.education-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.education-click', function() {
            $('.education-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        ========================================
            Click Popup Work Class
        ========================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.work-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.work-click', function() {
            $('.work-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        ===================================================
            Click Popup Package Name Edit Basic
        ====================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.basic-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-basic', function() {
            $('.basic-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Click Popup Package Name Edit Standard
        =====================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.standard-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-standard', function() {
            $('.standard-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Click Popup Package Name Edit Premium
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.premium-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-premium', function() {
            $('.premium-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Click Popup Main File Edit
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.mainFile-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-mainFile', function() {
            $('.mainFile-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Click Popup Package Name Edit Premium
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.screen-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-screen', function() {
            $('.screen-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Click Popup Package price
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.price-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-price', function() {
            $('.price-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Click Popup Package Price Edit
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.price-popup-charge, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-edit-price', function() {
            $('.price-popup-charge, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Click Popup Change Photos
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.change-photo-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-change', function() {
            $('.change-photo-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Click Popup Portfolio
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.change-portfolio-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-portfolio', function() {
            $('.change-portfolio-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Send Offer Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.sendOffer-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.sendOffer-click', function() {
            $('.sendOffer-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Add Note Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.addNote-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.addNote-click', function() {
            $('.addNote-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Bank Account Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.addBank-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.addBank-click', function() {
            $('.addBank-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Add Token Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.token-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.token-click', function() {
            $('.token-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Transaction Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.transaction-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.transaction-click', function() {
            $('.transaction-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Profile Settings Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.profile-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.profile-click', function() {
            $('.profile-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Ask Question Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.question-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.question-click', function() {
            $('.question-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Profile Details Price Edit Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.price-edit-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-price-edit', function() {
            $('.price-edit-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Profile Details Skills Add Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.skills-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-skills', function() {
            $('.skills-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Job Progress Milestone Add Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.milestone-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-milestone, .click-elipsis', function() {
            $('.milestone-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Job Details Interview Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.interview-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-interview', function() {
            $('.interview-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Contractor Milestone Refund Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.refund-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-refund', function() {
            $('.refund-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Payment Client Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.payment-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-payment', function() {
            $('.payment-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =====================================================
            Another Fund Click Popup
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.anotherFund-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.click-anotherFund', function() {
            $('.anotherFund-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        =============================================================
            Payment Client Click Popup Show Bonus Form
        =============================================================
        */
        if ($('.checked_bonus_input').is(':checked')) {
            $('.show_bonus_form').addClass('show');
        }
        $(document).on('click', '.checked_bonus_input', function() {
            if ($('.checked_bonus_input').is(':checked')) {
                $('.show_bonus_form').addClass('show');
            } else {
                $('.show_bonus_form').removeClass('show');
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


        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.add-project-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.add-project', function() {
            $('.add-project-popup, .popup-overlay').toggleClass('popup-active');
        });

        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.add-experience-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.add-experience', function() {
            $('.add-experience-popup, .popup-overlay').toggleClass('popup-active');
        });
        /* 
        ======================================================
           Profile Details Add Education Item
        ======================================================
        */
        $(document).on('click', '.popup-overlay, .popup-close', function() {
            $('.add-education-popup, .popup-overlay').removeClass('popup-active');
        });
        $(document).on('click', '.add-education', function() {
            $('.add-education-popup, .popup-overlay').toggleClass('popup-active');
        });

        /* 
        ======================================================
           Profile Details Add profile Item
        ======================================================
        */
        $(document).on('click', '.add-profile', function() {
            let addprofile = $(this).parent().parent().find('.profile-loop:last-child').clone();
            $(".add-profile-parent").append(addprofile);
        });

        /* 
        ======================================================
           Add milestone Chat Offer 
        ======================================================
        */
        $(document).on('click', '.add-offer', function() {
            let addoffer = $(this).parent().parent().parent().find('.add-offer-form:last-child').clone();
            $('.add-offer-parent').append(addoffer).children(".add-offer-form:last-child").addClass("offer-remove");
        });
        $(document).on('click', '.offer-remove-icon', function() {
            $(this).parent().parent().find(".offer-remove:last-child").remove();
        });

        /* 
        ======================================================
           Reaction List Click End Contract 
        ======================================================
        */
        $(document).on('click', '.reaction-list', function() {
            // first i need to get end-contract-feedback-single closets from this event
            let reaction = $(this).closest(".end-contract-feedback-single");
            // get class name from this 
            let className = reaction.attr("data-reaction-type");

            $(this).siblings().removeClass("active");
            $(this).addClass("active");

            $("." + className).addClass("active");
        });
        $(document).on('click', '.click-skip', function() {
            // first i need to get end-contract-feedback-single closets from this event
            let reaction = $(this).closest(".end-contract-feedback-single");
            // get class name from this 
            let className = reaction.attr("data-reaction-type");

            $("." + className).removeClass("active");
            $(this).parent().parent().find(".reaction-list.active").removeClass("active");
        });

        /* 
        ======================================================
           Add milestone Contractor 
        ======================================================
        */
        $(document).on('click', '.add-contract-milestone', function() {
            let addMilestone = $(this).parent().parent().find('.myJob-wrapper-single-milestone-item:first-child').clone().addClass("remove");
            $('.milestone-contractor-parent').append(addMilestone);
        });
        $(document).on('click', '.remove-milestone-contractor', function() {
            $(this).closest('.myJob-wrapper-single-milestone-item.remove').remove();
        });
        /* 
        ======================================================
           Contact Support Close icon
        ======================================================
        */
        $(document).on('click', '.question-answer-close', function() {
            $('.question-answer').remove();
        });

        /* 
        ======================================================
           Create Project Add rows Item
        ======================================================
        */
        $(document).on('click', '.remove-icon', function() {
            $(this).closest('.append-remove').remove();
        });

        $(document).on('click', '.add-rows', function() {
            let tableData = `
            <tr class="append-include append-remove">
                <th>
                    <div class="package-head-left">
                        <div class="package-head-left-flex">
                            <span class="package-head-left-title">Main file included</span>
                            <span class="package-head-left-edit click-mainFile"> <img src="assets/img/icons/edit_color.svg" alt=""> </span>
                        </div>
                        <div class="package-field">
                            <div class="package-field-select">
                                <select class="js_nice_select">
                                    <option value="1">Check boxes</option>
                                    <option value="2">Input field</option>
                                    <option value="3">Dropdown</option>
                                    <option value="4">Input radio</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </th>
                <td>
                    <div class="package-field">
                        <div class="package-field-checkbox">
                            <div class="checkbox-inline">
                                <input type="checkbox" class="check-input" checked>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="package-field">
                        <div class="package-field-checkbox">
                            <div class="checkbox-inline">
                                <input type="checkbox" class="check-input" checked>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="package-field">
                        <div class="package-field-icon remove-icon remove-mainFile">
                            <i class="fa-solid fa-minus"></i>
                        </div>
                        <div class="package-field-checkbox">
                            <div class="checkbox-inline">
                                <input type="checkbox" class="check-input" checked>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="append-screen append-remove remove-screens-row">
                <th>
                    <div class="package-head-left">
                        <div class="package-head-left-flex">
                            <span class="package-head-left-title">No. of screens</span>
                            <span class="package-head-left-edit click-screen"> <img src="assets/img/icons/edit_color.svg" alt=""> </span>
                        </div>
                        <div class="package-field">
                            <div class="package-field-select">
                                <select class="js_nice_select">
                                    <option value="1">Input field</option>
                                    <option value="2">Input field</option>
                                    <option value="3">Dropdown</option>
                                    <option value="4">Input radio</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </th>
                <td>
                    <div class="package-field">
                        <div class="package-field-input">
                            <form action="#">
                                <input type="number" class="form--control" value="5">
                            </form>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="package-field">
                        <div class="package-field-input">
                            <form action="#">
                                <input type="number" class="form--control" value="10">
                            </form>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="package-field">
                        <div class="package-field-icon remove-icon">
                            <i class="fa-solid fa-minus"></i>
                        </div>
                        <div class="package-field-input">
                            <form action="#">
                                <input type="number" class="form--control" value="15">
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            `;

            $('.add-rows-parent').append(tableData);

        });

        /* 
        =====================================================
            Choose account add remove class
        ======================================================
        */
        $(document).on('click', '.choose-account-single', function() {
            $(this).siblings().removeClass('selected');
            $(this).addClass('selected');
        });

        /*
        ========================================
           Code pen TagsInputs Js
        ========================================
        */
        if (document.querySelector('#tag-input1') != null) {
            var myTagInput = new TagsInputs({
                selector: 'tag-input1',
                duplicate: false,
                max: 100,
            });
            myTagInput.addData(['Javascript','adasda','asdas' ]);
        }

        /*
        ========================================
           Faq accordion
        ========================================
        */
        $('.faq-contents .faq-title').on('click', function(e) {
            var element = $(this).parent('.faq-item');
            if (element.hasClass('open')) {
                element.removeClass('open');
                element.find('.faq-panel').removeClass('open');
                element.find('.faq-panel').slideUp(300);
            } else {
                element.addClass('open');
                element.children('.faq-panel').slideDown(300);
                element.siblings('.faq-item').children('.faq-panel').slideUp(300);
                element.siblings('.faq-item').removeClass('open');
                element.siblings('.faq-item').find('.faq-title').removeClass('open');
                element.siblings('.faq-item').find('.faq-panel').slideUp(300);
            }
        });

        /*
        ========================================
           Bank Account Details Collapses
        ========================================
        */
        $('.bank-details-wrapper-item-arrow').on('click', function(e) {
            var elem = $(this).parent().parent('.bank-details-wrapper-item');
            if (elem.hasClass('open')) {
                elem.removeClass('open');
                elem.find('.bank-details-wrapper-item-inner').removeClass('open');
                elem.find('.bank-details-wrapper-item-inner').slideUp(300);
            } else {
                elem.addClass('open');
                elem.children('.bank-details-wrapper-item-inner').slideDown(300);
                elem.siblings('.bank-details-wrapper-item').children('.bank-details-wrapper-item-inner').slideUp(300);
                elem.siblings('.bank-details-wrapper-item').removeClass('open');
                elem.siblings('.bank-details-wrapper-item').find('.bank-details-wrapper-item-arrow').removeClass('open');
                elem.siblings('.bank-details-wrapper-item').find('.bank-details-wrapper-item-inner').slideUp(300);
            }
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
            Dashboard Responsive Sidebar
        ========================================
        */
        $(document).on('click', '.close-bars, .body-overlay', function() {
            $('.dashboard-close, .dashboard-left-content, .body-overlay').removeClass('active');
        });
        $(document).on('click', '.sidebar-icon', function() {
            $('.dashboard-close, .dashboard-left-content, .body-overlay').toggleClass('active');
        });
        /* 
        ========================================
            Profile Responsive Sidebar
        ========================================
        */
        $(document).on('click', '.profile-close, .responsive-overlay', function() {
            $('.profile-settings-menu-inner, .responsive-overlay').removeClass('active');
        });
        $(document).on('click', '.profile-bars', function() {
            $('.profile-settings-menu-inner, .responsive-overlay').toggleClass('active');
        });

        /*
        ========================================
            Blog Details Title open Close
        ========================================
        */
        $(document).on('click', '.blog-details-side-title .title', function(e) {
            var element = $(this).parent('.blog-details-side-title');
            if (element.hasClass('open')) {
                element.removeClass('open');
                element.find('.blog-details-side-inner').slideUp(300);
            } else {
                element.addClass('open');
                element.children('.blog-details-side-inner').slideDown(300);
                element.siblings('.blog-details-side-title').children('.blog-details-side-inner').slideUp(300);
                element.siblings('.blog-details-side-title').removeClass('open');
            }
        });

        /* 
        ========================================
            Account Setup Step Js
        ========================================
        */
        let Listings = document.querySelectorAll(".single-setup-request-list li");
        let sections = document.querySelectorAll(".setup-wrapper-contents");
        let nextButton = document.querySelector("#next");
        let prevButton = document.querySelector("#previous");
        let current = 0;

        const toggleListings = () => {
            Listings.forEach(function(e) {
                e.classList.remove('running');
            });
            Listings[current].classList.add("running");
            Listings[current].classList.remove("completed");
            if (current != 0) {
                Listings[current - 1].classList.add("completed");
            }
        }

        const toggleSections = () => {
            sections.forEach(function(section) {
                section.classList.remove('active');
            });
            sections[current].classList.add("active");
        }
        if (nextButton != null) {
            nextButton.addEventListener("click", function(e) {
                if (current < Listings.length - 1) {
                    current++
                }
                toggleListings();
                toggleSections();
            })
        }
        if (prevButton != null) {
            prevButton.addEventListener("click", function(e) {
                if (current > 0) {
                    current--
                }
                toggleListings();
                toggleSections();
            });
        }

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
            wow js init
        ========================================
        */
        new WOW().init();

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
            Select Country Code Js
        ========================================
        */
        // Vanilla Javascript
        if (document.querySelector('#phone') != null) {
            let TellInput = document.querySelector("#phone");
            window.intlTelInput(TellInput, {
                allowDropdown: true,
                excludeCountries: ["bn"],
            });
        }

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
            container
        ========================================
        */
        $(".footer-fluid").length && $(window).on("scroll", function() {
            ! function(t, a = 0) {
                var i = $(window).scrollTop(),
                    o = i + $(window).height(),
                    s = $(t).offset().top;
                return s + $(t).height() - parseInt(a) <= o && s >= i
            }(".footer-fluid", 100) ? $(".footer-fluid").removeClass("footer-bg-add"): $(".footer-fluid").addClass("footer-bg-add")
        })

        /*
        ========================================
            Global Slider Init
        ========================================
        */
        var globalSlickInit = $('.global-slick-init');
        if (globalSlickInit.length > 0) {
            // have to check slider item 
            $.each(globalSlickInit, function(index, value) {
                if ($(this).children('div').length > 1) {
                    // configure slider settings object
                    var sliderSettings = {};
                    var allData = $(this).data();
                    var infinite = typeof allData.infinite == 'undefined' ? false : allData.infinite;
                    var arrows = typeof allData.arrows == 'undefined' ? false : allData.arrows;
                    var autoplay = typeof allData.autoplay == 'undefined' ? false : allData.autoplay;
                    var focusOnSelect = typeof allData.focusonselect == 'undefined' ? false : allData.focusonselect;
                    var swipeToSlide = typeof allData.swipetoslide == 'undefined' ? false : allData.swipetoslide;
                    var slidesToShow = typeof allData.slidestoshow == 'undefined' ? 1 : allData.slidestoshow;
                    var slidesToScroll = typeof allData.slidestoscroll == 'undefined' ? 1 : allData.slidestoscroll;
                    var speed = typeof allData.speed == 'undefined' ? '500' : allData.speed;
                    var dots = typeof allData.dots == 'undefined' ? false : allData.dots;
                    var cssEase = typeof allData.cssease == 'undefined' ? 'linear' : allData.cssease;
                    var prevArrow = typeof allData.prevarrow == 'undefined' ? '' : allData.prevarrow;
                    var nextArrow = typeof allData.nextarrow == 'undefined' ? '' : allData.nextarrow;
                    var centerMode = typeof allData.centermode == 'undefined' ? false : allData.centermode;
                    var centerPadding = typeof allData.centerpadding == 'undefined' ? false : allData.centerpadding;
                    var rows = typeof allData.rows == 'undefined' ? 1 : parseInt(allData.rows);
                    var autoplay = typeof allData.autoplay == 'undefined' ? false : allData.autoplay;
                    var autoplaySpeed = typeof allData.autoplayspeed == 'undefined' ? 2000 : parseInt(allData.autoplayspeed);
                    var lazyLoad = typeof allData.lazyload == 'undefined' ? false : allData.lazyload; // have to remove it from settings object if it undefined
                    var appendDots = typeof allData.appenddots == 'undefined' ? false : allData.appenddots;
                    var appendArrows = typeof allData.appendarrows == 'undefined' ? false : allData.appendarrows;
                    var asNavFor = typeof allData.asnavfor == 'undefined' ? false : allData.asnavfor;
                    var verticalSwiping = typeof allData.verticalswiping == 'undefined' ? false : allData.verticalswiping;
                    var vertical = typeof allData.vertical == 'undefined' ? false : allData.vertical;
                    var fade = typeof allData.fade == 'undefined' ? false : allData.fade;
                    var rtl = typeof allData.rtl == 'undefined' ? false : allData.rtl;
                    var responsive = typeof $(this).data('responsive') == 'undefined' ? false : $(this).data('responsive');
                    //slider settings object setup
                    sliderSettings.infinite = infinite;
                    sliderSettings.arrows = arrows;
                    sliderSettings.autoplay = autoplay;
                    sliderSettings.focusOnSelect = focusOnSelect;
                    sliderSettings.swipeToSlide = swipeToSlide;
                    sliderSettings.slidesToShow = slidesToShow;
                    sliderSettings.slidesToScroll = slidesToScroll;
                    sliderSettings.speed = speed;
                    sliderSettings.dots = dots;
                    sliderSettings.cssEase = cssEase;
                    sliderSettings.prevArrow = prevArrow;
                    sliderSettings.nextArrow = nextArrow;
                    sliderSettings.rows = rows;
                    sliderSettings.autoplaySpeed = autoplaySpeed;
                    sliderSettings.autoplay = autoplay;
                    sliderSettings.verticalSwiping = verticalSwiping;
                    sliderSettings.vertical = vertical;
                    sliderSettings.rtl = rtl;
                    if (centerMode != false) {
                        sliderSettings.centerMode = centerMode;
                    }
                    if (centerPadding != false) {
                        sliderSettings.centerPadding = centerPadding;
                    }
                    if (lazyLoad != false) {
                        sliderSettings.lazyLoad = lazyLoad;
                    }
                    if (appendDots != false) {
                        sliderSettings.appendDots = appendDots;
                    }
                    if (appendArrows != false) {
                        sliderSettings.appendArrows = appendArrows;
                    }
                    if (asNavFor != false) {
                        sliderSettings.asNavFor = asNavFor;
                    }
                    if (fade != false) {
                        sliderSettings.fade = fade;
                    }
                    if (responsive != false) {
                        sliderSettings.responsive = responsive;
                    }
                    $(this).slick(sliderSettings);
                }
            });
        }

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

        /* 
        ========================================
            back to top
        ========================================
        */
        $(document).on('click', '.back-to-top', function() {
            $("html,body").animate({
                scrollTop: 0
            }, 700);
        });

    });
    /* 
    ========================================
        back to top
    ========================================
    */
    $(window).on('scroll', function() {
        //back to top show/hide
        var ScrollTop = $('.back-to-top');
        if ($(window).scrollTop() > 200) {
            ScrollTop.fadeIn(10);
        } else {
            ScrollTop.fadeOut(10);
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