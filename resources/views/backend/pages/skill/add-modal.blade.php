<!-- State Edit Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Add Skill') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.skill')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <x-form.text :title="__('Skill')" :type="__('text')" :name="'skill'" :id="'skill'" :placeholder="__('Enter skill name')"/>
                    <x-form.category-dropdown :title="__('Select Category')" :name="'category'" :id="'category'" :class="'form-control select2_category'" />
                    <div class="single-input mb-3">
                        <label class="label-title mt-3">{{ __('Select Subcategory(optional)') }}</label>
                        <select name="subcategory" id="subcategory" class="form-control get_subcategory select2_subcategory">
                            <option value="">{{ __('Select Subcategory') }}</option>
                        </select>
                        <span class="info_msg"></span>
                    </div>
                    <x-form.active-inactive :title="__('Select Status')" :info="__('If you select inactive the services will off for the country')" />

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Save')" :class="'btn btn-primary mt-4 pr-4 pl-4 add_skill'" />
                </div>
            </form>
        </div>
    </div>
</div>
