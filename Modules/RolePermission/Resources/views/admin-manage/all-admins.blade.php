@extends('backend.layout.master')
@section('title', __('All Admins'))
@section('style')
    <x-select2.select2-css/>
    <style>
        #edit_user_details {
            height: calc(100vh - 210px);
            overflow-y: auto;
        }
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Admins') }}</h4>

                            <a href="{{ route('admin.create') }}" class="btn btn-primary btn-md d-flex align-items-center">
                                <span class="btn_plus_icon me-1">
                                    <i class="fa-solid fa-plus"></i>
                                </span>
                                {{ __("Add New Admin") }}
                            </a>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice
                                :class="'mb-5'"
                                :description="__('Notice: All admins created by super admin.')"
                            />
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                <x-validation.error />
                                <table class="table_activation">
                                    <thead>
                                    <tr>
                                        <th>{{__('ID')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Image')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($all_admins->count() >=1)
                                        @foreach($all_admins as $admin)
                                            <tr>
                                                <td>{{ $admin->id }}</td>
                                                <td>{{ $admin->name }}</td>
                                                <td>{!! render_image_markup_by_attachment_id($admin->image,'','thumb') !!}</td>
                                                <td>
                                                    <x-status.table.select-action :title="__('Select Action')"/>
                                                    <ul class="dropdown-menu status_dropdown__list">
                                                        <li class="status_dropdown__item">
                                                            <a class="btn dropdown-item status_dropdown__list__link" href="{{ route('admin.edit',$admin->id) }}">
                                                                {{ __('Edit Admin') }}
                                                            </a>
                                                        </li>
                                                        <li class="status_dropdown__item">
                                                            <a class="btn dropdown-item status_dropdown__list__link change_admin_password"
                                                                data-bs-target="#adminPasswordModal"
                                                                data-bs-toggle="modal"
                                                                data-admin-id="{{ $admin->id }}"
                                                            >
                                                                {{ __('Change Password') }}
                                                            </a>
                                                        </li>
                                                        @if($admin->name != 'Super Admin')
                                                        <li class="status_dropdown__item">
                                                            <x-popup.delete-popup :title="__('Delete Admin')" :url="route('admin.delete',$admin->id)"/>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
                                    @endif
                                    </tbody>
                                </table>

                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('rolepermission::admin-manage.password-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    @include('rolepermission::admin-manage.admin-js')
@endsection
