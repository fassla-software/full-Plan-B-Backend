<table>
    <thead>
    <tr>
        <th>{{ __('Amount') }}</th>
        <th>{{ __('Gateway') }}</th>
        <th>{{ __('Gateway Info') }}</th>
        <th>{{ __('Freelancer Info') }}</th>
        <th>{{ __('Image') }}</th>
        <th>{{ __('Note') }}</th>
        <th>{{ __('Status') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($all_request as $request)
        @php $fields = ''; @endphp
        @foreach (unserialize($request->gateway_fields) as $key => $value)
            @php
                $fields .= ucwords(str_replace('_', ' ', $key)) . ' => ' . $value . "<br/>";
            @endphp
        @endforeach
        <tr>
            <td>{{ float_amount_with_currency_symbol($request->amount) }}</td>
            <td>{{ ucfirst($request?->gateway_name->name) }}</td>
            <td>{!! $fields !!}</td>
            <td>
                <p>{{ __('Name:') }} {{ $request?->user->fullname }}</p>
                <p>{{ __('Email:') }} {{ $request?->user->email }}</p>
                <p>{{ __('Balance:') }} {{ float_amount_with_currency_symbol($request?->user?->user_wallet->balance) }}</p>
            </td>
            <td>
                @if($request->image)
                    <img style="width:200px;" src="{{ asset('assets/uploads/withdraw-request/'.$request->image) }}">
                @endif
            </td>
            <td>
                <p>{{ $request->note ?? '' }}</p>
            </td>
            <td>
                <x-status.table.withdraw-request-status :status="$request->status" />
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="deposit-history-pagination mt-4">
    <x-pagination.laravel-paginate :allData="$all_request"/>
</div>
