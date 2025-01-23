<!-- Add Education Starts  -->
<div class="popup-fixed add-education-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title">{{ get_static_option('education_modal_title') ?? __('Educational Background') }}</h2>
        <p class="popup-contents-para">{{ __('Fill the form below to add your educational background') }}</p>
        <div class="popup-contents-form custom-form">
            <form action="#" name="addEducationForm">
                <x-form.text :title="__('Institution')" :type="__('text')" :name="'institution'" :id="'institution'" :placeholder="__('University of Oxford')" :value="''"/>
                <x-form.text :title="__('Degree')" :type="__('text')" :name="'degree'" :id="'degree'" :placeholder="__('B.Sc. in Computer Science')" :value="''"/>
                <x-form.text :title="__('Major (Field of study)')" :type="__('text')" :name="'subject'" :id="'subject'" :placeholder="__('Computer Science Engineering')" :value="''"/>
                <div class="single-flex-input">
                    <x-form.text :title="__('Start date')" :type="__('text')" :name="'start_date'" :id="'start_date_edu'" :class="'form-control date-picker'" :value="''"/>
                    <x-form.text :title="__('End date')" :type="__('text')" :name="'end_date'" :id="'end_date_edu'" :class="'form-control date-picker'" :value="''"/>
                </div>
                <x-form.right-info :info="__('If you are studying currently please choose an expected date')"/>
            </form>
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }}</a>
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 add_education">{{ __('Save') }}</a>
        </div>
    </div>
</div>
<!-- Add Education Ends  -->
