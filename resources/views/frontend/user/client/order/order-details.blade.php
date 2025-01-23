@extends('frontend.layout.master')
@section('site_title')
    {{ __('Order Details') }}
@endsection
@section('style')
    <x-summernote.summernote-css />
    <style>
        .user-details-manage-list {
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        .myOrder-single-content-para,
        .show_order_submit_description
        {
            white-space: pre-line
        }
    </style>
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Order Details')" :innerTitle="__('Order Details')" />

        <!-- Profile Details area Starts -->
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="myOrder-single bg-white padding-20 radius-10">
                            <div class="myOrder-single-item">
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
                                    <span
                                        class="myOrder-single-content-time">{{ $order_details->created_at->diffForHumans() }}
                                    </span>
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
                                                <span class="myOrder-single-block-subtitle">{{ __('Order budget') }}</span>
                                                <h6 class="myOrder-single-block-title mt-2">
                                                    {{ float_amount_with_currency_symbol($order_details->price) }}
                                                    <x-order.is-funded :isFunded="$order_details->payment_status" :paymentGateway="$order_details->payment_gateway" />
                                                </h6>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($order_details->delivery_time)
                                        <div class="myOrder-single-block-item">
                                            <div class="myOrder-single-block-item-content">
                                                <span class="myOrder-single-block-subtitle">{{ __('Delivery Time') }}</span>
                                                <x-order.deadline :deadline="$order_details->delivery_time ?? ''" />
                                            </div>
                                        </div>
                                    @endif

                                    @php
                                        $complete_orders = \App\Models\Order::where('freelancer_id',$order_details->freelancer_id)
                                            ->where('status',3)
                                            ->count();
                                    $active_orders = \App\Models\Order::where('freelancer_id',$order_details->freelancer_id)
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
                                    @php
                                        $mile_stones = \App\Models\OrderMilestone::where('order_id', $order_details->id)->get();
                                        $payable_amount = \App\Models\OrderMilestone::where('order_id', $order_details->id)
                                            ->where('status', '!=', 3)
                                            ->sum('price');
                                    @endphp
                                    <div class="btn-wrapper flex-btn">
                                        @if ($mile_stones->isEmpty())
                                            <span class="myJob-wrapper-single-fixed danger">{{ __('Revision:') }}
                                                {{ $order_details->revision }}</span>
                                            <span class="myJob-wrapper-single-fixed danger">{{ __('Revision Left:') }}
                                                {{ $order_details->revision_left }}</span>
                                        @endif
                                    </div>
                                    <div class="btn-wrapper flex-btn">
                                        @if ($order_details?->user?->is_suspend != 1)
                                            <form action="{{ route('client.message.send') }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="freelancer_id" id="freelancer_id"
                                                    value="{{ $order_details->freelancer_id }}">
                                                <input type="hidden" name="from_user" id="from_user" value="1">
                                                <input type="hidden" name="project_id" id="project_id"
                                                    value="{{ $order_details->identity }}">
                                                <input type="hidden" name="order_id" id="order_id"
                                                       value="{{ $order_details->id }}">
                                                <button type="submit" class="btn-profile btn-outline-1">
                                                    {{ __('Send Message') }}</button>
                                            </form>
                                            @if ($order_details->status == 3)
                                                <a href="{{ route('client.order.invoice.generate',$order_details->id) }}" class="btn-profile btn-outline-1">{{ __('Invoice') }}</a>
                                                <a href="{{ route('client.order.rating', $order_details->id) }}"
                                                    class="btn-profile btn-bg-1">{{ __('Submit Review') }}</a>
                                            @endif
                                        @endif
                                    </div>
                                    @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 0)
                                        <a href="{{ route('client.order.work.history',$order_details->id) }}" class="btn-profile btn-bg-1">{{ __('Work History') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="myOrder-single bg-white padding-20 radius-10">
                            <div class="row g-4">
                                @if(get_static_option('commission_disable_client_panel') != 'disable')

                                    <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                        <div class="myJob-wrapper-single-balance">
                                            <div class="myJob-wrapper-single-balance-contents">
                                                <div
                                                    class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                    @if ($order_details->status === 3)
                                                        <h4 class="contract_single__balance-price">
                                                            {{ float_amount_with_currency_symbol($order_details->payable_amount) }}
                                                        </h4>
                                                    @else
                                                        @php
                                                            $earnings = \App\Models\OrderMilestone::where('order_id', $order_details->id)
                                                                ->where('status', 2)
                                                                ->sum('price');
                                                        @endphp
                                                        <h4 class="contract_single__balance-price">
                                                            {{ float_amount_with_currency_symbol($earnings) }}</h4>
                                                    @endif
                                                    <span class="myJob-wrapper-single-balance-icon hover-question">
                                                        <i class="fa-solid fa-question"></i>
                                                        <span
                                                            class="hover-active-content">{{ __('Earned balance means how much amount freelancer have received for this order.') }}</span>
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
                                                <div
                                                    class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                    @php
                                                        $mile_stones = \App\Models\OrderMilestone::where('order_id', $order_details->id)->get();
                                                        $payable_amount = \App\Models\OrderMilestone::where('order_id', $order_details->id)
                                                            ->where('status', '!=', 3)
                                                            ->sum('price');
                                                    @endphp
                                                    @if ($mile_stones->count() > 0)
                                                        @if ($order_details->status != 3)
                                                            <h4 class="contract_single__balance-price">
                                                                {{ float_amount_with_currency_symbol($payable_amount - $earnings) }}
                                                            </h4>
                                                        @else
                                                            <h4 class="contract_single__balance-price">
                                                                {{ site_currency_symbol() }} 0</h4>
                                                        @endif
                                                    @else
                                                        @if ($order_details->status != 3 && $order_details->status != 4 && $order_details->payment_status != '')
                                                            <h4 class="contract_single__balance-price">
                                                                {{ float_amount_with_currency_symbol($order_details->payable_amount) }}
                                                            </h4>
                                                        @else
                                                            <h4 class="contract_single__balance-price">
                                                                {{ site_currency_symbol() }} 0</h4>
                                                        @endif
                                                    @endif

                                                    <span class="myJob-wrapper-single-balance-icon hover-question">
                                                        <i class="fa-solid fa-question"></i>
                                                        <span
                                                            class="hover-active-content">{{ __('Pending amount means how much amount Freelancer will get after complete this order.') }}</span>
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
                                            <div
                                                class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                <span
                                                    class="price-title">{{ float_amount_with_currency_symbol($order_details->commission_amount) }}</span>
                                                <span class="myJob-wrapper-single-balance-icon hover-question">
                                                    <i class="fa-solid fa-question"></i>
                                                    <span
                                                        class="hover-active-content">{{ __('Commission amount means how much amount admin will get from this order.') }}</span>
                                                </span>
                                            </div>
                                            <p class="myJob-wrapper-single-balance-para">{{ __('Commission Amount') }}</p>
                                        </div>
                                    </div>
                                </div>
                                    @endif

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
                                            <div
                                                class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                <span
                                                    class="price-title">{{ float_amount_with_currency_symbol($order_details->price) }}</span>
                                                <span class="myJob-wrapper-single-balance-icon hover-question">
                                                    <i class="fa-solid fa-question"></i>
                                                    <span
                                                        class="hover-active-content">{{ __('Total budget means how much you will pay for this order.') }}</span>
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
                                @if ($mile_stones->count() > 0)
                                    <li data-tab="Milestones" class="active">{{ __('Milestones') }}</li>
                                    <li data-tab="Description"> {{ __('Description & Requirements') }} </li>
                                @else
                                    <li data-tab="Description" class="active"> {{ __('Description & Requirements') }}
                                    </li>
                                @endif
                                <li data-tab="Works"> {{ __('Works Submitted') }} </li>
                            </ul>

                            @if ($mile_stones->count() > 0)
                                <div class="tab-content-item active mt-4" id="Milestones">
                                    <div class="myJob-wrapper-single">
                                        <div class="myJob-wrapper-single-header profile-border-bottom">
                                            <h4 class="myJob-wrapper-single-title">{{ __('Milestone') }}</h4>
                                        </div>
                                        <div class="myJob-wrapper-single-milestone milestone-contractor-parent">
                                            @foreach ($mile_stones as $mile_stone)
                                                <div class="myJob-wrapper-single-milestone-item">
                                                    <div class="myJob-wrapper-single-flex flex-between align-items-start">
                                                        <x-order.milestone-details :id="$mile_stone->id" :orderID="$order_details->id"
                                                            :clientID="$order_details->user_id" :title="$mile_stone->title" :price="$mile_stone->price"
                                                            :status="$mile_stone->status" :deadline="$mile_stone->deadline" :description="$mile_stone->description" />
                                                        <div class="myJob-wrapper-single-right">
                                                            <div class="myJob-wrapper-single-right-flex">
                                                                <x-order.is-funded :isFunded="$order_details->payment_status" :paymentGateway="$order_details->payment_gateway" />
                                                                <span
                                                                    class="myJob-wrapper-single-fixed danger">{{ __('Revision:') }}
                                                                    {{ $mile_stone->revision ?? '' }}</span>
                                                                <span
                                                                    class="myJob-wrapper-single-fixed danger">{{ __('Revision Left:') }}
                                                                    {{ $mile_stone->revision_left ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($mile_stones->count() > 0)
                                <div class="tab-content-item mt-4" id="Description">
                                @else
                                    <div class="tab-content-item mt-4 active" id="Description">
                            @endif
                            <div class="myOrder-single bg-white padding-20 radius-10">
                                <div class="myOrder-single-item">
                                    <div class="myOrder-single-content">
                                        <p class="myOrder-single-content-para">{{ $order_details->description ?? __('No description.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content-item mt-4" id="Works">
                            <div class="pay-now-single">
                                <h4 class="pay-now-single-title">{{ __('Work Submitted') }}</h4>
                                <div class="pay-now-single-contents profile-border-top">
                                    @if ($order_details?->order_submit_history?->count() > 0)
                                        @foreach ($order_details->order_submit_history as $history)
                                            <div class="pay-now-single-contents-work">
                                                <div class="pay-now-single-contents-work-flex">
                                                    <div class="pay-now-single-contents-work-item">
                                                        <span
                                                            class="pay-now-single-contents-work-date">{{ $history->created_at->toFormattedDateString() }}</span>
                                                    </div>
                                                    <div class="pay-now-single-contents-work-item">
                                                        <div class="single-refundRequest-item">
                                                            <a href="{{ asset('assets/uploads/attachment/order/' . $history->attachment) }}"
                                                                download class="single-refundRequest-item-uploads">
                                                                <i class="fa-solid fa-cloud-arrow-down"></i>
                                                                {{ __('Download Attachment') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="pay-now-single-contents-work-item">
                                                        <div class="pay-now-single-contents-work-item-status">
                                                            @if ($history->status === 0)
                                                                <span
                                                                    class="milestone-approved ">{{ __('Pending') }}</span>
                                                                @if (Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1)
                                                                    <a href="javascript:void(0)"
                                                                        class="btn-profile btn-bg-1 btn-small request_revision_submit"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#RevisionRequestModal"
                                                                        data-order_submit_history_id="{{ $history->id }}"
                                                                        data-order_id="{{ $history->order_id }}"
                                                                        data-order_milestone_id="{{ $history->order_milestone_id }}">
                                                                        {{ __('Request Revision') }}
                                                                    </a>
                                                                    @php
                                                                        $urlType = empty($history->order_milestone_id) ? 'order' : 'milestone';
                                                                    @endphp
                                                                    <x-status.table.status-change :title="__('Accept Order')"
                                                                        :class="'btn-profile btn-bg-cancel btn-small accept_and_pay'" :url="route(
                                                                            'client.order.milestone.approve',
                                                                            [
                                                                                $history->order_milestone_id ??
                                                                                $history->order_id,
                                                                                $urlType,
                                                                            ],
                                                                        )" />
                                                                @endif
                                                                @if (Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Session::get('user_role') == 'client')
                                                                    <a href="javascript:void(0)"
                                                                       class="btn-profile btn-bg-1 btn-small request_revision_submit"
                                                                       data-bs-toggle="modal"
                                                                       data-bs-target="#RevisionRequestModal"
                                                                       data-order_submit_history_id="{{ $history->id }}"
                                                                       data-order_id="{{ $history->order_id }}"
                                                                       data-order_milestone_id="{{ $history->order_milestone_id }}">
                                                                        {{ __('Request Revision') }}
                                                                    </a>
                                                                    @php
                                                                        $urlType = empty($history->order_milestone_id) ? 'order' : 'milestone';
                                                                    @endphp
                                                                    <x-status.table.status-change :title="__('Accept Order')"
                                                                                                  :class="'btn-profile btn-bg-cancel btn-small accept_and_pay'" :url="route(
                                                                            'client.order.milestone.approve',
                                                                            [
                                                                                $history->order_milestone_id ??
                                                                                $history->order_id,
                                                                                $urlType,
                                                                            ],
                                                                        )" />
                                                                @endif
                                                            @elseif($history->status === 1)
                                                                <span
                                                                    class="myJob-wrapper-single-fixed active">{{ __('Approved') }}</span>
                                                            @elseif($history->status === 2)
                                                                <span
                                                                    class="btn myJob-wrapper-single-fixed danger show_revision_details"
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

                        @php
                            $check_order_has_report_by_client = \App\Models\Report::where('client_id',$order_details->user_id)
                            ->where('order_id',$order_details->id)
                            ->where('reporter','client')
                            ->first();
                        @endphp

                        @if(empty($check_order_has_report_by_client))
                        <div class="myOrder-single-item mt-4">
                            <div class="myOrder-single-flex flex-between">
                                @if ($order_details?->user?->is_suspend != 1)
                                    @if ($order_details->status == 3 || $order_details->status == 4)
                                        <a href="javascript:void(0)" data-order-id="{{ $order_details->id }}"
                                            data-freelancer-id="{{ $order_details->freelancer_id }}"
                                            class="btn-profile btn-bg-cancel btn-hover-danger open_order_report_modal"
                                            data-bs-target="#reportModal" data-bs-toggle="modal">{{ __('Report Order') }}
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @else
                            <div class="myOrder-single-item mt-4">
                                <div class="myOrder-single-flex flex-between">
                                   <span class="btn-profile btn-bg-cancel"> {{ __('Reported') }}</span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="profile-details-widget sticky_top_lg">

                        <div class="jobFilter-wrapper-item">
                            <div class="jobFilter-wrapper-item-header">
                                <div class="jobFilter-proposal-author-flex">
                                <span class="jobFilter-proposal-author-thumb">
                                    <div class="myOrder-single-block-item-author">
                                        <x-order.profile-image :image="$order_details?->freelancer->image" />
                                    </div>
                                </span>
                                    <div class="jobFilter-proposal-author-contents">
                                        <h4 class="single-freelancer-author-name">
                                            <a href="{{ route('freelancer.profile.details', $order_details?->freelancer->username) }}">
                                                {{ $order_details?->freelancer->first_name }}
                                                {{ $order_details?->freelancer->last_name }}
                                            </a>
                                            <x-status.user-active-inactive-check :userID="$order_details?->freelancer->id" />
                                        </h4>
                                        <p class="jobFilter-proposal-author-contents-subtitle">
                                            {{ $order_details?->freelancer?->user_introduction?->title }} ·
                                            <span>
                                                @if($order_details?->freelancer?->user_state?->state != null)
                                                {{ $order_details?->freelancer?->user_state?->state }},
                                                @endif
                                                {{ $order_details?->freelancer?->user_country?->country }}
                                            </span>
                                            @if($order_details?->freelancer?->user_verified_status == 1) <i class="fas fa-circle-check"></i>@endif
                                        </p>
                                        <div class="jobFilter-proposal-author-contents-review mt-2">
                                            {!! freelancer_rating($order_details?->freelancer->id) !!}
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
        <!-- Profile Details area end -->
    </main>

    @include('frontend.user.client.order.request-revision')
    @include('frontend.user.client.order.revision-details')
    @include('frontend.user.client.order.report-modal')
    @include('frontend.user.client.order.order-submit-description')

@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-summernote.summernote-js />
    @include('frontend.user.client.order.order-js')
@endsection
