@if(get_static_option('job_enable_disable') != 'disable')
<!-- Category area starts -->
<section class="category-area pat-50 pab-50" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="section-title center-text">
            <h2 class="title"> {{ $title ?? __('Browse Jobs By Categories') }} </h2>
        </div>
        <div class="row gy-4 mt-4">
            <div class="col-lg-12">
                <div class="global-slick-init nav-style-one slider-inner-margin" data-rtl="{{get_user_lang_direction() == 'rtl' ? 'true' : 'false'}}" data-appendArrows=".append-jobCategory" data-slidesToShow="6" data-infinite="true" data-arrows="true" data-dots="false" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fas fa-arrow-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fas fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 5}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 3}},{"breakpoint": 768,"settings": {"slidesToShow": 2}},{"breakpoint": 480, "settings": {"slidesToShow": 1} }]'>
                    @if($items <= 1)
                        @foreach($job_categories as $category)
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="category-slider-item">
                                        <a href="{{ route('category.jobs',$category->slug) }}">
                                            <div class="single-category center-text radius-10">
                                                <div class="single-category-icon">
                                                    {!! render_image_markup_by_attachment_id($category->image) !!}
                                                </div>
                                                <div class="single-category-contents">
                                                    <h5 class="single-category-contents-title"> {{ $category->category ?? '' }} </h5>
                                                    <span class="single-category-contents-subtitle"> {{ $category->jobs_count ?? '' }} {{ __('Jobs') }} </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($job_categories as $category)
                            <div class="category-slider-item">
                                <a href="{{ route('category.jobs',$category->slug) }}">
                                    <div class="single-category center-text radius-10">
                                        <div class="single-category-icon">
                                            {!! render_image_markup_by_attachment_id($category->image) !!}
                                        </div>
                                        <div class="single-category-contents">
                                            <h5 class="single-category-contents-title"> {{ $category->category ?? '' }} </h5>
                                            <span class="single-category-contents-subtitle"> {{ $category->jobs_count ?? '' }} {{ __('Jobs') }} </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @if($job_categories->count() > 0)
            <div class="row mt-5">
                <div class="testimonial-arrows center-text">
                    <div class="append-jobCategory"> <span> {{ $slider_button_text ?? __('Swipe') }} </span> </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
<!-- Category area end -->
@endif