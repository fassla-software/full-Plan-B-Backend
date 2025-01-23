@extends('backend.layout.master')
@section('title', __('Skill Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Skill Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.account.skill')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Menu Title')" :type="__('text')" :name="'skill_menu_title'" :value="get_static_option('skill_menu_title') ?? '' " :placeholder="__('Enter Menu title')"/>
                                <br>
                                <x-form.text :title="__('Menu Subtitle')" :type="__('text')" :name="'skill_menu_sub_title'" :value="get_static_option('skill_menu_sub_title') ?? '' " :placeholder="__('Enter Menu subtitle')"/>
                                <br>
                                <x-form.text :title="__('Skill Title')" :type="__('text')" :name="'skill_title'" :value="get_static_option('skill_title') ?? '' " :placeholder="__('Enter skill title')"/>
                                @can('skill-page-settings-update')
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
