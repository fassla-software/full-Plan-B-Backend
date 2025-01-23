@if(get_static_option('job_enable_disable') != 'disable')
<!-- Jobs area starts -->
<section class="jobs-area pat-50 pab-100" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
    <div class="container">
        <div class="section-title text-left append-flex">
            <h2 class="title"> {{ $title ?? __('Recent Jobs') }} </h2>
            <div class="append-jobs"></div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="global-slick-init attraction-slider nav-style-one slider-inner-margin"
                     data-rtl="{{get_user_lang_direction() == 'rtl' ? 'true' : 'false'}}"
                    data-appendArrows=".append-jobs" data-arrows="true" data-infinite="true" data-dots="false"
                    data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="false" data-autoplaySpeed="2500"
                    data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                    data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>'
                    data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1} }]'>

                    @foreach ($jobs as $job)
                        <div class="jobs-item">
                            <div class="single-jobs radius-10">
                                <h4 class="single-jobs-title"> <a
                                            href="{{ route('job.details', ['username' => $job->job_creator?->username, 'slug' => $job->slug]) }}">
                                        {{ $job->title }} </a> </h4>
                                <p class="single-jobs-date">
                                    {{ $job->created_at->toFormattedDateString() ?? '' }} -
                                    <span>{{ ucfirst($job->level) ?? '' }}</span>
                                </p>

                                <h3 class="single-jobs-price">
                                    {{ float_amount_with_currency_symbol($job->budget) }}
                                    <span class="single-jobs-price-fixed">{{ __(ucfirst($job->type)) }}</span>
                                </h3>
                                <p class="single-jobs-para mt-4">
                                    {!! Str::limit(strip_tags($job->description), 90) !!} </p>
                                <div class="single-jobs-tag mt-4">
                                    @foreach ($job->job_skills as $skill)
                                        <a href="{{ route('skill.jobs', $skill->skill) }}" class="single-jobs-tag-link">
                                            {{ $skill->skill ?? '' }} </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Jobs area end -->
@endif
