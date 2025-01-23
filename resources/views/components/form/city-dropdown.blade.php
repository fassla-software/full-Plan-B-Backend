<div class="single-input">
    <label class="label-title">{{ $title }}</label>
    <select name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="form-control get_state_city city_select2">
        <option value="">{{ __('Select City') }}</option>
        @if(!empty(Auth::guard('web')->user()->country_id))
            @foreach($all_cities = \Modules\CountryManage\Entities\City::where('state_id',Auth::guard('web')->user()->state_id)->get() as $city)
                <option value="{{ $city->id }}" @if($city->id == Auth::guard('web')->user()->city_id) selected @endif>{{ $city->city }}</option>
            @endforeach
        @else
            @foreach($all_cities = \Modules\CountryManage\Entities\City::all_cities() as $city)
                <option value="{{ $city->id }}" @if($city->id == Auth::guard('web')->user()->city_id) selected @endif>{{ $city->city }}</option>
            @endforeach
        @endif
    </select>
    <span class="city_info"></span>
</div>
