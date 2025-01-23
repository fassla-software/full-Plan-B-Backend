@extends('backend.layout.master')
@section('title', __('Register Page Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Register Page Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.settings.register')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Choose Role Title')" :type="__('text')" :name="'register_page_choose_role_title'" :value="get_static_option('register_page_choose_role_title') ?? '' " :placeholder="__('Choose a Role')"/>
                                <br>
                                <x-form.text :title="__('Choose Role Subtitle')" :type="__('text')" :name="'register_page_choose_role_subtitle'" :value="get_static_option('register_page_choose_role_subtitle') ?? '' " :placeholder="__('Choose a role from below to continue signing up')"/>
                                <br>
                                <x-form.text :title="__('Join as Freelancer Title')" :type="__('text')" :name="'register_page_choose_join_freelancer_title'" :value="get_static_option('register_page_choose_join_freelancer_title') ?? '' " :placeholder="__('Join as a freelancer')"/>
                                <br>
                                <x-form.text :title="__('Join as Client Title')" :type="__('text')" :name="'register_page_choose_join_client_title'" :value="get_static_option('register_page_choose_join_client_title') ?? '' " :placeholder="__('Join as a client')"/>
                                <br>
                                <x-form.text :title="__('Continue Button Title')" :type="__('text')" :name="'register_page_continue_button_title'" :value="get_static_option('register_page_continue_button_title') ?? '' " :placeholder="__('Continue')"/>
                                <br>
                                <x-form.text :title="__('Page Form Title')" :type="__('text')" :name="'register_page_title'" :value="get_static_option('register_page_title') ?? '' " :placeholder="__('Sign Up')"/>
                                <br>
                                <x-form.text :title="__('Sign up Button Title')" :type="__('text')" :name="'register_page_button_title'" :value="get_static_option('register_page_button_title') ?? '' " :placeholder="__('Sign up Now')"/>
                                <br>
                                <x-form.text :title="__('Terms and Condition Url')" :type="__('text')" :name="'toc_page_link'" :value="get_static_option('toc_page_link') ?? '' " :placeholder="__('Terms and condition url')"/>
                                <br>
                                <x-form.text :title="__('Privacy Policy Url')" :type="__('text')" :name="'privacy_policy_link'" :value="get_static_option('privacy_policy_link') ?? '' " :placeholder="__('Privacy policy url')"/>
                                <br>
                                <x-form.text :title="__('Sidebar Title')" :type="__('text')" :name="'register_page_sidebar_title'" :value="get_static_option('register_page_sidebar_title') ?? '' " :placeholder="__('Register and start discover.')"/>
                                <br>
                                <x-form.textarea :title="__('Sidebar Description')" :name="'register_page_sidebar_description'" :value="get_static_option('register_page_sidebar_description') ?? '' " :placeholder="__('Once register you will see the magic of xilancer marketplace.')"/>
                                <br>
                                <x-backend.image :title="__('Sidebar Image')" :name="'register_page_sidebar_image'" :dimentions="'850x650'"/>
                                @can('register-page-settings-update')
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
