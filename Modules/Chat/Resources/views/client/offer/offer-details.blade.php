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
                                        @php
                                            $offer_order = \App\Models\Order::where('identity',$offer_details->id)->where('is_project_job','offer')->where('payment_status','complete')->first();
                                        @endphp
                                        <div class="myOrder-single-content-btn flex-btn mt-3">
                                            @if($offer_order)
                                            <span class="job-progress">{{ __('Accepted') }}</span>
                                            @else
    {{--                                            <x-offer.offer-status :status="$offer_details->status" />--}}
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
                                                <span class="myOrder-single-block-subtitle">{{ __('Delivery Time') }}</span><br>
                                                <h6 class="myOrder_single__block__title mt-2">
                                                    {{ $offer_details->deadline ?? '' }}
                                                </h6>
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

                        <div class="myOrder-single-item mt-4">
                            <div class="myOrder-single-flex flex-between">
                                @if($offer_order)
                                    <a href="javascript:void(0)" class="btn-profile btn-bg-1">{{ __('Accepted') }}</a>
                                @else
                                <a href="javascript:void(0)"
                                    class="btn-profile btn-bg-1 accept_custom_offer"
                                    data-offer-id-for-order="{{ $offer_details->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentGatewayModal">{{ __('Accept Offer') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="profile-details-widget sticky_top_lg">
                            <div class="jobFilter-wrapper-item">
                                <div class="myJob-wrapper-single-contents">
                                    <div class="jobFilter-proposal-author-flex">
                                        <div class="jobFilter-proposal-author-thumb">
                                            @if($offer_details->freelancer?->image)
                                                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $offer_details->freelancer?->image, load_from: $offer_details->freelancer?->load_from ?? '') }}" alt="{{ $offer_details->freelancer?->fullname ?? '' }}">
                                                @else
                                                    <img src="{{ asset('assets/uploads/profile/'.$offer_details->freelancer?->image) }}" alt="{{ $offer_details->freelancer?->fullname }}">
                                                @endif
                                            @else
                                                <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}">
                                            @endif
                                        </div>
                                        <div class="jobFilter-proposal-author-contents">
                                            <h4 class="jobFilter-proposal-author-contents-title">
                                                {{ $offer_details->freelancer?->fullname ?? '' }}
                                                <x-status.user-active-inactive-check :userID="$offer_details->freelancer->id" />
                                            </h4>
                                            <p class="jobFilter-proposal-author-contents-subtitle mt-2"> {{ $offer_details->freelancer?->user_introduction?->title ?? '' }} · <span> @if($offer_details?->freelancer?->user_state?->state != null){{ $offer_details->freelancer?->user_state?->state ?? '' }},@endif {{ $offer_details->freelancer?->user_country?->country ?? '' }}</span> @if($offer_details?->freelancer?->user_verified_status == 1) <i class="fas fa-circle-check"></i>@endif </p>
                                            <div class="jobFilter-proposal-author-contents-review mt-2">
                                                {!! freelancer_rating($offer_details->freelancer_id) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Details area end -->
        @include('frontend.user.client.job.modal.payment-gateway-modal')
    </main>

@endsection

@section('script')
    <x-frontend.payment-gateway.gateway-select-js />
    <x-sweet-alert.sweet-alert2-js/>
    <x-summernote.summernote-js />
    @include('chat::client.offer.offer-js')
@endsection
