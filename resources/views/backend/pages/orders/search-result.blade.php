<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{ __('User ID') }}</th>
        <th>{{ __('Order From') }}</th>
        <th>{{ __('Type') }}</th>
        <th>{{ __('Price') }}</th>
        <th>{{ __('Revision') }}</th>
        <th>{{ __('Payment Gateway') }}</th>
        <th>{{ __('Payment Status') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Order Date') }}</th>
        <th>{{ __('Action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->user_id ?? '' }}</td>
            <td>{{ ucfirst(__($order->is_project_job)) }}</td>
            <td>{{ ucfirst(__($order->is_fixed_hourly ?? 'Fixed')) }}</td>
            <td>{{ float_amount_with_currency_symbol($order->price) }}</td>
            <td>{{ $order->revision }}</td>
            <td>
                @if($order->payment_gateway == 'manual_payment')
                    {{ ucfirst(str_replace('_',' ',$order->payment_gateway)) }}
                @else
                    {{ $order->payment_gateway == 'authorize_dot_net' ? __('Authorize.Net') : ucfirst($order->payment_gateway) }}
                @endif
            </td>
            <td>
                @if($order->payment_gateway != 'manual_payment' && $order->payment_status == 'pending')
                    <span class="btn btn-danger btn-sm">{{ __('Payment Failed') }}</span>
                @elseif($order->payment_status == 'pending')
                    <span class="btn btn-warning btn-sm">{{ ucfirst(__($order->payment_status)) }}</span>
                @can('order-manual-payment-status-update')
                    <a
                        class="btn btn-sm btn-primary edit_payment_gateway_modal"
                        data-bs-toggle="modal"
                        data-bs-target="#editPaymentGatewayModal"
                        data-order_id="{{ $order->id }}"
                        data-order_price="{{ float_amount_with_currency_symbol($order->price) }}"
                        data-user_type="{{ $order->user?->user_type == 1 ? 'Client' : 'Freelancer' }}"
                        data-user_fullname="{{ $order->user?->first_name }} {{ $order->user?->last_name }}"
                        data-user_email="{{ $order->user?->email }}"
                        data-img_url="{{ $order->manual_payment_image }}">
                        {{ __('Update') }}
                    </a>
                @endcan
                @else
                    <span class="btn btn-success btn-sm">{{ ucfirst(__($order->payment_status)) }}</span>
                @endif
            </td>
            <td>
                <x-status.table.order-status :status="$order->status"/>
            </td>
            <td>{{ $order->created_at->format('Y-m-d') ?? '' }}</td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                @can('order-details')
                <ul class="dropdown-menu status_dropdown__list">
                    <a href="{{ route('admin.order.details',$order->id) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('View Details') }}</a>
                    @if($order->status == 3)
                        <a href="{{ route('admin.order.invoice.generate',$order->id) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('Invoice') }}</a>
                    @endif
                </ul>
                @endcan
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$orders"/>
