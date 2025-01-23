<!-- Country Edit Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Category') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.length.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="length_id" id="length_id" value="">
                <div class="modal-body">
                    <x-form.text :title="__('Length')" :type="__('text')" :name="'edit_length'" :id="'edit_length'" :value="''" :placeholder="__('Enter length')"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_length'" />
                </div>
            </form>
        </div>
    </div>
</div>
