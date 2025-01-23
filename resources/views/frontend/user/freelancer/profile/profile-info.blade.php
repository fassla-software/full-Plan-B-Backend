<div class="single-profile-settings" id="display_freelancer_profile_info">
    <div class="single-profile-settings-header">
        <div class="single-profile-settings-header-flex">
            <x-form.form-title :title="__('Personal Information')" :class="'single-profile-settings-header-title'" />
            <div class="btn-wrapper">
                <a href="javascript:void(0)" class="btn-profile btn-outline-gray profile-click"><i
                        class="fa-regular fa-edit"></i>{{ __('Edit Info') }}</a>
            </div>
        </div>
    </div>
    <div class="single-profile-settings-inner profile-border-top">
        <div class="single-profile-settings-form custom-form">
            <div class="single-flex-input">
                <div class="single-input">
                    <label for="title" class="label-title">{{ __('First Name') }}</label>
                    <input value="{{ Auth::guard('web')->user()->first_name ?? '' }}" class="form-control" readonly
                        disabled>
                </div>
                <div class="single-input">
                    <label for="title" class="label-title">{{ __('Last Name') }}</label>
                    <input value="{{ Auth::guard('web')->user()->last_name ?? '' }}" class="form-control" readonly
                        disabled>
                </div>
            </div>
            <div class="single-input">
                <label for="title" class="label-title">{{ __('Your Email') }}</label>
                <input value="{{ Auth::guard('web')->user()->email ?? '' }}" class="form-control" readonly disabled>
            </div>
            <div class="single-input">
                <label for="title" class="label-title">{{ __('Your Country') }}</label>
                <input value="{{ optional(Auth::guard('web')->user()->user_country)->country ?? '' }}"
                    class="form-control" readonly disabled>
            </div>
            @if(moduleExists('CoinPaymentGateway'))
            @else
            <div class="single-input">
                <label for="title" class="label-title">{{ __('Your State') }}</label>
                <input value="{{ optional(Auth::guard('web')->user()->user_state)->state ?? '' }}" class="form-control"
                    readonly disabled>
            </div>

                <div class="single-input">
                    <label for="title" class="label-title">{{ __('Your City') }}</label>
                    <input value="{{ optional(Auth::guard('web')->user()->user_city)->city ?? '' }}" class="form-control"
                           readonly disabled>
                </div>
            @endif
                <div class="single-input">
                    <label for="title" class="label-title">{{ __('Your Experience Level') }}</label>
                    <input value="{{ ucfirst(Auth::guard('web')->user()->experience_level) ?? '' }}" class="form-control"
                           readonly disabled>
                </div>
        </div>
    </div>
</div>
