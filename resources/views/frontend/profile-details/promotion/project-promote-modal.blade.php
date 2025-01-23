<!-- Level Add Modal -->
<div class="modal fade" id="openProjectPromoteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 heading_title_for_promotion_modal" id="exampleModalLabel">{{ __('Promotion Project') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('freelancer.package.buy')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="set_package_budget" id="set_package_budget">
                <input type="hidden" name="set_project_id_for_promote" id="set_project_id_for_promote" value="0">
                <input type="hidden" name="transaction_fee" id="transaction_fee" value="0">
                @php
                    $all_package = \Modules\PromoteFreelancer\Entities\ProjectPromoteSettings::select('id','title','budget','duration')->where('status',1)->get();
                @endphp

                <div class="modal-body">
                    <div class="alert alert-warning mb-5" role="alert">
                        <p class="warning_for_promotion_modal">{{ __("Notice: Days refers to the number of days a freelancer project will be displayed in the promotional area after he buy a package.") }}</p>
                    </div>
                    <div class="single-input mb-3">
                        <label class="label-title mt-3">{{ __('Choose Package') }}</label>
                            <select id="get_package_budget" name="package_id" class="form-control get_package_budget">
                                <option>{{__('Choose Package')}}</option>
                                @foreach($all_package as $package)
                                    <option value="{{ $package->id }}" data-budget="{{ $package->budget }}">{{$package->title}} (<strong>{{ __('Price:') }}</strong>{{float_amount_with_currency_symbol($package->budget)}}/<strong>{{ __('Duration:') }}</strong>{{$package->duration}} {{ __('days') }})</option>
                                @endforeach
                            </select>
                    </div>
                    <label class="checkbox-label" for="choose">
                        @if (Auth::check() && Auth::user()->user_wallet?->balance > 0)
                            {!! \App\Helper\PaymentGatewayList::renderWalletForm() !!}
                            <span class="wallet-balance mt-2 d-block">{{ __('Wallet Balance:') }}
                                <strong class="main-balance">{{ float_amount_with_currency_symbol(Auth::user()->user_wallet?->balance) }}</strong>
                            </span>
                            <span class="display_wallet_shortage_balance py-3"></span>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button class="btn btn-primary mt-4 pr-4 pl-4 confirm_promote_project">{{ __('Promote Now') }} <span id="promote_project_load_spinner"></span></button>
                </div>
            </form>
        </div>
    </div>
</div>
