@extends('frontend.layout.master')
@section('site_title') {{ $subcategory->sub_category ?? __('Subcategory Jobs') }} @endsection
@section('meta_title') {{ $subcategory->meta_title ?? '' }} @endsection
@section('meta_description') {{ $subcategory->meta_description ?? '' }} @endsection
@section('style')
    <x-select2.select2-css />
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Subcategory Jobs') ?? __('Jobs')" :innerTitle=" $subcategory->sub_category ?? '' "/>
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

                                @include('frontend.pages.subcategory-jobs.sidebar')
                                <input type="hidden" id="subcategory_id" value="{{$subcategory->id ?? ''}}">
                                <div class="shop-contents-wrapper-right">
                                    <div class="jobFilter-wrapper-search">
                                        <input type="text" id="job_search_string" placeholder="{{ __('Search Jobs...') }}" class="form--control">
                                        <button class="jobFilter-wrapper-search-btn" id="job_search_by_text"> {{ __('Search') }} </button>
                                    </div>

                                    <div class="search_job_result">
                                        @include('frontend.pages.subcategory-jobs.search-job-result')
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
    @include('frontend.pages.subcategory-jobs.jobs-filter-js')
    <x-select2.select2-js />
@endsection
