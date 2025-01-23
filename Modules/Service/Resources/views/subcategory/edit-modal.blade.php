<!-- Country Edit Modal -->
<div class="modal fade" id="editSubCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Sub Category') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.subcategory.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="edit_sub_category_id" id="edit_sub_category_id" value="">
                <div class="modal-body">
                    <x-form.text :title="__('Sub Category')" :type="__('text')" :name="'edit_sub_category'" :id="'edit_sub_category'" :value="''" :placeholder="__('Enter Sub Category')"/>
                    <x-form.slug :name="'edit_slug'" :id="'edit_slug'"/>
                    <x-form.text :title="__('Meta Title - ideal length is 50–60 characters')" :type="__('text')" :name="'edit_meta_title'" :id="'edit_meta_title'" :value="''" :placeholder="__('Enter meta title')"/>
                    <x-form.textarea :title="__('Meta Description - ideal length is 150-160 characters')" :name="'edit_meta_description'" :id="'edit_meta_description'" :value="''" :placeholder="__('Enter meta description')"/>
                    <x-form.textarea :title="__('Short Description')" :name="'edit_short_description'" :id="'edit_short_description'" :value="old('short_description', '')" :placeholder="__('Max 190 character')"/>
                    <x-form.category-dropdown :title="__('Select Category')" :name="'edit_category'" :id="'edit_category'" :class="'form-control category_select22'" />
                    <x-backend.image :title="__('')" :name="'image'" :dimentions="__('3000x300(optional)')"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_sub_category'" />
                </div>
            </form>
        </div>
    </div>
</div>
