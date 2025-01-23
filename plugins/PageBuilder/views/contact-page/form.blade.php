<div class="contact-area section-bg-2 pat-100 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
            <div class="row gy-5 justify-content-between flex-column-reverse flex-lg-row">
                <div class="col-lg-4">
                    <div class="contact-info">
                        <h4 class="contact-question-top-title">{{ $contact_info_heading ?? __('Contact Info') }}</h4>
                        <p class="contact-question-top-para mt-2">{{ $contact_info_des ?? '' }}</p>
                        <div class="contact-info-inner mt-4">
                            @foreach($repeater_data['icon_'] ?? [] as $key=> $icon)
                                <div class="contact-info-item">
                                <div class="contact-info-item-flex">
                                    <div class="contact-info-item-icon"><i class="{{ $icon }}"></i></div>
                                    <div class="contact-info-item-contents">
                                        <h6 class="contact-info-item-title">{{ $repeater_data['title_'][$key] ?? '' }}</h6>
                                        <p class="contact-info-item-para"><span>{{ $repeater_data['description_'][$key] ?? '' }}</span></p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="contact-question">
                        <h4 class="contact-question-top-title">{{ $heading ?? '' }}</h4>
                        <p class="contact-question-top-para mt-2">{{ $contact_form_des ?? '' }}</p>
                        <div class="contact-question-search contact-question-search-padding mt-4">
                            <x-validation.error />
                            <div class="contact-question-search-form custom-form mt-4">
                                {!! $form_details !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
