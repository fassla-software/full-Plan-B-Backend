<div class="modal fade" id="IndividualCommissionSettingsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Individual Commission Settings') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.user.individual.commission.settings') }}" id="IndividualSettingsModalForm" method="post">
                <input type="hidden" name="user_id_for_individual_settings" id="user_id_for_individual_settings" value="">
                @csrf
                <div class="modal-body">
                    <x-notice.general-notice :description="__('Notice: Individual Commission Settings means how much percentage will admin get per completed order for the selected user. Generally admin will get 25 percent each order if he/she don\'t set any global/individual commission.')" />
                    <div class="single-input mt-5 mb-3">
                        <label class="label-title">{{ __('Commission Type') }}</label>
                        <select name="admin_commission_type" id="admin_commission_type" class="form-control">
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="percentage">{{ __('Percentage') }}</option>
                            <option value="fixed">{{ __('Fixed') }}</option>
                        </select>
                    </div>
                    <x-form.number :title="__('Commission Charge')" :min="'1'" :max="'500'" :name="'admin_commission_charge'" :id="'admin_commission_charge'" :placeholder="__('Commission Charge')"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 admin_individual_settings_for_user'" />
                </div>
            </form>
        </div>
    </div>
</div>

