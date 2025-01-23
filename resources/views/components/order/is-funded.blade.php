@if($isFunded == 'complete')
    <span class="myJob-wrapper-single-fixed active">{{ __('Funded') }}</span>
@else
    @if($paymentGateway != 'manual_payment' && $isFunded == 'pending')
        <span class="myJob-wrapper-single-fixed danger">{{ __('Payment Failed') }}</span>
    @else
        <span class="myJob-wrapper-single-fixed danger">{{ __('Not Funded') }}</span>
    @endif
@endif
