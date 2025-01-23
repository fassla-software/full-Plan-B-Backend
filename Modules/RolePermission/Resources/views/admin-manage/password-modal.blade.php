<div class="modal fade" id="adminPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Change Password') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.password.change') }}" method="post" id="userPasswordModalForm">
                <input type="hidden" name="admin_id_for_change_password" id="admin_id_for_change_password" value="">
                @csrf
                <div class="modal-body">
                    <x-form.password :title="__('Enter new password')" :name="'password'" :id="'password'" :class="'form-control'" :placeholder="__('Enter New password')"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Change Password')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_admin_password'" />
                </div>
            </form>
        </div>
    </div>
</div>

