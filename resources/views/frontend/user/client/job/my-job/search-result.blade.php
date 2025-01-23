<div class="tab-content-item active mt-4" id="all-jobs">
    <div class="myJob-wrapper">
        @if (!empty($all_jobs))
            @foreach ($all_jobs as $job)
                <div class="myJob-wrapper-single job_open_close_location_{{ $job->id }}">
                    <div class="myJob-wrapper-single-flex flex-between align-items-center">
                        <div class="myJob-wrapper-single-contents">
                            <div class="flex-btn">
                                <span class="myJob-wrapper-single-id">#000{{ $job->id }}</span>
                                <div class="btn-item">
                                    <span class="myJob-wrapper-single-fixed">{{ __(ucfirst($job->type)) }}</span>
                                </div>
                                @if ($job->on_off == 0)
                                <div class="btn-item">
                                    <span
                                        class="myJob-wrapper-single-fixed closed">{{ __('Closed') }}</span>
                                </div>
                                @else
                                <div class="btn-item">
                                    <span
                                        class="myJob-wrapper-single-fixed active">{{ __('Open') }}
                                    </span>
                                </div>
                                @endif
                                @if ($job->current_status == 1)
                                <div class="btn-item">
                                    <span
                                        class="myJob-wrapper-single-fixed not-started">{{ __('In Progress') }}</span>
                                </div>
                                @endif
                                @if ($job->current_status == 2)
                                <div class="btn-item">
                                    <span
                                        class="myJob-wrapper-single-fixed completed">{{ __('Complete') }}</span>
                                </div>
                                @endif
                            </div>
                            <h4 class="myJob-wrapper-single-title mt-3">
                                <a href="{{ route('client.job.details', $job->id) }}">{{ $job->title }}</a>
                            </h4>
                            <div class="myJob-wrapper-single-list mt-3">
                                @if ($job->on_off == 1)
                                    <span class="job_publicPrivate_view">{{ __('Public') }}</span>
                                @else
                                    <span class="job_publicPrivate_view">{{ __('Only Me') }}</span>
                                @endif
                                <div
                                    class="single-jobs-date mt-0">{{ Carbon\Carbon::parse($job->created_at)->toFormattedDateString() }}
                                    - <span>{{ ucfirst($job->level) }}</span>
                                </div>
                                <span class="single-jobs-date mt-0">{{ __('Proposals:') }} {{ $job?->job_proposals_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-between profile-border-top">
                        <div class="btn-wrapper">
                            <a href="javascript:void(0)" class="job_open_close" data-job-id="{{ $job->id }}"
                                data-job-on-off="{{ $job->on_off }}">
                                @if ($job->on_off == 0)
                                    <span class="btn-profile btn-outline-1">{{ __('Open Job') }}</span>
                                @else
                                    <span class="btn-profile btn-outline-cancel">{{ __('Close Job') }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="btn-wrapper flex-btn gap-2">
                            @if(moduleExists('SecurityManage'))
                                @if(Auth::guard('web')->user()->freeze_job == 'freeze')
                                    <a href="#" class="btn-profile btn-outline-gray @if(Auth::guard('web')->user()->freeze_job == 'freeze') disabled-link @endif">
                                        <i class="fa-regular fa-edit"></i>{{ __('Edit Job') }}
                                    </a>
                                @else
                                    <a href="{{ route('client.job.edit', $job->id) }}" class="btn-profile btn-outline-gray">
                                        <i class="fa-regular fa-edit"></i>{{ __('Edit Job') }}
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('client.job.edit', $job->id) }}" class="btn-profile btn-outline-gray">
                                    <i class="fa-regular fa-edit"></i>{{ __('Edit Job') }}
                                </a>
                            @endif
                            <a href="{{ route('client.job.details', $job->id) }}"
                                class="btn-profile btn-bg-1">{{ __('View Job') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h2 class="text-danger">{{ __('No Jobs Found') }}</h2>
        @endif
    </div>
</div>

<div class="mt-3">
    <x-pagination.laravel-paginate :allData="$all_jobs" />
</div>
