<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{ __('ID') }}</th>
        <th>{{ __('Order ID') }}</th>
        <th>{{ __('Reported By') }}</th>
        <th>{{ __('Title') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($all_reports as $report)
        <tr>
            <td>{{ $report->id }}</td>
            <td>#000{{ $report->order_id }}</td>
            <td>{{ ucfirst($report->reporter) }}</td>
            <td>{{ $report->title }}</td>
            <td>
                @if($report->status == 0)
                    <span class="btn btn-primary btn-sm"> {{ __('In Review') }}</span>
                @elseif($report->status == 1)
                    <span class="btn btn-success btn-sm"> {{ __('Closed') }}</span>
                @else
                    <span class="btn btn-danger btn-sm"> {{ __('Rejected') }}</span>
                @endif
            </td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    <li class="status_dropdown__item">
                        <a class="btn dropdown-item status_dropdown__list__link edit_gateway_modal update-report"
                           data-bs-toggle="modal"
                           data-bs-target="#edit-request-modal"
                           data-id="{{ $report->id }}"
                           data-note="{{ $report->note }}"
                           data-title="{{ $report->title }}"
                           data-description="{!! $report->description !!}"
                           data-status="{{ $report->status }}">
                            {{ __('Update Status') }}
                        </a>
                    </li>
                    <li class="status_dropdown__item">
                        <a href="{{ route('admin.order.details',$report->order_id) }}" class="btn dropdown-item status_dropdown__list__link">
                            {{ __('View Order') }}
                        </a>
                    </li>
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_reports"/>
