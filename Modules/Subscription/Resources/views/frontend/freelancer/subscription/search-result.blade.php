<table>
    <thead>
    <tr>
        <th>{{ __('Type') }}</th>
        <th>{{ __('Price') }}</th>
        <th>{{ __('Connect') }}</th>
        <th>{{ __('Payment Gateway') }}</th>
        <th>{{ __('Payment Status') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Purchase Date') }}</th>
        <th>{{ __('Expire Date') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_subscriptions as $sub)
        <tr>
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
                @else
                    <span class="btn btn-success btn-sm">{{ ucfirst($sub->payment_status) }}</span>
                @endif
            </td>
            <td>
                @if($sub->status == 0)
                    <span class="btn btn-danger btn-sm">{{ __('Inactive') }}</span>
                @else
                    @if(Carbon\Carbon::parse($sub->expire_date) > Carbon\Carbon::now())
                        <span class="btn btn-success btn-sm">{{ __('Active')  }}</span>
                    @else
                        <span class="btn btn-warning btn-sm">{{ __('Expired')  }}</span>
                    @endif
                @endif
            </td>
            <td>{{ $sub->created_at->format('Y-m-d') ?? '' }}</td>
            <td>{{ Carbon\Carbon::parse($sub->expire_date)->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="deposit-history-pagination mt-4">
    <x-pagination.laravel-paginate :allData="$all_subscriptions"/>
</div>
