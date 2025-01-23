<div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('subscriptions.buy') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="subscription_id" id="subscription_id">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    @if (Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1)
                        <x-notice.general-notice :description="__('Notice: Please login as a freelancer to buy a subscription.')" />
                    @else
                        <h4>{{ __('Buy Subscription') }}</h4>
                    @endif

                </div>
                <div class="modal-body">
                    <div class="confirm-payment payment-border">
                        <div class="single-checkbox">
                            <div class="checkbox-inlines">
                                <label class="checkbox-label load_after_login" for="choose">
                                    @if (Auth::check() && Auth::user()->user_wallet?->balance > 0)
                                        {!! \App\Helper\PaymentGatewayList::renderWalletForm() !!}
                                        <span class="wallet-balance mt-2 d-block">{{ __('Wallet Balance:') }}
                                            <strong class="main-balance">
                                                {{ float_amount_with_currency_symbol(Auth::user()->user_wallet?->balance) }}</strong></span>
                                        <br>
                                        <span class="display_balance"></span>
                                        <br>
                                        <span class="deposit_link"></span>
                                    @endif
                                    {!! \App\Helper\PaymentGatewayList::renderPaymentGatewayForForm(false) !!}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-profile btn-outline-gray btn-hover-danger"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    @if (Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2)
                        <x-btn.submit :title="__('Buy Now')" :class="'btn-profile btn-bg-1 buy_subscription'" />
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
