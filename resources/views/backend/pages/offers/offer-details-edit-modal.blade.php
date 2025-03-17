<!-- State Edit Modal -->
<div class="modal fade" id="offerDetailsEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit Offer Info') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.offer.info.edit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="edit_offer_details">
                    <span class="email_send_message d-none"></span>
                    <div class="edit_offer_detailsInfo">
                        <input type="hidden" name="edit_offer_id" id="edit_offer_id" value="">

                        <x-form.text :title="__('Offer Price')" :type="'text'" :name="'edit_offer_price'" :id="'edit_offer_price'"
                            :placeholder="__('Enter Price')" />

                        <x-form.text :title="__('Per')" :type="'text'" :name="'edit_per'" :id="'edit_per'"
                            :placeholder="__('Enter Per')" />

                        <x-form.text :title="__('Offer Location')" :type="'text'" :name="'edit_current_location'" :id="'edit_current_location'"
                            :placeholder="__('Enter Location')" />

                        <x-form.text :title="__('Offer Ends At')" :type="'date'" :name="'edit_ends_at'" :id="'edit_ends_at'" />

                        <x-form.text :title="__('Other Terms')" :type="'text'" :name="'edit_other_terms'" :id="'edit_other_terms'"
                            :placeholder="__('Enter Other Terms')" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_offer_info'" />
                </div>
            </form>
        </div>
    </div>
</div>
