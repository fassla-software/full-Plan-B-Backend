@if($status === 0)
    <span class="pending-approval">{{ __('Pending') }}</span>
@endif
@if($status === 1)
    <span class="job-progress">{{ __('Accepted') }}</span>
@endif

@if($status === 2)
    <span class="pending-approval">{{ __('Rejected') }}</span>
@endif
