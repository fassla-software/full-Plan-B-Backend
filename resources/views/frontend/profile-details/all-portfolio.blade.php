<div class="profile-details-widget-single radius-10">
    <div class="profile-wrapper-item-flex flex-between align-items-center profile-border-bottom">
        <h4 class="profile-wrapper-item-title"> {{ __('Portfolio') }} </h4>
        @if (Auth::guard('web')->check() &&
                Auth::guard('web')->user()->user_type == 2 &&
                Auth::guard('web')->user()->username == $username)
            <div class="profile-wrapper-item-plus add-portfolio-click add_portfolio_show_hide">
                <i class="fas fa-plus"></i>
            </div>
        @endif
        @if (Auth::guard('web')->check() &&
                Auth::guard('web')->user()->user_type == 1 &&
                Auth::guard('web')->user()->username == $username && Session::get('user_role') == 'freelancer')
            <div class="profile-wrapper-item-plus add-portfolio-click add_portfolio_show_hide">
                <i class="fas fa-plus"></i>
            </div>
        @endif
    </div>
    <div class="profile-details-widget-portfolio-row portfolio_details_display">
        @foreach ($portfolios as $portfolio)
            <div class="profile-details-widget-portfolio-col click-portfolio view_portfolio_details"
                data-id="{{ $portfolio->id }}">
                <div class="profile-details-portfolio ">
                    <div class="profile-details-portfolio-thumb">
                        <a href="#/">
                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                <img src="{{ render_frontend_cloud_image_if_module_exists( 'portfolio/'.$portfolio->image, load_from: $portfolio->load_from) }}" alt="{{ __('portfolio') }}">
                            @else
                                <img src="{{ asset('assets/uploads/portfolio/' . $portfolio->image) }}" alt="{{ __('portfolio') }}">
                            @endif
                        </a>
                    </div>
                    <div class="profile-details-portfolio-content mt-3">
                        <h5 class="profile-details-portfolio-content-title d-flex justify-content-between">
                            <a href="#/">{{ $portfolio->title }}</a>
                        </h5>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <p class="profile-details-portfolio-content-para">
                                {{ $portfolio->created_at->toFormattedDateString() ?? '' }} </p>
                            <a href="#/" class="btn-profile btn-outline-1 btn-small">{{ __('Details') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
