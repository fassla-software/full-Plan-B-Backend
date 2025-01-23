@extends('frontend.layout.master')
@section('site_title')
    {{ $job_details->title ?? __('Job Details') }}
@endsection
@if(isset($job_details->meta_title) && !empty($job_details->meta_title))
    @section('meta_title', $job_details->meta_title)
@endif

@if(isset($job_details->meta_description) && !empty($job_details->meta_description))
    @section('meta_description', $job_details->meta_description)
@endif
@section('style')
    <x-summernote.summernote-css />
@endsection
@section('content')

    <main>
        <x-frontend.category.category />
        <x-breadcrumb.user-profile-breadcrumb :title="__('Job Details')" :innerTitle="__('Job Details')" />
        <!-- jobFilter area Starts -->
        <div class="responsive-overlay-lg"></div>
        <div class="responsive-overlay"></div>
        <div class="jobFilter-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="jobFilter-wrapper sticky_top">
                            <div class="jobFilter-wrapper-item jobDetails-padding">
                                <div class="jobFilter-wrapper-item-inner">
                                    <div class="jobFilter-wrapper-item-top">
                                        <div class="jobFilter-wrapper-item-top-left">
                                            <h1 class="jobFilter-wrapper-item-title">{{ $job_details->title }}</h1>
                                            <p class="single-jobs-date">
                                                {{ $job_details->created_at->toFormattedDateString() ?? '' }} -
                                                <span>{{ ucfirst(__($job_details->level)) ?? '' }}</span>
                                            </p>
                                        </div>
                                        <div class="jobFilter-wrapper-item-top-right">
                                            <div class="jobFilter-wrapper-item-top-right-image jobbookmark">
                                                <x-frontend.bookmark :identity="$job_details->id" :type="'job'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jobFilter-wrapper-item-contents">
                                        <div class="obFilter-wrapper-item-contents-flex flex-between">
                                            @if($job_details->type == 'hourly')
                                                <h3 class="single-jobs-price">
                                                    {{ float_amount_with_currency_symbol($job_details->hourly_rate) }} <span
                                                            class="single-jobs-price-fixed">{{ ucfirst($job_details->type) }}</span>
                                                </h3>
                                                @else
                                                <h3 class="single-jobs-price">
                                                    {{ float_amount_with_currency_symbol($job_details->budget) }} <span
                                                        class="single-jobs-price-fixed">{{ ucfirst($job_details->type) }}</span>
                                                </h3>
                                                @endif
                                        </div>
                                        <div class="single-jobs-para mt-4">{!! $job_details->description !!}</div>
                                        <div class="single-jobs-tag mt-4">
                                            @foreach ($job_details->job_skills as $skill)
                                                <a href="{{ route('skill.jobs', $skill->skill) }}"
                                                    class="single-jobs-tag-link">{{ $skill->skill ?? '' }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if (Auth::guard('web')->check())
                                        @if ($job_details->attachment)
                                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                <a href="{{ render_frontend_cloud_image_if_module_exists('jobs/'.$job_details->attachment, load_from: $job_details->load_from) }}"
                                                   download class="single-refundRequest-item-uploads">
                                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                                    {{ __('Download Attachment') }}
                                                </a>
                                            @else
                                                @if(!empty($job_details->attachment) && file_exists(base_path('../assets/uploads/jobs/' . $job_details->attachment)))
                                                <a href="{{ asset('assets/uploads/jobs/' . $job_details->attachment) }}"
                                                   download class="single-refundRequest-item-uploads">
                                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                                    {{ __('Download Attachment') }}
                                                </a>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                <div class="jobFilter-wrapper-item-bottom">
                                    <ul class="jobFilter-wrapper-item-bottom-list">
                                        <li class="jobFilter-wrapper-item-bottom-list-item">
                                            <span class="item-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.99844 11.8084C8.22344 11.8084 6.77344 10.3667 6.77344 8.58337C6.77344 6.80003 8.22344 5.3667 9.99844 5.3667C11.7734 5.3667 13.2234 6.80837 13.2234 8.5917C13.2234 10.375 11.7734 11.8084 9.99844 11.8084ZM9.99844 6.6167C8.9151 6.6167 8.02344 7.50003 8.02344 8.5917C8.02344 9.68337 8.90677 10.5667 9.99844 10.5667C11.0901 10.5667 11.9734 9.68337 11.9734 8.5917C11.9734 7.50003 11.0818 6.6167 9.99844 6.6167Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M10.0014 18.9667C8.76803 18.9667 7.52637 18.5001 6.5597 17.5751C4.10137 15.2084 1.3847 11.4334 2.4097 6.94175C3.3347 2.86675 6.89303 1.04175 10.0014 1.04175C10.0014 1.04175 10.0014 1.04175 10.0097 1.04175C13.118 1.04175 16.6764 2.86675 17.6014 6.95008C18.618 11.4417 15.9014 15.2084 13.443 17.5751C12.4764 18.5001 11.2347 18.9667 10.0014 18.9667ZM10.0014 2.29175C7.57637 2.29175 4.4597 3.58341 3.6347 7.21675C2.7347 11.1417 5.20137 14.5251 7.4347 16.6667C8.87637 18.0584 11.1347 18.0584 12.5764 16.6667C14.8014 14.5251 17.268 11.1417 16.3847 7.21675C15.5514 3.58341 12.4264 2.29175 10.0014 2.29175Z"
                                                        fill="#475467" />
                                                </svg>

                                            </span>
                                            <span
                                                class="item-para">{{ $job_details->job_creator?->user_country?->country }}</span>
                                        </li>
                                        <li class="jobFilter-wrapper-item-bottom-list-item"><span class="item-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M14.6836 8.0166H10.3086C9.96693 8.0166 9.68359 7.73327 9.68359 7.3916C9.68359 7.04993 9.96693 6.7666 10.3086 6.7666H14.6836C15.0253 6.7666 15.3086 7.04993 15.3086 7.3916C15.3086 7.73327 15.0336 8.0166 14.6836 8.0166Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M5.93151 8.65002C5.77318 8.65002 5.61484 8.59168 5.48984 8.46668L4.86484 7.84168C4.62318 7.60002 4.62318 7.20002 4.86484 6.95835C5.10651 6.71668 5.50651 6.71668 5.74818 6.95835L5.93151 7.14168L7.36484 5.70835C7.60651 5.46668 8.00651 5.46668 8.24818 5.70835C8.48984 5.95002 8.48984 6.35002 8.24818 6.59168L6.37318 8.46668C6.25651 8.58335 6.09818 8.65002 5.93151 8.65002Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M14.6836 13.8501H10.3086C9.96693 13.8501 9.68359 13.5668 9.68359 13.2251C9.68359 12.8834 9.96693 12.6001 10.3086 12.6001H14.6836C15.0253 12.6001 15.3086 12.8834 15.3086 13.2251C15.3086 13.5668 15.0336 13.8501 14.6836 13.8501Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M5.93151 14.4833C5.77318 14.4833 5.61484 14.4249 5.48984 14.2999L4.86484 13.6749C4.62318 13.4333 4.62318 13.0333 4.86484 12.7916C5.10651 12.5499 5.50651 12.5499 5.74818 12.7916L5.93151 12.9749L7.36484 11.5416C7.60651 11.2999 8.00651 11.2999 8.24818 11.5416C8.48984 11.7833 8.48984 12.1833 8.24818 12.4249L6.37318 14.2999C6.25651 14.4166 6.09818 14.4833 5.93151 14.4833Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M12.5013 18.9584H7.5013C2.9763 18.9584 1.04297 17.0251 1.04297 12.5001V7.50008C1.04297 2.97508 2.9763 1.04175 7.5013 1.04175H12.5013C17.0263 1.04175 18.9596 2.97508 18.9596 7.50008V12.5001C18.9596 17.0251 17.0263 18.9584 12.5013 18.9584ZM7.5013 2.29175C3.65964 2.29175 2.29297 3.65841 2.29297 7.50008V12.5001C2.29297 16.3417 3.65964 17.7084 7.5013 17.7084H12.5013C16.343 17.7084 17.7096 16.3417 17.7096 12.5001V7.50008C17.7096 3.65841 16.343 2.29175 12.5013 2.29175H7.5013Z"
                                                        fill="#475467" />
                                                </svg>
                                            </span> <span class="item-para">{{ __('Proposal:') }}
                                                {{ $job_details->job_proposals?->count() }}
                                            </span>
                                        </li>
                                        @if(moduleExists('HourlyJob'))
                                        <li class="jobFilter-wrapper-item-bottom-list-item">
                                            <span class="item-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.2513 7.70825H1.66797C1.3263 7.70825 1.04297 7.42492 1.04297 7.08325C1.04297 6.74159 1.3263 6.45825 1.66797 6.45825H11.2513C11.593 6.45825 11.8763 6.74159 11.8763 7.08325C11.8763 7.42492 11.593 7.70825 11.2513 7.70825Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M6.66667 14.375H5C4.65833 14.375 4.375 14.0917 4.375 13.75C4.375 13.4083 4.65833 13.125 5 13.125H6.66667C7.00833 13.125 7.29167 13.4083 7.29167 13.75C7.29167 14.0917 7.00833 14.375 6.66667 14.375Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M12.0833 14.375H8.75C8.40833 14.375 8.125 14.0917 8.125 13.75C8.125 13.4083 8.40833 13.125 8.75 13.125H12.0833C12.425 13.125 12.7083 13.4083 12.7083 13.75C12.7083 14.0917 12.425 14.375 12.0833 14.375Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M14.6346 17.7084H5.36797C2.0513 17.7084 1.04297 16.7084 1.04297 13.4251V6.57508C1.04297 3.29175 2.0513 2.29175 5.36797 2.29175H11.2513C11.593 2.29175 11.8763 2.57508 11.8763 2.91675C11.8763 3.25841 11.593 3.54175 11.2513 3.54175H5.36797C2.7513 3.54175 2.29297 3.99175 2.29297 6.57508V13.4167C2.29297 16.0001 2.7513 16.4501 5.36797 16.4501H14.6263C17.243 16.4501 17.7013 16.0001 17.7013 13.4167V10.0167C17.7013 9.67508 17.9846 9.39175 18.3263 9.39175C18.668 9.39175 18.9513 9.67508 18.9513 10.0167V13.4167C18.9596 16.7084 17.9513 17.7084 14.6346 17.7084Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M14.4237 7.45003C14.2654 7.45003 14.107 7.3917 13.982 7.2667C13.7404 7.02503 13.7404 6.62503 13.982 6.38337L17.2237 3.1417C17.4654 2.90003 17.8654 2.90003 18.107 3.1417C18.3487 3.38337 18.3487 3.78337 18.107 4.02503L14.8654 7.2667C14.7404 7.3917 14.582 7.45003 14.4237 7.45003Z"
                                                        fill="#475467" />
                                                    <path
                                                        d="M17.6576 7.45003C17.4992 7.45003 17.3409 7.3917 17.2159 7.2667L13.9742 4.02503C13.7326 3.78337 13.7326 3.38337 13.9742 3.1417C14.2159 2.90003 14.6159 2.90003 14.8576 3.1417L18.0992 6.38337C18.3409 6.62503 18.3409 7.02503 18.0992 7.2667C17.9826 7.3917 17.8242 7.45003 17.6576 7.45003Z"
                                                        fill="#475467" />
                                                </svg>
                                            </span>
                                            @if($job_details->job_creator?->user_wallet?->balance >= ($job_details->hourly_rate * $job_details->estimated_hours) )
                                                <span class="item-para">{{  __('Verified') }}</span>
                                            @else
                                                <span class="item-para">{{ __('Not Verified') }}</span>
                                            @endif
                                        </li>
                                        @endif
                                        <li class="jobFilter-wrapper-item-bottom-list-item">
                                            <span class="item-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M15.7106 15.93C15.5806 15.93 15.4506 15.9 15.3306 15.82L12.2306 13.97C11.4606 13.51 10.8906 12.5 10.8906 11.61V7.50999C10.8906 7.09999 11.2306 6.75999 11.6406 6.75999C12.0506 6.75999 12.3906 7.09999 12.3906 7.50999V11.61C12.3906 11.97 12.6906 12.5 13.0006 12.68L16.1006 14.53C16.4606 14.74 16.5706 15.2 16.3606 15.56C16.2106 15.8 15.9606 15.93 15.7106 15.93Z"
                                                        fill="#667085" />
                                                </svg>
                                            </span>
                                            <span class="item-para">{{ ucfirst(__($job_details->duration)) ?? '' }}</span>
                                        </li>
                                        @if($job_details->type == 'hourly')
                                            <li class="jobFilter-wrapper-item-bottom-list-item">
                                            <span class="item-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                            d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z"
                                                            fill="#667085" />
                                                    <path
                                                            d="M15.7106 15.93C15.5806 15.93 15.4506 15.9 15.3306 15.82L12.2306 13.97C11.4606 13.51 10.8906 12.5 10.8906 11.61V7.50999C10.8906 7.09999 11.2306 6.75999 11.6406 6.75999C12.0506 6.75999 12.3906 7.09999 12.3906 7.50999V11.61C12.3906 11.97 12.6906 12.5 13.0006 12.68L16.1006 14.53C16.4606 14.74 16.5706 15.2 16.3606 15.56C16.2106 15.8 15.9606 15.93 15.7106 15.93Z"
                                                            fill="#667085" />
                                                </svg>
                                            </span>
                                                <span class="item-para">{{ __('Estimated Hours:') }} {{ $job_details->estimated_hours ?? '' }}</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            @if (Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Session::get('user_role') != 'client' && auth()->user()->id != $job_details->user_id)
                                @php
                                    $proposal_count = \App\Models\JobProposal::where('job_id', $job_details->id)
                                        ->where('freelancer_id', auth()->user()->id)
                                        ->count();
                                @endphp
                                @if ($proposal_count < 1)
                                    <div class="jobFilter-wrapper-item">
                                        <div class="jobFilter-wrapper-item-header profile-border-bottom">
                                            <h4 class="profile-wrapper-item-title"> {{ __('Submit Proposal') }} </h4>
                                        </div>
                                        <div class="jobFilter-wrapper-item-form custom-form">
                                            <x-validation.error />
                                            <form action="{{ route('job.proposal.send') }}" method="post"
                                                enctype="multipart/form-data" id="job_proposal_form">
                                                @csrf
                                                <input type="hidden" name="job_id" value="{{ $job_details->id }}">
                                                <input type="hidden" name="client_id" value="{{ $job_details->user_id }}">
                                                <div class="single-flex-input">
                                                    @if(moduleExists('HourlyJob'))
                                                        @if($job_details->type == 'hourly')
                                                        <div class="single-input">
                                                            <label class="label-title"> {{ __('Hourly rate') }} </label>
                                                            <div class="single-input-icon">
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form--control" value="{{ $job_details->hourly_rate ?? '' }}">
                                                                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                        @else
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __('Proposal amount') }} </label>
                                                                <div class="single-input-icon">
                                                                    <input type="number" name="amount" id="amount"
                                                                           class="form--control" value="{{ $job_details->budget }}">
                                                                    <span class="input-icon">{{ get_static_option('site_global_currency') }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="single-input">
                                                            <label class="label-title"> {{ __('Proposal amount') }} </label>
                                                            <div class="single-input-icon">
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form--control" value="{{ $job_details->budget }}">
                                                                <span class="input-icon">{{ get_static_option('site_global_currency') }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <x-duration.delivery-time :class="'single-input'" :title="__('Delivery Time')"
                                                        :name="'duration'" :id="'duration'" />
                                                </div>

                                                <div class="single-input">
                                                    <label class="label-title"> {{ __('Revision') }} </label>
                                                    <input type="number" name="revision" id="revision"
                                                        class="form--control"
                                                        placeholder="{{ __('Job Revision Must be Number') }}" onkeypress="inpNum(event)">
                                                </div>

                                                <div class="single-input">
                                                    <label class="label-title"> {{ __('Your cover letter') }} </label>
                                                    <textarea name="cover_letter" id="cover_letter" class="form-message" rows="3"
                                                        placeholder="{{ __('Write your cover letter minimum 10 characters...') }}"></textarea>
                                                </div>

                                                <div class="photo-uploaded photo-uploaded-padding center-text mt-4">
                                                    <input type="file" name="attachment" id="attachment">
                                                </div>

                                                <div
                                                    class="jobFilter-wrapper-item-btn flex-btn justify-content-between profile-border-top">
                                                    <div class="jobFilter-wrapper-item-btn-inner"></div>
                                                    <div class="jobFilter-wrapper-item-btn flex-btn justify-content-end">
                                                        <button type="submit"
                                                            class="btn-profile btn-bg-1 send_job_proposal">{{ __('Send Proposal') }} <span id="send_proposal_load_spinner"></span></button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{--client as a freelancer--}}
                            @if (Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Session::get('user_role') == 'freelancer')
                                @php
                                    $proposal_count = \App\Models\JobProposal::where('job_id', $job_details->id)
                                        ->where('freelancer_id', auth()->user()->id)
                                        ->count();
                                @endphp
                                @if ($proposal_count < 1)
                                    <div class="jobFilter-wrapper-item">
                                        <div class="jobFilter-wrapper-item-header profile-border-bottom">
                                            <h4 class="profile-wrapper-item-title"> {{ __('Submit Proposal') }} </h4>
                                        </div>
                                        <div class="jobFilter-wrapper-item-form custom-form">
                                            <x-validation.error />
                                            <form action="{{ route('job.proposal.send') }}" method="post"
                                                  enctype="multipart/form-data" id="job_proposal_form">
                                                @csrf
                                                <input type="hidden" name="job_id" value="{{ $job_details->id }}">
                                                <input type="hidden" name="client_id" value="{{ $job_details->user_id }}">
                                                <div class="single-flex-input">
                                                    @if(moduleExists('HourlyJob'))
                                                        @if($job_details->type == 'hourly')
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __('Hourly rate') }} </label>
                                                                <div class="single-input-icon">
                                                                    <input type="number" name="amount" id="amount"
                                                                           class="form--control" value="{{ $job_details->hourly_rate ?? '' }}">
                                                                    <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __('Proposal amount') }} </label>
                                                                <div class="single-input-icon">
                                                                    <input type="number" name="amount" id="amount"
                                                                           class="form--control" value="{{ $job_details->budget }}">
                                                                    <span class="input-icon">{{ get_static_option('site_global_currency') }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="single-input">
                                                            <label class="label-title"> {{ __('Proposal amount') }} </label>
                                                            <div class="single-input-icon">
                                                                <input type="number" name="amount" id="amount"
                                                                       class="form--control" value="{{ $job_details->budget }}">
                                                                <span class="input-icon">{{ get_static_option('site_global_currency') }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <x-duration.delivery-time :class="'single-input'" :title="__('Delivery Time')"
                                                                              :name="'duration'" :id="'duration'" />
                                                </div>

                                                <div class="single-input">
                                                    <label class="label-title"> {{ __('Revision') }} </label>
                                                    <input type="number" name="revision" id="revision"
                                                           class="form--control"
                                                           placeholder="{{ __('Job Revision Must be Number') }}" onkeypress="inpNum(event)">
                                                </div>

                                                <div class="single-input">
                                                    <label class="label-title"> {{ __('Your cover letter') }} </label>
                                                    <textarea name="cover_letter" id="cover_letter" class="form-message" rows="3"
                                                              placeholder="{{ __('Write your cover letter minimum 10 characters...') }}"></textarea>
                                                </div>

                                                <div class="photo-uploaded photo-uploaded-padding center-text mt-4">
                                                    <input type="file" name="attachment" id="attachment">
                                                </div>

                                                <div
                                                        class="jobFilter-wrapper-item-btn flex-btn justify-content-between profile-border-top">
                                                    <div class="jobFilter-wrapper-item-btn-inner"></div>
                                                    <div class="jobFilter-wrapper-item-btn flex-btn justify-content-end">
                                                        <button type="submit"
                                                                class="btn-profile btn-bg-1 send_job_proposal">{{ __('Send Proposal') }} <span id="send_proposal_load_spinner"></span></button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="jobFilter-wrapper sticky_top">

                            {{-- Client Info --}}
                            <div class="jobFilter-wrapper-item">
                                <div class="jobFilter-about-clients">
                                    <div class="jobFilter-about-clients-single flex-between">
                                        <div class="jobFilter-about-clients-flex">
                                            <span class="jobFilter-about-clients-thumb">
                                                @if ($job_details->job_creator?->image)
                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $job_details?->job_creator?->image, load_from: $job_details?->job_creator?->load_from ?? '') }}" alt="{{ __('profile img') }}">
                                                    @else
                                                    <img src="{{ asset('assets/uploads/profile/' . $job_details->job_creator?->image) }}"
                                                        alt="{{ $job_details->job_creator?->fullname }}">
                                                    @endif
                                                @else
                                                    <img src="{{ asset('assets/static/img/author/author.jpg') }}"
                                                        alt="{{ __('AuthorImg') }}">
                                                @endif
                                            </span>
                                            <div class="jobFilter-about-clients-contents">
                                                <h6 class="single-freelancer-author-name">
                                                    {{ $job_details?->job_creator?->fullname }}
                                                    <x-status.user-active-inactive-check :userID="$job_details->job_creator->id" />
                                                </h6>
                                                <span>{{ $job_details?->job_creator?->user_state?->state }} ,
                                                    {{ $job_details?->job_creator?->user_country?->country }}</span>

                                                @if($job_details->job_creator?->user_verified_status == 1)
                                                    <span data-toggle="tooltip" title="{{ __('User Verified') }}"> <i class="fas fa-circle-check"></i> </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="jobFilter-about-clients">
                                    <div class="jobFilter-about-clients-single flex-between">
                                        <div class="jobFilter-about-clients-flex">
                                            <span class="jobFilter-about-clients-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M17 21.75H7C2.59 21.75 1.25 20.41 1.25 16V8C1.25 3.59 2.59 2.25 7 2.25H17C21.41 2.25 22.75 3.59 22.75 8V16C22.75 20.41 21.41 21.75 17 21.75ZM7 3.75C3.42 3.75 2.75 4.43 2.75 8V16C2.75 19.57 3.42 20.25 7 20.25H17C20.58 20.25 21.25 19.57 21.25 16V8C21.25 4.43 20.58 3.75 17 3.75H7Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M19 8.75H14C13.59 8.75 13.25 8.41 13.25 8C13.25 7.59 13.59 7.25 14 7.25H19C19.41 7.25 19.75 7.59 19.75 8C19.75 8.41 19.41 8.75 19 8.75Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M19 12.75H15C14.59 12.75 14.25 12.41 14.25 12C14.25 11.59 14.59 11.25 15 11.25H19C19.41 11.25 19.75 11.59 19.75 12C19.75 12.41 19.41 12.75 19 12.75Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M19 16.75H17C16.59 16.75 16.25 16.41 16.25 16C16.25 15.59 16.59 15.25 17 15.25H19C19.41 15.25 19.75 15.59 19.75 16C19.75 16.41 19.41 16.75 19 16.75Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M8.50141 12.04C7.09141 12.04 5.94141 10.89 5.94141 9.48C5.94141 8.07 7.09141 6.92 8.50141 6.92C9.91141 6.92 11.0614 8.07 11.0614 9.48C11.0614 10.89 9.91141 12.04 8.50141 12.04ZM8.50141 8.42C7.92141 8.42 7.44141 8.9 7.44141 9.48C7.44141 10.06 7.92141 10.54 8.50141 10.54C9.08141 10.54 9.56141 10.06 9.56141 9.48C9.56141 8.9 9.08141 8.42 8.50141 8.42Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M12.0019 17.08C11.6219 17.08 11.2919 16.79 11.2519 16.4C11.1419 15.32 10.2719 14.45 9.18186 14.35C8.72186 14.31 8.26186 14.31 7.80186 14.35C6.71186 14.45 5.84186 15.31 5.73186 16.4C5.69186 16.81 5.32186 17.12 4.91186 17.07C4.50186 17.03 4.20186 16.66 4.24186 16.25C4.42186 14.45 5.85186 13.02 7.66186 12.86C8.21186 12.81 8.77186 12.81 9.32186 12.86C11.1219 13.03 12.5619 14.46 12.7419 16.25C12.7819 16.66 12.4819 17.03 12.0719 17.07C12.0519 17.08 12.0219 17.08 12.0019 17.08Z"
                                                        fill="#667085" />
                                                </svg>
                                            </span>
                                            <span class="jobFilter-about-clients-para"> {{ __('Member since') }} </span>
                                        </div>
                                        <h6 class="jobFilter-about-clients-completed">
                                            {{ $job_details->job_creator?->created_at->toFormattedDateString() ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="jobFilter-about-clients">
                                    <div class="jobFilter-about-clients-single flex-between">
                                        <div class="jobFilter-about-clients-flex">
                                            <span class="jobFilter-about-clients-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M15.9983 22.75H7.99827C3.37827 22.75 2.51827 20.6 2.29827 18.51L1.54827 10.5C1.43827 9.44999 1.40827 7.89999 2.44827 6.73999C3.34827 5.73999 4.83827 5.25999 6.99827 5.25999H16.9983C19.1683 5.25999 20.6583 5.74999 21.5483 6.73999C22.5883 7.89999 22.5583 9.44999 22.4483 10.51L21.6983 18.5C21.4783 20.6 20.6183 22.75 15.9983 22.75ZM6.99827 6.74999C5.30827 6.74999 4.14827 7.07999 3.55827 7.73999C3.06827 8.27999 2.90827 9.10999 3.03827 10.35L3.78827 18.36C3.95827 19.94 4.38827 21.25 7.99827 21.25H15.9983C19.5983 21.25 20.0383 19.94 20.2083 18.35L20.9583 10.36C21.0883 9.10999 20.9283 8.27999 20.4383 7.73999C19.8483 7.07999 18.6883 6.74999 16.9983 6.74999H6.99827Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M16 6.75C15.59 6.75 15.25 6.41 15.25 6V5.2C15.25 3.42 15.25 2.75 12.8 2.75H11.2C8.75 2.75 8.75 3.42 8.75 5.2V6C8.75 6.41 8.41 6.75 8 6.75C7.59 6.75 7.25 6.41 7.25 6V5.2C7.25 3.44 7.25 1.25 11.2 1.25H12.8C16.75 1.25 16.75 3.44 16.75 5.2V6C16.75 6.41 16.41 6.75 16 6.75Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M12 16.75C9.25 16.75 9.25 15.05 9.25 14.03V13C9.25 11.59 9.59 11.25 11 11.25H13C14.41 11.25 14.75 11.59 14.75 13V14C14.75 15.04 14.75 16.75 12 16.75ZM10.75 12.75C10.75 12.83 10.75 12.92 10.75 13V14.03C10.75 15.06 10.75 15.25 12 15.25C13.25 15.25 13.25 15.09 13.25 14.02V13C13.25 12.92 13.25 12.83 13.25 12.75C13.17 12.75 13.08 12.75 13 12.75H11C10.92 12.75 10.83 12.75 10.75 12.75Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M13.9995 14.77C13.6295 14.77 13.2995 14.49 13.2595 14.11C13.2095 13.7 13.4995 13.32 13.9095 13.27C16.5495 12.94 19.0795 11.94 21.2095 10.39C21.5395 10.14 22.0095 10.22 22.2595 10.56C22.4995 10.89 22.4295 11.36 22.0895 11.61C19.7495 13.31 16.9895 14.4 14.0895 14.77C14.0595 14.77 14.0295 14.77 13.9995 14.77Z"
                                                        fill="#667085" />
                                                    <path
                                                        d="M10.0007 14.78C9.97072 14.78 9.94072 14.78 9.91072 14.78C7.17072 14.47 4.50072 13.47 2.19072 11.89C1.85072 11.66 1.76072 11.19 1.99072 10.85C2.22072 10.51 2.69072 10.42 3.03072 10.65C5.14072 12.09 7.57072 13 10.0707 13.29C10.4807 13.34 10.7807 13.71 10.7307 14.12C10.7007 14.5 10.3807 14.78 10.0007 14.78Z"
                                                        fill="#667085" />
                                                </svg>
                                            </span>
                                            <span class="jobFilter-about-clients-para">{{ __('Total Job') }}</span>
                                        </div>
                                        <h6 class="jobFilter-about-clients-completed">{{ $user->user_jobs?->count() }}
                                        </h6>
                                    </div>
                                </div>

                                @php
                                    $total_job = App\Models\JobPost::where('user_id', $job_details->user_id)->count();
                                    $total_order = App\Models\Order::where('user_id', $job_details->user_id)
                                        ->where('status', 3)
                                        ->count();

                                    $hiring_rate = '';
                                    if($hiring_rate > 0){
                                        $hiring_rate = ($total_order * 100) / $total_job;
                                    }
                                @endphp

                                @if ($hiring_rate >= 1)
                                    <div class="jobFilter-about-clients">
                                        <div class="jobFilter-about-clients-single flex-between">
                                            <div class="jobFilter-about-clients-flex">
                                                <span class="jobFilter-about-clients-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15 22.75H9C3.57 22.75 1.25 20.43 1.25 15V9C1.25 3.57 3.57 1.25 9 1.25H15C20.43 1.25 22.75 3.57 22.75 9V15C22.75 20.43 20.43 22.75 15 22.75ZM9 2.75C4.39 2.75 2.75 4.39 2.75 9V15C2.75 19.61 4.39 21.25 9 21.25H15C19.61 21.25 21.25 19.61 21.25 15V9C21.25 4.39 19.61 2.75 15 2.75H9Z"
                                                            fill="#667085" />
                                                        <path
                                                            d="M8.56781 16.02C8.37781 16.02 8.18781 15.95 8.03781 15.8C7.74781 15.51 7.74781 15.03 8.03781 14.74L14.5878 8.19003C14.8778 7.90003 15.3578 7.90003 15.6478 8.19003C15.9378 8.48003 15.9378 8.96003 15.6478 9.25003L9.09781 15.8C8.94781 15.95 8.75781 16.02 8.56781 16.02Z"
                                                            fill="#667085" />
                                                        <path
                                                            d="M8.98001 11.11C7.89001 11.11 7 10.22 7 9.13004C7 8.04004 7.89001 7.15002 8.98001 7.15002C10.07 7.15002 10.96 8.04004 10.96 9.13004C10.96 10.22 10.07 11.11 8.98001 11.11ZM8.98001 8.66003C8.72001 8.66003 8.5 8.87005 8.5 9.14005C8.5 9.41005 8.71001 9.62003 8.98001 9.62003C9.25001 9.62003 9.45999 9.41005 9.45999 9.14005C9.45999 8.87005 9.24001 8.66003 8.98001 8.66003Z"
                                                            fill="#667085" />
                                                        <path
                                                            d="M15.519 16.84C14.429 16.84 13.5391 15.95 13.5391 14.86C13.5391 13.77 14.429 12.88 15.519 12.88C16.609 12.88 17.4991 13.77 17.4991 14.86C17.4991 15.95 16.609 16.84 15.519 16.84ZM15.519 14.39C15.259 14.39 15.0391 14.6 15.0391 14.87C15.0391 15.14 15.249 15.35 15.519 15.35C15.789 15.35 15.9991 15.14 15.9991 14.87C15.9991 14.6 15.789 14.39 15.519 14.39Z"
                                                            fill="#667085" />
                                                    </svg>
                                                </span>
                                                <span class="jobFilter-about-clients-para">{{ __('Hire rate') }}</span>
                                            </div>
                                            <h6 class="jobFilter-about-clients-completed">{{ round($hiring_rate) ?? 0 }}%
                                            </h6>
                                        </div>
                                    </div>
                                @endif
                                @if ($job_details->last_seen != null)
                                    <div class="jobFilter-about-clients">
                                        <div class="jobFilter-about-clients-single flex-between">
                                            <div class="jobFilter-about-clients-flex">
                                                <span class="jobFilter-about-clients-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.0019 16.33C9.61187 16.33 7.67188 14.39 7.67188 12C7.67188 9.61001 9.61187 7.67001 12.0019 7.67001C14.3919 7.67001 16.3319 9.61001 16.3319 12C16.3319 14.39 14.3919 16.33 12.0019 16.33ZM12.0019 9.17001C10.4419 9.17001 9.17188 10.44 9.17188 12C9.17188 13.56 10.4419 14.83 12.0019 14.83C13.5619 14.83 14.8319 13.56 14.8319 12C14.8319 10.44 13.5619 9.17001 12.0019 9.17001Z"
                                                            fill="#667085" />
                                                        <path
                                                            d="M11.9981 21.02C8.23812 21.02 4.68813 18.82 2.24812 15C1.18813 13.35 1.18813 10.66 2.24812 9.00001C4.69812 5.18001 8.24813 2.98001 11.9981 2.98001C15.7481 2.98001 19.2981 5.18001 21.7381 9.00001C22.7981 10.65 22.7981 13.34 21.7381 15C19.2981 18.82 15.7481 21.02 11.9981 21.02ZM11.9981 4.48001C8.76813 4.48001 5.67813 6.42001 3.51813 9.81001C2.76813 10.98 2.76813 13.02 3.51813 14.19C5.67813 17.58 8.76813 19.52 11.9981 19.52C15.2281 19.52 18.3181 17.58 20.4781 14.19C21.2281 13.02 21.2281 10.98 20.4781 9.81001C18.3181 6.42001 15.2281 4.48001 11.9981 4.48001Z"
                                                            fill="#667085" />
                                                    </svg>
                                                </span>
                                                <span class="jobFilter-about-clients-para">{{ __('Last seen') }}</span>
                                            </div>
                                            <h6 class="jobFilter-about-clients-completed">
                                                {{ \Carbon\Carbon::parse($job_details->last_seen)?->diffForHumans() }}
                                            </h6>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if (Auth::guard('web')->check() && Auth::guard('web')->user()->id == $job_details->user_id)
                                {{-- // activity--}}
                                @if ($job_details?->job_proposals->count() >= 1)
                                    <div class="jobFilter-wrapper-item">
                                        <div class="jobFilter-wrapper-item-header profile-border-bottom">
                                            <h4 class="profile-wrapper-item-title"> {{ __('Activities') }} </h4>
                                        </div>
                                        <div class="jobFilter-activities">
                                            <ul class="jobFilter-activities-list">
                                                @php
                                                    $activity_count = 0;
                                                @endphp
                                                @foreach ($job_details?->job_proposals as $proposal)
                                                    @if ($proposal->is_interview_take == 1)
                                                        @php
                                                            $activity_count++;
                                                        @endphp
                                                        <li class="jobFilter-activities-list-item">
                                                            <h6 class="jobFilter-activities-list-title">
                                                                {{ __('Interviewed') }}
                                                                <strong>{{ $proposal?->freelancer?->fullname }}</strong>
                                                            </h6>
                                                            <p class="jobFilter-activities-list-para">
                                                                {{ $proposal->updated_at?->toFormattedDateString() }}</p>
                                                        </li>
                                                    @endif
                                                    @if ($proposal?->is_interview_take == 0 && $proposal?->is_short_listed == 1)
                                                        @php
                                                            $activity_count++;
                                                        @endphp
                                                        <li class="jobFilter-activities-list-item">
                                                            <h6 class="jobFilter-activities-list-title">
                                                                {{ __('Shortlisted') }}
                                                                <strong>{{ $proposal?->freelancer?->fullname }}</strong>
                                                            </h6>
                                                            <p class="jobFilter-activities-list-para">
                                                                {{ $proposal->updated_at?->toFormattedDateString() }}</p>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                @if($activity_count === 0)
                                                    <h6 class="jobFilter-activities-list-title">
                                                        {{ __('No Activities') }}
                                                    </h6>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                {{-- // proposal --}}
                                @if ($job_details->job_proposals->count() >= 1)
                                    <div class="jobFilter-wrapper-item proposal-padding">
                                        <div class="jobFilter-wrapper-item-header profile-border-bottom">
                                            <h4 class="profile-wrapper-item-title"> {{ __('All Proposals') }} </h4>
                                        </div>
                                        @foreach ($job_details->job_proposals as $proposal)
                                            @if($proposal?->freelancer)
                                            <div class="jobFilter-proposal">
                                                <div class="jobFilter-proposal-author">
                                                    <div class="jobFilter-proposal-author-flex">
                                                        <div class="jobFilter-proposal-author-thumb">
                                                            @if ($proposal->freelancer?->image)
                                                                <img src="{{ asset('assets/uploads/profile/' . $proposal->freelancer?->image) }}"
                                                                    alt="{{ $proposal->freelancer?->fullname }}">
                                                            @else
                                                                <img src="{{ asset('assets/static/img/author/author.jpg') }}"
                                                                    alt="{{ __('AuthorImg') }}">
                                                            @endif
                                                        </div>
                                                        <div class="jobFilter-proposal-author-contents">
                                                            <h4 class="jobFilter-proposal-author-contents-title">
                                                                {{ $proposal->freelancer?->fullname ?? '' }}
                                                                <x-status.user-active-inactive-check :userID="$proposal->freelancer->id" />
                                                            </h4>
                                                            <p class="jobFilter-proposal-author-contents-subtitle mt-2">
                                                                {{ $proposal->freelancer?->user_introduction?->title ?? '' }}
                                                                · <span>{{ $proposal->freelancer?->user_state?->state ?? '' }},
                                                                    {{ $proposal->freelancer?->user_country?->country ?? '' }}</span>
                                                            </p>
                                                            <div class="jobFilter-proposal-author-contents-review mt-2">
                                                                {!! freelancer_rating($proposal->freelancer_id) !!}
                                                            </div>
                                                            <p class="jobFilter-proposal-author-contents-time mt-2">
                                                                {{ $proposal->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="jobFilter-proposal-offered profile-border-top">
                                                    <div class="jobFilter-proposal-offered-single">
                                                        <span class="offered">{{ __('Offered') }} <span
                                                                class="offered-price">{{ float_amount_with_currency_symbol($proposal->amount) }}</span>
                                                        </span>
                                                    </div>
                                                    <div class="jobFilter-proposal-offered-single">
                                                        <span class="offered">{{ __('Est. delivery duration') }} <span
                                                                class="offered-days">{{ $proposal->duration }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <br>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jobFilter area end -->

    </main>
@endsection

@section('script')
    <script>
        function inpNum(e) {
            e = e || window.event;
            let charCode = (typeof e.which == "undefined") ? e.keyCode : e.which;
            let charStr = String.fromCharCode(charCode);
            if (!charStr.match(/^[0-9]+$/)){
                toastr_warning_js("{{ __('Job revision must be a number.') }}")
                e.preventDefault();
            }
        }
    </script>
    @include('frontend.pages.job-details.job-details-js')
@endsection
