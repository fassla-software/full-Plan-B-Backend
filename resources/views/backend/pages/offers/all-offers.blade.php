@extends('backend.layout.master')
@section('title', __('All Offers'))
@section('style')
    <x-select2.select2-css />
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
                            <h4 class="customMarkup__single__title">{{ __('All Offers') }}</h4>
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table mt-3 style-04 search_result">
                                @include('backend.pages.offers.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.offers.offer-details-modal')
    @include('backend.pages.offers.offer-details-edit-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-select2.select2-js />
    {{-- <x-bulk-action.bulk-delete-js :url="route('admin.user.delete.bulk.action')" /> --}}
    @include('backend.pages.offers.offer-js')
@endsection

