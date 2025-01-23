<div class="single-profile-settings">
    <form id="submit_freelancer_verify_info" method="post" enctype="multipart/form-data">
        @csrf
        <div class="identity-verifying">
            <h4 class="identity-verifying-title">{{ __('Verify your Identity') }}</h4>
            <p class="identity-verifying-para mt-2">{{ __('Please choose to submit any of the government-issued documents listed below.') }}</p>
            <div class="error_msg_container my-1"></div>
            <div class="identity-verifying-form custom-form profile-border-top">
                <div class="identity-verifying-flex">
                    <div class="identity-verifying-list custom-radio active">
                        <div class="identity-verifying-list-flex">
                            <div class="identity-verifying-list-contents">
                                <div class="identity-verifying-list-contents-flex">
                                    <div class="identity-verifying-list-contents-icon">
                                        <i class="fa-solid fa-id-card"></i>
                                    </div>
                                    <div class="identity-verifying-list-contents-details">
                                        <h5 class="identity-verifying-list-contents-details-title">{{ __('National ID Card') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <input type="radio" class="verify-radio" name="verify" checked="">
                        </div>
                    </div>
                    <div class="identity-verifying-list custom-radio">
                        <div class="identity-verifying-list-flex">
                            <div class="identity-verifying-list-contents">
                                <div class="identity-verifying-list-contents-flex">
                                    <div class="identity-verifying-list-contents-icon">
                                        <i class="fa-solid fa-id-card"></i>
                                    </div>
                                    <div class="identity-verifying-list-contents-details">
                                        <h5 class="identity-verifying-list-contents-details-title">{{ __('Driving License') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <input type="radio" class="verify-radio" name="verify">
                        </div>
                    </div>
                    <div class="identity-verifying-list custom-radio">
                        <div class="identity-verifying-list-flex">
                            <div class="identity-verifying-list-contents">
                                <div class="identity-verifying-list-contents-flex">
                                    <div class="identity-verifying-list-contents-icon">
                                        <i class="fa-solid fa-passport"></i>
                                    </div>
                                    <div class="identity-verifying-list-contents-details">
                                        <h5 class="identity-verifying-list-contents-details-title">{{ __('Passport') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <input type="radio" class="verify-radio" name="verify">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="verify_by" id="verify_by" value="National ID Card">
                <x-form.country-dropdown :title="__('ID issuing country')" :name="'country'" :id="'country'"/>
                <div class="single-flex-input">
                    <x-form.state-dropdown :title="__('State')" :name="'state'" :id="'state'"/>
                    <x-form.city-dropdown :title="__('City')" :name="'city'" :id="'city'"/>
                </div>
                <x-form.text :title="__('Address')" :type="'text'" :name="'address'" :id="'address'" :value="$user_identity->address ?? old('address')" :placeholder="__('Enter address')" :class="'form--control'" />
                <x-form.text :title="__('Zip Code')" :type="'text'" :name="'zipcode'" :id="'zipcode'" :value="$user_identity->zipcode ?? old('zipcode')" :placeholder="__('Enter zip code')" :class="'form--control'" />
                <x-form.text :title="__('National ID number')" :type="'number'" :name="'national_id_number'" :id="'national_id_number'" :value="$user_identity->national_id_number ?? old('national_id_number')" :placeholder="__('Enter id number')" :class="'form--control'" />

                <div class="identity-verifying-upload flex-btn mt-4">

                    <div class="photo-uploaded photo-uploaded-padding center-text">
                        @if(!empty($user_identity))
                            <img class="front_image" src="{{ asset('assets/uploads/verification/'.$user_identity->front_image) }}">
                        @endif
                        <img src="" class="front_image_preview">
                        <div class="mt-4">
                            <span class="photo-uploaded-icon"> <i class="fa-solid fa-upload"></i> </span>
                            <p class="photo-uploaded-para mt-3"> {{ __('Upload Front side of your ID') }}
                                <br> <small>{{__('Dimensions must be 500x300 px')}}</small> </p>
                            <input type="file" name="front_image" id="front_image" class="photo-uploaded-file front_image_upload">
                        </div>
                    </div>
                    <div class="photo-uploaded photo-uploaded-padding center-text">
                        @if(!empty($user_identity))
                            <img class="front_image" src="{{ asset('assets/uploads/verification/'.$user_identity->back_image) }}">
                        @endif
                            <img src="" class="back_image_preview">

                        <div class="mt-4">
                            <span class="photo-uploaded-icon"> <i class="fa-solid fa-upload"></i> </span>
                            <p class="photo-uploaded-para mt-3"> {{ __('Upload Back side of your ID') }}
                                <br> <small>{{__('Dimensions must be 500x300 px')}}</small></p>
                            <input type="file" name="back_image" id="back_image" class="photo-uploaded-file back_image_upload">
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-wrapper profile-border-top flex-btn justify-content-end">
                <x-btn.submit :title="__('Submit')" :class="'btn-profile btn-bg-1'" />
            </div>
        </div>
    </form>
</div>
