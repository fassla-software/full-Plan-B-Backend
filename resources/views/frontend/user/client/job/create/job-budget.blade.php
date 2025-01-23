<!-- Budget, Skills Start -->
<div class="setup-wrapper-contents">
    <div class="setup-wrapper-contents-item">
        <div class="setup-bank-form">
            <div class="setup-bank-form-item">
                <label class="label-title">{{ __('Job type') }}</label>
                <select class="form-control" name="type" id="type">
                    <option value="fixed" selected>{{ __('Fixed-Price (Pay a fixed amount for the job)') }}</option>
                    @if(moduleExists('HourlyJob'))
                    <option value="hourly">{{ __('Hourly Rate (Pay based on total hours worked for the job)') }}</option>
                    @endif
                </select>
            </div>
            @if(moduleExists('HourlyJob'))
            <div class="setup-bank-form-item setup-bank-form-item-icon d-none manage-hourly-jobs">
                <label class="label-title">{{ __('Hourly Rate') }}</label>
                <input type="number" class="form--control" name="hourly_rate" onkeyup="setTimeout(() => { if (this.value === '' || this.value <= 0) this.value = 1; if (this.value > 100000) this.value = 100000; }, 1500);" id="hourly_rate" placeholder="{{ __('Enter Hourly Rate') }}">
                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
            </div>
            <div class="setup-bank-form-item d-none manage-hourly-jobs">
                <label class="label-title">{{ __('Estimated Hours') }}</label>
                <input type="number" class="form--control" name="estimated_hours" onkeyup="setTimeout(() => { if (this.value === '' || this.value <= 0) this.value = 1; if (this.value > 100000) this.value = 100000; }, 1500);" id="estimated_hours" placeholder="{{ __('Enter Estimated Hours') }}">
            </div>
            @endif
            <div class="setup-bank-form-item setup-bank-form-item-icon manage-fixed-jobs">
                <label class="label-title">{{ __('Enter Budget') }}</label>
                <input type="number" class="form--control" name="budget" id="budget" value="0" placeholder="{{ __('Enter Your Budget') }}">
                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
            </div>
            <x-form.skill-dropdown :title="__('Select Skill')" :name="'skill[]'" :id="'skill'" :class="'form-control skill_select2'" />
            <div class="setup-bank-form-item">
                <label class="photo-uploaded center-text w-100">
                    <div class="photo-uploaded-flex d-flex uploadImage">
                        <div class="photo-uploaded-icon"><i class="fa-solid fa-paperclip"></i></div>
                        <span class="photo-uploaded-para">{{ __('Add attachments') }}</span>
                    </div>
                    <input class="photo-uploaded-file inputTag" type="file" name="attachment" id="attachment">
                </label>
                @if(get_static_option('file_extensions'))
                    <p class="mt-2">
                        {{ __('Supported files:') }} {{ implode(', ', json_decode(get_static_option('file_extensions'), true)) }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Budget, Skills Ends -->
