@extends('backend.layout.master')
@section('title', __('All Subscription'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <x-notice.general-notice :description="__('Notice: A subscription can be deleted if it has no dependencies. Specifically, it can be removed only if it is not associated with any user.')" />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Subscription') }}</h4>
                            @can('subscription-add')
                            <a href="{{ route('admin.subscription.add') }}"
                               class="btn btn-primary btn-md d-flex align-items-center">
                                <span class="btn_plus_icon me-1"><i class="fa-solid fa-plus"></i></span>
                                {{ __('Add New Subscription') }}
                            </a>
                            @endcan
                        </div>
                        <div class="search_delete_wrapper">
                            @can('subscription-bulk-delete')
                            <x-bulk-action.bulk-action/>
                            @endcan
                            <x-search.search-in-table :id="'string_search'"/>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('subscription::backend.subscription.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    <x-bulk-action.bulk-delete-js :url="route('admin.subscription.delete.bulk.action')"/>
    <x-media.js/>
    @include('subscription::backend.subscription.subscription-js')
@endsection
