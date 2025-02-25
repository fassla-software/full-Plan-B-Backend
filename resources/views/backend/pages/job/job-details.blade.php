@extends('backend.layout.master')
@section('title', __('Request Details'))
@section('style')
    <x-select2.select2-css />
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="customMarkup__single__item">
            <div class="customMarkup__single__inner mt-4">
                <div class="row g-4">
                    <!-- Job Information -->
                    <div class="col-xl-7 col-lg-12">
                        <div class="project-preview">
                            <h4 class="customMarkup__single__title">{{ __('Request Details') }}</h4>

                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <td>{{ $job->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <td>{{ $job->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Category') }}</th>
                                    <td>{{ $job->category?->category ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Sub Category') }}</th>
                                    <td>{{ $job->subCategory?->sub_category ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Status') }}</th>
                                    <td><x-status.table.active-inactive :status="$job->status ?? 0" /></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Created At') }}</th>
                                    <td>{{ $job->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>

                            <!-- Sub Category Image -->
                            <div class="mt-4">
                                <h4>{{ __('Sub Category Image') }}</h4>
                                <img width="200" height="200"
                                     src="{{ $job->subCategory && $job->sub_category_image ? asset('uploads/' . $job->subCategory->image) : asset('assets/uploads/no-image.png') }}"
                                     alt="{{ $job->subCategory?->sub_category }}">
                            </div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="col-xl-5 col-lg-8">
                        <div class="project-preview">
                            <h4 class="customMarkup__single__title">{{ __('User Information') }}</h4>

                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ __('User Name') }}</th>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Country') }}</th>
                                    <td>{{ $user->user_country?->country ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('State') }}</th>
                                    <td>{{ $user->user_state?->state ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('City') }}</th>
                                    <td>{{ $user->user_city?->city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Completed Requests') }}</th>
                                    <td>{{ $completedJobsCount }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-4">
                    <a href="{{ route('admin.jobs') }}" class="btn btn-secondary">{{ __('Back to All Requests') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection



{{--@extends('backend.layout.master')--}}
{{--@section('title', __('Job Details'))--}}
{{--@section('style')--}}
{{--    <x-select2.select2-css />--}}
{{--@endsection--}}
{{--@section('content')--}}
{{--    <div class="dashboard__body">--}}
{{--        <div class="customMarkup__single__item">--}}
{{--            <div class="customMarkup__single__inner mt-4">--}}
{{--                <div class="row g-4">--}}
{{--                    <div class="col-xl-7 col-lg-12">--}}
{{--                        <div class="project-preview">--}}
{{--                            <div class="project-preview-contents mt-4">--}}
{{--                                <div class="customMarkup__single__item__flex project--rejected--wrapper">--}}
{{--                                    <h4 class="customMarkup__single__title"><x-status.table.active-inactive--}}
{{--                                            :status="$job->status" /></h4>--}}
{{--                                    <h4 class="customMarkup__single__title">{{ __('No of Edit') }} <span--}}
{{--                                            class="project-reject-edit-count">{{ $job->job_history?->edit_count ?? '0' }}</span>--}}
{{--                                    </h4>--}}
{{--                                </div>--}}
{{--                                <h4 class="project-preview-contents-title mt-3"> {{ $job->title }} </h4>--}}
{{--                                <p class="project-preview-contents-para"> {!! $job->description !!} </p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="project-preview">--}}
{{--                            <div class="myJob-wrapper-single-flex flex-between align-items-center">--}}
{{--                                <div class="myJob-wrapper-single-contents">--}}
{{--                                    <div class="jobFilter-proposal-author-flex">--}}
{{--                                        <div class="jobFilter-proposal-author-thumb">--}}
{{--                                            @if($user->image)--}}
{{--                                                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))--}}
{{--                                                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $user->image, load_from: $user->load_from ?? '') }}" alt="{{ __('profile img') }}">--}}
{{--                                                @else--}}
{{--                                                    <img src="{{ asset('assets/uploads/profile/' . $user->image) }}" alt="{{ $user->first_name }}">--}}
{{--                                                @endif--}}
{{--                                            @else--}}
{{--                                                <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}">--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                        <div class="jobFilter-proposal-author-contents">--}}
{{--                                            <h4 class="jobFilter-proposal-author-contents-title"> {{ $user->first_name }}--}}
{{--                                                {{ $user->last_name }}</h4>--}}
{{--                                            <p class="jobFilter-proposal-author-contents-subtitle mt-2">--}}
{{--                                                {{ $user->user_introduction?->title }} ·--}}
{{--                                                <span>{{ $user->user_state?->state }},--}}
{{--                                                    {{ $user->user_country?->country }}</span> </p>--}}
{{--                                            <div class="jobFilter-proposal-author-contents-review mt-2">--}}
{{--                                                <a href="javascript:void(0)"--}}
{{--                                                    class="jobFilter-proposal-author-contents-jobs">--}}
{{--                                                    {{ $complete_jobs_count->count() }} Jobs Completed </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-xl-5 col-lg-8">--}}
{{--                        <div class="sticky-sidebar">--}}
{{--                            <div class="project-preview">--}}
{{--                                <div class="project-preview-tab">--}}
{{--                                    <div class="project-preview-tab-contents mt-4">--}}

{{--                                        <div class="tab-content-item dashboard-tab-content-item active" id="basic">--}}
{{--                                            <div class="project-preview-tab-header">--}}
{{--                                                <div class="project-preview-tab-header-item">--}}
{{--                                                    <span class="left"><i class="fa-solid fa-repeat"></i>--}}
{{--                                                        {{ __('Type') }}</span>--}}
{{--                                                    <strong class="right">{{ __(ucfirst($job->type)) }}</strong>--}}
{{--                                                </div>--}}
{{--                                                <div class="project-preview-tab-header-item">--}}
{{--                                                    <span class="left"><i class="fa-regular fa-clock"></i>--}}
{{--                                                        {{ __('Budget') }}</span>--}}
{{--                                                    <strong--}}
{{--                                                        class="right">{{ amount_with_currency_symbol($job->budget ?? '') }}</strong>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="project-preview-tab-inner mt-4">--}}
{{--                                                @if ($job->last_seen != null)--}}
{{--                                                    <div class="project-preview-tab-inner-item">--}}
{{--                                                        <span class="left">{{ __('Last Seen') }}</span>--}}
{{--                                                        <span class="check-icon">--}}
{{--                                                            {{ \Carbon\Carbon::parse($job->last_seen)?->diffForHumans() }}--}}
{{--                                                        </span>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
{{--                                                @if ($job->attachment)--}}
{{--                                                    <div class="project-preview-tab-inner-item">--}}
{{--                                                        <span class="left">{{ __('Attchment') }}</span>--}}
{{--                                                        <span class="check-icon">--}}
{{--                                                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))--}}
{{--                                                                <a href="{{ render_frontend_cloud_image_if_module_exists('jobs/'.$job->attachment, load_from: $job->load_from) }}"--}}
{{--                                                                   download class="single-refundRequest-item-uploads">--}}
{{--                                                                    <i class="fa-solid fa-cloud-arrow-down"></i>--}}
{{--                                                                    {{ __('Download Attachment') }}--}}
{{--                                                                </a>--}}
{{--                                                            @else--}}
{{--                                                                <a href="{{ asset('assets/uploads/jobs/' . $job->attachment) }}"--}}
{{--                                                                    download class="single-refundRequest-item-uploads">--}}
{{--                                                                    <i class="fa-solid fa-cloud-arrow-down"></i>--}}
{{--                                                                    {{ __('Download Attachment') }}--}}
{{--                                                                </a>--}}
{{--                                                            @endif--}}
{{--                                                        </span>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
{{--                                                @if ($job->level)--}}
{{--                                                    <div class="project-preview-tab-inner-item">--}}
{{--                                                        <span class="left">{{ __('Experience Level') }}</span>--}}
{{--                                                        <span class="check-icon">{{ ucfirst($job->level) }}</span>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
{{--                                                <div class="project-preview-tab-inner-item">--}}
{{--                                                    <span class="left">{{ __('Category') }}</span>--}}
{{--                                                    <span--}}
{{--                                                        class="check-icon">{{ $job->job_category?->category ?? '' }}</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <hr class="mt-5">--}}
{{--                                        <div class="btn-wrapper flex-btn justify-content-between">--}}
{{--                                            @if ($job->status === 0)--}}
{{--                                                <x-status.table.status-change :title="__('Approve Job')" :class="'btn-profile btn-bg-1 swal_status_change_button'"--}}
{{--                                                    :url="route('admin.job.status.change', $job->id)" />--}}
{{--                                            @else--}}
{{--                                                <x-status.table.status-change :title="__('Inactive Job')" :class="'btn-profile btn-bg-1 swal_status_change_button'"--}}
{{--                                                    :url="route('admin.job.status.change', $job->id)" />--}}
{{--                                            @endif--}}

{{--                                            <x-notice.general-notice :description="__(--}}
{{--                                                'Notice: Active means the job will show for the website users.',--}}
{{--                                            )" :description1="__(--}}
{{--                                                'Notice: Inactive means the job will not show for the website users.',--}}
{{--                                            )" />--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--@endsection--}}

{{--@section('script')--}}
{{--    <x-sweet-alert.sweet-alert2-js />--}}
{{--    <x-select2.select2-js />--}}
{{--    @include('backend.pages.project.project-js')--}}

{{--@endsection--}}
