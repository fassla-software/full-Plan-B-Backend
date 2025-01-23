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
        <th>{{__('State')}}</th>
        <th>{{__('Country')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_states as $state)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$state->id"/> </td>
            <td>{{ $state->id }}</td>
            <td>{{ $state->state }}</td>
            <td>{{ optional($state->country)->country }}</td>
            <td><x-status.table.active-inactive :status="$state->status"/></td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('state-edit')
                    <li class="status_dropdown__item">
                        <a
                            class="btn dropdown-item status_dropdown__list__link edit_state_modal"
                            data-bs-toggle="modal"
                            data-bs-target="#editStateModal"
                            data-state_id="{{ $state->id }}"
                            data-state="{{ $state->state }}"
                            data-country="{{ $state->country_id }}"
                            data-timezone="{{ $state->timezone }}">
                            {{ __('Edit State') }}
                        </a>
                    </li>
                    @endcan
                    @can('state-delete')
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete State')" :url="route('admin.state.delete',$state->id)"/></li>
                    @endcan
                    @can('state-status-change')
                    <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.state.status',$state->id)"/></li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_states"/>
