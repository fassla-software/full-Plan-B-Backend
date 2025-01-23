<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Toastr Css -->

    <link rel="stylesheet" href="{{asset('assets/common/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/icon.min.css')}}">

    <!-- favicon -->
    @php
        $site_favicon = get_attachment_image_by_id(get_static_option('site_favicon'),"full",false);
    @endphp
    @if (!empty($site_favicon))
        <link rel="icon" href="{{$site_favicon['img_url'] ?? ''}}" sizes="40x40" type="icon/png">
    @endif
    <title>{{ __('Admin Login') }}</title>


    <style>
        .login-area {
            background: #F3F8FB;
        }

        .login-box {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            min-height: 100vh;
        }

        .login-box form {
            margin: auto;
            width: 450px;
            max-width: 100%;
            background: #fff;
            border-radius: 3px;
            box-shadow: 0 0 10px #f1f1f1;
        }
        .login-form-inner {
            padding-inline: 20px;
        }

        .login-form-head {
            padding: 30px 30px 0;
        }

        .login-form-head h4 {
            letter-spacing: 0;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 7px;
            color: #111;
        }

        .login-form-head p {
            color: #777;
            font-size: 14px;
            line-height: 22px;
        }

        .login-form-body {
            padding: 30px;
        }

        .form-gp {
            margin-bottom: 25px;
            position: relative;
        }
        .form-gp.focused:not(:first-child) {
            margin-top: 50px;
        }

        .form-gp label {
            position: absolute;
            left: 0;
            top: 0;
            color: #777;
            -webkit-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }

        .form-gp.focused label {
            top: -25px;
            color: #777;
        }

        .form-gp input {
            width: 100%;
            height: 30px;
            border: none;
            border-bottom: 1px solid #e6e6e6;
            background: none;
        }

        .form-gp.focused input {
            border-color: #ddd;
        }

        .form-gp input:focus {
            outline: none;
            background: none;
        }

        .form-gp input::-webkit-input-placeholder {
            color: #dad7d7;
        }

        .form-gp input::-moz-placeholder {
            color: #dad7d7;
        }

        .form-gp input:-ms-input-placeholder {
            color: #dad7d7;
        }

        .form-gp input:-moz-placeholder {
            color: #dad7d7;
        }

        .form-gp i {
            position: absolute;
            right: 5px;
            color: #999;
            font-size: 16px;
            top: 8px;
        }

        .form-gp.has-error,
        .form-gp.has-error label,
        .form-gp.has-error input,
        .form-gp.has-error input::placeholder,
        .form-gp.has-error i {
            color: var(--red);
        }

        .rmber-area {
            font-size: 13px;
        }

        .submit-btn-area {
            text-align: center;
        }

        .submit-btn-area button {
            width: 100%;
            height: 50px;
            border: none;
            background: #6176f6;
            color: #fff;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0;
            font-weight: 600;
            font-size: 12px;
            box-shadow: 0 0 22px rgba(0, 0, 0, 0.07);
            -webkit-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }

        .submit-btn-area button:hover {
            background: #4d65ff;
            color: #ffffff;
        }

        .submit-btn-area button i {
            margin-left: 15px;
            -webkit-transition: margin-left 0.3s ease 0s;
            transition: margin-left 0.3s ease 0s;
        }

        .submit-btn-area button:hover i {
            margin-left: 20px;
        }

        .login-other a {
            display: block;
            width: 100%;
            max-width: 250px;
            height: 43px;
            line-height: 43px;
            border-radius: 40px;
            text-transform: capitalize;
            letter-spacing: 0;
            font-weight: 600;
            font-size: 12px;
            box-shadow: 0 0 22px rgba(0, 0, 0, 0.07);
        }

        .login-other a i {
            margin-left: 5px;
        }

        .login-other a.fb-login {
            background: #8655FC;
            color: #fff;
        }

        .login-other a.fb-login:hover {
            box-shadow: 0 5px 15px rgba(44, 113, 218, 0.38);
        }

        .login-other a.google-login {
            background: #fb5757;
            color: #fff;
        }

        .login-other a.google-login:hover {
            box-shadow: 0 5px 15px rgba(251, 87, 87, 0.38);
        }

        .form-footer a {
            margin-left: 5px;
        }


        /* login-s2 */

        .login-s2 {
            background: #fff;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .login-s2:before {
            content: '';
            position: absolute;
            height: 206%;
            width: 97%;
            background: #fcfcff;
            border-radius: 50%;
            left: -42%;
            z-index: -1;
            top: -47%;
            box-shadow: inset 0 0 51px rgba(0, 0, 0, 0.1);
        }

        .login-s2 .login-form-head,
        .login-s2 .login-box form,
        .login-s2 .login-box form .form-gp input {
            background: transparent;
        }

        .login-s2 .login-form-head h4,
        .login-s2 .login-form-head p {
            color: #444;
        }


        /* login-s3 */

        .login-bg {
            background: url(../images/bg/singin-bg.jpg) center/cover no-repeat;
            position: relative;
            z-index: 1;
        }

        .login-bg:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            z-index: -1;
            height: 100%;
            width: 100%;
            background: #272727;
            opacity: 0.7;
        }


        /* register 4 page */

        .login-box-s2 {
            min-height: 100vh;
            background: #f9f9f9;
            width: 100%;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .login-box-s2 form {
            margin: auto;
            background: #fff;
            width: 100%;
            max-width: 500px;
        }

        .adminlogin-info {
            margin-top: 40px;
            display: block;
            width: 100%;
        }

        .adminlogin-info table {
            width: 100%;
        }

        .adminlogin-info table th,
        .adminlogin-info table td {
            font-size: 14px;
            font-weight: 700;
            padding: 10px;
        }

        button#autoLogin {
            border: none;
            padding: 5px 10px;
            border-radius: 2px;
            background-color: #439c43;
            color: #fff;
            transition: all 300ms;
        }

        button#autoLogin:hover {
            opacity: .7;
        }

        .forgot-password {
            color: #6176f6;
            font-size: 14px;
            text-decoration: unset;
        }

        .custom-control-label {
            font-size: 15px;
            color: #333;
        }
        .login-area .logo-wrapper img {
            max-width: 180px;
            margin: 0 auto;
        }

        .login-area .logo-wrapper {
            text-align: center;
        }
    </style>

</head>

<body>
    @yield('content')
    <!-- jquery latest version -->
    <script src="{{ asset('assets/common/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Toastr js -->
    <script src="{{ asset('assets/common/js/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}

    <script>
        (function($){
            "use strict";
            //login form
            $('.form-gp input').on('focus', function() {
                $(this).parent('.form-gp').addClass('focused');
            });
            $('.form-gp input').on('focusout', function() {
                if ($(this).val().length === 0) {
                    $(this).parent('.form-gp').removeClass('focused');
                }
            });
        }(jQuery));
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
