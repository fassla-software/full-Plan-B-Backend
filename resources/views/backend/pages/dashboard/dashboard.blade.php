@extends('backend.layout.master')
@section('title', __('Dashboard'))
@section('content')
    <div class="dashboard__body">
        <div class="row g-4">
            <div class="col-xxl-8 col-lg-12">
                <div class="dashboard__promo bg-white">
                    <div class="dashboard__promo__row">
                        <a href="{{ route('admin.jobs') }}" class="dashboard__promo__col promo_child">
                            <div class="single_promo">
                                <div class="single_promo__contents">
                                    <span class="single_promo__subtitle"> {{ __('Total Requests') }} </span>
                                    <h4 class="single_promo__title mt-2">{{ $total_job ?? 0 }} </h4>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('admin.order.all') }}" class="dashboard__promo__col promo_child">
                            <div class="single_promo">
                                <div class="single_promo__contents">
                                    <span class="single_promo__subtitle"> {{ __('Total Offers') }} </span>
                                    <h4 class="single_promo__title mt-2">
                                        {{ $total_offers ?? 0 }} </h4>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.freelancer.all') }}" class="dashboard__promo__col promo_child">
                            <div class="single_promo">
                                <div class="single_promo__contents">
                                    <span class="single_promo__subtitle"> {{ __('Total Freelancers') }} </span>
                                    <h4 class="single_promo__title mt-2"> {{ $total_freelancer ?? '' }} </h4>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('admin.client.all') }}" class="dashboard__promo__col promo_child">
                            <div class="single_promo">
                                <div class="single_promo__contents">
                                    <span class="single_promo__subtitle"> {{ __('Total Clients') }} </span>
                                    <h4 class="single_promo__title mt-2"> {{ $total_client ?? '' }}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="dashboard__charts padding-20 radius-10 bg-white mt-4">
                    <div class="dashboard__charts__header flex-between align-items-center">
                        <h4 class="dashboard__charts__title">{{ __('Jobs and Offers') }}</h4>
                        <div class="dashboard__select">
                            <strong>{{ __('Monthly Jobs and Offers') }}</strong>
                        </div>
                    </div>
                    <div class="dashboard__charts__inner profile-border-top">
                        <canvas id="bar-chart-grouped"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-8">
                <div class="dashboard__maps bg-white padding-20 radius-10">
                    <div class="dashboard__maps__flex flex-between align-items-center">
                        <h4 class="dashboard__maps__title">{{ __('Quick Access') }}</h4>
                    </div>
                    <div class="dashboard__maps__footer mt-4">
                        <h6 class="dashboard__maps__footer__title">{{ __('System Settings By Super Admin') }}</h6>
                        <ul class="dashboard__maps__footer__list mt-4">
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country">{{ __('Commission Type') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ ucfirst(get_static_option('admin_commission_type') ?? '') }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country">{{ __('Commission Charge') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ get_static_option('admin_commission_charge') ?? '' }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span
                                    class="dashboard__maps__footer__list__country">{{ __('Transaction Fee Type') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ ucfirst(get_static_option('transaction_fee_type') ?? '') }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span
                                    class="dashboard__maps__footer__list__country">{{ __('Transaction Fee Charge') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ get_static_option('transaction_fee_charge') ?? '' }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span
                                    class="dashboard__maps__footer__list__country">{{ __('Connect Reduce Per Proposal') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ get_static_option('limit_settings') ?? 1 }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country">{{ __('Job Auto Approval') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ ucfirst(get_static_option('job_auto_approval')) }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span
                                    class="dashboard__maps__footer__list__country">{{ __('Project Auto Approval') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ ucfirst(get_static_option('project_auto_approval')) }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country">{{ __('Withdraw Fee') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ float_amount_with_currency_symbol(get_static_option('withdraw_fee')) ?? 0 }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span
                                    class="dashboard__maps__footer__list__country">{{ __('Display Commission Amount') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ !empty(get_static_option('commission_disable_client_panel')) && get_static_option('commission_disable_client_panel') == 'enable' ? __('Yes') : __('No') }}</span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span
                                    class="dashboard__maps__footer__list__country">{{ __('Maximum Deposit Amount') }}</span>
                                <span
                                    class="dashboard__maps__footer__list__count">{{ float_amount_with_currency_symbol(get_static_option('deposit_amount_limitation_for_user')) ?? 0 }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <div class="activities">
                    <div class="activities-single radius-10 padding-20">
                        <div class="activities-single-header profile-border-bottom flex-between align-items-center">
                            <h4 class="activities-single-header-title">{{ __('Recent Orders') }}</h4>
                        </div>
                        <div class="dashboard-tab-content-item active" id="Transactions">
                            <div class="activities-single-table mt-4">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th col="scope">{{ __('User ID') }}</th>
                                            <th col="scope">{{ __('Type') }}</th>
                                            <th col="scope">{{ __('Price') }}</th>
                                            <th col="scope">{{ __('Payment Gateway') }}</th>
                                            <th col="scope">{{ __('Payment Status') }}</th>
                                            <th col="scope">{{ __('Status') }}</th>
                                            <th col="scope">{{ __('Order Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order->user_id ?? '' }}</td>
                                                <td>{{ ucfirst($order->is_project_job) }}</td>
                                                <td>{{ float_amount_with_currency_symbol($order->price) }}</td>
                                                <td>
                                                    @if ($order->payment_gateway == 'manual_payment')
                                                        {{ ucfirst(str_replace('_', ' ', $order->payment_gateway)) }}
                                                    @else
                                                        {{ $order->payment_gateway == 'authorize_dot_net' ? __('Authorize.Net') : ucfirst($order->payment_gateway) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($order->payment_gateway != 'manual_payment' && $order->payment_status == 'pending')
                                                        <span
                                                            class="btn btn-danger btn-sm">{{ __('Payment Failed') }}</span>
                                                    @elseif($order->payment_status == 'pending')
                                                        <span
                                                            class="btn btn-warning btn-sm">{{ ucfirst(__($order->payment_status)) }}</span>
                                                    @else
                                                        <span
                                                            class="btn btn-success btn-sm">{{ ucfirst(__($order->payment_status)) }}</span>
                                                    @endif
                                                </td>
                                                <td> <x-status.table.order-status :status="$order->status" /> </td>
                                                <td>{{ $order->created_at->format('Y-m-d') ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                //monthly income
                new Chart(document.getElementById("bar-chart-grouped"), {
                    type: 'bar',
                    data: {
                        labels: [
                            @foreach ($month_list as $list)
                                "{{ $list }}",
                            @endforeach
                        ],
                        datasets: [{
                                label: "{{ __('Total Jobs') }}",
                                backgroundColor: "#6176F6",
                                data: [
                                    @foreach ($monthly_requests as $job)
                                        "{{ $job }}",
                                    @endforeach
                                ],
                                barThickness: 15,
                                hoverBackgroundColor: '#fff',
                                hoverBorderColor: '#6176F6',
                                borderColor: '#fff',
                                borderWidth: 2,
                            },
                            {
                                label: "{{ __('Total Offers') }}",
                                backgroundColor: "#F66161",
                                data: [
                                    @foreach ($monthly_offers as $offer)
                                        "{{ $offer }}",
                                    @endforeach
                                ],
                                barThickness: 15,
                                hoverBackgroundColor: '#fff',
                                hoverBorderColor: '#F66161',
                                borderColor: '#fff',
                                borderWidth: 2,
                            }
                        ],
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                }
                            },
                            y: {
                                grid: {
                                    display: false,
                                }
                            },
                        }
                    }
                });
            });
        }(jQuery));
    </script>
@endsection
