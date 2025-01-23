<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            $('.country_select2').select2({
                dropdownParent: $('.popup-fixed')
            });
            $('.state_select2').select2({
                dropdownParent: $('.popup-fixed')
            });
            $('.city_select2').select2({
                dropdownParent: $('.popup-fixed')
            });

            // profile photo change
            document.querySelector('#profile_photo').addEventListener('change', function() {
                $("#profilePhotoModal").modal('show');
                if (this.files && this.files[0]) {
                    let img = document.querySelector('.profile_photo_preview');
                    img.onload = () => {
                        URL.revokeObjectURL(img.src);  // no longer needed, free memory
                    }
                    img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                    document.querySelector(".profile_photo_upload").files = this.files;
                    // document.querySelector(".profile_photo_upload").value = this.value;
                }
            });

            //change profile photo
            $(document).on('submit','#profile_photo_change',function(e){
                e.preventDefault();
                $.ajax({
                    url:"{{ route('client.profile.photo.edit') }}",
                    method:'post',
                    data: new FormData(this),
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(){
                        $('#profilePhotoModal').modal('hide');
                        $('#display_client_profile_photo').load(location.href + " #display_client_profile_photo");
                        toastr_success_js("{{ __('Profile Photo Successfully Changed') }}");
                    },
                    error: function (err) {
                        let error = err.responseJSON;
                        $('.error_msg_container').html('');
                        $.each(error.errors, function (index, value) {
                            $('.error_msg_container').append('<p class="text-danger">'+value+'<p>');
                        });
                    }
                })
            });

            // change country and get state
            $(document).on('change','#country_id', function() {
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
            $('#state_id').on('change', function() {
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

            //update profile
            $(document).on('submit','#edit_profile_form',function(e){
                e.preventDefault();
                let first_name = $('#first_name').val();
                let last_name = $('#last_name').val();
                let email = $('#email').val();
                let country = $('#country_id').val();
                let state = $('#state_id').val();
                let city = $('#city_id').val();

                if(first_name == '' || last_name == '' || email == '' || country == ''){
                    toastr_warning_js('Except state and city all fields required !');
                    return false;
                }else{
                    $.ajax({
                        url: "{{ route('client.profile.edit') }}",
                        type: 'post',
                        data: {
                            first_name: first_name,
                            last_name:last_name,
                            email:email,
                            country:country,
                            state:state,
                            city:city,
                        },
                        success: function(res){
                            if(res.status == 'ok'){
                                $('.popup-fixed, .popup-overlay').removeClass('popup-active');
                                $('#display_client_profile_info').load(location.href + " #display_client_profile_info");
                                toastr_success_js("{{ __('Profile Info Successfully Updated') }}");
                            }
                        },
                        error: function (err) {
                            let error = err.responseJSON;
                            $('.error_msg_container').html('');
                            $.each(error.errors, function (index, value) {
                                $('.error_msg_container').append('<p class="text-danger">'+value+'<p>');
                            });
                        }
                    });
                }
            })

        });
    }(jQuery));


    // toastr warning
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
