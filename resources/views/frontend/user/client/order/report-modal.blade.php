<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('client.order.report') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="report_order_id" id="report_order_id">
            <input type="hidden" name="report_to_freelancer_id" id="report_to_freelancer_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="reportModalLabel">{{ __('Report') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-notice.general-notice :description="__('Notice: According reports admin will take action over user.')" />
                    <div class="error-message"></div>
                    <x-form.text :type="'text'" :title="__('Title')" :name="'report_title'" :placeholder="__('Enter title')" />
                    <x-form.summernote
                            :title="__('Write a description about your report')"
                            :name="'report_description'"
                            :id="'report_description'"
                            :rows="'10'" :cols="30"
                            :class="'summernote'"
                    />
                </div>
                <div class="modal-footer flex-column">
                    <div>
                        <button type="button" class="btn-profile btn-outline-gray btn-hover-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <x-btn.submit :title="__('Submit')" :class="'btn-profile btn-bg-1 client_order_report'" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
