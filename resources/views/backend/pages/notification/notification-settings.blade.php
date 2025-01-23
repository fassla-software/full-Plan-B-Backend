@extends('backend.layout.master')
@section('title', __('Push Notification Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Push Notification Settings For Mobile App') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :class="'mt-5'" :description="__('Notice: To activate push notifications for mobile app you must upload your firebase json file.')" />
                            @if($firebaseFileExists)
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> {{ __('Firebase JSON file is already uploaded.') }}
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-circle"></i> {{ __('No Firebase JSON file uploaded yet. Please upload a new one.') }}
                                </div>
                            @endif
                            <form action="{{route('admin.notification.settings')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="firebase_json" class="label-title">{{ __('Upload Firebase JSON File') }}</label>
                                    <input type="file" name="firebase_json" id="firebase_json" accept=".json" placeholder="{{ __('Upload Firebase JSON File') }}" class="form-control" >
                                </div>
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
