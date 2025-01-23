@if(get_static_option('project_enable_disable') != 'disable')
    <!-- Project area starts -->
    <section class="project-area pat-50 pab-50" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
        <div class="container">

            <div class="section-title text-left append-flex">
                <h2 class="title"> {{ $title ?? __('Top Projects') }} </h2>
                <div class="d-flex flex-column gap-2 align-items-end">
                    <div>
                        @if(!empty($view_all_link) && !empty($view_all_link))
                            <a href="{{ $view_all_link }}" class="view-all-projects-jobbs">{{ $view_all_text }}</a>
                        @endif
                    </div>
                    <div class="append-project"></div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="global-slick-init project-slider nav-style-one slider-inner-margin" data-rtl="{{get_user_lang_direction() == 'rtl' ? 'true' : 'false'}}" data-appendArrows=".append-project" data-arrows="true" data-infinite="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                         data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>
                        @if($items <= 1)
                            @foreach($top_projects as $project)
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="project-item wow fadeInUp" data-wow-delay=".1s">
                                            <div class="single-project radius-10">
                                                <div class="single-project-thumb">
                                                    <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}">
                                                        <img src="{{ asset('assets/uploads/project/'.$project->image) ?? '' }}" alt="{{ $project->title ?? '' }}">
                                                    </a>
                                                </div>
                                                <div class="single-project-content">

                                                    <div class="single-project-content-top align-items-center flex-between">
                                                        {!! project_rating($project->id) !!}
                                                    </div>

                                                    <h4 class="single-project-content-title"> <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}">{{ $project->title }} </a> </h4>
                                                </div>
                                                <div class="single-project-bottom flex-between">
                                <span class="single-project-content-price">
                                    @if($project->basic_discount_charge)
                                        {{ float_amount_with_currency_symbol($project->basic_discount_charge) }}
                                        <s>{{ float_amount_with_currency_symbol($project->basic_regular_charge) }}</s>
                                    @else
                                        {{ float_amount_with_currency_symbol($project->basic_regular_charge) }}
                                    @endif
                                </span>
                                                    <div class="single-project-delivery">
                                                        <span class="single-project-delivery-icon"> <i class="fa-regular fa-clock"></i> {{ __('Delivery') }} </span>
                                                        <span class="single-project-delivery-days"> {{ $project->basic_delivery }}  </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach($top_projects as $project)
                                <div class="project-item wow fadeInUp" data-wow-delay=".1s">
                                    <div class="single-project radius-10">
                                        <div class="single-project-thumb">
                                            <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}">
                                                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'project/'.$project->image, load_from: $project->load_from) }}" alt="{{ $project->title ?? '' }}">
                                                @else
                                                    <img src="{{ asset('assets/uploads/project/'.$project->image) ?? '' }}" alt="{{ $project->title ?? '' }}">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="single-project-content">

                                            <div class="single-project-content-top align-items-center flex-between">
                                                {!! project_rating($project->id) !!}
                                            </div>

                                            <h4 class="single-project-content-title"> <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}">{{ $project->title }} </a> </h4>
                                        </div>
                                        <div class="single-project-bottom flex-between">
                                <span class="single-project-content-price">
                                    @if($project->basic_discount_charge)
                                        {{ float_amount_with_currency_symbol($project->basic_discount_charge) }}
                                        <s>{{ float_amount_with_currency_symbol($project->basic_regular_charge) }}</s>
                                    @else
                                        {{ float_amount_with_currency_symbol($project->basic_regular_charge) }}
                                    @endif
                                </span>
                                            <div class="single-project-delivery">
                                                <span class="single-project-delivery-icon"> <i class="fa-regular fa-clock"></i> {{ __('Delivery') }} </span>
                                                <span class="single-project-delivery-days"> {{ $project->basic_delivery }}  </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Project area end -->
@endif