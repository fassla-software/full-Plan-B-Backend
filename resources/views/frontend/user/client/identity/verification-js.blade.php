<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            $('.country_select2').select2();
            $('.state_select2').select2();
            $('.city_select2').select2();

            // change country and get state
            $('#country').on('change', function() {
                let country = $(this).val();
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.state.all') }}",
                    data: {
                        country: country
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            let all_options = "<option value=''>{{__('Select State')}}</option>";
                            let all_state = res.states;
                            $.each(all_state, function(index, value) {
                                all_options += "<option value='" + value.id +
                                    "'>" + value.state + "</option>";
                            });
                            $(".get_country_state").html(all_options);
                            $(".state_info").html('');
                            if(all_state.length <= 0){
                                $(".state_info").html('<span class="text-danger"> {{ __('No state found for selected country!') }} <span>');
                            }
                        }
                    }
                })
            })

            // change state and get city
            $('#state').on('change', function() {
                let state = $(this).val();
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.city.all') }}",
                    data: {
                        state: state
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            let all_options = "<option value=''>{{__('Select City')}}</option>";
                            let all_city = res.cities;
                            $.each(all_city, function(index, value) {
                                all_options += "<option value='" + value.id +
                                    "'>" + value.city + "</option>";
                            });
                            $(".get_state_city").html(all_options);

                            $(".city_info").html('');
                            if(all_city.length <= 0){
                                $(".city_info").html('<span class="text-danger"> {{ __('No city found for selected state!') }} <span>');
                            }
                        }
                    }
                })
            })

            //front image preview
            $('.front_image_preview').hide();
            document.querySelector('#front_image').addEventListener('change', function() {
                $('.front_image_preview').show();
                $('.front_image').hide();
                $(".identity-verifying-upload").find('span').first().hide()
                $(".identity-verifying-upload").find('p').first().text("{{__('Click to change photo')}}")
                if (this.files && this.files[0]) {
                    let img = document.querySelector('.front_image_preview');
                    img.onload = () => {
                        URL.revokeObjectURL(img.src);  // no longer needed, free memory
                    }

                    img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                    document.querySelector(".front_image_upload").files = this.files;
                    document.querySelector(".front_image_upload").value = this.value;
                }
            });

            //back image preview
            $('.back_image_preview').hide();
            document.querySelector('#back_image').addEventListener('change', function() {
                $('.back_image_preview').show();
                $('.back_image').hide();
                $(".identity-verifying-upload").find('span').last().hide()
                $(".identity-verifying-upload").find('p').last().text("{{__('Click to change photo')}}")

                if (this.files && this.files[0]) {
                    let img = document.querySelector('.back_image_preview');
                    img.onload = () => {
                        URL.revokeObjectURL(img.src);  // no longer needed, free memory
                    }
                    img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                    document.querySelector(".back_image_upload").files = this.files;
                    document.querySelector(".back_image_upload").value = this.value;
                }
            });

            //identity verification request
            $(document).on('submit','#submit_client_verify_info',function(e){
                e.preventDefault();
                let country = $('#country').val();
                let state = $('#state').val();
                let city = $('#city').val();
                let address = $('#address').val();
                let zipcode = $('#zipcode').val();
                let national_id_number = $('#national_id_number').val();
                let front_image = $('#front_image').val();
                let back_image = $('#back_image').val();
                if(country == '' || address == '' || national_id_number == '' || front_image == '' || back_image == ''){
                    toastr_warning_js("{{ __('Except city all fields required !') }}");
                    return false;
                }else{
                    $('.verification_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')
                    $.ajax({
                        url:"{{ route('client.identity.verification') }}",
                        method:'post',
                        data: new FormData(this),
                        cache: false,
                        contentType: false,
                        processData: false,
                        success:function(res){
                            $('.error_msg_container').html('');
                           if(res.status=='success'){
                               $('#display_client_identity_verification').load(location.href + " #display_client_identity_verification");
                               $('.front_image_preview').html('');
                               $('.front_image').hide();
                               $('.back_image_preview').hide();
                               $(".identity-verifying-upload").find('span').first().hide()
                               $(".identity-verifying-upload").find('p').first().text("{{__('Click to change photo')}}")
                               toastr_success_js("{{ __('Documents successfully submitted') }}");
                           }
                        },
                        error: function (err) {
                            let error = err.responseJSON;
                            $('.error_msg_container').html('');
                            $.each(error.errors, function (index, value) {
                                $('.error_msg_container').append('<p class="text-danger">'+value+'<p>');
                            });
                        }
                    })
                }
            });

        });
    }(jQuery));

    //toastr warning
    function toastr_warning_js(msg){
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

    //toastr success
    function toastr_success_js(msg){
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
</script>
