@extends('backend.layout.master')

@section('title', __('Color Settings'))

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/colorpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/media-uploader.css') }}">
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Color Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.color')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input">
                                    <label for="main_color_one" class="label-title mb-3">{{__('Site Main Color')}}</label>
                                    <input type="text" name="main_color_one" style="background-color: {{get_static_option('main_color_one')}};" class="form-control"
                                           value="{{ get_static_option('main_color_one') ?? ''}}" id="main_color_one">
                                    <small class="form-text text-muted">{{__('you can change -site main color- from here, it will replace the website main color')}}</small>
                                </div>

                                <div class="single-input">
                                    <label for="main_color_two" class="label-title mb-3">{{__('Site Main Color Two')}}</label>
                                    <input type="text" name="main_color_two" style="background-color: {{get_static_option('main_color_two')}};" class="form-control"
                                           value="{{ get_static_option('main_color_two') ?? ''}}" id="main_color_two">
                                    <small class="form-text text-muted">{{__('you can change -site main color- from here, it will replace the website main color')}}</small>
                                </div>

                                <div class="single-input">
                                    <label for="secondary_color" class="label-title mb-3">{{__('Site Secondary Color')}}</label>
                                    <input type="text" name="secondary_color" style="background-color: {{get_static_option('secondary_color')}};" class="form-control"
                                           value="{{get_static_option('secondary_color')}}" id="secondary_color">
                                    <small class="form-text text-muted">{{__('you can change -site base color- from here, it will replace the website base color')}}</small>
                                </div>

                                <div class="single-input">
                                    <label for="heading_color" class="label-title mb-3">{{__('Heading Color')}}</label>
                                    <input type="text" name="heading_color" style="background-color: {{get_static_option('heading_color')}};" class="form-control"
                                           value="{{get_static_option('heading_color')}}" id="heading_color">
                                    <small class="form-text text-muted">{{__('you can change -heading color- from here, it will replace the website base color')}}</small>
                                </div>

                                <div class="single-input">
                                    <label for="paragraph_color" class="label-title mb-3">{{__('Paragraph Color')}}</label>
                                    <input type="text" name="paragraph_color" style="background-color: {{get_static_option('paragraph_color')}};" class="form-control"
                                           value="{{get_static_option('paragraph_color')}}" id="paragraph_color">
                                    <small class="form-text text-muted">{{__('you can change -heading color- from here, it will replace the website base color')}}</small>
                                </div>

                                <div class="single-input">
                                    <label for="body_color" class="label-title mb-3">{{__('Body Color')}}</label>
                                    <input type="text" name="body_color" style="background-color: {{get_static_option('body_color')}};" class="form-control"
                                           value="{{get_static_option('body_color')}}" id="body_color">
                                    <small class="form-text text-muted">{{__('you can change -heading color- from here, it will replace the website base color')}}</small>
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
    @section('script')
        <script src="{{ asset('assets/backend/js/colorpicker.js') }}"></script>
        <x-media.js />
        <script>
            (function($) {
                "use strict";
                $(document).ready(function() {

                    initColorPicker('#main_color_one');
                    initColorPicker('#secondary_color');
                    initColorPicker('#main_color_two');
                    initColorPicker('#heading_color');
                    initColorPicker('#paragraph_color');

                    function initColorPicker(selector) {
                        $(selector).ColorPicker({
                            color: '#852aff',
                            onShow: function(colpkr) {
                                $(colpkr).fadeIn(500);
                                return false;
                            },
                            onHide: function(colpkr) {
                                $(colpkr).fadeOut(500);
                                return false;
                            },
                            onChange: function(hsb, hex, rgb) {
                                $(selector).css('background-color', '#' + hex);
                                $(selector).val('#' + hex);
                            }
                        });
                    }
                });
            }(jQuery));
        </script>
@endsection
