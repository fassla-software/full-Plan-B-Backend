<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="LoginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="LoginModalLabel">
                        {{ __('Login to order') }}
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-notice.general-notice :description="__('Notice: You must have login as a client to continue order.')" />
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
                    <div class="d-flex flex-wrap gap-3">
                        <x-btn.submit :title="__('Login')" :class="'btn-profile btn-bg-1 login_to_continue_order'" />
                    </div>
                    <div>
                        <div class="login-bottom-contents">
                            <div class="or-contents mb-3">
                                <span class="or-contents-para"> {{ __("Don't have a client account?") }} </span>
                            </div>
                            <div class="login-others">
                                <div class="login-others-single">
                                    <a href="{{ route('user.register') }}" target="_blank"
                                        class="login-others-single-btn w-100">
                                        <span class="login-para"> {{ __('Register Now') }} </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
