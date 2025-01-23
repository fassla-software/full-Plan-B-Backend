<x-validation.error/>
<table class="DataTable_activation">
    <thead>
    <tr>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
        <th>{{__('ID')}}</th>
        <th>{{__('Experience Level')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_levels as $level)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$level->id"/> </td>
            <td>{{ $level->id }}</td>
            <td>{{ ucfirst($level->level) }}</td>
            <td><x-status.table.active-inactive :status="$level->status"/></td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    <li class="status_dropdown__item">
                        <a
                            class="btn dropdown-item status_dropdown__list__link edit_level_modal"
                            data-bs-toggle="modal"
                            data-bs-target="#editExperienceLevelModal"
                            data-level="{{ $level->level }}"
                            data-id="{{ $level->id }}">
                            {{ __('Edit Level') }}
                        </a>
                    </li>
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Level')" :url="route('admin.experience.level.delete',$level->id)"/></li>
                    <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.experience.level.status',$level->id)"/></li>
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_levels"/>
