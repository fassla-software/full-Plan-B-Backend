<div class="modal fade" id="userPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Change Password') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" id="userPasswordModalForm">
                <input type="hidden" name="user_id_for_change_password" id="user_id_for_change_password" value="">
                @csrf
                <div class="modal-body">

                    <x-notice.general-notice :description="__('User will get his password via an automated email once password has changed.')" />

                    <span class="email_send_message d-none"></span>
                    <x-form.password :title="__('Enter new password')" :name="'password'" :id="'password'" :class="'form-control'" :placeholder="__('Enter New password')"/>
                    <x-form.password :title="__('Confirm new password')" :name="'confirm_password'" :id="'confirm_password'" :class="'form-control'" :placeholder="__('Confirm new password')"/>
                    <span id="new_password_match"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Change Password')" :class="'btn btn-primary mt-4 pr-4 pl-4 change_user_password'" />
                </div>
            </form>
        </div>
    </div>
</div>

