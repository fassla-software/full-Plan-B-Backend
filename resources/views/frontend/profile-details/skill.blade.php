@if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)
    <div class="profile-wrapper-item radius-10">
        <div class="profile-wrapper-item-flex flex-between align-items-center profile-border-bottom">
            <h4 class="profile-wrapper-item-title"> {{ __('Skills') }} </h4>
            <div class="profile-wrapper-item-plus display_edit_skill_wrapper edit_skill_show_hide"><i class="fa-regular fa-pen-to-square"></i></div>
        </div>

        <ul class="setup-wrapper-work-list freelancer_skill_list">
            @php
                $array_skill = explode(", ",$skills);
                $array_length =  count($array_skill);
            @endphp
            @for($i = 0; $i<=($array_length-1); $i ++ )
                <li class="setup-wrapper-work-list-item "> {{ $array_skill[$i] }} </li>
            @endfor
        </ul>
        <div class="edit_skill_wrapper">
            <div class="setup-wrapper-skill">
                <p class="setup-wrapper-skill-para">{{ __('Type and hit ↵ Enter to add a skill or choose from suggestions below') }}</p>

                <div class="setup-wrapper-skill-tagInputs mt-4">
                    <input type="text" id="skill_input" placeholder="__('select tags')">
                </div>
            </div>

            <h6 class="setup-wrapper-experience-details-subtitle mt-4">{{ __('Suggested Skill') }} </h6>
            <ul class="setup-wrapper-work-list mt-3">
                @if($skills_according_to_category)
                    @foreach($skills_according_to_category as $skill)
                        @if(!in_array($skill->skill, $array_skill))
                            <li class="setup-wrapper-work-list-item choose_skill"> {{ $skill->skill }} </li>
                        @endif
                    @endforeach
                @endif
            </ul>
            <div class="btn-wrapper d-flex justify-content-end mt-3">
                <a href="javascript:void(0)" class="cmn-btn btn-bg-1 btn-small update_freelancer_skill">{{ __('Update Skills') }}</a>
            </div>
        </div>
    </div>
@elseif(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Auth::guard('web')->user()->username==$username && Session::get('user_role') == 'freelancer')
    <div class="profile-wrapper-item radius-10">
        <div class="profile-wrapper-item-flex flex-between align-items-center profile-border-bottom">
            <h4 class="profile-wrapper-item-title"> {{ __('Skills') }} </h4>
            <div class="profile-wrapper-item-plus display_edit_skill_wrapper edit_skill_show_hide"><i class="fa-regular fa-pen-to-square"></i></div>
        </div>

        <ul class="setup-wrapper-work-list freelancer_skill_list">
            @php
                $array_skill = explode(",",$skills);
                $array_length =  count($array_skill);
            @endphp
            @if($array_length > 1)
                @for($i = 0; $i<=($array_length-1); $i ++ )
                    <li class="setup-wrapper-work-list-item "> {{ $array_skill[$i] }} </li>
                @endfor
            @endif
        </ul>
        <div class="edit_skill_wrapper">
            <div class="setup-wrapper-skill">
                <p class="setup-wrapper-skill-para">{{ __('Type and hit ↵ Enter to add a skill or choose from suggestions below') }}</p>

                <div class="setup-wrapper-skill-tagInputs mt-4">
                    <input type="text" id="skill_input" placeholder="__('select tags')">
                </div>
            </div>

            <h6 class="setup-wrapper-experience-details-subtitle mt-4">{{ __('Suggested Skill') }} </h6>
            <ul class="setup-wrapper-work-list mt-3">
                @if($skills_according_to_category)
                    @foreach($skills_according_to_category as $skill)
                        @if(!in_array($skill->skill, $array_skill))
                            <li class="setup-wrapper-work-list-item choose_skill"> {{ $skill->skill }} </li>
                        @endif
                    @endforeach
                @endif
            </ul>
            <div class="btn-wrapper d-flex justify-content-end mt-3">
                <a href="javascript:void(0)" class="cmn-btn btn-bg-1 btn-small update_freelancer_skill">{{ __('Update Skills') }}</a>
            </div>
        </div>
    </div>
@else
    <div class="profile-wrapper-item radius-10">
        <div class="profile-wrapper-item-flex flex-between align-items-center profile-border-bottom">
            <h4 class="profile-wrapper-item-title"> {{ __('Skills') }} </h4>
        </div>
        <ul class="setup-wrapper-work-list">
            @php
                $array_skill = explode(",",$skills);
                $array_length =  count($array_skill);
            @endphp
            @if($array_length > 1)
                @for($i = 0; $i<=($array_length-1); $i ++ )
                    <li class="setup-wrapper-work-list-item "> {{ $array_skill[$i] }} </li>
                @endfor
            @endif
        </ul>
    </div>
@endif
