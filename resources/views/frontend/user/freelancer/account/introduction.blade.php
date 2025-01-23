<!-- Setup Introduction Start -->
<div class="setup-wrapper-contents active">
    <div class="setup-wrapper-contents-item">
        <h3 class="setup-wrapper-contents-title">{{ get_static_option('professional_title') ?? __('Tell us what professional title describes you?(Introduction)')}} </h3>
        <div class="setup-wrapper-contents-form">
            <form action="#">
                <div class="setup-wrapper-contents-form-item">
                    <input type="text" name="title" id="title" @if(!empty($user_introduction)) value="{{$user_introduction->title}}" @endif class="form--control" placeholder="{{ __('Enter Your Title') }}">
                </div>
            </form>
        </div>
    </div>
    <div class="setup-wrapper-contents-item">
        <h3 class="setup-wrapper-contents-title">{{ get_static_option('intro_title') ?? __('Provide an intro about yourself') }}</h3>
        <div class="setup-wrapper-contents-form">
            <form action="#">
                <div class="setup-wrapper-contents-form-item">
                    <textarea name="description" id="description" class="form-message" cols="30" rows="3" placeholder="{{ __('I am a professional develop...') }}">@if(!empty($user_introduction)) {{$user_introduction->description}} @endif</textarea>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Setup Introduction Ends -->
