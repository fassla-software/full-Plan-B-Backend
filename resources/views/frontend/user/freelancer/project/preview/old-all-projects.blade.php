@extends('frontend.layout.master')
@section('site_title',__('Project Preview'))
@section('style')
    <x-summernote.summernote-css />
@endsection
@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Project Preview')" :innerTitle="__('Project Preview')"/>
        <!-- Project preview area Starts -->
        <div class="preview-area section-bg-2 pat-100 pab-100">
            <div class="container">
                <div class="row g-4">
                    @foreach($all_projects as $project)
                    <div class="col-lg-6">
                        <div class="project-preview">
                            <div class="project-preview-head profile-border-bottom">
                                <h4 class="project-preview-head-title">{{ __('Project Catalogues') }}</h4>
                            </div>
                            <div class="project-preview-thumb">
                                <img src="{{ asset('assets/uploads/project/'.$project->image) }}" alt="projectPreview">
                            </div>
                            <div class="project-preview-contents mt-4">
                                <h4 class="project-preview-contents-title">{{ $project->title }}</h4>
                            </div>
                            <div class="project-preview-footer profile-border-top">
                                <div class="btn-wrapper flex-btn justify-content-end">
                                    <a href="javascript:void(0)"
                                       class="btn-profile btn-outline-gray btn-hover-danger project_description_view"
                                       data-bs-toggle="modal"
                                       data-bs-target="#projectDescriptionView"
                                       data-project_id="{{ $project->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                        {{ __('Project Description') }}
                                    </a>
                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger"> <i class="fa-solid fa-trash-can"></i>{{ __('Delete Project') }}</a>
                                    <a href="javascript:void(0)" class="btn-profile btn-bg-1">{{ __('Edit Project') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="project-preview">
                            <div class="project-preview-head profile-border-bottom">
                                <h4 class="project-preview-head-title"> {{ __('Packages & charges') }} </h4>
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
                                                    <li>{{__('Revisions')}}</li>
                                                    <li>{{ __('Delivery time') }}</li>
                                                    @foreach($project->project_attributes as $attr)
                                                    <li>{{ $attr->check_numeric_title }}</li>
                                                    @endforeach
                                                    <li>{{ __('Charges') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pricing-wrapper-right d-flex flex-wrap">
                                    @if($project->basic_title)
                                        <div class="pricing-wrapper-card text-center wow fadeInRight" data-wow-delay=".2s">
                                            <div class="pricing-wrapper-card-top">
                                                <h2 class="pricing-wrapper-card-top-prices"> {{ $project->basic_title }}</h2>
                                            </div>
                                            <div class="pricing-wrapper-card-bottom">
                                                <div class="pricing-wrapper-card-bottom-list">
                                                    <ul class="list-style-none">
                                                        <li><span class="close-icon"> {{ $project->basic_revision }} </span></li>
                                                        <li><span class="close-icon"> {{ $project->basic_delivery }} {{ __('days') }} </span></li>
                                                        @foreach($project->project_attributes as $attr)
                                                            @if($attr->basic_check_numeric == 'on')
                                                            <li><span class="check-icon"> <i class="fas fa-check"></i> </span></li>
                                                            @else
                                                            <li><span class="close-icon"> {{ $attr->basic_check_numeric }} </span></li>
                                                            @endif
                                                        @endforeach
                                                        <li>
                                                            <div class="price">
                                                                <h6 class="price-main"> {{ float_amount_with_currency_symbol($project->basic_regular_charge )}} </h6>
                                                                <s class="price-old"> {{ float_amount_with_currency_symbol($project->basic_discount_charge) }}</s>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="pricing-wrapper-card text-center wow fadeInLeft" data-wow-delay=".2s">
                                        <div class="pricing-wrapper-card-top">
                                            <h2 class="pricing-wrapper-card-top-prices"> {{ $project->standard_title }} </h2>
                                        </div>
                                        <div class="pricing-wrapper-card-bottom">
                                            <div class="pricing-wrapper-card-bottom-list">
                                                <ul class="list-style-none">
                                                    <li><span class="close-icon"> {{ $project->standard_revision }}</span></li>
                                                    <li><span class="close-icon"> {{ $project->standard_delivery }} {{ __('days') }} </span></li>
                                                    @foreach($project->project_attributes as $attr)
                                                        @if($attr->basic_check_numeric == 'on')
                                                            <li><span class="check-icon"> <i class="fas fa-check"></i> </span></li>
                                                        @else
                                                            <li><span class="close-icon"> {{ $attr->standard_check_numeric }} </span></li>
                                                        @endif
                                                    @endforeach
                                                    <li>
                                                        <div class="price">
                                                            <h6 class="price-main"> {{ float_amount_with_currency_symbol($project->standard_regular_charge) }} </h6>
                                                            <s class="price-old"> {{ float_amount_with_currency_symbol($project->standard_discount_charge ?? '') }}</s>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pricing-wrapper-card text-center wow fadeInRight" data-wow-delay=".3s">
                                        <div class="pricing-wrapper-card-top">
                                            <h2 class="pricing-wrapper-card-top-prices">{{ $project->premium_title }} </h2>
                                        </div>
                                        <div class="pricing-wrapper-card-bottom">
                                            <div class="pricing-wrapper-card-bottom-list">
                                                <ul class="list-style-none">
                                                    <li><span class="close-icon"> {{ $project->premium_revision }} </span></li>
                                                    <li><span class="close-icon"> {{ $project->premium_delivery }} {{ __('days') }} </span></li>
                                                    @foreach($project->project_attributes as $attr)
                                                        @if($attr->basic_check_numeric == 'on')
                                                            <li><span class="check-icon"> <i class="fas fa-check"></i> </span></li>
                                                        @else
                                                            <li><span class="close-icon"> {{ $attr->premium_check_numeric }} </span></li>
                                                        @endif
                                                    @endforeach
                                                    <li>
                                                        <div class="price">
                                                            <h6 class="price-main"> {{ float_amount_with_currency_symbol($project->premium_regular_charge) }} </h6>
                                                            <s class="price-old"> {{ float_amount_with_currency_symbol($project->premium_discount_charge ?? '') }}</s>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Project preview area end -->
    </main>

    <!-- Modal -->
    <div class="modal fade" id="projectDescriptionView" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="projectDescriptionViewLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="projectDescriptionViewLabel">{{ __('Project Description') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {

                // project title length check
                $(document).on('click','.project_description_view', function(){
                    let project_id = $(this).data('project_id');
                    $.ajax({
                        url:"{{route('freelancer.project.description')}}",
                        method:'get',
                        data:{project_id:project_id},
                        success:function(res){
                            $('.modal-body').html(res);
                        },
                    });
                });

            });
        }(jQuery));


    </script>

@endsection
