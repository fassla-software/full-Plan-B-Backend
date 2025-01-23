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
        <th>{{__('Length')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_lengths as $length)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$length->id"/> </td>
            <td>{{ $length->id }}</td>
            <td>{{ ucfirst($length->length) }}</td>
            <td><x-status.table.active-inactive :status="$length->status"/></td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    <li class="status_dropdown__item">
                        <a
                            class="btn dropdown-item status_dropdown__list__link edit_length_modal"
                            data-bs-toggle="modal"
                            data-bs-target="#editCategoryModal"
                            data-length="{{ $length->length }}"
                            data-id="{{ $length->id }}">
                            {{ __('Edit Length') }}
                        </a>
                    </li>
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Length')" :url="route('admin.length.delete',$length->id)"/></li>
                    <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.length.status',$length->id)"/></li>
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_lengths"/>
