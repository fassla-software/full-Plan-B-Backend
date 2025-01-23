@extends('backend.layout.master')
@section('title', __('Subscription Manual Payment Complete Email to Admin'))
@section('style')
    <x-summernote.summernote-css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Subscription Manual Payment Complete Email To Admin') }}</h4>
                        </div>
                        <div class="search_delete_wrapper">
                            <h4><a class="btn-profile btn-bg-1" href="{{ route('admin.email.template.all') }}">{{ __('All Templates') }}</a></h4>
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error />
                            <form action="{{route('admin.user.subscription.manual.payment.complete.to.admin')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text
                                    :title="__('Email Subject')"
                                    :type="__('text')"
                                    :name="'manual_subscription_complete_subject_to_admin'"
                                    :id="'manual_subscription_complete_subject_to_admin'"
                                    :value="get_static_option('manual_subscription_complete_subject') ?? __('Subscription Manual Payment Pending Email')"
                                />
                                <x-form.summernote
                                    :title="__('Email Message')"
                                    :name="'manual_subscription_complete_message_to_admin'"
                                    :id="'manual_subscription_complete_message_to_admin'"
                                    :value="get_static_option('manual_subscription_complete_message_to_admin') ?? '' "
                                />
                                <small class="form-text text-muted text-danger margin-top-20"><code>@subscription_id</code> {{__('will be replaced by dynamically with subscription id.')}}</small><br>
                                <x-btn.submit :title="__('Save')" :class="'btn-profile btn-bg-1 mt-4 pr-4 pl-4 update_info'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-summernote.summernote-js />
@endsection
