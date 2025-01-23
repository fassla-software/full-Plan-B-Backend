@if(Cache::has('user_is_online_'.$userID))
    <span class="single-freelancer-author-status"> {{ __('Active') }} </span>
@else
    <span class="single-freelancer-author-status-ofline"> {{ __('Inactive') }} </span>
@endif