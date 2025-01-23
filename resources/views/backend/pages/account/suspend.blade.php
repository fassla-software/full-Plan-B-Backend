@extends('backend.layout.master')
@section('title', __('Suspend Account'))
@section('content')
    <div class="dashboard__body">
        <div class="dashboard__inner">
            <div class="row g-4">
                <div class="col-xxl-8 col-lg-12">
                    <div class="topic bg-white padding-20 radius-10">
                        <div class="topic__inner">
                            <div class="topic_single">
                                <div class="topic_single__header flex-between">
                                    @if($order->freelancer?->is_suspend == 1 && $order->user?->is_suspend == 1)
                                        <h4 class="topic_single__title text-danger">{{ __('Both account of client and freelancer has suspended.') }}</h4>
                                    @else
                                        <h4 class="topic_single__title">{{ __('Choose whom account you want to suspend.') }}</h4>
                                    @endif
                                    <x-notice.general-notice
                                        :description="__('Notice: Suspended user will complete his active and delivered order.')"
                                        :description1="__('Notice: User can withdraw his balance.')"
                                        :description2="__('Notice: User will not able to receive any new order and bid any job.')"
                                    />
                                </div>
                                <ul class="dashboard-tabs topic_single__row mt-4">
                                    <li @class(["topic_single__item",$order->freelancer?->is_suspend == 1 ? "d-none" : ''])  data-tab="Account">
                                        <div class="topic_single__icon">
                                            <i class="fa-regular fa-user"></i>
                                        </div>
                                        <div class="topic_single__contents mt-2">
                                            <h4 class="topic_single__contents__title"><a href="javascript:void(0)">{{ __('Freelancer') }}</a></h4>
                                        </div>
                                    </li>
                                    <li @class(["topic_single__item",$order->user?->is_suspend == 1 ? "d-none" : ''])  data-tab="Proposals">
                                        <div class="topic_single__icon">
                                            <i class="fa-solid fa-file-signature"></i>
                                        </div>
                                        <div class="topic_single__contents mt-2">
                                            <h4 class="topic_single__contents__title"><a href="javascript:void(0)">{{ __('Client') }}</a></h4>
                                        </div>
                                    </li>
                                </ul>
                                <div class="proposal_wrapper mt-4">
                                    <div class="proposal__footer profile-border-top">
                                        <div class="dashboard-tab-content-item" id="Account">
                                            <div class="single_proposal">
                                                <div class="single_proposal__item">
                                                    <div class="single_proposal__item__left">
                                                        <h4 class="single_proposal__item__title mb-3">{{ __('Freelancer Details') }}</h4>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('Active Order:') }}</strong> {{ $freelancer_active_order }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('Deliver Order:') }}</strong> {{ $freelancer_deliver_order }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('Remaining Balance:') }}</strong> {{ float_amount_with_currency_symbol($freelancer_remaining_balance->remaining_balance) }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('Wallet Balance:') }}</strong> {{ float_amount_with_currency_symbol($freelancer_wallet_balance->balance) }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('Name:') }}</strong> {{ $order->freelancer?->fullname }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('Email:') }}</strong> {{ $order->freelancer?->email }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('Phone:') }}</strong> {{ $order->freelancer?->phone }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('Country:') }}</strong> {{ $order->freelancer?->user_country?->country }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('State:') }}</strong> {{ $order->freelancer?->user_state?->state }}</p>
                                                        <p class="single_proposal__item__para mt-3"><strong>{{ __('City:') }}</strong> {{ $order->freelancer?->user_city?->city }}</p>
                                                    </div>
                                                    <div class="single_proposal__item__action">
                                                        <div class="single_proposal__item__action__flex">
                                                            <x-status.table.status-change
                                                                :title="__('Suspend')"
                                                                :class="'btn-profile btn-bg-cancel suspend_user_account'"
                                                                :url="route('admin.account.suspend',$order->freelancer?->id)"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dashboard-tab-content-item" id="Proposals">
                                            <div class="single_proposal">
                                                <div class="single_proposal__item">
                                                    <div class="single_proposal__item__left">
                                                        <div class="single_proposal__item__left">
                                                            <h4 class="single_proposal__item__title mb-3">{{ __('Client Details') }}</h4>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('Active Order:') }}</strong> {{ $client_active_order }}</p>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('Deliver Order:') }}</strong> {{ $client_deliver_order }}</p>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('Wallet Balance:') }}</strong> {{ float_amount_with_currency_symbol($client_wallet_balance->balance) }}</p>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('Name:') }}</strong> {{ $order->user?->fullname }}</p>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('Email:') }}</strong> {{ $order->user?->email }}</p>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('Phone:') }}</strong> {{ $order->user?->phone }}</p>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('Country:') }}</strong> {{ $order->user?->user_country?->country }}</p>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('State:') }}</strong> {{ $order->user?->user_state?->state }}</p>
                                                            <p class="single_proposal__item__para mt-3"><strong>{{ __('City:') }}</strong> {{ $order->user?->user_city?->city }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="single_proposal__item__action">
                                                        <div class="single_proposal__item__action__flex">
                                                            <x-status.table.status-change
                                                                :title="__('Suspend')"
                                                                :class="'btn-profile btn-bg-cancel suspend_user_account'"
                                                                :url="route('admin.account.suspend',$order->user?->id)"/>
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
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    @include('backend.pages.account.account-js')
@endsection
