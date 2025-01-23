@extends('frontend.layout.master')
@section('site_title') {{ $skill->skill ?? __('Skills') }} @endsection
@section('style')
    <x-select2.select2-css />
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Skill Jobs') ?? __('Skill')" :innerTitle=" $skill->skill ?? '' "/>
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

                                @include('frontend.pages.skill-jobs.sidebar')
                                <input type="hidden" id="skill_id" value="{{$skill->id ?? ''}}">
                                <div class="shop-contents-wrapper-right">
                                    <div class="jobFilter-wrapper-search">
                                        <input type="text" id="job_search_string" placeholder="{{ __('Search Jobs...') }}" class="form--control">
                                        <button class="jobFilter-wrapper-search-btn" id="job_search_by_text"> {{ __('Search') }} </button>
                                    </div>
                                    <div class="search_job_result">
                                        @include('frontend.pages.skill-jobs.search-job-result')
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
    @include('frontend.pages.skill-jobs.jobs-filter-js')
    <x-select2.select2-js />
@endsection
