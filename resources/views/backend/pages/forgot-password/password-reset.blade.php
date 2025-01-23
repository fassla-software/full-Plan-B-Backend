@extends('layouts.login-screens')
@section('content')
    <div class="login-area">
        <div class="container">
            <div class="login-box ptb--100 d-flex">
                <form method="POST" action="{{ route('admin.forgot.password.reset') }}">
                    @csrf
                    <div class="login-form-body">

                        <h2 class="single-title"> {{ __('Password Reset!') }} </h2>
                        <x-validation.error />

                        <div class="form-gp focused">
                            <label class="label-title mb-3"> {{ __('New Password') }} </label>
                            <input class="form--control" name="password" type="text" placeholder="{{ __('Enter new password') }}">
                        </div>
                        <div class="form-gp focused">
                            <label class="label-title mb-3"> {{ __('Confirm New Password') }} </label>
                            <input class="form--control" name="confirm_password" type="text" placeholder="{{ __('Confirm new password') }}">
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


{{--@extends('layouts.login-screens')--}}
{{--@section('content')--}}
{{--    <!-- login Area Starts -->--}}
{{--    <section class="login-area pat-100 pab-100">--}}
{{--        <div class="container custom-container-one">--}}
{{--            <div class="login-wrapper">--}}
{{--                <div class="login-wrapper-contents margin-inline login-shadow login-padding">--}}
{{--                    <h2 class="single-title"> {{ __('Password Reset!') }} </h2>--}}
{{--                    <x-validation.error />--}}
{{--                    <form class="login-wrapper-form custom-form" method="post" action="{{ route('admin.forgot.password.reset') }}">--}}
{{--                        @csrf--}}
{{--                        <div class="single-input mt-4">--}}
{{--                            <label class="label-title mb-3"> {{ __('New Password') }} </label>--}}
{{--                            <input class="form--control" name="password" type="text" placeholder="{{ __('Enter new password') }}">--}}
{{--                        </div>--}}
{{--                        <div class="single-input mt-4">--}}
{{--                            <label class="label-title mb-3"> {{ __('Confirm New Password') }} </label>--}}
{{--                            <input class="form--control" name="confirm_password" type="text" placeholder="{{ __('Confirm new password') }}">--}}
{{--                        </div>--}}
{{--                        <button class="submit-btn w-100 mt-4" type="submit"> {{ __('Submit Now') }} </button>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <!-- login Area end -->--}}
{{--@endsection--}}
