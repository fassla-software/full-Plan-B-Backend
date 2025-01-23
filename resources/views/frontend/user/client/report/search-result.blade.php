<x-validation.error />
<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Status (change by admin)')}}</th>
        <th>{{__('Report Create Date')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reports as $report)
        <tr>
            <td>{{ $report->id }}</td>
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
            <td>{{ $report->created_at->toFormattedDateString() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$reports"/>
