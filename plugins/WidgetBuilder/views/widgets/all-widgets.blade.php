@extends('backend.layout.master')
@section('title', __('All Widgets'))
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/fontawesome-iconpicker.min.css') }}">
    <x-media.css/>
    <x-summernote.summernote-css />
    <x-backend.all-widgets-css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('All Widgets') }}</h4>
                        <div class="customMarkup__single__inner mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <ul id="sortable_02" class="available-form-field all-widgets sortable_02">
                                        {!! render_admin_panel_widgets_list() !!}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__inner mt-4">
                            <div class="sidebar-list-wrap">
                                {!! get_admin_sidebar_list() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')
    <script src="{{asset('assets/backend/js/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('assets/backend/js/fontawesome-iconpicker.min.js') }}"></script>
    <x-media.js/>
    <x-summernote.summernote-js />
    <x-icon-picker.icon-picker />
    <x-backend.all-widgets-js />
@endsection
