<!-- About Numbers area starts -->
<section class="aboutNumber-area about-bg-2 pat-100 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="row g-4 justify-content-between align-items-center">
            <div class="col-xl-5 col-lg-5 col-md-12">
                <div class="aboutNumber-item">
                    <div class="section-title text-left">
                        <h2 class="title">{{ $title ?? _('Our Last 3 Years in Numbers') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-lg-7 col-md-12">
                <div class="row g-4">
                    @foreach($repeater_data['image_'] ?? [] as $key=> $image)
                        <div class="col-xxl-4 col-lg-6 col-sm-6">
                            <div class="aboutNumber-item">
                                <div class="aboutNumber-item-icon">
                                    {!! render_image_markup_by_attachment_id($image) ?? '' !!}
                                </div>
                                <div class="aboutNumber-item-contents mt-3">
                                    <h6 class="aboutNumber-item-title">{{ $repeater_data['subtitle_'][$key] }}</h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Numbers area end -->