<table class="table_activation">
    <thead>
    <tr>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
        <th>{{__('ID')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Phone')}}</th>
        <th>{{__('Active Status')}}</th>
        <th>{{__('Verified Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($all_requests->total() >= 1)
        @foreach($all_requests as $req)
            <tr>
                <td>
                    <x-bulk-action.bulk-delete-checkbox :id="$req->id"/>
                </td>
                <td>{{ $req->id }}</td>
                <td>{{ optional($req->user)->first_name }}</td>
                <td>{{ optional($req->user)->email }}</td>
                <td>{{ optional($req->user)->phone }}</td>
                <td>
                    <x-status.table.active-inactive :status="optional($req->user)->user_active_inactive_status"/>
                </td>
                <td class="verified_status_load_{{$req->user_id}}">
                    <x-status.table.verified-status :status="optional($req->user)->user_verified_status"/>
                    @if($req->status === 2)
                        <span class="alert alert-danger" >{{__('Decline')}}</span>
                    @endif
                </td>
                <td>
                    <a class="btn dropdown-item status_dropdown__list__link user_identity_details" data-bs-toggle="modal" data-bs-target="#userIdentityModal" data-user_id="{{ $req->user_id }}">
                        {{ __('View Identity Details') }}
                    </a>
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'8'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_requests"/>
