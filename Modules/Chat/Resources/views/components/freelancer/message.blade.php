@php
    $project = json_decode(json_encode($message->message['project']));
@endphp

@if($message->from_user == 1)
    <div class="chat-wrapper-details-inner-chat">
        <div class="chat-wrapper-details-inner-chat-flex">
            <div class="chat-wrapper-details-inner-chat-thumb">
                @if($data->client?->image)
                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $data?->client?->image, load_from: $data?->client?->load_from ?? '') }}" alt="{{ $data->client?->fullname }}">
                    @else
                        <img src="{{ asset('assets/uploads/profile/'.$data->client?->image) }}" alt="">
                    @endif
                @else
                    <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                @endif
            </div>
            <div class="chat-wrapper-details-inner-chat-contents {{ !empty($project->type) ? "bg-danger p-2 text-dark bg-opacity-10" : "" }}">
                <p class="chat-wrapper-details-inner-chat-contents-para {{ !empty($project) ? "d-none" : "" }}">
                    @if(!empty($message->message['message']))
                    <span class="chat-wrapper-details-inner-chat-contents-para-span">{{ $message->message['message'] ?? '' }}</span>
                    @endif
                    @if(!empty($message->file))
                        <br />
                        <br />
                            @php
                                $ext = pathinfo($message->file, PATHINFO_EXTENSION);
                            @endphp
                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                @if($ext == 'pdf' || $ext == 'docx' || $ext == 'zip' || $ext == 'doc' || $ext == 'csv' || $ext == 'txt' || $ext == 'xlx' || $ext == 'xlsx' || $ext == 'ppt' || $ext == 'pptx' || $ext == 'rar' || $ext == '7z')
                                    <a class="download-pdf-chat mt-2" href="{{ render_frontend_cloud_image_if_module_exists('media-uploader/live-chat/'. $message->file, load_from: $message->load_from) }}" download>{{ __('Download file') }}</a>
                                @else
                                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'media-uploader/live-chat/'.$message->file, load_from: $message->load_from) }}">
                                    <br />
                                    <a class="download-pdf-chat mt-2" href="{{ render_frontend_cloud_image_if_module_exists('media-uploader/live-chat/'. $message->file, load_from: $message->load_from) }}" download>{{ __('Download file') }}</a>
                                @endif
                            @else
                                @if($ext == 'pdf' || $ext == 'docx' || $ext == 'zip' || $ext == 'doc' || $ext == 'csv' || $ext == 'txt' || $ext == 'xlx' || $ext == 'xlsx' || $ext == 'ppt' || $ext == 'pptx' || $ext == 'rar' || $ext == '7z')
                                <a class="download-pdf-chat mt-2" href="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" download>{{ __('Download file') }}</a>
                                @else
                                    <img src="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" alt="{{ $message->file ?? '' }}">
                                    <br />
                                    <a class="download-pdf-chat mt-2" href="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" download>{{ __('Download file') }}</a>
                                @endif
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
                                        <img class="img-fluid rounded-start" src="{{ render_frontend_cloud_image_if_module_exists( 'project/'. $project->image, load_from: $project->load_from ?? '') }}" alt="{{ $project->image }}">
                                    @else
                                        <img src="{{ asset('assets/uploads/project/'.$project->image) }}" class="img-fluid rounded-start" alt="{{ $project->image ?? ''}}">
                                    @endif
                                @endif
                            </div>
                            <div class="{{ ($project->type ?? '') == 'job'?'col-md-12' : 'col-md-8' }}">
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
    <div class="chat-wrapper-details-inner-chat  @if(Auth::guard('web')->check()) chat-reply @endif">
        <div class="chat-wrapper-details-inner-chat-flex">
            <div class="chat-wrapper-details-inner-chat-thumb">
                @if($data->freelancer?->image)
                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $data?->freelancer?->image, load_from: $data?->freelancer?->load_from ?? '') }}" alt="{{ $data->freelancer?->fullname }}">
                    @else
                        <img src="{{ asset('assets/uploads/profile/'.$data->freelancer?->image) }}" alt="">
                    @endif
                @else
                    <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                @endif
            </div>
            <div class="chat-wrapper-details-inner-chat-contents">
                <p class="chat-wrapper-details-inner-chat-contents-para">
                    @if(!empty($message->message['message']))
                    <span class="chat-wrapper-details-inner-chat-contents-para-span">{{ $message->message['message'] ?? '' }}</span>
                    @endif
                    @if(!empty($message->file))
                        <br />
                        <br />
                        @php
                            $ext = pathinfo($message->file, PATHINFO_EXTENSION);
                        @endphp
                        @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                @if($ext == 'pdf' || $ext == 'docx' || $ext == 'zip' || $ext == 'doc' || $ext == 'csv' || $ext == 'txt' || $ext == 'xlx' || $ext == 'xlsx' || $ext == 'ppt' || $ext == 'pptx' || $ext == 'rar' || $ext == '7z')
                                    <a class="download-pdf-chat mt-2" href="{{ render_frontend_cloud_image_if_module_exists('media-uploader/live-chat/'. $message->file, load_from: $message->load_from) }}" download>{{ __('Download file') }}</a>
                                @else
                                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'media-uploader/live-chat/'.$message->file, load_from: $message->load_from) }}">
                                    <br />
                                     <a class="download-pdf-chat mt-2" href="{{ render_frontend_cloud_image_if_module_exists('media-uploader/live-chat/'. $message->file, load_from: $message->load_from) }}" download>{{ __('Download file') }}</a>
                                @endif
                        @else
                                @if($ext == 'pdf' || $ext == 'docx' || $ext == 'zip' || $ext == 'doc' || $ext == 'csv' || $ext == 'txt' || $ext == 'xlx' || $ext == 'xlsx' || $ext == 'ppt' || $ext == 'pptx' || $ext == 'rar' || $ext == '7z')
                                <a class="download-pdf-chat mt-2" href="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" download>{{ __('Download file') }}</a>
                                @else
                                    <img src="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" alt="{{ $message->file ?? '' }}">
                                    <br />
                                    <a class="download-pdf-chat mt-2" href="{{ asset('assets/uploads/media-uploader/live-chat/'. $message->file) }}" download>{{ __('Download file') }}</a>
                                @endif
                        @endif
                    @endif
                </p>


                @if(!empty($project))
                    <div class="card mb-3" style="max-width: 540px; margin-left:auto">
                        <div class="row g-0">
                                <div class="col-md-4 {{ ($project->type ?? '') == 'job'?'d-none' : '' }}">
                                @if(($project->type ?? '') == 'job')
                                        <span></span>
                                @else
                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                        <img class="img-fluid rounded-start" src="{{ render_frontend_cloud_image_if_module_exists( 'project/'. $project->image, load_from: $project->load_from ?? '') }}" alt="{{ $project->image }}">
                                    @else
                                        <img src="{{ asset('assets/uploads/project/'.$project->image) }}" class="img-fluid rounded-start" alt="{{ $project->image ?? '' }}">
                                    @endif
                                @endif
                            </div>
                            <div class="{{ ($project->type ?? '') == 'job'?'col-md-12' : 'col-md-8' }}">
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
