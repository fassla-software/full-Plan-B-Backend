@extends('frontend.layout.master')
@section('site_title') {{ __('Offer Details') }}  @endsection
@section('style')
    <x-summernote.summernote-css />
    <style>
        .user-details-manage-list {display: flex;flex-direction: column;gap: 10px}
        .myOrder-single-content-para , .myJob-wrapper-single-para{white-space: pre-line}
        .show_order_submit_description{white-space: pre-line}
    </style>
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Offer Details')" :innerTitle="__('Offer Details')"/>

        <!-- Profile Details area Starts -->
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="myOrder-single bg-white padding-20 radius-10">
                            <div class="myOrder-single-item">
                                <div class="myOrder-single-flex">
                                    <div class="myOrder-single-content">
                                        <span class="myOrder-single-content-id">#000{{ $offer_details->id }}</span>
                                        <div class="myOrder-single-content-btn flex-btn mt-3">
                                            @php
                                                $offer_order = \App\Models\Order::where('identity',$offer_details->id)->where('is_project_job','offer')->where('payment_status','complete')->first();
                                            @endphp
                                            @if($offer_order)
                                                <span class="job-progress">{{ __('Accepted') }}</span>
                                            @else
                                                {{-- <x-offer.offer-status :status="$offer->status" />--}}
                                                <span class="pending-approval">{{ __('Pending') }}</span>
                                            @endif
                                            <span class="custom-order">{{__("Custom Offer")}}</span>
                                        </div>
                                    </div>
                                    <span class="myOrder-single-content-time">{{ $offer_details->created_at->diffForHumans() }} </span>
                                </div>
                            </div>
                            <div class="myOrder-single-item">
                                <div class="myOrder-single-block">
                                    <div class="myOrder-single-block-item">
                                        <div class="myOrder-single-block-item-content">
                                            <span class="myOrder-single-block-subtitle">{{ __('Offer Price') }}</span>
                                            <h6 class="myOrder_single__block__title mt-2">
                                                {{ float_amount_with_currency_symbol($offer_details->price) }}
                                            </h6>
                                        </div>
                                    </div>
                                    @if($offer_details->deadline)
                                        <div class="myOrder-single-block-item">
                                            <div class="myOrder-single-block-item-content">
                                                <span class="myOrder-single-block-subtitle">{{ __('Delivery Time') }}</span> <br>
                                                <h6 class="myOrder_single__block__title mt-2">{{ $offer_details->deadline ?? '' }}</h6>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="myOrder-single-block-item">
                                        <div class="myOrder-single-block-item-content">
                                            <span class="myOrder-single-block-subtitle">{{ __('Create Date') }}</span><br>
                                            <h6 class="myOrder_single__block__title mt-2">
                                                {{ $offer_details->created_at->toFormattedDateString() ?? '' }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="myOrder-single-item">
                                <div class="myOrder-single-flex flex-between">
                                    @php
                                        $mile_stones = \Modules\Chat\Entities\OfferMilestone::where('offer_id',$offer_details->id)->get();
                                    @endphp
                                    <div class="btn-wrapper flex-btn">
                                        @if($mile_stones->isEmpty())
                                            <span class="myJob-wrapper-single-fixed danger">{{ __('Revision:') }} {{ $offer_details->revision }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="myJob-tabs mt-5">
                            @if($mile_stones->count() > 0)
                                <div class="tab-content-item active mt-5">
                                    <div class="myJob-wrapper-single">
                                        <div class="myJob-wrapper-single-header profile-border-bottom">
                                            <h4 class="myJob-wrapper-single-title">{{ __('Milestone') }}</h4>
                                        </div>
                                        <div class="myJob-wrapper-single-milestone milestone-contractor-parent">
                                            @foreach($mile_stones as $mile_stone)
                                                <div class="myJob-wrapper-single-milestone-item">
                                                    <div class="myJob-wrapper-single-flex flex-between align-items-start">
                                                        <x-offer.milestone-details
                                                            :id="$mile_stone->id"
                                                            :title="$mile_stone->title"
                                                            :price="$mile_stone->price"
                                                            :deadline="$mile_stone->deadline"
                                                            :description="$mile_stone->description"
                                                        />
                                                        <div class="myJob-wrapper-single-right">
                                                            <div class="myJob-wrapper-single-right-flex">
                                                                <span class="myJob-wrapper-single-fixed danger">{{ __('Revision:') }} {{ $mile_stone->revision ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="myJob-wrapper-single">
                                   <p class="myOrder-single-content-para">{!! $offer_details->description !!}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="profile-details-widget sticky_top_lg">

                            <div class="jobFilter-wrapper-item">
                                <div class="jobFilter-proposal-author-flex">
                                    <span class="user-details-manage-thumb">
                                        <div class="myOrder-single-block-item-author">
                                            <x-order.profile-image :image="$offer_details?->client->image" />
                                        </div>
                                    </span>
                                    <div class="jobFilter-proposal-author-contents">
                                        <h5 class="single-freelancer-author-name">
                                            {{ $offer_details?->client->first_name }}
                                            {{ $offer_details?->client->last_name }}
                                            @if(Cache::has('user_is_online_' . $offer_details?->client->id))
                                                <span class="single-freelancer-author-status"> {{ __('Active') }} </span>
                                            @else
                                                <span class="single-freelancer-author-status-ofline"> {{ __('Inactive') }} </span>
                                            @endif
                                        </h5>
                                        <p class="jobFilter-proposal-author-contents-subtitle mt-2">
                                            @if($offer_details?->client?->user_state?->state != null)
                                            {{ $offer_details?->client?->user_state?->state }},
                                            @endif
                                            {{ $offer_details?->client?->user_country?->country }}
                                            @if($offer_details?->client?->user_verified_status == 1) <i class="fas fa-circle-check"></i>@endif
                                        </p>
                                    </div>
                                </div>

                                <div class="jobFilter-about-clients">
                                    <div class="jobFilter-about-clients-single flex-between">
                                        @if ($offer_details?->job?->last_seen != null)
                                            <h6 class="jobFilter-wrapper-item-completed">
                                                <span class="jobFilter-about-clients-para">{{ __('Last seen') }}</span>
                                                {{ \Carbon\Carbon::parse($offer_details?->job?->last_seen)?->diffForHumans() }}
                                            </h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="jobFilter-about-clients">
                                    <div class="jobFilter-about-clients-single flex-between">
                                        <div class="jobFilter-about-clients-flex">
                                        <span class="jobFilter-about-clients-icon">
                                            <img
                                                    src="{{ asset('assets/static/icons/member_since.svg') }}" alt="">
                                        </span>
                                            <span class="jobFilter-about-clients-para"> {{ __('Member since') }} </span>
                                        </div>
                                        <h6 class="jobFilter-about-clients-completed">
                                            {{ $offer_details->client?->created_at->toFormattedDateString() ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="jobFilter-about-clients">
                                    <div class="jobFilter-about-clients-single flex-between">
                                        <div class="jobFilter-about-clients-flex">
                                        <span class="jobFilter-about-clients-icon">
                                            <img src="{{ asset('assets/static/icons/job_post.svg') }}" alt="">
                                        </span>
                                            <span class="jobFilter-about-clients-para">{{ __('Total Job') }}</span>
                                        </div>
                                        <h6 class="jobFilter-wrapper-item-completed">{{ $offer_details?->client?->user_jobs?->count() }}</h6>
                                    </div>
                                </div>

                                @php
                                    $total_job = App\Models\JobPost::where('user_id', $offer_details?->client->id)->count();
                                    $total_order = App\Models\Order::where('user_id', $offer_details?->client->id)
                                        ->where('status', 3)
                                        ->count();

                                    $hiring_rate = '';
                                         if ($total_job > 0) {
                                          $hiring_rate = ($total_order * 100) / $total_job;
                                        }
                                @endphp

                                @if ($hiring_rate >= 1)
                                    <div class="jobFilter-about-clients">
                                        <div class="jobFilter-about-clients-single flex-between">
                                            <div class="jobFilter-about-clients-flex">
                                            <span class="jobFilter-about-clients-icon"> <img
                                                        src="{{ asset('assets/static/icons/hire_rate.svg') }}"
                                                        alt=""> </span>
                                                <span class="jobFilter-about-clients-para">{{ __('Hire rate') }}</span>
                                            </div>
                                            <h6 class="jobFilter-wrapper-item-completed">{{ round($hiring_rate) ?? 0 }}%
                                            </h6>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
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
