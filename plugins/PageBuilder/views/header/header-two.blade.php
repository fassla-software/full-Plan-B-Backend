<!-- Banner area Starts -->
<div class="banner-area banner-area-three section-bg-gradient" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}">
    <div class="banner-shapes-bg">
        {!! render_image_markup_by_attachment_id($background_image) !!}
    </div>
    <div class="container">
        <div class="row gy-4 justify-content-center align-items-center">
            <div class="col-lg-8">
                <div class="banner-single">
                    <div class="banner-single-content center-text">
                        <h1 class="banner-single-content-title">{{ $title }}</h1>
                        <p class="banner-single-content-para">{{ $subtitle }}</p>
                        <div class="btn-wrapper flex-btn mt-5">
                                <a href="{{ $find_work_button_link  ?? '' }}" class="cmn-btn btn-bg-secondary"> {{$find_work_button_text ?? __('Find Work') }} </a>
                                <a href="{{ $find_project_button_link ?? '' }}" class="cmn-btn btn-outline-1 color-one"> {{$find_project_button_text ?? __('Find Project') }} </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="banner-wrapper">
                    <div class="banner-wrapper-left">
                        <div class="banner-wrapper-thumb">
                            @if($freelancer_image)
                                {!! render_image_markup_by_attachment_id($freelancer_image) !!}
                            @else
                                <img src="{{ asset('assets/static/img/freelancer.png') }}" alt="{{ __('Freelancer Image') }}">
                            @endif
                        </div>
                        <div class="banner-wrapper-project">
                            <div class="banner-wrapper-project-flex">
                                <div class="banner-wrapper-project-icon">
                                    {!! render_image_markup_by_attachment_id($light_image) !!}
                                </div>
                                <div class="banner-wrapper-project-content">
                                    @if($freelancer_order_count > 0)
                                        <span class="banner-wrapper-project-content-title"> {{ __('Completed') }}
                                            <strong>{{ $freelancer_order_count }}</strong> {{ __('Projects') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-wrapper-right">
                        <div class="banner-wrapper-thumb">
                            @if($client_image)
                                {!! render_image_markup_by_attachment_id($client_image) !!}
                            @else
                                <img src="{{ asset('assets/static/img/client.png') }}" alt="{{ __('Client Image') }}">
                            @endif
                        </div>
                        <div class="banner-wrapper-project">
                            <div class="banner-wrapper-project-flex">
                                <div class="banner-wrapper-project-icon">
                                    {!! render_image_markup_by_attachment_id($talent_image) !!}

                                </div>
                                <div class="banner-wrapper-project-content">
                                    @if($client_order_count > 0)
                                        <span class="banner-wrapper-project-content-title">{{ __('Hired') }}
                                            <strong>{{ $client_order_count }}+</strong>
                                            {{ __('Talents') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-wrapper-line">
                        <div class="banner-wrapper-line-shape">
                            {!! render_image_markup_by_attachment_id($shape_image_one) !!}
                        </div>
                        <div class="banner-wrapper-line-fav">
                            {!! render_image_markup_by_attachment_id($shape_image_two) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner area end -->