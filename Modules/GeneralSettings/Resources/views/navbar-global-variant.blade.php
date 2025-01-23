@extends('backend.layout.master')
@section('title', __('Navbar Global Variant'))
@section('style')
    <style>
        .img-select {
            position: relative;
        }
        .img-select.selected:after {
            position: absolute;
            left: 0px;
            top: 0;
            z-index: 1;
            color: #fff;
            content: "\f058";
            font-size: 30px;
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            line-height: 30px;
            width: 40px;
            height: 40px;
            background-color: #007bff;
            padding-left: 5px;
            padding-top: 4px;
        }
    </style>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Navbar Global Variant') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.navbar.global.variant')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <input type="hidden" class="form-control" id="global_navbar_variant" value="{{ get_static_option('global_navbar_variant') }}" name="global_navbar_variant">
                                    <div class="row">
                                        @for($i = 1; $i <4; $i++)
                                            <div class="col-lg-12 col-md-12">
                                                <div class="img-select selected">
                                                    <div class="img-wrap">
                                                        <img src="{{asset('assets/frontend/navbar-variant/'.$i.'.jpg')}}" data-home_id="0{{$i}}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
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
    <script>
        (function($) {
            "use strict";
            $(document).ready(function(){
                //For Navbar
                let imgSelect = $('.img-select');
                let id = $('#global_navbar_variant').val();
                imgSelect.removeClass('selected');
                $('img[data-home_id="'+id+'"]').parent().parent().addClass('selected');
                $(document).on('click','.img-select img',function (e) {
                    e.preventDefault();
                    imgSelect.removeClass('selected');
                    $(this).parent().parent().addClass('selected').siblings();
                    $('#global_navbar_variant').val($(this).data('home_id'));
                })

            });
        })(jQuery);
    </script>
@endsection
