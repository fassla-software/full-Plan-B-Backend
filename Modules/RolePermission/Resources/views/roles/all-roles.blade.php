@extends('backend.layout.master')
@section('title', __('All Roles'))
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
                            <h4 class="customMarkup__single__title">{{ __('All Roles') }}</h4>
                            <a
                                class="btn btn-primary btn-md d-flex align-items-center"
                                href="javascript:void(0)"
                                data-bs-target="#addRoleModal"
                                data-bs-toggle="modal"
                            ><i class="fas fa-plus-circle"></i> {{ __('Add Role') }}</a>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                <x-validation.error />
                                <table class="table_activation">
                                    <thead>
                                    <tr>
                                        <th>{{__('ID')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($roles->count() >=1)
                                        @foreach($roles as $role)
                                            <tr>
                                                <td>{{ $role->id }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    @if($role->name == 'Super Admin')
                                                        <span>{{ __('By default super admin has all permissions.') }}</span>
                                                    @else
                                                        <x-status.table.select-action :title="__('Select Action')"/>
                                                        <ul class="dropdown-menu status_dropdown__list">
                                                            <li class="status_dropdown__item">
                                                                <a href="javascript:void(0)"
                                                                   class="btn dropdown-item status_dropdown__list__link edit_role_modal"
                                                                   data-bs-target="#editRoleModal"
                                                                   data-bs-toggle="modal"
                                                                   data-role-id="{{ $role->id }}"
                                                                   data-role-name="{{ $role->name }}"
                                                                >{{ __('Edit Role') }}</a>
                                                            </li>
                                                            <li class="status_dropdown__item">
                                                                <a class="btn dropdown-item status_dropdown__list__link" href="{{ route('admin.role.permission',$role->id) }}">
                                                                    {{ __('Assign Permission') }}
                                                                </a>
                                                            </li>
                                                            <li class="status_dropdown__item">
                                                                <x-popup.delete-popup :title="__('Delete Role')" :url="route('admin.role.delete',$role->id)"/>
                                                            </li>
                                                        </ul>
                                                    @endif

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
    @include('rolepermission::roles.add-role-modal')
    @include('rolepermission::roles.edit-role-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    @include('rolepermission::roles.role-js')
@endsection
