@extends('backend.layout.master')

@section('title', __('Maintenance Page Manage'))

@section('style')
    <x-media.css/>
    <link rel="stylesheet" href="{{asset('assets/common/css/flatpickr.min.css')}}">
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Maintenance Page Manage') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.maintenance')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="maintain_page_title" class="label-title">{{__('Title')}}</label>
                                    <input type="text" name="maintain_page_title"  class="form-control"  value="{{get_static_option('maintain_page_title')}}">
                                </div>
                                <div class="single-input mb-3">
                                    <x-backend.image :title="__('Maintenance Image')" :name="'maintain_page_logo'" :dimentions="'540x345'"/>
                                </div>
                                @can('update-maintenance-page')
                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                                @endcan
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
    <script src="{{asset('assets/common/js/flatpickr.js')}}"></script>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $("#maintenance_duration").flatpickr({
                    dateFormat: "Y-m-d",
                });
            });
        })(jQuery)
    </script>
@endsection
