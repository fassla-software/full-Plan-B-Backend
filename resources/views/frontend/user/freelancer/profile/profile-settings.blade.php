@extends('frontend.layout.master')
@section('site_title',__('Profile Settings'))
@section('style')
    <x-select2.select2-css/>
    <style>
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
        .popup-contents-form .single-input .select2-container {
            z-index: 9 !important;
        }
    </style>
@endsection
@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Profile Settings')" :innerTitle="__('Profile Settings')"/>
        <!-- Profile Settings area Starts -->
        <div class="responsive-overlay"></div>
        <div class="profile-settings-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    @include('frontend.user.layout.partials.sidebar')
                    <div class="col-xl-9 col-lg-8">
                        <div class="profile-settings-wrapper">
                            @include('frontend.user.freelancer.profile.profile-photo')
                            @include('frontend.user.freelancer.profile.profile-info')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Settings area end -->
       @include('frontend.user.freelancer.profile.edit-profile-info-modal')
       @include('frontend.user.freelancer.profile.profile-preview-modal')
       @include('frontend.user.freelancer.profile.feedback-modal')
    </main>
@endsection

@section('script')
    <x-select2.select2-js />
    @include('frontend.user.freelancer.profile.profile-js')
@endsection
