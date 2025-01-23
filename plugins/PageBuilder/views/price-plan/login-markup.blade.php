<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="LoginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="subscription_price">
            <div class="modal-content">
                <div class="modal-header">
                    <x-notice.general-notice :description="__('Notice: Please login as a freelancer to buy a subscription.')" />
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="error-message"></div>
                    <div class="single-input">
                        <label class="label-title mb-2">{{ __('Email Or User Name') }}</label>
                        <input class="form--control" type="text" name="username" id="username"
                            placeholder="{{ __('Email Or User Name') }}">
                    </div>
                    <div class="single-input mt-4">
                        <label class="label-title mb-2"> {{ __('Password') }} </label>
                        <div class="single-input-inner">
                            <input class="form--control" type="password" name="password" id="password"
                                placeholder="{{ __('Type Password') }}">
                            <div class="icon toggle-password">
                                <div class="show-icon"> <i class="fas fa-eye-slash"></i> </div>
                                <span class="hide-icon"> <i class="fas fa-eye"></i> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-column">
                    <div>
                        <button type="button" class="btn-profile btn-outline-gray btn-hover-danger"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <x-btn.submit :title="__('Login')" :class="'btn-profile btn-bg-1 login_to_buy_a_subscription'" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
