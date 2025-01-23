<div class="chat-wrapper-details-header profile-border-bottom flex-between" id="livechat-message-header"
    data-client-id="{{ $data->client->id }}">
    <div class="chat-wrapper-details-header-left d-flex gap-2 align-items-center">
        <div class="chat-wrapper-details-header-left-author d-flex gap-2 align-items-center">
            @if ($data->client?->image)
                <div class="chat-wrapper-contact-list-thumb-main chat-wrapper-contact-list-thumb">
                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $data?->client?->image, load_from: $data?->client?->load_from ?? '') }}" alt="{{ $data->client?->fullname }}">
                    @else
                    <img src="{{ asset('assets/uploads/profile/' . $data->client?->image) }}"
                        alt="{{ $data->client?->fullname }}">
                    @endif
                </div>
            @else
                <div class="chat-wrapper-contact-list-thumb-main chat-wrapper-contact-list-thumb">
                    <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                </div>
            @endif
            <div class="chat-wrapper-contact-list-thumb-contents">
                <h5 class="chat-wrapper-details-header-title">{{ $data->client?->fullname }}</h5>
            </div>
        </div>
    </div>
    <div class="chat-wrapper-details-header-right">
        <div class="flex-btn gap-2">
            <button class="btn-profile btn-outline-1 color-one get_client_id" data-client-id="{{ $data->client?->id }}"
                data-bs-toggle="modal" data-bs-target="#exampleModal">{{ __('Send Offer') }}
            </button>
        </div>
    </div>
</div>
