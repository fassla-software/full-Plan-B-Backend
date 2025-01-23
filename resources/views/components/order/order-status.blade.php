@if($status === 1)
    <span class="job-progress">{{ __('Active Order') }}</span>
@else
    @if($status === 0)
        <span class="pending-approval">{{ __('Queue Order') }}</span>
    @endif
    @if($status === 2)
        <span class="pending-approval">{{ __('Deliver Order') }}</span>
    @endif
    @if($status=== 3)
        <span class="pending-approval">{{ __('Complete Order') }}</span>
    @endif
    @if($status === 4)
         <span class="pending-approval">{{ __('Cancel Order') }}</span>
    @endif
    @if($status === 5)
         <span class="pending-approval">{{ __('Decline Order') }}</span>
    @endif
    @if($status === 6)
         <span class="pending-approval">{{ __('Suspend Order') }}</span>
    @endif
    @if($status === 7)
        <span class="pending-approval">{{ __('Hold Order') }}</span>
    @endif
@endif
