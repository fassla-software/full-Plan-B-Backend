<div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('order.user.confirm') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="job_id_for_order" id="job_id_for_order">
            <input type="hidden" name="proposal_id_for_order" id="proposal_id_for_order">
            <input type="hidden" name="offer_id_for_order" id="offer_id_for_order">
            <input type="hidden" name="job_type_for_order" id="job_type_for_order">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="set_basic_standard_premium_type"></h3>
                </div>
                <div class="modal-body">
                    <x-notice.general-notice :description="__(
                        'Before create an order we encourage to contact the seller so that seller can not decline your order.',
                    )" />
                    <div class="confirm-payment payment-border">
                        <div class="single-checkbox">
                            <div class="checkbox-inlines">
                                <label class="checkbox-label load_after_login" for="choose">
                                    @if (Auth::check() && Auth::user()->user_wallet?->balance > 0)
                                        {!! \App\Helper\PaymentGatewayList::renderWalletForm() !!}
                                        <span class="wallet-balance mt-2 d-block">{{ __('Wallet Balance:') }}
                                            <strong
                                                class="main-balance">{{ float_amount_with_currency_symbol(Auth::user()->user_wallet?->balance) }}</strong></span>
                                        <br>
                                        <span class="display_balance"></span>
                                        <br>
                                        <span class="deposit_link"></span>
                                    @endif
                                    <p class="d-none show_hide_transaction_section">
                                        <strong>{{ __('Transaction Fee') }}</strong>
                                        <span class="currency_symbol"></span>
                                        <span class="transaction_fee_amount"></span>
                                    </p>
                                    <br>

                                    {!! \App\Helper\PaymentGatewayList::renderPaymentGatewayForForm(false) !!}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-profile btn-outline-gray btn-hover-danger"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Order Now')" :class="'btn-profile btn-bg-1'" />
                </div>
            </div>
        </form>
    </div>
</div>
