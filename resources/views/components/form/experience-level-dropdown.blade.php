
@php $all_levels = \App\Models\ExperienceLevel::where('status',1)->get() @endphp

@if($all_levels->count() >= 1)
<div class="single-flex-input">
    <div class="single-input">
        <label class="label-title">{{ $title ?? '' }}</label>
        <select name="level" id="level" class="{{ $class ?? 'form-control' }}">
            <option value="">{{ __('Select') }}</option>
            @foreach($all_levels as $level)
                <option value="{{ $level->level }}">{{ $level->level }}</option>
            @endforeach
        </select>
    </div>
</div>
@else
    <div class="single-flex-input">
        <div class="single-input">
            <label class="label-title">{{ $title ?? '' }}</label>
            <select name="level" id="level" class="{{ $class ?? 'form-control' }}">
                <option value="">{{ __('Select') }}</option>
                <option value="junior">{{ __('Junior') }}</option>
                <option value="midLevel">{{ __('MidLevel') }}</option>
                <option value="senior">{{ __('Senior') }}</option>
                <option value="not mandatory">{{ __('Not Mandatory') }}</option>
            </select>
        </div>
    </div>
@endif
