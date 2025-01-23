<?php
    $current_url = url()->current();
    $root_url = url('/');
    $contains = Str::of($current_url)->contains($root_url.'/jobs');
    if($contains == $root_url.'/jobs') {
        //if project disable show job categories as default
        if(get_static_option('project_enable_disable') != 'disable'){
            $jobs_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status', '1')->whereHas('jobs')->get();
        }
        //if project disable show job categories as default end
    }
    else{
        if(get_static_option('category_section_enable_disable_for_homepage') == 'disable'){
            if($current_url != $root_url){
                $all_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status','1')->whereHas('projects')->get();
            }
        }else{
            $all_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status','1')->whereHas('projects')->get();
        }
   }
?>

@if(get_static_option('category_section_enable_disable') != 'disable')
    @if(!empty($jobs_categories))
        <div class="categorySub-area categorySub-padding border-top bg-white">
            <div class="container custom-container-one">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="categorySub">
                            <div class="categorySub-list nav-horizontal-scroll has-arrows" id="categoryWrap-list">
                                <div class="categorySub-arrow" id="left-arrow"></div>
                                <ul class="categorySub-list-slide" id="categoryslide-list">
                                    @foreach($jobs_categories as $category)
                                        <li class="categorySub-list-slide-list">
                                            <a href="{{ route('category.jobs',$category->slug) }}" class="categorySub-list-slide-link">{{ $category->category }}<span class="mobileIcon"></span></a>
                                            <ul class="categorySub-slide-submenu">
                                                @foreach($category->sub_categories as $sub_category)
                                                    @if($sub_category->jobs())
                                                        <li><a href="{{ route('subcategory.jobs',$sub_category->slug) }}">{{ $sub_category->sub_category }}</a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="categorySub-arrow right-arrow" id="right-arrow"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(get_static_option('project_enable_disable') == 'disable')
        {{--if project disable show job categories as default--}}
        @php
            $jobs_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status', '1')->whereHas('jobs')->get();
        @endphp
        @if(!empty($jobs_categories))
            <div class="categorySub-area categorySub-padding border-top bg-white">
                <div class="container custom-container-one">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="categorySub">
                                <div class="categorySub-list nav-horizontal-scroll has-arrows" id="categoryWrap-list">
                                    <div class="categorySub-arrow" id="left-arrow"></div>
                                    <ul class="categorySub-list-slide" id="categoryslide-list">
                                        @foreach($jobs_categories as $category)
                                            <li class="categorySub-list-slide-list">
                                                <a href="{{ route('category.jobs',$category->slug) }}" class="categorySub-list-slide-link">{{ $category->category }}<span class="mobileIcon"></span></a>
                                                <ul class="categorySub-slide-submenu">
                                                    @foreach($category->sub_categories as $sub_category)
                                                        @if($sub_category->jobs())
                                                            <li><a href="{{ route('subcategory.jobs',$sub_category->slug) }}">{{ $sub_category->sub_category }}</a></li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="categorySub-arrow right-arrow" id="right-arrow"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{--if project disable show job categories as default end --}}
    @else
        @if(!empty($all_categories))
            <div class="categorySub-area categorySub-padding border-top bg-white">
                <div class="container custom-container-one">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="categorySub">
                                <div class="categorySub-list nav-horizontal-scroll has-arrows" id="categoryWrap-list">
                                    <div class="categorySub-arrow" id="left-arrow"></div>
                                    <ul class="categorySub-list-slide" id="categoryslide-list">
                                        @foreach($all_categories as $category)
                                            <li class="categorySub-list-slide-list">
                                                <a href="{{ route('category.projects',$category->slug) }}" class="categorySub-list-slide-link">{{ $category->category }}<span class="mobileIcon"></span></a>
                                                <ul class="categorySub-slide-submenu">
                                                    @foreach($category->sub_categories as $sub_category)
                                                        <li><a href="{{ route('subcategory.projects',$sub_category->slug) }}">{{ $sub_category->sub_category }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="categorySub-arrow right-arrow" id="right-arrow"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif