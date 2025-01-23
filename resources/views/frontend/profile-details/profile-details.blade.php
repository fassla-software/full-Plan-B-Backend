@extends('frontend.layout.master')
@section('site_title',__('Profile Details'))
@section('style')
    <x-select2.select2-css/>
    <style>
        .rating_profile_details {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .level-badge-wrapper {
            top: 10px;
            right: 14px;
        }
        .disabled-link {
            background-color: #ccc !important;
            pointer-events: none;
            cursor: default;
        }
        [data-star] {
            text-align:left;
            font-style:normal;
            display:inline-block;
            position: relative;
            unicode-bidi: bidi-override;
        }
        [data-star]::before {
            display:block;
            content: "\f005" "\f005" "\f005" "\f005" "\f005";
            width: 100%;
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 15px;;
            color: var(--body-color);
        }
        [data-star]::after {
            white-space:nowrap;
            position:absolute;
            top:0;
            left:0;
            content: "\f005" "\f005" "\f005" "\f005" "\f005";
            width: 100%;
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 15px;;
            width: 0;
            color: var(--secondary-color);
            overflow:hidden;
            height:100%;
        }

        [data-star^="0.1"]::after{width:2%}
        [data-star^="0.2"]::after{width:4%}
        [data-star^="0.3"]::after{width:6%}
        [data-star^="0.4"]::after{width:8%}
        [data-star^="0.5"]::after{width:10%}
        [data-star^="0.6"]::after{width:12%}
        [data-star^="0.7"]::after{width:14%}
        [data-star^="0.8"]::after{width:16%}
        [data-star^="0.9"]::after{width:18%}
        [data-star^="1"]::after{width:20%}
        [data-star^="1.1"]::after{width:22%}
        [data-star^="1.2"]::after{width:24%}
        [data-star^="1.3"]::after{width:26%}
        [data-star^="1.4"]::after{width:28%}
        [data-star^="1.5"]::after{width:30%}
        [data-star^="1.6"]::after{width:32%}
        [data-star^="1.7"]::after{width:34%}
        [data-star^="1.8"]::after{width:36%}
        [data-star^="1.9"]::after{width:38%}
        [data-star^="2"]::after{width:40%}
        [data-star^="2.1"]::after{width:42%}
        [data-star^="2.2"]::after{width:44%}
        [data-star^="2.3"]::after{width:46%}
        [data-star^="2.4"]::after{width:48%}
        [data-star^="2.5"]::after{width:50%}
        [data-star^="2.6"]::after{width:52%}
        [data-star^="2.7"]::after{width:54%}
        [data-star^="2.8"]::after{width:56%}
        [data-star^="2.9"]::after{width:58%}
        [data-star^="3"]::after{width:60%}
        [data-star^="3.1"]::after{width:62%}
        [data-star^="3.2"]::after{width:64%}
        [data-star^="3.3"]::after{width:66%}
        [data-star^="3.4"]::after{width:68%}
        [data-star^="3.5"]::after{width:70%}
        [data-star^="3.6"]::after{width:72%}
        [data-star^="3.7"]::after{width:74%}
        [data-star^="3.8"]::after{width:76%}
        [data-star^="3.9"]::after{width:78%}
        [data-star^="4"]::after{width:80%}
        [data-star^="4.1"]::after{width:82%}
        [data-star^="4.2"]::after{width:84%}
        [data-star^="4.3"]::after{width:86%}
        [data-star^="4.4"]::after{width:88%}
        [data-star^="4.5"]::after{width:90%}
        [data-star^="4.6"]::after{width:92%}
        [data-star^="4.7"]::after{width:94%}
        [data-star^="4.8"]::after{width:96%}
        [data-star^="4.9"]::after{width:98%}
        [data-star^="5"]::after{width:100%}

        .project_reject_reason_description
        {
            white-space: pre-line
        }
    </style>
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Profile Details')" :innerTitle="__('Profile Details')"/>

        <!-- Profile Details area Starts -->
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row gy-4 justify-content-center">
                    <div class="col-xl-7">
                        <div class="profile-wrapper">

                            @include('frontend.profile-details.profile')

                            <div class="profile-wrapper-item radius-10">
                                <div class="profile-wrapper-details">
                                    <div class="profile-wrapper-details-single">
                                        <div class="profile-wrapper-details-single-flex">
                                            <div class="profile-wrapper-details-single-thumb">
                                                {{ site_currency_symbol() ?? '' }}
                                            </div>
                                            <div class="profile-wrapper-details-single-contents">
                                                <h4 class="profile-wrapper-details-single-contents-title"> {{ float_amount_with_currency_symbol($total_earning->total_earning ?? 0) }} </h4>
                                                <p class="profile-wrapper-details-single-contents-para"> {{ __('Total Earned') }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-wrapper-details-single">
                                        <div class="profile-wrapper-details-single-flex">
                                            <div class="profile-wrapper-details-single-thumb"><img
                                               src="{{ asset('assets/static/icons/project_complete.svg') }}" alt="{{ __('complete order') }}"></div>
                                            <div class="profile-wrapper-details-single-contents">
                                                <h4 class="profile-wrapper-details-single-contents-title"> {{ $complete_orders_in_total ?? '' }} </h4>
                                                <p class="profile-wrapper-details-single-contents-para"> {{ __('Order Completed') }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-wrapper-details-single">
                                        <div class="profile-wrapper-details-single-flex">
                                            <div class="profile-wrapper-details-single-thumb"><img
                                               src="{{ asset('assets/static/icons/active_order.svg') }}" alt="{{ __('active order') }}"></div>
                                            <div class="profile-wrapper-details-single-contents">
                                                <h4 class="profile-wrapper-details-single-contents-title"> {{ $active_orders_count ?? __('No Active Orders') }} </h4>
                                                <p class="profile-wrapper-details-single-contents-para"> {{ __('Active Orders') }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(get_static_option('project_enable_disable') != 'disable')
                            @include('frontend.profile-details.project')
                            @endif
                            @include('frontend.profile-details.experience')
                            @include('frontend.profile-details.education')
                            @include('frontend.profile-details.skill')
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="profile-details-widget sticky_top">

                            @if($complete_orders->count() >= 1)
                            <div class="profile-details-widget-single radius-10">
                                <div
                                    class="profile-wrapper-item-flex flex-between align-items-center profile-border-bottom">
                                    <h4 class="profile-wrapper-item-title"> {{ __('Reviews') }} </h4>
                                </div>
                                @foreach($complete_orders as $order)
                                   @php $rating = \App\Models\Rating::where('order_id',$order->id)->where('sender_type',1)->first() @endphp
                                    @if($rating)
                                        <div class="profile-details-widget-inner">
                                            <div class="profile-details-widget-review">
                                                <div class="rating_profile_details">
                                                    <div class="rating_profile_details_icon">
                                                        <i data-star="{{ $rating->rating }}"></i>
                                                    </div>
                                                    <span class="rating_profile_details-para">{{ $rating->rating }}</span>
                                                </div>
                                                @if($rating?->order?->project)
                                                   <h4 class="profile-details-widget-review-title mt-3">{{ $rating?->order?->project?->title }}</h4>
                                                @else
                                                    <h4 class="profile-details-widget-review-title mt-3">{{ $rating?->order?->job?->title }}</h4>
                                                @endif
                                            </div>
                                            <div class="profile-wrapper-details profile-border-top">
                                                <div class="profile-wrapper-details-single">
                                                    <span class="profile-wrapper-details-para"> {{ __('Earned') }} </span>
                                                    <h5 class="profile-wrapper-details-single-title mt-1"> {{ float_amount_with_currency_symbol($rating->order?->payable_amount) }} </h5>
                                                </div>
                                                <div class="profile-wrapper-details-single">
                                                    <span class="profile-wrapper-details-para"> {{ __('Reviewed by') }} </span>
                                                    <h5 class="profile-wrapper-details-single-title mt-1"> {{ $rating->order?->user?->fullname }} </h5>
                                                </div>
                                                <div class="profile-wrapper-details-single">
                                                    <span class="profile-wrapper-details-para"> {{ __('Reviewed') }} </span>
                                                    <h5 class="profile-wrapper-details-single-title mt-1"> {{ $rating->created_at->toFormattedDateString() }} </h5>
                                                </div>
                                            </div>
                                            <div class="profile-details-widget-single-bottom profile-border-top">
                                                <p class="profile-details-widget-single-bottom-para">{{ $rating->review_feedback }} </p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif

                            @include('frontend.profile-details.all-portfolio')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Details area end -->

        <!-- Change Portfolio Popup Starts -->
        <div class="popup-fixed change-portfolio-popup">
            <div class="popup-contents"></div>
        </div>
        <!-- Change Portfolio Popup Ends -->

    @include('frontend.profile-details.add-portfolio')
    @include('frontend.profile-details.edit-portfolio')

</main>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    <x-select2.select2-js />
    <x-frontend.payment-gateway.gateway-select-js />
    @include('frontend.profile-details.profile-details-js')
@endsection
