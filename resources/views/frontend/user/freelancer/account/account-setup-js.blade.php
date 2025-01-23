<script>
    (function ($) {
        "use strict";
        pre_next();
        $(document).ready(function () {

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
                    toastr_warning_js("{{__('Please fill all fields !')}}");
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

            // add education
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
                                $('.popup-fixed, .popup-overlay').removeClass('popup-active');
                                $('#display_user_education_data').load(location.href + " #display_user_education_data");
                                $(addExperienceForm)[0].reset();
                                toastr_success_js("{{ __('Education Successfully Updated') }}");
                            }
                        }
                    });
                }
            });

            // get subcategories
            $(document).on('click','.work_category_id', function() {
                let category = $(this).find('input').val();
                $('#set_category_id').val(category); //set category id
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.subcategory.all') }}",
                    data: {
                        category: category
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            let all_options = "";
                            let all_subcategories = res.subcategories;
                            $.each(all_subcategories, function(index, value) {
                                all_options += '<li class="setup-wrapper-work-list-item get_subcategory choose_a_subcategory" data-id="'+ value.id +'">' + value.sub_category + '</li>';
                            });
                            if(all_subcategories.length <= 0){
                                $(".get_subcategory").html('<span class="text-danger"> {{ __('No subcategory found for selected category!') }} <span>');
                                $('.work-popup, .popup-overlay').removeClass('popup-active');
                            }else{
                                $(".get_subcategory").html(all_options);
                                $('.work-popup, .popup-overlay').removeClass('popup-active');
                            }
                        }
                    }
                })
            })

            $(document).on('click','.choose_a_subcategory', function() {
                let sub_category = $(this).data('id');
                $('#set_sub_category_id').val(sub_category); //set sub category id
            });

            // search category
            $(document).on('keyup','#category_search_string', function (){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('freelancer.account.category.search') }}",
                    method:'GET',
                    data:{string_search:string_search},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h5 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h5>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            });

            //choose skill
            const myTagInput = new TagsInputs({
                selector: 'skill_input',
                duplicate: false,
                max: 30,
            });

            @php
                $skills =  \App\Models\UserSkill::select('skill')->where('user_id',Auth::guard('web')->user()->id)->first()->skill ?? '';
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

            //profile photo upload
            document.querySelector('#upload_profile_photo').addEventListener('change', function() {
                $("#profilePhotoModal").modal('show');
                if (this.files && this.files[0]) {
                    let img = document.querySelector('.profile_photo_preview');
                    img.onload = () => {
                        URL.revokeObjectURL(img.src);  // no longer needed, free memory
                    }
                    img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                    document.querySelector(".profile_photo_upload").files = this.files;
                    // document.querySelector(".profile_photo_upload").value = this.value;
                    $("#crop").trigger("click");
                }
            });

            //profile photo save
            $(document).on('submit','#profilePhotoUploadForm', function(e) {
                e.preventDefault();

                $.ajax({
                    url:"{{ route('freelancer.account.profile.photo.upload') }}",
                    method:'post',
                    data:new FormData(e.target),
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: () => { },
                    success: (res) => {
                        if(res.status=='uploaded'){
                            $('#profilePhotoModal').modal('hide');
                            $('.profile_photo_area').load(location.href + ' .profile_photo_area');
                        }else{
                            $('.error_msg').html('');
                        }
                    },errors: (err) => {
                    }
                });
            });


        });
    }(jQuery));

    function pre_next()
    {
        let Listings = document.querySelectorAll(".single-setup-request-list li");
        let sections = document.querySelectorAll(".setup-wrapper-contents");
        let nextButton = document.querySelector("#next");
        let prevButton = document.querySelector("#previous");
        let current = 0;

        const toggleListings = () => {
            Listings.forEach(function(e) {
                e.classList.remove('running');
            });
            Listings[current]?.classList?.add("running");
            Listings[current]?.classList?.remove("completed");
            if (current != 0) {
                Listings[current - 1]?.classList?.add("completed");
            }
        }

        const toggleSections = () => {
            sections.forEach(function(section) {
                section?.classList?.remove('active');
            });
            sections[current]?.classList?.add("active");
        }

        if (nextButton != null) {
            nextButton.addEventListener("click", function(e) {
                e.preventDefault();
                if (current <= Listings.length - 1) {
                    current++

                    // add introduction
                    if(current == 1){
                        let title = $('#title').val();
                        let description = $('#description').val();
                        if(title == '' || description == ''){
                            current = 0;
                            toastr_warning_js("{{ __('Please fill title and description !') }}");
                            return false;
                        }else if(description.length >250){
                            toastr_warning_js("{{ __('Description must not greater than 250 charecter !') }}");
                            return false;
                        }else{
                            $.ajax({
                                url: "{{ route('freelancer.account.introduction.add') }}",
                                type: 'post',
                                data: {title: title,description:description},
                                success: function(res){
                                    if(res.status == 'ok'){
                                        toastr_success_js("{{ __('Introduction Successfully Updated') }}");
                                    }
                                }
                            });
                        }
                    }
                    // add experience
                    else if(current == 2){
                        //add experience
                    }
                    // add education
                    else if(current == 3){
                        //add education
                    }
                    // add work
                    else if(current == 4){
                        let category = $('#set_category_id').val();
                        let subcategory = $('#set_sub_category_id').val();
                        if(category == ''){
                            current = 3;
                            toastr_warning_js("{{ __('Please choose a category !') }}");
                            return false;
                        }else{
                            $.ajax({
                                url: "{{ route('freelancer.account.work.add') }}",
                                type: 'post',
                                data: {category: category,subcategory:subcategory},
                                success: function(res){
                                    if(res.status == 'ok'){
                                        toastr_success_js("{{ __('Work Successfully Updated') }}");
                                    }
                                }
                            });
                        }
                    }
                    // add skill
                    else if(current == 5){
                        let skill = $('#skill_input').val();
                        if(skill == ''){
                            current = 4;
                            toastr_warning_js("{{ __('You must add one or more skills !') }}");
                            return false;
                        }else{
                            $.ajax({
                                url: "{{ route('freelancer.account.skill.add') }}",
                                type: 'post',
                                data: {skill: skill},
                                success: function(res){
                                    if(res.status == 'ok'){
                                        toastr_success_js("{{ __('Skill Successfully Updated') }}");
                                    }
                                }
                            });
                        }
                    }
                    // add hourly rate
                    else if(current == 6){
                        let hourly_rate = $('#hourly_rate').val() ?? 1;
                        if(hourly_rate == ''){
                            current = 5;
                            toastr_warning_js("{{ __('You must add hourly rate!') }}");
                            return false;
                        }else{
                            $.ajax({
                                url: "{{ route('freelancer.account.hourly.rate.add') }}",
                                type: 'post',
                                data: {hourly_rate: hourly_rate},
                                success: function(res){
                                    if(res.status == 'ok'){
                                        toastr_success_js("{{ __('Profile Photo Successfully Updated') }}");
                                        let redirectPath = "{{route('freelancer.account.congrats')}}";
                                        @if(!empty(request()->get('return')))
                                            redirectPath = "{{url('/'.request()->get('return'))}}";
                                        @endif
                                            window.location = redirectPath;
                                    }
                                }
                            });
                        }
                    }
                }
                if(current != 6){
                    toggleListings();
                    toggleSections();
                }
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
    }

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
    // todo toastr success
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
