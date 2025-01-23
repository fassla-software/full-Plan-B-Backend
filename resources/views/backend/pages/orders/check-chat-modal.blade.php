<!-- State Edit Modal -->
<div class="modal fade" id="checkChatModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Chat Messages') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                @php
                    $chat_id = \Modules\Chat\Entities\LiveChat::where('freelancer_id',$order_details->freelancer_id)
                    ->where('client_id',$order_details->user_id)
                    ->first();
                @endphp
            @if($chat_id !=null)
                <div class="modal-body">
                    @php $messages = \Modules\Chat\Entities\LiveChatMessage::where('live_chat_id',$chat_id->id)->get(); @endphp
                    @foreach($messages as $message)
                        @php
                            $project = json_decode(json_encode($message->message['project']));
                        @endphp
                        @if($message->from_user == 1)
                            <div class="chat-wrapper-details-inner-chat">
                                <div class="chat-wrapper-details-inner-chat-flex">
                                    <div class="chat-wrapper-details-inner-chat-thumb">
                                        @if($order_details->user?->image)
                                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $order_details->user?->image, load_from: $order_details->user?->load_from ?? '') }}" alt="{{ $order_details->user?->fullname ?? '' }}">
                                            @else
                                                <img src="{{ asset('assets/uploads/profile/'.$order_details->user?->image) }}" alt="">
                                            @endif
                                        @else
                                            <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                                        @endif
                                    </div>
                                    <div class="chat-wrapper-details-inner-chat-contents {{ !empty($project->type) ? "bg-danger p-2 text-dark bg-opacity-10" : "" }}">
                                        <p class="chat-wrapper-details-inner-chat-contents-para {{ !empty($project) ? "d-none" : "" }}">
                                            @if(!empty($message->message['message']))
                                            <span class="chat-wrapper-details-inner-chat-contents-para-span">{{ $message->message['message'] }}</span>
                                            @endif
                                            @if(!empty($message->file))
                                                <br />
                                                <br />
                                                <img src="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" alt="">
                                                    <?php
                                                    $ext = pathinfo($message->file, PATHINFO_EXTENSION);
                                                    ?>
                                                @if($ext == 'pdf')
                                                    <a class="download-pdf-chat" href="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" download>{{ __('Download pdf') }}</a>
                                                @endif
                                            @endif
                                        </p>

                                        @if(!empty($project))
                                            <div class="card mb-3" style="max-width: 540px;">
                                                <div class="row g-0">
                                                    <div class="col-md-4 {{ ($project->type ?? '') == 'job'?'d-none' : '' }}">
                                                        @if(($project->type ?? '') == 'job')
                                                            <span></span>
                                                        @else
                                                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                                <img class="img-fluid rounded-start" src="{{ render_frontend_cloud_image_if_module_exists( 'project/'. $project->image, load_from: $project->load_from ?? '') }}" alt="{{ $project->image ?? '' }}">
                                                            @else
                                                                <img src="{{ asset('assets/uploads/project/'.$project->image) }}" class="img-fluid rounded-start" alt="{{ $project->image ?? ''}}">
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $project->title }}</h5>
                                                            @if(($project->type ?? '') == 'job')
                                                                <a class="btn btn-primary btn-sm" target="_blank" href="{{ route('job.details', ['username' => $project->username, 'slug' => $project->slug]) }}">{{ __('View details') }}</a>
                                                            @else
                                                                <a class="btn btn-primary btn-sm" target="_blank" href="{{ route('project.details', ['username' => $project->username, 'slug' => $project->slug]) }}">{{ __('View details') }}</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                @if(($project->type ?? '') == 'job')
                                                    <h5>{{ $project->interview_message ?? '' }}</h5>
                                                @endif
                                            </div>
                                        @endif
                                        <span class="chat-wrapper-details-inner-chat-contents-time mt-2">
                                            {{ $message->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($message->from_user == 2)
                            <div class="chat-wrapper-details-inner-chat chat-reply">
                                <div class="chat-wrapper-details-inner-chat-flex">
                                    <div class="chat-wrapper-details-inner-chat-thumb">
                                        @if($order_details->freelancer?->image)
                                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $order_details->freelancer?->image, load_from: $order_details->freelancer?->load_from ?? '') }}" alt="{{ $order_details->freelancer?->fullname ?? '' }}">
                                            @else
                                                <img src="{{ asset('assets/uploads/profile/'.$order_details->freelancer?->image) }}" alt="">
                                            @endif
                                        @else
                                            <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                                        @endif
                                    </div>
                                    <div class="chat-wrapper-details-inner-chat-contents">
                                        <p class="chat-wrapper-details-inner-chat-contents-para">
                                            @if(!empty($message->message['message']))
                                            <span class="chat-wrapper-details-inner-chat-contents-para-span">{{ $message->message['message'] }}</span>
                                            @endif
                                            @if(!empty($message->file))
                                                <br />
                                                <img src="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" alt="">
                                            @endif
                                        </p>


                                        @if(!empty($project))
                                            <div class="card mb-3" style="max-width: 540px;">
                                                <div class="row g-0">
                                                    <div class="col-md-4">
                                                        @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                            <img class="img-fluid rounded-start" src="{{ render_frontend_cloud_image_if_module_exists( 'project/'. $project->image, load_from: $project->load_from ?? '') }}" alt="{{ $project->image ?? '' }}">
                                                        @else
                                                            <img src="{{ asset('assets/uploads/project/'.$project->image) }}" class="img-fluid rounded-start" alt="{{ $project->image ?? '' }}">
                                                        @endif
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $project->title }}</h5>
                                                            <a class="btn btn-primary btn-sm" target="_blank" href="{{ route('project.details', ['username' => $project->username, 'slug' => $project->slug]) }}">{{ __('View details') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <span class="chat-wrapper-details-inner-chat-contents-time mt-2">
                                            {{ $message->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
            @else
                <div class="modal-body">
                    <p>{{ __('No chats yet!') }}</p>
                </div>
            @endif
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
        </div>
    </div>
</div>
