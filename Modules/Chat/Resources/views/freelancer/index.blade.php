@extends('frontend.layout.master')
@section('site_title',__('Live Chat'))

@section('style')
    <x-summernote.summernote-css />
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
                    @if($freelancer_chat_list->count() > 0)
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
                                            @foreach($freelancer_chat_list as $freelancer_chat)
                                                <x-chat::freelancer.client-list :freelancerChat="$freelancer_chat" />
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="chat-wrapper-details">

                                    <div class="chat-wrapper-details-header d-none flex-between" id="chat_header">

                                    </div>

                                    <div class="chat-wrapper-details-inner client-chat-body" id="chat_body">

                                    </div>

                                    <div class="chat-wrapper-details-footer profile-border-top d-none" id="freelancer-message-footer">
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
                                                        <a href="javascript:void(0)" class="btn-profile btn-bg-1" id="freelancer-send-message-to-client">{{ __('Send Message') }}</a>
                                                    @endif
                                                @else
                                                    <a href="javascript:void(0)" class="btn-profile btn-bg-1" id="freelancer-send-message-to-client">{{ __('Send Message') }}</a>
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('freelancer.offer.send') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="client_id" id="client_id">
                    <input type="hidden" name="pay_by_milestone" id="pay_by_milestone">
                    <input type="hidden" name="pay_at_once" id="pay_at_once" value="pay-at-once">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>{{ __('Send Offer') }}</h3>
                        </div>
                        <div class="modal-body">
                            <x-notice.general-notice
                                :description="__('Notice: Please discuss project requirements and budget with the client before sending an offer to prevent misunderstandings.')"
                                :description1="__('Notice: If pay by milestone you can skip description section')"
                            />
                            <div class="offer_total_price mt-5 setup-bank-form-item setup-bank-form-item-icon">
                                <labe><strong>{{ __('Offer Price') }}</strong></labe>
                                <input type="number" class="form-control" name="offer_price" id="offer_price" placeholder="{{ __('Enter Price') }}">
                                <span class="input-icon">{{ get_static_option('site_global_currency') ?? '' }}</span>
                            </div>
                            <br>

                            <div class="d-flex flex-wrap gap-4 mb-4">

                                <div id="pay_at_once_btn" class="identity-verifying-list active">
                                    <strong>{{ __('Pay at Once') }}</strong>
                                    <span>{{ __('You will get the amount after complete the job.') }}</span>
                                </div>

                                <div id="pay_by_milestone_btn" class="identity-verifying-list">
                                    <strong>{{ __('Pay by Milestones') }}</strong>
                                    <span>{{ __('You will get the amount after complete each milestone.') }}</span>
                                </div>

                            </div>

                            <div class="description_wrapper">
                                <div class="row g-4">
                                    <div class="col-sm-6">
                                        <div class="single-input">
                                            <label class="label-title">{{ __('Revision') }}</label>
                                            <input type="number" min="1" max="200" class="form-control" name="offer_revision" id="offer_revision" placeholder="{{ __('Enter Revision') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="single-input">
                                            <x-duration.delivery-time :class="'single-input set_dead_line'" :title="__('Delivery Time')" :name="'offer_deadline'" :id="'offer_deadline'" />
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="single-input">
                                            <label class="label-title">{{ __('Description') }}</label>
                                            <textarea name="offer_description" id="offer_description" rows="5" class="form-control summernote" placeholder="{{ __('Enter a description') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="myJob-wrapper-single milestone_wrapper d-none">
                                <div class="myJob-wrapper-single-header profile-border-bottom">
                                    <h4 class="myJob-wrapper-single-title">{{ __('Milestone') }}</h4>
                                </div>
                                <div class="myJob-wrapper-single-milestone milestone-contractor-parent">
                                    <div class="myJob-wrapper-single-milestone-item">
                                        <div class="myJob-wrapper-single-flex flex-between align-items-start">
                                            <div class="myJob-wrapper-single-contents">
                                                <div class="row g-4">
                                                    <div class="col-sm-12">
                                                        <div class="single-input">
                                                            <label class="label-title">{{ __('title') }}</label>
                                                            <input type="text" class="form-control milestone_title" name="milestone_title[]" placeholder="{{ __('Enter Title') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="single-input">
                                                            <label class="label-title">{{ __('Description') }}</label>
                                                            <textarea cols="30" rows="5" class="form-control milestone_description" name="milestone_description[]" placeholder="{{ __('Enter Description') }}"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="single-input">
                                                            <label class="label-title">{{ __('Price') }}</label>
                                                            <input type="number" class="form-control milestone_price" name="milestone_price[]" placeholder="{{ __('Enter Price') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="single-input">
                                                            <label class="label-title">{{ __('Revision') }}</label>
                                                            <input type="number" min="1" max="100" class="form-control milestone_revision" name="milestone_revision[]" placeholder="{{ __('Enter Revision') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="single-input">
                                                            <x-duration.delivery-time :class="'single-input'" :selectClass="'form-control milestone_deadline set_dead_line'" :title="__('Delivery Time')" :name="'milestone_deadline[]'" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-wrapper remove-milestone-contractor mt-4">
                                            <a href="#" class="btn-profile btn-bg-cancel">{{ __('Remove') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-wrapper mt-4">
                                    <a href="javascript:void(0)" class="btn-profile btn-outline-gray add-contract-milestone"><i class="fa-solid fa-plus"></i>{{ __('Add Milestone') }}</a>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-profile btn-outline-gray btn-hover-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn-profile btn-bg-1 send_offer_realtime_validation">{{ __('Send Offer') }}</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <audio id="chat-alert-sound" style="display: none">
            <source src="{{ asset('assets/uploads/chat_image/sound/facebook_chat.mp3') }}" />
        </audio>
    </main>
@endsection

@section('script')
    <script src="{{ asset('assets/common/js/helpers.js') }}"></script>
    <script>
        let client_list = { {{ $arr }} };
    </script>
    <x-chat::livechat-js />
    <x-chat::freelancer.freelancer-chat-js />

    <script>
        //:get_client_id
        $(document).on('click','.get_client_id',function(){
            $('#client_id').val($(this).data('client-id'));
        });

        //pay by milestone
        $(document).on('click','#pay_by_milestone_btn',function(){
            $('.milestone_wrapper').removeClass('d-none');
            $('.description_wrapper').addClass('d-none');

            $('#pay_by_milestone').val('pay-by-milestone');
            $('#pay_at_once').val('');

            $( "#pay_by_milestone_btn").addClass( "active" );
            $( "#pay_at_once_btn").removeClass( "active" );
        });

        //pay at once
        $(document).on('click','#pay_at_once_btn',function(){
            $('.description_wrapper').removeClass('d-none');
            $('.milestone_wrapper').addClass('d-none');

            $('#pay_at_once').val('pay-at-once');
            $('#pay_by_milestone').val('');

            $( "#pay_at_once_btn").addClass( "active" );
            $( "#pay_by_milestone_btn").removeClass( "active" );

        });

        //send_offer_realtime_validation
        $(document).on('click','.send_offer_realtime_validation',function(){

            let pay_by_milestone = $('#pay_by_milestone').val();
            let pay_at_once = $('#pay_at_once').val();
            let offer_price = $('#offer_price').val();
            let offer_revision = $('#offer_revision').val();
            let offer_deadline = $('#offer_deadline').val();

            if(offer_price == ''){
                toastr_warning_js("{{ __('Please fill price field') }}")
                return false;
            }

            if(pay_at_once == 'pay-at-once'){
                if(offer_revision == '' || offer_deadline == ''){
                    toastr_warning_js("{{ __('Please fill all fields') }}")
                    return false;
                }
            }

            if(pay_by_milestone == 'pay-by-milestone'){

                let milestone_title = [], milestone_description = [], milestone_price = [], milestone_revision = [], milestone_deadline = [], total_milestone_price = 0;

                $('.milestone_title').each(function() {
                    let value = $(this).val();
                    if (value) {
                        milestone_title.push(value);
                    }
                });

                $('.milestone_description').each(function() {
                    let value = $(this).val();
                    if (value) {
                        milestone_description.push(value);
                    }
                });


                $('.milestone_price').each(function() {
                    let value = $(this).val();
                    if (value) {
                        milestone_price.push(value);
                        total_milestone_price = parseInt(total_milestone_price) + parseInt(value);
                    }
                });

                $('.milestone_revision').each(function() {
                    let value = $(this).val();
                    if (value) {
                        milestone_revision.push(value);
                    }
                });

                $('.milestone_deadline').each(function() {
                    let value = $(this).val();
                    if (value) {
                        milestone_deadline.push(value);
                    }
                });

                if(offer_price != total_milestone_price){
                    toastr_warning_js("{{ __('Total milestone price must be equal to offer price') }}")
                    return false;
                }

                if (offer_price == '' || milestone_title.length === 0 || milestone_description.length === 0 || milestone_price.length === 0 || milestone_revision.length === 0 || milestone_deadline.length === 0) {
                    toastr_warning_js("{{ __('Please fill all fields') }}")
                    return false;
                }
            }
        })
    </script>

    <script>
        @if(count($errors) > 0)
        @foreach($errors->all() as $error)
        toastr.warning("{{ $error }}");
        @endforeach
        @endif
    </script>
    <x-summernote.summernote-js />
@endsection
