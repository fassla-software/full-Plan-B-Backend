@extends('backend.layout.master')
@section('title', __('Update Admin Info'))
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
                            <h4 class="customMarkup__single__title">{{ __('Update Your Info') }}</h4>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error />
                            <form action="{{ route('admin.update.info') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text :title="__('Name')" :type="'text'" :name="'name'" :value="$admin->name" :class="'form-control'" :placeholder="__('Enter name')" />
                                <x-form.text :title="__('Username')" :type="'text'" :name="'username'" :value="$admin->username" :class="'form-control'" :placeholder="__('Enter username')" />
                                <x-form.text :title="__('Email')" :type="'email'" :name="'email'" :value="$admin->email" :class="'form-control'" :placeholder="__('Enter email')" />
                                <x-form.password :title="__('Enter new password')" :name="'password'" :id="'password'" :class="'form-control'" :placeholder="__('Enter new password')"/>
                                <x-form.password :title="__('Confirm new password')" :name="'new_password'" :id="'new_password'" :class="'form-control'" :placeholder="__('Confirm new password')"/>
                                <x-backend.image :title="__('Profile Image')" :name="'image'" :dimentions="'48x48'" :id="$admin->image"/>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4 add_skill">{{ __('Update') }}</button>
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
