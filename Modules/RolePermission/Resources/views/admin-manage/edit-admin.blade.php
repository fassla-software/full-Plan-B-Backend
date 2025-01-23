@extends('backend.layout.master')
@section('title', __('Edit Admin Info'))
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
                            <h4 class="customMarkup__single__title">{{ __('Edit Admin Info') }}</h4>
                            <a class="btn btn-primary btn-md d-flex align-items-center" href="{{ route('admin.all') }}">{{ __('All Admins') }}</a>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error />
                            <form action="{{ route('admin.edit',$admin->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Name')" :type="'text'" :name="'name'" :value="$admin->name" :class="'form-control'" :placeholder="__('Enter name')" />
                                <x-form.text :title="__('Username')" :type="'text'" :name="'username'" :value="$admin->username" :class="'form-control'" :placeholder="__('Enter username')" />
                                <x-form.text :title="__('Email')" :type="'email'" :name="'email'" :value="$admin->email" :class="'form-control'" :placeholder="__('Enter email')" />
                                <x-backend.image :title="__('Profile Image')" :name="'image'" :dimentions="'48x48'" :id="$admin->image"/>

                                <div class="single-input mt-3">
                                    <label class="label-title">{{ __('Select Role') }}</label>
                                    <select name="role" class="form-control">
                                        <option value="">{{ __('Select Role') }}</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role}}" @if(in_array($role,$admin_role)) selected @endif>{{$role}}</option>

                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
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
