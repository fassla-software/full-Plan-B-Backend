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
        <th>{{__('Department')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($departments as $department)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$department->id"/> </td>
            <td>{{ $department->id }}</td>
            <td>{{ $department->name }}</td>
            <td><x-status.table.active-inactive :status="$department->status"/></td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('department-edit')
                    <li class="status_dropdown__item">
                        <a
                            class="btn dropdown-item status_dropdown__list__link edit_department_modal"
                            data-bs-toggle="modal"
                            data-bs-target="#editCountryModal"
                            data-department="{{ $department->name }}"
                            data-department_id="{{ $department->id }}">
                            {{ __('Edit Department') }}
                        </a>
                    </li>
                    @endcan
                    @can('department-delete')
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Department')" :url="route('admin.department.delete',$department->id)"/></li>
                    @endcan
                    @can('department-status-update')
                    <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.department.status',$department->id)"/></li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
