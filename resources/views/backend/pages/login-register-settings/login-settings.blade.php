@extends('backend.layout.master')
@section('title', __('Login Page Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Login Page Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.settings.login')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Page Title')" :type="__('text')" :name="'login_page_title'" :value="get_static_option('login_page_title') ?? '' " :placeholder="__('Log In')"/>
                                <br>
                                <x-form.text :title="__('Login in Button Title')" :type="__('text')" :name="'login_page_button_title'" :value="get_static_option('login_page_button_title') ?? '' " :placeholder="__('Sign in Now')"/>
                                <br>
                                <x-form.text :title="__('Sidebar Title')" :type="__('text')" :name="'login_page_sidebar_title'" :value="get_static_option('login_page_sidebar_title') ?? '' " :placeholder="__('Login and start discover.')"/>
                                <br>
                                <x-form.textarea :title="__('Sidebar Description')" :name="'login_page_sidebar_description'" :value="get_static_option('login_page_sidebar_description') ?? '' " :placeholder="__('Once login you will see the magic of xilancer marketplace.')"/>
                                <br>
                                <div class="switch">
                                    <label class="label-title mb-3"><strong>{{__('Social Login Enable/Disable')}}</strong></label>
                                    <input class="custom-switch" type="checkbox" id="login_page_social_login_enable_disable" name="login_page_social_login_enable_disable" @if(!empty(get_static_option('login_page_social_login_enable_disable'))) checked @endif>
                                    <label class="switch-label" for="login_page_social_login_enable_disable">{{__('Social Login Enable/Disable')}}</label>
                                    <small class="form-text text-info">{{__('Disable, means user will not able to login with social platform like facebook, google etc.')}}</small>
                                </div>
                                <br>
                                <x-backend.image :title="__('Sidebar Image')" :name="'login_page_sidebar_image'" :dimentions="'850x650'"/>
                                @can('login-page-settings-update')
                                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                                @endcan
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
