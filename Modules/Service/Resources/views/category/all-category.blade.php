@extends('backend.layout.master')
@section('title', __('All Categories'))
@section('style')
    <x-media.css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <x-notice.general-notice :description="__('Notice: A category can only be deleted if it has no dependencies. This means it can be removed if it is not linked to any subcategories, jobs, projects, or skills.')" />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Categories') }}</h4>
                            @can('category-edit')
                            <x-btn.add-modal :title="__('Add Category')" />
                            @endcan
                        </div>
                        <div class="search_delete_wrapper">
                            @can('category-bulk-delete')
                            <x-bulk-action.bulk-action />
                            @endcan
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                               @include('service::category.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('service::category.add-modal')
    @include('service::category.edit-modal')
    <x-media.markup />
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.category.delete.bulk.action')"/>
    <x-media.js />
    @include('service::category.category-js')
@endsection
