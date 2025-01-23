<div class="modal fade" id="orderSubmitModal" tabindex="-1" aria-labelledby="orderSubmitModallLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('freelancer.order.submit') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="order_id" id="order_id_for_submit_order">
            <input type="hidden" name="order_milestone_id" id="order_milestone_id">
            <input type="hidden" name="client_id" id="client_id_for_notification">

            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="orderSubmitModallLabel">{{ __('Submit Order') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-notice.general-notice :description="__('Notice: After submit order client will review it. Here he will approved it or ask for further modification according to order revisions. Order amount will automatically add your account once the client approved the oreder.')" />
                    <div class="error-message"></div>
                    <div class="single-input mt-4">
                        <label class="label-title mb-3">{{ __('Upload Attachment') }} ({{ __('max 2 GB') }})</label>
                        <input class="form--control" type="file" name="attachment" id="attachment">
                    </div>
                    <div class="single-input mt-4">
                        <label class="label-title mb-3">{{ __('Description') }} ({{ __('max 300 character') }})</label>
                        <textarea class="form--control" name="description" id="description" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer flex-column">
                    <div>
                        <button type="button" class="btn-profile btn-outline-gray btn-hover-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <x-btn.submit :title="__('Submit')" :class="'btn-profile btn-bg-1 submit_order_details'" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
