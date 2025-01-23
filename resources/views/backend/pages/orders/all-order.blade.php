@extends('backend.layout.master')
@section('title', __('All Orders'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <x-notice.general-notice :description="__('Notice: You can manage all orders from here.')" :description1="__('Notice: You can search by id, order type, create date.')" :description2="__('Notice: Order type refers to whether the order was generated from a project or a job.')" :description3="__('Notice: The admin has the ability to update the payment status for transactions that are pending.')" :description4="__('Notice: Invoices are available only for complete order.')" />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Orders') }}</h4>
                            <x-search.search-in-table :placeholder="__('Search by ....')" :id="'string_search'" />
                        </div>
                        <div class="allOrders__list">
                            <input type="hidden" id="get_selected_status_value" value="">
                            @can('order-queue')
                                <button class="order_sort_by_status allOrders__list__item" data-val="qutem">
                                    {{ __('Queue') }} ({{ $pending_orders->total() }})</button>
                            @endcan
                            @can('order-active')
                                <button class="order_sort_by_status allOrders__list__item" data-val="1">
                                    {{ __('Active') }} ({{ $active_orders->total() }})</button>
                            @endcan
                            @can('order-deliver')
                                <button class="order_sort_by_status allOrders__list__item" data-val="2">
                                    {{ __('Delivered') }} ({{ $deliver_orders->total() }})</button>
                            @endcan
                            @can('order-complete')
                                <button class="order_sort_by_status allOrders__list__item" data-val="3">
                                    {{ __('Complete') }} ({{ $complete_orders->total() }})</button>
                            @endcan
                            @can('order-cancel')
                                <button class="order_sort_by_status allOrders__list__item" data-val="4">
                                    {{ __('Cancel') }} ({{ $cancel_orders->total() }})</button>
                            @endcan
                            @can('order-decline')
                                <button class="order_sort_by_status allOrders__list__item" data-val="5">
                                    {{ __('Decline') }} ({{ $decline_orders->total() }})</button>
                            @endcan
                            @can('order-hold')
                                <button class="order_sort_by_status allOrders__list__item" data-val="7">
                                    {{ __('Hold') }} ({{ $hold_orders->total() }})</button>
                            @endcan
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('backend.pages.orders.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.orders.manual-payment-modal')
    <x-media.markup />
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    @include('backend.pages.orders.order-js')
@endsection
