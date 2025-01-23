<script>
    (function($){
        "use strict";

        $(document).ready(function(){

            // add rows
            var row = $(".attr");
            function addRow() {
                row.clone(true, true).appendTo("#features");
            }
            function removeRow(button) {
                button.closest("div.attr").remove();
            }
            $('#features .attr:first-child').find('.remove').hide();

            $(".add").on('click', function () {
                addRow();
                if($("#features .attr").length > 1) {
                    $(".remove").show();
                }
            });
            $(".remove").on('click', function () {
                if($("#features .attr").size() == 1) {
                    $(".remove").hide();
                } else {
                    removeRow($(this));
                    if($("#features .attr").size() == 1) {
                        $(".remove").hide();
                    }
                }
            });

            //edit type
            var edit_row = `<div class="attr single-input-feature-attr">
                <input name="feature[]" class="feature form-control" type="text" placeholder="{{ __('Enter feature') }}">
                    <div class="checkbox-inline">
                        <input name="status[]" type="checkbox" class="required-entry single-input-feature-checkbox check-input">
                    </div>
                    <button class="btn btn-danger btn-sm remove_row" type="button"><i class="fas fa-times"></i></button>
            </div>`;

            $(".add_new_row_for_edit").on('click', function () {
                $('#features').append(edit_row);
            });

            $(document).on('click',".remove_row", function () {
                $(this).parent().remove();
            });

            // add subscription
            $(document).on('click','.validate_subscription_type',function(){
                let type = $('#type').val();
                let title = $('#title').val();
                let price = $('#price').val();
                let connect = $('#connect').val();
                let feature = $('.feature').val();
                if(type == '' || title=='' || price == '' || connect == '' || feature == ''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let string_search = $('#string_search').val();
                categories(page,string_search);
            });
            function categories(page,string_search){
                $.ajax({
                     url:"{{ route('admin.subscription.paginate.data').'?page='}}" + page,
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
                    url:"{{ route('admin.subscription.search') }}",
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
