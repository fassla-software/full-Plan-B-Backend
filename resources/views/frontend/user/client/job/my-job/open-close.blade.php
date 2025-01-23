<div class="btn-wrapper job_open_close_location_{{$job->id}}">
    <a href="javascript:void(0)" class="job_open_close" data-job-id="{{ $job->id }}" data-job-on-off="{{ $job->on_off }}">
        @if($job->on_off == 0)
            <span class="btn-profile btn-bg-1">{{ __('Open Job') }}</span>
        @else
            <span class="btn-profile btn-bg-cancel">{{ __('Close Job') }}</span>
        @endif
    </a>
</div>
