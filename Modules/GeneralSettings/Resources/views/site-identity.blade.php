@extends('backend.layout.master')
@section('title', __('Site Identity'))
@section('style')
    <x-media.css/>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Site Identity Settings') }}</h4>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{ route('admin.general.settings.site.identity') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <x-backend.image :title="__('Site Logo')" :name="'site_logo'" :dimentions="'180x56'"/>
                                <small>{{ __('This logo will be use for admin dashboard') }}</small>
                                <x-backend.image :title="__('Site White Logo')" :name="'site_white_logo'" :dimentions="'180x56'"/>
                                <x-backend.image :title="__('Favicon')" :name="'site_favicon'" :dimentions="'40x40'"/>
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
    <x-media.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function () {
                <x-btn.update/>
                <x-icon-picker.icon-picker/>
            });
        })(jQuery);
    </script>
@endsection
