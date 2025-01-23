<div class="popup-overlay"></div>
<div class="popup-fixed experience-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title"> {{ get_static_option('experience_edit_modal_title') ?? __('Edit Work experience') }} </h2>
        <p class="popup-contents-para"> {{ __('Fill the form below to add your work experience') }} </p>
        <div class="popup-contents-form custom-form">
            <form action="#">
                <input type="hidden" name="edit_id" id="edit_id">
                <x-form.text :title="__('Title')" :type="__('text')" :name="'edit_experience_title'" :id="'edit_experience_title'" :placeholder="__('Front-End Developer')" :value="''"/>
                <x-form.text :title="__('Organization')" :type="__('text')" :name="'edit_organization'" :id="'edit_organization'" :placeholder="__('Xgenious')" :value="''"/>
                <x-form.text :title="__('Address')" :type="__('text')" :name="'edit_address'" :id="'edit_address'" :placeholder="__('8502 Preston Rd. Ingle')" :value="''"/>
                <x-form.textarea :title="__('Short Description')" :name="'edit_short_description'" :id="'edit_short_description'" :cols="'30'" :rows="'3'" :placeholder="__('I am a professional develop...')"/>
                <div class="single-flex-input">
                    <x-form.text :title="__('Start date')" :type="__('text')" :name="'edit_start_date'" :id="'edit_start_date'" :class="'form-control date-picker'" :value="''"/>
                    <x-form.text :title="__('End date')" :type="__('text')" :name="'edit_end_date'" :id="'edit_end_date'" :class="'form-control date-picker'" :value="''"/>
                </div>
                <x-form.right-info :info="__('If you currently working here please leave this field empty')"/>
            </form>
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }} </a>
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 update_single_experience"> {{ __('Update') }} </a>
        </div>
    </div>
</div>
