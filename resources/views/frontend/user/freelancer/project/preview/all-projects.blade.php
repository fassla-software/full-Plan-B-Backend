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
                        <div class="col-lg-4 col-md-6">
                            <div class="project-preview new_style">
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
                                    <div class="btn-wrapper flex-btn gap-2">
                                        <a href="javascript:void(0)"
                                           class="btn-profile btn-outline-gray project_description_view"
                                           data-bs-toggle="modal"
                                           data-bs-target="#projectDescriptionView"
                                           data-project_id="{{ $project->id }}">
                                            <i class="fa-regular fa-eye"></i>
                                            {{ __('Description') }}
                                        </a>
                                        <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger"> <i class="fa-regular fa-trash-can"></i> {{ __('Delete') }}</a>
                                        <a href="{{ route('freelancer.project.edit',$project->id) }}" class="btn-profile btn-outline-gray"><i class="fa-regular fa-pen-to-square"></i> {{ __('Edit') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>

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
