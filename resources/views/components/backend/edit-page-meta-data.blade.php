<div class="col-lg-3">
    <h5 class="header-title mb-4">{{ __('Meta Section') }}</h5>
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

        <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home" role="tab"
            aria-controls="v-pills-home" aria-selected="true">{{ $sidebarHeading }}</a>

        <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile" role="tab"
            aria-controls="v-pills-profile" aria-selected="false">{{ __('Facebook Meta') }}</a>

        <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages" role="tab"
            aria-controls="v-pills-messages" aria-selected="false">{{ __('Twitter Meta') }}</a>

    </div>
</div>
<div class="col-lg-9">
    <div class="tab-content" id="v-pills-tabContent">

        <div class="tab-pane fade show active dynamic-page-meta" id="v-pills-home" role="tabpanel"
            aria-labelledby="v-pills-home-tab">
            <div class="single-input">
                <label for="title" class="label-title mt-3">{{ __('Meta Title') }}</label>
                <input type="text" class="form-control" name="meta_title"
                    value="{{ $pageDetails->meta_data->meta_title ?? '' }}">
            </div>
            <div class="single-input">
                <label for="slug" class="label-title mt-3">{{ __('Meta Tags') }}</label>
                <input type="text" class="form-control" name="meta_tags"
                    value="{{ $pageDetails->meta_data->meta_tags ?? '' }}" data-role="tagsinput">
            </div>

            <div class="row">
                <div class="single-input col-md-12">
                    <label for="title" class="label-title mt-3">{{ __('Meta Description') }}</label>
                    <textarea name="meta_description" class="form-control max-height-140" cols="20" rows="4">{!! $pageDetails->meta_data->meta_description ?? '' !!}</textarea>
                </div>
            </div>

        </div>

        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
            <div class="single-input">
                <label for="title" class="label-title mt-3">{{ __('Facebook Meta Title') }}</label>
                <input type="text" class="form-control" name="facebook_meta_tags"
                    value="{{ $pageDetails->meta_data->facebook_meta_tags ?? '' }}">
            </div>

            <div class="row">
                <div class="single-input col-md-12">
                    <label for="title" class="label-title mt-3">{{ __('Facebook Meta Description') }}</label>
                    <textarea name="facebook_meta_description" class="form-control max-height-140" cols="20" rows="4">{!! $pageDetails->meta_data->facebook_meta_description ?? '' !!}</textarea>
                </div>
            </div>

            <div class="single-input">
                <label for="og_meta_image" class="label-title mt-3">{{ __('Facebook Meta Image') }}</label>
                <div class="media-upload-btn-wrapper">
                    <div class="img-wrap">
                        {!! render_attachment_preview_for_admin($pageDetails->meta_data->facebook_meta_image ?? '') !!}
                    </div>
                    <input type="hidden" id="facebook_meta_image" name="facebook_meta_image"
                        value="{{ $pageDetails->meta_data->facebook_meta_image ?? '' }}">
                    <button type="button" class="btn btn-info media_upload_form_btn"
                        data-btntitle="{{ __('Select Image') }}" data-modaltitle="{{ __('Upload Image') }}"
                        data-bs-toggle="modal" data-bs-target="#media_upload_modal">
                        {{ 'Change Image' }}
                    </button>
                </div>
                <small class="form-text text-muted">{{ __('allowed image format: jpg,jpeg,png') }}</small>
            </div>
        </div>

        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
            <div class="single-input">
                <label for="title" class="label-title mt-3">{{ __('Twitter Meta Title') }}</label>
                <input type="text" class="form-control" name="twitter_meta_tags"
                    value=" {{ $pageDetails->meta_data->twitter_meta_tags ?? '' }}">
            </div>

            <div class="row">
                <div class="single-input col-md-12">
                    <label for="title" class="label-title mt-3">{{ __('Twitter Meta Description') }}</label>
                    <textarea name="twitter_meta_description" class="form-control max-height-140 meta-desc" cols="20"
                        rows="4">{!! $pageDetails->meta_data->twitter_meta_description ?? '' !!}</textarea>
                </div>
            </div>
            <div class="single-input">
                <label for="og_meta_image" class="label-title mt-3">{{ __('Twitter Meta Image') }}</label>
                <div class="media-upload-btn-wrapper">
                    <div class="img-wrap">
                        {!! render_attachment_preview_for_admin($pageDetails->meta_data->twitter_meta_image ?? '') !!}
                    </div>
                    <input type="hidden" id="twitter_meta_image" name="twitter_meta_image"
                        value="{{ $pageDetails->meta_data->twitter_meta_image ?? '' }}">
                    <button type="button" class="btn btn-info media_upload_form_btn"
                        data-btntitle="{{ __('Select Image') }}" data-modaltitle="{{ __('Upload Image') }}"
                        data-bs-toggle="modal" data-bs-target="#media_upload_modal">
                        {{ 'Change Image' }}
                    </button>
                </div>
                <small class="form-text text-muted">{{ __('allowed image format: jpg,jpeg,png') }}</small>
            </div>
        </div>

    </div>
</div>
