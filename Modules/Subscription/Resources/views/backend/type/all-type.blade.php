@extends('backend.layout.master')
@section('title', __('All Types'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <x-notice.general-notice :description="__('Notice: A type can only be deleted if it has no dependencies. If a type is not associated with any subscriptions, you can proceed to delete it.')" />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Types') }}</h4>
                            @can('subscription-type-add')
                            <x-btn.add-modal :title="__('Add Subscription Type')" />
                            @endcan
                        </div>
                        <div class="search_delete_wrapper">
                            @can('subscription-type-bulk-delete')
                            <x-bulk-action.bulk-action/>
                            @endcan
                            <x-search.search-in-table :id="'string_search'"/>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('subscription::backend.type.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('subscription::backend.type.add-modal')
    @include('subscription::backend.type.edit-modal')
    <x-media.markup/>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    <x-bulk-action.bulk-delete-js :url="route('admin.subscription.type.delete.bulk.action')"/>
    <x-media.js/>
    @include('subscription::backend.type.type-js')
@endsection
