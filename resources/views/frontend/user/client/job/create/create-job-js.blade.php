<script>
    (function($){
        "use strict";
        pre_next();
        $(document).ready(function(){
            $('.category_select2').select2();
            $('.subcategory_select2').select2();
            $('.skill_select2').select2();

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
            $('#job_title_char_length_check').hide();
            $('#title').on('keydown keyup change', function(){
                $('#job_title_char_length_check').show();
                let title_min_length = 5;
                let title_max_length = 100;
                let job_title_length = $('#title').val().length;

                if(job_title_length < title_min_length){
                    $('#job_title_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum') }} '+ title_min_length +' {{ __('required') }}.</p>');
                }else if(job_title_length > title_max_length){
                    $('#job_title_char_length_check').html('<p class="text text-danger">{{ __('Length is not valid, maximum') }} '+ title_max_length +' {{ __('allowed') }}.</p>');
                }else{
                    $('#job_title_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                }
            });


            //slug generate

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


            $('.full-slug-show').hide();
            $(document).on('keyup', '#title , #slug', function (e) {
                $('.full-slug-show').show();
                let slug = convertToSlug($(this).val());
                $('#slug').val(slug);

                let url = `{{url('/')}}/` + slug;
                $('.full-slug-show').text(url);
            });

            //update slug
            $(document).on('click','.edit_job_slug',function(){
                $('.display_label_title').removeClass('d-none');
                $('#slug').removeClass('d-none');
            })

            //tags input
            if (document.querySelector('#tags') != null) {
                let myTagInput = new TagsInputs({
                    selector: 'tags',
                    duplicate: false,
                    max: 100,
                });
                myTagInput.addData(['tags']);
            }

            //attachment js
            $(document).on('click , change','#attachment',function(){
                let uploadImage = document.querySelector(".uploadImage");
                let inputTag = document.querySelector(".inputTag");
                if(inputTag != null) {
                    inputTag.addEventListener('change', ()=> {
                        let inputTag = document.querySelector(".inputTag").files[0];
                        uploadImage.innerText = inputTag.name;
                    });
                };
            });

            //hourly rate
            $(document).on('change','#type',function(){
                let type = $(this).val();
                if(type == 'hourly') {
                    $('.manage-hourly-jobs').removeClass('d-none');
                    $('.manage-fixed-jobs').addClass('d-none');
                }else{
                    $('.manage-fixed-jobs').removeClass('d-none');
                    $('.manage-hourly-jobs').addClass('d-none');
                }
            });

            //confirm create job
            $(document).on('click','#confirm_create_job',function(){
                let type = $('#type').val();
                let budget = $('#budget').val();
                let skills = $('#skill').val();
                if(type == '' || budget == '' || skills == ''){
                    toastr_warning_js("{{ __('Except attachment all fields required !') }}");
                    return false;
                }else{
                    $('#job_create_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')
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

                // add introduction
                if(current == 1){
                    let category = $('#category').val();
                    let subcategory = $('#subcategory').val();
                    let title = $('#title').val();
                    let description = $('#description').val();
                    let level = $('#level').val();
                    let duration = $('#duration').val();
                    if(category == '' || subcategory == '' || title == '' || description == '' || level == '' || duration==''){
                        current = 0;
                        toastr_warning_js("{{ __('Please fill all fields !') }}");
                        return false;
                    }else if(title.length < 5){
                        current = 0;
                        toastr_warning_js("{{ __('Title must be at least 5 characters') }}");
                        return false;
                    }else if(description.length < 10){
                        current = 0;
                        toastr_warning_js("{{ __('Description must be at least 10 characters') }}");
                        return false;
                    }
                    else {
                        $('.setup-footer-right').html('<button type="submit" class="btn-profile btn-bg-1" id="confirm_create_job">{{ __('Create Job') }}<span id="job_create_load_spinner"></span></button>');
                    }
                }
            }

            toggleListings();
            toggleSections();
        })

        $(document).on("click", "#previous", function (){
            if (current > 0) {
                current--
                if(current == 2){
                    $('.setup-footer-right').html('<input type="submit" class="btn-profile btn-bg-1" value="{{ __('Create Job') }}">');
                }else{
                    $('.setup-footer-right').html('<a href="javascript:void(0)" class="setup-footer-next next" id="next"> <i class="fas fa-arrow-right"></i> </a>');
                }
            }
            toggleListings();
            toggleSections();
        });
    }

</script>
