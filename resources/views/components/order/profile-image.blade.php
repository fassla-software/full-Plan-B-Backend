@if($image)
    <a href="javascript:void(0)">
        @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
            <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $image, load_from: $loadFrom ?? '') }}" alt="{{ __('profile img') }}">
        @else
            <img src="{{ asset('assets/uploads/profile/'.$image) }}" alt="{{ __('AuthorImg') }}">
        @endif
    </a>
@else
    <a href="javascript:void(0)"><img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}"></a>
@endif
