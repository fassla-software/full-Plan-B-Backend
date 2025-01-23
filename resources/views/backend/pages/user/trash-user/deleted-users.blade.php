@extends('backend.layout.master')
@section('title', __('Trash List'))
@section('style')
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Trash List') }}</h4>
                            <x-search.search-in-table :id="'string_search'"/>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice
                                    :class="'mb-5'"
                                    :description="__('Notice: Permanently deleting a user results in the irreversible removal of all their associated information from the system, making these data non-recoverable.')"/>
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('backend.pages.user.trash-user.search-result-for-delete-users')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    @include('backend.pages.user.trash-user.delete-user-js')

@endsection
