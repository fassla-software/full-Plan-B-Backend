@extends('frontend.layout.master')
@section('site_title', __('Subscriptions'))
@section('meta_title'){{ __('Subscriptions') }}@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Subscriptions') ?? __('Subscriptions')" :innerTitle="__('Subscriptions') ?? ''" />
        <!-- Pricing area start -->
        <section class="pricing-area section-bg-2 pat-100 pab-100">
            <div class="container">
                <div class="section-title center-text">
                    <h1 class="title">{{ __('Subscription Plan') }}</h1>
                </div>
                <div class="row mt-5">
                    <div class="pricing-tabs subsription-tabs">
                        <div class="tab-parents pricing-tabs-switch justify-content-center">
                            <span data-type_id="all" class="get_subscription_type_id subsription-btn active">{{__('All')}} </span>
                            @foreach ($subscription_types as $type)
                                <span data-type_id="{{ $type->id }}" class="get_subscription_type_id subsription-btn">
                                    {{ $type->type }} </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row gy-4 mt-4 search_subscription_result">
                    @include('subscription::frontend.subscriptions.search-result')
                </div>
            </div>
        </section>
        <!-- Pricing area end -->
    </main>
    @include('subscription::frontend.subscriptions.login-markup')
    @include('subscription::frontend.subscriptions.gateway-markup')
@endsection

@section('script')
    <x-frontend.payment-gateway.gateway-select-js />
    @include('subscription::frontend.subscriptions.subscriptions-js')
@endsection
