<!-- State Edit Modal -->
<div class="modal fade" id="operationDetailsEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Operation Cost') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.operation-costs.info.edit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="edit_operation_details">
                    <div class="edit_operation_detailsInfo">
                        <input type="hidden" name="edit_operation_id" id="edit_operation_id" value="">
                        <x-form.text :title="__('Cost')" :type="'text'" :name="'edit_cost'" :id="'edit_cost'"
                            :placeholder="__('Enter Operation Cost')" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_operation_info'" />
                </div>
            </form>
        </div>
    </div>
</div>
