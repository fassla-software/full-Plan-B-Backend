@extends('frontend.layout.master')
@section('site_title',__('Support Ticket Details'))
@section('style')
    <style>
        .text_style_manege{white-space: pre-line}
        .supportTicket-messages-body {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
    <x-summernote.summernote-css />
@endsection

@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Tickets')" :innerTitle="__('Tickets')"/>
        <!-- Profile Settings area Starts -->
        <div class="responsive-overlay"></div>
        <div class="profile-settings-area pat-50 pab-50 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    @include('frontend.user.layout.partials.sidebar')
                    <div class="col-xl-9 col-lg-8">
                        <div class="profile-settings-wrapper">
                            <div class="single-profile-settings">
                                <div class="supportTicket-single radius-10">
                                    <div class="supportTicket-single-item">
                                        <div class="supportTicket-single-flex">
                                            <div class="supportTicket-single-content">
                                                <div class="supportTicket-single-content-header d-flex align-items-center gap-3">
                                                    <span class="supportTicket-single-content-id">#{{ $ticket_details->id }}</span>
                                                    <div class="supportTicket-single-content-btn gap-2 flex-btn">
                                                        @if($ticket_details->status == 'close')
                                                            <a href="javascript:void(0)" class="pending-closed">{{ __('Closed') }}</a>
                                                        @else
                                                            <a href="javascript:void(0)" class="pending-progress completed">{{ __('Open') }}</a>
                                                        @endif
                                                        <a href="javascript:void(0)" class="pending-progress cancel">{{ $ticket_details->priority }}</a>
                                                    </div>
                                                </div>
                                                <h4 class="supportTicket-single-content-title mt-2">{{ $ticket_details->title }}</h4>
                                            </div>
                                            <span class="supportTicket-single-content-time">{{ __('Last update') }} {{ $ticket_details?->get_ticket_latest_message?->updated_at->diffForHumans() ?? $ticket_details->updated_at->diffForHumans() }} </span>
                                        </div>
                                    </div>
                                    <div class="supportTicket-single-item supportTicket-messages-body">
                                        @foreach($ticket_details->message as $message)
                                            @if($message->type == 'admin')
                                                <div class="supportTicket-single-chat">
                                                    <div class="supportTicket-single-chat-flex">
                                                        <div class="supportTicket-single-chat-thumb">
                                                            <img src="{{ asset('assets/static/img/admin/admin.jpg') }}" alt="{{ __('admin') }}">
                                                        </div>
                                                        <div class="supportTicket-single-chat-contents">
                                                            <div class="supportTicket-single-chat-box">
                                                                <p class="supportTicket-single-chat-message text_style_manege">
                                                                    {{ $message->message }}
                                                                </p>
                                                                @if($message->attachment)
                                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                                        <a href="{{ render_frontend_cloud_image_if_module_exists('ticket/chat-messages/'.$message->attachment, load_from: $message->load_from) }}"
                                                                           download class="single-refundRequest-item-uploads">
                                                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                                                            {{ __('Download Attachment') }}
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ asset('assets/uploads/ticket/chat-messages/'.$message->attachment) }}"
                                                                           class="single-refundRequest-item-uploads">
                                                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                                                            {{ __('Download Attachment') }}
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <p class="supportTicket-single-chat-time mt-2">{{ $message->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="supportTicket-single-chat reply">
                                                    <div class="supportTicket-single-chat-flex">
                                                        <div class="supportTicket-single-chat-thumb">
                                                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                                <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $ticket_details?->client?->image, load_from: $ticket_details?->client?->load_from ?? '') }}" alt="{{ __('client') }}">
                                                            @else
                                                                <img src="{{ asset('assets/uploads/profile/'.$ticket_details->client?->image) }}" alt="{{ __('client') }}">
                                                            @endif
                                                        </div>
                                                        <div class="supportTicket-single-chat-contents">
                                                            <div class="supportTicket-single-chat-box">
                                                                <p class="supportTicket-single-chat-message text_style_manege">
                                                                    {{ $message->message }}
                                                                </p>
                                                                @if($message->attachment)
                                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                                        <a href="{{ render_frontend_cloud_image_if_module_exists('ticket/chat-messages/'.$message->attachment, load_from: $message->load_from) }}"
                                                                           download class="single-refundRequest-item-uploads">
                                                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                                                            {{ __('Download Attachment') }}
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ asset('assets/uploads/ticket/chat-messages/'.$message->attachment) }}"
                                                                           class="single-refundRequest-item-uploads">
                                                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                                                            {{ __('Download Attachment') }}
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <p class="supportTicket-single-chat-time mt-2">{{ $message->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="supportTicket-single-item">
                                        <div class="supportTicket-single-chat-replyForm">
                                            <x-validation.error />
                                            <form action="{{ route('client.ticket.details',$ticket_details->id) }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="supportTicket-single-chat-replyForm-input">
                                                    <textarea name="message" id="message" class="form-message" placeholder="{{ __('Write your reply....') }}"></textarea>
                                                </div>

                                                <div class="supportTicket-single-chat-replyForm-submit flex-between align-items-center mt-3">
                                                    <div>
                                                        <div class="supportTicket_single__attachment mt-3">
                                                            <input type="file" name="attachment" id="attachment" class="file_upload">
                                                        </div>
                                                        <div class="supportTicket-single-chat-replyForm-input">
                                                            <label for="email_notify"><input type="checkbox" name="email_notify" id="email_notify"> {{ __('Email Notify') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="btn-wrapper d-flex flex-wrap gap-2">
                                                        <button type="submit" class="btn-profile btn-bg-1 send_reply">{{ __('Send Reply') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Settings area end -->
    </main>
@endsection

@section('script')
    @include('supportticket::client.ticket-js')
    <x-summernote.summernote-js />
@endsection
