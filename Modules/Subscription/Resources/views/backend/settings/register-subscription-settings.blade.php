@extends('backend.layout.master')
@section('title', __('Register Subscription Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Register Subscription Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :class="'mt-5'" :description="__('Notice: When a new user register as a freelancer by default he/she will get the free subscription. Once it is complete or expired he will must buy subscription for sending job proposal or bid.')" />
                            <form action="{{route('admin.free.subscription.settings')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Make Free Subscription') }}</label>
                                    <select name="register_subscription" class="form-control">
                                        <option value="">{{ __('Select Subscription') }}</option>
                                        @foreach($subscriptions as $sub)
                                        <option value="{{ $sub->id }}" {{ get_static_option('register_subscription') == $sub->id ? 'selected' : '' }}>{{ $sub?->subscription_type?->type ?? '' }} - {{ $sub->title }}</option>
                                        @endforeach
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

@section('script')
    <x-media.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function () {

            });
        })(jQuery);
    </script>
@endsection
