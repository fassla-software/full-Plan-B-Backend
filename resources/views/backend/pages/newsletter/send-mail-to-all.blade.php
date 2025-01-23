@extends('backend.layout.master')
@section('title', __('Email to All Subscriber'))
@section('style')
    <x-data-table.data-table-css />
    <x-summernote.summernote-css />
    <style>
        .w-90 {width: 90%;}

        .w-20 {width: 20%;}
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Email to All Subscriber') }}</h4>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <div class="custom_table style-04">
                                <x-validation.error />
                                <form class="" method="POST" action="{{ route('admin.newsletter.email.send.to.all') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="single-input mb-3">
                                        <label class="label-title"> {{ __('Subject') }}</label>
                                        <input type="text" class="form-control" name="subject" placeholder="{{ __('Enter subject') }}">
                                    </div>
                                    <div class="single-input mb-3">
                                        <label class="label-title"> {{ __('Message') }}</label>
                                        <textarea class="form-control summernote" name="message" placeholder="__('Enter subject')"></textarea>
                                    </div>
                                    <div class="form-group mt-5">
                                        <button id="submit" type="submit" class="btn-profile btn-bg-1">{{__('Send Email')}}</button>
                                    </div>
                                </form>
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
    <x-summernote.summernote-js />
@endsection
