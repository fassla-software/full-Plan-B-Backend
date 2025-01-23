@extends('frontend.layout.master')
@section('site_title',__('2 Factor Authentication'))
@section('style')
    <style>
        .single-profile-settings:not(:first-child) {
            margin-top: 0px;
        }
    </style>
@endsection

@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('2 Factor Authentication')" :innerTitle="__('2 Factor Authentication')"/>
        <!-- Password Settings area Starts -->
        <div class="responsive-overlay"></div>
        <div class="profile-settings-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    @include('frontend.user.layout.partials.sidebar')
                    <div class="col-xl-9 col-lg-8">
                        <div class="profile-settings-wrapper">
                            <div class="single-profile-settings">
                                <h4 class="mb-5"> {{__('Two Factor Authentication Settings')}} </h4>
                                @if($user->google_2fa_secret == null)
                                    <form method="POST" action="{{ route('client._2fa.enable.disable') }}">
                                        @csrf
                                        <input type="hidden" name="form_type" value="generate_secret_key">
                                        <div class="btn-wrapper margin-top-50">
                                            <x-btn.submit :title="__('Generate Secret Key to Enable 2FA')" :class="'btn-profile btn-bg-1'" />
                                        </div>
                                    </form>
                                @elseif($user->google_2fa_enable_disable_disable == 0)
                                    {{ __('1. Scan this QR code with your Google Authenticator App. Alternatively, you can use the code:') }} <strong>{{ $secret_key }}</strong><br>
                                    <!-- QR code img  -->
                                     <!-- QR code img  -->
                                    @if(str_contains($google_2fa_url,'data:image/png;base64'))
                                        <img src="{{$google_2fa_url}}"> <br>
                                    @else
                                        {!! $google_2fa_url !!}
                                    @endif 
                                    <br><br>
                                    {{ __('2. Enter the pin from Google Authenticator app:') }}<br><br>
                                    <form method="POST" action="{{ route('client._2fa.enable.disable') }}">
                                        @csrf
                                        <input type="hidden" name="form_type" value="enable_2fa">
                                        <x-form.text :title="__('Authenticator Code')" :type="'number'" :name="'secret'" :id="'secret'" :class="'form-control'" :placeholder="__('Type code')" />
                                        <div class="btn-wrapper profile-border-top flex-btn justify-content-end">
                                            <x-btn.submit :title="__('Enable 2FA')" :class="'btn-profile btn-bg-1 check_enable_2fa'" />
                                        </div>
                                    </form>

                                @elseif($user->google_2fa_enable_disable_disable == 1)
                                    <div class="alert alert-success">{{ __('2FA is currently') }} <strong>{{ __('enabled') }}</strong> {{ __('on your account.') }}</div>

                                    <h4 class="mt-5 mb-2"> {{__('Want to Disable Two Factor Authentication ?')}} </h4>
                                    <form method="POST" action="{{ route('client._2fa.enable.disable') }}">
                                        @csrf
                                        <x-form.password :type="'password'" :name="'current-password'" :id="'current_password'" :class="'form-control'" :placeholder="__('Enter current password')" />
                                        <div class="btn-wrapper profile-border-top flex-btn justify-content-end">
                                            <x-btn.submit :title="__('Disable 2FA')" :class="'btn-profile btn-bg-1 check_disable_2fa check_disable_2fa'" />
                                        </div>
                                    </form>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Password Settings area end -->
    </main>
@endsection

@section('script')
    <x-select2.select2-js />
    @include('frontend.user.client._2fa.-2fa-js')
@endsection
