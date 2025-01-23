@extends('frontend.layout.master')
@section('site_title',__('Identity Verification'))
@section('style')
    <x-select2.select2-css/>
@endsection
@section('content')
        <main>
            <x-breadcrumb.user-profile-breadcrumb :title="__('Identity Verification')" :innerTitle="__('Identity Verification')"/>
            <!-- Profile Settings area Starts -->
            <div class="responsive-overlay"></div>
            <div class="profile-settings-area pat-100 pab-100 section-bg-2">
                <div class="container">
                    <div class="row g-4">
                        @include('frontend.user.layout.partials.sidebar')
                        <div class="col-xl-9 col-lg-8">
                        @if(Auth::guard('web')->user()->user_verified_status == 1 && Auth::guard('web')->user()->user_type == 1)
                            <div class="single-profile-settings">
                                <div class="identity-verification verify">
                                    <div class="identity-verification-flex">
                                        <div class="identity-verification-contents">
                                            <div class="identity-verification-contents-flex">
                                                <div class="identity-verification-contents-icon">
                                                    <i class="fa-solid fa-check"></i>
                                                </div>
                                                <div class="identity-verification-contents-details">
                                                    <h5 class="identity-verification-contents-details-title">{{ __('Your identity is verified') }}</h5>
                                                    <p class="identity-verification-contents-details-para mt-2">{{ __('Your identity has been verified by our team.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="profile-settings-wrapper" id="display_client_identity_verification">
                                <div class="single-profile-settings">
                                    <form id="submit_client_verify_info" enctype="multipart/form-data">
                                        @csrf
                                        <div class="identity-verifying">
                                            @if(isset($user_identity) && $user_identity->status == 2)
                                                <h4 class="identity-verifying-title mb-3">
                                                    {{ __('Identity Verification') }}
                                                    <small class="btn btn-sm btn-danger">{{__('Failed')}}</small>
                                                </h4>
                                                <x-notice.general-notice :description="__('Please resubmit your identity details with proper information so that we can verify it\'s you.')" />
                                            @else
                                                @if(isset($user_identity))
                                                    <h4 class="identity-verifying-title mb-3">
                                                        {{ __('Identity Verification') }}
                                                        <small class="btn btn-sm btn-danger">{{__('Pending')}}</small>
                                                    </h4>
                                                    <x-notice.general-notice :description="__('Please wait. we will notify by email whether you verified or not. Multiple request may delay your verification.')" />
                                                @endif
                                            @endif
                                            <h4 class="identity-verifying-title mb-3">{{ __('Identity Verification') }}</h4>
                                            <p class="identity-verifying-para mt-2">{{ __('Please choose to submit any of the government-issued documents listed below. User general infos are common for any documents submitted.') }}</p>
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
                                                @if(moduleExists('CoinPaymentGateway'))
                                                @else
                                                    <div class="single-flex-input">
                                                        <x-form.state-dropdown :title="__('State')" :name="'state'" :id="'state'"/>
                                                        <x-form.city-dropdown :title="__('City (optional)')" :name="'city'" :id="'city'"/>
                                                    </div>
                                                @endif
                                                <x-form.text :title="__('Address')" :type="'text'" :name="'address'" :id="'address'" :value="$user_identity->address ?? old('address')" :placeholder="__('Enter address')" :class="'form--control'" />
                                                @if(moduleExists('CoinPaymentGateway'))
                                                @else
                                                    <x-form.text :title="__('Zip Code')" :type="'text'" :name="'zipcode'" :id="'zipcode'" :value="$user_identity->zipcode ?? old('zipcode')" :placeholder="__('Enter zip code')" :class="'form--control'" />
                                                @endif
                                                <x-form.text :title="__('National ID number')" :type="'text'" :name="'national_id_number'" :id="'national_id_number'" :value="$user_identity->national_id_number ?? old('national_id_number')" :placeholder="__('Enter id number')" :class="'form--control'" />

                                                <div class="identity-verifying-upload d-grid gap-4 mt-4">

                                                    <div class="photo-uploaded photo-uploaded-padding center-text">
                                                        @if(!empty($user_identity))
                                                            <img class="front_image" src="{{ asset('assets/uploads/verification/'.$user_identity->front_image) }}">
                                                        @endif
                                                        <img src="" class="front_image_preview">
                                                        <div class="mt-4">
                                                            <span class="photo-uploaded-icon"> <i class="fa-solid fa-upload"></i> </span>
                                                            <p class="photo-uploaded-para mt-3"> {{ __('Upload Front side of your ID') }}
                                                                <br> <small>{{__('Recommended Dimensions 500x300 px')}}</small> </p>
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
                                                                <br> <small>{{__('Recommended Dimensions 500x300 px')}}</small></p>
                                                            <input type="file" name="back_image" id="back_image" class="photo-uploaded-file back_image_upload">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-wrapper profile-border-top flex-btn justify-content-end">
                                                <x-btn.submit :title="__('Submit')" :class="'btn-profile btn-bg-1 verification_load_spinner'" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profile Settings area end -->
        </main>

@endsection

@section('script')
    <x-select2.select2-js />
    @include('frontend.user.client.identity.verification-js')
@endsection
