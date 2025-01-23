@php
    if(!isset($page_post)){https://bitbucket.org/xgenious/widget-builder-laravel/src/master/
        return;
    }
@endphp


@if($page_post->layout === 'normal_layout' || $page_post->layout === 'home_page_layout' || $page_post->layout === 'home_page_layout_two')
    {!! \plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page',$page_post->id) !!}
@endif
@if($page_post->layout === 'home_page_layout')
    <div class="d-none" data-padding-top="100">
        <div class="container {{$page_post->page_class}}">
            <div class="row">
                <div class="col-lg-8 {{$page_post->left_column}}">
                    {!! \plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id) !!}
                </div>

                <div class="col-md-6 col-lg-4 {{$page_post->right_column}} widg ">
                    <div class="widget-area-wrapper custom-margin-widget @if(get_static_option('site_frontend_dark_mode') === 'on')   dark-version  @endif style-{{$page_post->widget_style}}" data-padding-bottom="100">
                        {!! render_frontend_sidebar($page_post->sidebar_layout,['column' => false]) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endif

@if($page_post->layout === 'home_page_layout_two')
    <div class="blog-list-area-wrapper index-01" data-padding-top="100">
        <div class="container {{$page_post->page_class}}">
            <div class="row">
                <div class="col-lg-8 {{$page_post->left_column}}">
                    {!! \plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id) !!}
                </div>

                <div class="col-md-6 col-lg-4 {{$page_post->right_column}} widg">
                    <div class="widget-area-wrapper style-{{$page_post->widget_style}}">
                        {!! render_frontend_sidebar($page_post->sidebar_layout,['column' => false]) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="container-fluid">
            <div class="col-lg-12">
                {!! \plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar_two',$page_post->id) !!}
            </div>
        </div>
    </div>
@endif



@if($page_post->layout === 'sidebar_layout')
    <div class="blog-list-area-wrapper index-01">
        <div class="container {{$page_post->page_class}}">
            <div class="row">
                <div class="col-lg-8 col-xl-9">
                    {!! \plugins\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page_with_sidebar',$page_post->id) !!}
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 widg">
                    <div class="widget-area-wrapper style-02">
                        {!! render_frontend_sidebar($page_post->sidebar_layout,['column' => false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


