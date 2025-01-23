<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            let site_default_currency_symbol = '{{ site_currency_symbol() }}';
            $('document').on('click','.set_dead_line',function(){
                $(this).flatpickr({
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                });

            })

            // login
            $(document).on('click', '.login_to_continue_order', function(e){
                e.preventDefault();
                let username = $('#username').val();
                let password = $('#password').val();
                let erContainer = $(".error-message");
                erContainer.html('');
                $.ajax({
                    url:"{{ route('order.user.login')}}",
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
                            window.location.reload();
                        }
                        if(res.status == 'failed'){
                            erContainer.html('<div class="alert alert-danger">'+res.msg+'</div>');
                        }
                    }


                });
            });

            // chat warning
            $(document).on('click','.contact_warning_chat_message',function(){
                toastr_warning_js("{{__('Please login as a client to chat with freelancer.')}}")
                return false;
            })


            //get user type
            @php
                $user_type = '';
                if(Auth::check()){
                    $user_type = Auth::user()->user_type == 1 ? 'client' : 'freelancer';
                    $user_type = route($user_type .'.'. 'wallet.history');
                }
            @endphp


            //get type and calculate transaction fee
            $(document).on('click','.basic_standard_premium',function(){
                let type = $('.project-preview-tab .tabs .active').text();
                let project_id = $(this).data('project_id');

                $('.set_basic_standard_premium_type').text(type);
                $('#project_id_for_order').val(project_id);
                $('#basic_standard_premium_type').val(type);

                let currentTab = $('.project-preview-tab .tabs .active').attr("data-tab");
                let price = $(`#${currentTab} .project-preview-tab-inner-item .price span`).text();
                let new_price = price.substring(1);
                let remove_comma_fron_new_price = new_price.replace(/\,/g,'')
                let float_price = parseFloat(remove_comma_fron_new_price);

                <?php
                    $transaction_type = get_static_option('transaction_fee_type') ?? '';
                    $transaction_charge = get_static_option('transaction_fee_charge') ?? 0;
                ?>

                if("{{ $transaction_charge > 0}}"){
                    $('.show_hide_transaction_section').removeClass('d-none');
                    let transaction_type = "{{ $transaction_type }}";
                    let transaction_charge = parseFloat("{{ $transaction_charge }}");
                    let transaction_amount = transaction_type == 'fixed' ? transaction_charge : (float_price*transaction_charge/100);
                    $('.currency_symbol').text(site_default_currency_symbol);
                    $('.transaction_fee_amount').text(transaction_amount.toFixed(2));
                }

                let wallet_balance = {{ Auth::check() ? (Auth::user()->user_wallet?->balance ?? 0) : 0 }};
                if(float_price > wallet_balance){
                    $('.display_balance').html('<span class="text-danger">{{__('Wallet Balance Shortage:')}}'+ site_default_currency_symbol + (float_price-wallet_balance) +'</span>');
                    $('.deposit_link').html('<a href="{{ $user_type }}" target="_blank">{{ __('Deposit')}}</a>');
                }

            });

            //milestone show hide
            $(document).on('click','#pay_by_milestone',function(){
                if ($(this).prop('checked')==true){
                    $('.milestone_wrapper').removeClass('d-none');
                    $('#pay_by_milestone').val('pay-by-milestone');
                }else{
                    $('.milestone_wrapper').addClass('d-none');
                    $('#pay_by_milestone').val('');
                }
            });

            //description show hide
            $(document).on('click','#order_description_btn',function(){
                if ($(this).prop('checked')==true){
                    $('.description_wrapper').removeClass('d-none');
                }else{
                    $('.description_wrapper').addClass('d-none');
                }
            });

            $(document).on('click', '.wallet_selected_payment_gateway , .payment_getway_image ul li',function() {
               let gateway = $('#order_from_user_wallet').val();
               if(gateway == 'wallet' || gateway == 'manual_payment'){
                   $('.show_hide_transaction_section').addClass('d-none');
               }else{
                   $('.show_hide_transaction_section').removeClass('d-none');
               }
            });

            //prevent multiple submit
            $('#prevent_multiple_order_submit').on('submit', function () {
                $('#order_now_only_for_load_spinner').attr('disabled', 'true');
            });

            //load spinner
            $(document).on('click','#order_now_only_for_load_spinner',function(){
                let manual_payment = $('#order_from_user_wallet').val();
                if(manual_payment == 'manual_payment') {
                    let manual_payment_image = $('input[name="manual_payment_image"]').val();
                    if(manual_payment_image == '') {
                        toastr_warning_js("{{__('Image field is required')}}")
                        return false
                    }
                }

                $('#order_create_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')
                setTimeout(function () {
                    $('#order_create_load_spinner').html('');
                }, 10000);
            });

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
