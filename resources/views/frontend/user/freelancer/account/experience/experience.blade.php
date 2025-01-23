<!-- Setup Experience Start -->
<div class="setup-wrapper-contents">
    <div class="setup-wrapper-contents-item">
        <h3 class="setup-wrapper-contents-title">{{ get_static_option('experience_title') ?? __('Tell us about your professional experiences(Experience)') }}</h3>
        <div class="setup-wrapper-experience">
            <div class="setup-wrapper-experience-add">
                <span class="setup-wrapper-experience-add-title">{{ __('Add a work experience') }}</span>
                <a href="javascript:void(0)" class="setup-wrapper-experience-add-icon add-experience"> <i
                        class="fas fa-plus"></i> </a>
            </div>
        </div>
    </div>
    <div class="setup-wrapper-contents-item" id="display_user_experience_data">
        <h4 class="setup-wrapper-contents-title-two">{{ get_static_option('experience_inner_title') ?? __('Experiences') }} </h4>
        @foreach($experiences as $experience)
            <div class="setup-wrapper-experience">
                <div class="setup-wrapper-experience-details">
                    <div class="setup-wrapper-experience-details-flex">
                        <div class="setup-wrapper-experience-details-left">
                            <h5 class="setup-wrapper-experience-details-title"> {{ $experience->title }} </h5>
                            <p class="setup-wrapper-experience-details-subtitle"> {{ $experience->organization }} </p>
                        </div>
                        <a href="javascript:void(0)"
                           class="setup-wrapper-experience-details-edit experience-click edit_single_experience"
                           data-id="{{ $experience->id }}"
                           data-title="{{ $experience->title }}"
                           data-organization="{{ $experience->organization }}"
                           data-address="{{ $experience->address }}"
                           data-short_description="{{ $experience->short_description }}"
                           data-start_date="{{ Carbon\Carbon::parse($experience->start_date)->toFormattedDateString() }}"
                           data-end_date="{{ $experience->end_date ? Carbon\Carbon::parse($experience->end_date)->toFormattedDateString() : '' }}"
                        > <i class="fas fa-edit"></i>
                        </a>
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
            </div>
        @endforeach
    </div>
</div>


<!-- Edit Experience Starts  -->
@include('frontend.user.freelancer.account.experience.edit-experience-modal')
<!-- Edit Experience Ends  -->

<!-- Add Experience Starts  -->
@include('frontend.user.freelancer.account.experience.add-experience-modal')
<!-- Add Experience Ends  -->

