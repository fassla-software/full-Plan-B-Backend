<div class="profile-wrapper-item radius-10">
    <div class="profile-wrapper-flex flex-between">
        <div class="profile-wrapper-author-cotents">
            <h4 class="profile-wrapper-about-title mt-2"> <a href="{{ route('client.job.create') }}">{{ __('Post a Job') }}</a> </h4>
            <span class="profile-wrapper-about-para mt-2">{{ __('Post a job to find and hire talents for your projects.') }} </span>
        </div>
        <div class="profile-wrapper-right">
            <div class="btn-wrapper">
                @if(moduleExists('SecurityManage'))
                    @if(Auth::guard('web')->user()->freeze_job == 'freeze')
                        <a href="#" class="btn-profile btn-bg-1 @if(Auth::guard('web')->user()->freeze_job == 'freeze') disabled-link @endif">{{ __('Post a Job') }}</a>
                    @else
                        <a href="{{ route('client.job.create') }}" class="btn-profile btn-bg-1">{{ __('Post a Job') }}</a>
                    @endif
                @else
                   <a href="{{ route('client.job.create') }}" class="btn-profile btn-bg-1">{{ __('Post a Job') }}</a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="tab-content-item-two active" id="job-postings">
    <div class="myJob-wrapper-tab mt-4">
        <div class="myJob-tabs">
            <ul class="tabs">
                <li data-tab="all-jobs" data-val="all" class="active jobs_filter_for_client"> {{ __('All Jobs') }} ({{ $all_jobs->total() }})</li>
                <li data-tab="active-jobs" data-val="active" class="jobs_filter_for_client"> {{ __('Active Jobs') }} ({{ $active_jobs }})</li>
                <li data-tab="closed-jobs" data-val="close" class="jobs_filter_for_client"> {{ __('Closed Jobs') }} ({{ $closed_jobs }})</li>
                <li data-tab="completed-jobs" data-val="complete" class="jobs_filter_for_client"> {{ __('Completed Jobs') }} ({{ $complete_jobs }})</li>
            </ul>
        </div>
    </div>
</div>

<input type="hidden" id="set_filter_type_value" value="all">
