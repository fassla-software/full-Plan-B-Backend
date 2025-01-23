<!-- About Job Start -->
<div class="setup-wrapper-contents active">
    <div class="setup-wrapper-contents-item">
        <div class="setup-bank-form">
            <x-form.text :title="__('Job Title')" :type="'text'" :id="'title'" :name="'title'" :divClass="'mb-0'" :class="'form--control'" :value="old('title')" :placeholder="__('e.g. I need  landing page')" />
            <span id="job_title_char_length_check"></span>

            <x-form.text :title="__('Slug')" :type="'text'" :id="'slug'" :name="'slug'" :value="old('slug')" :divClass="'mb-0'" :class="'form--control d-none'" :labelClass="'d-none display_label_title'" :placeholder="__('Slug')" />
            <div class="mb-0">

                <strong>{{ __('Slug:') }}</strong>
                <span class="full-slug-show"></span>
                <span class="edit_job_slug"><i class="fas fa-edit"></i></span>
            </div>

            <x-form.category-dropdown :title="__('Select Category')" :name="'category'" :id="'category'" :class="'form-control category_select2'" />
            <div class="single-input">
                <label class="label-title">{{ __('Select Subcategory') }}</label>
                <select name="subcategory[]" id="subcategory" class="form-control get_subcategory subcategory_select2" multiple></select>
                <span id="subcategory_info"></span>
            </div>

            @if($all_lengths->count() >= 1)
                <div class="single-input">
                    <label class="label-title">{{ __('Job duration') }}</label>
                    <select name="duration" id="duration" class="form-control">
                        <option value="">{{ __('Select Duration') }}</option>
                        @foreach($all_lengths as $length)
                        <option value="{{ $length->length }}">{{ ucfirst($length->length) }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="single-input">
                    <label class="label-title">{{ __('Job duration') }}</label>
                    <select name="duration" id="duration" class="form-control">
                        <option value="">{{ __('Select Duration') }}</option>
                        <option value="1 Days">{{ __('1 Days') }}</option>
                        <option value="1 Days">{{ __('2 Days') }}</option>
                        <option value="1 Days">{{ __('3 Days') }}</option>
                        <option value="less than a week">{{ __('Less than a Week') }}</option>
                        <option value="less than a month">{{ __('Less than a month') }}</option>
                        <option value="less than 2 month">{{ __('Less than 2 month') }}</option>
                        <option value="less than 3 month">{{ __('Less than 3 month') }}</option>
                        <option value="More than 3 month">{{ __('More than 3 month') }}</option>
                    </select>
                </div>
            @endif
            <x-form.experience-level-dropdown :title="__('Select Experience Level')" :class="'form-control'" :name="'level'" :id="'level'"/>
            <x-form.summernote
                :title="__('Write a job description')"
                :name="'description'"
                :id="'description'"
                :rows="'10'" :cols="30"
                :value="old('description')"
                :class="'description '"
            />
            <span id="job_description_char_length_check"></span>

            <x-form.text :title="__('Meta Title - ideal length is 50–60 characters (optional)')" :type="'text'" :id="'meta_title'" :name="'meta_title'" :divClass="'mb-0'" :class="'form--control'" :value="old('meta_title')" :placeholder="__('Enter meta title')" />

            <div class="single-input">
                <label class="label-title">{{ __('Meta Description - ideal length is 150-160 characters (optional)') }}</label>
                <textarea name="meta_description" id="meta_description" class="form-message" cols="30" rows="3" placeholder="{{ __('Enter meta description') }}"></textarea>
            </div>

        </div>
    </div>
</div>
<!-- About Job Ends -->
