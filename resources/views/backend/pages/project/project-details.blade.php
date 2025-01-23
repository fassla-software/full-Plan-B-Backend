@extends('backend.layout.master')
@section('title', __('Project Details'))
@section('style')
    <x-select2.select2-css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="customMarkup__single__item">

            <div class="customMarkup__single__inner mt-4">
                <div class="row g-4">
                    <div class="col-xl-7 col-lg-12">
                        <div class="project-preview">
                            <div class="project-preview-thumb">
                                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                    <img src="{{ render_frontend_cloud_image_if_module_exists('project/'.$project->image, load_from: $project->load_from) }}" alt="{{ $project->title ?? '' }}">
                                @else
                                    <img src="{{ asset('assets/uploads/project/' . $project->image) }}" alt="{{ $project->title }}">
                                @endif
                            </div>
                            <div class="project-preview-contents mt-4">
                                <div class="customMarkup__single__item__flex project--rejected--wrapper">
                                    <span class="customMarkup__single__title">{{ __('Status:') }}
                                        @if ($project->status === 0)
                                            <span>{{ __('Pending') }}</span>
                                        @elseif($project->status === 1)
                                            <span>{{ __('Approved') }}</span>
                                        @elseif($project->status === 2)
                                            <span>{{ __('Rejected') }}</span>
                                        @endif
                                    </span>
                                    <span class="customMarkup__single__title">{{ __('Reject:') }}
                                        <span>{{ $project->project_history?->reject_count ?? '0' }}</span>
                                    </span>
                                    <span class="customMarkup__single__title">{{ __('Edit:') }}
                                        <span>{{ $project->project_history?->edit_count ?? '0' }}</span>
                                    </span>
                                </div>
                                <h4 class="project-preview-contents-title mt-3"> {{ $project->title }} </h4>
                                <p class="project-preview-contents-para"> {!! $project->description !!} </p>
                            </div>
                        </div>
                        <div class="project-preview">
                            <div class="myJob-wrapper-single-flex flex-between align-items-center">
                                <div class="myJob-wrapper-single-contents">
                                    <div class="jobFilter-proposal-author-flex">
                                        <div class="jobFilter-proposal-author-thumb">
                                            @if($user->image)
                                                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $user->image, load_from: $user->load_from ?? '') }}" alt="{{ __('profile img') }}">
                                                @else
                                                    <img src="{{ asset('assets/uploads/profile/' . $user->image) }}" alt="{{ $user->first_name }}">
                                                @endif
                                            @else
                                                <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}">
                                            @endif
                                        </div>
                                        <div class="jobFilter-proposal-author-contents">
                                            <h4 class="jobFilter-proposal-author-contents-title"> {{ $user->first_name }}
                                                {{ $user->last_name }}</h4>
                                            <p class="jobFilter-proposal-author-contents-subtitle mt-2">
                                                @if($user->user_introduction?->title)
                                                {{ $user->user_introduction?->title }} ·
                                                @endif
                                                <span>
                                                    @if($user->user_state?->state)
                                                    {{ $user->user_state?->state }},
                                                    @endif
                                                    @if($user->user_country?->country)
                                                    {{ $user->user_country?->country }}
                                                    @endif
                                                </span>
                                            </p>

                                            <div class="jobFilter-proposal-author-contents-review mt-2">
                                                {!! freelancer_rating($user->id) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!empty($project->standard_title) && !empty($project->premium_title))
                            <div class="project-preview">
                                <div class="project-preview-head profile-border-bottom">
                                    <h4 class="project-preview-head-title"> {{ __('Compare Packages') }} </h4>
                                </div>
                                <div class="pricing-wrapper d-flex flex-wrap">
                                    <!-- left wrapper -->
                                    <div class="pricing-wrapper-left">
                                        <div class="pricing-wrapper-card mb-30 wow fadeInLeft" data-wow-delay=".1s">
                                            <div class="pricing-wrapper-card-top">
                                            </div>
                                            <div class="pricing-wrapper-card-bottom">
                                                <div class="pricing-wrapper-card-bottom-list">
                                                    <ul class="list-style-none">
                                                        <li>{{ __('Revisions') }}</li>
                                                        <li>{{ __('Delivery time') }}</li>
                                                        @foreach ($project->project_attributes as $attr)
                                                            <li>{{ $attr->check_numeric_title }}</li>
                                                        @endforeach
                                                        <li>{{ __('Charges') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pricing-wrapper-right d-flex flex-wrap">
                                        @if ($project->basic_title)
                                            <div class="pricing-wrapper-card text-center wow fadeInRight"
                                                data-wow-delay=".2s">
                                                <div class="pricing-wrapper-card-top">
                                                    <h2 class="pricing-wrapper-card-top-prices">
                                                        {{ $project->basic_title }}</h2>
                                                </div>
                                                <div class="pricing-wrapper-card-bottom">
                                                    <div class="pricing-wrapper-card-bottom-list">
                                                        <ul class="list-style-none">
                                                            <li><span class="close-icon"> {{ $project->basic_revision }}
                                                                </span></li>
                                                            <li><span class="close-icon">
                                                                    {{ $project->basic_delivery }}</span></li>
                                                            @foreach ($project->project_attributes as $attr)
                                                                @if ($attr->basic_check_numeric == 'on')
                                                                    <li><span class="check-icon"> <i
                                                                                class="fas fa-check"></i> </span></li>
                                                                @else
                                                                    <li><span class="close-icon">
                                                                            {{ $attr->basic_check_numeric }} </span></li>
                                                                @endif
                                                            @endforeach
                                                            <li>
                                                                <div class="price">
                                                                    @if ($project->basic_discount_charge != null && $project->basic_discount_charge > 0)
                                                                        <h6 class="price-main">
                                                                            {{ amount_with_currency_symbol($project->basic_discount_charge) }}
                                                                        </h6>
                                                                        <s class="price-old">
                                                                            {{ amount_with_currency_symbol($project->basic_regular_charge) }}</s>
                                                                    @else
                                                                        <h6 class="price-main">
                                                                            {{ amount_with_currency_symbol($project->basic_regular_charge) }}
                                                                        </h6>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="pricing-wrapper-card text-center wow fadeInLeft" data-wow-delay=".2s">
                                            <div class="pricing-wrapper-card-top">
                                                <h2 class="pricing-wrapper-card-top-prices"> {{ $project->standard_title }}
                                                </h2>
                                            </div>
                                            <div class="pricing-wrapper-card-bottom">
                                                <div class="pricing-wrapper-card-bottom-list">
                                                    <ul class="list-style-none">
                                                        <li><span class="close-icon">
                                                                {{ $project->standard_revision }}</span></li>
                                                        <li><span class="close-icon"> {{ $project->standard_delivery }}
                                                            </span></li>
                                                        @foreach ($project->project_attributes as $attr)
                                                            @if ($attr->basic_check_numeric == 'on')
                                                                <li><span class="check-icon"> <i class="fas fa-check"></i>
                                                                    </span></li>
                                                            @else
                                                                <li><span class="close-icon">
                                                                        {{ $attr->standard_check_numeric }} </span></li>
                                                            @endif
                                                        @endforeach
                                                        <li>
                                                            <div class="price">
                                                                @if ($project->standard_discount_charge != null && $project->standard_discount_charge > 0)
                                                                    <h6 class="price-main">
                                                                        {{ amount_with_currency_symbol($project->standard_discount_charge) }}
                                                                    </h6>
                                                                    <s class="price-old">
                                                                        {{ amount_with_currency_symbol($project->standard_regular_charge ?? '') }}</s>
                                                                @else
                                                                    <h6 class="price-main">
                                                                        {{ amount_with_currency_symbol($project->standard_regular_charge ?? '') }}
                                                                    </h6>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pricing-wrapper-card text-center wow fadeInRight" data-wow-delay=".3s">
                                            <div class="pricing-wrapper-card-top">
                                                <h2 class="pricing-wrapper-card-top-prices">{{ $project->premium_title }}
                                                </h2>
                                            </div>
                                            <div class="pricing-wrapper-card-bottom">
                                                <div class="pricing-wrapper-card-bottom-list">
                                                    <ul class="list-style-none">
                                                        <li><span class="close-icon"> {{ $project->premium_revision }}
                                                            </span></li>
                                                        <li><span class="close-icon"> {{ $project->premium_delivery }}
                                                            </span></li>
                                                        @foreach ($project->project_attributes as $attr)
                                                            @if ($attr->basic_check_numeric == 'on')
                                                                <li><span class="check-icon"> <i class="fas fa-check"></i>
                                                                    </span></li>
                                                            @else
                                                                <li><span class="close-icon">
                                                                        {{ $attr->premium_check_numeric }} </span></li>
                                                            @endif
                                                        @endforeach
                                                        <li>
                                                            <div class="price">
                                                                @if ($project->premium_discount_charge != null && $project->premium_discount_charge > 0)
                                                                    <h6 class="price-main">
                                                                        {{ amount_with_currency_symbol($project->premium_discount_charge) }}
                                                                    </h6>
                                                                    <s class="price-old">
                                                                        {{ amount_with_currency_symbol($project->premium_regular_charge) }}
                                                                    </s>
                                                                @else
                                                                    <h6 class="price-main">
                                                                        {{ amount_with_currency_symbol($project->premium_regular_charge) }}
                                                                    </h6>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="col-xl-5 col-lg-8">
                        <div class="sticky-sidebar">
                            <div class="project-preview">
                                <div class="project-preview-tab">
                                    <ul class="tabs dashboard-tabs">
                                        <li data-tab="basic" class="active">{{ $project->basic_title }}</li>
                                        <li data-tab="standard">{{ $project->standard_title }}</li>
                                        <li data-tab="premium">{{ $project->premium_title }}</li>
                                    </ul>
                                    <div class="project-preview-tab-contents mt-4">

                                        <div class="tab-content-item dashboard-tab-content-item active" id="basic">
                                            <div class="project-preview-tab-header">
                                                <div class="project-preview-tab-header-item">
                                                    <span class="left"><i class="fa-solid fa-repeat"></i>
                                                        {{ __('Revisions') }}</span>
                                                    <strong class="right">{{ $project->basic_revision }}</strong>
                                                </div>
                                                <div class="project-preview-tab-header-item">
                                                    <span class="left"><i class="fa-regular fa-clock"></i>
                                                        {{ __('Delivery time') }}</span>
                                                    <strong class="right">{{ $project->basic_delivery }} </strong>
                                                </div>
                                            </div>
                                            <div class="project-preview-tab-inner mt-4">
                                                @foreach ($project->project_attributes as $attr)
                                                    <div class="project-preview-tab-inner-item">
                                                        <span class="left">{{ $attr->check_numeric_title }}</span>
                                                        @if ($attr->basic_check_numeric == 'on')
                                                            <span class="check-icon"> <i class="fas fa-check"></i> </span>
                                                        @else
                                                            <span class="right"> {{ $attr->basic_check_numeric }} </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                                <div class="project-preview-tab-inner-item">
                                                    @if ($project->basic_discount_charge != null && $project->basic_discount_charge > 0)
                                                        <span class="left price-title">{{ __('Price') }}</span>
                                                        <span class="right price">
                                                            <s>{{ amount_with_currency_symbol($project->basic_regular_charge ?? '') }}</s>{{ amount_with_currency_symbol($project->basic_discount_charge) }}</span>
                                                    @else
                                                        <span class="left price-title">{{ __('Price') }}</span>
                                                        <span
                                                            class="right price">{{ amount_with_currency_symbol($project->basic_regular_charge ?? '') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content-item dashboard-tab-content-item" id="standard">
                                            <div class="project-preview-tab-header">
                                                <div class="project-preview-tab-header-item">
                                                    <span class="left"><i class="fa-solid fa-repeat"></i>
                                                        {{ __('Revisions') }}</span>
                                                    <strong class="right">{{ $project->basic_revision }}</strong>
                                                </div>
                                                <div class="project-preview-tab-header-item">
                                                    <span class="left"><i class="fa-regular fa-clock"></i>
                                                        {{ __('Delivery time') }}</span>
                                                    <strong class="right">{{ $project->basic_delivery }}</strong>
                                                </div>
                                            </div>
                                            <div class="project-preview-tab-inner mt-4">
                                                @foreach ($project->project_attributes as $attr)
                                                    <div class="project-preview-tab-inner-item">
                                                        <span class="left">{{ $attr->check_numeric_title }}</span>
                                                        @if ($attr->standard_check_numeric == 'on')
                                                            <span class="check-icon"> <i class="fas fa-check"></i> </span>
                                                        @else
                                                            <span class="right"> {{ $attr->standard_check_numeric }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                                <div class="project-preview-tab-inner-item">
                                                    @if ($project->standard_discount_charge != null && $project->standard_discount_charge > 0)
                                                        <span class="left price-title">{{ __('Price') }}</span>
                                                        <span class="right price">
                                                            <s>{{ amount_with_currency_symbol($project->standard_regular_charge ?? '') }}</s>{{ amount_with_currency_symbol($project->standard_discount_charge) }}</span>
                                                    @else
                                                        <span class="left price-title">{{ __('Price') }}</span>
                                                        <span
                                                            class="right price">{{ amount_with_currency_symbol($project->standard_regular_charge ?? '') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content-item dashboard-tab-content-item" id="premium">
                                            <div class="project-preview-tab-header">
                                                <div class="project-preview-tab-header-item">
                                                    <span class="left"><i class="fa-solid fa-repeat"></i>
                                                        {{ __('Revisions') }}</span>
                                                    <strong class="right">{{ $project->premium_revision }}</strong>
                                                </div>
                                                <div class="project-preview-tab-header-item">
                                                    <span class="left"><i class="fa-regular fa-clock"></i>
                                                        {{ __('Delivery time') }}</span>
                                                    <strong class="right">{{ $project->premium_delivery }}</strong>
                                                </div>
                                            </div>
                                            <div class="project-preview-tab-inner mt-4">
                                                @foreach ($project->project_attributes as $attr)
                                                    <div class="project-preview-tab-inner-item">
                                                        <span class="left">{{ $attr->check_numeric_title }}</span>
                                                        @if ($attr->premium_check_numeric == 'on')
                                                            <span class="check-icon"> <i class="fas fa-check"></i> </span>
                                                        @else
                                                            <span class="right"> {{ $attr->premium_check_numeric }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach

                                                <div class="project-preview-tab-inner-item">
                                                    @if ($project->premium_discount_charge != null && $project->premium_discount_charge > 0)
                                                        <span class="left price-title">{{ __('Price') }}</span>
                                                        <span class="right price">
                                                            <s>{{ amount_with_currency_symbol($project->premium_regular_charge ?? '') }}</s>{{ amount_with_currency_symbol($project->premium_discount_charge) }}</span>
                                                    @else
                                                        <span class="left price-title">{{ __('Price') }}</span>
                                                        <span
                                                            class="right price">{{ amount_with_currency_symbol($project->premium_regular_charge ?? '') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="mt-5">
                                        <div class="btn-wrapper flex-btn justify-content-between">
                                            @can('project-reject')
                                                <a href="#" class="btn-profile btn-outline-gray btn-hover-danger" data-bs-target="#rejectProjectModal" data-bs-toggle="modal">{{ __('Click to Reject') }}</a>
                                            @endcan
                                            @can('project-status-change')
                                                @if ($project->status === 0 || $project->status === 2)
                                                    <x-status.table.status-change :title="__('Click to Active')" :class="'btn-profile btn-bg-1 swal_status_change_button'"
                                                        :url="route('admin.project.status.change', $project->id)" />
                                                @else
                                                    <x-status.table.status-change :title="__('Click to Inactive')" :class="'btn-profile btn-bg-1 swal_status_change_button'"
                                                        :url="route('admin.project.status.change', $project->id)" />
                                                @endif
                                            @endcan
                                            <div class="mt-3">
                                                <x-notice.general-notice :description="__(
                                                    'Notice: Active means users will be able to see the project on the website.',
                                                )" :description1="__(
                                                    'Notice: Inactive means the project will be hidden from users.',
                                                )"
                                                    :description2="__(
                                                        'Notice: Rejected means the project has issues and the user is requested to resolve these issues and resubmit the project.',
                                                    )" />
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.project.project-reject')

@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-select2.select2-js />
    @include('backend.pages.project.project-js')

@endsection
