@extends('frontend.layout.master')
@section('site_title', __('Order Rating'))
@section('content')
    <main>
        <x-frontend.category.category />
        <x-breadcrumb.user-profile-breadcrumb :title="__('Rating')" :innerTitle="__('Rating')"/>

        <!-- End Contract area Starts -->
        <div class="end-contract-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <form action="{{ route('freelancer.order.rating',$id) }}" method="post">
                    @csrf
                    <div class="row gy-4 justify-content-center">
                        <div class="col-lg-8">
                            <input type="hidden" name="skill_rating" id="skill_rating" value="0">
                            <input type="hidden" name="availability_rating" id="availability_rating" value="0">
                            <input type="hidden" name="communication_rating" id="communication_rating" value="0">
                            <input type="hidden" name="work_quality_rating" id="work_quality_rating" value="0">
                            <input type="hidden" name="deadline_rating" id="deadline_rating" value="0">
                            <input type="hidden" name="co_operation_rating" id="co_operation_rating" value="0">

                            <div class="end-contract">
                                <div class="end-contract-single">
                                    <div class="end-contract-single-select">
                                        <label class="label-title">{{ __('Leave a Review') }}</label>
                                        <textarea name="review_feedback" id="review_feedback" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="end-contract-feedback mt-4">
                                    <h4 class="end-contract-feedback-title">{{ __('Provide Feedback') }}</h4>
                                    <p class="end-contract-feedback-para mt-2">{{ __('Your feedback will be shared to publicly in client profile and client feedback will be shared publicly in your profile.') }}</p>
                                    <div class="end-contract-feedback-contents mt-4">

                                        <div data-reaction-type="skills" class="end-contract-feedback-single">
                                            <div class="end-contract-feedback-single-title-flex">
                                                <h4 class="end-contract-feedback-single-title">{{ __('How would you rate') }} {{ $find_login_user_order?->user->first_name }}{{ __("'s") }} {{ __('skills') }} </h4>
                                                <div class="btn-wrapper click-skip">
                                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray get_skill_val" data-skill_val="0">{{ __('Reset This') }}</a>
                                                </div>
                                            </div>
                                            <div class="end-contract-feedback-single-contents profile-border-top">
                                                <div class="end-contract-reaction">
                                                    <div class="end-contract-reaction-item reaction-list get_skill_val" data-skill_val="1">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very sad') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/sad_reaction.svg') }}" alt="sad_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_skill_val" data-skill_val="2">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Not Good') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/not_good_reaction.svg') }}" alt="not_good_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_skill_val" data-skill_val="3">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("It's Ok") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/its_ok_reaction.svg') }}" alt="its_ok_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_skill_val" data-skill_val="4">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("I'm Happy") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/happy_reaction.svg') }}" alt="happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_skill_val" data-skill_val="5">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very Happy') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/very_happy_reaction.svg') }}" alt="very_happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div data-reaction-type="availability" class="end-contract-feedback-single">
                                            <div class="end-contract-feedback-single-title-flex">
                                                <h4 class="end-contract-feedback-single-title">{{ __('Rate') }} {{ $find_login_user_order?->user->first_name }}{{ __("'s") }} {{ __('availability') }} </h4>
                                                <div class="btn-wrapper click-skip">
                                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray get_availability_val" data-availability_val="0">{{ __('Reset This') }}</a>
                                                </div>
                                            </div>
                                            <div class="end-contract-feedback-single-contents profile-border-top">
                                                <div class="end-contract-reaction">
                                                    <div class="end-contract-reaction-item reaction-list get_availability_val" data-availability_val="1">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("Very sad") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/sad_reaction.svg') }}" alt="sad_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_availability_val" data-availability_val="2">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("Not Good") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/not_good_reaction.svg') }}" alt="not_good_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_availability_val" data-availability_val="3">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("It's Ok") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/its_ok_reaction.svg') }}" alt="its_ok_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_availability_val" data-availability_val="4">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("I'm Happy") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/happy_reaction.svg') }}" alt="happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_availability_val" data-availability_val="5">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very Happy') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/very_happy_reaction.svg') }}" alt="very_happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div data-reaction-type="communication" class="end-contract-feedback-single">
                                            <div class="end-contract-feedback-single-title-flex">
                                                <h4 class="end-contract-feedback-single-title">{{ __('Rate') }} {{ $find_login_user_order?->user->first_name }}{{ __("'s") }} {{ __('Communications') }} </h4>
                                                <div class="btn-wrapper click-skip">
                                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray get_communication_val" data-communication_val="0">{{ __('Reset This') }}</a>
                                                </div>
                                            </div>
                                            <div class="end-contract-feedback-single-contents profile-border-top">
                                                <div class="end-contract-reaction">
                                                    <div class="end-contract-reaction-item reaction-list get_communication_val" data-communication_val="1">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very sad') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/sad_reaction.svg') }}" alt="sad_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_communication_val" data-communication_val="2">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Not Good') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/not_good_reaction.svg') }}" alt="not_good_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_communication_val" data-communication_val="3">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("It's Ok") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/its_ok_reaction.svg') }}" alt="its_ok_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_communication_val" data-communication_val="4">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("I'm Happy") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/happy_reaction.svg') }}" alt="happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_communication_val" data-communication_val="5">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very Happy') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/very_happy_reaction.svg') }}" alt="very_happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div data-reaction-type="work-quality" class="end-contract-feedback-single">
                                            <div class="end-contract-feedback-single-title-flex">
                                                <h4 class="end-contract-feedback-single-title">{{ __('Rate') }} {{ $find_login_user_order?->user->first_name }}{{ __("'s") }} {{ __('Work Quality') }} </h4>
                                                <div class="btn-wrapper click-skip">
                                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray get_work_quality_val" data-work_quality_val="0">{{ __('Reset This') }}</a>
                                                </div>
                                            </div>
                                            <div class="end-contract-feedback-single-contents profile-border-top">
                                                <div class="end-contract-reaction">
                                                    <div class="end-contract-reaction-item reaction-list get_work_quality_val" data-work_quality_val="1">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very sad') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/sad_reaction.svg') }}" alt="sad_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_work_quality_val" data-work_quality_val="2">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Not Good') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/not_good_reaction.svg') }}" alt="not_good_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_work_quality_val" data-work_quality_val="3">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("It's Ok") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/its_ok_reaction.svg') }}" alt="its_ok_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_work_quality_val" data-work_quality_val="4">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("I'm Happy") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/happy_reaction.svg') }}" alt="happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_work_quality_val" data-work_quality_val="5">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very Happy') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/very_happy_reaction.svg') }}" alt="very_happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div data-reaction-type="meeting-deadline" class="end-contract-feedback-single">
                                            <div class="end-contract-feedback-single-title-flex">
                                                <h4 class="end-contract-feedback-single-title">{{ __('Rate') }} {{ $find_login_user_order?->user->first_name }}{{ __("'s") }} {{ __('Meetings') }} </h4>
                                                <div class="btn-wrapper click-skip">
                                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray get_deadline_val" data-deadline_val="0">{{ __('Reset This') }}</a>
                                                </div>
                                            </div>
                                            <div class="end-contract-feedback-single-contents profile-border-top">
                                                <div class="end-contract-reaction">
                                                    <div class="end-contract-reaction-item reaction-list get_deadline_val" data-deadline_val="1">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("Very sad") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/sad_reaction.svg') }}" alt="sad_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_deadline_val" data-deadline_val="2">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Not Good') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/not_good_reaction.svg') }}" alt="not_good_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_deadline_val" data-deadline_val="3">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("It's Ok") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/its_ok_reaction.svg') }}" alt="its_ok_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_deadline_val" data-deadline_val="4">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("I'm Happy") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/happy_reaction.svg') }}" alt="happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_deadline_val" data-deadline_val="5">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very Happy') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/very_happy_reaction.svg') }}" alt="very_happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div data-reaction-type="co-operations" class="end-contract-feedback-single">
                                            <div class="end-contract-feedback-single-title-flex">
                                                <h4 class="end-contract-feedback-single-title">{{ __('Rate') }} {{ $find_login_user_order?->user->first_name }}{{ __("'s") }} {{ __('Co-operations') }} </h4>
                                                <div class="btn-wrapper click-skip">
                                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray get_co_operation_val" data-co_operation_val="0">{{ __('Reset This') }}</a>
                                                </div>
                                            </div>
                                            <div class="end-contract-feedback-single-contents profile-border-top">
                                                <div class="end-contract-reaction">
                                                    <div class="end-contract-reaction-item reaction-list get_co_operation_val" data-co_operation_val="1">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very sad') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/sad_reaction.svg') }}" alt="sad_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_co_operation_val" data-co_operation_val="2">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Not Good') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/not_good_reaction.svg') }}" alt="not_good_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_co_operation_val" data-co_operation_val="3">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("It's Ok") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/its_ok_reaction.svg') }}" alt="its_ok_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_co_operation_val" data-co_operation_val="4">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __("I'm Happy") }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/happy_reaction.svg') }}" alt="happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="end-contract-reaction-item reaction-list get_co_operation_val" data-co_operation_val="5">
                                                        <span class="end-contract-reaction-item-tooltip">{{ __('Very Happy') }}</span>
                                                        <div class="end-contract-reaction-item-flex">
                                                            <div class="end-contract-reaction-icon">
                                                                <img src="{{ asset('assets/static/icons/very_happy_reaction.svg') }}" alt="very_happy_reaction.svg">
                                                            </div>
                                                            <div class="end-contract-reaction-review">
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                                <span class="end-contract-reaction-review-star"><i class="fa-solid fa-star"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="end-contract-widget sticky_top_lg">
                                <div class="end-contract-widget-item">
                                    <ul class="end-contract-widget-list">
                                        <li class="end-contract-widget-list-item skills">{{ __('Skills') }}</li>
                                        <li class="end-contract-widget-list-item availability">{{ __('Availability') }}</li>
                                        <li class="end-contract-widget-list-item communication">{{ __('Communication') }}</li>
                                        <li class="end-contract-widget-list-item work-quality">{{ __('Work Quality') }}</li>
                                        <li class="end-contract-widget-list-item meeting-deadline">{{ __('Meeting Deadline') }}</li>
                                        <li class="end-contract-widget-list-item co-operations">{{ __('Co-operations') }}</li>
                                    </ul>
                                    <div class="end-contract-widget-item-footer profile-border-top">
                                        <div class="overall-score">
                                            <span class="overall-score-para">{{ __('Overall Score') }}</span>
                                            <span class="overall-score-review">
                                                <span class="overall-score-review-icon">
                                                    <i class="fa-solid fa-star"></i> </span>
                                                <span class="overall-score-review-para show_average_score">0.0</span>
                                            </span>
                                        </div>
                                        <div class="btn-wrapper mt-4">
                                            <button type="submit" class="btn-profile btn-bg-1 w-100 submit_rating">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Contract area end -->
    </main>

@endsection

@section('script')
    @include('frontend.user.freelancer.order.rating.rating-js')
@endsection
