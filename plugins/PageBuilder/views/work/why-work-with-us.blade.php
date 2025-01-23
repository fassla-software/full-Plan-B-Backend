<!-- Work area starts -->
<section class="work-area pat-100 pab-50" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="section-title center-text">
            <h2 class="title"> {{ $title ?? __('Why work in our platform?') }}  </h2>
        </div>
        <div class="row gy-4 mt-5">
            @foreach ($repeater_data['image_'] as $key => $data)
                <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInRight" data-wow-delay=".2s">
                <div class="single-work single-work-border radius-10">
                    <div class="single-work-icon">
                        {!! render_image_markup_by_attachment_id($data) !!}
                    </div>
                    <div class="single-work-contents mt-3">
                        <h4 class="single-work-contents-title"> <a href="#/">{{ $repeater_data['title_'][$key] }}</a> </h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Work area end -->