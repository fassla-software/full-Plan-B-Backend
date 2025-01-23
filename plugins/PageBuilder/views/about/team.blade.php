<!-- About Team area starts -->
<section class="aboutTeam-area pat-100 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="section-title text-left append-flex">
            <h2 class="title">{{ $title ?? __('Meet our hardworking team') }}</h2>
            <div class="append-team"></div>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-lg-12">
                <div class="global-slick-init attraction-slider nav-style-one slider-inner-margin"
                     data-rtl="{{get_user_lang_direction() == 'rtl' ? 'true' : ''}}"
                     data-appendArrows=".append-team" data-arrows="true" data-infinite="true" data-dots="false"
                     data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="false" data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>'
                     data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>
                    @foreach($repeater_data['image_'] ?? [] as $key=> $image)
                        <div class="slider-item">
                            <div class="aboutTeam-item">
                                <div class="aboutTeam-item-thumb">
                                    {!! render_image_markup_by_attachment_id($image) ?? '' !!}
                                </div>
                                <div class="aboutTeam-item-contents mt-3">
                                    <h6 class="aboutTeam-item-title">{{ $repeater_data['name_'][$key] ?? '' }}</h6>
                                    <p class="aboutTeam-item-para">{{ $repeater_data['designation_'][$key] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Team area end -->