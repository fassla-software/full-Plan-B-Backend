@extends('backend.layout.master')
@section('title', __('All Freelancers'))
@section('style')
    <x-select2.select2-css/>
    <style>
        #edit_user_details {
            height: calc(100vh - 210px);
            overflow-y: auto;
        }
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Freelancers') }}</h4>
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice
                                :class="'mb-5'"
                                :description="__('Notice: Suspended user will complete his active and delivered order.')"
                                :description1="__('Notice: Suspended user can withdraw his balance.')"
                                :description2="__('Notice: A suspended user will be unable to receive new orders or place bids on any jobs.')"
                                :description3="__('Notice: When an admin deactivates a user account, the user will no longer receive new orders. Additionally, projects and jobs associated with both freelancers and clients will be hidden from public view until the account is reactivated.')"
                                :description4="__('Notice: Identity verify means user verified his identity by legal documents')"
                                :description5="__('Notice: Individual commission settings allow the admin to assign a unique commission rate for each specific user.')"
                            />

                            @if(moduleExists('SecurityManage'))
                                <x-notice.general-notice
                                        :class="'mb-2'"
                                        :description="__('Notice: A freeze on withdrawals means that freelancers are unable to submit requests to withdraw amount.')"
                                        :description1="__('Notice: A freeze on projects create edit means that freelancers are unable to create and edit his project.')"
                                        :description2="__('Notice: A freeze on chat means that freelancers are unable to send chat message.')"
                                />
                            @endif
                            <!-- Table Start -->
                            @can('user-delete')
                                <x-bulk-action.bulk-action />
                            @endcan
                            <div class="custom_table mt-3 style-04 search_result">
                                @include('backend.pages.user.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.user.user-details-modal')
    @include('backend.pages.user.user-password-modal')
    @include('backend.pages.user.user-details-edit-modal')
    @include('backend.pages.user.identity-verify-details-modal')
    @include('backend.pages.user.individual-commission-settings-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    <x-select2.select2-js/>
    <x-bulk-action.bulk-delete-js :url="route('admin.user.delete.bulk.action')"/>
    @include('backend.pages.user.user-js')

@endsection
