@extends('backend.layout.master')
@section('title', __('Reading'))
@section('style')
    <x-media.css/>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Reading Settings') }}</h4>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{ route('admin.general.settings.reading') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mt-3">
                                    <label class="label-title">{{ __('Select Home Page') }}</label>
                                    <p class="text-info mb-1">{{ __('Selected page will be your application home page') }}</p>
                                    <select name="home_page" class="form-control">
                                        <option value="">{{ __('Select Page') }}</option>
                                        @foreach($all_pages as $page)
                                            <option value="{{ $page->id }}" @if($page->id == get_static_option('home_page')) selected @endif>{{ $page->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update')}}</button>
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
