@extends('backend.layout.master')
@section('title', __('All Admins'))
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
                            <h4 class="customMarkup__single__title">{{ __('Add New Admin') }}</h4>
                            <a class="btn btn-primary btn-md d-flex align-items-center" href="{{ route('admin.all') }}">{{ __('All Admins') }}</a>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error />
                            <form action="{{ route('admin.create') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Name')" :type="'text'" :name="'name'" :class="'form-control'" :placeholder="__('Enter name')" />
                                <x-form.text :title="__('Username')" :type="'text'" :name="'username'" :class="'form-control'" :placeholder="__('Enter username')" />
                                <x-form.text :title="__('Email')" :type="'email'" :name="'email'" :class="'form-control'" :placeholder="__('Enter email')" />
                                <x-form.text :title="__('Password')" :type="'password'" :name="'password'" :class="'form-control'" :placeholder="__('Enter password')" />
                                <x-form.text :title="__('Confirm Password')" :type="'password'" :name="'password_confirmation'" :class="'form-control'" :placeholder="__('Confirm password')" />
                                <x-backend.image :title="__('Profile Image')" :name="'image'" :dimentions="'48x48'"/>

                                <div class="single-input mt-3">
                                    <label class="label-title">{{ __('Select Role') }}</label>
                                    <select name="role" class="form-control">
                                        <option value="">{{ __('Select Role') }}</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4 add_skill">{{ __('Save') }}</button>
                                </div>
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
