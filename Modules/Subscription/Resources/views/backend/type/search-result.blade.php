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
        <th>{{__('Type')}}</th>
        <th>{{__('Validity')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_types as $type)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$type->id"/> </td>
            <td>{{ $type->id }}</td>
            <td>{{ $type->type }}</td>
            <td>{{ $type->validity }} {{ __('days') }}</td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('subscription-type-edit')
                    <li class="status_dropdown__item">
                        <a
                            class="btn dropdown-item status_dropdown__list__link edit_type_modal"
                            data-bs-toggle="modal"
                            data-bs-target="#editTypeModal"
                            data-id="{{ $type->id }}"
                            data-type="{{ $type->type }}"
                            data-validity="{{ $type->validity }}">
                            {{ __('Edit Type') }}
                        </a>
                    </li>
                    @endcan
                    @can('subscription-type-delete')
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Type')" :url="route('admin.subscription.type.delete',$type->id)"/></li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_types"/>
