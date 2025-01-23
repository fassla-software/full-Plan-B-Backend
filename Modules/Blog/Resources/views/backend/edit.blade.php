@extends('backend.layout.master')
@section('title', __('Edit Blog'))
@section('style')
    <x-media.css />
    <x-summernote.summernote-css />
    <x-tags.tag-input-css />
    <x-select2.select2-css />
    <style>
        .note-editor.note-airframe .note-editing-area .note-editable, .note-editor.note-frame .note-editing-area .note-editable {
            height: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <form action="{{ route('admin.blog.update',$blog->id) }}" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="customMarkup__single">
                        <div class="customMarkup__single__item">
                            <h4 class="customMarkup__single__title">{{ __('Blog Details') }}</h4>
                            <x-validation.error />
                            <div class="customMarkup__single__inner mt-4">
                                @csrf
                                <div class="tab-content margin-top-40">
                                    <div class="single-input">
                                        <label for="title" class="label-title mt-3">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" id="edit_title" name="title" value="{{ $blog->title }}" placeholder="{{ __('Title') }}">
                                    </div>
                                    <x-form.text :title="__('Slug')" :type="'text'" :id="'edit_slug'" :name="'slug'" :value="$job_details->slug ?? old('slug')" :divClass="'mb-0'" :class="'form--control d-none'" :labelClass="'d-none display_label_title'" :placeholder="__('Slug')" />
                                    <div class="mb-1">
                                        <strong>{{ __('Slug:') }}</strong>
                                        <span class="full-slug-show"></span>
                                        <span class="edit_blog_slug"><i class="fas fa-edit"></i></span>
                                    </div>
                                    <div class="single-input mb-3">
                                        <label for="content" class="label-title mt-3">{{ __('Content') }}</label>
                                        <div class="summernote-wrapper">
                                            <textarea name="blog_content" class="form-control summernote"> {!! $blog->content !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-lg-3">
                                        <h5 class="header-title">{{__('Meta Section')}}</h5>
                                        <div class="nav flex-column nav-pills" id="v-pills-tab"
                                             role="tablist" aria-orientation="vertical">

                                            <a class="nav-link active" id="v-pills-home-tab"
                                               data-bs-toggle="pill" href="#v-pills-home" role="tab"
                                               aria-controls="v-pills-home"
                                               aria-selected="true">{{__('Blog Meta')}}</a>

                                            <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                               href="#v-pills-profile" role="tab"
                                               aria-controls="v-pills-profile"
                                               aria-selected="false">{{__('Facebook Meta')}}</a>

                                            <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                                               href="#v-pills-messages" role="tab"
                                               aria-controls="v-pills-messages"
                                               aria-selected="false">{{__('Twitter Meta')}}</a>

                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="tab-content" id="v-pills-tabContent">

                                            <div class="tab-pane fade show active dynamic-page-meta" id="v-pills-home"
                                                 role="tabpanel" aria-labelledby="v-pills-home-tab">
                                                <div class="single-input">
                                                    <label for="title" class="label-title mt-3">{{__('Meta Title')}}</label>
                                                    <input type="text" class="form-control" name="meta_title" value="{{ $blog?->meta_data->meta_title ?? '' }}"
                                                           placeholder="{{__('Title')}}">
                                                </div>
                                                <div class="single-input">
                                                    <label for="slug" class="label-title mt-3">{{__('Meta Tags')}}</label>
                                                    <input type="text" class="form-control" placeholder="tags" name="meta_tags" value="{{ $blog?->meta_data->meta_tags ?? '' }}" data-role="tagsinput">
                                                </div>

                                                <div class="row">
                                                    <div class="single-input col-md-12">
                                                        <label for="title" class="label-title mt-3">{{__('Meta Description')}}</label>
                                                        <textarea name="meta_description" class="form-control max-height-140" cols="20" rows="4">{{ $blog?->meta_data->meta_description ?? '' }}</textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                                 aria-labelledby="v-pills-profile-tab">
                                                <div class="form-group">
                                                    <label for="title" class="label-title mt-3">{{__('Facebook Meta Title')}}</label>
                                                    <input type="text" class="form-control" name="facebook_meta_tags" value="{{ $blog?->meta_data->facebook_meta_tags ?? '' }}">
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="title" class="label-title mt-3">{{__('Facebook Meta Description')}}</label>
                                                        <textarea name="facebook_meta_description"
                                                                  class="form-control max-height-140"
                                                                  cols="20"
                                                                  rows="4">{{ $blog?->meta_data->facebook_meta_description ?? '' }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="single-input">
                                                    <label for="image" class="label-title mt-3">{{__('Facebook Meta Image')}}</label>
                                                    <div class="media-upload-btn-wrapper">
                                                        <div class="img-wrap">
                                                            {!! render_attachment_preview_for_admin($blog->meta_data->facebook_meta_image ?? '') !!}
                                                        </div>
                                                        <input type="hidden" name="facebook_meta_image">
                                                        <button type="button"
                                                                class="btn btn-info media_upload_form_btn"
                                                                data-btntitle="{{__('Select Image')}}"
                                                                data-modaltitle="{{__('Upload Image')}}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#media_upload_modal">
                                                            {{__('Upload Image')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                                 aria-labelledby="v-pills-messages-tab">
                                                <div class="single-input">
                                                    <label for="title" class="label-title mt-3">{{__('Twitter Meta Title')}}</label>
                                                    <input type="text" class="form-control"
                                                           name="twitter_meta_tags" value="{{ $blog?->meta_data->twitter_meta_tags ?? '' }}">
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="title" class="label-title mt-3">{{__('Twitter Meta Description')}}</label>
                                                        <textarea name="twitter_meta_description"
                                                                  class="form-control max-height-140"
                                                                  cols="20"
                                                                  rows="4">{{ $blog?->meta_data->twitter_meta_description ?? '' }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="image" class="label-title mt-3">{{__('Twitter Meta Image')}}</label>
                                                    <div class="media-upload-btn-wrapper">
                                                        <div class="img-wrap">
                                                            {!! render_attachment_preview_for_admin($blog->meta_data->twitter_meta_image ?? '') !!}
                                                        </div>
                                                        <input type="hidden" name="twitter_meta_image">
                                                        <button type="button"
                                                                class="btn btn-info media_upload_form_btn"
                                                                data-btntitle="{{__('Select Image')}}"
                                                                data-modaltitle="{{__('Upload Image')}}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#media_upload_modal">
                                                            {{__('Upload Image')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="customMarkup__single">
                        <div class="customMarkup__single__item">
                            <h4 class="customMarkup__single__title">{{ __('Blog Catalogue') }}</h4>
                            <div class="customMarkup__single__inner mt-4">
                                <div class="tab-content margin-top-40">
                                    <div class="single-input mt-3">
                                        <label class="label-title">{{ __('Select Category') }}</label>
                                        <select name="category" id="category" class="form-control select2_category">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach($allCategories = \Modules\Service\Entities\Category::all_categories() as $data)
                                                <option value="{{ $data->id }}" @if($blog->category_id == $data->id) selected @endif>{{ $data->category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="single-input mt-3">
                                        <label for="slug" class="label-title mt-3">{{__('Blog Tags')}}</label>
                                        <input type="text" class="form-control" placeholder="tags" name="tag_name" data-role="tagsinput" value="{{ $blog->tag_name }}">
                                    </div>
                                    <x-status.form.active-inactive :title="'Status'" :status="$blog->status" />
                                    <x-backend.image :title="__('')" :name="'image'" :id="$blog->image" :dimentions="__('590x320 pixels')"/>
                                    <x-btn.submit class="btn btn-primary mt-4" :title="'Submit'" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <x-media.markup />
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-select2.select2-js />
    <x-media.js />
    <x-summernote.summernote-js />
    <x-tags.tag-input-js />
    @include('blog::backend.blog-js')
@endsection
