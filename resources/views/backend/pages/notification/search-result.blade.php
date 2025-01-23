<x-validation.error />
<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Message')}}</th>
        <th>{{__('Notification Type')}}</th>
        <th>{{__('Read/Unread')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_notifications as $notification)
        <tr>
            <td>{{ $notification->id }}</td>
            <td>{{ $notification->message }}</td>
            <td>{{ $notification->type }}</td>
            <td>
                @if($notification->is_read == 'unread')
                    <span class="badge bg-danger">{{ __('Unread') }}</span>
                @else
                    <span class="badge bg-success">{{ __('Read') }}</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_notifications"/>
