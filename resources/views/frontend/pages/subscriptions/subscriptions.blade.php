@extends('frontend.layout.master')
@section('site_title', __('Subscription Plan'))
@section('style')
    <x-select2.select2-css />
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Subscriptions') ?? __('Subscriptions')" :innerTitle="__('Subscriptions') ?? ''" />
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
                                <!-- Pricing area start -->
                                <section class="pricing-area pat-50 pab-50">
                                    <div class="container">
                                        <div class="section-title center-text">
                                            <h2 class="title">{{ __('Subscription Plan') }} </h2>
                                        </div>

                                        <div class="tab-content-item" id="monthly">
                                            <div class="row gy-4 mt-4">
                                                @foreach ($subscription_types as $type)
                                                    @if ($type->type == 'Monthly')
                                                        @foreach ($type->subscriptions as $subscription)
                                                            <div class="col-xxl-4 col-lg-4 col-md-6">
                                                                <div class="single-pricing single-pricing-border radius-10">
                                                                    <div
                                                                        class="single-pricing-top d-flex gap-3 flex-wrap align-items-center">
                                                                        <div class="single-pricing-brand">
                                                                            {!! render_image_markup_by_attachment_id($subscription->logo) !!}
                                                                        </div>
                                                                        <div class="single-pricing-top-contents">
                                                                            <h5 class="single-pricing-title">
                                                                                {{ $subscription->title }} </h5>
                                                                        </div>
                                                                    </div>
                                                                    <ul class="single-pricing-list list-style-none">
                                                                        @foreach ($subscription->features as $feature)
                                                                            @if ($feature->status == 'on')
                                                                                <li class="single-pricing-list-item">
                                                                                    <span
                                                                                        class="single-pricing-list-item-icon">
                                                                                        <i class="fa-solid fa-check"></i>
                                                                                    </span> {{ $feature->feature }}
                                                                                </li>
                                                                            @else
                                                                                <li class="single-pricing-list-item">
                                                                                    <span
                                                                                        class="single-pricing-list-item-icon cross-icon">
                                                                                        <i class="fa-solid fa-xmark"></i>
                                                                                    </span>{{ $feature->feature }}
                                                                                </li>
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                    <h3 class="single-pricing-price">
                                                                        {{ float_amount_with_currency_symbol($subscription->price) }}
                                                                        <sub>/{{ ucfirst($type->type) }}</sub>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Pricing area end -->
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
    @include('frontend.pages.subscriptions.subscriptions-js')
    <x-select2.select2-js />
@endsection
