@extends('frontend.layout.master')
@section('site_title',__('My Proposals'))
@section('style')
    <style>
        #proposal_cover_letter_details{white-space:pre-line}
    </style>
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Proposals')" :innerTitle="__('My Proposals')"/>

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
                                                @include('frontend.user.freelancer.proposal.search-result')
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
                                @if(moduleExists('HourlyJob'))
                                    @foreach ($jobs as $job)
                                        <div class="single-jobs border-0 radius-10 bg-white mt-4">
                                            <h4 class="single-jobs-title">
                                                <a href="{{ route('job.details', ['username' => $job->job_creator?->username, 'slug' => $job->slug]) }}">{{ $job->title }}</a>
                                            </h4>
                                            <p class="single-jobs-date">
                                                {{ $job->created_at->toFormattedDateString() ?? '' }} -
                                                <span>{{ ucfirst($job->level) ?? '' }}</span>
                                            </p>

                                            <h3 class="single-jobs-price">
                                                {{ $job->type == 'hourly' ? float_amount_with_currency_symbol($job->hourly_rate) : float_amount_with_currency_symbol($job->budget) }}
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
                                    @foreach ($jobs as $job)
                                        <div class="single-jobs border-0 radius-10 bg-white mt-4">
                                            <h4 class="single-jobs-title">
                                                <a href="{{ route('job.details', ['username' => $job->job_creator?->username, 'slug' => $job->slug]) }}">{{ $job->title }}</a>
                                            </h4>
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
                                @endif
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

    @include('frontend.user.freelancer.proposal.cover-letter-modal')

@endsection

@section('script')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on('click','.cover_letter_details',function(){
                    let cover_letter = $(this).data('cover-letter');
                    $('#proposal_cover_letter_details').text(cover_letter);
                })

                $(document).on('click', '.pagination a', function(e){
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];
                    subscriptions(page);
                });
                function subscriptions(page){
                    $.ajax({
                        url:"{{ route('freelancer.proposal.paginate.data').'?page='}}" + page,
                        success:function(res){
                            $('.search_result').html(res);
                        }
                    });
                }
            });
        }(jQuery));
    </script>
@endsection

