@extends('frontend.layout.master')
@section('site_title', __('Order Details'))
@section('style')
    <x-summernote.summernote-css />
    <style>
        .user-details-manage-list {display: flex;flex-direction: column;gap: 10px}
        .myOrder-single-content-para,
        .show_order_submit_description
        {white-space: pre-line}
    </style>
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Order Details')" :innerTitle="__('Order Details')"/>

        <!-- Profile Details area Starts -->
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="myOrder-single bg-white padding-20 radius-10">
                            <div class="myOrder-single-item">
                                <x-validation.error />
                                <div class="myOrder-single-flex">
                                    <div class="myOrder-single-content">
                                        <span class="myOrder-single-content-id">#000{{ $order_details->id }}</span>
                                        <h4 class="myOrder-single-content-title mt-2">
                                            @if($order_details->is_project_job == 'project')
                                                <a href="javascript:void(0)"> {{ $order_details?->project->title ?? '' }} </a>
                                            @elseif($order_details->is_project_job == 'job')
                                                <a href="javascript:void(0)">{{ $order_details?->job->title ?? '' }}</a>
                                            @else
                                                {{ __('Custom order')}}
                                            @endif
                                        </h4>
                                        <div class="myOrder-single-content-btn flex-btn mt-3">
                                            <x-order.order-status :status="$order_details->status" />
                                            <x-order.is-custom :isCustom="$order_details->is_project_job" />
                                            <x-order.payment-verify :paymentVerifyCheck="$order_details" />
                                        </div>
                                    </div>
                                    <span class="myOrder-single-content-time">{{ $order_details->created_at->diffForHumans() }} </span>
                                </div>
                            </div>
                            <div class="myOrder-single-item">
                                <div class="myOrder-single-block">
                                    <div class="myOrder-single-block-item">
                                        <div class="myOrder-single-block-item-content">
                                            @if($order_details->is_fixed_hourly == 'hourly')
                                                <span class="myOrder-single-block-subtitle">{{ __('Hourly Rate') }}</span>
                                                <h6 class="myOrder-single-block-title mt-2">
                                                    {{ float_amount_with_currency_symbol($order_details?->job->hourly_rate) }}
                                                </h6>
                                            @else
                                                <span class="myOrder-single-block-subtitle">{{ __('Order Budget') }}</span>
                                                <h6 class="myOrder-single-block-title mt-2">
                                                    {{ float_amount_with_currency_symbol($order_details->price) }}
                                                    <x-order.is-funded :isFunded="$order_details->payment_status" :paymentGateway="$order_details->payment_gateway" />
                                                </h6>
                                            @endif
                                        </div>
                                    </div>
                                    @if($order_details->delivery_time)
                                    <div class="myOrder-single-block-item">
                                        <div class="myOrder-single-block-item-content">
                                            <span class="myOrder-single-block-subtitle">{{ __('Delivery Time') }}</span>
                                            <x-order.deadline :deadline="$order_details->delivery_time ?? '' " />
                                        </div>
                                    </div>
                                    @endif

                                    @php
                                        $complete_orders = \App\Models\Order::where('user_id',$order_details->user_id)
                                            ->where('status',3)
                                            ->count();
                                        $active_orders = \App\Models\Order::where('user_id',$order_details->user_id)
                                            ->where('status',1)
                                            ->count();
                                    @endphp
                                    <div class="myOrder-single-block-item">
                                        <div class="myOrder-single-block-item-content">
                                            <span class="myOrder-single-block-subtitle">{{ __('Complete Orders') }}</span>
                                            <h6 class="myOrder-single-block-title mt-2">{{ $complete_orders }}</h6>
                                        </div>
                                    </div>
                                    <div class="myOrder-single-block-item">
                                        <div class="myOrder-single-block-item-content">
                                            <span class="myOrder-single-block-subtitle">{{ __('Active Orders') }}</span>
                                            <h6 class="myOrder-single-block-title mt-2">{{ $active_orders }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="myOrder-single-item">
                                <div class="myOrder-single-flex flex-between">
                                    <div class="btn-wrapper flex-btn">
                                        @if($order_details?->freelancer?->is_suspend !=1)
                                            <form action="{{ route('freelancer.message.send') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="client_id" id="client_id" value="{{ $order_details->user_id }}">
                                                <input type="hidden" name="from_user" id="from_user" value="1">
                                                <input type="hidden" name="project_id" id="project_id" value="{{ $order_details->identity }}">
                                                <input type="hidden" name="order_id" id="order_id" value="{{ $order_details->id }}">
                                                <button type="submit" class="btn-profile btn-outline-1"> {{ __('Send Message') }}</button>
                                            </form>
                                        @if($order_details->status == 3)
                                             <a href="{{ route('freelancer.order.invoice.generate',$order_details->id) }}" class="btn-profile btn-outline-1">{{ __('Invoice') }}</a>
                                             <a href="{{ route('freelancer.order.rating',$order_details->id) }}" class="btn-profile btn-bg-1">{{ __('Submit Review') }}</a>
                                            @endif
                                        @endif

                                        @if($order_details->status != 3)
                                            @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 0)
                                                <a href="{{ route('freelancer.order.time.tracker',$order_details->id) }}" class="btn-profile btn-bg-1">{{ __('Start Tracking') }}</a>
                                            @endif
                                        @endif

                                        @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 0)
                                            <a href="{{ route('freelancer.order.work.history',$order_details->id) }}" class="btn-profile btn-bg-1">{{ __('Work History') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="myOrder-single bg-white padding-20 radius-10">
                            <div class="row g-4">
                                @php
                                    $mile_stones = \App\Models\OrderMilestone::where('order_id',$order_details->id)->get();
                                    $payable_amount = \App\Models\OrderMilestone::where('order_id',$order_details->id)->where('status','!=',3)->sum('price');
                                @endphp

                                <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                    <div class="myJob-wrapper-single-balance">
                                        <div class="myJob-wrapper-single-balance-contents">
                                            <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                @if($order_details->status === 3)
                                                    <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details->payable_amount) }}</h4>
                                                @else
                                                    @php $earnings = \App\Models\OrderMilestone::where('order_id',$order_details->id)->where('status',2)->sum('price'); @endphp
                                                    <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($earnings)  }}</h4>
                                                @endif
                                                <span class="myJob-wrapper-single-balance-icon hover-question">
                                                    <i class="fa-solid fa-question"></i>
                                                    <span class="hover-active-content">{{ __('Earned balance means how much amount you have received for this order.') }}</span>
                                                </span>
                                            </div>
                                            <p class="myJob-wrapper-single-balance-para">{{ __('Earned Balance') }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 3)
                                    <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                        <div class="myJob-wrapper-single-balance">
                                            <div class="myJob-wrapper-single-balance-contents">
                                                <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                   <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details?->job->hourly_rate) }} </h4>
                                                    <span class="myJob-wrapper-single-balance-icon hover-question">
                                                        <i class="fa-solid fa-question"></i>
                                                        <span class="hover-active-content">{{ __('Hourly rate means how much amount client will pay for each hour after complete the order.') }}</span>
                                                    </span>
                                                </div>
                                                <p class="myJob-wrapper-single-balance-para">{{ __('Hourly Rate') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                    <div class="myJob-wrapper-single-balance">
                                        <div class="myJob-wrapper-single-balance-contents">
                                            <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                @if($mile_stones->count() > 0)
                                                    @if($order_details->status !=3)
                                                    <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($payable_amount - $earnings) }} </h4>
                                                    @else
                                                        <h4 class="contract_single__balance-price">{{ site_currency_symbol() }} 0</h4>
                                                    @endif
                                                @else
                                                    @if($order_details->status != 3 && $order_details->payment_status != '')
                                                        <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details->payable_amount) }}</h4>
                                                    @else
                                                        <h4 class="contract_single__balance-price">{{ site_currency_symbol() }} 0</h4>
                                                    @endif
                                                @endif
                                                <span class="myJob-wrapper-single-balance-icon hover-question">
                                                    <i class="fa-solid fa-question"></i>
                                                    <span class="hover-active-content">{{ __('Pending amount means how much amount you will get after complete this order.') }}</span>
                                                </span>
                                            </div>
                                            <p class="myJob-wrapper-single-balance-para">{{ __('Pending Balance') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 3)
                                    <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                    <div class="myJob-wrapper-single-balance">
                                        <div class="myJob-wrapper-single-balance-contents">
                                            <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                <span class="price-title">{{ $order_details?->job->estimated_hours }}</span>
                                                <span class="myJob-wrapper-single-balance-icon hover-question">
                                                    <i class="fa-solid fa-question"></i>
                                                    <span class="hover-active-content">{{ __('Estimated hours refer to the approximate time a client can set for completing the order. The client can adjust this time before accepting the order.') }}</span>
                                                </span>
                                            </div>
                                            <p class="myJob-wrapper-single-balance-para">{{ __('Estimated Hours') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                            <div class="myJob-wrapper-single-balance">
                                                <div class="myJob-wrapper-single-balance-contents">
                                                    <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                        <span class="price-title">{{ float_amount_with_currency_symbol($order_details->commission_amount) }}</span>
                                                        <span class="myJob-wrapper-single-balance-icon hover-question">
                                                    <i class="fa-solid fa-question"></i>
                                                    <span class="hover-active-content">{{ __('Commission amount means how much amount admin will get from this order.') }}</span>
                                                </span>
                                                    </div>
                                                    <p class="myJob-wrapper-single-balance-para">{{ __('Commission Amount') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                @endif

                                @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 3)
                                    <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                    <div class="myJob-wrapper-single-balance">
                                        <div class="myJob-wrapper-single-balance-contents">
                                            <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                <span class="price-title">{{ float_amount_with_currency_symbol($order_details->price) }}</span>
                                                <span class="myJob-wrapper-single-balance-icon hover-question">
                                                    <i class="fa-solid fa-question"></i>
                                                    <span class="hover-active-content">{{ __('The approximate budget indicates the expected payment for this order. This amount may vary depending on the rate and the estimated working hours.') }}</span>
                                                </span>
                                            </div>
                                            <p class="myJob-wrapper-single-balance-para">{{ __('Approximate  Budget') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                    <div class="myJob-wrapper-single-balance">
                                        <div class="myJob-wrapper-single-balance-contents">
                                            <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                <span class="price-title">{{ float_amount_with_currency_symbol($order_details->price) }}</span>
                                                <span class="myJob-wrapper-single-balance-icon hover-question">
                                                    <i class="fa-solid fa-question"></i>
                                                    <span class="hover-active-content">{{ __('Total budget means how much client will pay for this order.') }}</span>
                                                </span>
                                            </div>
                                            <p class="myJob-wrapper-single-balance-para">{{ __('Total Budget') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="myJob-tabs mt-5">
                            <ul class="tabs">
                                @if($mile_stones->count() > 0)
                                    <li data-tab="Milestones" class="active">{{ __('Milestones') }}</li>
                                    <li data-tab="Description"> {{ __('Description & Requirements') }} </li>
                                @else
                                    <li data-tab="Description" class="active"> {{ __('Description & Requirements') }} </li>
                                @endif
                                <li data-tab="Works"> {{ __('Works Submitted') }} </li>
                            </ul>

                            @if($mile_stones->count() > 0)
                                <div class="tab-content-item active mt-4" id="Milestones">
                                    <div class="myJob-wrapper-single">
                                    <div class="myJob-wrapper-single-header profile-border-bottom">
                                        <h4 class="myJob-wrapper-single-title">{{ __('Milestone') }}</h4>
                                    </div>
                                    <div class="myJob-wrapper-single-milestone milestone-contractor-parent">
                                    @foreach($mile_stones as $mile_stone)
                                        <div class="myJob-wrapper-single-milestone-item">
                                            <div class="myJob-wrapper-single-flex flex-between align-items-start">
                                                <x-order.milestone-details
                                                    :id="$mile_stone->id"
                                                    :orderID="$order_details->id"
                                                    :clientID="$order_details->user_id"
                                                    :title="$mile_stone->title"
                                                    :price="$mile_stone->price"
                                                    :status="$mile_stone->status"
                                                    :deadline="$mile_stone->deadline"
                                                    :description="$mile_stone->description"
                                                />
                                                <div class="myJob-wrapper-single-right">
                                                    <div class="myJob-wrapper-single-right-flex">
                                                        <x-order.is-funded :isFunded="$order_details->payment_status" :paymentGateway="$order_details->payment_gateway" />
                                                        <span class="myJob-wrapper-single-fixed danger">{{ __('Revision:') }} {{ $mile_stone->revision ?? '' }}</span>
                                                        <span class="myJob-wrapper-single-fixed danger">{{ __('Revision Left:') }} {{ $mile_stone->revision_left ?? '' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($mile_stones->count() > 0)
                                <div class="tab-content-item mt-4" id="Description">
                            @else
                                <div class="tab-content-item mt-4 active" id="Description">
                            @endif
                                <div class="myOrder-single bg-white padding-20 radius-10">
                                    <div class="myOrder-single-item">
                                        <div class="myOrder-single-content">
                                            <p class="myOrder-single-content-para">{{  $order_details->description ?? __('No description.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content-item mt-4" id="Works">
                                <div class="pay-now-single">
                                    <h4 class="pay-now-single-title">{{ __('Work Submitted') }}</h4>
                                    <div class="pay-now-single-contents profile-border-top">
                                        @if($order_details?->order_submit_history?->count() > 0)
                                            @foreach($order_details->order_submit_history as $history)
                                            <div class="pay-now-single-contents-work">
                                                <div class="pay-now-single-contents-work-flex">
                                                    <div class="pay-now-single-contents-work-item">
                                                        <span class="pay-now-single-contents-work-date">{{ $history->created_at->toFormattedDateString() }}</span>
                                                    </div>
                                                    <div class="pay-now-single-contents-work-item">
                                                        <div class="single-refundRequest-item">
                                                            <a href="{{ asset('assets/uploads/attachment/order/'.$history->attachment) }}" download class="single-refundRequest-item-uploads">
                                                                <i class="fa-solid fa-cloud-arrow-down"></i>
                                                                {{ __('Download Attachment') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="pay-now-single-contents-work-item">
                                                        <div class="pay-now-single-contents-work-item-status">
                                                            @if($history->status === 0)
                                                                <span class="milestone-approved ">{{  __('Pending') }}</span>

                                                            @elseif($history->status === 1)
                                                                <span class="myJob-wrapper-single-fixed active">{{ __('Approved') }}</span>
                                                            @elseif($history->status === 2)
                                                                <span class="btn myJob-wrapper-single-fixed danger show_revision_details"
                                                                      data-bs-target="#RevisionDetailsModal"
                                                                      data-bs-toggle="modal"
                                                                      data-revision_id="{{ $history->request_revision?->id }}"
                                                                      data-revision_description="{{ $history->request_revision?->description }}">
                                                                    {{ __('Revision Details') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="pay-now-single-contents-work-item">
                                                        <div class="pay-now-single-contents-work-item-btn">
                                                            <a href="javascript:void(0)"
                                                               class="pay-now-single-contents-work-viewMore order_submit_description"
                                                               data-description="{{ $history->description }}"
                                                               data-order_milestone_id="{{ $history->order_milestone_id }}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#OrderSubmitDescriptionModal">
                                                                {{ __('Description') }}
                                                                <i class="fa-solid fa-angle-right"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @else
                                            <p>{{ __('No work submitted') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="myOrder-single-item mt-4">
                                <div class="myOrder-single-flex flex-between">
                                    @php
                                        $check_order_has_report_by_freelancer = \App\Models\Report::where('freelancer_id',$order_details->freelancer_id)
                                        ->where('order_id',$order_details->id)
                                        ->where('reporter','freelancer')
                                        ->first();
                                    @endphp
                                    @if(empty($check_order_has_report_by_freelancer))
                                        @if($order_details->status == 3 || $order_details->status == 4)
                                            @if($order_details?->freelancer?->is_suspend !=1)
                                                <a href="javascript:void(0)"
                                                   data-order-id="{{ $order_details->id }}"
                                                   data-client-id="{{ $order_details->user_id }}"
                                                   class="btn-profile btn-bg-cancel btn-hover-danger open_order_report_modal"
                                                   data-bs-target="#reportModal"
                                                   data-bs-toggle="modal"
                                                >{{ __('Report Order') }}
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                       <span class="btn-profile btn-bg-cancel"> {{ __('Reported') }}</span>
                                    @endif

                                    <div class="btn-wrapper flex-btn">
                                        @if($order_details->status == 0)
                                            <x-status.table.status-change
                                                :title="__('Decline Order')"
                                                :class="'btn-profile btn-bg-cancel decline_and_change_order_status'"
                                                :value="__('decline')"
                                                :url="route('freelancer.order.decline',$order_details->id)"/>
                                            <x-status.table.status-change
                                                :title="__('Accept Order')"
                                                :class="'btn-profile btn-bg-1 accept_and_change_order_status'"
                                                :url="route('freelancer.order.accept',$order_details->id)"/>
                                        @else
                                            @if($order_details->status != 5 && $order_details->status != 4 && $order_details->status != 3 && $order_details->status != 7)
                                                <x-status.table.status-change
                                                    :title="__('Cancel Order')"
                                                    :class="'btn-profile btn-bg-cancel cancel_and_change_order_status'"
                                                    :value="__('cancel')"
                                                    :url="route('freelancer.order.decline',$order_details->id)"/>
                                            @endif
                                            @if($mile_stones->count() <= 0)
                                                @if(Auth::guard('web')->user()->user_type == 2 && $order_details->status == 1)
                                                    <a href="javascript:void(0)"
                                                       class="btn-profile btn-bg-1 order_submit"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#orderSubmitModal"
                                                       data-order_id="{{ $order_details->id }}"
                                                       data-order_milestone_id="{{ $id ?? '' }}"
                                                       data-client_id="{{ $order_details->user_id  }}"
                                                    >
                                                        {{ __('Submit') }}
                                                    </a>
                                                @endif
                                                @if(Auth::guard('web')->user()->user_type == 1 && $order_details->status == 1 && Session::get('user_role') == 'freelancer')
                                                    <a href="javascript:void(0)"
                                                       class="btn-profile btn-bg-1 order_submit"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#orderSubmitModal"
                                                       data-order_id="{{ $order_details->id }}"
                                                       data-order_milestone_id="{{ $id ?? '' }}"
                                                       data-client_id="{{ $order_details->user_id  }}"
                                                    >
                                                        {{ __('Submit') }}
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                        <div class="col-lg-4">
                            <div class="profile-details-widget sticky_top_lg">
                                <div class="jobFilter-wrapper-item">
                                    <div class="jobFilter-about-clients">
                                        <div class="jobFilter-proposal-author-flex">
                                        <span class="user-details-manage-thumb">
                                            <div class="myOrder-single-block-item-author">
                                                <x-order.profile-image :image="$order_details?->user->image" :loadFrom="$order_details?->user->load_from" />
                                            </div>
                                        </span>
                                        <div class="jobFilter-proposal-author-contents">
                                            <h5 class="single-freelancer-author-name">
                                                {{ $order_details?->user->first_name }}
                                                {{ $order_details?->user->last_name }}
                                                @if(Cache::has('user_is_online_' . $order_details?->user->id))
                                                    <span class="single-freelancer-author-status"> {{ __('Active') }} </span>
                                                @else
                                                    <span class="single-freelancer-author-status-ofline"> {{ __('Inactive') }} </span>
                                                @endif
                                            </h5>
                                            <p class="jobFilter-proposal-author-contents-subtitle mt-2">
                                                @if($order_details?->user?->user_state?->state != null)
                                                {{ $order_details?->user?->user_state?->state }},
                                                @endif
                                                {{ $order_details?->user?->user_country?->country }}
                                                @if($order_details?->user?->user_verified_status == 1) <i class="fas fa-circle-check"></i>@endif

                                            </p>
                                        </div>
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
                                                {{ $order_details?->user->created_at->toFormattedDateString() ?? '' }}
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
                                            <h6 class="jobFilter-wrapper-item-completed">{{ $order_details?->user?->user_jobs?->count() }}</h6>
                                        </div>
                                    </div>

                                    @php
                                        $total_job = App\Models\JobPost::where('user_id', $order_details?->user->id)->count();
                                        $total_order = App\Models\Order::where('user_id', $order_details?->user->id)
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
                                                <h6 class="jobFilter-wrapper-item-completed"> @if($hiring_rate > 100) 100% @else {{ round($hiring_rate) ?? 0 }}% @endif
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
        </div>
        <!-- Profile Details area end -->
    </main>

    @include('frontend.user.freelancer.order.order-submit')
    @include('frontend.user.freelancer.order.revision-details')
    @include('frontend.user.freelancer.order.report-modal')
    @include('frontend.user.freelancer.order.order-submit-description')

@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    <x-summernote.summernote-js />
    @include('frontend.user.freelancer.order.order-js')
@endsection
