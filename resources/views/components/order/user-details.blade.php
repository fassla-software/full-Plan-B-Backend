<div class="user-details-manage bg-white">
    <div class="user-details-manage-list">
        <p class="item"><strong>{{ __('Name:') }}</strong> {{ $firstName.' '.$lastName }}</p>
        <p class="item"><strong>{{ __('Email:') }}</strong> {{ $email }}</p>
        <p class="item"><strong>{{ __('Phone:') }}</strong> {{ $phone }}</p>
        @if($country)
        <p class="item"><strong>{{ __('Country:') }}</strong> {{ $country }}</p>
        @endif
        @if($state)
            <p class="item"><strong>{{ __('State:') }}</strong> {{ $state }}</p>
        @endif
        @if($city)
            <p class="item"><strong>{{ __('City:') }}</strong> {{ $city }}</p>
        @endif
    </div>
</div>
