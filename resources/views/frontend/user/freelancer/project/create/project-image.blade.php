<!-- Upload Gallery Start -->
<div class="setup-wrapper-contents">
    <div class="create-project-wrapper-item">
        <div class="create-project-wrapper-item-top profile-border-bottom">
            <h4 class="create-project-wrapper-title">{{ __('Upload Project Image') }} </h4>
        </div>
        <div class="create-project-wrapper-upload">
            <div class="create-project-wrapper-upload-browse center-text radius-10">
                <img src="" alt="" class="project_photo_preview">
                <span class="create-project-wrapper-upload-browse-icon mt-3">
                    <i class="fa-solid fa-image"></i>
                </span>
                <span class="create-project-wrapper-upload-browse-para"> {{ __('Drag and drop or Click to browse file') }} </span>

                <input class="upload-gallery" type="file" name="image" id="upload_project_photo">
            </div>
            <p class="mt-3"><strong>{{ __('info:') }}</strong> {{ __('recommended dimensions 1770x960 pixels.Drag and drop single file only') }}</p>
        </div>
    </div>
</div>
<!-- Upload Gallery Ends -->
