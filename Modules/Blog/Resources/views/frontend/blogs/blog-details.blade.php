@extends('frontend.layout.master')
@section('site_title')
    {{ $blog_details->title ?? __('Blog Details') }}
@endsection

@if(isset($blog_details?->meta_data->meta_title) && !empty($blog_details?->meta_data->meta_title))
    @section('meta_title', $blog_details->meta_data->meta_title)
@endif

@if(isset($blog_details?->meta_data->meta_description) && !empty($blog_details?->meta_data->meta_description))
    @section('meta_description', $blog_details->meta_data->meta_description)
@endif

@section('style')
    <style>
        .jobFilter-about-clients.active {
            border-color: var(--main-color-one);
            color: var(--main-color-one);
        }
        .jobFilter-about-clients.active .jobFilter-about-clients-para {
            color: var(--main-color-one);
        }
        .single-shop-left-title .blog-category-title {
            font-size: 20px;
        }
    </style>
@endsection
@section('content')
    <main>
        @if(moduleExists('CoinPaymentGateway'))@else<x-frontend.category.category/>@endif
        <x-breadcrumb.user-profile-breadcrumb :title="__('Blog Details')" :innerTitle="__('Blog Details')" />
        <!-- Project preview area Starts -->
        <div class="preview-area section-bg-2 pat-100 pab-100">
            <div class="container">
                <div class="row g-4">
                    <div class="col-xl-8 col-lg-8 search_result">
                        <div class="project-preview">
                            <div class="project-preview-thumb">
                                {!! render_image_markup_by_attachment_id($blog_details->image) !!}
                            </div>
                            <div class="project-preview-contents mt-4">
                                <h1 class="project-preview-contents-title mt-3"> {{ $blog_details->title }} </h1>
                                <p class="project-preview-contents-para"> {!! $blog_details->content !!} </p>
                            </div>
                        </div>
                        <div class="project-preview">
                            <div class="myJob-wrapper-single-flex flex-between align-items-center">
                                <div class="myJob-wrapper-single-contents">
                                    <div class="row g-4">
                                        <h4>{{ __('Related Blogs') }}</h4>
                                        @foreach($related_blogs as $blog)
                                            <div class="col-xxl-6">
                                                <div class="project-category-item radius-10">
                                                    <div class="single-project project-catalogue">
                                                        <div class="single-project-thumb">
                                                            <a href="{{ route('blog.details',$blog->slug) }}">
                                                                {!! render_image_markup_by_attachment_id($blog->image) !!}
                                                            </a>
                                                        </div>
                                                        <div class="single-project-content">
                                                            <h4 class="single-project-content-title">
                                                                <a href="{{ route('blog.details',$blog->slug) }}"> {{ $blog->title }} </a>
                                                            </h4>
                                                        </div>
                                                        <div class="project-category-item-bottom profile-border-top">
                                                            <div class="project-category-item-bottom-flex flex-between align-items-center">
                                                                <div class="project-category-right-flex flex-btn">
                                                                    <p>{{ $blog->created_at->toFormattedDateString() }}</p>
                                                                </div>
                                                                <div class="project-category-item-btn flex-btn">
                                                                    <a href="{{ route('blog.details',$blog->slug) }}" class="btn-profile btn-outline-1"> {{ __('View Details') }} </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-xl-4 col-lg-4">
                        <div class="sticky-sidebar">
                           @include('blog::frontend.blogs.sidebar')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Project preview area end -->
    </main>

@endsection

@section('script')
    @include('blog::frontend.blogs.blog-js')
@endsection
