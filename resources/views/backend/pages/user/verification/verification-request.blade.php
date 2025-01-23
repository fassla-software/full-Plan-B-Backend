@extends('backend.layout.master')
@section('title', __('All Requests'))
@section('style')
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Requests') }}</h4>
                            <x-search.search-in-table :id="'string_search'"/>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('backend.pages.user.verification.verification-request-search')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.user.verification.identity-verify-details-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    @include('backend.pages.user.verification.verification-js')

@endsection
