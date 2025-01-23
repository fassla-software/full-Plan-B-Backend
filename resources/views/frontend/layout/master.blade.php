@include('frontend.layout.partials.header')
@include('frontend.layout.partials.preloader')
@include('frontend.layout.partials.navbar')

@if (!empty($page_post) && $page_post->breadcrumb_status == 'on')
    <div class="banner-inner-area border-top pat-20 pab-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-inner-contents">
                        <ul class="inner-menu">
                            <li class="list"><a href="{{ url('/') }}">{{ __('Home') }} </a></li>
                            <li class="list"> {{ $page_post->title ?? '' }} </li>
                        </ul>
                        <h2 class="banner-inner-title"> {{ $page_post->title ?? '' }} @yield('inner-title')</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@yield('content')
@include('frontend.layout.partials.footer')
