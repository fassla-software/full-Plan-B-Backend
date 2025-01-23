<table>
    <thead>
    <tr>
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
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->title }}</td>
            <td>{{ $ticket->priority }}</td>
            <td>
                @if($ticket->status === 'open')
                    <span class="btn btn-sm btn-success" >{{__('Open')}}</span>
                @else
                    <span class="btn btn-sm btn-danger" >{{__('Close')}}</span>
                @endif
            </td>
            <td>
                <a class="btn-sm btn-profile btn-bg-1" href="{{ route('freelancer.ticket.details',$ticket->id) }}">{{ __('View Details') }}</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="deposit-history-pagination mt-4">
    <x-pagination.laravel-paginate :allData="$tickets"/>
</div>
