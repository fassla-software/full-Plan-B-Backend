<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            // validate subscription type
            $(document).on('click','.add_type',function(){
                let type = $('#type').val();
                let validity = $('#validity').val();
                if(type == '' || validity == ''){
                    toastr_warning_js("{{ __('Both field is required !') }}");
                    return false;
                }
            });
            $(document).on('click','.edit_type',function(){
                let type = $('#edit_type').val();
                let edit_validity = $('#edit_validity').val();
                if(type == '' || edit_validity == ''){
                    toastr_warning_js("{{ __('Both field is required !') }}");
                    return false;
                }
            });

            // show category in edit modal
            $(document).on('click','.edit_type_modal',function(){
                let id = $(this).data('id');
                let type = $(this).data('type');
                let validity = $(this).data('validity');

                $('#type_id').val(id);
                $('#edit_type').val(type);
                $('#edit_validity').val(validity);
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let string_search = $('#string_search').val();
                types(page,string_search);
            });
            function types(page,string_search){
                $.ajax({
                     url:"{{ route('admin.subscription.type.paginate.data').'?page='}}" + page,
                    data:{string_search:string_search},
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search category
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.subscription.type.search') }}",
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

</script>
