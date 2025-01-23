<div class="single-input mt-3">
    <label for="" class="label-title">{{ $title }}</label>
    <textarea class="{{ $class ?? 'form-control summernote' }}" name="{{ $name }}" id="{{ $id }}">{!!  $value ?? '' !!}</textarea>
</div>
