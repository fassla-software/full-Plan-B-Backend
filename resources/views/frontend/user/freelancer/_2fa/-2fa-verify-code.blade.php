@extends('frontend.layout.master')
@section('content')
    <!-- login Area Starts -->
    <section class="login-area pat-100 pab-100">
        <div class="container">
            <div class="row gy-5 align-items-center justify-content-between">
                <div class="offset-md-3 col-md-6">
                    <div class="login-wrapper">
                        <div class="login-wrapper-contents">
                            <h3 class="login-wrapper-contents-title">{{ __('Enter 2FA OTP Code ') }}</h3>
                            <span class="text-info">{{__('Please open your google authenticator app and enter the given OTP code')}}</span>
                            <x-validation.error/>
                            <div class="error-message"></div>
                            <form class="login-wrapper-contents-form custom-form signup-forms" method="post" action="{{ route('freelancer._2fa.verify.code') }}">
                                @csrf
                                <div class="single-input mt-4">
                                    <input class="form--control" type="text" name="one_time_password" id="one_time_password" placeholder="{{ __('Enter Verification Code') }}">
                                </div>
                                <x-btn.submit :title="__('Verify Account')" :class="'submit-btn w-100 mt-4'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- login Area end -->
@endsection


{{--todo register script--}}
@section('script')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                //
            });
        }(jQuery));
    </script>
@endsection









{{--<div class="signup-area padding-top-70 padding-bottom-100">--}}
{{--        <div class="container">--}}
{{--            <div class="signup-wrapper">--}}
{{--                <div class="signup-contents">--}}
{{--                    <h3 class="signup-title"> {{ __('Enter 2FA Security Code')}} </h3>--}}
{{--                    <x-msg.error/>--}}
{{--                    <x-session-msg/>--}}
{{--                    <div class="alert alert-info alert-dismissible fade show mt-5 mb-1" role="alert">--}}
{{--                        {{__('please open your google authenticator app and enter the given security code')}}--}}
{{--                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
{{--                            <span aria-hidden="true">&times;</span>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <form class="signup-forms" action="{{ route('client._2fa.verify.code')}}" method="post">--}}
{{--                        @csrf--}}
{{--                        <div class="single-signup margin-top-30">--}}
{{--                            <label class="signup-label"> {{__('Enter code*')}} </label>--}}
{{--                            <input class="form--control" type="text" name="one_time_password" placeholder="{{__('Enter code')}}">--}}
{{--                        </div>--}}

{{--                        <div class="btn-wrapper margin-top-50">--}}
{{--                            <button type="submit" class="cmn-btn btn-bg-1" >{{ __('Verify Account') }} </button>--}}
{{--                        </div>--}}

{{--                    </form>--}}
{{--                </div>--}}
{{--                <br>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

