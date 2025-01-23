<div class="navbar-right-content show-nav-content">
    <div class="single-right-content">
        <div class="navbar-right-flex">
            <div class="navbar-right-item position-relative">
                <a href="#0" class="navbar-right-chat search-header-open">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                <div class="header-global-search">
                    <div class="header-global-search-header">
                        <h5 class="header-global-search-title">{{ __('Search') }}</h5>
                        <div class="header-global-search-close search-close"><i class="fa-solid fa-times"></i>
                        </div>
                    </div>
                    <div class="header-global-search-input d-flex align-items-center">
                        <div class="header-global-search-input-inner">
                            <div class="header-global-search-input-inner-icon" id="header_search_load_spinner">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" id="search_your_desired_job" class="form-control" placeholder="{{ __('Search') }}" autocomplete="off">
                            <div class="header-global-search-select">
                                <select id="Select_project_or_job_for_search">
                                    @if(get_static_option('project_enable_disable') != 'disable')
                                        <option value="project">{{ __('Project') }}</option>
                                    @endif
                                    @if(get_static_option('job_enable_disable') != 'disable')
                                        <option value="job">{{ __('Job') }}</option>
                                    @endif
                                    <option value="talent">{{ __('Talent') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="display_search_result"></div>
                </div>
                <div class="search-overlay"></div>
            </div>
            <div class="navbar-right-btn">
                @if(moduleExists('Community'))
                <a href="{{ route('community.all') }}"
                   class="cmn-btn btn-outline-1 color-one btn-small radius-10">{{ __('Community') }}
                </a>
                @endif
                <a href="{{ route('user.login') }}"
                   class="cmn-btn btn-outline-1 color-one btn-small radius-10">{{ __('Log In') }}
                </a>
            </div>
            <div class="btn-wrapper">
                <a href="{{ route('user.register') }}"
                   class="cmn-btn btn-bg-1 btn-small radius-10">{{ __('Sign Up') }}
                </a>
            </div>
        </div>
    </div>
</div>