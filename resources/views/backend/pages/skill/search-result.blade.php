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
        <th>{{__('Skill')}}</th>
        <th>{{__('Category')}}</th>
        <th>{{__('Subcategory')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_skills as $skill)
        <tr>
            <td>
                <x-bulk-action.bulk-delete-checkbox :id="$skill->id"/>
            </td>
            <td>{{ $skill->id }}</td>
            <td>{{ $skill->skill }}</td>
            <td>{{ optional($skill->category)->category }}</td>
            <td>{{ optional($skill->subcategory)->sub_category }}</td>
            <td>
                <x-status.table.active-inactive :status="$skill->status"/>
            </td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('skill-edit')
                    <li class="status_dropdown__item">
                        <a class="btn dropdown-item status_dropdown__list__link edit_skill_modal"
                           data-bs-toggle="modal"
                           data-bs-target="#editSkillModal"
                           data-skill="{{ $skill->skill }}"
                           data-skill_id="{{ $skill->id }}"
                           data-category="{{ $skill->category_id }}"
                           data-subcategory="{{ $skill->sub_category_id }}">
                            {{ __('Edit Skill') }}
                        </a>
                    </li>
                    @endcan
                    @can('skill-edit')
                    <li class="status_dropdown__item">
                        <x-popup.delete-popup :title="__('Delete Skill')" :url="route('admin.skill.delete',$skill->id)"/>
                    </li>
                    @endcan
                    @can('skill-status-change')
                    <li class="status_dropdown__item">
                        <x-status.table.status-change :title="__('Change Status')" :url="route('admin.skill.status',$skill->id)"/>
                    </li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_skills"/>
