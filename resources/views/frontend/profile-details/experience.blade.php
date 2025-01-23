<div class="profile-wrapper-item add-experience-parent radius-10" id="display_user_experience_data">
    <div class="profile-wrapper-item-flex flex-between align-items-center profile-border-bottom">
        <h4 class="profile-wrapper-item-title">{{ __(get_static_option('experience_inner_title')) ?? __('Experiences') }}  </h4>
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)
           <div class="profile-wrapper-item-plus add-experience add_experience_show_hide"><i class="fas fa-plus"></i></div>
        @endif
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Auth::guard('web')->user()->username==$username && Session::get('user_role') == 'freelancer')
            <div class="profile-wrapper-item-plus add-experience add_experience_show_hide"><i class="fas fa-plus"></i></div>
        @endif
    </div>

    @foreach($experiences as $experience)
       <div class="setup-wrapper-experience-details">
            <div class="setup-wrapper-experience-details-flex">
                <div class="setup-wrapper-experience-details-left">
                    <h5 class="setup-wrapper-experience-details-title"> {{ $experience->title }} </h5>
                    <p class="setup-wrapper-experience-details-subtitle"> {{ $experience->organization }} </p>
                </div>
                @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)
                    <a href="javascript:void(0)" class="setup-wrapper-experience-details-edit btn-profile btn-hover-danger delete_experience delete_experience_show_hide" data-id="{{ $experience->id }}">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                    <a href="javascript:void(0)"
                       class="setup-wrapper-experience-details-edit experience-click edit_single_experience edit_experience_show_hide"
                       data-id="{{ $experience->id }}"
                       data-title="{{ $experience->title }}"
                       data-organization="{{ $experience->organization }}"
                       data-address="{{ $experience->address }}"
                       data-short_description="{{ $experience->short_description }}"
                       data-country_id="{{ $experience->country_id }}"
                       data-state_id="{{ $experience->state_id }}"
                       data-start_date="{{ Carbon\Carbon::parse($experience->start_date)->toFormattedDateString() }}"
                       data-end_date="{{ $experience->end_date ? Carbon\Carbon::parse($experience->end_date)->toFormattedDateString() : '' }}"
                    > <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                @endif
                @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Auth::guard('web')->user()->username==$username && Session::get('user_role') == 'freelancer')
                    <a href="javascript:void(0)" class="setup-wrapper-experience-details-edit btn-profile btn-hover-danger delete_experience delete_experience_show_hide" data-id="{{ $experience->id }}">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                    <a href="javascript:void(0)"
                       class="setup-wrapper-experience-details-edit experience-click edit_single_experience edit_experience_show_hide"
                       data-id="{{ $experience->id }}"
                       data-title="{{ $experience->title }}"
                       data-organization="{{ $experience->organization }}"
                       data-address="{{ $experience->address }}"
                       data-short_description="{{ $experience->short_description }}"
                       data-country_id="{{ $experience->country_id }}"
                       data-state_id="{{ $experience->state_id }}"
                       data-start_date="{{ Carbon\Carbon::parse($experience->start_date)->toFormattedDateString() }}"
                       data-end_date="{{ $experience->end_date ? Carbon\Carbon::parse($experience->end_date)->toFormattedDateString() : '' }}"
                    > <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                @endif
            </div>
            <ul class="setup-wrapper-experience-details-list">
                <li class="setup-wrapper-experience-details-list-item">
                        <span class="list-inner">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span class="list-inner-para">{{ __('Duration') }}</span>
                        </span>
                    <span class="list-inner">
                        <span class="list-inner-para">
                            {{ Carbon\Carbon::parse($experience->start_date)->toFormattedDateString() }} - <a
                                href="javascript:void(0)">{{ $experience->end_date ? Carbon\Carbon::parse($experience->end_date)->toFormattedDateString() : __('Current Position') }} </a>
                        </span>
                    </span>
                </li>
                <li class="setup-wrapper-experience-details-list-item">
                        <span class="list-inner"> <i class="fa-solid fa-location-dot"></i> <span
                                class="list-inner-para">{{ __('Location') }}</span> </span>
                    <span class="list-inner"> <span
                            class="list-inner-para">{{ $experience->address }}</span></span>
                </li>
            </ul>
       </div>
    @endforeach
</div>

{{--add experience modal--}}
<div class="popup-fixed add-experience-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title">{{ __(get_static_option('experience_modal_title')) ?? __('Work experience') }}</h2>
        <p class="popup-contents-para">{{ __('Fill the form below to add your work experience') }}</p>
        <div class="popup-contents-form custom-form">
            <form action="#" name="addExperienceForm">
                <x-form.text :title="__('Title')" :type="__('text')" :name="'experience_title'" :id="'experience_title'" :divClass="'mb-0'" :placeholder="__('Front-End Developer')" :value="''"/>
                <x-form.text :title="__('Organization')" :type="__('text')" :name="'organization'" :id="'organization'" :divClass="'mb-0'" :placeholder="__('Xgenious')" :value="''"/>
                <x-form.text :title="__('Address')" :type="__('text')" :name="'address'" :id="'address'" :divClass="'mb-0'" :placeholder="__('8502 Preston Rd. Ingle')" :value="''"/>
                <x-form.textarea :title="__('Short Description')" :name="'short_description'" :id="'short_description'" :divClass="'mb-0'" :cols="'30'" :rows="'3'" :placeholder="__('I am a professional develop...')"/>
                <div class="single-flex-input mt-0">
                    <x-form.text :title="__('Start date')" :type="__('text')" :name="'start_date'" :id="'start_date'" :divClass="'mb-0'" :class="'form-control date-picker'" :value="''"/>
                    <x-form.text :title="__('End date')" :type="__('text')" :name="'end_date'" :id="'end_date'" :divClass="'mb-0'" :class="'form-control date-picker'" :value="''"/>
                </div>
                <x-form.right-info :infoTitle="__('info:')" :info="__('If you currently working here please leave this field empty')"/>
            </form>
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }}</a>
            <a href="javascript:void(0)" class="btn-profile btn-bg-1 add_experience">{{ __('Save') }}</a>
        </div>
    </div>
</div>


{{--edit experience modal--}}
<div class="popup-overlay"></div>
<div class="popup-fixed experience-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title"> {{ __(get_static_option('experience_edit_modal_title')) ?? __('Edit Work experience') }} </h2>
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

