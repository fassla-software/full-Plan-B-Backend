<div class="modal fade" id="rejectProjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Reject Project') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.project.reject') }}" id="rejectProjectForm" method="post">
                <input type="hidden" name="reject_project_id" value="{{ $project->id ?? '' }}">
                @csrf
                <div class="modal-body">

                    <x-notice.general-notice :description="__('Notice: Tell your user why you reject this project so that he can rewrite it correctly.')" />
                    <label class="label-title">{{ __('Enter a description - optional') }}</label>
                    <textarea name="reject_reason" rows="5" class="form-control mt-3">{{ $project?->project_history?->reject_reason ?? '' }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Reject Project')" :class="'btn btn-danger mt-4 pr-4 pl-4'" />
                </div>
            </form>
        </div>
    </div>
</div>

