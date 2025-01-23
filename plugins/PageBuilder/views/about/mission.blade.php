<!-- About Mission area starts -->
<section class="aboutMission-area pat-100 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="row g-4 justify-content-between align-items-center">
            <div class="col-xxl-5 col-lg-6">
                <div class="aboutMission-wrapper-left">
                    <div class="aboutMission-wrapper-thumb">
                        {!! render_image_markup_by_attachment_id($image) ?? '' !!}
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-lg-6">
                <div class="aboutMission-wrapper-contents">
                    <div class="section-title text-left">
                        <h2 class="title">{{ $title ?? __('Our Mission') }}</h2>
                        <p class="section-para">{!! $description ?? '' !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Mission area end -->