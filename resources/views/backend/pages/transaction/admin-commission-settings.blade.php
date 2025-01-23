@extends('backend.layout.master')
@section('title', __('Admin Commission Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Admin Commission Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :class="'mt-5'" :description="__('Notice: Commission Settings means how much percentage will admin get per completed order. Generally admin will get 25 percent each order if he/she don\'t set any commission.')" />
                            <form action="{{route('admin.commission.settings')}}" method="post">
                                @csrf
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Commission Type') }}</label>
                                    <select name="admin_commission_type" class="form-control">
                                        <option value="">{{ __('Select Type') }}</option>
                                        <option value="percentage" @if(get_static_option('admin_commission_type') == 'percentage') selected @endif>{{ __('Percentage') }}</option>
                                        <option value="fixed" @if(get_static_option('admin_commission_type') == 'fixed') selected @endif>{{ __('Fixed') }}</option>
                                    </select>
                                </div>
                                <x-form.number :title="__('Commission Charge')" :min="'1'" :max="'500'" :step="'0.01'" :name="'admin_commission_charge'" :value="get_static_option('admin_commission_charge') ?? 25 " :placeholder="__('Commission Charge')"/>
                                @can('admin-commission-settings-update')
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
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
