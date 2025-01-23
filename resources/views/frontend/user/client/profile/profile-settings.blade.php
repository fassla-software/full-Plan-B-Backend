@extends('frontend.layout.master')
@section('site_title',__('Profile Settings'))
@section('style')
    <x-select2.select2-css/>
    <style>
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
                            @include('frontend.user.client.profile.profile-photo')
                            @include('frontend.user.client.profile.profile-info')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Settings area end -->
        @include('frontend.user.client.profile.edit-profile-info-modal')
        @include('frontend.user.client.profile.profile-preview-modal')
    </main>
@endsection

@section('script')
    <x-select2.select2-js />
    @include('frontend.user.client.profile.profile-js')
@endsection
