@extends('backend.layout.master')
@section('style')
    <x-summernote.summernote-css />
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
@endsection
@section('site-title')
    {{__('All Newsletter')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <x-validation.error />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{__('All Newsletter Subscriber')}}</h4>
                        <div class="btn-wrapper d-flex">
                            @can('newsletter-bulk-action')
                                <div class="bulk-delete-wrapper">
                                    <div class="select-box-wrap d-flex">
                                        <select name="bulk_option" id="bulk_option">
                                            <option value="">{{{__('Bulk Action')}}}</option>
                                            <option value="delete">{{{__('Delete')}}}</option>
                                        </select>
                                        <button class="btn btn-primary btn-sm px-5" id="bulk_delete_btn">{{__('Apply')}}</button>
                                    </div>
                                </div>
                            @endcan
                            @can("newsletter-new")
                                <button class="cmn_btn btn_bg_profile" data-bs-toggle="modal" data-bs-target="#new_subscribe_model">{{ __("Add New Subscribe") }}</button>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap">
                            <table class="table table-default data-table-style newsLetterTable">
                                <thead>
                                @can('newsletter-bulk-action')
                                    <th class="no-sort">
                                        <div class="mark-all-checkbox">
                                            <input type="checkbox" class="all-checkbox">
                                        </div>
                                    </th>
                                @endcan
                                <th>{{__('ID')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_newsletter as $data)
                                    <tr>
                                        @can('newsletter-bulk-action')
                                            <td>
                                                <div class="bulk-checkbox-wrapper">
                                                    <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                                </div>
                                            </td>
                                        @endcan
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->email}} @if($data->verified > 0) <i class="fas fa-check-circle"></i>@endif</td>
                                        <td>
                                            @can('newsletter-delete')
                                                {{--                                                <x-delete-popover :url="route('admin.newsletter.delete',$data->id)"/>--}}
                                            @endcan
                                            @can('newsletter-newsletter-verify-mail-send')
                                                <a class="btn btn-lg btn-primary btn-sm mb-2 me-2 send_mail_modal_btn"
                                                   href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#send_mail_to_subscriber_modal"
                                                   data-email="{{$data->email}}"
                                                >
                                                    <i class="ti-email"></i>
                                                </a>

                                                @if($data->verified <1)
                                                    <form class="mb-2 me-2" style="display: inline;float: left;margin-right: 7px" action="" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$data->id}}">
                                                        <button class="btn btn-sm btn-secondary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __("Send Verify Mail") }}">
                                                            <i class="las la-plus"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('newsletter-new')
                <div class="col-lg-5">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{__('Add New Subscriber')}}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="" method="post">
                                @csrf

                                <div class="form-group">
                                    <label for="email">{{__('Email')}}</label>
                                    <input type="text" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                                </div>

                                <div class="form-group">
                                    <button id="submit" type="submit" class="cmn_btn btn_bg_profile">{{__('Submit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>

    @can('newsletter-newsletter-verify-mail-send')
        <div class="modal fade" id="new_subscribe_model" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Add New Subscriber')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email">{{__('Email')}}</label>
                                <input type="text" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button id="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="send_mail_to_subscriber_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Send Mail To Subscriber')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="" id="send_mail_to_subscriber_edit_modal_form" method="post">
                        <div class="modal-body">
                            @csrf
                            <div class="d-none form-group">
                                <label for="email">{{__('Email')}}</label>
                                <input type="email" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_icon">{{__('Subject')}}</label>
                                <input type="text" class="form-control"  id="subject" name="subject" placeholder="{{__('Subject')}}">
                            </div>
                            <div class="form-group">
                                <label for="message">{{__('Message')}}</label>
                                <input type="hidden" name="message" >
                                <div class="summernote"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button id="submit" type="submit" class="btn btn-primary">{{__('Send Mail')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <x-media.markup/>
@endsection

@section('script')
    <x-summernote.summernote-js />
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>

    @can('newsletter-bulk-action')
        {{--        <x-bulk-action-js :url="route('admin.newsletter.bulk.action')" />--}}
    @endcan

    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {
                <x-btn.submit />

                $(document).on('click','.send_mail_modal_btn',function(){
                    var el = $(this);
                    var email = el.data('email');
                    var form = $('#send_mail_to_subscriber_edit_modal_form');
                    form.find('#email').val(email);
                });

                $(document).on('click', '.swal_delete_button', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '{{ __('Are you sure?') }}',
                        text: '{{ __('You would not be able to revert this item!') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).next().find('.swal_form_submit_btn').trigger('click');
                        }
                    });
                });
            });
        })(jQuery)
    </script>
@endsection
