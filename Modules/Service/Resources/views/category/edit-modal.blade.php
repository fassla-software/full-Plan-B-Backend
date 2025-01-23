<!-- Country Edit Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Category') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.category.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" id="category_id" value="">
                <div class="modal-body">
                    <x-form.text :title="__('Category')" :type="__('text')" :name="'edit_category'" :id="'edit_category'" :value="''" :placeholder="__('Enter Category name')"/>
                    <x-form.slug :name="'edit_slug'" :id="'edit_slug'"/>
                    <x-form.text :title="__('Meta Title - ideal length is 50–60 characters')" :type="__('text')" :name="'edit_meta_title'" :id="'edit_meta_title'" :value="''" :placeholder="__('Enter meta title')"/>
                    <x-form.textarea :title="__('Meta Description - ideal length is 150-160 characters')" :name="'edit_meta_description'" :id="'edit_meta_description'" :value="''" :placeholder="__('Enter meta description')"/>
                    <x-form.textarea :title="__('Short Description')" :name="'edit_short_description'" :id="'edit_short_description'" :value="''" :placeholder="__('Max 190 character')"/>
                    <x-backend.image :title="__('')" :name="'image'" :dimentions="__('300x300(optional)')"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_category'" />
                </div>
            </form>
        </div>
    </div>
</div>
