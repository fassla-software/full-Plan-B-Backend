@extends('backend.layout.master')

@section('title', __('Manage 4o4 Page'))

@section('style')
    <x-media.css/>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Manage 4o4 Page') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.404')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="error_404_page_title" class="label-title">{{__('Title')}}</label>
                                    <input type="text" name="error_404_page_title"  class="form-control"  value="{{get_static_option('error_404_page_title')}}">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="error_404_page_subtitle" class="label-title">{{__('Sub Title')}}</label>
                                    <input type="text" name="error_404_page_subtitle"  class="form-control"  value="{{get_static_option('error_404_page_subtitle')}}">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="error_404_page_button_text" class="label-title">{{__('Button Text')}}</label>
                                    <input type="text" name="error_404_page_button_text"  class="form-control"  value="{{get_static_option('error_404_page_button_text')}}">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="error_404_page_paragraph" class="label-title">{{__('Paragraph')}}</label>
                                    <textarea name="error_404_page_paragraph" class="form-control" id="error_404_page_paragraph" cols="30" rows="4">{{get_static_option('error_404_page_paragraph')}}</textarea>
                                </div>
                                <div class="single-input mb-3">
                                    <x-backend.image :title="__('Error Image')" :name="'error_image'" :dimentions="'172x290'"/>
                                </div>
                                @can('update-404-page')
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
@endsection
