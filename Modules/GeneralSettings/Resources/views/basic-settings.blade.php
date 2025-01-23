@extends('backend.layout.master')

@section('title', __('Basic Settings'))

@section('style')
    <x-media.css/>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Basic Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.basic')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="site_title" class="label-title mb-1">{{__('Site Title')}}</label>
                                    <input type="text" id="site_title" name="site_title"  class="form-control" value="{{get_static_option('site_title')}}" id="site_title">
                                </div>

                                <div class="single-input mb-3">
                                    <label for="site_tag_line" class="label-title mb-1">{{__('Site Tag Line')}}</label>
                                    <input type="text" id="site_tag_line" name="site_tag_line"  class="form-control" value="{{get_static_option('site_tag_line')}}" id="site_tag_line">
                                </div>

                                <div class="single-input mb-3">
                                    <label for="site_footer_copyright" class="label-title mb-1">{{__('Footer Copyright')}}</label>
                                    <input type="text" id="site_footer_copyright" name="site_footer_copyright"  class="form-control" value="{{get_static_option('site_footer_copyright')}}" id="site_footer_copyright">
                                    <small class="form-text text-muted">{{__('{copy} will replace by ©; and {year} will be replaced by current year.')}}</small>
                                </div>
{{--                                <div class="switch mb-3">--}}
{{--                                    <label class="label-title mb-1"><strong>{{__('User Email Verify')}}</strong></label>--}}
{{--                                    <input class="custom-switch" type="checkbox" id="disable_user_email_verify" name="disable_user_email_verify" @if(!empty(get_static_option('disable_user_email_verify'))) checked @endif id="disable_user_email_verify">--}}
{{--                                    <label class="switch-label" for="disable_user_email_verify">{{__('User Email Verify')}}</label>--}}
{{--                                    <small class="form-text text-muted">{{__('Disable, means user must have to verify their email account in order to access his/her dashboard.')}}</small>--}}
{{--                                </div>--}}
                                <div class="switch mb-3">
                                    <label class="label-title mb-1"><strong>{{__('Maintenance Mode')}}</strong></label>
                                    <input class="custom-switch" type="checkbox" id="site_maintenance_mode" name="site_maintenance_mode" @if(!empty(get_static_option('site_maintenance_mode'))) checked @endif id="site_maintenance_mode">
                                    <label class="switch-label" for="site_maintenance_mode">{{__('Maintenance Mode')}}</label>
                                </div>
                                <div class="switch mb-3">
                                    <label class="label-title mb-1"><strong>{{__('Enable/Disable Google Captcha for Register')}}</strong></label>
                                    <input class="custom-switch" type="checkbox" id="site_google_captcha_enable" name="site_google_captcha_enable" @if(!empty(get_static_option('site_google_captcha_enable'))) checked @endif>
                                    <label class="switch-label" for="site_google_captcha_enable">{{__('Enable/Disable Google Captcha for Register')}}</label>
                                </div>
                                <div class="switch mb-3">
                                    <label class="label-title mb-1"><strong>{{__('Enable Force SSL Redirection')}}</strong></label>
                                    <input class="custom-switch" type="checkbox" id="site_force_ssl_redirection" name="site_force_ssl_redirection" @if(!empty(get_static_option('site_force_ssl_redirection'))) checked @endif>
                                    <label class="switch-label" for="site_force_ssl_redirection">{{__('Enable Force SSL Redirection')}}</label>
                                </div>
                                <div class="switch mb-3">
                                    <label class="label-title mb-1"><strong>{{__('Admin Preloader Animation')}}</strong></label>
                                    <input class="custom-switch" type="checkbox" id="admin_loader_animation" name="admin_loader_animation" @if(!empty(get_static_option('admin_loader_animation'))) checked @endif id="admin_loader_animation">
                                    <label class="switch-label" for="admin_loader_animation">{{__('Admin Preloader Animation')}}</label>
                                </div>
                                <div class="switch mb-3">
                                    <label class="label-title mb-1"><strong>{{__('Site Preloader Animation')}}</strong></label>
                                    <input class="custom-switch" type="checkbox" id="site_loader_animation" name="site_loader_animation" @if(!empty(get_static_option('site_loader_animation'))) checked @endif id="site_loader_animation">
                                    <label class="switch-label" for="site_loader_animation">{{__('Site Preloader Animation')}}</label>
                                </div>
                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
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
                <x-btn.update/>
                <x-icon-picker.icon-picker/>
            });
        })(jQuery);
    </script>
@endsection
