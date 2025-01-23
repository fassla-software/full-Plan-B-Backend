<!-- About area starts -->
<section class="about-area section-bg-2 pat-100 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg == 'rgb(201, 38, 38)' ? '#F5F5F5' : $section_bg}}">
    <div class="container">
        <div class="row g-4 justify-content-between">
            <div class="col-xxl-6 col-lg-6">
                <div class="about-wrapper-left">
                    <div class="section-title text-left">
                        <h2 class="title">{{ $title ?? __('About Us') }}</h2>
                        <p class="section-para">{!! $description ?? ''  !!}</p>
                    </div>
                        <div class="about-counter mt-5">
                            @foreach($repeater_data['title_'] ?? [] as $key=> $title)
                                <div class="about-counter-item">
                                    <h3 class="about-counter-item-title">
                                        <span class="about-counter-item-title-heading">{{ $title ?? '' }}</span>
                                    </h3>
                                    <p class="about-counter-item-para">{{$repeater_data['description_'][$key] ?? ''  }}</p>
                                </div>
                            @endforeach
                        </div>
                </div>
            </div>
            <div class="col-xxl-6 col-lg-6">
                <div class="about-wrapper-right">
                    <div class="about-wrapper-thumb">
                        <div class="about-wrapper-thumb-item">
                                                        {!! render_image_markup_by_attachment_id($image) ?? '' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About area end -->