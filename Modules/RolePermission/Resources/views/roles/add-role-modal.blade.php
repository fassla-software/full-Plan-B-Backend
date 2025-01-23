<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Add New Role') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.role.create') }}" method="post" id="userPasswordModalForm">
                @csrf
                <div class="modal-body">
                    <x-form.text :title="__('Enter Role Name')" :type="'text'" :name="'role'" :class="'form-control'" :placeholder="__('Enter role name')"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Create Role')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                </div>
            </form>
        </div>
    </div>
</div>

