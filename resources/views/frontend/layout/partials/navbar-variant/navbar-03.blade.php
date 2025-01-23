<?php
$current_url = url()->current();
$root_url = url('/');
$contains = Str::of($current_url)->contains($root_url.'/jobs');
if($contains == $root_url.'/jobs') {
    //if project disable show job categories as default
    if(get_static_option('project_enable_disable') != 'disable'){
        $jobs_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status', '1')->where('selected_category',1)->get();
    }
    //if project disable show job categories as default end
}
else{
    $all_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status','1')->where('selected_category',1)->get();
}
?>


<!-- Menu area Starts -->
<nav class="navbar navbar-area navbar-four navbar-expand-lg">
    <div class="container nav-container">
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#xilancer_menu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="xilancer_menu">
            <ul class="navbar-nav">
                @if(moduleExists('CoinPaymentGateway'))
                    @if(get_static_option('category_section_enable_disable') != 'disable')

                        @if(!empty($jobs_categories))
                            <li class="menu-item-has-children current-menu-item">
                                <a href="javascript:void(0)">{{ __('Categories') }}</a>
                                <ul class="sub-menu">
                                    @foreach($jobs_categories as $category)
                                        <li><li>
                                        <li class="menu-item-has-children position-static">
                                            <a href="{{ route('category.jobs',$category->slug) }}"> {{ $category->category }} </a>
                                            <ul class="sub-mega-menu">
                                                @foreach($category->sub_categories as $sub_category)
                                                    @if($sub_category->jobs())
                                                        <li><a href="{{ route('subcategory.jobs',$sub_category->slug) }}">{{ $sub_category->sub_category }}</a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                        @if(get_static_option('project_enable_disable') == 'disable')
                            {{--if project disable show job categories as default--}}
                            @php
                                $jobs_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status', '1')->whereHas('jobs')->get();
                            @endphp
                            @if(!empty($jobs_categories))
                                    <li class="menu-item-has-children current-menu-item">
                                        <a href="javascript:void(0)">{{ __('Categories') }}</a>
                                        <ul class="sub-menu">
                                            @foreach($jobs_categories as $category)
                                                <li><li>
                                                <li class="menu-item-has-children position-static">
                                                    <a href="{{ route('category.jobs',$category->slug) }}"> {{ $category->category }} </a>
                                                    <ul class="sub-mega-menu">
                                                        @foreach($category->sub_categories as $sub_category)
                                                            @if($sub_category->jobs())
                                                                <li><a href="{{ route('subcategory.jobs',$sub_category->slug) }}">{{ $sub_category->sub_category }}</a></li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                            @endif
                            {{--if project disable show job categories as default end --}}
                        @else
                            @if(!empty($all_categories))

                                    <li class="menu-item-has-children current-menu-item">
                                        <a href="javascript:void(0)">{{ __('Categories') }}</a>
                                        <ul class="sub-menu">
                                            @foreach($all_categories as $category)
                                                <li class="menu-item-has-children position-static">
                                                    <a href="{{ route('category.projects',$category->slug) }}"> {{ $category->category }} </a>
                                                    <ul class="sub-mega-menu">
                                                        @foreach($category->sub_categories as $sub_category)
                                                                <li><a href="{{ route('subcategory.projects',$sub_category->slug) }}">{{ $sub_category->sub_category }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                            @endif
                        @endif

                    @endif
                @endif
                {!! render_frontend_menu($primary_menu) !!}
            </ul>
        </div>
        <x-frontend.user-menu-for-nav-03 />
    </div>
</nav>
<!-- Menu area end -->