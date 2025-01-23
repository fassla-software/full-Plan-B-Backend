@extends('backend.layout.master')
@section('title', __('Education Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Education Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.account.education')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Menu Title')" :type="__('text')" :name="'education_menu_title'" :value="get_static_option('education_menu_title') ?? '' " :placeholder="__('Enter menu title')"/>
                                <br>
                                <x-form.text :title="__('Menu Subtitle')" :type="__('text')" :name="'education_menu_sub_title'" :value="get_static_option('education_menu_sub_title') ?? '' " :placeholder="__('Enter menu subtitle')"/>
                                <br>
                                <x-form.text :title="__('Education Title')" :type="__('text')" :name="'education_title'" :value="get_static_option('education_title') ?? '' " :placeholder="__('Enter education title')"/>
                                <br>
                                <x-form.text :title="__('Inner Title')" :type="__('text')" :name="'education_inner_title'" :value="get_static_option('education_inner_title') ?? '' " :placeholder="__('Enter inner title')"/>
                                <br>
                                <x-form.text :title="__('Modal Title')" :type="__('text')" :name="'education_modal_title'" :value="get_static_option('education_modal_title') ?? '' " :placeholder="__('Enter modal title')"/>
                                <br>
                                <x-form.text :title="__('Edit Modal Title')" :type="__('text')" :name="'education_edit_modal_title'" :value="get_static_option('education_edit_modal_title') ?? '' " :placeholder="__('Enter edit modal title')"/>
                                <br>
                                @can('education-page-settings-update')
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
