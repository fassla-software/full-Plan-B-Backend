<div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('order.user.confirm') }}" method="post" enctype="multipart/form-data" id="prevent_multiple_order_submit">
            <input type="hidden" name="project_id" id="project_id_for_order">
            <input type="hidden" name="basic_standard_premium_type" id="basic_standard_premium_type">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="set_basic_standard_premium_type"></h3>
                </div>
                <div class="modal-body">
                    <x-notice.general-notice :description="__(
                        'Notice: Before create an order we encourage to contact the seller so that seller can not decline your order. Keep in mind your milestone price must be equal to original price',
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

                                @if(get_static_option('order_enable_disable_description_settings') != 'disable')
                                <div id="show_hide_description_btn" class="mt-2">
                                    <input type="checkbox" name="order_description_btn" id="order_description_btn">
                                    <strong>{{ __('Description') }}</strong>
                                </div>
                                @endif

                                <div class="description_wrapper d-none">
                                    <textarea name="order_description" id="order_description" rows="5" class="form-control mt-3"
                                        placeholder="{{ __('Enter a description') }}"></textarea>
                                </div>

                                @if(get_static_option('order_enable_disable_milestone_settings') != 'disable')
                                <div id="show_hide_milestone_btn" class="mt-3">
                                    <input type="checkbox" name="pay_by_milestone" id="pay_by_milestone">
                                    <strong class="">{{ __('Pay by Milestones') }}</strong>
                                    <span
                                        class="d-block mt-2">{{ __('Pay an amount you fixed when a portion of the whole project is completed') }}</span>
                                </div>
                                @endif

                                <div class="myJob-wrapper-single milestone_wrapper d-none">
                                    <div class="myJob-wrapper-single-header profile-border-bottom">
                                        <h4 class="myJob-wrapper-single-title">{{ __('Milestone') }}</h4>
                                    </div>
                                    <div class="myJob-wrapper-single-milestone milestone-contractor-parent">
                                        <div class="myJob-wrapper-single-milestone-item">
                                            <div class="myJob-wrapper-single-flex flex-between align-items-start">
                                                <div class="myJob-wrapper-single-contents">
                                                    <label>{{ __('Title') }}</label>
                                                    <input type="text" class="form-control" name="milestone_title[]"
                                                        placeholder="{{ __('Enter Title') }}">
                                                    <br>
                                                    <label>{{ __('Description') }}</label>
                                                    <textarea id="" cols="30" rows="5" class="form-control" name="milestone_description[]"
                                                        placeholder="{{ __('Enter Description') }}"></textarea>
                                                    <br>
                                                    <label>{{ __('Price') }}</label>
                                                    <input type="number" class="form-control" name="milestone_price[]"
                                                        placeholder="{{ __('Enter Price') }}">
                                                    <br>
                                                    <label>{{ __('Revision') }}</label>
                                                    <input type="number" min="1" max="100"
                                                        class="form-control" name="milestone_revision[]"
                                                        placeholder="{{ __('Enter Revision') }}">
                                                    <br>
                                                    <x-duration.delivery-time :class="'single-input'" :selectClass="'form-control set_dead_line'"
                                                        :title="__('Delivery Time')" :name="'milestone_deadline[]'" />
                                                </div>
                                            </div>
                                            <div class="btn-wrapper remove-milestone-contractor mt-4">
                                                <a href="#"
                                                    class="btn-profile btn-bg-cancel">{{ __('Remove') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-wrapper mt-4">
                                        <a href="javascript:void(0)"
                                            class="btn-profile btn-outline-gray add-contract-milestone"><i
                                                class="fa-solid fa-plus"></i> {{ __('Add Milestone') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-profile btn-outline-gray btn-hover-danger"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    @if (Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1)
                        <button type="submit" class="btn-profile btn-bg-1" id="order_now_only_for_load_spinner">{{ __('Order Now') }}<span id="order_create_load_spinner"></span></button>
                    @elseif(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Session('user_role') == 'client')
                        <button type="submit" class="btn-profile btn-bg-1" id="order_now_only_for_load_spinner">{{ __('Order Now') }}<span id="order_create_load_spinner"></span></button>
                    @else
                        <a href="{{ route('user.register') }}" target="_blank" class="btn-profile btn-bg-1">{{ __('Register as a client to continue') }}</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
