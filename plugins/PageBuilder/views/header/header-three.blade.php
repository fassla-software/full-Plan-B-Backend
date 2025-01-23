@if(moduleExists('CoinPaymentGateway'))
<style>
    .playvid-btn-wraper {
        text-align: center;
        .playvid-btn {
            margin-left: auto;
            margin-right: auto;
            color: #fff;
            height: 70px;
            width: 70px;
            border-radius: 50%;
            background: #3A394A;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size:30px;
        }
    }
</style>

<!-- Banner area Starts -->
<div class="banner-area banner-area-four section-bg-base">
{{--    <video loop muted plays-inline class="back-video" id="back_video">--}}
    <video loop autoplay muted plays-inline class="back-video">
        <source src="{{ $background_video }}" type="video/mp4">
    </video>
    <div class="container">
        <div class="banner-content-wraper">
            <div class="next-gen">{{ $highlighted_banner_text ?? __('Next Gen. Freelance Platform') }}</div>
            <h1 class="main-heading">{{ $title ?? __('Take your talent to the orbit') }}</h1>
{{--            <div class="playvid-btn-wraper">--}}
{{--                <div class="playvid-btn">--}}
{{--                    <i class="fa-solid fa-play"></i>--}}
{{--                    <i class="fa-solid fa-pause d-none"></i>--}}
{{--                </div>--}}
{{--            </div>--}}
            <p class="pera">{{ $subtitle ?? '' }}</p>
            <div class="btn-wraper">
                <a href="{{ $find_talent_button_link ?? '' }}" class="cmn-btn blue-btn">{{$find_talent_button_text ?? __('Find talent') }} <span><i class="fa-solid fa-arrow-right"></i></span></a>
                <a href="{{ $find_work_button_link ?? '' }}" class="cmn-btn white-btn">{{$find_work_button_text ?? __('Find work') }}</a>
            </div>
        </div>
    </div>
</div>
<!-- Banner area end -->
@endif