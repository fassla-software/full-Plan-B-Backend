@extends('backend.layout.master')
@section('title', __('All Subcategories'))
@section('style')
    <x-select2.select2-css />
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <x-notice.general-notice :description="__('Notice: A subcategory can be deleted only if it has no dependencies. It can be removed if it is not associated with any jobs, projects, or skills.')" />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Sub Categories') }}</h4>
                            @can('subcategory-add')
                            <x-btn.add-modal :title="__('Add Sub Category')" />
                            @endcan
                        </div>
                        <div class="search_delete_wrapper">
                            @can('subcategory-bulk-delete')
                            <x-bulk-action.bulk-action />
                            @endcan
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('service::subcategory.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('service::subcategory.add-modal')
    @include('service::subcategory.edit-modal')
    <x-media.markup/>
@endsection

@section('script')
    <x-media.js/>
    <x-sweet-alert.sweet-alert2-js />
    <x-select2.select2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.subcategory.delete.bulk.action')"/>
    @include('service::subcategory.subcategory-js')
@endsection
