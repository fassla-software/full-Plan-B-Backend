@extends('backend.layout.master')
@section('title', __('All Skills'))
@section('style')
    <x-select2.select2-css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <x-notice.general-notice :description="__('Notice: A skill can be deleted if it has no dependencies. It can be removed if it is not linked to any jobs.')" />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Skills') }}</h4>
                            @can('skill-add')
                            <x-btn.add-modal :title="__('Add Skill')" />
                            @endcan
                        </div>
                        <div class="search_delete_wrapper">
                            @can('skill-bulk-delete')
                            <x-bulk-action.bulk-action />
                            @endcan
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('backend.pages.skill.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.skill.add-modal')
    @include('backend.pages.skill.edit-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    <x-select2.select2-js/>
    <x-bulk-action.bulk-delete-js :url="route('admin.skill.delete.bulk.action')"/>
    @include('backend.pages.skill.skill-js')

@endsection
