<x-validation.error/>
<table class="DataTable_activation">
    <thead>
    <tr>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
        <th>{{__('Freelancer')}}</th>
        <th>{{__('Feedback Title')}}</th>
        <th>{{__('Feedback Message')}}</th>
        <th>{{__('Rating')}}</th>
        <th style="width:10%">{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($feedbacks as $feedback)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$feedback->id"/> </td>
            <td>
                <span class="img_100">
                    @if($feedback?->user?->image)
                        @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                            <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $feedback?->user?->image, load_from: $feedback?->user?->load_from ?? '') }}" alt="{{ __('Profile Image') }}">
                        @else
                            <img src="{{ asset('assets/uploads/profile/'.$feedback?->user?->image) }}" alt="{{ __('Profile Image') }}">
                        @endif
                    @else
                        <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('Profile Image') }}">
                    @endif
                </span>
            </td>
            <td>{{ $feedback->title }}</td>
            <td>{{ $feedback->description }}</td>
            <td>{{ $feedback->rating }}</td>
            <td><x-status.table.active-inactive :status="$feedback->status"/></td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    <li class="status_dropdown__item">
                        <a
                            class="btn dropdown-item status_dropdown__list__link edit_feedback_modal"
                            data-bs-toggle="modal"
                            data-bs-target="#editFeedbackModal"
                            data-id="{{ $feedback->id }}"
                            data-title="{{ $feedback->title }}"
                            data-description="{{ $feedback->description }}"
                            data-rating="{{ $feedback->rating }}">
                            {{ __('Edit Feedback') }}
                        </a>
                    </li>
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Feedback')" :url="route('admin.feedback.delete',$feedback->id)"/></li>
                    <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.feedback.status',$feedback->id)"/></li>
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$feedbacks"/>
