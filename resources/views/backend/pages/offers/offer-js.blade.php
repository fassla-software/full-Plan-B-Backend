<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            //show offer details in modal
            $(document).on('click', '.offer_details', function() {
                let offer_id = $(this).data('offer_id');
                let user_name = $(this).data('user_name');
                let user_email = $(this).data('user_email');
                let request_name = $(this).data('request_name');
                let subcategory = $(this).data('subcategory');
                let price = $(this).data('price');
                let per = $(this).data('per');
                let other_terms = $(this).data('other_terms');
                let offer_location = $(this).data('offer_location');
                let is_seen = $(this).data('is_seen');
                let ends_at = $(this).data('ends_at');
                let status = is_seen ? "Seen" : "Unseen";

                $('#offer_details .user_name').text(user_name);
                $('#offer_details .user_email').text(user_email);
                $('#offer_details .request_name').text(request_name);
                $('#offer_details .subcategory').text(subcategory);
                $('#offer_details .price').text(price + " EGP / " + per);
                $('#offer_details .offer_location').text(offer_location);
                $('#offer_details .other_terms').text(other_terms);
                $('#offer_details .ends_at').text(ends_at);
                $('#offer_details .is_seen').text(status);

                $('#edit_offer_details #edit_offer_id').val(offer_id);
                $('#edit_offer_details #edit_offer_price').val(price);
                $('#edit_offer_details #edit_per').val(per);
                $('#edit_offer_details #edit_current_location').val(offer_location);
                $('#edit_offer_details #edit_ends_at').val(ends_at);
                $('#edit_offer_details #edit_other_terms').val(other_terms);
            });

            $(document).on('click', '.offer_info_edit', function(e) {
                e.preventDefault();
                $('#offerDetailsModal').modal('hide');
                $('#offerDetailsEditModal').modal('show');
            });

            $(document).on('click', '.update_offer_info', function() {
                $('.email_send_message').removeClass("d-none");
                let price = $('#edit_offer_details #edit_offer_price').val();
                let per = $('#edit_offer_details #edit_per').val();
                let location = $('#edit_offer_details #edit_current_location').val();
                let ends_at = $('#edit_offer_details #edit_ends_at').val();
                let other_terms = $('#edit_offer_details #edit_other_terms').val();
                let country = $('#edit_offer_details #edit_country').val();
                let state = $('#edit_offer_details #edit_state').val();
                let city = $('#edit_offer_details #edit_city').val();
                let hourly_rate = $('#edit_offer_details #edit_hourly_rate').val();
                let commas = $('#edit_offer_details #edit_commas').val();
                let remaining_commas = $('#edit_offer_details #edit_remaining_commas').val();

                if (price == '' || per == '' || location == '' || ends_at == '' || other_terms ==
                    '') {
                    toastr_warning_js("{{ __('Please fill all fields') }}")
                    return false;
                }
                $(".email_send_message").html(
                    "{{ __('Please wait while email is sending... !') }}").css("color",
                    "green");
            });

            // pagination
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });

            function countries(page) {
                $.ajax({
                    url: "{{ route('admin.offers.paginate.data') . '?page=' }}" + page,
                    success: function(res) {
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup', '#string_search', function() {
                let string_search = $(this).val();
                $.ajax({
                    url: "{{ route('admin.offers.search') }}",
                    method: 'GET',
                    data: {
                        string_search: string_search
                    },
                    success: function(res) {
                        if (res.status == 'nothing') {
                            $('.search_result').html(
                                '<h3 class="text-center text-danger">' +
                                "{{ __('Nothing Found') }}" + '</h3>');
                        } else {
                            $('.search_result').html(res);
                        }
                    }
                });
            })

        });
    }(jQuery));

    //toastr success
    function toastr_success_js(msg) {
        Command: toastr["success"](msg, "Success !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }

    //toastr warning
    function toastr_warning_js(msg) {
        Command: toastr["warning"](msg, "Warning !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }
</script>
