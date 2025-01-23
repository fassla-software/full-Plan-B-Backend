@if($isHired == 1)
    <span class="shortlisted-item hired">{{ __('Hired') }}</span>
@elseif($isShortListed == 1)
    <span class="shortlisted-item">{{ __('Shortlisted') }}</span>
@endif
