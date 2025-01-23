@extends('backend.layout.master')

@section('title', __('Typography Settings'))

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            box-sizing: border-box;
            display: grid;
            margin: 0;
            position: relative;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.typography')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <h4 class="customMarkup__single__title">{{ __('Body Typography Settings') }}</h4>
                                <div class="single-input">
                                    <label for="body_font_family" class="label-title mb-3">{{__('Font Family')}}</label>
                                    <select class="form-control body-font-family-single" name="body_font_family" id="body_font_family">
                                        @foreach($google_fonts as $font_family => $font_variant)
                                            <option value="{{$font_family}}" @if($font_family == get_static_option('body_font_family')) selected @endif>{{$font_family}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="single-input">
                                    <label for="body_font_variant" class="label-title mb-3">{{__('Font Variant')}}</label>
                                    @php
                                        $font_family_selected = get_static_option('body_font_family') ?? get_static_option('body_font_family') ;
                                        $get_font_family_variants = property_exists($google_fonts,$font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => array('regular')];
                                    @endphp
                                    <select class="form-control body-font-family-multiple" id="body_font_variant" name="body_font_variant[]" multiple="multiple">
                                        @foreach($get_font_family_variants['variants'] as $variant)
                                            @php
                                                $selected_variant = !empty(get_static_option('body_font_variant')) ? unserialize(get_static_option('body_font_variant')) : [];
                                            @endphp
                                            <option value="{{$variant}}" @if(in_array($variant,$selected_variant)) selected @endif>{{str_replace(['0,','1,'],['','i'],$variant)}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <h4 class="mt-5">{{__("Heading Typography Settings")}}</h4>
                                <div class="switch">
                                    <label class="label-title mb-3" for="heading_font">{{__('Heading Font')}}</label>
                                    <label class="switch-label" for="heading_font">{{__('Heading Font')}}</label>
                                    <small>{{__('Use different font family for heading tags ( h1,h2,h3,h4,h5,h6)')}}</small>
                                </div>
                                <div class="single-input">
                                    <label for="heading_font_family">{{__('Font Family')}}</label>
                                    <select class="form-control heading-font-family-single" name="heading_font_family" id="heading_font_family">
                                        @foreach($google_fonts as $font_family => $font_variant)
                                            <option value="{{$font_family}}" @if($font_family == get_static_option('heading_font_family')) selected @endif>{{$font_family}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group margin-top-50">
                                    <label for="heading_font_variant">{{__('Font Variant')}}</label>
                                    @php
                                        $font_family_selected = get_static_option('heading_font_family') ?? '';
                                        $get_font_family_variants = property_exists($google_fonts,$font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => array('regular')];
                                    @endphp
                                    <select class="form-control heading-font-family-multiple" multiple name="heading_font_variant[]" id="heading_font_variant">
                                        @foreach($get_font_family_variants['variants'] as $variant)
                                            @php
                                                $selected_variant = !empty(get_static_option('heading_font_variant')) ? unserialize(get_static_option('heading_font_variant')) : [];
                                            @endphp
                                            <option value="{{$variant}}"
                                                    @if(in_array($variant,$selected_variant)) selected @endif>{{str_replace(['0,','1,'],['','i'],$variant)}}</option>
                                        @endforeach
                                    </select>
                                </div>



                                <h4 class="customMarkup__single__title mt-5">{{ __('Section Typography Settings') }}</h4>
                                <div class="single-input">
                                    <label for="section_font_family" class="label-title mb-3">{{__('Font Family')}}</label>
                                    <select class="form-control section-font-family-single" name="section_font_family" id="section_font_family">
                                        @foreach($google_fonts as $font_family => $font_variant)
                                            <option value="{{$font_family}}" @if($font_family == get_static_option('section_font_family')) selected @endif>{{$font_family}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="single-input">
                                    <label for="section_font_variant" class="label-title mb-3">{{__('Font Variant')}}</label>
                                    @php
                                        $font_family_selected = get_static_option('section_font_family') ?? get_static_option('section_font_family') ;
                                        $get_font_family_variants = property_exists($google_fonts,$font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => array('regular')];
                                    @endphp
                                    <select class="form-control section-font-family-multiple" id="section_font_variant" name="section_font_variant[]" multiple="multiple">
                                        @foreach($get_font_family_variants['variants'] as $variant)
                                            @php
                                                $selected_variant = !empty(get_static_option('section_font_variant')) ? unserialize(get_static_option('section_font_variant')) : [];
                                            @endphp
                                            <option value="{{$variant}}" @if(in_array($variant,$selected_variant)) selected @endif>{{str_replace(['0,','1,'],['','i'],$variant)}}</option>
                                        @endforeach
                                    </select>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function($){
            "use strict";
            $(document).ready(function () {
                $('.body-font-family-single').select2();
                $('.body-font-family-multiple').select2();
                $('.heading-font-family-single').select2();
                $('.heading-font-family-multiple').select2();
                $('.section-font-family-single').select2();
                $('.section-font-family-multiple').select2();

                function getVariant(fontFamily,selector){
                    $.ajax({
                        url: "{{route('admin.general.settings.typography.single')}}",
                        type: "POST",
                        data:{
                            _token: "{{csrf_token()}}",
                            font_family : fontFamily
                        },
                        success:function (data) {
                            var variantSelector = $('#'+selector);
                            variantSelector.html('');
                            $.each(data.variants,function (index,value) {
                                var nameval = value.replace('0,','');
                                nameval = nameval.replace('1,','i');
                                variantSelector.append('<option value="'+value+'">'+nameval+'</option>');
                            });
                            variantSelector.niceSelect('update');
                        }
                    });
                }


                $(document).on('change','#body_font_family',function (e) {
                    e.preventDefault();
                    var fontFamily =  $(this).val();

                    $.ajax({
                        url: "{{route('admin.general.settings.typography.single')}}",
                        type: "POST",
                        data:{
                            _token: "{{csrf_token()}}",
                            font_family : fontFamily
                        },
                        success:function (data) {
                            var variantSelector = $('#body_font_variant');
                            variantSelector.html('');
                            $.each(data.variants,function (index,value) {
                                var nameval = value.replace('0,','');
                                nameval = nameval.replace('1,','i');
                                variantSelector.append('<option value="'+value+'">'+nameval+'</option>');
                            });
                            variantSelector.niceSelect('update');
                        }
                    });
                });

                $(document).on('change','#section_font_family',function (e) {
                    e.preventDefault();
                    var fontFamily =  $(this).val();

                    $.ajax({
                        url: "{{route('admin.general.settings.typography.single')}}",
                        type: "POST",
                        data:{
                            _token: "{{csrf_token()}}",
                            font_family : fontFamily
                        },
                        success:function (data) {
                            var variantSelector = $('#section_font_variant');
                            variantSelector.html('');
                            $.each(data.variants,function (index,value) {
                                var nameval = value.replace('0,','');
                                nameval = nameval.replace('1,','i');
                                variantSelector.append('<option value="'+value+'">'+nameval+'</option>');
                            });
                            variantSelector.niceSelect('update');
                        }
                    });
                });

                $(document).on('change','#heading_font_family',function (e) {
                    e.preventDefault();
                    var fontFamily =  $(this).val();

                    $.ajax({
                        url: "{{route('admin.general.settings.typography.single')}}",
                        type: "POST",
                        data:{
                            _token: "{{csrf_token()}}",
                            font_family : fontFamily
                        },
                        success:function (data) {
                            var variantSelector = $('#heading_font_variant');
                            variantSelector.html('');
                            $.each(data.variants,function (index,value) {
                                var nameval = value.replace('0,','');
                                nameval = nameval.replace('1,','i');
                                variantSelector.append('<option value="'+value+'">'+nameval+'</option>');
                            });

                            variantSelector.niceSelect('update');
                        }
                    });

                });

                var dependendFields = $('select[name="heading_font_family"],#heading_font_variant');
                if(!$('input[name="heading_font"]').prop('checked')){
                    dependendFields.parent().hide()
                }
                $(document).on('change','input[name="heading_font"]',function (e) {
                    if(!$(this).prop('checked')){
                        dependendFields.parent().hide();
                    }else{
                        dependendFields.parent().show();
                    }
                });

                $(document).on('click','#typography_submit_btn',function (e) {
                    e.preventDefault();
                    $(this).text('Updating...').prop('disabled',true);
                    $(this).parent().trigger('submit');
                })
            });
        })(jQuery);
    </script>
@endsection
