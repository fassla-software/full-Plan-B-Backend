@extends('backend.layout.master')

@section('title', __('Social Login Settings'))

@section('style')

@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Social Login Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.social.login')}}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="single-input mb-3">
                                    <label for="enable_facebook_login" class="label-title"><strong>{{__('Facebook Login Details')}}</strong></label>
                                </div>
                                <div class="single-input mb-3">
                                    <label for="facebook_client_id" class="label-title">{{__('Facebook Client ID')}}</label>
                                    <input type="text" name="facebook_client_id"  class="form-control" value="{{get_static_option('facebook_client_id')}}">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="facebook_client_secret" class="label-title">{{__('Facebook Client Secret')}}</label>
                                    <input type="text" name="facebook_client_secret"  class="form-control" value="{{get_static_option('facebook_client_secret')}}">
                                </div>
                                <p class="info-text">{{__('facebook callback url for your app')}}
                                    <code>{{url('/')}}/facebook/callback</code>
                                    <a href="https:xilancer.com/facebook-login/" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </p>

                                <div class="single-input mb-3">
                                    <label for="enable_google_login" class="label-title"><strong>{{__('Google Login Details')}}</strong></label>
                                </div>
                                <div class="single-input mb-3">
                                    <label for="google_client_id" class="label-title">{{__('Google Client ID')}}</label>
                                    <input type="text" name="google_client_id"  class="form-control" value="{{get_static_option('google_client_id')}}">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="google_client_secret" class="label-title">{{__('Google Client Secret')}}</label>
                                    <input type="text" name="google_client_secret"  class="form-control" value="{{get_static_option('google_client_secret')}}">
                                </div>
                                <p class="info-text">{{__('google callback url for your app')}}
                                    <code>{{url('/')}}/google/callback</code>
                                    <a href="https:xilancer.com/facebook-login/" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                </p>

                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')

@endsection
