@extends('backend.layout.master')

@section('title', __('Email Template Settings'))

@section('style')
    <x-summernote.summernote-css />
    <style>
        .summernote-wrapper .note-editing-area {
            height: 400px;
        }
        .note-editor.note-airframe .note-editing-area .note-editable, .note-editor.note-frame .note-editing-area .note-editable {
            height: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Email Template Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.email.template')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="site_global_email" class="label-title">{{__('Global Email')}}</label>
                                    <input type="text" name="site_global_email"  class="form-control" value="{{get_static_option('site_global_email')}}" >
                                    <small class="form-text text-muted">{{__('use your web mail here')}}</small>
                                </div>
                                <div class="single-input mb-3">
                                 <label for="site_global_email_template" class="label-title">{{__('Email Template')}}</label>
                                 <div class="summernote-wrapper">
                                     <textarea id="summernote_js"  name="site_global_email_template"  class="form-control">{{get_static_option('site_global_email_template')}}</textarea>
                                 </div>
                                    <small class="form-text text-muted">{{__('@username Will replace by username of user and @company will be replaced by site title also @message will be replaced by dynamically with message.')}}</small>
                                </div>
                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')
    <x-summernote.summernote-js />
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                $('#summernote_js').summernote({});
            });
        }(jQuery));
    </script>
@endsection
