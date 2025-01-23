@extends('frontend.layout.master')
@section('site_title',__('Live Chat'))
@section('style')
    <link rel="stylesheet" href="{{ asset("assets/css/vendor-chat.css") }}" />
    <style>
        .disabled-link {
            background-color: #ccc !important;
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection

@section('content')
    <main>

        <!-- Profile Details area Starts -->
        <div class="responsive-overlay"></div>
        <div class="profile-area pat-20 pab-20 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    @if($client_chat_list->count() > 0)
                        <div class="col-lg-12">
                            <div class="chat-wrapper">
                                <div class="chat-wrapper-flex">
                                    <div class="chat-sidebar chatText d-lg-none">
                                        {{__('View Chat List')}}
                                    </div>
                                    <div class="chat-wrapper-contact">
                                        <div class="chat-wrapper-contact-close">
                                            <div class="close-chat d-lg-none"> <i class="fas fa-times"></i> </div>
                                            <ul class="chat-wrapper-contact-list">
                                                @foreach($client_chat_list as $client_chat)
                                                    <x-chat::client.freelancer-list :clientChat="$client_chat" />
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="chat-wrapper-details">

                                        <div class="chat-wrapper-details-header d-none flex-between" id="chat_header" data-freelancer-id="{{ request()->freelancer_id }}">
                                        </div>
                                        <div class="chat-wrapper-details-inner client-chat-body" id="chat_body">
                                        </div>

                                        <div class="chat-wrapper-details-footer profile-border-top d-none" id="client-message-footer">
                                            <div class="chat-wrapper-details-footer-form custom-form">
                                                <form action="#">
                                                    <div class="single-input">
                                                        <textarea name="message" id="message" class="form--control form-message" placeholder="Write your message"></textarea>
                                                    </div>
                                                </form>
                                                <div class="chat-wrapper-details-footer-btn flex-btn justify-content-end mt-3">
                                                    <div class="position-relative">
                                                        <input class="photo-uploaded-file inputTag" id="message-file" type="file">
                                                        <span class="show_uploaded_file"></span>
                                                        <span class="dropMedia__file" id="uploadImage">
                                                            <i class="fa-solid fa-paperclip"></i> {{ __("Attach Files") }}
                                                        </span>
                                                    </div>
                                                    @if(moduleExists('SecurityManage'))
                                                        @if(Auth::guard('web')->user()->freeze_chat == 'freeze')
                                                            <a href="javascript:void(0)" class="btn-profile btn-bg-1 @if(Auth::guard('web')->user()->freeze_chat == 'freeze') disabled-link @endif">{{ __('Send Message') }}</a>
                                                        @else
                                                            <a href="javascript:void(0)" class="btn-profile btn-bg-1" id="client-send-message-to-freelancer">{{ __('Send Message') }}</a>
                                                        @endif
                                                    @else
                                                        <a href="javascript:void(0)" class="btn-profile btn-bg-1" id="client-send-message-to-freelancer">{{ __('Send Message') }}</a>
                                                    @endif
                                                </div>
                                                @if(get_static_option('file_extensions'))
                                                    <div class="chat-wrapper-details-footer-btn-right">
                                                        <small>{{ __('Supported files:') }} {{ implode(', ', json_decode(get_static_option('file_extensions'), true)) }}</small>
                                                    </div>
                                                @else
                                                    <div class="chat-wrapper-details-footer-btn-right">
                                                        <small>{{ __('Supported files: jpeg,jpg,png,pdf,gif,docx,zip') }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-12">
                            <div class="chat-wrapper">

                                <div class="chat-wrapper-flex">
                                    <div class="chat-sidebar d-lg-none">
                                        <i class="fas fa-bars"></i>
                                    </div>

                                    <div class="chat-wrapper-contact">
                                        <div class="chat-wrapper-contact-close">
                                            <div class="close-chat d-lg-none"> <i class="fas fa-times"></i> </div>
                                            <ul class="chat-wrapper-contact-list">
                                                <h4 class="text-danger text-center mt-5">{{ __('No Contacts Yet.') }}</h4>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="chat-wrapper-details"> </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
        <!-- Profile Details area end -->
        <audio id="chat-alert-sound" style="display: none">
            <source src="{{ asset('assets/uploads/chat_image/sound/facebook_chat.mp3') }}" />
        </audio>
    </main>
@endsection

@section('script')
    <script src="{{ asset('assets/common/js/helpers.js') }}"></script>
    <script>
        let freelancer_list = { {{ $arr }} };
    </script>
    <x-chat::livechat-js />
    <x-chat::client.client-chat-js />
@endsection
