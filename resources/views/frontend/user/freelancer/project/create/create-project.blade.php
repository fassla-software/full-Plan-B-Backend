@extends('frontend.layout.master')
@section('site_title',__('Create Project'))
@section('style')
    <x-summernote.summernote-css />
    <x-select2.select2-css/>
    <style>
        .sticky {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9995;
            background: #fff;
            -webkit-box-shadow: 0px 2px 5px 0px rgba(104, 104, 104, 0.49);
            -moz-box-shadow: 0px 2px 5px 0px rgba(104, 104, 104, 0.49);
            box-shadow: 0px 2px 5px 0px rgba(104, 104, 104, 0.49);
        }
    </style>
@endsection
@section('content')
<main>
   <x-breadcrumb.user-profile-breadcrumb :title="__('Create Project')" :innerTitle="__('Create Project')"/>
    <!-- Account Setup area Starts -->
    <div class="account-area section-bg-2 pat-100 pab-100">
        <div class="container">
            <div class="setup-wrapper create-project-wrap">
                <div class="setup-wrapper-flex">
                    @include('frontend.user.freelancer.project.create.project-sidebar')
                    <div class="create-project-wrapper">
                        <x-validation.error />
                         <form action="{{ route('freelancer.project.create') }}" id="submit_create_project_form" method="post" enctype="multipart/form-data">
                            @csrf
                             <input type="hidden" name="basic_title" id="set_basic_title">
                             <input type="hidden" name="standard_title" id="set_standard_title">
                             <input type="hidden" name="premium_title" id="set_premium_title">

                            @include('frontend.user.freelancer.project.create.project-introduction')
                            @include('frontend.user.freelancer.project.create.project-image')
                            @include('frontend.user.freelancer.project.create.project-package-charge')
                            @include('frontend.user.freelancer.project.create.project-footer')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Account Setup area end -->
</main>
@endsection

@section('script')
   @include('frontend.user.freelancer.project.create.project-js')
   <x-summernote.summernote-js-function />
   <script>
       initializeSummernote($('.description'), {
           onKeyup: function(e) {
               setTimeout(function(){
                   let description_min_length = 50;
                   let project_description_length = $('#project_description').val().length;

                   if(project_description_length < description_min_length){
                       $('#project_description_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum ') }}'+ description_min_length +' {{ __('required') }}.</p>');
                   }else{
                       $('#project_description_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                   }
               },200);
           }
       })
   </script>

   <x-select2.select2-js />
@endsection
