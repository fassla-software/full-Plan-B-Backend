<style>
    .rating-wrap .ratings span.show-rating {
        width: 100% !important;
    }
    .rating_profile_details {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    [data-star] {
        text-align:left;
        font-style:normal;
        display:inline-block;
        position: relative;
        unicode-bidi: bidi-override;
    }
    [data-star]::before {
        display:block;
        content: "\f005" "\f005" "\f005" "\f005" "\f005";
        width: 100%;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 15px;;
        color: var(--body-color);
    }
    [data-star]::after {
        white-space:nowrap;
        position:absolute;
        top:0;
        left:0;
        content: "\f005" "\f005" "\f005" "\f005" "\f005";
        width: 100%;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 15px;;
        width: 0;
        color: var(--secondary-color);
        overflow:hidden;
        height:100%;
    }

    [data-star^="0.1"]::after{width:2%}
    [data-star^="0.2"]::after{width:4%}
    [data-star^="0.3"]::after{width:6%}
    [data-star^="0.4"]::after{width:8%}
    [data-star^="0.5"]::after{width:10%}
    [data-star^="0.6"]::after{width:12%}
    [data-star^="0.7"]::after{width:14%}
    [data-star^="0.8"]::after{width:16%}
    [data-star^="0.9"]::after{width:18%}
    [data-star^="1"]::after{width:20%}
    [data-star^="1.1"]::after{width:22%}
    [data-star^="1.2"]::after{width:24%}
    [data-star^="1.3"]::after{width:26%}
    [data-star^="1.4"]::after{width:28%}
    [data-star^="1.5"]::after{width:30%}
    [data-star^="1.6"]::after{width:32%}
    [data-star^="1.7"]::after{width:34%}
    [data-star^="1.8"]::after{width:36%}
    [data-star^="1.9"]::after{width:38%}
    [data-star^="2"]::after{width:40%}
    [data-star^="2.1"]::after{width:42%}
    [data-star^="2.2"]::after{width:44%}
    [data-star^="2.3"]::after{width:46%}
    [data-star^="2.4"]::after{width:48%}
    [data-star^="2.5"]::after{width:50%}
    [data-star^="2.6"]::after{width:52%}
    [data-star^="2.7"]::after{width:54%}
    [data-star^="2.8"]::after{width:56%}
    [data-star^="2.9"]::after{width:58%}
    [data-star^="3"]::after{width:60%}
    [data-star^="3.1"]::after{width:62%}
    [data-star^="3.2"]::after{width:64%}
    [data-star^="3.3"]::after{width:66%}
    [data-star^="3.4"]::after{width:68%}
    [data-star^="3.5"]::after{width:70%}
    [data-star^="3.6"]::after{width:72%}
    [data-star^="3.7"]::after{width:74%}
    [data-star^="3.8"]::after{width:76%}
    [data-star^="3.9"]::after{width:78%}
    [data-star^="4"]::after{width:80%}
    [data-star^="4.1"]::after{width:82%}
    [data-star^="4.2"]::after{width:84%}
    [data-star^="4.3"]::after{width:86%}
    [data-star^="4.4"]::after{width:88%}
    [data-star^="4.5"]::after{width:90%}
    [data-star^="4.6"]::after{width:92%}
    [data-star^="4.7"]::after{width:94%}
    [data-star^="4.8"]::after{width:96%}
    [data-star^="4.9"]::after{width:98%}
    [data-star^="5"]::after{width:100%}
</style>

<!-- Testimonial area starts -->
<section class="testimonial-area section-bg-1 pat-100 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="testimonial-shapes">
        <img src="{{ asset('assets/static/img/testimonial/testimonial1.svg') }}" alt="{{ __('shapes') }}">
        <img src="{{ asset('assets/static/img/testimonial/testimonial2.svg') }}" alt="{{ __('shapes') }}">
        <img src="{{ asset('assets/static/img/testimonial/testimonial3.svg') }}" alt="{{ __('shapes') }}">
        <img src="{{ asset('assets/static/img/testimonial/testimonial4.svg') }}" alt="{{ __('shapes') }}">
        <img src="{{ asset('assets/static/img/testimonial/testimonial5.svg') }}" alt="{{ __('shapes') }}">
    </div>
    <div class="container">
        <div class="section-title center-text">
            <h2 class="title">{{ $title ?? __('What Freelancers are Thinking About Us')}}  </h2>
        </div>
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="global-slick-init attraction-slider nav-style-one slider-inner-margin" data-rtl="{{get_user_lang_direction() == 'rtl' ? 'true' : ''}}" data-appendArrows=".append-testimonial" data-infinite="true" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>' data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1} }]'>
                    @if($items <= 1)
                        @foreach($feedbacks as $feedback)
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="testimonial-item">
                                        <div class="single-testimonial bg-white radius-10">
                                            <div class="single-testimonial-top">
                                                <h4 class="single-testimonial-title"> {{ ucfirst($feedback->title) }}</h4>
                                                <span class="single-testimonial-date"> {{ $feedback->created_at->toFormattedDateString() }} </span>
                                            </div>
                                            <div class="rating-wrap mt-2">
                                                <div class="ratings">
                                                    <i data-star="{{ $feedback->rating }}"></i>
                                                </div>
                                            </div>
                                            <p class="single-testimonial-para">{{ $feedback->description }}</p>
                                            <div class="single-testimonial-author">
                                                <div class="single-testimonial-author-flex d-flex flex-wrap gap-3">
                                                    <div class="single-testimonial-author-thumb">
                                                        <a href="{{ route('freelancer.profile.details', $feedback?->user?->username) }}">
                                                            @if(!empty($feedback?->user?->image))
                                                                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'.$feedback?->user?->image, load_from: $feedback?->user?->load_from) }}" alt="{{ __('profile img') }}">
                                                                @else
                                                                    <img src="{{ asset('assets/uploads/profile/'.$feedback?->user?->image) ?? '' }}" alt="{{ __('profile img') }}">
                                                                @endif
                                                            @else
                                                                <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="dashboard-logo">
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="single-testimonial-author-contents flex-fill">
                                                        <h6 class="single-testimonial-author-contents-title"> <a href="{{ route('freelancer.profile.details', $feedback?->user?->username) }}">{{ $feedback?->user?->fullname }}</a> </h6>
                                                        <p class="single-testimonial-author-contents-para">
                                                            {{ $feedback?->user?->user_introduction?->title ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($feedbacks as $feedback)
                            <div class="testimonial-item">
                                <div class="single-testimonial bg-white radius-10">
                                    <div class="single-testimonial-top">
                                        <h4 class="single-testimonial-title"> {{ ucfirst($feedback->title) }} </h4>
                                        <span class="single-testimonial-date"> {{ $feedback->created_at->toFormattedDateString() }} </span>
                                    </div>
                                    <div class="rating-wrap mt-2">
                                        <div class="ratings">
                                            <i data-star="{{ $feedback->rating }}"></i>
                                        </div>
                                    </div>
                                    <p class="single-testimonial-para">{{ $feedback->description }}</p>
                                    <div class="single-testimonial-author">
                                        <div class="single-testimonial-author-flex d-flex flex-wrap gap-3">
                                            <div class="single-testimonial-author-thumb">
                                                <a href="{{ route('freelancer.profile.details', $feedback?->user?->username) }}">
                                                    @if(!empty($feedback?->user?->image))
                                                        @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                            <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'.$feedback?->user?->image, load_from: $feedback?->user?->load_from) }}" alt="{{ __('profile img') }}">
                                                        @else
                                                            <img src="{{ asset('assets/uploads/profile/'.$feedback?->user?->image) ?? '' }}" alt="{{ __('profile img') }}">
                                                        @endif
                                                    @else
                                                        <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="dashboard-logo">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="single-testimonial-author-contents flex-fill">
                                                <h6 class="single-testimonial-author-contents-title"> <a href="{{ route('freelancer.profile.details', $feedback?->user?->username) }}">{{ $feedback?->user?->fullname }}</a> </h6>
                                                <p class="single-testimonial-author-contents-para">
                                                    {{ $feedback?->user?->user_introduction?->title ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
        @if($feedbacks->count() > 0)
        <div class="row mt-5">
            <div class="testimonial-arrows center-text">
                <div class="append-testimonial"> <span> {{ $slider_button_text ?? __('Swipe') }} </span> </div>
            </div>
        </div>
        @endif
    </div>
</section>
<!-- Guest area end -->