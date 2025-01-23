@extends('frontend.layout.master')
@section('site_title',__('Support Tickets'))
@section('style')
    <x-summernote.summernote-css />
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
        <x-breadcrumb.user-profile-breadcrumb :title="__('Tickets')" :innerTitle="__('Tickets')"/>
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
                                        <h4>{{ __('Ticket') }}</h4>
                                    </div>
                                    <div class="single-profile-settings-contents">
                                        <div class="single-profile-settings-contents-upload">
                                            <div class="single-profile-settings-contents-upload-btn">
                                                <button class="btn-profile btn-bg-1" data-bs-toggle="modal" data-bs-target="#addModal">{{ __('Add New Ticket') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-profile-settings" id="display_client_profile_info">
                                <div class="single-profile-settings-header">
                                    <x-validation.error />
                                    <x-notice.general-notice :description="__('Notice: You can search here by ticket id, ticket status and ticket priority.')" />
                                    <div class="single-profile-settings-header-flex">
                                        <x-form.form-title :title="__('All Tickets')" :class="'single-profile-settings-header-title'" />
                                        <x-search.search-in-table :id="'string_search'" :placeholder="__('Search here')" />
                                    </div>
                                </div>
                                <div class="single-profile-settings-inner profile-border-top">
                                    <div class="custom_table style-04 search_result">
                                        @include('supportticket::client.search-result')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Settings area end -->
        @include('supportticket::client.add-modal')
    </main>
@endsection

@section('script')
    @include('supportticket::client.ticket-js')
    <x-summernote.summernote-js />
@endsection
