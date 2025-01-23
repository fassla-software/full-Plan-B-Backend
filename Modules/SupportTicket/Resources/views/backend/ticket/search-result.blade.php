<style>
    .alert-success {
        border-color: #f2f2f2;
        border-left: 3px solid #319a31;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .alert-danger {
        border-color: #f2f2f2;
        border-left: 3px solid #dd0000;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
</style>
<x-validation.error />
<table class="DataTable_activation">
    <thead>
    <tr>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
        <th>{{__('ID')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Priority')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tickets as $ticket)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$ticket->id"/> </td>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->title }}</td>
            <td>{{ $ticket->priority }}</td>
            <td>
                @if($ticket->status === 'open')
                    <span class="alert alert-success" >{{__('Open')}}</span>
                @else
                    <span class="alert alert-danger" >{{__('Close')}}</span>
                @endif
            </td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('support-ticket-details')
                        <li class="status_dropdown__item"><a class="dropdown-item status_dropdown__list__link" href="{{ route('admin.ticket.details',$ticket->id) }}">{{ __('View Ticket') }}</a></li>
                    @endcan
                    @can('support-ticket-delete')
                        <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Ticket')" :url="route('admin.ticket.delete',$ticket->id)"/></li>
                    @endcan
                    @can('support-ticket-status-change')
                        @if($ticket->status === 'open')
                            <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.ticket.status',$ticket->id)"/></li>
                        @endif
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$tickets"/>

