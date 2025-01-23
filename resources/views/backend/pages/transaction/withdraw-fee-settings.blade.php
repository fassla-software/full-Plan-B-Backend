@extends('backend.layout.master')
@section('title', __('Transaction Fee Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Withdraw Fee Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice
                                :class="'mt-5'"
                                :description="__('Notice: Withdraw fee means how much amount will user pay for each withdraw. Generally no fee will be applicable if you set withdraw fee 0.')" />
                            <form action="{{route('admin.withdraw.fee.settings')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Withdraw Fee Settings') }}</label>
                                    <select name="withdraw_fee_type" class="form-control">
                                        <option value="" selected>{{ __('Select Type') }}</option>
                                        <option value="percentage" @if(get_static_option('withdraw_fee_type') == 'percentage') selected @endif>{{ __('Percentage') }}</option>
                                        <option value="fixed" @if(get_static_option('withdraw_fee_type') == 'fixed') selected @endif>{{ __('Fixed') }}</option>
                                    </select>
                                </div>
                                <x-form.number :title="__('Withdraw Fee')" :min="'0.0'" :name="'withdraw_fee'" :value="get_static_option('withdraw_fee') ?? 0 " :placeholder="__('Withdraw Fee')"/>
                                @can('withdraw-fee-settings-update')
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
