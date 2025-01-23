<div class="myJob-wrapper-single-contents">
    <h4 class="myJob-wrapper-single-title">
        <a href="javascript:void(0)"># {{ $id }} - {{ $title }}</a>
    </h4>
    <div class="myJob-wrapper-single-priceCompleted mt-3">
        <span class="myJob-wrapper-single-price color-one d-flex align-content-center gap-2">
            {{ float_amount_with_currency_symbol($price) }}
        </span>
    </div>
    <span class="myJob-wrapper-single-date mt-3"><strong>{{ __('Delivery Time:') }}</strong> {{ $deadline ?? '' }}</span>
    <p class="myJob-wrapper-single-para mt-3">{{ $description }}</p>
</div>
