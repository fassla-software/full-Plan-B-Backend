<x-validation.error />
<table class="table_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Phone')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($all_users->total() >=1)
        @foreach($all_users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>
                    <x-status.table.select-action :title="__('Select Action')"/>
                    <ul class="dropdown-menu status_dropdown__list">
                        <li class="status_dropdown__item">
                            <x-popup.restore-popup :title="__('Restore User')" :url="route('admin.user.restore',$user->id)" :class="'btn dropdown-item status_dropdown__list__link'"/>
                        </li>
                        <li class="status_dropdown__item">
                            <x-popup.delete-popup :title="__('Delete Permanently')" :url="route('admin.user.permanent.delete',$user->id)"/>
                        </li>
                    </ul>
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'5'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_users"/>
