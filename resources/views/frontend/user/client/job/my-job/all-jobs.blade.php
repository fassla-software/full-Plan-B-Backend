@extends('frontend.layout.master')
@section('site_title',__('All Jobs'))
@section('style')
    <x-summernote.summernote-css/>
    <x-select2.select2-css/>
    <style>
        .disabled-link {
            background-color: #ccc !important;
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection
@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('My Jobs')" :innerTitle="__('My Jobs')"/>
        <!-- Profile Details area Starts -->
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row gy-4 justify-content-center">
                    <div class="@if(get_static_option('project_enable_disable') != 'disable') col-xl-8 col-lg-9 @else col-12 @endif">
                        <div class="profile-wrapper sticky_top_lg">
                            @include('frontend.user.client.job.my-job.header')
                            <div class="search_result">
                                @include('frontend.user.client.job.my-job.search-result')
                            </div>
                        </div>
                    </div>
                    @if(get_static_option('project_enable_disable') != 'disable')
                    <div class="col-xl-4 col-lg-7">
                        <div class="profile-details-widget sticky_top_lg">
                            <div class="file-wrapper-item-flex flex-between align-items-center profile-border-bottom">
                                <h4 class="profile-wrapper-item-title"> {{ __('Project Catalogues') }} </h4>
                                <a href="{{ route('projects.all') }}" class="profile-wrapper-item-browse-btn"> {{ __('Browse All ') }}</a>
                            </div>
                            @if($top_projects->count() > 0)
                                @foreach($top_projects as $project)
                                    <div class="project-category-item radius-10">
                                        <div class="single-project project-catalogue">
                                            <div class="single-project-thumb">
                                                <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}">
                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'project/'. $project->image, load_from: $project->load_from ?? '') }}" alt="{{ $project->title ?? '' }}">
                                                    @else
                                                        <img src="{{ asset('assets/uploads/project/'.$project->image) ?? '' }}" alt="{{ $project->title ?? '' }}">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="single-project-content">
                                                <div class="single-project-content-top align-items-center flex-between">
                                                    {!! project_rating($project->id) !!}
                                                </div>
                                                <h4 class="single-project-content-title">
                                                    <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}"> {{ $project->title }} </a>
                                                </h4>
                                            </div>
                                            <div class="single-project-bottom flex-between">
                                                <span class="single-project-content-price">
                                                    @if($project->basic_discount_charge)
                                                        {{ float_amount_with_currency_symbol($project->basic_discount_charge) }}
                                                        <s>{{ float_amount_with_currency_symbol($project->basic_regular_charge) }}</s>
                                                    @else
                                                        {{ float_amount_with_currency_symbol($project->basic_regular_charge) }}
                                                    @endif
                                                </span>
                                                <div class="single-project-delivery">
                                                    <span class="single-project-delivery-icon"> <i class="fa-regular fa-clock"></i>{{ __('Delivery') }}</span>
                                                    <span class="single-project-delivery-days"> {{ $project->basic_delivery }} </span>
                                                </div>
                                            </div>
                                            <div class="project-category-item-bottom profile-border-top">
                                                <div class="project-category-item-bottom-flex flex-between align-items-center">
                                                    <div class="project-category-right-flex flex-btn">
                                                        <x-frontend.bookmark :identity="$project->id" :type="'project'" />
                                                    </div>
                                                    <div class="project-category-item-btn flex-btn">
                                                        @if(moduleExists('SecurityManage'))
                                                            @if(Auth::guard('web')->user()->freeze_order_create == 'freeze')
                                                                <a href="#" class="btn-profile btn-outline-1 @if(Auth::guard('web')->user()->freeze_order_create == 'freeze') disabled-link @endif"> {{ __('Order Now') }} </a>
                                                            @else
                                                                <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}" class="btn-profile btn-outline-1"> {{ __('Order Now') }} </a>
                                                            @endif
                                                        @else
                                                            <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}" class="btn-profile btn-outline-1"> {{ __('Order Now') }} </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                           @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Profile Details area end -->
    </main>
@endsection

@section('script')
    <x-summernote.summernote-js/>
    <x-select2.select2-js/>
    <x-sweet-alert.sweet-alert2-js/>
    <script>
        let mainPageUrl = {href: window.location.href};
    </script>

    @include('frontend.user.client.job.my-job.all-jobs-js')
@endsection
