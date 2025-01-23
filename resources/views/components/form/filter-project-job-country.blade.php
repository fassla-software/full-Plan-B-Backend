<div class="single-input">
    <label class="label-title">{{ $title ?? '' }}</label>
    <select name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="form-control country_select2">
        <option value="">{{ $innerTitle ?? '' }}</option>
        @foreach($all_countries = \Modules\CountryManage\Entities\Country::all_countries() as $country)
            <option value="{{ $country->id }}">{{ $country->country }}</option>
        @endforeach
    </select>
</div>
