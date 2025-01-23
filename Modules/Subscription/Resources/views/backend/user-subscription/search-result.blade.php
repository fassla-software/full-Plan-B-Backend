<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{ __('ID') }}</th>
        <th>{{ __('User ID') }}</th>
        <th>{{ __('Type') }}</th>
        <th>{{ __('Price') }}</th>
        <th>{{ __('Limit') }}</th>
        <th>{{ __('Payment Gateway') }}</th>
        <th>{{ __('Payment Status') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Purchase Date') }}</th>
        <th>{{ __('Expire Date') }}</th>
        <th>{{ __('Action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_subscriptions as $sub)
        <tr>
            <td>{{ $sub->id }}</td>
            <td>{{ $sub->user_id ?? '' }}</td>
            <td>{{ $sub->subscription?->subscription_type?->type }}</td>
            <td>{{ float_amount_with_currency_symbol($sub->price) }}</td>
            <td>{{ $sub->limit }}</td>
            <td>
                @if($sub->payment_gateway == 'manual_payment')
                    {{ ucfirst(str_replace('_',' ',$sub->payment_gateway)) }}
                @else
                    {{ $sub->payment_gateway == 'authorize_dot_net' ? __('Authorize.Net') : ucfirst($sub->payment_gateway) }}
                @endif
            </td>
            <td>
                @if($sub->payment_status == '' || $sub->payment_status == 'cancel')
                    <span class="btn btn-danger btn-sm">{{ __('Cancel') }}</span>
                @elseif($sub->payment_status == 'pending')
                    <span class="btn btn-warning btn-sm">{{ ucfirst($sub->payment_status) }}</span>
                @can('user-subscription-manual-payment-status-change')
                    <a
                        class="btn btn-sm btn-danger edit_payment_gateway_modal"
                        data-bs-toggle="modal"
                        data-bs-target="#editPaymentGatewayModal"
                        data-subscription_id="{{ $sub->id }}"
                        data-user_type="{{ $sub->user?->user_type == 1 ? 'Client' : 'Freelancer' }}"
                        data-user_firstname="{{ $sub->user?->first_name }}"
                        data-user_email="{{ $sub->user?->email }}"
                        data-img_url="{{ $sub->manual_payment_image }}">
                        {{ __('Update') }}
                    </a>
                @endcan
                @else
                    <span class="btn btn-success btn-sm">{{ ucfirst($sub->payment_status) }}</span>
                @endif
            </td>
            <td>
                @if($sub->status == 0)
                    <span class="btn btn-danger btn-sm">{{ __('Inactive') }}</span>
                @else
                    <span class="btn btn-success btn-sm">{{ __('Active')  }}</span>
                @endif
            </td>
            <td>{{ $sub->created_at->format('Y-m-d') ?? '' }}</td>
            <td>{{ Carbon\Carbon::parse($sub->expire_date)->format('Y-m-d') }}</td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('user-subscription-status-change')
                    <li class="status_dropdown__item">
                        <x-status.table.status-change :title="__('Change Status')" :url="route('admin.user.subscription.status',$sub->id)"/>
                    </li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :route="$route" :allData="$all_subscriptions"/>
