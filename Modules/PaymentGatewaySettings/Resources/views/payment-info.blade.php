@extends('backend.layout.master')

@section('title', __('Payment Info Settings'))

@section('style')
    <x-media.css/>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Payment Info Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.payment.settings.info')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input">
                                    <label for="site_global_currency" class="label-title mt-3">{{__('Site Global Currency')}}</label>
                                    <select name="site_global_currency" class="form-control" id="site_global_currency">
                                        @foreach(getAllCurrency() as $cur => $symbol)
                                            <option value="{{$cur}}"
                                                    @if(get_static_option('site_global_currency') == $cur) selected @endif
                                            >
                                                {{$cur.' ( '.$symbol.' )'}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="single-input">
                                    <label for="enable_disable_decimal_point" class="label-title mt-3">{{__('Enable/Disable Decimal Point')}}</label>
                                    <select name="enable_disable_decimal_point" class="form-control" id="enable_disable_decimal_point">
                                        <option value="enable" @if(get_static_option('enable_disable_decimal_point') == 'enable') selected @endif>{{ __('Enable') }}</option>
                                        <option value="disable" @if(get_static_option('enable_disable_decimal_point') == 'disable') selected @endif>{{ __('Disable') }}</option>
                                    </select>
                                </div>

                                <div class="single-input">
                                    <label for="site_currency_symbol_position" class="label-title mt-3">{{__('Currency Symbol Position')}}</label>
                                    @php $all_currency_position = ['left','right']; @endphp
                                    <select name="site_currency_symbol_position" class="form-control" id="site_currency_symbol_position">
                                        @foreach($all_currency_position as $cur)
                                            <option value="{{$cur}}"
                                                    @if(get_static_option('site_currency_symbol_position') == $cur) selected @endif>{{ucwords($cur)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="single-input">
                                    <label for="site_currency_thousand_separator" class="label-title mt-3">{{__('Currency Thousand Separator')}}</label>
                                    <input type="text" class="form-control"
                                           name="site_currency_thousand_separator"
                                           value="{{get_static_option('site_currency_thousand_separator')}}">
                                </div>
                                <div class="single-input">
                                    <label for="site_currency_decimal_separator" class="label-title mt-3">{{__('Currency Decimal Separator')}}</label>
                                    <input type="text" class="form-control"
                                           name="site_currency_decimal_separator"
                                           value="{{get_static_option('site_currency_decimal_separator')}}">
                                </div>
                                <div class="single-input">
                                    <label for="site_default_payment_gateway" class="label-title mt-3">{{__('Default Payment Gateway')}}</label>
                                    <select name="site_default_payment_gateway" class="form-control" >
                                        @php
                                            $all_gateways = \App\Helper\PaymentGatewayList::listOfPaymentGateways();
                                        @endphp
                                        @foreach($all_gateways as $gateway)
                                            @if(!empty(get_static_option($gateway.'_gateway')))
                                                <option value="{{$gateway}}" @if(get_static_option('site_default_payment_gateway') == $gateway) selected @endif>{{ucwords(str_replace('_',' ',$gateway))}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @php $global_currency = strtolower(get_static_option('site_global_currency')) ?? '';@endphp

                                @if($global_currency != 'USD')
                                    <div class="single-input">
                                        <label for="site_{{strtolower($global_currency)}}_to_usd_exchange_rate" class="label-title mt-3">{{__($global_currency.' to USD Exchange Rate')}}</label>
                                        <input type="text" class="form-control"
                                               name="site_{{strtolower($global_currency)}}_to_usd_exchange_rate"
                                               value="{{get_static_option('site_'.$global_currency.'_to_usd_exchange_rate')}}">
                                        <span class="info-text">{{sprintf(__('enter %s to USD exchange rate. eg: 1 %s = ? USD'),$global_currency,$global_currency) }}</span>
                                    </div>
                                @endif

                                @if($global_currency != 'BDT')
                                    <div class="single-input">
                                        <label for="site_{{strtolower($global_currency)}}_to_bdt_exchange_rate" class="label-title mt-3">{{__($global_currency.' to BDT Exchange Rate')}}</label>
                                        <input type="text" class="form-control"
                                               name="site_{{strtolower($global_currency)}}_to_bdt_exchange_rate"
                                               value="{{get_static_option('site_'.$global_currency.'_to_bdt_exchange_rate')}}">
                                        <span class="info-text">{{sprintf(__('enter %s to USD exchange rate. eg: 1 %s = ? BDT'),$global_currency,$global_currency) }}</span>
                                    </div>
                                @endif

                                @if($global_currency != 'IDR')
                                    <div class="single-input">
                                        <label for="site_{{strtolower($global_currency)}}_to_idr_exchange_rate" class="label-title mt-3">{{__($global_currency.' to IDR Exchange Rate')}}</label>
                                        <input type="text" class="form-control"
                                               name="site_{{strtolower($global_currency)}}_to_idr_exchange_rate"
                                               value="{{get_static_option('site_'.$global_currency.'_to_idr_exchange_rate')}}">
                                        <span class="info-text">{{sprintf(__('enter %s to USD exchange rate. eg: 1 %s = ? IDR'),$global_currency,$global_currency) }}</span>
                                    </div>
                                @endif

                                @if($global_currency != 'INR' && !empty(get_static_option('paytm_gateway') || !empty(get_static_option('razorpay_gateway'))))
                                    <div class="single-input">
                                        <label for="site_{{strtolower($global_currency)}}_to_inr_exchange_rate" class="label-title mt-3">{{__($global_currency.' to INR Exchange Rate')}}</label>
                                        <input type="text" class="form-control"
                                               name="site_{{strtolower($global_currency)}}_to_inr_exchange_rate"
                                               value="{{get_static_option('site_'.$global_currency.'_to_inr_exchange_rate')}}">
                                        <span class="info-text">{{__('enter '.$global_currency.' to INR exchange rate. eg: 1'.$global_currency.' = ? INR')}}</span>
                                    </div>
                                @endif

                                @if($global_currency != 'NGN' && !empty(get_static_option('paystack_gateway') ))
                                    <div class="single-input">
                                        <label for="site_{{strtolower($global_currency)}}_to_ngn_exchange_rate" class="label-title mt-3">{{__($global_currency.' to NGN Exchange Rate')}}</label>
                                        <input type="text" class="form-control"
                                               name="site_{{strtolower($global_currency)}}_to_ngn_exchange_rate"
                                               value="{{get_static_option('site_'.$global_currency.'_to_ngn_exchange_rate')}}">
                                        <span class="info-text">{{__('enter '.$global_currency.' to NGN exchange rate. eg: 1'.$global_currency.' = ? NGN')}}</span>
                                    </div>
                                @endif

                                @if($global_currency != 'ZAR')
                                    <div class="single-input">
                                        <label for="site_{{strtolower($global_currency)}}_to_zar_exchange_rate" class="label-title mt-3">{{__($global_currency.' to ZAR Exchange Rate')}}</label>
                                        <input type="text" class="form-control"
                                               name="site_{{strtolower($global_currency)}}_to_zar_exchange_rate"
                                               value="{{get_static_option('site_'.$global_currency.'_to_zar_exchange_rate')}}">
                                        <span class="info-text">{{sprintf(__('enter %s to USD exchange rate. eg: 1 %s = ? ZAR'),$global_currency,$global_currency) }}</span>
                                    </div>
                                @endif

                                @if($global_currency != 'BRL')
                                    <div class="single-input">
                                        <label for="site_{{strtolower($global_currency)}}_to_brl_exchange_rate" class="label-title mt-3">{{__($global_currency.' to BRL Exchange Rate')}}</label>
                                        <input type="text" class="form-control"
                                               name="site_{{strtolower($global_currency)}}_to_brl_exchange_rate"
                                               value="{{get_static_option('site_'.$global_currency.'_to_brl_exchange_rate')}}">
                                        <span class="info-text">{{__('enter '.$global_currency.' to BRL exchange rate. eg: 1'.$global_currency.' = ? BRL')}}</span>
                                    </div>
                                @endif

                                @if($global_currency != 'MYR')
                                    <div class="single-input">
                                        <label for="site_{{strtolower($global_currency)}}_to_myr_exchange_rate" class="label-title mt-3">{{__($global_currency.' to MYR Exchange Rate')}}</label>
                                        <input type="text" class="form-control"
                                               name="site_{{strtolower($global_currency)}}_to_myr_exchange_rate"
                                               value="{{get_static_option('site_'.$global_currency.'_to_myr_exchange_rate')}}">
                                        <span class="info-text">{{__('enter '.$global_currency.' to MYR exchange rate. eg: 1'.$global_currency.' = ? MYR')}}</span>
                                    </div>
                                @endif

                                @can('payment-info-settings')
                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                                @endcan
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')
    <x-media.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function () {
                <x-btn.update/>
                <x-icon-picker.icon-picker/>
            });
        })(jQuery);
    </script>
@endsection
