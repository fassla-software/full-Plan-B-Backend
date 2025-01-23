@extends('backend.layout.master')
@section('title', __('Ticket Details'))
@section('style')
    <style>
        .text_style_manege{white-space: pre-line}
        .supportTicket-messages-body {
            max-height: 380px;
            overflow-y: auto;
        }
        .supportTicket_single__attachment {
            display: flex;
        }
        .text-end.margin-reverse-30 {
            margin-top: -38px;
        }
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-xxl-8">
                <div class="supportTicket bg-white padding-20 radius-10">
                    <div class="supportTicket_single radius-10">
                        <div class="supportTicket_single__item">
                            <div class="supportTicket_single__flex">
                                <div class="supportTicket_single__content">
                                    <div class="supportTicket-single-content-header d-flex align-items-center gap-3">
                                        <span class="supportTicket_single__content__id">#{{ $ticket_details->id }}</span>
                                        <div class="supportTicket_single__content__btn gap-2 flex-btn">
                                            @if($ticket_details->status == 'open')
                                                <a href="javascript:void(0)" class="pending-progress completed">{{ __('Open') }}</a>
                                            @else
                                                <a href="javascript:void(0)" class="pending-progress closed">{{ __('Closed') }}</a>

                                            @endif
                                            <a href="javascript:void(0)" class="pending-progress cancel">{{ $ticket_details->priority }}</a>
                                        </div>
                                    </div>
                                    <h4 class="supportTicket_single__content__title mt-2">{{ $ticket_details->title }}</h4>
                                </div>
                                <span class="supportTicket_single__content__time"> {{ __('Last update') }} {{ $ticket_details?->get_ticket_latest_message?->updated_at->diffForHumans() ?? $ticket_details->updated_at->diffForHumans() }} </span>
                            </div>
                        </div>

                        <div class="supportTicket_single__item supportTicket-messages-body">
                            @foreach($ticket_details->message as $message)
                                @if($message->type == 'admin')
                                    <div class="supportTicket_single__chat">
                                    <div class="supportTicket_single__chat__flex">
                                        <div class="supportTicket_single__chat__thumb">
                                            @if(Auth::check() && Auth::guard('admin')->user()->image)
                                                {!! render_image_markup_by_attachment_id(Auth::guard('admin')->user()->image,'','thumb') !!}
                                            @else
                                                <img src="{{ asset('assets/static/img/admin/admin.jpg') }}" alt="{{ __('admin') }}">
                                            @endif
                                        </div>
                                        <div class="supportTicket_single__chat__contents">
                                            <div class="supportTicket_single__chat__box">
                                                <p class="supportTicket_single__chat__message text_style_manege">
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
                                                           download class="supportTicket_single__uploads">
                                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                                            {{ __('Download Attachment') }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                            <p class="supportTicket_single__chat__time mt-2">{{ $message->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <div class="supportTicket_single__chat reply">
                                        <div class="supportTicket_single__chat__flex">
                                            <div class="supportTicket_single__chat__thumb">
                                                @if($ticket_details->freelancer?->image)
                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $ticket_details?->freelancer?->image, load_from: $ticket_details?->freelancer?->load_from ?? '') }}" alt="{{ __('profile img') }}">
                                                    @else
                                                        <img src="{{ asset('assets/uploads/profile/'.$ticket_details?->freelancer?->image) }}" alt="{{ __('freelancer') }}">
                                                    @endif
                                                @else
                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $ticket_details->client?->image, load_from: $ticket_details?->client?->load_from ?? '') }}" alt="{{ __('profile img') }}">
                                                    @else
                                                        <img src="{{ asset('assets/uploads/profile/'.$ticket_details->client?->image) }}" alt="{{ __('client') }}">
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="supportTicket_single__chat__contents">
                                                <div class="supportTicket_single__chat__box">
                                                    <p class="supportTicket_single__chat__message text_style_manege">
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
                                                               download class="supportTicket_single__uploads">
                                                                <i class="fa-solid fa-cloud-arrow-down"></i>
                                                                {{ __('Download Attachment') }}
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                                <p class="supportTicket_single__chat__time mt-2">{{ $message->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="supportTicket_single__item">
                            <div class="supportTicket_single__chat__replyForm">
                                <x-validation.error />
                                <form action="{{ route('admin.ticket.details',$ticket_details->id) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="supportTicket_single__chat__replyForm__input">
                                        <textarea name="message" id="message" class="form-message" placeholder="{{ __('Write your reply....') }}"></textarea>
                                    </div>

                                    <div class="supportTicket_single__attachment mt-3">
                                        <span class="supportTicket_single__attachment__para"><i class="fa-solid fa-paperclip"></i></span>
                                        <input type="file" name="attachment" id="attachment">
                                    </div>

                                    <div class="supportTicket-single-chat-replyForm-input">
                                        <label for="email_notify" class="label-title"><input type="checkbox" name="email_notify" id="email_notify"> {{ __('Email Notify') }}</label>
                                    </div>
                                    <div class="btn-wrapper d-flex flex-between profile-border-top">
                                        @can('support-ticket-reply')
                                            <div class="btn-wrapper flex-btn gap-2">
                                                <button type="submit" class="btn-profile btn-bg-1 send_reply">{{ __('Send Reply') }}</button>
                                            </div>
                                        @endcan
                                    </div>
                                </form>
                            </div>
                            <div class="text-end margin-reverse-30">
                                @can('support-ticket-close')
                                    @if($ticket_details->status === 'open')
                                        <x-status.table.status-change :title="__('Close Ticket')" :class="'btn btn-danger swal_status_change_button'" :url="route('admin.ticket.status',$ticket_details->id)"/>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4">
                <div class="supportTicket bg-white padding-20 radius-10">
                    <div class="supportTicket_single radius-10">
                        <div class="supportTicket_single__item">
                            <div class="supportTicket_single__flex">
                                <div class="supportTicket_single__content">
                                    <h4 class="supportTicket_single__content__title mt-2">{{ __('Ticket Details') }}</h4>
                                    <div class="supportTicket_single__content__btn gap-2 flex-btn mt-3">
                                        <p>{!! $ticket_details->description ?? __('No Details') !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    @include('supportticket::backend.ticket.ticket-js')
@endsection
