@extends('frontend.layout.master')
@section('site_title', __('Email Verify'))
@section('style')
    <style>
        .verify-form input {
            height: 50px;
            padding-left: 5px;
        }

        button.close {
            width: 30px;
            height: 30px;
            border: none;
            background: #000;
            color: #fff;
            border-radius: 3px;
            float: right;
            font-size: 20px;
        }

        .verify-form .verify-account {
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
@endsection
@section('content')
    <div class="signup-area pat-100 pab-100">
        <div class="container">
            <div class="signup-wrapper verify-account-wrapper">
                <div class="signup-contents">
                    <h3 class="signup-title"> {{ __('Verify Your Account') }} </h3>
                    <x-validation.error />
                    <div class="alert alert-info alert-bs-dismissible fade show mt-5 mb-1" role="alert">
                        {{ __('Please check email inbox/spam for verification code') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="verify-form" method="post" action="{{ route('email.verify') }}">
                        @csrf
                        <div class="single-input mt-4">
                            <label class="label-title mb-3">{{ __('Enter verification code*') }}</label>
                            <input class="form--control" type="text" name="email_verify_token"
                                placeholder="{{ __('Enter code') }}">
                        </div>
                        <button class="submit-btn mt-4 verify-account" type="submit">{{ __('Verify Account') }}</button>
                    </form>
                    <div class="resend-verify-code-wrap mt-3">
                        <a class="text-center"
                            href="{{ route('resend.verify.code') }}"><strong>{{ __('Resend Code') }}</strong></a>
                    </div>
                </div>
                <br>

            </div>
        </div>
    </div>
@endsection
