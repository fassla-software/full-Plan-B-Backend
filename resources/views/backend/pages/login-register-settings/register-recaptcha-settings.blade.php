@extends('backend.layout.master')
@section('title', __('Register Page Recaptcha Settings'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Register Page Recaptcha Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.settings.register.recaptcha')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="site_tag_line" class="label-title mb-1">{{__('Recaptcha Site Key')}}</label>
                                    <input type="text" name="recaptcha_site_key"  class="form-control" value="{{get_static_option('recaptcha_site_key')}}">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="site_tag_line" class="label-title mb-1">{{__('Recaptcha Secret Key')}}</label>
                                    <input type="text" name="recaptcha_secret_key"  class="form-control" value="{{get_static_option('recaptcha_secret_key')}}">
                                </div>
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
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
    <x-media.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function () {

            });
        })(jQuery);
    </script>
@endsection
