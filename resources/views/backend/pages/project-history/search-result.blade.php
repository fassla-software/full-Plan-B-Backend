<x-validation.error />
<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Project ID')}}</th>
        <th>{{__('Project Title')}}</th>
        <th>{{__('Status (change by admin)')}}</th>
        <th>{{__('Total Reject')}}</th>
        <th>{{__('Total Edit')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_histories as $history)
        <tr>
            <td>{{ $history->id }}</td>
            <td>{{ $history->project?->id }}</td>
            <td>{{ $history->project?->title }}</td>
            <td><x-status.table.active-inactive :status="$history->project?->status"/></td>
            <td>{{ $history->reject_count }}</td>
            <td>{{ $history->edit_count }}</td>
            <td><a target="_blank" href="{{ route('admin.project.details',$history->project_id) }}" class="btn-bg-1 btn-profile">{{ __('Project Details') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_histories"/>
