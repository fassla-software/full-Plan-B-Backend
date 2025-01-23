@extends('backend.layout.master')
@section('title', __('All Custom Form'))
@section('style')
    <x-data-table.data-table-css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-8">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('All Custom Form') }}</h4>
                        @can('form-bulk-delete')
                            <x-bulk-action.bulk-action />
                        @endcan
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04">
                                <table class="DataTable_activation">
                                    <thead>
                                        <tr>
                                            <th class="no-sort">
                                                <div class="mark-all-checkbox">
                                                    <input type="checkbox" class="all-checkbox">
                                                </div>
                                            </th>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Receiving Email') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($all_forms as $form)
                                            <tr>
                                                <td> <x-bulk-action.bulk-delete-checkbox :id="$form->id" /> </td>
                                                <td>{{ $form->id }}</td>
                                                <td>{{ $form->title }}</td>
                                                <td>{{ $form->email }}</td>
                                                <td>
                                                    <x-status.table.select-action :title="__('Select Action')" />
                                                    <ul class="dropdown-menu status_dropdown__list">
                                                        @can('form-edit')
                                                            <li class="status_dropdown__item"><x-btn.edit :title="__('Edit Form')"
                                                                    :url="route('admin.form.edit', $form->id)" /></li>
                                                        @endcan
                                                        @can('form-delete')
                                                            <li class="status_dropdown__item"><x-popup.delete-popup
                                                                    :title="__('Delete Form')" :url="route('admin.form.delete', $form->id)" /></li>
                                                        @endcan
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <x-validation.error />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Add New Form') }}</h4>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{ route('admin.form') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="title" class="label-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                                        placeholder="{{ __('Enter form title') }}" class="form-control">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="email" class="label-title">{{ __('Receiving Email') }}</label>
                                    <input type="text" name="email" id="email" value="{{ old('email') }}"
                                        placeholder="{{ __('Enter receiving email') }}" class="form-control">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="button_text" class="label-title">{{ __('Button Title') }}</label>
                                    <input type="text" name="button_text" id="button_text"
                                        value="{{ old('button_text') }}" placeholder="{{ __('Enter button title') }}"
                                        class="form-control">
                                </div>
                                <div class="single-input mb-3">
                                    <label for="success_message" class="label-title">{{ __('Success Message') }}</label>
                                    <input type="text" name="success_message" id="success_message"
                                        value="{{ old('success_message') }}"
                                        placeholder="{{ __('Enter success message') }}" class="form-control">
                                </div>
                                @can('form-add')
                                    <x-btn.submit class="btn btn-primary" :title="__('Add New Form')" />
                                @endcan
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-data-table.data-table-js />
    <script src="{{ asset('assets/common/js//sweetalert2.js') }}"></script>
    <x-bulk-action.bulk-delete-js :url="route('admin.delete.bulk.action.form')" />

    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('.DataTable_activation').DataTable();
            });
        }(jQuery));
    </script>
@endsection
