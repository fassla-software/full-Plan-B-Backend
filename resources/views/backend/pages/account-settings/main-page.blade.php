@extends('backend.layout.master')
@section('title', __('Main Page Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Main Page Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.account.main.page')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Account Page Title')" :type="__('text')" :name="'account_page_title'" :value="get_static_option('account_page_title') ?? '' " :placeholder="__('Enter page title')"/>
                                <br>
                                <x-form.text :title="__('Account Page Skip Title')" :type="__('text')" :name="'account_page_skip_title'" :value="get_static_option('account_page_skip_title') ?? '' " :placeholder="__('Enter skip title')"/>
                                <br>
                                <x-form.text :title="__('Account Page Back Button Title')" :type="__('text')" :name="'account_page_back_button_title'" :value="get_static_option('account_page_back_button_title') ?? '' " :placeholder="__('Enter back button title')"/>
                                @can('account-page-settings-update')
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
