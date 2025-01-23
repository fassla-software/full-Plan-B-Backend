<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            let site_default_currency_symbol = '{{ site_currency_symbol() }}';

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                jobs(page);
            });
            function jobs(page){
                $.ajax({
                    url:"{{ route('subscriptions.pagination').'?page='}}" + page,
                    method:'GET',
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_subscription_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_subscription_result').html(res);
                        }
                    }

                });
            }

            //filter subscription
            $(document).on('click', '.get_subscription_type_id', function(e){
                e.preventDefault();
                let type_id = $(this).data('type_id');
                $.ajax({
                    url:"{{ route('subscriptions.filter')}}",
                    data:{type_id:type_id},
                    method:'GET',
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_subscription_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_subscription_result').html(res);
                        }
                    }

                });
            });


            //choose plan
            @php
                $user_type = '';
                if(Auth::check()){
                    $user_type = Auth::user()->user_type == 1 ? 'client' : 'freelancer';
                    $user_type = route($user_type .'.'. 'wallet.history');
                }
            @endphp

            $(document).on('click', '.choose_plan', function(e){
                let subscription_id = $(this).data('id');
                let subscription_price = $(this).data('price');
                let balance = {{ Auth::check() ? Auth::user()->user_wallet->balance : 0 }};
                $('#subscription_id').val(subscription_id);
                $('#subscription_price').val(subscription_price);

                if(subscription_price > balance){
                     $('.display_balance').html('<span class="text-danger">{{__('Wallet Balance Shortage:')}}'+ site_default_currency_symbol + (subscription_price-balance) +'</span>');
                     $('.deposit_link').html('<a href="{{ $user_type }}" target="_blank">{{ __('Deposit')}}</a>');
                }
            })

            // login
            $(document).on('click', '.login_to_buy_a_subscription', function(e){
                e.preventDefault();
                let username = $('#username').val();
                let password = $('#password').val();
                let subscription_price = $('#subscription_price').val();
                let erContainer = $(".error-message");
                erContainer.html('');
                $.ajax({
                    url:"{{ route('subscriptions.user.login')}}",
                    data:{username:username,password:password},
                    method:'POST',
                    error:function(res){
                        let errors = res.responseJSON;
                        erContainer.html('<div class="alert alert-danger"></div>');
                        $.each(errors.errors, function(index,value){
                            erContainer.find('.alert.alert-danger').append('<p>'+value+'</p>');
                        });
                    },
                    success: function(res){
                        if(res.status=='success'){
                            location.reload();
                            let balance = res.balance;
                            $('#loginModal').modal('hide');
                            if(subscription_price > balance){
                                $('.load_after_login').load(location.href + ' .load_after_login', function (){
                                    $('.display_balance').html('<span class="text-danger">{{__('Wallet Balance Shortage:')}}'+ site_default_currency_symbol + (subscription_price-balance) +'</span>');
                                    $('.deposit_link').html('<a href="{{ $user_type }}" target="_blank">{{ __('Deposit')}}</a>');
                                });
                            }
                        }
                        if(res.status == 'failed'){
                            erContainer.html('<div class="alert alert-danger">'+res.msg+'</div>');
                        }
                    }

                });
            });

            //buy subscription-load spinner
            $(document).on('click','#confirm_buy_subscription_load_spinner',function(){
                //Image validation
                let manual_payment = $('#order_from_user_wallet').val();
                if(manual_payment == 'manual_payment') {
                    let manual_payment_image = $('input[name="manual_payment_image"]').val();
                    if(manual_payment_image == '') {
                        toastr_warning_js("{{__('Image field is required')}}")
                        return false
                    }
                }

                $('#buy_subscription_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')
                setTimeout(function () {
                    $('#buy_subscription_load_spinner').html('');
                }, 10000);
            });

        });
    }(jQuery));
</script>
