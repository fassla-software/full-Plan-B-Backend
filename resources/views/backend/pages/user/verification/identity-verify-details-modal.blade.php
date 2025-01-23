<!-- State Edit Modal -->
<div class="modal fade" id="userIdentityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Verify User Identity') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="user_identity_details">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Decline Verify Identity')" :class="'btn btn-danger mt-4 pr-4 pl-4 user_identity_decline'" />
                    <x-btn.submit :title="__('Update Verify Identity Status')" :class="'btn btn-primary mt-4 pr-4 pl-4 user_verify_status'" />
                </div>
            </form>
        </div>
    </div>
</div>
