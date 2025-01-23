<!-- Edit Education Starts  -->
<div class="popup-fixed education-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title"> {{ get_static_option('education_edit_modal_title') ?? __('Edit Educational Background') }} </h2>
        <p class="popup-contents-para">{{ __('Fill the form below to edit your educational background') }}</p>
        <div class="popup-contents-form custom-form">
            <form action="#">
                <input type="hidden" name="edit_id" id="edit_id">
                <x-form.text :title="__('Institution')" :type="__('text')" :name="'edit_institution'" :id="'edit_institution'" :placeholder="__('University of Oxford')" :value="''"/>
                <x-form.text :title="__('Degree')" :type="__('text')" :name="'edit_degree'" :id="'edit_degree'" :placeholder="__('B.Sc. in Computer Science')" :value="''"/>
                <x-form.text :title="__('Major (Field of study)')" :type="__('text')" :name="'edit_subject'" :id="'edit_subject'" :placeholder="__('Computer Science Engineering')" :value="''"/>
                <div class="single-flex-input">
                    <x-form.text :title="__('Start date')" :type="__('text')" :name="'edit_start_date'" :id="'edit_start_date_edu'" :class="'form-control date-picker'" :value="''"/>
                    <x-form.text :title="__('End date')" :type="__('text')" :name="'edit_end_date'" :id="'edit_end_date_edu'" :class="'form-control date-picker'" :value="''"/>
                </div>
                <x-form.right-info :info="__('If you are studying currently please choose an expected date')"/>
            </form>
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }}</a>
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 update_single_education">{{ __('Save') }}</a>
        </div>
    </div>
</div>
<!-- Edit Education Ends  -->
