@extends('backend.layout.master')
@section('title', __('Order Auto Complete Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Order Auto Complete Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice
                                    :class="'mt-5'"
                                    :description="__('Notice: Auto approval settings means the order will complete automatically if client don\'t do any action within the setup days')"
                                    :description2="__('Notice: If you do not set any option by default order will complete automatically after 30 days.')"
                            />
                            <form action="{{route('admin.order.approval.settings')}}" method="POST">
                                @csrf
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Select Auto Approval Days') }}</label>
                                    <select name="order_auto_approval" class="form-control">
                                        <option value="" selected>{{ __('Select One') }}</option>
                                        <option value="3" @if(get_static_option('order_auto_approval') == 3) selected @endif>{{ __('3 Days') }}</option>
                                        <option value="7" @if(get_static_option('order_auto_approval') == 7) selected @endif>{{ __('7 Days') }}</option>
                                        <option value="10" @if(get_static_option('order_auto_approval') == 10) selected @endif>{{ __('10 Days') }}</option>
                                        <option value="15" @if(get_static_option('order_auto_approval') == 15) selected @endif>{{ __('15 Days') }}</option>
                                        <option value="20" @if(get_static_option('order_auto_approval') == 20) selected @endif>{{ __('20 Days') }}</option>
                                        <option value="30" @if(get_static_option('order_auto_approval') == 30) selected @endif>{{ __('30 Days') }}</option>
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
