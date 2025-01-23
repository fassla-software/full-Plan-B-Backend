<div class="single-input {{ $divClass ?? 'mb-3' }}">
    <label for="{{ $id ?? '' }}" class="{{ $labelClass ?? 'label-title'}}">{{ $title ?? '' }}</label>
    <input type="number" min="{{ $min ?? 7 }}" max="{{ $max ?? 365 }}" step="{{ $step ?? '' }}"  name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" value="{{ $value ?? '' }}" placeholder="{{ $placeholder ?? '' }}" class="{{ $class ?? 'form-control' }}" >
</div>
