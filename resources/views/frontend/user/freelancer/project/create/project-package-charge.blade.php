<!-- Package & Charge Start -->
<div class="setup-wrapper-contents">
    <div class="create-project-wrapper-item">
        <div class="create-project-wrapper-item-top profile-border-bottom">
            <h4 class="create-project-wrapper-title"> {{ __('Package & Charge') }} </h4>
            <div class="custom_switch_wrapper d-flex align-items-center gap-2">
                <span>{{ __('Enable/Disable package') }}</span>
                <label class="custom_switch">
                    <input class="custom-switch" type="checkbox" name="offer_packages_available_or_not" id="offer_packages_available_or_not" value="1" checked>
                    <span class="switch-label slider round" for="offer_packages_available_or_not"></span>
                </label>
            </div>
        </div>
        <div class="package-contents">
            <div class="package-table">
                <table class="table table-bordered table-responsive create_project_table">
                    <thead>
                    <tr>
                        <th class="package-head">
                        </th>
                        <th class="package-head">
                            <div class="package-head-flex flex-between align-items-center">
                                <span class="package-head-title" id="basic_title">{{ __('Basic') }}</span>
                                <span class="package-head-edit"></span>
                            </div>
                        </th>
                        <th class="package-head">
                            <div class="package-head-flex flex-between align-items-center">
                                <span class="package-head-title" id="standard_title">{{ __('Standard') }}</span>
                                <span class="package-head-edit"></span>
                            </div>
                        </th>
                        <th class="package-head">
                            <div class="package-head-flex flex-between align-items-center">
                                <span class="package-head-title" id="premium_title">{{ __('Premium') }}</span>
                                <span class="package-head-edit"></span>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="add-rows-parent">
                    <tr>
                        <th>
                            <div class="package-head-left">
                                <span class="package-head-left-title">{{ __('Revisions') }}</span>
                            </div>
                        </th>
                        <td>
                            <div class="package-field">
                                <div class="package-select">
                                    <select class="form-control" name="basic_revision" id="basic_revision">
                                        @for($i = 1; $i<=10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                        <option value="1000">{{ __('Unlimited') }}</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="package-field">
                                <div class="package-select">
                                    <select class="form-control disabled_or_not" name="standard_revision" id="standard_revision">
                                        @for($i = 1; $i<=10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                        <option value="1000">{{ __('Unlimited') }}</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="package-field">
                                <div class="package-select">
                                    <select class="form-control disabled_or_not" name="premium_revision" id="premium_revision">
                                        @for($i = 1; $i<=10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                        <option value="1000">{{ __('Unlimited') }}</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class="package-head-left">
                                <span class="package-head-left-title">{{ __('Delivery time') }}</span>
                            </div>
                        </th>
                        @php $all_lengths = \App\Models\Length::where('status',1)->get() @endphp
                        @if($all_lengths->count() >= 1)
                            <td>
                                <div class="package-select">
                                    <select class="form-control" name="basic_delivery" id="basic_delivery">
                                        @foreach($all_lengths as $length)
                                            <option value="{{ $length->length }}">{{ $length->length }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td>
                                <select class="form-control disabled_or_not" name="standard_delivery" id="standard_delivery">
                                    @foreach($all_lengths as $length)
                                        <option value="{{ $length->length }}">{{ $length->length }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control disabled_or_not" name="premium_delivery" id="premium_delivery">
                                    @foreach($all_lengths as $length)
                                        <option value="{{ $length->length }}">{{ $length->length }}</option>
                                    @endforeach
                                </select>
                            </td>

                        @else
                            <td>
                                <div class="package-select">
                                    <select class="form-control" name="basic_delivery" id="basic_delivery">
                                        <option value="1 Days">{{ __('1 Days') }}</option>
                                        <option value="2 Days">{{ __('2 Days') }}</option>
                                        <option value="3 Days">{{ __('3 Days') }}</option>
                                        <option value="Less than a week">{{ __('Less than a Week') }}</option>
                                        <option value="Less than a month">{{ __('Less than a month') }}</option>
                                        <option value="Less than 2 month">{{ __('Less than 2 month') }}</option>
                                        <option value="Less than 3 month">{{ __('Less than 3 month') }}</option>
                                        <option value="More than 3 month">{{ __('More than 3 month') }}</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <select class="form-control disabled_or_not" name="standard_delivery" id="standard_delivery">
                                    <option value="1 Days">{{ __('1 Days') }}</option>
                                    <option value="2 Days">{{ __('2 Days') }}</option>
                                    <option value="3 Days">{{ __('3 Days') }}</option>
                                    <option value="Less than a week">{{ __('Less than a Week') }}</option>
                                    <option value="Less than a month">{{ __('Less than a month') }}</option>
                                    <option value="Less than 2 month">{{ __('Less than 2 month') }}</option>
                                    <option value="Less than 3 month">{{ __('Less than 3 month') }}</option>
                                    <option value="More than 3 month">{{ __('More than 3 month') }}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control disabled_or_not" name="premium_delivery" id="premium_delivery">
                                    <option value="1 Days">{{ __('1 Days') }}</option>
                                    <option value="2 Days">{{ __('2 Days') }}</option>
                                    <option value="3 Days">{{ __('3 Days') }}</option>
                                    <option value="Less than a week">{{ __('Less than a Week') }}</option>
                                    <option value="Less than a month">{{ __('Less than a month') }}</option>
                                    <option value="Less than 2 month">{{ __('Less than 2 month') }}</option>
                                    <option value="Less than 3 month">{{ __('Less than 3 month') }}</option>
                                    <option value="More than 3 month">{{ __('More than 3 month') }}</option>
                                </select>
                            </td>
                        @endif
                    </tr>
                    <tr class="append-include">
                        <th>
                            <div class="package-head-left">
                                <div class="package-head-left-flex flex-column">
                                    <input class="form-control checkbox_or_numeric_title" type="text" name="checkbox_or_numeric_title[]" placeholder="{{ __('Enter Title') }}">
                                    <div class="text-danger validation-error"></div>
                                </div>
                                <div class="package-field">
                                    <div class="package-field-select">
                                        <select class="form-control checkbox_or_numeric_select" name="checkbox_or_numeric_select[]">
                                            <option value="checkbox">{{ __('Check Boxes') }}</option>
                                            <option value="numeric">{{ __('Numeric') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </th>

                        <td>
                            <input name="title[basic]" type="checkbox" class="check-input" value="on" checked>
                        </td>
                        <td>
                            <input name="title[standard]" type="checkbox" class="check-input disabled_or_not" value="on" checked>
                        </td>
                        <td>
                            <input name="title[premium]" type="checkbox" class="check-input disabled_or_not" value="on" checked>
                            <div class="package-button-wrapper">
                                <div class="package-field-icon add-rows">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                                <div class="package-field-icon remove-rows remove-icon">
                                    <i class="fa-solid fa-minus"></i>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="delivery_charge_amount">
                        <th>
                            <div class="package-head-left">
                                <div class="package-head-left-flex">
                                    <span class="package-head-left-title">{{ __('Charges') }}</span>
                                </div>
                            </div>
                        </th>
                        <td>
                            <div class="package-field">
                                <div class="package-field-price">
                                    <div class="package-field-price-flex flex-between">
                                        <div class="package-field-price-main">
                                            <h5 class="package-field-price-main-title">
                                                <span class="basic_regular_charge">{{ float_amount_with_currency_symbol(50) }}</span>
                                                <span class="basic_discount_charge"><s>{{ float_amount_with_currency_symbol(40) }}</s></span>
                                            </h5>
                                        </div>
                                        <div class="package-field-price-edit click-edit-basic-price">
                                            <img src="{{ asset('assets/static/icons/edit_color.svg') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="package-field">
                                <div class="package-field-price">
                                    <div class="package-field-price-flex flex-between">
                                        <div class="package-field-price-main">
                                            <h5 class="package-field-price-main-title">
                                                <span class="standard_regular_charge">{{ float_amount_with_currency_symbol(60) }}</span>
                                                <span class="standard_discount_charge"><s>{{ float_amount_with_currency_symbol(50) }}</s></span>
                                            </h5>
                                        </div>
                                        <div class="package-field-price-edit click-edit-standard-price">
                                            <img src="{{ asset('assets/static/icons/edit_color.svg') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="package-field">
                                <div class="package-field-price">
                                    <div class="package-field-price-flex flex-between">
                                        <div class="package-field-price-main">
                                            <h5 class="package-field-price-main-title">
                                                <span class="premium_regular_charge">{{ float_amount_with_currency_symbol(70) }} </span>
                                                <span class="premium_discount_charge"><s>{{ float_amount_with_currency_symbol(60) }}</s></span>
                                            </h5>
                                        </div>
                                        <div class="package-field-price-edit click-edit-premium-price">
                                            <img src="{{ asset('assets/static/icons/edit_color.svg') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="popup-overlay"></div>

<!-- Basic Popup start -->
<div class="popup-fixed price-popup-basic-charge">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title">{{ __('Set Charge') }}</h2>
        <div class="popup-contents-form custom-form">
            <div class="single-input single-input-icon">
                <label class="label-title"> {{ __('Regular Charge') }} </label>
                <input type="number" name="basic_regular_charge" id="basic_regular_charge" class="form--control" value="50">
                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
            </div>
            <div class="single-input single-input-icon">
                <label class="label-title">{{ __('Discount Charge(Optional)') }}</label>
                <input type="number" name="basic_discount_charge" id="basic_discount_charge" class="form--control" value="40">
                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
            </div>
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }}</a>
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 basic_price_setup">{{ __('Set Price') }}</a>
        </div>
    </div>
</div>

<!-- Standard Popup start -->
<div class="popup-fixed price-popup-standard-charge">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title"> {{ __('Set Charge') }} </h2>
        <div class="popup-contents-form custom-form">
            <div class="single-input single-input-icon">
                <label class="label-title"> {{ __('Regular Charge') }} </label>
                <input type="text" name="standard_regular_charge" id="standard_regular_charge" class="form--control" value="60">
                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
            </div>
            <div class="single-input single-input-icon">
                <label class="label-title"> {{ __('Discount Charge(Optional)') }} </label>
                <input type="text" name="standard_discount_charge" id="standard_discount_charge" class="form--control" value="50">
                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
            </div>
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }}</a>
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 standard_price_setup">{{ __('Set Price') }}</a>
        </div>
    </div>
</div>

<!-- Premium Popup start -->
<div class="popup-fixed price-popup-premium-charge">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title">{{ __('Set Charge') }}</h2>
        <div class="popup-contents-form custom-form">
            <div class="single-input single-input-icon">
                <label class="label-title">{{ __('Regular Charge') }}</label>
                <input type="text" name="premium_regular_charge" id="premium_regular_charge" class="form--control" value="70">
                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
            </div>
            <div class="single-input single-input-icon">
                <label class="label-title">{{ __('Discount Charge(Optional)') }}</label>
                <input type="text" name="premium_discount_charge" id="premium_discount_charge" class="form--control" value="60">
                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
            </div>
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }}</a>
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 premium_price_setup">{{ __('Set Price') }}</a>
        </div>
    </div>
</div>
<!-- Package & Charge Ends -->
