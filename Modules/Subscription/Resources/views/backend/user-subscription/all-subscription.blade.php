@extends('backend.layout.master')
@section('title', __('User Subscriptions'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <x-notice.general-notice :description="__(
                    'Notice: A manual payment subscription can be used only when the payment status is complete and the status remains active.',
                )" :description1="__('Notice: You can search here by id, user id, purchase date and expire date.')" />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('User Subscriptions') }}</h4>
                            <x-search.search-in-table :placeholder="__('Search by ....')" :id="'string_search'" />
                        </div>
                        <div class="userSubscription__list">
                            <input type="hidden" id="get_selected_value">
                            @can('user-active-subscription')
                                <button id="active_subscription" class="userSubscription__list__item" data-val="active-sub">
                                    {{ __('Active') }} {{ $active_subscription ?? 0 }}</button>
                            @endcan
                            @can('user-inactive-subscription')
                                <button id="inactive_subscription" class="userSubscription__list__item" data-val="inactive-sub">
                                    {{ __('Inactive') }} {{ $inactive_subscription ?? 0 }}</button>
                            @endcan
                            @can('user-manual-subscription')
                                <button id="manual_subscription" class="userSubscription__list__item" data-val="manual-sub">
                                    {{ __('Manual') }} {{ $manual_subscription ?? 0 }}</button>
                            @endcan
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('subscription::backend.user-subscription.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('subscription::backend.user-subscription.manual-payment-modal')
    <x-media.markup />
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    @include('subscription::backend.user-subscription.subscription-js')
@endsection
