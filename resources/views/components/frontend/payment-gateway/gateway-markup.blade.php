@php $user_type = auth()->user()->user_type == 1 ? 'client' : 'freelancer' @endphp

<div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel" aria-hidden="true">
    <div class="modal-dialog ab">
        <form action="{{ route($user_type.'.'.'wallet.deposit') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="paymentGatewayModalLabel">{{ $title ?? '' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-form.text
                        :type="'number'"
                        :title="__('Enter Deposit Amount')"
                        :name="'amount'"
                        :id="'amount'"
                        :placeholder="__('Max Limit: '). get_static_option('deposit_amount_limitation_for_user') ?? '3000' " />
                    <div class="confirm-payment payment-border">
                        <div class="single-checkbox">
                            <div class="checkbox-inlines">
                                <label class="checkbox-label" for="check2">
                                    {!! \App\Helper\PaymentGatewayList::renderPaymentGatewayForForm(false) !!}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-profile btn-outline-gray btn-hover-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Deposit')" :class="'btn-profile btn-bg-1 deposit_amount_to_wallet'" />
                </div>
            </div>
        </form>
    </div>
</div>

