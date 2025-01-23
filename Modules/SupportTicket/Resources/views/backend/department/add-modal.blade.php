<!-- Country Edit Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Add New Department') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.department')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <x-notice.general-notice :description="__('Notice: If you select inactive status the department will not show while create a ticket.')" :class="'mb-5'" />
                    <x-form.text :title="__('Department')" :type="__('text')" :name="'name'" :id="'name'" :placeholder="__('Enter department name')"/>
                    <x-form.active-inactive :title="__('Select Status')" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Save')" :class="'btn btn-primary mt-4 pr-4 pl-4 add_department'" />
                </div>
            </form>
        </div>
    </div>
</div>
