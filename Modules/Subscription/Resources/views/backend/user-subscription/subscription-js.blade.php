<script>
    (function($){
        "use strict";

        $(document).ready(function(){
            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let string_search = $('#string_search').val();
                let route = $(this).closest(".custom_pagination").attr("data-route");
                let url = route + "?page=" + page;

                subscriptions(url,string_search);
            });
            function subscriptions(url,string_search){
                $.ajax({
                     url: url,
                    data:{string_search:string_search},
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search category
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                let selected_value = $('#get_selected_value').val();
                let filter_val = '';
                if(selected_value == 'active-sub'){
                    filter_val = 1
                }
                if(selected_value == 'inactive-sub'){
                    filter_val = 0
                }
                if(selected_value == 'manual-sub'){
                    filter_val = 'manual_payment'
                }
                $.ajax({
                    url:"{{ route('admin.user.subscription.search') }}",
                    method:'GET',
                    data:{string_search:string_search,filter_val:filter_val},
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
            $(document).on('click','#active_subscription',function(){
                $(this).siblings().removeClass('btn-success');
                $(this).siblings().val('');
                $(this).addClass('btn-success');
                let string_search = $('#string_search').val();
                let val = $(this).attr('data-val');
                $('#get_selected_value').val(val)

                $.ajax({
                    url:"{{ route('admin.user.subscription.active') }}",
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
            });
            $(document).on('click','#inactive_subscription',function(){
                $(this).siblings().removeClass('btn-success');
                $(this).siblings().val('');
                $(this).addClass('btn-success');
                $(this).attr('data-val');
                let string_search = $('#string_search').val();
                let val = $(this).attr('data-val');
                $('#get_selected_value').val(val)

                $.ajax({
                    url:"{{ route('admin.user.subscription.inactive') }}",
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
            });
            $(document).on('click','#manual_subscription',function(){
                $(this).siblings().removeClass('btn-success');
                $(this).siblings().val('');
                $(this).addClass('btn-success');
                let val = $(this).attr('data-val');
                $('#get_selected_value').val(val)
                let string_search = $('#string_search').val();

                $.ajax({
                    url:"{{ route('admin.user.subscription.manual') }}",
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
            });

            //edit manual payment subscription
            $(document).on('click','.edit_payment_gateway_modal',function(){
                let subscription_id = $(this).data('subscription_id');
                let user_type = $(this).data('user_type');
                let user_firstname = $(this).data('user_firstname');
                let user_email = $(this).data('user_email');
                let img_name = $(this).data('img_url');
                let manual_payment_image = "{{ url('/assets/uploads/manual-payment/subscription') }}/" + img_name;

                let extension = img_name.slice((img_name.lastIndexOf(".") - 1 >>> 0) + 2);

                $('#user_firstname').val(user_firstname);
                $('#user_email').val(user_email);
                $('#subscription_id').val(subscription_id);

                $('.user_type').text(user_type);
                $('.user_email').text(user_email);
                if (extension.toLowerCase() === 'pdf') {
                    $('.manual_payment_img').html(`<a href="${manual_payment_image}" class="text-primary" download">{{ __('Download Pdf') }}</a>`);
                } else {
                    $('.manual_payment_img').html(`<img src="${manual_payment_image}" alt="{{ __('Manual Payment Image') }}">`);
                }

            })

        });
    }(jQuery));

</script>
