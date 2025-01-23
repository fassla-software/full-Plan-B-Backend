@if(moduleExists('PromoteFreelancer'))
<style>
    .single-freelancer.center-text .single-freelancer-author-name {
        justify-content: center;
    }
    .single-freelancer.center-text .single-freelancer-bottom {
        justify-content: center;
    }
    .single-freelancer {
        flex-direction: column;
        display: flex;
        justify-content: space-between;
        height: 100%;
        background: var(--white);
    }
    .single-freelancer-author{
        position: relative;
    }
    .pro-profile-badge {
        position: absolute;
        right: -10px;
        top: -10px;
        border-radius:20px;
        background: #FAF5FF;
        color: #9e4cf4;
        font-weight: 600;
    }
    .pro-icon-background {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #9e4cf4;
        padding: 3px;
        border-radius: 50%;
        color: #fff;
        font-size: 12px;
    }
</style>
    <!-- Project area starts -->
    <section class="project-area pat-50 pab-50" data-padding-top="{{$padding_top ?? ''}}" data-padding-bottom="{{$padding_bottom ?? ''}}" style="background-color:{{$section_bg ?? ''}}">
        <div class="container">
            <div class="section-title text-left append-flex">
                <h2 class="title"> {{ $title ?? __('Promoted Projects') }} </h2>
                <div class="append-project"></div>
            </div>
            <div class="row g-4">
                <div class="categoryWrap-wrapper-item">
                    <div class="global-slick-init project-slider nav-style-one slider-inner-margin" data-rtl="{{get_user_lang_direction() == 'rtl' ? 'true' : 'false'}}" data-appendArrows=".append-project" data-arrows="true" data-infinite="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                         data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>
                        @php $current_date = \Carbon\Carbon::now()->toDateTimeString() @endphp
                        @foreach ($talents as $talent)
                            <div class="col-xxl-4 col-md-6">
                                <div class="single-freelancer center-text radius-20">
                                    <div class="single-freelancer-author">
                                        @if(moduleExists('PromoteFreelancer'))
                                            @if($talent->pro_expire_date >= $current_date  && $talent->is_pro === 'yes')
                                                <div class="single-project-content-review pro-profile-badge">
                                                    <div class="pro-icon-background">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <small>{{ __('Pro') }}</small>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="single-freelancer-author-thumb mb-2">
                                            @if ($talent->image)
                                                <a href="{{ route('freelancer.profile.details', $talent->username) }}">
                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $talent->image, load_from: $talent->load_from) }}" alt="{{ $talent->first_name }}">
                                                    @else
                                                        <img src="{{ asset('assets/uploads/profile/' . $talent->image) }}"
                                                             alt="{{ $talent->first_name }}">
                                                    @endif
                                                </a>
                                                @if(moduleExists('FreelancerLevel'))
                                                    <div class="freelancer-level-badge">
                                                        {!! freelancer_level($talent->id,'talent') ?? '' !!}
                                                    </div>
                                                @endif
                                            @else
                                                <a href="{{ route('freelancer.profile.details', $talent->username) }}">
                                                    <img src="{{ asset('assets/static/img/author/author.jpg') }}"
                                                         alt="{{ __('AuthorImg') }}">
                                                </a>
                                                @if(moduleExists('FreelancerLevel'))
                                                    <div class="freelancer-level-badge">
                                                        {!! freelancer_level($talent->id,'talent') ?? '' !!}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <x-status.user-active-inactive-check :userID="$talent->id" />
                                        <h4 class="single-freelancer-author-name mt-2">
                                            <a href="{{ route('freelancer.profile.details', $talent->username) }}">
                                                {{ $talent->full_name }}
                                                @if($talent->user_verified_status == 1) <i class="fas fa-circle-check"></i>@endif
                                            </a>
                                        </h4>
                                        <span class="single-freelancer-author-para mt-2">
                                        {{ $talent?->user_introduction?->title ?? '' }}
                                    </span>
                                        {!! freelancer_rating_for_profile_details_page($talent->id) !!}
                                    </div>
                                    <div class="single-freelancer-bottom">
                                        <div class="btn-wrapper">
                                            <a href="{{ route('freelancer.profile.details', $talent->username) }}" class="cmn-btn btn-bg-gray btn-small w-100 radius-5"> {{ __('View Profile') }} </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Project area end -->
@endif