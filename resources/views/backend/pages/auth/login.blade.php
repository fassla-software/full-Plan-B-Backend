@extends('layouts.login-screens')

@section('content')
    <div class="login-area">
        <div class="container">
            <div class="login-box ptb--100">
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="login-form-head">
                        <div class="logo-wrapper" style="margin-bottom: 40px;">
                            {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                        </div>
                        <h4>{{__('Admin Login')}}</h4>
                        <p>{{__('Hello there, Sign in and start managing your website')}}</p>
                    </div>
                    <div class="login-form-inner">
                        <div class="error-message"></div>
                    </div>
                    <div class="login-form-body">
                        <div class="form-gp focused">
                            <label for="username">{{__('Username or Email')}}</label>
                            <input type="text" id="username" name="username">
                            <i class="fa-solid fa-envelope"></i>
                        </div>

                        <div class="form-gp focused">
                            <label for="password">{{__('Password')}}</label>
                            <input type="password" id="password" name="password" >
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div class="row mb-4 rmber-area">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                                    <label class="custom-control-label" for="remember">{{__('Remember Me')}}</label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('admin.forgot.password') }}" class="forgot-password">{{__('Forgot Password?')}}</a>
                            </div>
                        </div>
                        <div class="submit-btn-area">
                            <button id="form_submit" type="submit">{{__('Login')}} <i class="fa-solid fa-arrow-right"></i></button>
                        </div>

                        {{--login only for demo start--}}
                        @if(url()->current() == 'https://xilancer.xgenious.com/admin')
                            <div class="adminlogin-info">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th>{{ __('Username') }}</th>
                                        <th>{{ __('Password') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    <tr>
                                        <td id="td_username">super_admin</td>
                                        <td id="td_password">12345678</td>
                                        <td><button type="button" class="autoLogin" id="autoLogin">{{ __('Login') }}</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        {{--login only for demo end--}}

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        (function($){
        "use strict";

            $(document).ready(function ($){
                 $(document).on('click','#autoLogin',function(){
                   let el = $(this);
                   let username = $('#td_username').text();
                   let passwrod = $('#td_password').text();
                   $('#username').val(username);
                   $('#password').val(passwrod);
                   $('#form_submit').trigger('click');
                });

                $(document).on('click','#form_submit',function (e){
                    e.preventDefault();
                    var el = $(this);
                    var erContainer = $(".error-message");
                    erContainer.html('');
                    el.text('{{__('Please Wait..')}}');
                    $.ajax({
                        url: "{{route('admin.login')}}",
                        type: "POST",
                        data: {
                            _token : "{{csrf_token()}}",
                            username : $('#username').val(),
                            password : $('#password').val(),
                            remember : $('#remember').val(),
                        },
                        error:function(data){
                            var errors = data.responseJSON;
                            erContainer.html('<div class="alert alert-danger"></div>');
                            $.each(errors.errors, function(index,value){
                                erContainer.find('.alert.alert-danger').append('<p>'+value+'</p>');
                            });
                            el.text('{{__('Login')}}');
                        },
                        success:function (data){
                            $('.alert.alert-danger').remove();
                            if (data.status == 'ok'){
                                el.text('{{__('Redirecting')}}..');
                                erContainer.html('<div class="alert alert-'+data.type+'">'+data.msg+'</div>');
                                window.location = "{{route('admin.dashboard')}}";
                            }else{
                                erContainer.html('<div class="alert alert-'+data.type+'">'+data.msg+'</div>');
                                el.text('{{__('Login')}}');
                            }
                        }
                    });
                });

            });
        })(jQuery);
    </script>
@endsection
