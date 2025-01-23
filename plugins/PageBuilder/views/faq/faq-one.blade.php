<!-- Question area starts -->
<section class="question-area pat-100 pab-50" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="faq-question faq-question-padding sticky-top">
                    <div class="section-title">
                        <h2 class="title"> {{ $title ?? __('Frequently Asked Question') }}  </h2>
                        <p class="section-para"> {{ $subtitle ?? '' }}  </p>
                    </div>
                    <div class="btn-wrapper mt-5">
                        <a href="#/" class="cmn-btn btn-bg-1" data-bs-toggle="modal" data-bs-target="#questionModal"> {{ __('Submit Question') }}
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                    @if(!empty($image))
                    <div class="faq-question-submit mt-5">
                        <div class="faq-question-submit-thumb">
                            {!! render_image_markup_by_attachment_id($image ?? '') !!}
                        </div>
                        <span class="faq-question-submit-icon">
                                <svg width="68" height="90" viewBox="0 0 68 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path class="faq-svg-path" d="M29.5 85C29.5 87.4853 31.5147 89.5 34 89.5C36.4853 89.5 38.5 87.4853 38.5 85L29.5 85ZM37.182 1.81802C35.4246 0.0606613 32.5754 0.0606613 30.818 1.81802L2.1802 30.4558C0.422837 32.2132 0.422837 35.0624 2.1802 36.8198C3.93755 38.5772 6.7868 38.5772 8.54416 36.8198L34 11.364L59.4558 36.8198C61.2132 38.5772 64.0624 38.5772 65.8198 36.8198C67.5772 35.0624 67.5772 32.2132 65.8198 30.4558L37.182 1.81802ZM38.5 85L38.5 5L29.5 5L29.5 85L38.5 85Z" fill="#344054"/>
                                </svg>
                            </span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-7">
                <div class="faq-wrapper">
                    <div class="faq-contents">
                        @foreach($repeater_data['title_'] as $key=> $title)
                            <div class="faq-item wow {{get_user_lang_direction() == 'rtl' ? 'fadeInRight' : 'fadeInLeft'}}" data-wow-delay=".2s">
                                <h3 class="faq-title"> {{ $title ?? '' }}</h3>
                                <div class="faq-panel">
                                    <p class="faq-para"> {{$repeater_data['description_'][$key]  }} </p>
                                </div>
                            </div>
                        @endforeach
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Question area end -->

<!-- Modal -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Submit Question') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('faq.question') }}" method="post" id="ask_your_question">
                @csrf
                <div class="modal-body">
                    <div class="error-message"></div>
                    <div class="single-input">
                        <input type="text" name="question" class="form--control" placeholder="{{ __('Enter Question') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary ask_you_question">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>