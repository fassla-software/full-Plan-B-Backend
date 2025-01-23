<!-- Setup Skills Starts -->
<div class="setup-wrapper-contents">
    <div class="setup-wrapper-contents-item">
        <h3 class="setup-wrapper-contents-title"> {{ get_static_option('skill_title') ?? __('Great! Now add some skills you have') }} </h3>
        <div class="setup-wrapper-skill">
            <p class="setup-wrapper-skill-para">{{ __('Type and hit ↵ Enter to add a skill or choose from suggestions below') }}</p>
            <div class="setup-wrapper-skill-tagInputs mt-4">
                <input type="text" id="skill_input" placeholder="__('select tags')">
            </div>
        </div>
    </div>
    <div class="setup-wrapper-contents-item">
        <ul class="setup-wrapper-work-list">
            @php
                $skills =  \App\Models\UserSkill::select('skill')->where('user_id',Auth::guard('web')->user()->id)->first()->skill ?? '';
                $array_skill = explode(", ",$skills);
            @endphp
            @if($skills_according_to_category)
                @foreach($skills_according_to_category as $skill)
                    @if(!in_array($skill->skill, $array_skill))
                        <li class="setup-wrapper-work-list-item choose_skill"> {{ $skill->skill }} </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>
<!-- Setup Skills Ends -->

