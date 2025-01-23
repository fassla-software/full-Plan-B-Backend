<!-- Country Edit Modal -->
<div class="modal fade" id="editCountryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Country') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.country.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="country_id" id="country_id" value="">
                <div class="modal-body">
                    <div class="single-input mb-3">
                        <label for="title" class="label-title">{{__('Country')}}</label>
                        <input type="text" name="edit_country" id="edit_country" value="{{ old('country') }}" placeholder="{{ __('Enter country') }}" class="form-control" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_country'"/>
                </div>
            </form>
        </div>
    </div>
</div>
