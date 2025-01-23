<!-- Upload Gallery Start -->
<div class="setup-wrapper-contents">
    <div class="create-project-wrapper-item">
        <div class="create-project-wrapper-item-top profile-border-bottom">
            <h4 class="create-project-wrapper-title">{{ __('Upload Gallery') }} </h4>
        </div>
        <div class="create-project-wrapper-upload">
            <div class="create-project-wrapper-upload-browse center-text radius-10">

                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'project/'.$project_details->image, load_from: $project_details->load_from) }}" alt="project" class="project_photo_preview">
                @else
                    @if($project_details->image)
                        <img src="{{ asset('assets/uploads/project/'.$project_details->image) }}" alt="{{ __('Project Image') }}" class="project_photo_preview">
                    @else
                        <img src="" alt="{{ __('Project Image') }}" class="project_photo_preview">
                    @endif
                @endif


                <span class="create-project-wrapper-upload-browse-icon mt-3">
                    <i class="fa-solid fa-image"></i>
                </span>
                <span class="create-project-wrapper-upload-browse-para"> {{ __('Drag and drop or Click to browse files') }} </span>
                <input class="upload-gallery" type="file" name="image" id="upload_project_photo">
            </div>
            <p class="mt-3"><strong>{{ __('info:') }}</strong> {{ __('recommended dimensions 1770x960 pixels') }}</p>
        </div>
    </div>
</div>
<!-- Upload Gallery Ends -->
