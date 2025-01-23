@extends('frontend.layout.master')
@section('site_title',__('Account Delete'))
@section('style')
    <style>
        .single-profile-settings:not(:first-child) {
            margin-top: 0px;
        }
    </style>
@endsection

@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Account Delete')" :innerTitle="__('Account Delete')"/>
        <!-- Password Settings area Starts -->
        <div class="responsive-overlay"></div>
        <div class="profile-settings-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    @include('frontend.user.layout.partials.sidebar')
                    <div class="col-xl-9 col-lg-8">
                        <div class="profile-settings-wrapper">
                            <form method="post" action="{{ route('freelancer.account.delete') }}">
                                @csrf
                                <div class="single-profile-settings">
                                    <h4>{{ __('Delete Account') }}</h4>
                                    <div class="change-password custom-form mt-5">
                                        <x-notice.general-notice :description="__('Notice: Remember you will not able to login this account after delete your account.')" />
                                    </div>
                                    <div class="btn-wrapper profile-border-top flex-btn justify-content-end">
                                        <x-btn.submit :title="__('Delete Account')" :class="'btn btn-danger'" />
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
    @include('frontend.user.freelancer.password.password-js')
@endsection
