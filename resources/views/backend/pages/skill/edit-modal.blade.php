<!-- State Edit Modal -->
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Skill') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.skill.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="skill_id" id="skill_id" value="">

                <div class="modal-body">
                    <x-form.text :title="__('Skill')" :type="__('text')" :name="'edit_skill'" :id="'edit_skill'" :placeholder="__('Enter skill name')"/>
                    <div class="single-input">
                        <label class="label-title mt-3">{{ __('Select Category') }}</label>
                        <select name="edit_category" id="edit_category" class="form-control select22_category">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($all_categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="single-input mb-3">
                        <label class="label-title mt-3">{{ __('Select Subcategory(optional)') }}</label>
                        <select name="edit_sub_category" id="edit_sub_category" class="form-control get_subcategory select22_subcategory">
                            <option value="">{{ __('Select Subcategory') }}</option>
                            @foreach($all_sub_categories as $sub_cat)
                                <option value="{{ $sub_cat->id }}">{{ $sub_cat->subcategory }}</option>
                            @endforeach
                        </select>
                        <span class="info_msg"></span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 edit_skill'" />
                </div>
            </form>
        </div>
    </div>
</div>
