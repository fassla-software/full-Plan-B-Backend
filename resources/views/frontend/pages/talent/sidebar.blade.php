<div class="shop-sidebar-content">
    <div class="shop-close-content">
        <div class="shop-close-content-icon"> <i class="fas fa-times"></i> </div>
        <div class="single-shop-left bg-white radius-10">
            <div class="single-shop-left-filter">
                <div class="single-shop-left-filter-flex flex-between">
                    <div class="single-shop-left-filter-title">
                        <h5 class="title">
                            {{ __('Talent Filter') }}
                        </h5>
                    </div>
                    <a href="{{ route('talents.all') }}" class="single-shop-left-filter-reset">{{ __('Reset Filter') }}</a>
                </div>
            </div>
        </div>
        <div class="single-shop-left bg-white radius-10 mt-4">
            <div class="single-shop-left-title open">
                <h5 class="title"> {{ __('Search by Country') }} </h5>
                <div class="single-shop-left-inner margin-top-15">
                    <div class="single-shop-left-select">
                        <x-form.filter-project-job-country :innerTitle="__('Select')" :name="'category'" :id="'country'" />
                    </div>
                </div>
            </div>
        </div>

        <div class="single-shop-left bg-white radius-10 mt-4">
            <div class="single-shop-left-title open">
                <h5 class="title">{{ __('Experience Level') }}</h5>
                <div class="single-shop-left-inner margin-top-15">
                    <div class="single-shop-left-select">
                        <div class="single-flex-input">
                            <div class="single-input">
                                <select name="level" id="level" class="form-control">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="junior">{{ __('Junior') }}</option>
                                    <option value="midLevel">{{ __('MidLevel') }}</option>
                                    <option value="senior">{{ __('Senior') }}</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @if(moduleExists('FreelancerLevel'))
            @php
                $levels = \Modules\FreelancerLevel\Entities\FreelancerLevel::latest()->whereHas('level_rule')
                ->with('level_rule')
                ->where('status',1)
                ->get();
            @endphp
        <div class="single-shop-left bg-white radius-10 mt-4">
            <div class="single-shop-left-title open">
                <h5 class="title">{{ __('Talent Badge') }}</h5>
                <div class="single-shop-left-inner margin-top-15">
                    <div class="single-shop-left-select">
                        <div class="single-flex-input">
                            <div class="single-input">
                                <select name="talent_badge" id="talent_badge" class="form-control">
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level?->level_rule?->period }}">{{ $level->level }}</option>
                                    @endforeach
{{--                                    <option value="rising_talent">{{ __('Rising Talent') }}</option>--}}
{{--                                    <option value="top_rated">{{ __('Top Rated') }}</option>--}}
{{--                                    <option value="top_rated_plus">{{ __('Top Rated Plus') }}</option>--}}
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="single-shop-left bg-white radius-10 mt-4">
            <div class="single-shop-left-title open">
                <h5 class="title">{{ __('Search By Category') }}</h5>
                <div class="single-shop-left-inner margin-top-15">
                    <div class="single-shop-left-select">
                        <x-form.category-dropdown :title="''" :name="'category'" :id="'category'" :class="'form-control'" />
                    </div>
                </div>
            </div>
        </div>
        <div class="single-shop-left bg-white radius-10 mt-4">
            <div class="single-shop-left-title open">
                <h5 class="title"> {{ __('Search by Subcategory') }} </h5>
                <div class="single-shop-left-inner margin-top-15">
                    <div class="single-shop-left-select">
                        <div class="single-input mt-3">
                            <select name="subcategory[]" id="subcategory" class="form-control get_subcategory subcategory_select2" multiple>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="single-shop-left bg-white radius-10 mt-4">
            <div class="single-shop-left-title open">
                <h5 class="title">{{ __('Search By Skill') }}</h5>
                <div class="single-shop-left-inner margin-top-15">
                    <div class="single-shop-left-select">
                        <select name="skill" id="skill" class="form-control">
                            <option value="">{{ __('Select Skill') }}</option>
                            @foreach($allSkills = \App\Models\Skill::all_skills() as $data)
                                <option value="{{ $data->skill }}">{{ $data->skill }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
