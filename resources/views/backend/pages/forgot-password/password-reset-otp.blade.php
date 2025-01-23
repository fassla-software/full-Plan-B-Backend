@extends('layouts.login-screens')
@section('content')
    <div class="login-area">
        <div class="container">
            <div class="login-box ptb--100 d-flex">
                <form method="POST" action="{{ route('admin.forgot.password.otp') }}">
                    @csrf
                    <div class="login-form-body">

                        <h2 class="single-title"> {{ __('Confirm Here!') }} </h2>
                        <x-validation.error />

                        <div class="form-gp focused">
                            <label class="label-title mb-3"> {{ __('OTP') }} </label>
                            <input class="form--control" name="otp" type="text" placeholder="{{ __('Enter otp') }}">
                        </div>
                        <div class="submit-btn-area">
                            <button type="submit">{{__('Submit')}} <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection