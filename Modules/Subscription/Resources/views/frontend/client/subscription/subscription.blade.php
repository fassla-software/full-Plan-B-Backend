@extends('frontend.layout.master')
@section('site_title',__('Subscriptions'))
@section('style')
    <style>

        .single-profile-settings-flex {
            justify-content: space-between;
        }
        .single-profile-settings-contents .single-profile-settings-contents-upload-btn {
            padding: 0;
        }
        .single-profile-settings .single-profile-settings-thumb {
            max-width: unset;
        }
        .balance-wallet {
            color: var(--paragraph-color);
        }
        .balance-wallet strong {
            color: var(--heading-color);
        }
    </style>
@endsection

@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Subscriptions')" :innerTitle="__('Subscriptions')"/>
        <!-- Profile Settings area Starts -->
        <div class="responsive-overlay"></div>
        <div class="profile-settings-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    @include('frontend.user.layout.partials.sidebar')
                    <div class="col-xl-9 col-lg-8">
                        <div class="profile-settings-wrapper">


                            <div class="single-profile-settings" id="display_client_profile_photo">
                                <div class="single-profile-settings-flex">
                                    <div class="single-profile-settings-thumb">
                                        <h4 class="balance-wallet">{{ __('Total Connect:') }} <strong>{{ $total_limit }}</strong></h4>
                                    </div>
                                    <div class="single-profile-settings-contents">
                                        <div class="single-profile-settings-contents-upload">
                                            <div class="single-profile-settings-contents-upload-btn">
                                                <a href="{{ route('subscriptions.all') }}" class="btn-profile btn-bg-1">{{ __('Buy Subscription') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-profile-settings" id="display_client_profile_info">
                                <div class="single-profile-settings-header">
                                    <x-validation.error />
                                    <div class="single-profile-settings-header-flex">
                                        <x-form.form-title :title="__('Subscription History')" :class="'single-profile-settings-header-title'" />
                                        <x-search.search-in-table :id="'string_search'" :placeholder="__('Enter date to search')" />
                                    </div>
                                    <x-notice.general-notice :class="'mt-5'" :description="__('Notice: If your monthly free limit ends, Each time ') .' '.(get_static_option('limit_settings') ?? 1) .' '. __('limit will be reduce from your total limit while you create a job post.')" />
                                </div>
                                <div class="single-profile-settings-inner profile-border-top">
                                    <div class="custom_table style-04 search_result">
                                          @include('subscription::frontend.client.subscription.search-result')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Settings area end -->
    </main>
@endsection

@section('script')
    @include('subscription::frontend.client.subscription.subscription-js')
@endsection
