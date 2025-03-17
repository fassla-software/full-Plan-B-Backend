<!-- State Edit Modal -->
<div class="modal fade" id="offerDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-edit d-flex gap-2">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Offer Details') }} </h1>
                    <a class="offer_info_edit btn btn-sm btn-primary" href=""><i class="fas fa-pencil"></i></a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body" id="offer_details">
                <div class="userDetails__wrapper">
                    <p class="userDetails__wrapper__item"><strong>{{ __('Presenter\'s Namee: ') }}</strong> <span
                            class="user_name"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Email: ') }}</strong> <span
                            class="user_email"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Request Name: ') }}</strong> <span
                            class="request_name"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Subcategory: ') }}</strong><span
                            class="subcategory"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Price: ') }}</strong><span
                            class="price"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Other Terms: ') }}</strong><span
                            class="other_terms"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Offer Ends At: ') }}</strong><span
                            class="ends_at"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Status: ') }}</strong><span
                            class="is_seen"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Offer Location: ') }}</strong><span
                            class="offer_location"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mt-4"
                    data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
