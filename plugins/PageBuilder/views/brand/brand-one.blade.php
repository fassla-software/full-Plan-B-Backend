<!-- Brand logo area starts -->
<div class="brand-area pat-50 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="global-slick-init attraction-slider slider-inner-margin" data-rtl="{{get_user_lang_direction() == 'rtl' ? 'true' : ''}}" data-slidesToShow="6" data-infinite="true" data-arrows="false" data-dots="false" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fas fa-angle-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fas fa-angle-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 6}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 4}},{"breakpoint": 768,"settings": {"slidesToShow": 3}},{"breakpoint": 576, "settings": {"slidesToShow": 2} }]'>
                    @foreach ($repeater_data['brand_'] as $key => $logo) {
                        <div class="single-brand">
                            <a href="#/" class="single-brand-thumb">
                                {!! render_image_markup_by_attachment_id($logo) !!}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Brand Logo area end -->
