<!-- Country Edit Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Add New Ticket') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.ticket')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <x-form.text :title="__('Title')" :type="__('text')" :name="'title'" :id="'title'" :placeholder="__('Enter ticket title')"/>
                    <div class="single-input mb-3">
                        <label for="priority" class="label-title">{{ __('Department') }}</label>
                        <select name="department" id="department" class="form-control">
                            <option value="">{{ __('Select Department') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="single-input mb-3">
                        <label for="priority" class="label-title">{{ __('Priority') }}</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="">{{ __('Select Priority') }}</option>
                            <option value="High">{{ __('High') }}</option>
                            <option value="Medium">{{ __('Medium') }}</option>
                            <option value="Low">{{ __('Low') }}</option>
                        </select>
                    </div>
                    <div class="single-input mb-3">
                        <label for="priority" class="label-title">{{ __('Select User') }}</label>
                        <select name="user" id="user" class="form-control">
                            <option value="">{{ __('Select User') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->fullname }} ({{ $user->username }})</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form.summernote :title="__('Description')" :name="'description'" :id="'description'" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Save')" :class="'btn btn-primary mt-4 pr-4 pl-4 add_ticket'" />
                </div>
            </form>
        </div>
    </div>
</div>
