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

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                projects(page);
            });
            function projects(page){
                $.ajax({
                    url:"{{ route('admin.project.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup input','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.project.search') }}",
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
