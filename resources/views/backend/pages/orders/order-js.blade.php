<script>
    (function($){
        "use strict";

        $(document).ready(function(){
            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let string_search = $('#string_search').val();
                let status = $('#get_selected_status_value').val();
                $.ajax({
                    url:"{{ route('admin.order.paginate.data').'?page='}}" + page,
                    data:{string_search:string_search, status:status},
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            });

            // search order
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                let status = $('#get_selected_status_value').val();
                $.ajax({
                    url:"{{ route('admin.order.search') }}",
                    method:'GET',
                    data:{status:status, string_search:string_search},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            })

            //filter
            $(document).on('click','.order_sort_by_status',function(){
                $(this).siblings().removeClass('btn-success');
                $(this).siblings().val('');
                $(this).addClass('btn-success');
                $('#string_search').val('');
                let status = $(this).attr('data-val');
                $('#get_selected_status_value').val(status);

                $.ajax({
                    url:"{{ route('admin.order.sort.by.status') }}",
                    method:'GET',
                    data:{status:status},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            });

            //edit manual payment order
            $(document).on('click','.edit_payment_gateway_modal',function(){
                let order_id = $(this).data('order_id');
                let order_price = $(this).data('order_price');
                let user_type = $(this).data('user_type');
                let user_fullname = $(this).data('user_fullname');
                let user_email = $(this).data('user_email');
                let img_name = $(this).data('img_url');

                if(img_name == ''){
                   $('.manual_payment_image_display').hide();
                }else{
                    $('.manual_payment_image_display').show();
                    let manual_payment_image = "{{ url('/assets/uploads/manual-payment/order') }}/" + img_name;
                    $('.manual_payment_img').attr('src', manual_payment_image);
                }

                $('#user_firstname').val(user_fullname);
                $('#user_email').val(user_email);
                $('#order_id').val(order_id);

                $('.user_fullname').text(user_fullname);
                $('.user_email').text(user_email);
                $('.order_price').text(order_price);
            })

            //show orderr submit description
            $(document).on('click','.order_submit_description',function(){
                let milestone_id = $(this).data('order_milestone_id');
                let description = $(this).data('description');
                if(milestone_id != ''){
                    $('.show_milestone_id').text('#' + milestone_id);
                }
                $('.show_order_submit_description').text(description);
            });

            //show revision details
            $(document).on('click','.show_revision_details',function(){
                let revision_id = $(this).data('revision_id');
                let revision_description = $(this).data('revision_description');
                $('#display_request_revision_description').html(revision_description)
            });

            //hold order status
            $(document).on('click','.hold_order_status',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Hold Order ?")}}',
                    text: '{{__("If you hold the order freelancer and client will not be able to do any action for this order until back to the previous status.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Hold it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            //unhold order status
            $(document).on('click','.unhold_order_status',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Unhold Order ?")}}',
                    text: '{{__("Are you sure to unhold this order.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Unhold it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            //cancel order
            $(document).on('click','.swal_status_change_button_for_cancel_order',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are You Sure?")}}',
                    text: '{{__("If you cancel the order you can not change it again.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Cancel it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            //decline order
            $(document).on('click','.swal_status_change_button_for_decline_order',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are You Sure?")}}',
                    text: '{{__("If you decline the order you can not change it again.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Decline it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            //check chat message
            $(document).on('click','.check_live_chat_message',function(){
                let client_id = $(this).data('client-id')
                let freelancer_id = $(this).data('freelancer-id')
            })

        });
    }(jQuery));

</script>
