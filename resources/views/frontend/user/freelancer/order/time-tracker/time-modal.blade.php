<div class="modal fade" id="timeModal" tabindex="-1" aria-labelledby="timeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="timeModalLabel">{{ __('Work Hour') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <x-notice.general-notice
                        :description="__('Notice: If your computer shuts down unexpectedly or you log out before submitting you need to restore the work hour.')"
                />
                <x-notice.general-notice
                        :description="__('Notice: After restoring your work hours, you must submit your work to avoid losing the previous hours. Once submitted, restart the timer.')"
                />

                <strong>{{ __('info: time format must be 00:00:00') }}</strong>
                <input id="manual_time" class="form-control" placeholder="{{ __('Enter time') }}">
            </div>
            <div class="modal-footer flex-column">
                <div>
                    <button type="button" class="btn-profile btn-outline-gray display_restore_time" data-bs-dismiss="modal">{{ __('Add') }}</button>
                    <button type="button" class="btn-profile btn-outline-danger" data-bs-dismiss="modal">{{ __('close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
