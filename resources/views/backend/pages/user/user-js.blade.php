<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            $('.country_select2, .state_select2, .city_select2').select2({
                dropdownParent: $('#userDetailsEditModal')
            });

            //show user details in modal
            $(document).on('click','.user_details',function(){
                let user_id = $(this).data('user_id');
                let hourly_rate = $(this).data('hourly_rate');
                let user_type = $(this).data('type');
                let first_name = $(this).data('first_name');
                let last_name = $(this).data('last_name');
                let full_name = first_name + last_name;
                let username = $(this).data('username');
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                let country = $(this).data('country');
                let country_id = $(this).data('country_id');
                let state = $(this).data('state');
                let state_id = $(this).data('state_id');
                let city = $(this).data('city');
                let city_id = $(this).data('city_id');
                user_type = user_type == '1' ? 'Client' : 'Freelancer';

                $('#user_details .user_type').text(user_type);
                $('#user_details .hourly_rate').text(hourly_rate);
                $('#user_details .full_name').text(full_name);
                $('#user_details .username').text(username);
                $('#user_details .email').text(email);
                $('#user_details .phone').text(phone);
                country != '' ? $('#user_details .country').text(country) : $('#user_details .country').text('No country');
                state != '' ? $('#user_details .state').text(state) : $('#user_details .state').text('No state');
                city != '' ? $('#user_details .city').text(city) : $('#user_details .city').text('No city');

                //edit user info
                $('#edit_user_details #edit_user_id').val(user_id);
                $('#edit_user_details #edit_user_type').val(user_type);
                $('#edit_user_details #edit_first_name').val(first_name);
                $('#edit_user_details #edit_last_name').val(last_name);
                $('#edit_user_details #edit_username').val(username);
                $('#edit_user_details #edit_email').val(email);
                $('#edit_user_details #edit_phone').val(phone);
                $('#edit_user_details #edit_country').val(country_id).trigger('change');
                $('#edit_user_details #edit_state').val(state_id).trigger('change');
                $('#edit_user_details #edit_city').val(city_id).trigger('change');
                $('#edit_user_details #edit_hourly_rate').val(hourly_rate);
            });

            // user info edit
            $(document).on('click','.user_info_edit',function(e){
                e.preventDefault();
                $('#userDetailsModal').modal('hide');
                $('#userDetailsEditModal').modal('show');
            });

            // change country and get state
            $('#edit_country').on('change', function(e) {
                e.preventDefault();
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

            // change country and get state
            $('#edit_state').on('change', function(e) {
                e.preventDefault();
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

            //check username availability
            $(document).on('keyup','#edit_username',function(){
                let username = $(this).val();
                let usernameRegex = /^[a-zA-Z0-9]+$/;
                if(usernameRegex.test(username) && username != ''){
                    $.ajax({
                        url: "{{ route('user.name.availability') }}",
                        type: 'post',
                        data: {username: username},
                        success: function(res){
                            if(res.status == 'available'){
                                $("#user_name_availability").html("<span style='color: green;'>"+ res.msg +"</span>");
                            }else{
                                $("#user_name_availability").html("<span style='color: red;'>"+ res.msg +"</span>");
                            }
                        }
                    });
                }else{
                    $("#user_name_availability").html("<span style='color: red;'>{{ __('Enter valid username') }}</span>");
                }
            });

            //check email availability
            $(document).on('keyup','#edit_email',function(){
                let email = $(this).val();
                let emailRegex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
                if(emailRegex.test(email) && email != ''){
                    $.ajax({
                        url: "{{ route('user.email.availability') }}",
                        type: 'post',
                        data: {email: email},
                        success: function(res){
                            if(res.status == 'available'){
                                $("#email_availability").html("<span style='color: green;'>"+ res.msg +"</span>");
                            }else{
                                $("#email_availability").html("<span style='color: red;'>"+ res.msg +"</span>");
                            }
                        }
                    });
                }else{
                    $("#email_availability").html("<span style='color: red;'>{{ __('Enter valid email') }}</span>");
                }
            });

            //check phone availability
            $(document).on('keyup','#edit_phone',function(){
                let phone = $(this).val();
                let phoneRegex = /([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})/;
                if(phoneRegex.test(phone) && phone != ''){
                    $.ajax({
                        url: "{{ route('user.phone.number.availability') }}",
                        type: 'post',
                        data: {email: phone},
                        success: function(res){
                            if(res.status == 'available'){
                                $("#phone_availability").html("<span style='color: green;'>"+ res.msg +"</span>");
                            }else{
                                $("#phone_availability").html("<span style='color: red;'>"+ res.msg +"</span>");
                            }
                        }
                    });
                }else{
                    $("#phone_availability").html("<span style='color: red;'>{{ __('Enter valid phone number') }}</span>");
                }
            });

            //validation while update user info
            $(document).on('click','.update_user_info',function(){
                $('.email_send_message').removeClass("d-none");
                let first_name = $('#edit_user_details #edit_first_name').val();
                let last_name = $('#edit_user_details #edit_last_name').val();
                let username = $('#edit_user_details #edit_username').val();
                let email = $('#edit_user_details #edit_email').val();
                let phone = $('#edit_user_details #edit_phone').val();
                let country = $('#edit_user_details #edit_country').val();
                let state = $('#edit_user_details #edit_state').val();
                let city = $('#edit_user_details #edit_city').val();
                let hourly_rate = $('#edit_user_details #edit_hourly_rate').val();

                if(first_name == '' || last_name == '' || username == '' || email == '' || phone == '' || country == '' || state == '' || city == '' || hourly_rate == ''){
                    toastr_warning_js("{{__('Please fill all fields')}}")
                    return false;
                }
                $(".email_send_message").html("{{ __('Please wait while email is sending... !') }}").css("color", "green");
            });


            //get user id for update password
            $(document).on('click','.user_password_update_modal',function (){
                let user_id = $(this).data('user_id_for_change_password');
                $('#user_id_for_change_password').val(user_id);
            });

            //password match
            $(document).on('keyup','#confirm_password',function(){
                let password = $('#password').val();
                let confirm_password = $('#confirm_password').val();
                if (password != confirm_password) {
                    $("#new_password_match").html("{{ __('New and confirm new password does not match !') }}").css("color", "red");
                }else {
                    $("#new_password_match").html("{{ __('Password match !') }}").css("color", "green");
                }
            });

            //change user password
            $(document).on('click','.change_user_password',function(e){
                e.preventDefault();
                $('.email_send_message').removeClass("d-none");
                $("#new_password_match").html('');

                let user_id = $('#user_id_for_change_password').val();
                let password = $('#password').val();
                let confirm_password = $('#confirm_password').val();
                if(password == '' || confirm_password == ''){
                    toastr_warning_js("{{__('Please fill both password field')}}");
                    return false;
                }
                if (password != confirm_password) {
                    toastr_warning_js("{{ __('Password and confirm password does not match!') }}")
                    return false;
                }else{
                    $.ajax({
                        url:"{{ route('admin.user.password.change') }}",
                        method:'post',
                        data:{user_id:user_id,password:password,confirm_password:confirm_password},
                        beforeSend:function(){
                            $(".email_send_message").html("{{ __('Please wait while email is sending... !') }}").css("color", "green");
                        },
                        success:function(res){
                            if(res.status == 'ok'){
                                toastr_success_js("{{ __('Password successfully change') }}")
                                $('#userPasswordModal').modal('hide');
                                $('#userPasswordModalForm')[0].reset();
                                $('.table_activation').load(location.href + ' .table_activation');
                                $("#new_password_match").html('');
                                $(".email_send_message").html('');
                            }
                            if(res.status == 'not_match'){
                                toastr_warning_js("{{ __('Password and confirm password does not match!') }}")
                            }
                        }
                    });
                }
            });

            //user identity details
            $(document).on('click','.user_identity_details',function(e){
                e.preventDefault();
                let user_id = $(this).data('user_id');
                $.ajax({
                    url:"{{ route('admin.user.identity.details') }}",
                    method:'post',
                    data:{user_id:user_id},
                    success:function(res){
                        $('#user_identity_details').html(res);
                    }
                });
            })

            //user identity verify status
            $(document).on('click','.user_verify_status',function(e){
                e.preventDefault();
                let user_id = $('.compare-profile-and-identity #user_id_for_verified_status').val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "To change user verified status",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('admin.user.identity.verify.status') }}",
                            method:'post',
                            data:{user_id:user_id},
                            success:function(res){
                                toastr_success_js("{{ __('Status successfully updated') }}")
                                $('.table_activation').load(location.href + ' .table_activation');
                            }
                        });
                        Swal.fire(
                            'Updated!',
                            'Status successfully updated.',
                            'success'
                        )
                    }
                    $('#userIdentityModal').modal('hide');
                })
            })

            //user identity decline
            $(document).on('click','.user_identity_decline',function(e){
                e.preventDefault();
                let user_id = $('.compare-profile-and-identity #user_id_for_verified_status').val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "To decline the user identity verify request",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, decline it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('admin.user.identity.verify.decline') }}",
                            method:'post',
                            data:{user_id:user_id},
                            success:function(res){
                                toastr_warning_js("{{ __('Identity verify request successfully decline') }}")
                                $('.table_activation').load(location.href + ' .table_activation');
                            }
                        });
                        Swal.fire(
                            'Updated!',
                            'Request successfully decline.',
                            'success'
                        )
                    }
                    $('#userIdentityModal').modal('hide');
                })
            })

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });
            function countries(page){
                $.ajax({
                    url:"{{ route('admin.freelancer.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.freelancer.search') }}",
                    method:'GET',
                    data:{string_search:string_search},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            })

            // individual commission settings
            $(document).on('click','.individual_commission_settings_modal',function(){
                let user_id = $(this).data('user_id_for_individual_settings');
                let admin_commission_type = $(this).data('admin_commission_type');
                let admin_commission_charge = $(this).data('admin_commission_charge');

                $('#user_id_for_individual_settings').val(user_id);
                $('#admin_commission_type').val(admin_commission_type);
                $('#admin_commission_charge').val(admin_commission_charge);
            })

            $(document).on('click','.admin_individual_settings_for_user',function(){
                let admin_commission_type = $('#admin_commission_type').val();
                let admin_commission_charge =  $('#admin_commission_charge').val();

                if(admin_commission_type == '' || admin_commission_charge == ''){
                    toastr_warning_js("{{ __('Please fill both type and charge fields.') }}")
                    return false;
                }
            })

            //suspend user
            $(document).on('click','.suspend_user_account',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("To suspend this user.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, suspend user!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            //suspend user
            $(document).on('click','.unsuspend_user_account',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("To unsuspend this user.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, unsuspend user!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

        });
    }(jQuery));

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

</script>
