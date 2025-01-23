<div class="single-input mt-3">
    <label class="label-title">{{ $title }}</label>
    <select name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="{{ $class ?? '' }}" multiple>
        <option value="">{{ __('Select Skill') }}</option>
        @foreach($allSkills = \App\Models\Skill::all_skills() as $data)
            <option value="{{ $data->id }}">{{ $data->skill }}</option>
        @endforeach
    </select>
</div>
