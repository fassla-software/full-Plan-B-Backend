
<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            //available for work or not
            $(document).on('click','#check_work_availability',function(e){
                e.preventDefault();
                let user_id = $(this).data('user_id');
                let check_work_availability = $(this).data('check_work_availability');
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('To change work availability status !') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, change it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('freelancer.work.availability.status') }}",
                            method:'post',
                            data:{user_id:user_id,check_work_availability:check_work_availability},
                            success:function(res){
                                if(res.status == 'success'){
                                    $('.display_work_availability').load(location.href + ' .display_work_availability');
                                    toastr_success_js("{{ __('Work Availability Status Successfully Changed') }}")
                                }
                            }
                        })
                    }
                })
            })

            $('#country_id').select2();
            $('#state_id').select2();

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

            // professional title length check
            $('#professional_title_char_length_check').hide()
            $('#professional_title').on('keydown keyup change', function(){
                let title_min_length = 10;
                let title_max_length = 60;
                let professional_title_length = $('#professional_title').val().length;
                $('#professional_title_char_length_check').show();

                if(professional_title_length < title_min_length){
                    $('#professional_title_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum') }} '+ title_min_length +' {{ __('required') }}.</p>');
                }else if(professional_title_length > title_max_length){
                    $('#professional_title_char_length_check').html('<p class="text text-danger">{{ __('Length is not valid, maximum') }} '+ title_max_length +' {{ __('allowed') }}.</p>');
                }else{
                    $('#professional_title_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                }
            });

            // professional description length check
            $('#professional_description_char_length_check').hide()
            $('#professional_description').on('keydown keyup change', function(){
                let description_min_length = 50;
                let description_max_length = 150;
                let professional_description_length = $('#professional_description').val().length;
                $('#professional_description_char_length_check').show();

                if(professional_description_length < description_min_length){
                    $('#professional_description_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum') }} '+ description_min_length +' {{ __('required') }}.</p>');
                }else if(professional_description_length > description_max_length){
                    $('#professional_description_char_length_check').html('<p class="text text-danger">{{ __('Length is not valid, maximum') }} '+ description_max_length +' {{ __('allowed') }}.</p>');
                }else{
                    $('#professional_description_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                }
            });

            //update profile
            $(document).on('click','.edit_public_profile_info',function(e){
                e.preventDefault();
                let first_name = $('#first_name').val();
                let last_name = $('#last_name').val();
                let title = $('#professional_title').val();
                let description = $('#professional_description').val();
                let country_id = $('#country_id').val();
                let state_id = $('#state_id').val();

                if(first_name == '' || last_name =='' || title == '' || description == '' || country_id==''){
                    toastr_warning_js("{{ __('Please fill all fields.') }}")
                    return false;
                }else{
                    $.ajax({
                        url:"{{ route('freelancer.profile.details.update') }}",
                        method:'post',
                        data:{first_name:first_name,last_name:last_name,title:title,description:description,country_id:country_id,state_id:state_id},
                        success:function(res){
                            if(res.status=='success'){
                                $('#profileModal').modal('hide');
                                $('.display_profile_info').load(location.href + ' .display_profile_info');
                                toastr_success_js("{{ __('Profile Info Successfully Updated') }}")
                            }
                        },
                        error:function(err){
                            let error = err.responseJSON;
                            $('.error_msg_container').html('');
                            $.each(error.errors, function (index, value) {
                                $('.error_msg_container').append('<p class="text-danger">'+value+'<p>');
                            });
                        }
                    });
                }
            });

            //update hourly rate
            $(document).on('click','.edit_public_hourly_rate',function(e){
                e.preventDefault();
                let hourly_rate = $('#hourly_rate').val();

                if(hourly_rate == ''){
                    toastr_warning_js("{{ __('price is required.') }}")
                    return false;
                }else{
                    $.ajax({
                        url:"{{ route('freelancer.profile.details.hourly.rate.update') }}",
                        method:'post',
                        data:{hourly_rate:hourly_rate},
                        success:function(res){
                            if(res.status=='success'){
                                $('#priceModal').modal('hide');
                                $('.display_hourly_rate').load(location.href + ' .display_hourly_rate');
                                toastr_success_js("{{ __('Price Successfully Updated') }}")
                            }
                        },
                        error:function(err){
                            let error = err.responseJSON;
                            $('.error_msg_container').html('');
                            $.each(error.errors, function (index, value) {
                                $('.error_msg_container').append('<p class="text-danger">'+value+'<p>');
                            });
                        }
                    });
                }
            });

            //Portfolio add Popup
            $(document).on('click', '.popup-overlay, .popup-close', function() {
                $('.portfolioadd-popup, .popup-overlay').removeClass('popup-active');
            });
            $(document).on('click', '.add-portfolio-click', function() {
                $('.portfolioadd-popup, .popup-overlay').toggleClass('popup-active');
            });

            //portfolio photo upload
            document.querySelector('#upload_portfolio_photo').addEventListener('change', function() {
                $("#add_portfolio_form").find('.change_image_text').text("{{__('Click to change photo')}}")
                if (this.files && this.files[0]) {
                    let img = document.querySelector('.portfolio_photo_preview');
                    img.onload = () =>{
                        URL.revokeObjectURL(img.src);  // no longer needed, free memory
                    }
                    img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                    document.querySelector(".portfolio_photo_preview").files = this.files;
                }
            });

            // portfolio title length check
            $('#portfolio_title_char_length_check').hide()
            $('#portfolio_title').on('keydown keyup change', function(){
                let title_min_length = 10;
                let title_max_length = 60;
                let portfolio_title_length = $('#portfolio_title').val().length;
                $('#portfolio_title_char_length_check').show();

                if(portfolio_title_length < title_min_length){
                    $('#portfolio_title_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum') }} '+ title_min_length +' {{ __('required') }}.</p>');
                }else if(portfolio_title_length > title_max_length){
                    $('#portfolio_title_char_length_check').html('<p class="text text-danger">{{ __('Length is not valid, maximum') }} '+ title_max_length +' {{ __('allowed') }}.</p>');
                }else{
                    $('#portfolio_title_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                }
            });

            // portfolio description length check
            $('#portfolio_description_char_length_check').hide()
            $('#portfolio_description').on('keydown keyup change', function(){
                let description_min_length = 50;
                let description_max_length = 150;
                let portfolio_description_length = $('#portfolio_description').val().length;
                $('#portfolio_description_char_length_check').show();

                if(portfolio_description_length < description_min_length){
                    $('#portfolio_description_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum') }} '+ description_min_length +' {{ __('required') }}.</p>');
                }else if(portfolio_description_length > description_max_length){
                    $('#portfolio_description_char_length_check').html('<p class="text text-danger">{{ __('Length is not valid, maximum') }} '+ description_max_length +' {{ __('allowed') }}.</p>');
                }else{
                    $('#portfolio_description_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                }
            });

            //add portfolio
            $(document).on('submit','#add_portfolio_form',function(e){
                e.preventDefault();
                let image = $('#upload_portfolio_photo').val();
                let title = $('#portfolio_title').val();
                let description = $('#portfolio_description').val();
                let formData = new FormData(this);

                if(image == '' || title == '' || description == ''){
                    toastr_warning_js("{{ __('Image, title and description fields are require') }}")
                    return false;
                }else{
                    $.ajax({
                        url:"{{ route('freelancer.portfolio.add') }}",
                        method:'post',
                        data:formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        success:function(res){
                            if(res.status=='success'){
                                $('.portfolioadd-popup, .popup-overlay').removeClass('popup-active');
                                $('.portfolio_details_display').load(location.href + ' .portfolio_details_display');
                                toastr_success_js("{{ __('Portfolio Successfully Added') }}")
                            }
                        },
                        error:function(err){
                            let error = err.responseJSON;
                            $('.error_msg_container').html('');
                            $.each(error.errors, function (index, value) {
                                $('.error_msg_container').append('<p class="text-danger">'+value+'<p>');
                            });
                        }
                    });
                }
            });

            //Open and close Popup for display Portfolio details
            $(document).on('click', '.popup-overlay, .popup-close', function() {
                $('.change-portfolio-popup, .portfolio_edit_popup, .popup-overlay').removeClass('popup-active');
            });
            $(document).on('click', '.click-portfolio', function() {
                $('.change-portfolio-popup, .popup-overlay').toggleClass('popup-active');
            });

            // view portfolio details
            $(document).on('click','.view_portfolio_details',function(e){
                let portfolio_id = $(this).data('id');
                $.ajax({
                    url:"{{ route('freelancer.portfolio.details') }}",
                    method:'post',
                    data:{id:portfolio_id},
                    success:function(res){
                      $('.change-portfolio-popup .popup-contents').html(res);
                    }
                });
            });

            //portfolio photo change
            document.querySelector('#change_portfolio_photo').addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    let img = document.querySelector('.edit_portfolio_photo_preview');
                    img.onload = () =>{
                        URL.revokeObjectURL(img.src);  // no longer needed, free memory
                    }
                    img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                    document.querySelector(".edit_portfolio_photo_preview").files = this.files;
                }
            });

            //edit portfolio popup
            $(document).on('click','.edit_portfolio_details',function(){
                let portfolio_id = $(this).data('id');
                let portfolio_title = $(this).data('title');
                let portfolio_description = $(this).data('description');
                let portfolio_image_name = $(this).data('image');
                let portfolio_image = "../assets/uploads/portfolio/" + portfolio_image_name;

                $('#edit_portfolio_title_char_length_check').html('');
                $('#edit_portfolio_description_char_length_check').html('');
                $('.error_msg_container').html('');

                $('#edit_portfolio_id').val(portfolio_id);
                $('#portfolio_target_img').attr('src', portfolio_image);
                $('#edit_portfolio_title').val(portfolio_title);
                $('#edit_portfolio_description').val(portfolio_description);
                $('.change-portfolio-popup, .popup-overlay').removeClass('popup-active');
                $('.portfolio_edit_popup, .popup-overlay').toggleClass('popup-active');
            });

            // edit portfolio title length check
            $('#edit_portfolio_title_char_length_check').hide()
            $('#edit_portfolio_title').on('keydown keyup change', function(){
                let title_min_length = 10;
                let title_max_length = 60;
                let portfolio_title_length = $('#edit_portfolio_title').val().length;
                $('#edit_portfolio_title_char_length_check').show();

                if(portfolio_title_length < title_min_length){
                    $('#edit_portfolio_title_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum') }} '+ title_min_length +' {{ __('required') }}.</p>');
                }else if(portfolio_title_length > title_max_length){
                    $('#edit_portfolio_title_char_length_check').html('<p class="text text-danger">{{ __('Length is not valid, maximum') }} '+ title_max_length +' {{ __('allowed') }}.</p>');
                }else{
                    $('#edit_portfolio_title_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                }
            });

            // edit portfolio description length check
            $('#edit_portfolio_description_char_length_check').hide()
            $('#edit_portfolio_description').on('keydown keyup change', function(){
                let description_min_length = 50;
                let description_max_length = 150;
                let portfolio_description_length = $('#edit_portfolio_description').val().length;
                $('#edit_portfolio_description_char_length_check').show();

                if(portfolio_description_length < description_min_length){
                    $('#edit_portfolio_description_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum') }} '+ description_min_length +' {{ __('required') }}.</p>');
                }else if(portfolio_description_length > description_max_length){
                    $('#edit_portfolio_description_char_length_check').html('<p class="text text-danger">{{ __('Length is not valid, maximum') }} '+ description_max_length +' {{ __('allowed') }}.</p>');
                }else{
                    $('#edit_portfolio_description_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                }
            });

            //update portfolio
            $(document).on('submit','#edit_portfolio_form',function(e){
                e.preventDefault();
                let image = $('#edit_upload_portfolio_photo').val();
                let title = $('#edit_portfolio_title').val();
                let description = $('#edit_portfolio_description').val();
                let formData = new FormData(this);

                if(image == '' || title == '' || description == ''){
                    toastr_warning_js("{{ __('Image, title and description fields are require') }}")
                    return false;
                }else {
                    $.ajax({
                        url: "{{ route('freelancer.portfolio.edit') }}",
                        method: 'post',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (res) {
                            if(res.status=='success'){
                                $('.portfolio_edit_popup, .popup-overlay').removeClass('popup-active');
                                $('.portfolio_details_display').load(location.href + ' .portfolio_details_display');
                                toastr_success_js("{{ __('Portfolio Successfully Updated') }}")
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
            });

            //delete portfolio
            $(document).on('click','.delete_portfolio',function(e){
                e.preventDefault();
                let portfolio_id = $(this).data('id');
                $('.change-portfolio-popup, .popup-overlay').removeClass('popup-active');
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('To delete this portfolio !') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, delete it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('freelancer.portfolio.delete') }}",
                            method:'post',
                            data:{id:portfolio_id},
                            success:function(res){
                                if(res.status == 'success'){
                                    $('.portfolio_details_display').load(location.href + ' .portfolio_details_display');
                                    toastr_delete_js("{{ __('Portfolio Successfully Deleted') }}")
                                }
                            }
                        })
                    }
                })
            })

            //delete project
            $(document).on('click','.delete_project',function(e){
                e.preventDefault();
                let project_id = $(this).data('project-id');
                $('.change-portfolio-popup, .popup-overlay').removeClass('popup-active');
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('To delete this project !') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, delete it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('freelancer.project.delete') }}",
                            method:'post',
                            data:{project_id:project_id},
                            success:function(res){
                                if(res.status == 'success'){
                                    $('.project_wrapper_area').load(location.href + ' .project_wrapper_area');
                                    toastr_delete_js("{{ __('Project Successfully Deleted') }}")
                                }
                            }
                        })
                    }
                })
            })


            //choose skill
            const myTagInput = new TagsInputs({
                selector: 'skill_input',
                duplicate: false,
                max: 30,
            });

            @php
                $array_skill = explode(",",$skills);
                $array_length =  count($array_skill);
            @endphp

            @for($i = 0; $i<=($array_length-1); $i ++ )
            myTagInput.addData(["{{$array_skill[$i]}}"]);
            @endfor

            $(document).on('click','.choose_skill',function (){
                let skill = $(this).text();
                myTagInput.addData([skill]);
            });

            //update skill
            $('.edit_skill_wrapper').hide();
            $(document).on('click','.display_edit_skill_wrapper',function(){
                $('.edit_skill_wrapper').show();
                $('.freelancer_skill_list').hide();
            });
            $(document).on('click','.update_freelancer_skill',function(){
                let skill = $('#skill_input').val();
                $.ajax({
                    url: "{{ route('freelancer.account.skill.add') }}",
                    type: 'post',
                    data: {skill: skill},
                    success: function(res){
                        if(res.status == 'ok'){
                            toastr_success_js("{{ __('Skill Successfully Updated') }}");
                            $('.edit_skill_wrapper').hide();
                            $('.freelancer_skill_list').show();
                            $('.freelancer_skill_list').load(location.href + ' .freelancer_skill_list');
                        }
                    }
                });
            });

            // todo add education
            $(document).on('click','.add_education',function(){
                let institution = $('#institution').val();
                let degree = $('#degree').val();
                let subject = $('#subject').val();
                let start_date = $('#start_date_edu').val();
                let end_date = $('#end_date_edu').val();
                if(institution == '' || degree == '' || subject == '' || start_date == '' || end_date == ''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }else{
                    $.ajax({
                        url: "{{ route('freelancer.account.education.add') }}",
                        type: 'post',
                        data: {
                            institution: institution,
                            degree:degree,
                            subject:subject,
                            start_date:start_date,
                            end_date:end_date,
                        },
                        success: function(res){
                            if(res.status == 'ok'){
                                $('.popup-fixed, .popup-overlay').removeClass('popup-active');
                                $('#display_user_education_data').load(location.href + " #display_user_education_data");
                                $(addEducationForm)[0].reset();
                                toastr_success_js("{{ __('Education Successfully Added') }}");
                            }
                        }
                    });
                }
            });

            // edit education
            $(document).on('click','.edit_single_education',function(){
                let id = $(this).data('id');
                let institution = $(this).data('institution');
                let subject = $(this).data('subject');
                let degree = $(this).data('degree');
                let start_date = $(this).data('start_date');
                let end_date = $(this).data('end_date');

                $('#edit_id').val(id);
                $('#edit_institution').val(institution);
                $('#edit_subject').val(subject);
                $('#edit_degree').val(degree);
                $('#edit_start_date_edu').val(start_date);
                $('#edit_start_date_edu').parent().find('.date-picker').val(start_date);
                $('#edit_end_date_edu').val(end_date);
                $('#edit_end_date_edu').parent().find('.date-picker').val(end_date);
            });

            // update education
            $(document).on('click','.update_single_education',function(){
                let id = $('#edit_id').val();
                let institution = $('#edit_institution').val();
                let subject = $('#edit_subject').val();
                let degree = $('#edit_degree').val();
                let start_date = $('#edit_start_date_edu').val();
                let end_date = $('#edit_end_date_edu').val();
                if(institution == '' || subject == '' || degree == '' || start_date == '' || end_date == ''){
                    toastr_warning_js('Please fill all fields !');
                    return false;
                }else{
                    $.ajax({
                        url: "{{ route('freelancer.account.education.update') }}",
                        type: 'post',
                        data: {
                            id: id,
                            institution: institution,
                            subject:subject,
                            degree:degree,
                            start_date:start_date,
                            end_date:end_date,
                        },
                        success: function(res){
                            if(res.status == 'ok'){
                                toastr_success_js("{{ __('Education Successfully Updated') }}");
                                $('.popup-fixed, .popup-overlay').removeClass('popup-active');
                                $('#display_user_education_data').load(location.href + " #display_user_education_data");
                                $(addExperienceForm)[0].reset();
                            }
                        }
                    });
                }
            });

            //delete education
            $(document).on('click','.delete_education',function(e){
                e.preventDefault();
                let education_id = $(this).data('id');
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('To delete this education !') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, delete it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('freelancer.education.delete') }}",
                            method:'post',
                            data:{id:education_id},
                            success:function(res){
                                if(res.status == 'success'){
                                    $('#display_user_education_data').load(location.href + ' #display_user_education_data');
                                    toastr_delete_js("{{ __('Education Successfully Deleted') }}")
                                }
                            }
                        })
                    }
                })
            })

            // change country and get state
            $(document).on('change', '#country_id , #edit_country_id', function() {
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

            // add experience
            $(document).on('click','.add_experience',function(){
                let experience_title = $('#experience_title').val();
                let organization = $('#organization').val();
                let address = $('#address').val();
                let short_description = $('#short_description').val();
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();

                if(experience_title == '' || organization == '' || address == '' || short_description == '' || start_date == ''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }
                if(end_date != '' && start_date >end_date){
                    toastr_warning_js("{{ __('Start date must not greater than end date !') }}");
                    return false;
                }else{
                    $.ajax({
                        url: "{{ route('freelancer.account.experience.add') }}",
                        type: 'post',
                        data: {
                            experience_title: experience_title,
                            organization:organization,
                            address:address,
                            short_description:short_description,
                            country_id:1,
                            state_id:1,
                            start_date:start_date,
                            end_date:end_date,
                        },
                        success: function(res){
                            if(res.status == 'ok'){
                                $('.popup-fixed, .popup-overlay').removeClass('popup-active');
                                $('#display_user_experience_data').load(location.href + " #display_user_experience_data");
                                $(addExperienceForm)[0].reset();
                                toastr_success_js("{{ __('Experience Successfully Added') }}");
                            }
                        }
                    });
                }
            });

            // edit experience
            $(document).on('click','.edit_single_experience',function(){
                let id = $(this).data('id');
                let title = $(this).data('title');
                let organization = $(this).data('organization');
                let address = $(this).data('address');
                let short_description = $(this).data('short_description');
                let start_date = $(this).data('start_date');
                let end_date = $(this).data('end_date');

                $('#edit_id').val(id);
                $('#edit_experience_title').val(title);
                $('#edit_organization').val(organization);
                $('#edit_address').val(address);
                $('#edit_short_description').val(short_description);
                $('#edit_start_date').val(start_date);
                $('#edit_start_date').parent().find('.date-picker').val(start_date);
                $('#edit_end_date').parent().find('.date-picker').val(end_date);
                $('#edit_end_date').val(end_date);
            });

            // update experience
            $(document).on('click','.update_single_experience',function(){
                let id = $('#edit_id').val();
                let experience_title = $('#edit_experience_title').val();
                let organization = $('#edit_organization').val();
                let address = $('#edit_address').val();
                let short_description = $('#edit_short_description').val();
                let start_date = $('#edit_start_date').val();
                let end_date = $('#edit_end_date').val();
                if(experience_title == '' || organization == '' || address == '' || short_description == '' || start_date == ''){
                    toastr_warning_js('Please fill all fields !');
                    return false;
                }
                if(end_date != '' && start_date >end_date){
                    toastr_warning_js("{{ __('Start date must not greater than end date !') }}");
                    return false;
                }else{
                    $.ajax({
                        url: "{{ route('freelancer.account.experience.update') }}",
                        type: 'post',
                        data: {
                            id: id,
                            experience_title: experience_title,
                            organization:organization,
                            address:address,
                            short_description:short_description,
                            country_id:1,
                            state_id:1,
                            start_date:start_date,
                            end_date:end_date,
                        },
                        success: function(res){
                            if(res.status == 'ok'){
                                $('.popup-fixed, .popup-overlay').removeClass('popup-active');
                                $('#display_user_experience_data').load(location.href + " #display_user_experience_data");
                                $(addExperienceForm)[0].reset();
                                toastr_success_js("{{ __('Experience Successfully Updated') }}");
                            }
                        }
                    });
                }
            });

            //delete experience
            $(document).on('click','.delete_experience',function(e){
                e.preventDefault();
                let education_id = $(this).data('id');
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('To delete this experience !') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, delete it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('freelancer.experience.delete') }}",
                            method:'post',
                            data:{id:education_id},
                            success:function(res){
                                if(res.status == 'success'){
                                    $('#display_user_experience_data').load(location.href + ' #display_user_experience_data');
                                    toastr_delete_js("{{ __('Experience Successfully Deleted') }}")
                                }
                            }
                        })
                    }
                })
            })

            //available for order or not
            $(document).on('click','#available_for_order_or_not',function(e){
                e.preventDefault();
                let project_id = $(this).data('id');
                let project_on_off = $(this).data('project_on_off');
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('To change availability status !') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, change it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('freelancer.availability.status') }}",
                            method:'post',
                            data:{id:project_id,project_on_off:project_on_off},
                            success:function(res){
                                if(res.status == 'success'){
                                    $('.display_availability_for_order_or_not_'+project_id).load(location.href + ' .display_availability_for_order_or_not_'+project_id);
                                    toastr_success_js("{{ __('Availability Status Successfully Changed') }}")
                                }
                            }
                        })
                    }
                })
            })

            // view as a client
            $(document).on('click','.view_as_a_client',function(){
                $('.change_client_view').html('<a href="javascript:void(0)" class="btn-profile btn-outline-gray view_original"> {{ __('Exit View as Client') }} </a>')
                $('.price_edit_show_hide').hide();
                $('.edit_info_show_hide').hide();
                $('.create_project_show_hide').hide();
                $('.order_availability_show_hide').hide();
                $('.add_experience_show_hide').hide();
                $('.edit_experience_show_hide').hide();
                $('.delete_experience_show_hide').hide();
                $('.add_education_show_hide').hide();
                $('.edit_education_show_hide').hide();
                $('.delete_education_show_hide').hide();
                $('.edit_skill_show_hide').hide();
                $('.add_portfolio_show_hide').hide();
                $('.profile-wrapper-item-bottom.profile-border-top').addClass("d-none")
            })

            $(document).on('click','.view_original',function(){
                $('.change_client_view').html('<a href="javascript:void(0)" class="btn-profile btn-outline-gray view_as_a_client"> {{ __('View as Client') }} </a>')
                $('.price_edit_show_hide').show();
                $('.edit_info_show_hide').show();
                $('.create_project_show_hide').show();
                $('.order_availability_show_hide').show();
                $('.add_experience_show_hide').show();
                $('.edit_experience_show_hide').show();
                $('.delete_experience_show_hide').show();
                $('.add_education_show_hide').show();
                $('.edit_education_show_hide').show();
                $('.delete_education_show_hide').show();
                $('.edit_skill_show_hide').show();
                $('.add_portfolio_show_hide').show();
                $('.profile-wrapper-item-bottom.profile-border-top').removeClass("d-none")
            })

            //view project reject details
            $(document).on('click','.view_project_reject_reason_details',function(){
                let description = $(this).data('project-reject-description')
                $('.project_reject_reason_description').text(description);
            })

            // promotion plugin js start
            $(document).on('change','#get_package_budget',function (){
                let package_budget = $(this).find(':selected').attr('data-budget')
                $('#set_package_budget').val(package_budget);
            });

            //promote project
            $(document).on('click', '#get_package_budget, .wallet_selected_payment_gateway , .payment_getway_image ul li',function() {
                let site_default_currency_symbol = '{{ site_currency_symbol() }}';
                let gateway = $('#order_from_user_wallet').val();
                let package_budget = $('#set_package_budget').val();

                <?php
                $transaction_type = get_static_option('promote_transaction_fee_type') ?? '';
                $transaction_charge = get_static_option('promote_transaction_fee_charge') ?? 0;
                ?>

                if(gateway == 'wallet' || gateway == 'manual_payment'){
                    $('.show_hide_transaction_section').addClass('d-none');
                    let wallet_balance = {{ Auth::check() ? (Auth::user()->user_wallet?->balance ?? 0) : 0 }};
                    if(package_budget > wallet_balance){
                        $('.display_wallet_shortage_balance').html('<span class="text-danger">{{__('Wallet Balance Shortage:')}}'+ site_default_currency_symbol + (package_budget-wallet_balance) +'<a class="btn btn-primary btn-sm ml-2" href="{{ route('freelancer.wallet.history') }}" target="_blank">{{ __('Deposit Wallet') }}</a></span>');
                    }
                }else{
                    if("{{ $transaction_charge > 0}}"){
                        let transaction_amount = 0;
                        $('.show_hide_transaction_section').removeClass('d-none');
                        let transaction_type = "{{ $transaction_type }}";
                        let transaction_charge = parseFloat("{{ $transaction_charge }}");
                        transaction_amount = transaction_type == 'fixed' ? transaction_charge : (package_budget*transaction_charge/100);
                        $('.currency_symbol').text(site_default_currency_symbol);
                        $('.transaction_fee_amount').text(transaction_amount.toFixed(2));
                        $('#transaction_fee').val(transaction_amount)
                    }
                }
            });

            $(document).on('click','.open_project_promote_modal',function(){
                $('#set_project_id_for_promote').val($(this).data('project-id'))

                if($('#set_project_id_for_promote').val() == 0){
                    $('.heading_title_for_promotion_modal').text("{{ __('Promote Profile') }}")
                    $('.warning_for_promotion_modal').text("{{ __("Notice: Days refers to the number of days a freelancer profile will be displayed in the talent page promotional area after he buy a package.") }}")
                }else{
                    $('.heading_title_for_promotion_modal').text("{{ __('Promote Project') }}")
                    $('.warning_for_promotion_modal').text("{{ __("Notice: Days refers to the number of days a freelancer project will be displayed in the project promotional area after he buy a package.") }}")

                }
            })

            $(document).on('click','.confirm_promote_project',function(){
                let package_budget = $('#set_package_budget').val();
                let payment_gateway = $('#order_from_user_wallet').val();
                let manual_payment_image = $('input[name="manual_payment_image"]').val();

                if(package_budget == ''){
                    toastr_warning_js("{{ __('Please choose package plan') }}")
                    return false;
                }
                if(payment_gateway == 'manual_payment'){
                    if(manual_payment_image == ''){
                        toastr_warning_js("{{ __('Please choose image for manual payment.') }}")
                        return false;
                    }
                }

                //load spinner
                $('#promote_project_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')
                setTimeout(function () {
                    $('#promote_project_load_spinner').html('');
                }, 10000);
            });

            // promotion plugin js end

        });
    }(jQuery));


    // todo toastr warning
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
    //toastr delete
    function toastr_delete_js(msg){
        Command: toastr["error"](msg, "Delete !")
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

    //cretae function

</script>
