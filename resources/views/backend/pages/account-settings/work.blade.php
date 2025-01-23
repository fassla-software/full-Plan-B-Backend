@extends('backend.layout.master')
@section('title', __('Work Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Work Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.account.work')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Menu Title')" :type="__('text')" :name="'work_menu_title'" :value="get_static_option('work_menu_title') ?? '' " :placeholder="__('Enter Menu title')"/>
                                <br>
                                <x-form.text :title="__('Menu Subtitle')" :type="__('text')" :name="'work_menu_sub_title'" :value="get_static_option('work_menu_sub_title') ?? '' " :placeholder="__('Enter Menu subtitle')"/>
                                <br>
                                <x-form.text :title="__('Work Title')" :type="__('text')" :name="'work_title'" :value="get_static_option('work_title') ?? '' " :placeholder="__('Enter work title')"/>
                                <br>
                                <x-form.text :title="__('Inner Title')" :type="__('text')" :name="'work_inner_title'" :value="get_static_option('work_inner_title') ?? '' " :placeholder="__('Enter inner title')"/>
                                <br>
                                <x-form.text :title="__('Modal Title')" :type="__('text')" :name="'work_modal_title'" :value="get_static_option('work_modal_title') ?? '' " :placeholder="__('Enter modal title')"/>
                                <br>
                                <br>
                                @can('work-page-settings-update')
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
