@extends('backend.layout.master')
@section('title', __('Enable Disable Orders'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Enable Disable Orders') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :class="'mt-5'" :description="__('Notice: If you didn\'t want to create order if payment process failed keep this disable')" />
                            <form action="{{route('admin.order.enable.disable.settings')}}" method="post">
                                @csrf
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Enable/Disable Payment Failed Orders') }}</label>
                                    <select name="payment_failed_order_enable_disable" class="form-control">
                                        <option value="">{{ __('Enable-Disable') }}</option>
                                        <option value="enable" @if(get_static_option('payment_failed_order_enable_disable') == 'enable') selected @endif>{{ __('Enable') }}</option>
                                        <option value="disable" @if(get_static_option('payment_failed_order_enable_disable') == 'disable') selected @endif>{{ __('Disabled') }}</option>
                                    </select>
                                </div>
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
