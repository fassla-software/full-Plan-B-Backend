<!-- State Edit Modal -->
<div class="modal fade" id="operatinoDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-edit d-flex gap-2">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Operation Details') }} </h1>
                    <a class="operation_info_edit btn btn-sm btn-primary" href=""><i
                            class="fas fa-pencil"></i></a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body" id="operation_details">
                <div class="operationDetails__wrapper">
                    <p class="operationDetails__wrapper__item"><strong>{{ __('Operation: ') }}</strong> <span
                            class="operation_type"></span></p>

                    <p class="operationDetails__wrapper__item"><strong>{{ __('Category: ') }}</strong> <span
                            class="category_slug"></span></p>

                    <p class="operationDetails__wrapper__item"><strong>{{ __('Cost: ') }}</strong> <span
                            class="cost"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mt-4"
                    data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
