<div class="single-input">
    <label class="label-title">{{ $title }}</label>
    <select name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" class="form-control get_country_state state_select2">
        <option value="">{{ __('Select State') }}</option>
        @if(!empty(Auth::guard('web')->user()->country_id))
            @foreach($all_states = \Modules\CountryManage\Entities\State::where('country_id',Auth::guard('web')->user()->country_id)->get() as $state)
                <option value="{{ $state->id }}" @if(Auth::guard('web')->check() && $state->id == Auth::guard('web')->user()->state_id) selected @endif>{{ $state->state }}</option>
            @endforeach
        @else
            @foreach($all_states = \Modules\CountryManage\Entities\State::all_states() as $state)
                <option value="{{ $state->id }}" @if(Auth::guard('web')->check() && $state->id == Auth::guard('web')->user()->state_id) selected @endif>{{ $state->state }}</option>
            @endforeach
        @endif
    </select>
    <span class="state_info"></span>
</div>
