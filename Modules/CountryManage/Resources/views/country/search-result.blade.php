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
        <th>{{__('Country')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_countries as $country)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$country->id"/> </td>
            <td>{{ $country->id }}</td>
            <td>{{ $country->country }}</td>
            <td><x-status.table.active-inactive :status="$country->status"/></td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('country-edit')
                    <li class="status_dropdown__item">
                        <a
                            class="btn dropdown-item status_dropdown__list__link edit_country_modal"
                            data-bs-toggle="modal"
                            data-bs-target="#editCountryModal"
                            data-country="{{ $country->country }}"
                            data-country_id="{{ $country->id }}">
                            {{ __('Edit Country') }}
                        </a>
                    </li>
                    @endcan
                    @can('country-delete')
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Country')" :url="route('admin.country.delete',$country->id)"/></li>
                    @endcan
                    @can('country-status-change')
                    <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.country.status',$country->id)"/></li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_countries"/>
