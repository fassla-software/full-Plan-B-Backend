<div class="profile-wrapper-item add-education-parent radius-10" id="display_user_education_data">
    <div class="profile-wrapper-item-flex flex-between align-items-center profile-border-bottom">
        <h4 class="profile-wrapper-item-title"> {{ __(get_static_option('education_inner_title')) ?? __('Education') }} </h4>
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)
           <div class="profile-wrapper-item-plus add-education add_education_show_hide"><i class="fas fa-plus"></i></div>
        @endif
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Auth::guard('web')->user()->username==$username && Session::get('user_role') == 'freelancer')
            <div class="profile-wrapper-item-plus add-education add_education_show_hide"><i class="fas fa-plus"></i></div>
        @endif
    </div>
    @foreach($educations as $education)
    <div class="setup-wrapper-experience-details">

        <div class="setup-wrapper-experience-details-flex">
            <div class="setup-wrapper-experience-details-left">
                <h5 class="setup-wrapper-experience-details-title">{{ $education->subject }}</h5>
                <p class="setup-wrapper-experience-details-subtitle">{{ $education->institution }}</p>
            </div>
            @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)
                <a href="javascript:void(0)" class="setup-wrapper-experience-details-edit btn-profile btn-hover-danger delete_education delete_education_show_hide" data-id="{{ $education->id }}">
                    <i class="fa-regular fa-trash-can"></i>
                </a>
                <a href="javascript:void(0)"
                   class="setup-wrapper-experience-details-edit education-click edit_single_education edit_education_show_hide"
                   data-id ="{{ $education->id }}"
                   data-institution ="{{ $education->institution }}"
                   data-subject ="{{ $education->subject }}"
                   data-degree ="{{ $education->degree }}"
                   data-start_date="{{ Carbon\Carbon::parse($education->start_date)->toFormattedDateString() }}"
                   data-end_date="{{ Carbon\Carbon::parse($education->end_date)->toFormattedDateString() }}"
                ><i class="fa-regular fa-pen-to-square"></i>
                </a>
            @endif
            @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Auth::guard('web')->user()->username==$username && Session::get('user_role') == 'freelancer')
                <a href="javascript:void(0)" class="setup-wrapper-experience-details-edit btn-profile btn-hover-danger delete_education delete_education_show_hide" data-id="{{ $education->id }}">
                    <i class="fa-regular fa-trash-can"></i>
                </a>
                <a href="javascript:void(0)"
                   class="setup-wrapper-experience-details-edit education-click edit_single_education edit_education_show_hide"
                   data-id ="{{ $education->id }}"
                   data-institution ="{{ $education->institution }}"
                   data-subject ="{{ $education->subject }}"
                   data-degree ="{{ $education->degree }}"
                   data-start_date="{{ Carbon\Carbon::parse($education->start_date)->toFormattedDateString() }}"
                   data-end_date="{{ Carbon\Carbon::parse($education->end_date)->toFormattedDateString() }}"
                ><i class="fa-regular fa-pen-to-square"></i>
                </a>
            @endif
        </div>
        <ul class="setup-wrapper-experience-details-list">
            <li class="setup-wrapper-experience-details-list-item">
                <span class="list-inner"> <i class="fa-solid fa-graduation-cap"></i>
                    <span class="list-inner-para">{{ __('Degree') }}</span>
                </span>
                <span class="list-inner">
                    <span class="list-inner-para">{{ $education->degree }}</span>
                </span>
            </li>
            <li class="setup-wrapper-experience-details-list-item">
                <span class="list-inner"> <i class="fa-solid fa-calendar-days"></i> <span class="list-inner-para">{{ __('From-To') }}</span> </span>
                <span class="list-inner">
                    <span class="list-inner-para">{{ Carbon\Carbon::parse($education->start_date)->toFormattedDateString() }} - <a href="javascript:void(0)">{{ $education->end_date ? Carbon\Carbon::parse($education->end_date)->toFormattedDateString() : __('(Expected)') }}</a></span>
                </span>
            </li>
        </ul>

    </div>
    @endforeach
</div>

<!-- Add Education Starts  -->
<div class="popup-fixed add-education-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title">{{ __(get_static_option('education_modal_title')) ?? __('Educational Background') }}</h2>
        <p class="popup-contents-para">{{ __('Fill the form below to add your educational background') }}</p>
        <div class="popup-contents-form custom-form">
            <form action="#" name="addEducationForm">
                <x-form.text :title="__('Institution')" :type="__('text')" :name="'institution'" :id="'institution'" :divClass="'mb-0'" :placeholder="__('University of Oxford')" :value="''"/>
                <x-form.text :title="__('Degree')" :type="__('text')" :name="'degree'" :id="'degree'" :divClass="'mb-0'" :placeholder="__('B.Sc. in Computer Science')" :value="''"/>
                <x-form.text :title="__('Major (Field of study)')" :type="__('text')" :name="'subject'" :id="'subject'" :divClass="'mb-0'" :placeholder="__('Computer Science Engineering')" :value="''"/>
                <div class="single-flex-input">
                    <x-form.text :title="__('Start date')" :type="__('text')" :name="'start_date'" :id="'start_date_edu'" :divClass="'mb-0'" :class="'form-control date-picker'" :value="''"/>
                    <x-form.text :title="__('End date')" :type="__('text')" :name="'end_date'" :id="'end_date_edu'" :divClass="'mb-0'" :class="'form-control date-picker'" :value="''"/>
                </div>
                <x-form.right-info :infoTitle="__('info:')" :info="__('If you are studying currently please choose an expected date')"/>
            </form>
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }}</a>
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 add_education">{{ __('Save') }}</a>
        </div>
    </div>
</div>
<!-- Add Education Ends  -->



<!-- Edit Education Starts  -->
<div class="popup-fixed education-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title"> {{ __(get_static_option('education_edit_modal_title')) ?? __('Edit Educational Background') }} </h2>
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
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 update_single_education">{{ __('Update') }}</a>
        </div>
    </div>
</div>
<!-- Edit Education Ends  -->
