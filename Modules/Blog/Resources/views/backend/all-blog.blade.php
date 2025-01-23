@extends('backend.layout.master')
@section('title', __('All Blogs'))
@section('style')
    <style>
        .alert-warning {
            border-color: #f2f2f2;
            border-left: 3px solid #e0a800;
            background-color: #f2f2f2;
            color: #333;
            border-radius: 0;
            padding: 5px;
        }
        .alert-success {
            border-color: #f2f2f2;
            border-left: 3px solid #319a31;
            background-color: #f2f2f2;
            color: #333;
            border-radius: 0;
            padding: 5px;
        }
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Blogs') }}</h4>
                            <div class="search_delete_wrapper">
                                <x-search.search-in-table :id="'string_search'" />
                                @can('blog-add')
                                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-md d-flex align-items-center">
                                    <span class="btn_plus_icon me-1">
                                        <i class="fa-solid fa-plus"></i>
                                    </span>
                                    {{__('Add Blog') }}
                                </a>
                                @endcan
                            </div>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('blog::backend.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    <x-select2.select2-js/>
    @include('blog::backend.blog-js')

@endsection
