@extends('frontend.layout.master')
@section('site_title',__('Create Job'))
@section('style')
    <x-summernote.summernote-css/>
    <x-select2.select2-css/>
@endsection
@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Post a Job')" :innerTitle="__('Post a Job')"/>
        <!-- Account Setup area Starts -->
        <div class="account-area section-bg-2 pat-100 pab-100">
            <div class="container">
                <div class="account-setup-wrapper">
                    @include('frontend.user.client.job.create.job-header')
                    <div class="single-setup-account-inner custom-form profile-border-top">
                        <x-validation.error/>
                        <form action="{{ route('client.job.create') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            @include('frontend.user.client.job.create.job-details')
                            @include('frontend.user.client.job.create.job-budget')
                            @include('frontend.user.client.job.create.job-footer')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Account Setup area end -->
    </main>
@endsection

@section('script')
    @include('frontend.user.client.job.create.create-job-js')
    <x-summernote.summernote-js-function />
    <script>
        initializeSummernote($('.description'), {
            onKeyup: function(e) {
                setTimeout(function(){
                    let description_min_length = 10;
                    let job_description_length = $('#description').val().length;

                    if(job_description_length < description_min_length){
                        $('#job_description_char_length_check').html('<p class="text text-danger">{{ __('Length is short, minimum ') }}'+ description_min_length +' {{ __('required') }}.</p>');
                    }else{
                        $('#job_description_char_length_check').html('<p class="text text-success">{{ __('Length is valid') }}</p>');
                    }
                },200);
            }
        })
    </script>
    <x-select2.select2-js/>
@endsection
