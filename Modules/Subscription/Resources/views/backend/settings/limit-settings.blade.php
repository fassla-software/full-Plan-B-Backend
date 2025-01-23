@extends('backend.layout.master')
@section('title', __('Subscription Connect Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Subscription Connect Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :class="'mt-5'" :description="__('Notice: Connect Settings refers to the number of connects deducted for each bid or job proposal. By default, one connect is automatically reduced unless connect is specified.')" />
                            <form action="{{route('admin.subscription.limit.settings')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.number :title="__('Connect Settings')" :min="'1'" :max="'10'" :name="'limit_settings'" :value="get_static_option('limit_settings') ?? '' " :placeholder="__('Limit Settings')"/>
                                @can('subscription-connect-settings-update')
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                                @endcan
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
    <x-media.js/>
@endsection
