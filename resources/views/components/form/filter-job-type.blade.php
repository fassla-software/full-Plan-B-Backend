<div class="single-shop-left bg-white radius-10 mt-4">
    <div class="single-shop-left-title open">
        <h5 class="title">{{ __('Job Type') }}</h5>
        <div class="single-shop-left-inner margin-top-15">
            <div class="single-shop-left-select">
                <select class="form-control" name="type" id="type">
                    <option value="">{{ __('Select') }}</option>
                    <option value="fixed">{{ __('Fixed') }}</option>
                    @if(moduleExists('HourlyJob'))
                        <option value="hourly">{{ __('Hourly') }}</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>
