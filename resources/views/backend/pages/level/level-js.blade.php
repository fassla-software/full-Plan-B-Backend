<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            // add category
            $(document).on('click','.add_level',function(){
                let level = $('#level').val();
                if(level == ''){
                    toastr_warning_js("{{ __('Level field is required !') }}");
                    return false;
                }
            });

            // show length in edit modal
            $(document).on('click','.edit_level_modal',function(){
                let id = $(this).data('id');
                let level = $(this).data('level');

                $('#level_id').val(id);
                $('#edit_level').val(level);
            });

            // update length
            $(document).on('click','.update_level',function(){
                let level = $('#edit_level').val();
                if(level == ''){
                    toastr_warning_js("{{ __('Level field is required !') }}");
                    return false;
                }
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                levels(page);
            });
            function levels(page){
                $.ajax({
                    url:"{{ route('admin.experience.level.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search category
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.experience.level.search') }}",
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
