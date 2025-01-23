<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Add New Type') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.subscription.type.all')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <x-form.text :title="__('Type')" :type="__('text')" :name="'type'" :id="'type'" :value="old('type', '')" :placeholder="__('Enter subscription type')"/>
                    <x-form.number :title="__('Validity')" :min="7" :max="365" :name="'validity'" :id="'validity'" :divClass="'mb-0'" :value="old('validity', '')" :placeholder="__('Enter validity')"/>
                    <p class="text-info">{{__('Validity must be a number between 7 to 365 days')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Save')" :class="'btn btn-primary mt-4 pr-4 pl-4 add_type'" />
                </div>
            </form>
        </div>
    </div>
</div>
