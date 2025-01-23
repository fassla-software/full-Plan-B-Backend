@extends('backend.layout.master')
@section('title', __('Introduction Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Introduction Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.page.account.introduction')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Menu Title')" :type="__('text')" :name="'introduction_menu_title'" :value="get_static_option('introduction_menu_title') ?? '' " :placeholder="__('Enter Menu title')"/>
                                <br>
                                <x-form.text :title="__('Menu Subtitle')" :type="__('text')" :name="'introduction_menu_sub_title'" :value="get_static_option('introduction_menu_sub_title') ?? '' " :placeholder="__('Enter Menu subtitle')"/>
                                <br>
                                <x-form.text :title="__('Professional Title')" :type="__('text')" :name="'professional_title'" :value="get_static_option('professional_title') ?? '' " :placeholder="__('Enter professional title')"/>
                                <br>
                                <x-form.text :title="__('Intro Title')" :type="__('text')" :name="'intro_title'" :value="get_static_option('intro_title') ?? '' " :placeholder="__('Enter intro title')"/>
                                @can('introduction-page-settings-update')
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

