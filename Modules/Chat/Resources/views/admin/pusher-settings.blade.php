@extends('backend.layout.master')
@section('title', __('Pusher Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Pusher Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :class="'mt-5'" :description="__('Notice: To activate live chat you must setup your pusher credentials.')" />
                            <form action="{{route('admin.pusher.settings')}}" method="post">
                                @csrf
                                <x-form.text :title="__('Pusher App ID')" :name="'PUSHER_APP_ID'" :value="env('PUSHER_APP_ID') ?? '' " :placeholder="__('Pusher App ID')"/>
                                <x-form.text :title="__('Pusher App Key')" :name="'PUSHER_APP_KEY'" :value="env('PUSHER_APP_KEY') ?? '' " :placeholder="__('Pusher App Key')"/>
                                <x-form.text :title="__('Pusher App Secret')" :name="'PUSHER_APP_SECRET'" :value="env('PUSHER_APP_SECRET') ?? '' " :placeholder="__('Pusher App Secret')"/>
                                <x-form.text :title="__('Pusher App Cluster')" :name="'PUSHER_APP_CLUSTER'" :value="env('PUSHER_APP_CLUSTER') ?? '' " :placeholder="__('Pusher App Cluster')"/>
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
