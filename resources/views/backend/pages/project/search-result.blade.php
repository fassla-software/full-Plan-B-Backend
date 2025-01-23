<x-validation.error />
<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Project Title')}}</th>
        <th>{{__('Image')}}</th>
        <th>{{__('Status (change by admin)')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_projects as $project)
        <tr>
            <td>{{ $project->id }}</td>
            <td>
                {{ $project->title }} <br>
                @if($project->project_approve_request === 0) <small class="badge bg-danger">{{ __('Request for activate') }}</small> @endif
            </td>
            <td>
                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                    <img width="250" height="100" src="{{ render_frontend_cloud_image_if_module_exists('project/'.$project->image, load_from: $project->load_from) }}" alt="{{ $project->title ?? '' }}">
                @else
                    <img width="250" height="100" src="{{ asset('assets/uploads/project/'. $project->image) }}" alt="{{ $project->title }}">
                @endif
            </td>
            <td>
                <x-status.table.active-inactive :status="$project->status"/>
            </td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('project-details')
                    <li class="status_dropdown__item">
                        <a href="{{ route('admin.project.details',$project->id) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('Project Details') }}</a>
                    </li>
                    @endcan
                    @can('project-delete')
                    <li class="status_dropdown__item">
                        <x-popup.delete-popup :title="__('Delete Project')" :url="route('admin.project.delete',$project->id)"/>
                    </li>
                    @endcan
                    @can('project-status-change')
                    <li class="status_dropdown__item">
                        @if($project->project_approve_request === 0 || $project->project_approve_request === 2)
                            <x-status.table.status-change :title="__('Activate Project')" :url="route('admin.project.status.change',$project->id)"/>
                        @else
                            <x-status.table.status-change :title="__('Inactivate Project')" :url="route('admin.project.status.change',$project->id)"/>
                        @endif
                    </li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_projects"/>
