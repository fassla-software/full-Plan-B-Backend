@extends('frontend.layout.master')
@section('site_title',__('Jobs'))
@section('meta_title'){{ __('Jobs') }}@endsection
@section('style')
    <x-select2.select2-css />
    <style>
        .select2-selection--multiple {
            height: auto !important;
            padding: 0 !important;
        }
        .select2-selection--multiple .select2-selection__rendered {
            height: auto !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice:first-child {
            margin-left: 6px !important;
        }
        span.select2-selection.select2-selection--multiple {
            padding-left: 9px !important;
        }
    </style>
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Jobs') ?? __('Jobs')" :innerTitle="__('Jobs') ?? '' "/>
        <!-- Project preview area Starts -->
        <div class="preview-area section-bg-2 pat-100 pab-100">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="categoryWrap-wrapper">
                            <div class="shop-contents-wrapper responsive-lg">
                                <div class="shop-icon">
                                    <div class="shop-icon-sidebar">
                                        <i class="fas fa-bars"></i>
                                    </div>
                                </div>

                                @include('frontend.pages.jobs.sidebar')
                                <input type="hidden" id="category_id" value="{{$category->id ?? ''}}">
                                <div class="shop-contents-wrapper-right">
                                    <div class="jobFilter-wrapper-search">
                                        <input type="text" id="job_search_string" placeholder="{{ __('Search Jobs...') }}" class="form--control">
                                        <button type="submit" class="jobFilter-wrapper-search-btn" id="job_search_by_text"> {{ __('Search') }} </button>
                                    </div>
                                    <div class="search_job_result">
                                        @include('frontend.pages.jobs.search-job-result')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Project preview area end -->
    </main>

@endsection

@section('script')
    @include('frontend.pages.jobs.jobs-filter-js')
    <x-select2.select2-js />
@endsection
