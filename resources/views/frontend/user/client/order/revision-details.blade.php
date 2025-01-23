<div class="modal fade" id="RevisionDetailsModal" tabindex="-1" aria-labelledby="RevisionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="RevisionDetailsModalLabel">{{ __('Revision Details') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="display_request_revision_description"></p>
            </div>
            <div class="modal-footer flex-column">
                <div>
                    <button type="button" class="btn-profile btn-outline-gray btn-hover-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
