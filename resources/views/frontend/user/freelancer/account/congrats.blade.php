@extends('frontend.layout.master')
@section('site_title',__('Account Setup Complete'))
@section('content')
    <div class="congratulation-area section-bg-2 pat-100 pab-100">
        <div class="container">
            <div class="congratulation-wrapper">
                <div class="congratulation-contents center-text">
                    <div class="congratulation-contents-icon wow  zoomIn animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">
                        <i class="fas fa-check"></i>
                    </div>
                    <h4 class="congratulation-contents-title"> {{ __('Congratulations!') }} </h4>
                    <p class="congratulation-contents-para">{{ __('Your account setup has successfully completed') }}</p>
                    <div class="btn-wrapper mt-4">
                        <a href="{{ route('freelancer.dashboard') }}" class="btn-profile btn-bg-1">{{ __('Back to Dashboard') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




