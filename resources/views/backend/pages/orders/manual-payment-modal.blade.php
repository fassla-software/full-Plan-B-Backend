<!-- Country Edit Modal -->
<div class="modal fade" id="editPaymentGatewayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Complete Payment Status') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.order.update.manual.payment')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_id" id="order_id">
                <input type="hidden" name="user_firstname" id="user_firstname">
                <input type="hidden" name="user_email" id="user_email">

                <div class="modal-body">
                    <h4 class="pb-2">{{ __('Customer Details') }}</h4>
                    <p class="pb-1" >{{ __('Full Name:') }} <span class="user_fullname"></span></p>
                    <p class="pb-1" >{{ __('Email:') }} <span class="user_email"></span></p>
                    <p class="pb-1" >{{ __('Order Price:') }} <span class="order_price"></span></p>
                    <p class="manual_payment_image_display">{{ __('Payment Image:') }}
                        <img class="manual_payment_img" src="" alt="{{ __('Manual Payment Image') }}">
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_category'" />
                </div>
            </form>
        </div>
    </div>
</div>
