<!-- Country Edit Modal -->
<div class="modal fade" id="editFeedbackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Feedback') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reviewForm" action="{{route('admin.category.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="feedback_id" id="feedback_id" value="">
                <div class="modal-body">

                    <div class="error-message"></div>

                    <x-form.text :title="__('Title')" :type="__('text')" :name="'title'" :id="'title'" :value="''" :placeholder="__('Enter feedback name')"/>
                    <x-form.textarea :title="__('Description')" :name="'description'" :id="'description'" :value="''" :placeholder="__('Enter description')"/>
                    <x-form.text :title="__('Rating')" :type="__('number')" :name="'rating'" :id="'rating'" :value="''" :placeholder="__('Enter rating')"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_rating'" />
                </div>
            </form>
        </div>
    </div>
</div>
