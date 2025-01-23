<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{ __('ID') }}</th>
        <th>{{ __('Email') }}</th>
        <th>{{ __('Action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($all_newsletter as $newsletter)
        <tr>
            <td>{{ $newsletter->id }}</td>
            <td>{{ $newsletter->email }}</td>
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
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_reports"/>
