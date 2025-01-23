@extends('backend.layout.master')
@section('title', __('Experience Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Experience Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.account.experience')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Menu Title')" :type="__('text')" :name="'experience_menu_title'" :value="get_static_option('experience_menu_title') ?? '' " :placeholder="__('Enter Menu title')"/>
                                <br>
                                <x-form.text :title="__('Menu Subtitle')" :type="__('text')" :name="'experience_menu_sub_title'" :value="get_static_option('experience_menu_sub_title') ?? '' " :placeholder="__('Enter Menu subtitle')"/>
                                <br>
                                <x-form.text :title="__('Experience Title')" :type="__('text')" :name="'experience_title'" :value="get_static_option('experience_title') ?? '' " :placeholder="__('Enter experience title')"/>
                                <br>
                                <x-form.text :title="__('Inner Title')" :type="__('text')" :name="'experience_inner_title'" :value="get_static_option('experience_inner_title') ?? '' " :placeholder="__('Enter inner title')"/>
                                <br>
                                <x-form.text :title="__('Modal Title')" :type="__('text')" :name="'experience_modal_title'" :value="get_static_option('experience_modal_title') ?? '' " :placeholder="__('Enter modal title')"/>
                                <br>
                                <x-form.text :title="__('Edit Modal Title')" :type="__('text')" :name="'experience_edit_modal_title'" :value="get_static_option('experience_edit_modal_title') ?? '' " :placeholder="__('Enter edit modal title')"/>
                                <br>
                                @can('experience-page-settings-update')
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 add_skill'" />
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
