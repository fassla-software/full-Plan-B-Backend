<div class="{{ $class ?? 'single-input' }}">
    <label class="label-title">{{ $title ?? __('Delivery Time') }}</label>
    <select name="{{ $name ?? 'duration' }}" id="{{ $id ?? 'duration' }}" class="{{ $selectClass ?? 'form-control' }} ">
        <option value="">{{ __('Select Delivery Time') }}</option>
        <option value="1 Days">{{ __('1 Days') }}</option>
        <option value="2 Days">{{ __('2 Days') }}</option>
        <option value="3 Days">{{ __('3 Days') }}</option>
        <option value="Less than a week">{{ __('Less than a Week') }}</option>
        <option value="Less than a month">{{ __('Less than a month') }}</option>
        <option value="Less than 2 month">{{ __('Less than 2 month') }}</option>
        <option value="Less than 3 month">{{ __('Less than 3 month') }}</option>
        <option value="More than 3 month">{{ __('More than 3 month') }}</option>
    </select>
</div>
