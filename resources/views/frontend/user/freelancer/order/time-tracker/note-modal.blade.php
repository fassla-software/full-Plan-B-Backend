<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="noteModalLabel">{{ __('Add Notes') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea id="work_notes" class="form-message" rows="3" placeholder="{{ __('Enter notes') }}"></textarea>
            </div>
            <div class="modal-footer flex-column">
                <div>
                    <button type="button" class="btn-profile btn-outline-gray add_notes" data-bs-dismiss="modal">{{ __('Add') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
