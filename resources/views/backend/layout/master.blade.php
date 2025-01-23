@include('backend.layout.partials.header')
@include('backend.layout.partials.preloader')
<!-- Dashboard area Starts -->
<div class="body-overlay"></div>
<div class="dashboard-area section-bg-2">
    <div class="container-fluid p-0">
        <div class="dashboard__contents__wrapper">
            <div class="dashboard__icon">
                <div class="dashboard__icon__bars sidebar-icon">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>
            @include('backend.layout.partials.left-sidebar')
            <div class="dashboard__right">
                <div class="dashboard__inner">
                    @include('backend.layout.partials.top-header')
                    @yield('content')
                    @include('backend.layout.partials.copyright')
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard area end -->

@include('backend.layout.partials.footer')
