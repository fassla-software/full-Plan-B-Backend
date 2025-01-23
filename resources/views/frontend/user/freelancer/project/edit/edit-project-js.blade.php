<script>
    $(document).ready(function (){
        setTimeout(function (){
            $("#offer_packages_available_or_not").trigger('change');
        },1000)
    });

    (function ($) {
        "use strict";

        let validation = {
            project_title_error: false
        };

        pre_next();

        $(document).ready(function () {

            $('.category_select2').select2();
            $('.subcategory_select2').select2();
            let check_package_titles = {
                "status":false
            };

            var url = `{{url('/')}}/{{ $project_details->slug }}`;
            $('.display_project_slug').text(url);

            // change country and get state
            $('#subcategory_info').hide();
            $(document).on('change','#category', function() {
                let category = $(this).val();
                $('#subcategory_info').show();
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.subcategory.all') }}",
                    data: {
                        category: category
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            let all_options = "<option value=''>{{__('Select Sub Category')}}</option>";
                            let all_subcategories = res.subcategories;
                            $.each(all_subcategories, function(index, value) {
                                all_options += "<option value='" + value.id +
                                    "'>" + value.sub_category + "</option>";
                            });
                            $(".get_subcategory").html(all_options);
                            $("#subcategory_info").html('');
                            if(all_subcategories.length <= 0){
                                $("#subcategory_info").html('<span class="text-danger"> {{ __('No sub categories found for selected category!') }} <span>');
                            }
                        }
                    }
                })
            })

            // project title length check
            $('#project_title_char_length_check').hide();
            $('#project_title').on('keydown keyup change', function(){
                $('#project_title_char_length_check').show();
                let title_min_length = 5;
                let title_max_length = 100;
                let project_title_length = $('#project_title').val().length;

                if(project_title_length < title_min_length){
                    $('#project_title_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum') }} '+ title_min_length +' {{ __('required') }}.</p>');
                }else if(project_title_length > title_max_length){
                    $('#project_title_char_length_check').html('<p class="text text-danger">{{ __('Length is not valid, maximum') }} '+ title_max_length +' {{ __('allowed') }}.</p>');
                }else{
                    $('#project_title_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                }
            });

            //slug generate
            // function makeSlug(slug){
            //     let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
            //     finalSlug = slug.replace(/  +/g, ' ');
            //     finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
            //     return finalSlug;
            // }

            function transliterateCyrillic(text) {
                const cyrillicToLatinMap = {
                    'А': 'A', 'а': 'a', 'Б': 'B', 'б': 'b', 'В': 'V', 'в': 'v',
                    'Г': 'G', 'г': 'g', 'Д': 'D', 'д': 'd', 'Е': 'E', 'е': 'e',
                    'Ё': 'Yo', 'ё': 'yo', 'Ж': 'Zh', 'ж': 'zh', 'З': 'Z', 'з': 'z',
                    'И': 'I', 'и': 'i', 'Й': 'Y', 'й': 'y', 'К': 'K', 'к': 'k',
                    'Л': 'L', 'л': 'l', 'М': 'M', 'м': 'm', 'Н': 'N', 'н': 'n',
                    'О': 'O', 'о': 'o', 'П': 'P', 'п': 'p', 'Р': 'R', 'р': 'r',
                    'С': 'S', 'с': 's', 'Т': 'T', 'т': 't', 'У': 'U', 'у': 'u',
                    'Ф': 'F', 'ф': 'f', 'Х': 'Kh', 'х': 'kh', 'Ц': 'Ts', 'ц': 'ts',
                    'Ч': 'Ch', 'ч': 'ch', 'Ш': 'Sh', 'ш': 'sh', 'Щ': 'Shch', 'щ': 'shch',
                    'Ъ': '', 'ъ': '', 'Ы': 'Y', 'ы': 'y', 'Ь': '', 'ь': '',
                    'Э': 'E', 'э': 'e', 'Ю': 'Yu', 'ю': 'yu', 'Я': 'Ya', 'я': 'ya',
                    // Additional characters for other Cyrillic-based languages
                    'Ә': 'Ae', 'ә': 'ae', 'Ғ': 'Gh', 'ғ': 'gh', 'Қ': 'Q', 'қ': 'q',
                    'Ң': 'Ng', 'ң': 'ng', 'Ө': 'Oe', 'ө': 'oe', 'Ұ': 'U', 'ұ': 'u',
                    'Ү': 'Ue', 'ү': 'ue', 'Һ': 'H', 'һ': 'h', 'І': 'I', 'і': 'i',
                    // Ukrainian specific
                    'Є': 'Ye', 'є': 'ye', 'І': 'I', 'і': 'i', 'Ї': 'Yi', 'ї': 'yi',
                    'Ґ': 'G', 'ґ': 'g',
                    // Belarusian specific
                    'Ў': 'U', 'ў': 'u',
                    // Serbian specific
                    'Ђ': 'Dj', 'ђ': 'dj', 'Ј': 'J', 'ј': 'j', 'Љ': 'Lj', 'љ': 'lj',
                    'Њ': 'Nj', 'њ': 'nj', 'Ћ': 'C', 'ћ': 'c', 'Џ': 'Dz', 'џ': 'dz',
                    // Macedonian specific
                    'Ѓ': 'Gj', 'ѓ': 'gj', 'Ѕ': 'Dz', 'ѕ': 'dz', 'Ќ': 'Kj', 'ќ': 'kj',
                    'Љ': 'Lj', 'љ': 'lj', 'Њ': 'Nj', 'њ': 'nj', 'Џ': 'Dz', 'џ': 'dz'
                };

                const arabicToLatinMap = {
                    'ا': 'a', 'أ': 'a', 'إ': 'i', 'آ': 'aa', 'ب': 'b', 'ت': 't', 'ث': 'th',
                    'ج': 'j', 'ح': 'h', 'خ': 'kh', 'د': 'd', 'ذ': 'dh', 'ر': 'r', 'ز': 'z',
                    'س': 's', 'ش': 'sh', 'ص': 's', 'ض': 'd', 'ط': 't', 'ظ': 'dh', 'ع': 'a',
                    'غ': 'gh', 'ف': 'f', 'ق': 'q', 'ك': 'k', 'ل': 'l', 'م': 'm', 'ن': 'n',
                    'ه': 'h', 'و': 'w', 'ي': 'y', 'ى': 'a', 'ة': 'h', 'ئ': 'e', 'ء': 'a',
                    'ؤ': 'o', 'لا': 'la'
                };

                const langToLatinMap = currentLang() === 'ar' ? arabicToLatinMap : cyrillicToLatinMap;

                return text.split('').map(char => langToLatinMap[char] || char).join('');
            }

            function convertToSlug(text) {
                const transliteratedText = transliterateCyrillic(text);

                return transliteratedText
                    .toLowerCase()
                    .trim()
                    .replace(/\s+/g, '-');           // Replace spaces with -
            }

            function currentLang()
            {
                return document.documentElement.lang === 'ar' ? 'ar' : 'cy';
            }

            $(document).on('keyup', '#slug', function (e) {
                $('.display_project_slug').text('');
                let slug = convertToSlug($(this).val());
                $('#slug').val(slug);

                let url = `{{url('/')}}/` + slug;
                $('.full-slug-show').text(url);
            });

            //update slug
            $(document).on('click','.edit_project_slug',function(){
                $('.display_label_title').removeClass('d-none');
                $('#slug').removeClass('d-none');
            })

            // check package is available or not
            $(document).on('change','#offer_packages_available_or_not',function (e) {
                if($(this).prop('checked')){
                    $('.disabled_or_not'). prop('disabled', false);
                    $('#offer_packages_available_or_not').val('1')
                }else{
                    $('.disabled_or_not'). prop('disabled', true);
                    $('#offer_packages_available_or_not').val('0')
                }

            });

            // todo select checkbox or numeric
            $('.package-field-input .disabled_or_not').remove();
            // $(document).on('keyup','.checkbox_or_numeric_title',function(){
            //     let variable_name = $(this).val().toLowerCase().replaceAll(" ","_");
            //     let currentRow = $(this).closest(".append-include").find("td input");
            //
            //     let t_array = [
            //         "basic",
            //         "standard",
            //         "premium"
            //     ];
            //     let i = 0;
            //     console.log(variable_name)
            //
            //     currentRow.each(function () {
            //         // let inputName = variable_name + '[' + t_array[i++] + ']';
            //
            //         $(this).removeAttr('name').attr("name", `${variable_name}[${t_array[i++]}]`);
            //     });
            // });

            $(document).on('change','.checkbox_or_numeric_select',function(){
                let value = $(this).val().toLowerCase().replaceAll(" ","_");

                let variable_name = $(this).closest(".append-include").find('.checkbox_or_numeric_title').val().replaceAll(" ","_");
                let currentRow = $(this).closest(".append-include").find("td");
                let add_minus_button = `
                    <div class="package-button-wrapper">
                         <div class="package-field-icon add-rows">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div class="package-field-icon remove-rows remove-icon">
                            <i class="fa-solid fa-minus"></i>
                        </div>
                    </div>
                `;

                let t_array = [
                    "basic",
                    "standard",
                    "premium"
                ];
                let i = 0;

                currentRow.each(function (){
                    let row_type = t_array[i++];
                    let inputName = variable_name + '['+ row_type +']';

                    let checkbox, number;


                    if($('#offer_packages_available_or_not').val() != 1 && i > 1){
                        checkbox = '<input type="checkbox" name="'+ inputName +'" class="check-input disabled_or_not" checked>';
                        number = '<input type="number" name="'+ inputName +'" class="form-control disabled_or_not" value="5">';
                    }else{
                        checkbox = '<input type="checkbox" name="'+ inputName +'" class="check-input" checked>';
                        number = '<input type="number" name="'+ inputName +'" class="form-control" value="5">';
                    }

                    if(row_type == 'premium'){
                        checkbox  = checkbox + add_minus_button;
                        number  = number + add_minus_button;
                    }

                    if(value == 'checkbox'){
                        $(this).html(checkbox);
                    }else{
                        $(this).html(number);
                    }

                    if($('#offer_packages_available_or_not').val() == 1){
                        $('.disabled_or_not'). prop('disabled', false);
                    }else{
                        $('.disabled_or_not'). prop('disabled', true);
                    }
                })
            });


            // checkbox numeric title get and set
            $(document).on('keyup','.checkbox_or_numeric_title',function(){
                let check_numeric_title = $(this).text();
                $('#check_numeric_title').val(check_numeric_title);
            });

            //remove row
            $(document).on('click', '.remove-icon', function() {
                $(this).closest('.append-remove').remove();
            });

            //add row
            $(document).on('click', '.add-rows', function() {
                let tableData = `
                    <tr class="append-include append-remove">
                       <th>
                            <div class="package-head-left">
                                <div class="package-head-left-flex flex-column">
                                    <input class="form-control checkbox_or_numeric_title" type="text" name="checkbox_or_numeric_title[]" placeholder="{{ __('Enter Title') }}">
                                    <div class="text-danger validation-error"></div>
                                </div>
                                <div class="package-field">
                                    <div class="package-field-select">
                                        <select class="form-control checkbox_or_numeric_select" name="checkbox_or_numeric_select[]">
                                            <option value="checkbox">{{ __('Check Boxes') }}</option>
                                            <option value="numeric">{{ __('Numeric') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </th>

                        <td>
                                <input type="checkbox" name="title[basic]" class="check-input" checked>
                        </td>

                        <td>
                                <input name="title[standard]" type="checkbox" class="check-input disabled_or_not" checked>
                        </td>

                        <td>
                            <input name="title[premium]" type="checkbox" class="check-input disabled_or_not" checked>

                            <div class="package-button-wrapper">
                                <div class="package-field-icon add-rows">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                                <div class="package-field-icon remove-rows remove-icon">
                                    <i class="fa-solid fa-minus"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
            `;

                $('.create_project_table tr:last').prev().after(tableData);
                $('.package-field-input .disabled_or_not').remove();

                if($('#offer_packages_available_or_not').prop('checked')){
                    $('.create_project_table .disabled_or_not'). prop('disabled', false);
                }else{
                    $('.create_project_table .disabled_or_not'). prop('disabled', true);
                }
            });

            //profile photo upload
            document.querySelector('#upload_project_photo').addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    let img = document.querySelector('.project_photo_preview');
                    img.onload = () =>{
                        URL.revokeObjectURL(img.src);  // no longer needed, free memory
                    }
                    img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                    document.querySelector(".project_photo_preview").files = this.files;
                    $("#crop").trigger("click");
                }
            });

            // basic price setup
            $(document).on('click','.basic_price_setup',function(){
                let basic_discount_charge = $('#basic_discount_charge').val();
                let basic_regular_charge = $('#basic_regular_charge').val();
                if(basic_discount_charge != ''){
                    $('.basic_discount_charge').html('<span class="basic_discount_charge">' + '{{ site_currency_symbol() }}' + basic_discount_charge + '</span>');
                    $('.basic_regular_charge').html('<span class="basic_regular_charge"><s>' + '{{ site_currency_symbol() }}' +basic_regular_charge + '</s></span>');
                }else{
                    $('.basic_discount_charge').html('');
                    $('.basic_regular_charge').html('<span class="basic_regular_charge">' + '{{ site_currency_symbol() }}' + basic_regular_charge + '</span>');
                }
                $('.price-popup-basic-charge, .popup-overlay').removeClass('popup-active');
            })

            // basic price setup
            $(document).on('click','.standard_price_setup',function(){
                let standard_regular_charge = $('#standard_regular_charge').val();
                let standard_discount_charge = $('#standard_discount_charge').val();
                if(standard_discount_charge != ''){
                    $('.standard_discount_charge').html('<span class="standard_discount_charge">' + '{{ site_currency_symbol() }}' + standard_discount_charge + '</span>');
                    $('.standard_regular_charge').html('<span class="standard_regular_charge"><s>' + '{{ site_currency_symbol() }}' +standard_regular_charge + '</s></span>');
                }else{
                    $('.standard_discount_charge').html('');
                    $('.standard_regular_charge').html('<span class="standard_regular_charge">' + '{{ site_currency_symbol() }}' + standard_regular_charge + '</span>');
                }
                $('.price-popup-standard-charge, .popup-overlay').removeClass('popup-active');
            })

            // basic price setup
            $(document).on('click','.premium_price_setup',function(){
                let premium_discount_charge = $('#premium_discount_charge').val();
                let premium_regular_charge = $('#premium_regular_charge').val();
                if(premium_discount_charge != ''){
                    $('.premium_discount_charge').html('<span class="premium_discount_charge">' + '{{ site_currency_symbol() }}' + premium_discount_charge + '</span>');
                    $('.premium_regular_charge').html('<span class="premium_regular_charge"><s>' + '{{ site_currency_symbol() }}' +premium_regular_charge + '</s></span>');
                }else{
                    $('.premium_discount_charge').html('');
                    $('.premium_regular_charge').html('<span class="premium_regular_charge">' + '{{ site_currency_symbol() }}' + premium_regular_charge + '</span>');
                }
                $('.price-popup-premium-charge, .popup-overlay').removeClass('popup-active');
            })

            $(document).on('keyup','.checkbox_or_numeric_title',async function(){
                let variable_name = $(this).val().replaceAll(" ","_").toLowerCase();
                let currentRow = $(this).closest(".append-include").find("td input");
                let arrVal = [];

                $(this).parent().find('.validation-error').text("");

                validation.project_title_error = false;

                await $(`.checkbox_or_numeric_title`).each(function (){
                    if(arrVal.includes($(this).val())) {
                        validation.project_title_error = true;
                        arrVal.push($(this).val());

                        if($(this).val().length > 0){
                            $(this).parent().find('.validation-error').text("{{ __("This title is already in use.") }}");
                        }else{
                            $(this).parent().find('.validation-error').text("{{ __("This field is required.") }}");
                        }
                    }else{
                        if($(this).val().length < 1){
                            validation.project_title_error = true;
                            $(this).parent().find('.validation-error').text("{{ __("This field is required.") }}");
                        }
                        arrVal.push($(this).val());
                    }
                });

                let t_array = [
                    "basic",
                    "standard",
                    "premium"
                ];
                let i = 0;

                currentRow.each(function () {
                    let inputName = variable_name + '[' + t_array[i++] + ']';
                    $(this).attr("name", inputName);
                });
            });

            function titleShouldBeUnique(){
                toastr_warning_js("{{ __("All package title is required and title must be unique.") }}");
            }

            // create project
            $(document).on('click','#confirm_create_project',async function(e){
               let basic_title = $('#basic_title').text();
               let standard_title = $('#standard_title').text();
               let premium_title = $('#premium_title').text();
               let checkbox_or_numeric_title = $('.checkbox_or_numeric_title').val();

                $('#set_basic_title').val(basic_title)
                $('#set_standard_title').val(standard_title)
                $('#set_premium_title').val(premium_title)

                let selfError = {error: false};
                let arrVal = [];
                let validation = false;

                await $(`.checkbox_or_numeric_title`).each(function (){
                    if(arrVal.includes($(this).val())) {
                        validation = true;
                    }else{
                        if($(this).val().length < 1){
                            validation = true;
                            $(this).parent().find('.validation-error').text("{{ __("This field is required.") }}");
                        }
                        arrVal.push($(this).val());
                    }
                });

                if(validation){
                    titleShouldBeUnique();
                    e.preventDefault();
                    return false;
                }else{
                    check_package_titles.status = true;
                }

                if(selfError.error){
                    titleShouldBeUnique();
                    return false;
                }

                if(check_package_titles.status){
                    $('#project_edit_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')
                }else{
                    return false;
                }

            })

        });
    }(jQuery));

    function pre_next()
    {
        let Listings = document.querySelectorAll(".single-setup-request-list li");
        let sections = document.querySelectorAll(".setup-wrapper-contents");
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

        $(document).on("click", "#next", function (e){
            e.preventDefault();

            if (current <= Listings.length) {
                current++

                // todo add introduction
                if(current == 1){
                    let title = $('#project_title').val();
                    let description = $('#project_description').val();
                    let category = $('#category').val();
                    let subcategory = $('#subcategory').val();

                    if(title == '' || description == '' || category == '' || subcategory == ''){
                        current = 0;
                        toastr_warning_js("{{ __('Please fill all fields !') }}");
                        return false;
                    }
                    if(title.length < 5){
                        current = 0;
                        toastr_warning_js("{{ __('Title must be at least 5 characters') }}");
                        return false;
                    }
                    if(description.length < 10){
                        current = 0;
                        toastr_warning_js("{{ __('Description must be at least 10 characters') }}");
                        return false;
                    }
                }
                else if(current == 2){
                    let image = $('#upload_project_photo').val();
                    @if(moduleExists('SecurityManage'))
                        @if(Auth::guard('web')->user()->freeze_project == 'freeze')
                            $('.setup-footer-right').html('<a href="#" class="btn-profile btn-bg-1 @if(Auth::guard('web')->user()->freeze_project == 'freeze') disabled-link @endif">{{ __('Update Project') }}</a>');
                        @else
                            $('.setup-footer-right').html('<button type="submit" class="btn-profile btn-bg-1" id="confirm_create_project">{{ __('Update Project') }}<span id="project_edit_load_spinner"></span></button>');
                        @endif
                    @else
                        $('.setup-footer-right').html('<button type="submit" class="btn-profile btn-bg-1" id="confirm_create_project">{{ __('Update Project') }}<span id="project_edit_load_spinner"></span></button>');
                    @endif
                }else{
                    $('.setup-footer-right').html('<a href="javascript:void(0)" class="setup-footer-next next" id="next"> <i class="fas fa-arrow-right"></i> </a>');
                }
            }

            toggleListings();
            toggleSections();
        })

        $(document).on("click", "#previous", function (){
            if (current > 0) {
                current--
                if(current == 2){
                    $('.setup-footer-right').html('<input type="submit" class="btn-profile btn-bg-1" value="{{ __('Update Project') }}">');
                }else{
                    $('.setup-footer-right').html('<a href="javascript:void(0)" class="setup-footer-next next" id="next"> <i class="fas fa-arrow-right"></i> </a>');
                }
            }
            toggleListings();
            toggleSections();
        });
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
