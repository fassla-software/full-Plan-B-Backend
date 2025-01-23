@extends('backend.layout.master')
@section('title', __('License Settings'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('License Settings') }}</h4>
                            <button class="btn btn-sm btn-primary"
                                    style="padding: 5px; margin-left: 20px"
                                    data-bs-toggle="modal"
                                    data-bs-target="#licenseRequestModal"
                            >{{__("Get License Key")}}
                            </button>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.license.settings')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="site_license_key" class="label-title">{{__('License Key')}}</label>
                                    <input type="text" name="site_license_key"  class="form-control" value="{{get_static_option('site_license_key')}}" >
                                    <small>{{__("enter license key, which you get in your email after verify your license while install or you can get your license by click on \"Get License Key\", then system will send you a license code into your email, check your email inbox and spam folder as well. ")}}</small>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="envato_username" class="label-title">{{__('Envato Username')}}</label>
                                    <input type="text" class="form-control"  name="envato_username" value="{{get_static_option("license_username")}}">
                                </div>
                                @can('withdraw-fee-settings-update')
                                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="licenseRequestModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Request for license key')}}</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route("admin.license.key.generate")}}" id="user_password_change_modal_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email" class="label-title">{{__('Your Email')}}</label>
                            <input type="email" class="form-control" name="email" value="{{get_static_option("license_email")}}">
                            <small>{{__("Make sure you have given valid email, we will send you license key for enable one click update, We'll email you script updates - no spam, just the good stuff!")}} 🌟✉️</small>
                        </div>
                        <div class="form-group mt-3">
                            <label for="envato_username" class="label-title">{{__('Envato Username')}}</label>
                            <input type="text" class="form-control"  name="envato_username" value="{{get_static_option("license_username")}}">
                        </div>
                        <div class="form-group mt-3">
                            <label for="envato_purchase_code" class="label-title">{{__('Envato Purchase code')}}</label>
                            <input type="text" class="form-control" name="envato_purchase_code" value="{{get_static_option("license_purchase_code")}}">
                            <small>{{__('follow this article to know how you will get your envato purchase code for this script')}}
                                <a href="https://xgenious.com/where-can-i-find-my-purchase-code-at-codecanyon/" target="_blank">{{__('how to get envato purchase code')}}</a></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button id="update" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
