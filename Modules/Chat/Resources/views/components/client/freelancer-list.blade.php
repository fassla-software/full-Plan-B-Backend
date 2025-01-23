<li class="chat-wrapper-contact-list-item chat_item" data-freelancer-id="{{ $clientChat?->freelancer?->id }}">
    <div class="chat-wrapper-contact-list-flex">
        <div class="chat-wrapper-contact-list-thumb">
            <a href="javascript:void(0)">
                @if($clientChat?->freelancer?->image)
                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $clientChat?->freelancer?->image, load_from: $clientChat?->freelancer?->load_from ?? '') }}" alt="{{ $clientChat?->freelancer?->fullname }}">
                    @else
                        <img src="{{ asset('assets/uploads/profile/'.$clientChat?->freelancer?->image) }}" alt="{{ $clientChat?->freelancer?->fullname }}">
                    @endif
                @else
                    <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                @endif
            </a>
            <div class="notification-dots {{ Cache::has('user_is_online_' . $clientChat->freelancer?->id) ? "active" : "" }}"></div>
        </div>
        <div class="chat-wrapper-contact-list-contents">
            <div class="chat-wrapper-contact-list-contents-flex flex-between">
                <h4 class="chat-wrapper-contact-list-contents-title"><a href="javascript:void(0)">{{ $clientChat->freelancer?->fullname }}</a></h4>
                <span class="chat-wrapper-contact-list-time">{{ $clientChat?->freelancer?->check_online_status?->diffForHumans() }}</span>
            </div>
            <div>
                <p class="chat-wrapper-contact-list-contents-para">{{ $clientChat?->freelancer?->user_introduction?->title }}</p>
                <div class ="unseen_message_count_{{$clientChat?->freelancer?->id}}">
                    @if($clientChat->client_unseen_msg_count > 0)
                        <span class="badge bg-danger text-right">{{ $clientChat->client_unseen_msg_count }}</span>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>

