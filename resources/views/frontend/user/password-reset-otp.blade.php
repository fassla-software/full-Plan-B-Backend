@extends('frontend.layout.master')
@section('site_title',__('Password Reset'))
@section('style')
    <style>
        .verify-form input{
            height:50px;
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
        .verify-form .verify-account{
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
@endsection
@section('content')
    <!-- login Area Starts -->
    <section class="login-area pat-100 pab-100">
        <div class="container custom-container-one">
            <div class="login-wrapper">
                <div class="login-wrapper-contents margin-inline login-shadow login-padding">
                    <h2 class="single-title"> {{ __('Confirm Here!') }} </h2>
                    <x-validation.error />
                    <form class="login-wrapper-form custom-form" method="post" action="{{ route('user.forgot.password.otp') }}">
                        @csrf
                        <div class="single-input mt-4">
                            <label class="label-title mb-3"> {{ __('OTP') }} </label>
                            <input class="form--control" name="otp" type="text" placeholder="{{ __('Enter otp') }}">
                        </div>
                        <button class="submit-btn w-100 mt-4" type="submit"> {{ __('Submit Now') }} </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- login Area end -->
@endsection
