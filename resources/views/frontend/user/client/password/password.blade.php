@extends('frontend.layout.master')
@section('site_title',__('Change Password'))
@section('style')
    <style>
        .single-profile-settings:not(:first-child) {
             margin-top: 0px;
        }
    </style>
@endsection

@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Change Password')" :innerTitle="__('Change Password')"/>
        <!-- Password Settings area Starts -->
        <div class="responsive-overlay"></div>
        <div class="profile-settings-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    @include('frontend.user.layout.partials.sidebar')
                    <div class="col-xl-9 col-lg-8">
                        <div class="profile-settings-wrapper">
                            <form id="password_change_form">
                                @csrf
                                <x-validation.error />
                                <div class="single-profile-settings">
                                    <div class="change-password custom-form">
                                        <div class="error_msg_container my-2"></div>
                                        <x-form.password :title="__('Current Password')" :type="'password'" :name="'current_password'" :id="'current_password'" :class="'form-control'" :placeholder="__('Type Current Password')" />
                                        <span id="current_password_match"></span>
                                        <x-form.password :title="__('New Password')" :type="'password'" :name="'new_password'" :id="'new_password'" :class="'form-control'" :placeholder="__('Type New Password')" />
                                        <x-form.password :title="__('Confirm New Password')" :type="'password'" :name="'confirm_new_password'" :id="'confirm_new_password'" :class="'form-control'" :placeholder="__('Confirm New Password')" />
                                        <span id="new_password_match"></span>
                                    </div>
                                    <div class="btn-wrapper profile-border-top flex-btn justify-content-end">
                                        <x-btn.submit :title="__('Change Password')" :class="'btn-profile btn-bg-1'" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Password Settings area end -->
    </main>
@endsection

@section('script')
    <x-select2.select2-js />
    @include('frontend.user.client.password.password-js')
@endsection
