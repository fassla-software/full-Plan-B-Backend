<header class="header-style-01">
    <!-- Menu area Starts -->
    <nav class="navbar navbar-area navbar-expand-lg" @if(get_static_option('sticky_menu') == 'enable') id="navigation" @endif>
        <div class="container bg-white nav-container">
            <div class="logo-wrapper">
                <a href="{{ route('homepage') }}" class="logo">
                    @if(!empty(get_static_option('site_logo')))
                        {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                    @else
                        <img src="{{ asset('assets/static/img/logo/logo.png') }}" alt="site-logo">
                    @endif
                </a>
            </div>
            <div class="responsive-mobile-menu d-lg-none">
                <a href="javascript:void(0)" class="click-nav-right-icon">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#xilancer_menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="xilancer_menu">
                <ul class="navbar-nav">
                    {!! render_frontend_menu($primary_menu) !!}
                </ul>
            </div>

            <x-frontend.user-menu />

        </div>
    </nav>
    @if(request()->routeIs('homepage'))
        <x-frontend.category.category />
    @endif
    <!-- Menu area end -->
</header>
