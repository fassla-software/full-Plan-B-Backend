@extends('frontend.layout.master')
@section('site_title',__('My Offers'))
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Custom Offers')" :innerTitle="__('Custom Offers')"/>

        <!-- Profile Details area Starts -->
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row gy-4 justify-content-center">
                    <div class="@if(get_static_option('job_enable_disable') != 'disable') col-xl-8 col-lg-8 @else col-12 @endif">
                        <div class="shop-contents-wrapper-right">
                            <div class="myOrder-wrapper">
                                <div class="myOrder-wrapper-tabs">
                                    <div class="myOrder-tab-content">
                                        <div class="tab-content-item active">
                                            <div class="search_result">
                                                @include('chat::freelancer.offer.search-result')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(get_static_option('job_enable_disable') != 'disable')
                    <div class="col-xl-4 col-lg-4">
                        <div class="profile-details-widget sticky_top">
                            <div class="file-wrapper-item-flex flex-between align-items-center profile-border-bottom">
                                <h4 class="profile-wrapper-item-title"> {{__('Available Jobs')}} </h4>
                                <a href="{{route('jobs.all')}}" class="profile-wrapper-item-browse-btn"> {{__('Browse All')}} </a>
                            </div>
                            @if($jobs->count()>0)
                                @foreach ($jobs as $job)
                                    <div class="single-jobs border-0 radius-10 bg-white mt-4">
                                        <h4 class="single-jobs-title"> <a
                                                    href="{{ route('job.details', ['username' => $job->job_creator?->username, 'slug' => $job->slug]) }}">
                                                {{ $job->title }} </a> </h4>
                                        <p class="single-jobs-date">
                                            {{ $job->created_at->toFormattedDateString() ?? '' }} -
                                            <span>{{ ucfirst($job->level) ?? '' }}</span>
                                        </p>

                                        <h3 class="single-jobs-price">
                                            {{ float_amount_with_currency_symbol($job->budget) }}
                                            <span class="single-jobs-price-fixed">{{ __(ucfirst($job->type)) }}</span>
                                        </h3>
                                        <div class="single-jobs-tag mt-4">
                                            @foreach ($job->job_skills as $skill)
                                                <a href="{{ route('skill.jobs', $skill->skill) }}" class="single-jobs-tag-link">
                                                    {{ $skill->skill ?? '' }} </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h6 class="profile-wrapper-item-title">{{__('No Jobs Found')}}</h6>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Profile Details area end -->
    </main>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    @include('chat::freelancer.offer.offer-js')
@endsection
