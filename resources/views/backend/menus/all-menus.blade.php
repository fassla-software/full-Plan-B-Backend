@extends('backend.layout.master')
@section('title', __('All Menus'))
@section('style')
    <x-data-table.data-table-css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-7">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('All Menus') }}</h4>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04">
                                <table class="DataTable_activation">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($all_menus as $menu)
                                            <tr>
                                                <td>{{ $menu->id }}</td>
                                                <td>{{ $menu->title }}</td>
                                                <td><x-status.table.menu :status="$menu->status" :menuID="$menu->id" /></td>
                                                <td>{{ $menu->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <x-status.table.select-action :title="__('Select Action')" />
                                                    <ul class="dropdown-menu status_dropdown__list">
                                                        @can('menu-edit')
                                                            <li class="status_dropdown__item"><x-btn.edit :title="__('Edit Menu')"
                                                                    :url="route('admin.menu.edit', $menu->id)" /></li>
                                                        @endcan
                                                        @can('menu-delete')
                                                            <li class="status_dropdown__item"><x-popup.delete-popup
                                                                    :title="__('Delete Menu')" :url="route('admin.menu.delete', $menu->id)" /></li>
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
            <div class="col-lg-5">
                <x-validation.error />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Add New Menu') }}</h4>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{ route('admin.menu') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="title" class="label-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="title"
                                        placeholder="{{ __('Enter menu title') }}" class="form-control">
                                </div>
                                @can('menu-add')
                                    <x-btn.submit class="btn btn-primary" :title="__('Add Menu')" />
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

    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('.DataTable_activation').DataTable();
            });
        }(jQuery));
    </script>
@endsection
