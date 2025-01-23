@extends('backend.layout.master')
@section('title', __('Hourly Rate & Photo Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Hourly Rate & Photo Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.account.rate.photo')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Menu Title')" :type="__('text')" :name="'hourly_rate_menu_title'" :value="get_static_option('hourly_rate_menu_title') ?? '' " :placeholder="__('Enter menu title')"/>
                                <br>
                                <x-form.text :title="__('Menu Subtitle')" :type="__('text')" :name="'hourly_rate_menu_sub_title'" :value="get_static_option('hourly_rate_menu_sub_title') ?? '' " :placeholder="__('Enter menu subtitle')"/>
                                <br>
                                <x-form.text :title="__('Hourly Rate Title')" :type="__('text')" :name="'hourly_rate_title'" :value="get_static_option('hourly_rate_title') ?? '' " :placeholder="__('Enter hourly rate title')"/>
                                <br>
                                <x-form.text :title="__('Profile Photo Title')" :type="__('text')" :name="'profile_photo_title'" :value="get_static_option('profile_photo_title') ?? '' " :placeholder="__('Enter profile photo title')"/>
                                @can('photo-page-settings-update')
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
