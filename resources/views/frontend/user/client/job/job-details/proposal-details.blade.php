@extends('frontend.layout.master')
@section('site_title',__('Proposal Details'))
@section('style')
    <x-summernote.summernote-css/>
    <x-select2.select2-css/>
    <style>
        .cover_letter_details{white-space:pre-line}
    </style>
@endsection
@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Proposal Details')" :innerTitle="__('Proposal Details')"/>
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row gy-4 justify-content-center">
                    <div class="col-lg-12">
                        <div class="profile-wrapper">
                            <div class="myJob-wrapper">
                                <div class="myJob-wrapper-single">
                                    <div class="myJob-wrapper-single-flex flex-between align-items-center">
                                        <div class="myJob-wrapper-single-contents">
                                            <div class="jobFilter-proposal-author-flex">
                                                <div class="jobFilter-proposal-author-thumb position-relative">
                                                    @if($proposal_details->freelancer?->image)
                                                        <a href="{{ route('freelancer.profile.details', $proposal_details?->freelancer->username) }}">
                                                            <img src="{{ asset('assets/uploads/profile/'.$proposal_details?->freelancer?->image) }}" alt="{{ $proposal_details?->freelancer?->fullname }}">
                                                        </a>
                                                    @else
                                                        <a href="{{ route('freelancer.profile.details', $proposal_details?->freelancer->username) }}">
                                                        <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}">
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="jobFilter-proposal-author-contents">
                                                    <h4 class="jobFilter-proposal-author-contents-title">
                                                        <a href="{{ route('freelancer.profile.details', $proposal_details?->freelancer->username) }}">
                                                        {{ $proposal_details->freelancer?->fullname ?? '' }}
                                                        </a>
                                                        <x-status.user-active-inactive-check :userID="$proposal_details->freelancer->id" />
                                                    </h4>
                                                    <p class="jobFilter-proposal-author-contents-subtitle mt-1"> {{ $proposal_details->freelancer?->user_introduction?->title ?? '' }} · <span>{{ $proposal_details->freelancer?->user_state?->state ?? '' }}, {{ $proposal_details->freelancer?->user_country?->country ?? '' }}</span> </p>
                                                    <div class="jobFilter-proposal-author-contents-review mt-2">
                                                        {!! freelancer_rating($proposal_details->freelancer_id) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="myJob-wrapper-single-arrow">
                                            <div class="job-proposal-btn">
                                                <div class="job-proposal-btn-item">
                                                    <x-job.job-proposal-view :isView="$proposal_details->is_view" />
                                                </div>
                                                <div class="job-proposal-btn-item">
                                                    <x-job.hire-short-list-check :isHired="$proposal_details->is_hired" :isShortListed="$proposal_details->is_short_listed" />
                                                </div>
                                                <div class="job-proposal-btn-item">
                                                    <p class="jobFilter-proposal-author-contents-time">{{ $proposal_details->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jobFilter-proposal-offered profile-border-top">
                                        <div class="jobFilter-proposal-offered-single">
                                            <span class="offered">{{ __('Offered') }} <span class="offered-price">{{ float_amount_with_currency_symbol($proposal_details->amount) }}</span> </span>
                                        </div>
                                        <div class="jobFilter-proposal-offered-single">
                                            <span class="offered">{{ __('Est. delivery duration') }} <span class="offered-days">{{ $proposal_details->duration }}</span> </span>
                                        </div>
                                        @if($proposal_details?->job->type == 'hourly')
                                            <div class="jobFilter-proposal-offered-single">
                                                <span class="offered">{{ __(ucfirst($proposal_details?->job->type)) }}
                                                 <span class="offered-price">{{ float_amount_with_currency_symbol($proposal_details?->job->hourly_rate) }}</span>
                                                </span>
                                            </div>
                                        @endif
                                        @if($proposal_details?->job->type == 'hourly')
                                            <div class="jobFilter-proposal-offered-single">
                                                <span class="offered">{{ __('Estimated hour') }}
                                                 <span class="offered-price">{{ $proposal_details?->job->estimated_hours ?? '' }}</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-between profile-border-top">
                                        <div class="btn-wrapper flex-btn gap-2 add_remove_interview_location_{{ $proposal_details->id }}">

                                            <a href="javascript:void(0)" class="loadingRound add_remove_shortlist" data-proposal-id="{{ $proposal_details->id }}">
                                                @if($proposal_details->is_short_listed == 0)
                                                    <span class="btn-profile btn-outline-gray add_to_short_listed">{{ __('Add to Shortlist') }}</span>
                                                @else
                                                    <span class="btn-profile btn-outline-gray remove_from_short_listed">{{ __('Remove from Shortlist') }}</span>
                                                @endif
                                            </a>

                                            @if($proposal_details?->job->type == 'hourly')
                                                <a href="javascript:void(0)"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#RateAndHoursModal"
                                                   class="btn-profile btn-bg-1">{{ __('Update Hourly Rate') }}</a>
                                            @endif

                                            <div class="btn-wrapper rejected_interview_location_{{ $proposal_details->id }}">
                                                @if($proposal_details->is_rejected == 1)
                                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray">{{ __('Rejected') }}</a>
                                                @else
                                                    <a href="javascript:void(0)"
                                                       class="btn-profile btn-bg-1 click-interview take_freelancer_interview"
                                                       data-job-id="{{ $proposal_details->job?->id }}"
                                                       data-proposal-id="{{ $proposal_details->id }}"
                                                       data-freelancer-id="{{ $proposal_details->freelancer_id }}"
                                                       data-job-title="{{ $proposal_details->job?->title }}"
                                                       data-job-level="{{ $proposal_details->job?->level }}"
                                                       data-job-type="{{ $proposal_details->job?->type }}"
                                                       data-job-create-date="{{ $proposal_details->job?->created_at }}"
                                                    >
                                                        @if ($proposal_details->is_interview_take == 1) {{ __('Interviewed') }} @else {{ __('Take Interview') }} @endif
                                                    </a>
                                                    @if($proposal_details->is_hired == 0 && $proposal_details?->job->type != 'hourly')
                                                        <a href="javascript:void(0)" class="btn-profile btn-outline-gray reject_proposal" data-proposal-id="{{ $proposal_details->id }}">{{ __('Reject') }}</a>
                                                        <a href="javascript:void(0)"
                                                           class="btn-profile btn-outline-gray accept_proposal"
                                                           data-job-id-for-order="{{ $proposal_details->job_id }}"
                                                           data-proposal-id-for-order="{{ $proposal_details->id }}"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#paymentGatewayModal">{{ __('Accept') }}</a>
                                                    @endif
                                                    @if(moduleExists('HourlyJob'))
                                                        @if($proposal_details->is_hired == 0 && $proposal_details?->job->type == 'hourly')
                                                            <a href="javascript:void(0)" class="btn-profile btn-outline-gray reject_proposal" data-proposal-id="{{ $proposal_details->id }}">{{ __('Reject') }}</a>

                                                            <a href="javascript:void(0)" class="btn-profile btn-outline-gray accept_hourly_proposal swal_status_change_button">{{ __('Accept') }}</a>
                                                            <form method='post' action='{{ route('order.user.confirm') }}' class="d-none">
                                                                <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                                                <input type='hidden' name='job_id_for_order' value="{{  $proposal_details->job_id }}">
                                                                <input type='hidden' name='proposal_id_for_order' value="{{  $proposal_details->id }}">
                                                                <input type="hidden" name="offer_id_for_order" id="offer_id_for_order">
                                                                <input type="hidden" name="job_type_for_order" id="job_type_for_order">
                                                                <button type="submit" class="swal_form_submit_btn d-none"></button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="myJob-wrapper-single">
                                    <div class="myJob-wrapper-single-header profile-border-bottom">
                                        <h2 class="myJob-wrapper-single-title">{{ __('Cover Letter') }}</h2>
                                    </div>
                                    <div class="myJob-wrapper-single-contents">
                                        <div class="myJob-wrapper-single-contents-item">
                                            <p class="myJob-wrapper-single-contents-para cover_letter_details">{{ $proposal_details->cover_letter ?? '' }} </p>
                                        </div>
                                    </div>
                                </div>
                                @if($proposal_details->attachment)
                                    <div class="myJob-wrapper-single">
                                        <div class="myJob-wrapper-single-header profile-border-bottom">
                                            <h2 class="myJob-wrapper-single-title">{{ __('Attachments') }}</h2>
                                        </div>
                                        <div class="myJob-wrapper-single-contents">
                                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                <a href="{{ render_frontend_cloud_image_if_module_exists('jobs/proposal/'.$proposal_details->attachment, load_from: $proposal_details->load_from) }}"
                                                   download
                                                   class="single-refundRequest-item-uploads">
                                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                                    {{ __('Download Attachment') }}
                                                </a>
                                            @else
                                                <a href="{{ asset('assets/uploads/jobs/proposal/'.$proposal_details->attachment) }}" download class="single-refundRequest-item-uploads"><i class="fa-solid fa-cloud-arrow-down"></i> {{ __('Download Attachment')  }}</a>
                                           @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Send Offer Modal area starts -->
        <div class="popup-overlay"></div>
        <div class="popup-fixed interview-popup">
            <div class="popup-contents">
                <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
                <h2 class="popup-contents-title">{{ __('Take Interview') }}</h2>
                <div class="popup-contents-interview profile-border-top">
                    <div class="myJob-wrapper-single-contents">
                        <span class="myJob-wrapper-single-id">#000{{ $proposal_details->job?->id }} </span>
                        <h4 class="myJob-wrapper-single-title mt-3"><a href="javascript:void(0)">{{ $proposal_details->job?->title }}</a></h4>
                        <div class="myJob-wrapper-single-list mt-3">
                            <span class="myJob-wrapper-single-list-para">{{ $proposal_details->job?->created_at->diffForHumans() }} - <a href="javascript:void(0)">{{ ucfirst($proposal_details->job?->level) }} </a></span>
                        </div>
                    </div>
                </div>
                <div class="popup-contents-btn flex-between profile-border-top">
                    <div class="popup-contents-interview-form custom-form w-100">
                        <form action="{{ route('client.message.send') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="freelancer_id" id="freelancer_id">
                            <input type="hidden" name="from_user" id="from_user" value="{{ $proposal_details?->job?->user_id }}">
                            <input type="hidden" name="job_id" id="job_id" value="{{ $proposal_details?->job?->id }}">
                            <input type="hidden" name="type" id="type" value="job">
                            <input type="hidden" name="proposal_id" id="proposal_id_for_check_interview" value="job">
                            <div class="form-group mb-4 mt-0">
                                <label for="messages" class="label-title">{{ __('Write a Message') }}</label>
                                <textarea name="interview_message" id="interview_message" cols="30" rows="2" class="form-message form-control" placeholder="{{ __('E.g.I would you like to invite yo...') }}"></textarea>
                            </div>
                            <div class="btn-wrapper flex-btn gap-2 mt-3">
                                <div class="btn-wrapper">
                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> {{ __('Cancel') }} </a>
                                </div>
                                <button type="submit" class="btn-profile btn-bg-1"><i class="fa-regular fa-comments"></i> {{ __('Send Message') }}</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- Send Offer Modal area ends -->

        <!-- update rate and hours -->
        <div class="modal fade" id="RateAndHoursModal" tabindex="-1" aria-labelledby="RateAndHoursModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('client.job.hourly.rate') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="RateAndHoursModalLabel"> {{ __('Hourly Rate & Estimated Hours') }} </h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="job_id" value="{{ $proposal_details?->job?->id }}">
                            <div class="single-input">
                                <label class="label-title mb-2">{{ __('Hourly Rate') }}</label>
                                <input name="hourly_rate" class="form-control" value="{{ $proposal_details?->job?->hourly_rate }}">
                            </div>
                            <div class="single-input mt-2">
                                <label class="label-title mb-2">{{ __('Estimated Hours') }}</label>
                                <input name="estimated_hour" class="form-control" value="{{ $proposal_details?->job?->estimated_hours }}">
                            </div>
                        </div>
                        <div class="modal-footer flex-column">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="submit" class="btn-profile btn-bg-1">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('frontend.user.client.job.modal.payment-gateway-modal')

    </main>
@endsection

@section('script')
    <x-frontend.payment-gateway.gateway-select-js />
    <x-summernote.summernote-js/>
    <x-select2.select2-js/>
    <x-sweet-alert.sweet-alert2-js/>
    @include('frontend.user.client.job.job-details.proposal-js')
@endsection
