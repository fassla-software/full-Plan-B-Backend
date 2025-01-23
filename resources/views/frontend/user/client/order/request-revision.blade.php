<div class="modal fade" id="RevisionRequestModal" tabindex="-1" aria-labelledby="RevisionRequestModallLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('client.order.revision') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="order_submit_history_id" id="order_submit_history_id">
            <input type="hidden" name="order_id" id="order_id_for_revision_order">
            <input type="hidden" name="order_milestone_id" id="order_milestone_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="RevisionRequestModallLabel">{{ __('Request Revision') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-notice.general-notice :description="__('Notice: You will send revision request if the order or milestone has available revision.')" />
                    <div class="error-message"></div>
                    <x-form.summernote
                        :title="__('Write a description about your modification')"
                        :name="'revision_description'"
                        :id="'revision_description'"
                        :rows="'10'" :cols="30"
                        :class="'summernote'"
                    />
                </div>
                <div class="modal-footer flex-column">
                    <div>
                        <button type="button" class="btn-profile btn-outline-gray btn-hover-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <x-btn.submit :title="__('Submit')" :class="'btn-profile btn-bg-1 request_for_order_revision'" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
