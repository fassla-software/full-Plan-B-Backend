<!-- Project Introduction Start -->
<div class="setup-wrapper-contents active">
    <div class="create-project-wrapper-item">
        <div class="create-project-wrapper-item-top profile-border-bottom">
            <h4 class="create-project-wrapper-title">{{ __('Project Intro') }}</h4>
        </div>
        <div class="create-project-intro-contents">
            <div class="create-project-intro-contents-form custom-form">
                <x-form.category-dropdown :title="__('Select Category')" :name="'category'" :id="'category'" :class="'form-control category_select2'" />

                <div class="single-input">
                    <label class="label-title">{{ __('Select Subcategory') }}</label>
                    <select name="subcategory[]" id="subcategory" class="form-control get_subcategory subcategory_select2" multiple>
                    </select>
                    <span id="subcategory_info"></span>
                </div>


               <x-form.text :title="__('What are you offering to clients?')" :type="'text'" :id="'project_title'" :name="'project_title'" :divClass="'mb-0'" :class="'form--control'" :value="old('project_title')" :placeholder="__('You’ll get a Mobile application designed')" />
                <span id="project_title_char_length_check"></span>
                <x-form.text :title="__('Slug')" :type="'text'" :id="'slug'" :name="'slug'" :value="old('slug')" :divClass="'mb-0'" :class="'form--control d-none'" :labelClass="'d-none display_label_title'" :placeholder="__('Slug')" />
                <div class="mb-4">
                    <strong>{{ __('Slug:') }}</strong>
                    <span class="full-slug-show"></span>
                    <span class="edit_project_slug"><i class="fas fa-edit"></i></span>
                </div>


                <x-form.summernote
                    :title="__('Write a description about your service')"
                    :name="'project_description'"
                    :id="'project_description'"
                    :rows="'10'" :cols="30"
                    :value="old('project_description')"
                    :class="'description'"
                />
                <span id="project_description_char_length_check"></span>

                <x-form.text :title="__('Meta Title - ideal length is 50–60 characters (optional)')" :type="'text'" :id="'meta_title'" :name="'meta_title'" :divClass="'mb-0'" :class="'form--control'" :value="old('meta_title')" :placeholder="__('Enter meta title')" />

                <div class="single-input">
                    <label class="label-title">{{ __('Meta Description - ideal length is 150-160 characters (optional)') }}</label>
                    <textarea name="meta_description" id="meta_description" class="form-message" cols="30" rows="3" placeholder="{{ __('Enter meta description') }}"></textarea>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Project Introduction Ends -->
