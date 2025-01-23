@extends('backend.layout.master')
@section('title', __('Freelancer Feedbacks'))
@section('style')
    <x-media.css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <x-notice.general-notice
                        :description="__('Notice: This is a collection of freelancer feedbacks. You can edit feedbacks here. Only approved feedbacks will be displayed on the frontend.')"
                        :description1="__('Notice: You can search by rating only.')"
                />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Freelancer Feedbacks') }}</h4>
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                               @include('backend.pages.feedback.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.feedback.edit-modal')
    <x-media.markup />
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.category.delete.bulk.action')"/>
    <x-media.js />
    @include('backend.pages.feedback.feedback-js')
@endsection
