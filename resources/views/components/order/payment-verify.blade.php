@php
    $order = $paymentVerifyCheck;
@endphp
@if($order->status != 3)
    @if(!empty($order?->job->hourly_rate) && $order->is_fixed_hourly == 'hourly')
        <span class="item-icon">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M11.2513 7.70825H1.66797C1.3263 7.70825 1.04297 7.42492 1.04297 7.08325C1.04297 6.74159 1.3263 6.45825 1.66797 6.45825H11.2513C11.593 6.45825 11.8763 6.74159 11.8763 7.08325C11.8763 7.42492 11.593 7.70825 11.2513 7.70825Z"
                        fill="#475467" />
                <path
                        d="M6.66667 14.375H5C4.65833 14.375 4.375 14.0917 4.375 13.75C4.375 13.4083 4.65833 13.125 5 13.125H6.66667C7.00833 13.125 7.29167 13.4083 7.29167 13.75C7.29167 14.0917 7.00833 14.375 6.66667 14.375Z"
                        fill="#475467" />
                <path
                        d="M12.0833 14.375H8.75C8.40833 14.375 8.125 14.0917 8.125 13.75C8.125 13.4083 8.40833 13.125 8.75 13.125H12.0833C12.425 13.125 12.7083 13.4083 12.7083 13.75C12.7083 14.0917 12.425 14.375 12.0833 14.375Z"
                        fill="#475467" />
                <path
                        d="M14.6346 17.7084H5.36797C2.0513 17.7084 1.04297 16.7084 1.04297 13.4251V6.57508C1.04297 3.29175 2.0513 2.29175 5.36797 2.29175H11.2513C11.593 2.29175 11.8763 2.57508 11.8763 2.91675C11.8763 3.25841 11.593 3.54175 11.2513 3.54175H5.36797C2.7513 3.54175 2.29297 3.99175 2.29297 6.57508V13.4167C2.29297 16.0001 2.7513 16.4501 5.36797 16.4501H14.6263C17.243 16.4501 17.7013 16.0001 17.7013 13.4167V10.0167C17.7013 9.67508 17.9846 9.39175 18.3263 9.39175C18.668 9.39175 18.9513 9.67508 18.9513 10.0167V13.4167C18.9596 16.7084 17.9513 17.7084 14.6346 17.7084Z"
                        fill="#475467" />
                <path
                        d="M14.4237 7.45003C14.2654 7.45003 14.107 7.3917 13.982 7.2667C13.7404 7.02503 13.7404 6.62503 13.982 6.38337L17.2237 3.1417C17.4654 2.90003 17.8654 2.90003 18.107 3.1417C18.3487 3.38337 18.3487 3.78337 18.107 4.02503L14.8654 7.2667C14.7404 7.3917 14.582 7.45003 14.4237 7.45003Z"
                        fill="#475467" />
                <path
                        d="M17.6576 7.45003C17.4992 7.45003 17.3409 7.3917 17.2159 7.2667L13.9742 4.02503C13.7326 3.78337 13.7326 3.38337 13.9742 3.1417C14.2159 2.90003 14.6159 2.90003 14.8576 3.1417L18.0992 6.38337C18.3409 6.62503 18.3409 7.02503 18.0992 7.2667C17.9826 7.3917 17.8242 7.45003 17.6576 7.45003Z"
                        fill="#475467" />
            </svg>
        </span>
        @if($order?->job?->job_creator?->user_wallet?->balance >= ($order?->job->hourly_rate * $order?->job->estimated_hours) )
            <span class="item-para">{{  __('Verified') }}</span>
        @else
            <span class="item-para">{{ __('Not Verified') }}</span>
        @endif
    @endif
@endif
