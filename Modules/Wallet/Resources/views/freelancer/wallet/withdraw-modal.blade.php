<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('freelancer.wallet.withdraw.request') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h2 class="popup-contents-title"> {{ __('Withdraw Money') }} </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger"><strong>{{ __('Minimum: ') }}</strong> {{ float_amount_with_currency_symbol(get_static_option('minimum_withdraw_amount') ?? '') }}</p>
                    <p class="text-danger"><strong>{{ __('Maximum:') }}</strong> {{ float_amount_with_currency_symbol(get_static_option('maximum_withdraw_amount') ?? '') }}</p>
                    <div class="popup-contents-inner profile-border-top">
                        <div class="withdrawal-single-item">
                            <span class="withdrawal-single-item-subtitle">{{ __('Available balance') }}</span>
                            <h2 class="withdrawal-single-item-balance mt-2">{{ float_amount_with_currency_symbol($total_wallet_balance) ?? '' }}</h2>
                        </div>
                    </div>
                    <div class="popup-contents-form custom-form profile-border-top">
                        <div class="single-input">
                            <label class="label-title"> {{ __('Enter amount') }} </label>
                            <input type="number" name="amount" class="form--control" id="withdraw_request_amount" placeholder="00">
                        </div>
                        <div class="single-input">
                            <label class="label-title"> {{ __('Withdraw method') }} </label>
                            <select name="gateway_name" class="form-control gateway-name">
                                <option value="">{{ __("Select gateway") }}</option>
                                @foreach($withdraw_gateways as $gateway)
                                    <option  value="{{ $gateway->id }}" data-fields="{{ json_encode(unserialize($gateway->field)) }}">{{ $gateway->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="gateway-information-wrapper mt-3">

                        </div>

                    </div>

                    <div class="popup-contents-withdraw mt-4 fee_and_receive_amount_container d-none">
                        <div class="popup-contents-withdraw-item">
                            <div class="popup-contents-withdraw-flex">
                                <span class="popup-contents-withdraw-title">{{ __("Withdraw Fee") }}</span>
                                <h3 class="popup-contents-withdraw-price withdraw_fee_amount_for_each_transaction"></h3>
                            </div>
                        </div>
                        <div class="popup-contents-withdraw-item">
                            <div class="popup-contents-withdraw-flex">
                                <span class="popup-contents-withdraw-title">{{ __("Amount you'll receive") }}</span>
                                <h3 class="popup-contents-withdraw-price"><span class="receiveable_amount"></span></h3>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-profile btn-outline-gray btn-hover-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Submit Now')" :class="'btn-profile btn-bg-1 withdraw_amount_from_wallet'" />
                </div>
            </div>

        </form>
    </div>
</div>

