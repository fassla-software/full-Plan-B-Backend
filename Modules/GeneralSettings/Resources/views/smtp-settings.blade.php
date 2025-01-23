@extends('backend.layout.master')
@section('title', __('SMTP Settings'))

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-7">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('SMTP Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.smtp')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="site_smtp_mail_mailer" class="label-title">{{__('SMTP Mailer')}}</label>
                                    <select name="site_smtp_mail_mailer" class="form-control">
                                        <option value="smtp" @if(get_static_option('site_smtp_mail_mailer') == 'smtp') selected @endif>{{__('SMTP')}}</option>
                                        <option value="sendmail" @if(get_static_option('site_smtp_mail_mailer') == 'sendmail') selected @endif>{{__('SendMail')}}</option>
                                        <option value="mailgun" @if(get_static_option('site_smtp_mail_mailer') == 'mailgun') selected @endif>{{__('Mailgun')}}</option>
                                        <option value="postmark" @if(get_static_option('site_smtp_mail_mailer') == 'postmark') selected @endif>{{__('Postmark')}}</option>
                                    </select>
                                </div>
                                <div class="single-input mb-3">
                                    <label for="site_smtp_mail_host" class="label-title">{{__('SMTP Mail Host')}}</label>
                                    <input type="text" name="site_smtp_mail_host"  class="form-control" value="{{get_static_option('site_smtp_mail_host')}}">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="site_smtp_mail_port" class="label-title">{{__('SMTP Mail Port')}}</label>
                                    <select name="site_smtp_mail_port" class="form-control">
                                        <option value="587" @if(get_static_option('site_smtp_mail_port') == '587') selected @endif>{{__('587')}}</option>
                                        <option value="465" @if(get_static_option('site_smtp_mail_port') == '465') selected @endif>{{__('465')}}</option>
                                    </select>
                                </div>
                                <div class="single-input mb-3">
                                    <label for="site_smtp_mail_username" class="label-title">{{__('SMTP Mail Username')}}</label>
                                    <input type="text" name="site_smtp_mail_username"  class="form-control" value="{{get_static_option('site_smtp_mail_username')}}" id="site_smtp_mail_username">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="site_smtp_mail_password" class="label-title">{{__('SMTP Mail Password')}}</label>
                                    <input type="password" name="site_smtp_mail_password"  class="form-control" value="{{get_static_option('site_smtp_mail_password')}}" id="site_smtp_mail_password">
                                    <span id="show_password"><i class="fa fa-eye"></i></span>
                                </div>
                                <div class="single-input mb-3">
                                    <label for="site_smtp_mail_encryption" class="label-title">{{__('SMTP Mail Encryption')}}</label>
                                    <select name="site_smtp_mail_encryption" class="form-control">
                                        <option value="ssl" @if(get_static_option('site_smtp_mail_encryption') == 'ssl') selected @endif>{{__('SSL')}}</option>
                                        <option value="tls" @if(get_static_option('site_smtp_mail_encryption') == 'tls') selected @endif>{{__('TLS')}}</option>
                                    </select>
                                </div>
                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('SMTP Test') }}</h4>

                        @if($errors->EmailSend->any())
                            <div class="alert alert-danger">
                                <ul class="list-none">
                                    <button type="button btn-sm" class="close" data-bs-dismiss="alert">×</button>
                                    @foreach($errors->EmailSend->all() as $error)
                                        <li> {{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.smtp.test')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="email" class="label-title">{{__('Email')}}</label>
                                    <input type="email" name="email"  class="form-control" >
                                </div>
                                <div class="single-input mb-3">
                                    <label for="subject" class="label-title">{{__('Subject')}}</label>
                                    <input type="text" name="subject" value="{{ __('Test Email Subject') }}" class="form-control" >
                                </div>
                                <div class="single-input mb-3">
                                    <label for="message" class="label-title">{{__('Message')}}</label>
                                    <textarea name="message" class="form-control" cols="30" rows="7">{{ __('Test Email Message') }}</textarea>
                                </div>
                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Send Mail')}}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on('click','#show_password',function(){
                    let password = $("#site_smtp_mail_password");
                    if (password.attr("type") === "password") {
                        password.attr("type","text")
                    } else {
                        password.attr("type","password")
                    }
                })
            })
        }(jQuery));
    </script>
@endsection
