<div class="profile-wrapper-item radius-10 display_profile_info">
    <div class="profile-wrapper-flex flex-between">
        <div class="profile-wrapper-author">
            <div class="profile-wrapper-author-flex d-flex gap-3">
                <div class="profile-wrapper-author-thumb position-relative">
                    @if($user->image)
                        <a href="#/">
                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $user->image, load_from: $user->load_from) }}" alt="{{ __('profile img') }}">
                            @else
                                <img src="{{ asset('assets/uploads/profile/'.$user->image) }}" alt="{{ __('profile img') }}">
                            @endif
                        </a>
                        @if(moduleExists('FreelancerLevel'))
                            @if(get_static_option('profile_page_badge_settings') == 'enable')
                                <div class="freelancer-level-badge position-absolute">
                                    {!! freelancer_level($user->id,'talent') ?? '' !!}
                                </div>
                            @endif
                        @endif
                    @else
                        <a href="#/"><img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}"></a>
                        @if(moduleExists('FreelancerLevel'))
                            @if(get_static_option('profile_page_badge_settings') == 'enable')
                                <div class="freelancer-level-badge position-absolute">
                                    {!! freelancer_level($user->id,'talent') ?? '' !!}
                                </div>
                            @endif
                        @endif
                    @endif

                </div>
                <div class="profile-wrapper-author-cotents">
                    <h4 class="single-freelancer-author-name">
                        <a href="#/" tabindex="0">
                            {{ $user->first_name .' '.$user->last_name  }}@if(moduleExists('FreelancerLevel'))<small>{{ freelancer_level($user->id) }}</small>@endif
                        </a>
                        @if(Cache::has('user_is_online_' . $user->id))
                            <span class="single-freelancer-author-status"> {{ __('Active') }} </span>
                        @else
                            <span class="single-freelancer-author-status-ofline"> {{ __('Inactive') }} </span>
                        @endif
                    </h4>
                    <span class="single-freelancer-author-para mt-2">
                        {{ optional($user->user_introduction)->title ?? '' }} @if($user->user_verified_status == 1) <i class="fas fa-circle-check"></i>@endif
                    </span>
                    {!! freelancer_rating_for_profile_details_page($user->id) !!}
                </div>
            </div>
        </div>
        <div class="profile-wrapper-right">
            <div class="profile-wrapper-right-flex flex-btn">
                @if($user->check_work_availability == 1)
                <span class="profile-wrapper-switch-title"> {{ __('Available for Work') }}</span>
                @endif
                    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)

                <div class="profile-wrapper-switch-custom display_work_availability">
                    <label class="custom_switch">
                            <input type="checkbox" id="check_work_availability" data-user_id="{{ $user->id }}" data-check_work_availability="{{ $user->check_work_availability }}" @if($user->check_work_availability == 1)checked @endif>
                            <span class="slider round"></span>

                    </label>
                </div>
                    @endif
            </div>
        </div>
    </div>

    @if($user?->user_country?->country)
        <div class="profile-wrapper-details profile-border-top">
        @if(moduleExists('HourlyJob'))
            @if($user->hourly_rate >= 1)
            <div class="profile-wrapper-details-single">
                <div class="profile-wrapper-details-single-flex">
                    <h4 class="profile-wrapper-details-single-price display_hourly_rate"> {{ amount_with_currency_symbol($user->hourly_rate ?? '') }} <sub>{{ __('hour') }}</sub></h4>
                    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)
                        <span class="profile-wrapper-details-edit price_edit_show_hide" data-bs-toggle="modal" data-bs-target="#priceModal"><i class="fas fa-edit"></i></span>
                    @endif
                </div>
            </div>
            @endif
        @endif

        <div class="profile-wrapper-details-single">
            <div class="profile-wrapper-details-single-flex">
                <div class="profile-wrapper-details-single-flag">
                    <i class="flag flag-{{strtolower(optional($user->user_country)->country)}}"></i>
                </div>
                <span class="profile-wrapper-details-para"> @if($user?->user_state?->state != null) {{ optional($user->user_state)->state }}, @endif {{ optional($user->user_country)->country }} </span>
            </div>
        </div>

        @if(!empty($user->user_state->timezone))
        <div class="profile-wrapper-details-single">
            <div class="profile-wrapper-details-single-flex">
                <span class="profile-wrapper-details-single-icon"><i class="fa-regular fa-clock"></i></span>
                <span class="profile-wrapper-details-para">
                    @php
                    if(!empty($user->user_state->timezone)){
                        date_default_timezone_set(optional($user->user_state)->timezone ?? '');
                        echo date('h:i:a');
                    }
                    @endphp
                </span>
                    <span>({{ __('Local Time') }})</span>
            </div>
        </div>
        @endif

    </div>
    @endif

    @if($user?->user_introduction?->description)
    <div class="profile-wrapper-about profile-border-top">
        <h4 class="profile-wrapper-about-title"> {{ __('About Me') }} </h4>
        <p class="profile-wrapper-about-para mt-2">{{ optional($user->user_introduction)->description ?? '' }}</p>
    </div>
   @endif
    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)
        <div class="d-flex">
            <div class="profile-wrapper-item-btn flex-btn profile-border-top">
                <div class="change_client_view">
                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray view_as_a_client"> {{ __('View as Client') }} </a>
                </div>
                <a href="javascript:void(0)" class="btn-profile btn-bg-1 edit_info_show_hide" data-bs-toggle="modal" data-bs-target="#profileModal"> {{ __('Edit info') }} </a>
            </div>
            <div class="promote_profile profile-border-top">

                @if(moduleExists('PromoteFreelancer'))
                    @php
                        $current_date = \Carbon\Carbon::now()->toDateTimeString();
                        $is_promoted = \Modules\PromoteFreelancer\Entities\PromotionProjectList::where('identity',auth()->user()->id)
                        ->where('type','profile')
                        ->where('expire_date','>',$current_date)
                        ->where('payment_status','complete')
                        ->first();
                    @endphp

                    @if(!empty($is_promoted))
                        <button type="button" class="btn btn-outline-primary" disabled>{{ __('Profile Promoted') }}</button>
                    @else
                        <a href="javascript:void(0)"
                           class="btn-profile btn-bg-1 open_project_promote_modal"
                           data-bs-target="#openProjectPromoteModal"
                           data-bs-toggle="modal"
                           data-project-id="0">
                            {{ __('Promote Profile') }}
                        </a>
                    @endif

                @endif

            </div>
        </div>
    @endif
</div>

<!--price update modal-->
<div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="PriceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="PriceModalLabel">{{ __('Edit Price') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="single-profile-settings-form custom-form">
                    <div class="error_msg_container"></div>
                    <x-form.text :type="'number'"  min="1" max="300" :title="__('Enter Price')" :id="'hourly_rate'" :class="'form-control'" value="{{ $user->hourly_rate ?? '' }}" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary edit_public_hourly_rate">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>

<!--Update info Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="profileModalLabel">{{ __('Edit Profile Info') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="single-profile-settings-form custom-form">
                    <div class="error_msg_container"></div>
                    <div class="single-flex-input">
                        <x-form.text :type="'text'" :title="__('First Name')" :id="'first_name'" :class="'form-control'" value="{{ $user->first_name }}" />
                        <x-form.text :type="'text'" :title="__('Last Name')" :id="'last_name'" :class="'form-control'" value="{{ $user->last_name }}" />
                    </div>
                    <x-form.text :type="'text'" :title="__('Professional Title')" :id="'professional_title'" :class="'form-control'" value="{{ optional($user->user_introduction)->title }}" />
                    <span id="professional_title_char_length_check"></span>
                    <x-form.textarea :type="'text'" :title="__('Intro About Yourself')" :id="'professional_description'" :class="'form-control'" value="{{ optional($user->user_introduction)->description }}" />
                    <span id="professional_description_char_length_check"></span>
                    <x-form.country-dropdown :title="__('Your Country')" :id="'country_id'" />
                    <x-form.state-dropdown :title="__('Your State')" :id="'state_id'" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn btn-primary edit_public_profile_info">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>

@if(moduleExists('PromoteFreelancer'))
    @include('frontend.profile-details.promotion.project-promote-modal')
@endif
