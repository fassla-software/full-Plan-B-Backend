@extends('backend.layout.master')

@section('title', __('Seo Settings'))

@section('style')
    <x-media.css/>
    <x-tags.tag-input-css />
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Seo Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.seo')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="site_meta_tags" class="label-title">{{__('Site Meta Tags')}}</label>
                                    <input type="text" name="site_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('site_meta_tags')}}" id="site_meta_tags">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="site_meta_description" class="label-title">{{__('Site Meta Description')}}</label>
                                    <textarea name="site_meta_description"  class="form-control" id="site_meta_description">{{get_static_option('site_meta_description')}}</textarea>
                                </div>
                                <div class="single-input mb-3">
                                    <label for="og_meta_title" class="label-title">{{__('Og Meta Title')}}</label>
                                    <input type="text" name="og_meta_title"  class="form-control"  value="{{get_static_option('og_meta_title')}}" id="og_meta_title">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="og_meta_description" class="label-title">{{__('Og Meta Description')}}</label>
                                    <textarea name="og_meta_description"  class="form-control" id="og_meta_description">{{get_static_option('og_meta_description')}}</textarea>
                                </div>
                                <div class="single-input mb-3">
                                    <label for="og_meta_site_name" class="label-title">{{__('Og Meta Site Name')}}</label>
                                    <input type="text" name="og_meta_site_name"  class="form-control"  value="{{get_static_option('og_meta_site_name')}}" id="og_meta_site_name">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="og_meta_url" class="label-title">{{__('Og Meta URL')}}</label>
                                    <input type="text" name="og_meta_url"  class="form-control"  value="{{get_static_option('og_meta_url')}}" id="og_meta_url">
                                </div>
                                <div class="single-input mb-3">
                                    <x-backend.image :title="__('Og Meta Image Image')" :name="'og_meta_image'" :dimentions="'1920x600'"/>
                                </div>
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
    <x-tags.tag-input-js />
@endsection
