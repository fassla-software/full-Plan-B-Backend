@if($isView == 1)
    <span class="shortlisted-item seen">{{ __('Seen') }}</span>
@else
    <span class="shortlisted-item not_seen">{{ __('Not Seen') }}</span>
@endif