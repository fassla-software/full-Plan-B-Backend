@extends('frontend.layout.master')
@section('site_title',__('Blogs'))
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
        <x-breadcrumb.user-profile-breadcrumb :title=" __('All Blogs')" :innerTitle=" __('All Blogs') ?? '' "/>
        <!-- Project preview area Starts -->
        <div class="preview-area section-bg-2 pat-100 pab-100">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="categoryWrap-wrapper">
                            <div class="row g-4">
                                <div class="col-xl-8 col-lg-8">
                                    <div class="shop-contents-wrapper-right search_result">
                                        @include('blog::frontend.blogs.search-result')
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4">
                                    @include('blog::frontend.blogs.sidebar')
                                </div>
                            </div>
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
