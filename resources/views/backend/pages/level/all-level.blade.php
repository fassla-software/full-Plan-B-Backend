@extends('backend.layout.master')
@section('title', __('All Lengths'))
@section('style')
    <x-media.css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Experience Levels') }}</h4>
                            <x-btn.add-modal :title="__('Add Experience Level')" />
                        </div>
                        <div class="search_delete_wrapper">
                            <x-bulk-action.bulk-action />
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('backend.pages.level.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.level.add-modal')
    @include('backend.pages.level.edit-modal')
    <x-media.markup />
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.experience.level.delete.bulk.action')"/>
    <x-media.js />
    @include('backend.pages.level.level-js')
@endsection