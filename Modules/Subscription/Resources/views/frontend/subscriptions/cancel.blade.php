@extends('frontend.layout.master')
@section('site_title',__('Payment Cancel'))
@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Payment Cancel')" :innerTitle="__('Payment Cancel')"/>
        <div class="responsive-overlay"></div>
        <div class="congratulation-area section-bg-2 pat-100 pab-100">
            <div class="container">
                <div class="congratulation-wrapper">
                    <div class="congratulation-contents center-text">
                        <div class="congratulation-contents-icon bg-danger wow  zoomIn animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">
                            <i class="fas fa-times"></i>
                        </div>
                        <h4 class="congratulation-contents-title"> {{ __('OPPS!') }} </h4>
                        <p class="congratulation-contents-para">{{ __('Payment') }} <strong>{{ __('Cancel') }}</strong> </p>
                        <div class="btn-wrapper mt-4">
                            <a href="{{ route('client.wallet.history') }}" class="btn-profile btn-bg-1">{{ __('Back to Subscription') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection



