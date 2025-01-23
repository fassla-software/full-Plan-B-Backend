<div class="credit-area">
    <div class="container">
        <div class="credit-wrapper border-bottom pat-50 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
            <div class="row g-4">
                @foreach($repeater_data['title_'] as $key=> $title)
                    <div class="col-lg-4 col-sm-6">
                        <div class="credit-item text-center">
                            <h3 class="credit-item-title">
                                <span class="credit-item-title-heading">{{ $title ?? '' }}</span>
                            </h3>
                            <p class="credit-item-para"> {{$repeater_data['description_'][$key] ?? ''  }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>