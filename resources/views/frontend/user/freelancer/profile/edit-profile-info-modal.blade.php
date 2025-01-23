<!-- Profile Settings Popup Starts -->
<div class="popup-overlay"></div>
<form id="edit_profile_form" method="post">
    @csrf
    <div class="popup-fixed profile-popup">
        <div class="popup-contents">
            <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
            <h2 class="popup-contents-title"> {{ __('Personal Information') }} </h2>
            <x-notice.general-notice :description="__('Notice: Except state and city all fields are required. Your identity verify documents info must be similar your personal info.')" />
            <div class="popup-contents-form custom-form profile-border-top">
                <div class="error_msg_container"></div>
                <div class="single-flex-input">
                    <x-form.text :title="__('First Name')" :type="__('text')" :name="'first_name'" :id="'first_name'" :placeholder="__('Type First Name')" :value="Auth::guard('web')->user()->first_name ?? '' "/>
                    <x-form.text :title="__('Last Name')" :type="__('text')" :name="'last_name'" :id="'last_name'" :placeholder="__('Type Last Name')" :value="Auth::guard('web')->user()->last_name ?? '' "/>
                </div>
                <div class="single-flex-input">
                    <x-form.email :title="__('Your Email')" :type="__('email')" :name="'email'" :id="'email'" :placeholder="__('Type Email')" :value="Auth::guard('web')->user()->email ?? '' "/>
                </div>
                <div class="single-flex-input">
                    <x-form.country-dropdown :title="__('Select Your Country')" :id="'country_id'"/>
                </div>
                @if(moduleExists('CoinPaymentGateway'))
                @else
                <div class="single-flex-input">
                    <x-form.state-dropdown :title="__('Select Your State')" :id="'state_id'"/>
                    <x-form.city-dropdown :title="__('Select Your City')" :id="'city_id'"/>
                </div>
                @endif
                <div class="single-flex-input">
                    <div class="single-flex-input">
                        <div class="single-input">
                            <label class="label-title">{{ __('Select Experience Level') }}</label>
                            <select name="level" id="level" class="form-control">
                                <option value="">{{ __('Select') }}</option>
                                <option value="junior" @if(Auth::guard('web')->check() && Auth::guard('web')->user()->experience_level == 'junior') selected @endif>{{ __('Junior') }}</option>
                                <option value="midlevel"  @if(Auth::guard('web')->check() && Auth::guard('web')->user()->experience_level == 'midLevel') selected @endif>{{ __('Mid Level') }}</option>
                                <option value="senior"  @if(Auth::guard('web')->check() && Auth::guard('web')->user()->experience_level == 'senior') selected @endif>{{ __('Senior') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="popup-contents-btn flex-btn profile-border-top justify-content-end">
                <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger color-one popup-close"> <i class="las la-arrow-left"></i> {{__('Cancel')}} </a>
                <button type="submit" class="btn-profile btn-bg-1">{{ __('Update Profile') }}</button>
            </div>
        </div>
    </div>
</form>
</div>
<!-- Profile Settings Popup Ends -->
