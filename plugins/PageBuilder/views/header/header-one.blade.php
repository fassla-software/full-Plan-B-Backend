<!-- Banner area Starts -->
<div class="banner-area banner-area-padding section-bg-1" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="row gy-5 align-items-center flex-column-reverse flex-lg-row">
            <div class="col-lg-7">
                <div class="banner-single">
                    <div class="banner-single-content">
                        <h1 class="banner-single-content-title"> {{ $title ?? __('Work from anywhere, Get the freedom you deserve') }} </h1>
                        <p class="banner-single-content-para"> {{ $subtitle ?? __('Get hired by great clients and businesses around the world and work independently as you want.') }} </p>

                        <div class="btn-wrapper flex-btn mt-5">
                            <a href="{{ $find_work_button_link ?? '' }}" class="cmn-btn btn-bg-1"> {{$find_work_button_text ?? __('Find Work') }} </a>
                            <a href="{{ $find_project_button_link ?? '' }}" class="cmn-btn btn-outline-1 color-one"> {{$find_project_button_text ?? __('Find Project') }} </a>
                        </div>
                        <div class="banner-single-content-logo mt-5">
                            <h5 class="banner-single-content-logo-title"> {{ __('Trusted by:') }} </h5>
                            <ul class="banner-single-content-logo-list list-style-none my-4">
                                @foreach ($repeater_data['logo_'] as $key => $logo)
                                    <li class="banner-single-content-logo-list-item">
                                        <span class="banner-single-content-logo-list-link">
                                            {!! render_image_markup_by_attachment_id($logo) ?? '' !!}
                                        </span>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="banner-right-content">
                    @if($slider_image)
                        <div class="banner-right-content-thumb wow slideInUp" data-wow-delay=".4s">
                            {!! render_image_markup_by_attachment_id($slider_image) !!}
                        </div>
                    @else
                        <div class="banner-right-content-thumb wow slideInUp" data-wow-delay=".4s">
                            <img src="{{ asset('assets/static/img/banner/banner.png') }}" alt="img">
                        </div>
                    @endif
                    <div class="banner-right-content-shape">
                        {!! render_image_markup_by_attachment_id($shape_image_one) !!}
                        {!! render_image_markup_by_attachment_id($shape_image_two) !!}
                    </div>

                    @if($top_freelancer?->freelancer_orders_count >= 1)
                    <div class="banner-right-content-bottom">
                        <div class="banner-right-content-profile wow fadeIn" data-wow-delay=".4s">
                            <div class="banner-right-content-profile-flex align-items-center d-flex gap-3">
                                <div class="banner-right-content-profile-thumb">
                                    <img src="{{ asset('assets/uploads/profile/'.$top_freelancer->image) }}" alt="{{ __('profile') }}">
                                </div>
                                <div class="banner-right-content-profile-content">
                                    <h6 class="banner-right-content-profile-content-name"> {{ $top_freelancer?->fullname }} </h6>
                                    <p class="banner-right-content-profile-content-para"> {{ $top_freelancer_of_the_month ?? __('Top Freelancer of month') }} </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="banner-right-content-top">
                        <div class="banner-right-content-rating wow zoomIn" data-wow-delay=".3s">
                            <div class="banner-right-content-rating-icon">
                                <img src="{{ asset('assets/static/img/banner/rating.svg') }}" alt="{{ __('rating') }}">
                            </div>
                            <p class="banner-right-content-rating-para"> {{ freelancer_rating($top_freelancer->id, 'header') ?? 4.9 }} {{ __('Ratings') }} </p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner area end -->