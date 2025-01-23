@extends('backend.layout.master')
@section('title', __('Custom JS'))
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/codemirror.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/show-hint.css')}}">
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Custom JS') }}</h4>
                        <p class="margin-bottom-30">{{__('you can only add js code here. no other code work here.')}}</p>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.custom.js')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="message" class="label-title">{{__('Message')}}</label>
                                    <textarea class="form-control" name="custom_js_area" id="custom_js_area" cols="30" rows="10">{{$custom_js}}</textarea>
                                </div>
                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('assets/backend/js/codemirror.js')}}"></script>
    <script src="{{asset('assets/backend/js/show-hint.js')}}"></script>
    <script>
        (function($) {
            "use strict";
            var editor = CodeMirror.fromTextArea(document.getElementById("custom_js_area"), {
                lineNumbers: true,
                mode: "text/javascript",
                matchBrackets: true
            });
        })(jQuery);
    </script>
@endsection
