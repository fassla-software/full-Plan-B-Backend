<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            $('.select2_category, .select2_subcategory').select2({
                dropdownParent: $('#addModal')
            });
            $('.select22_category, .select22_subcategory').select2({
                dropdownParent: $('#editSkillModal')
            });

            // add skill
            $(document).on('click','.add_skill',function(e){
                let category = $('#category').val();
                let skill = $('#skill').val();
                if(skill == '' || category == ''){
                    toastr_warning_js("{{ __('Please fill both skill & category field !') }}");
                    return false;
                }
            });

            //show skill in edit modal
            $(document).on('click','.edit_skill_modal',function(){
                let skill_id = $(this).data('skill_id');
                let skill = $(this).data('skill');
                let category = $(this).data('category');
                let subcategory = $(this).data('subcategory');

                $('#skill_id').val(skill_id).trigger("change");
                $('#edit_skill').val(skill).trigger("change");
                $('#edit_category').val(category).trigger("change");
                $('#edit_sub_category').val(subcategory).trigger("change");
            });

            // update skill
            $(document).on('click','.edit_skill',function(e){
                let skill = $('#edit_skill').val();
                let category = $('#edit_category').val();
                if(skill == '' || category == ''){
                    toastr_warning_js("{{ __('Please fill both skill & category field !') }}");
                    return false;
                }
            });

            //change category and get subcategory
            $('#category , #edit_category').on('change', function() {
                let category = $(this).val();
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.subcategory.all') }}",
                    data: {
                        category: category
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            let all_options = "<option value=''>{{__('Select Subcategory')}}</option>";
                            let all_subcategories = res.subcategories;
                            $.each(all_subcategories, function(index, value) {
                                all_options += "<option value='" + value.id +
                                    "'>" + value.sub_category + "</option>";
                            });
                            $(".get_subcategory").html(all_options);
                            $(".info_msg").html('');
                            if(all_subcategories.length <= 0){
                                $(".info_msg").html('<span class="text-danger"> {{ __('No subcategory found for selected category!') }} <span>');
                            }
                        }
                    }
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
                    url:"{{ route('admin.skill.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.skill.search') }}",
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
