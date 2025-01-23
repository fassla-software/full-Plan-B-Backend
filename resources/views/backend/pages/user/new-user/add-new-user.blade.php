@extends('backend.layout.master')
@section('title', __('Add New User'))
@section('style')
    <x-media.css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Add New User') }}</h4>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error/>
                            <form action="{{route('admin.user.add')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <x-form.text :title="__('First Name')" :type="__('text')" :name="'first_name'" :value="old('first_name', '')" :placeholder="__('Enter first name')"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <x-form.text :title="__('Last Name')" :type="__('text')" :name="'last_name'" :value="old('last_name', '')" :placeholder="__('Enter last name')"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <x-form.text :title="__('Username')" :type="__('text')" :name="'username'" :value="old('username', '')" :placeholder="__('Enter username')"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <x-form.text :title="__('Email Address')" :type="__('email')" :name="'email'" :value="old('email', '')" :placeholder="__('Enter email')"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <x-form.text :title="__('Phone Number')" :type="__('phone')" :name="'phone'" :value="old('phone', '')" :placeholder="__('Enter phone')"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="single-input mb-3">
                                            <label class="label-title">{{ __('User Type') }}</label>
                                            <select name="user_type" class="form-control">
                                                <option value="">{{ __('Select Type') }}</option>
                                                <option value="1">{{ __('Client') }}</option>
                                                <option value="2">{{ __('Freelancer') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <x-form.text :title="__('Password')" :type="__('text')" :name="'password'" :value="old('password', '')" :placeholder="__('Enter password')"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <x-form.text :title="__('Confirm Password')" :type="__('text')" :name="'password_confirmation'" :value="old('password_confirmation', '')" :placeholder="__('Confirm password')"/>
                                    </div>

                                </div>
                                <br>
                                <x-btn.submit :title="__('Save')" :class="'btn-profile btn-bg-1 mt-4 pr-4 pl-4 validate_subscription_type'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection

@section('script')
    <x-media.js />
    @include('subscription::backend.subscription.subscription-js')
@endsection
