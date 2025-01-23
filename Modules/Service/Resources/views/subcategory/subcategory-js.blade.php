<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            $('.category_select2').select2({
                dropdownParent: $('#addModal')
            });
            $('.category_select22').select2({
                dropdownParent: $('#editSubCategoryModal')
            });

            // slug generate
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

            {{--function makeSlug(slug){--}}
            {{--    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');--}}
            {{--    finalSlug = slug.replace(/  +/g, ' ');--}}
            {{--    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');--}}
            {{--    return finalSlug;--}}
            {{--}--}}
            $(document).on('keyup', '#sub_category', function (e) {
                let slug = convertToSlug($(this).val());
                $('#slug').val(slug);

                let url = `{{url('/')}}/` + slug;
                $('.full-slug-show').text(url);
            });

            // add sub category
            $(document).on('click','.add_subcategory',function(){
                let sub_category = $('#sub_category').val();
                let short_description = $('#short_description').val();
                let slug = $('#slug').val();
                let category = $('#category').val();
                if(sub_category == '' || short_description == '' || slug== '' || category == ''){
                    toastr_warning_js("{{ __('Please fill all field !') }}");
                    return false;
                }
            });

            // show subcategory in modal
            $(document).on('click','.edit_sub_category_modal',function(){
                let id = $(this).data('id');
                let subcategory = $(this).data('subcategory');
                let meta_title = $(this).data('meta_title');
                let meta_description = $(this).data('meta_description');
                let short_description = $(this).data('short_description');
                let slug = $(this).data('slug');
                let category = $(this).data('category');
                let image = $(this).data('img_url');
                let image_id = $(this).data('img_id');

                $('#edit_sub_category_id').val(id).trigger("change");
                $('#edit_sub_category').val(subcategory).trigger("change");
                $('#edit_meta_title').val(meta_title);
                $('#edit_meta_description').val(meta_description);
                $('#edit_short_description').val(short_description).trigger("change");
                $('#edit_slug').val(slug).trigger("change");
                $("#edit_category").val(category).trigger("change");

                $('#editSubCategoryModal').find('.media-upload-btn-wrapper .img-wrap').html('');
                $('#editSubCategoryModal').find('.media-upload-btn-wrapper input').val('');

                if (image_id != '') {
                    $('#editSubCategoryModal').find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' + image + '" > </div></div></div>');
                    $('#editSubCategoryModal').find('.media-upload-btn-wrapper input').val(image_id);
                    $('#editSubCategoryModal').find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });

            // update subcategory
            $(document).on('click','.update_sub_category',function(){
                let subcategory = $('#edit_sub_category').val();
                let short_description = $('#edit_short_description').val();
                let category = $('#edit_category').val();
                let slug = $('#edit_slug').val();
                if(subcategory == '' || short_description == '' || category == '' || slug == ''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                categories(page);
            });
            function categories(page){
                $.ajax({
                    url:"{{ route('admin.subcategory.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search category
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.subcategory.search') }}",
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
</script>
