<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{ __('Amount') }}</th>
        <th>{{ __('Gateway Name') }}</th>
        <th>{{ __('Gateway Info') }}</th>
        <th>{{ __('Freelancer Info') }}</th>
        <th>{{ __('Image') }}</th>
        <th>{{ __('Note') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Action') }}</th>
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
                <p>{{ __('Name:') }} {{ $request?->user?->fullname }}</p>
                <p>{{ __('Email:') }} {{ $request?->user?->email }}</p>
                <p>{{ __('Balance:') }} {{ float_amount_with_currency_symbol($request?->user?->user_wallet->balance) }}</p>
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
            <td>
                @if($request->status == 3)
                    <span class="text-danger"> {{ __('Cancelled') }}</span>
                @else
                    <x-status.table.select-action :title="__('Select Action')"/>
                    <ul class="dropdown-menu status_dropdown__list">
                        @can('withdraw-status-change')
                        <li class="status_dropdown__item">
                            <a class="btn dropdown-item status_dropdown__list__link edit_gateway_modal update-request"
                               data-bs-toggle="modal"
                               data-bs-target="#edit-request-modal"
                               data-amount="{{ $request->amount }}"
                               data-id="{{ $request->id }}"
                               data-status="{{ $request->status }}">
                                {{ __('Update Status') }}
                            </a>
                        </li>
                        @endcan
                    </ul>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_request"/>
