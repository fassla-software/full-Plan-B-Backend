@extends('backend.layout.master')
@section('title', __('History Details'))
@section('style')
    <style>
        .custom_table.style-04 table tbody tr td, .custom_table.style-04 table tbody tr th {
            border: 1px solid var(--border-color);
        }
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('History Details') }}</h4>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04">
                                <table class="DataTable_activation">
                                    <tbody>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <td>{{ $history_details->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <td>{{ $history_details->user?->first_name .' '.$history_details->user?->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Email') }}</th>
                                            <td>{{ $history_details->user?->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Phone') }}</th>
                                            <td>{{ $history_details->user?->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Verified Status') }}</th>
                                            <td> <x-status.table.verified-status :status="$history_details->user?->user_verified_status"/></td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Payment Gateway') }}</th>
                                            <td>
                                                @if($history_details->payment_gateway == 'manual_payment')
                                                    {{ ucfirst(str_replace('_',' ',$history_details->payment_gateway)) }}
                                                @else
                                                    {{ $history_details->payment_gateway == 'authorize_dot_net' ? __('Authorize.Net') : ucfirst($history_details->payment_gateway) }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Payment Status') }}</th>
                                            <td>
                                                @if($history_details->payment_status == '' || $history_details->payment_status == 'cancel')
                                                    <span class="btn btn-danger btn-sm">{{ __('Cancel') }}</span>
                                                @else
                                                    <span class="btn btn-success btn-sm">{{ ucfirst($history_details->payment_status) }}</span>
                                                    @if($history_details->payment_status == 'pending')
                                                        <x-status.table.status-change :title="__('Change Status')" :class="'btn btn-danger wallet_history_status_change'" :url="route('admin.wallet.history.status',$history_details->id)"/>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Deposit Amount') }}</th>
                                            <td>{{ float_amount_with_currency_symbol($history_details->amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Manual Payment Image') }}</th>
                                            <td>
                                                <span class="img_100">
                                                    @if(empty($history_details->manual_payment_image))
                                                        <img src="{{ asset('assets/static/img/no_image.png') }}" alt="">
                                                    @else
                                                        <img src="{{ asset('assets/uploads/manual-payment/'.$history_details->manual_payment_image) }}" alt="">
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Deposit Date') }}</th>
                                            <td>{{ $history_details->created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    @include('wallet::admin.wallet.wallet-js')
@endsection
