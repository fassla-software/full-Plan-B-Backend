<div class="single-input {{ $divClass ?? 'mb-3' }}">
    <label for="{{ $id ?? '' }}" class="{{ $labelClass ?? 'label-title'}}">{{ $title ?? '' }}</label>
    <input type="{{ $type ?? '' }}" name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" value="{{ $value ?? '' }}" step="{{ $step ?? '' }}" placeholder="{{ $placeholder ?? '' }}" class="{{ $class ?? 'form-control' }}" >
</div>
