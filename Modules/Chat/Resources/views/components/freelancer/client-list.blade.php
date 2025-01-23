<li class="chat-wrapper-contact-list-item chat_item" data-client-id="{{ $freelancerChat?->client?->id }}">
    <div class="chat-wrapper-contact-list-flex">
        <div class="chat-wrapper-contact-list-thumb">
            <a href="javascript:void(0)">
                @if($freelancerChat?->client?->image)
                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $freelancerChat?->client?->image, load_from: $freelancerChat?->client?->load_from ?? '') }}" alt="{{ $freelancerChat->client?->fullname }}">
                    @else
                        <img src="{{ asset('assets/uploads/profile/'.$freelancerChat?->client?->image) }}" alt="{{ $freelancerChat->client?->fullname }}">
                    @endif
                @else
                    <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                @endif
            </a>
            <div class="notification-dots {{ Cache::has('user_is_online_' . $freelancerChat?->client?->id) ? "active" : "" }}"></div>
        </div>
        <div class="chat-wrapper-contact-list-contents">
            <div class="chat-wrapper-contact-list-contents-flex flex-between">
                <h4 class="chat-wrapper-contact-list-contents-title"><a href="javascript:void(0)">{{ $freelancerChat?->client?->fullname }}</a></h4>
                <span class="chat-wrapper-contact-list-time">{{ $freelancerChat->client?->check_online_status?->diffForHumans() }}</span>
            </div>
            <div>
                <p class="chat-wrapper-contact-list-contents-para">{{ $freelancerChat?->client?->user_introduction?->title ?? '' }}</p>
                <div class="unseen_message_count_{{$freelancerChat?->client->id}}">
                    @if($freelancerChat->freelancer_unseen_msg_count > 0)
                        <span class="badge bg-danger text-right">{{ $freelancerChat->freelancer_unseen_msg_count }}</span>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>

