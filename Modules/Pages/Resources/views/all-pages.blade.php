@extends('backend.layout.master')

@section('title', __('All Pages'))

@section('style')
    <x-data-table.data-table-css />
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('All Pages') }}</h4>
                        @can('page-delete-bulk-action')
                        <x-bulk-action.bulk-action/>
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
                                        <th>{{ __('Create Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($all_pages as $page)
                                        <tr>
                                            <td> <x-bulk-action.bulk-delete-checkbox :id="$page->id"/> </td>
                                            <td>{{ $page->id }}</td>
                                            <td>
                                                {{ $page->title ?? __('Untitled') }}
                                                @if($page->id == get_static_option('home_page'))
                                                    <strong class="text-primary">- {{__('Home Page')}}</strong>
                                                @endif
                                            </td>
                                            <td>{{$page->created_at->diffForHumans()}}</td>
                                            <td><x-status.table.active-inactive :status="$page->status"/></td>
                                            <td>
                                                <x-status.table.select-action :title="__('Select Action')"/>
                                                <ul class="dropdown-menu status_dropdown__list">
                                                    @can('page-edit')
                                                    <li class="status_dropdown__item"><x-btn.edit :title="__('Edit Page')" :url="route('admin.edit.page',$page->id)"/></li>
                                                    @endcan
                                                    @can('page-delete')
                                                        @if($page->id != get_static_option('home_page'))
                                                            <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Page')" :url="route('admin.delete.single.page',$page->id)"/></li>
                                                        @endif
                                                    @endcan
                                                    @if($page->page_builder_status == 'on')
                                                        <li class="status_dropdown__item">
                                                            <a class="dropdown-item status_dropdown__list__link" href="{{route('admin.dynamic.page.builder',['type' =>'dynamic-page','id' => $page->id])}}" target="_blank" >{{ __('Open Page Builder') }}</a>
                                                        </li>
                                                    @endif
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
        </div>
    </div>
@endsection

@section('script')
    <x-data-table.data-table-js />
    <script src="{{asset('assets/common/js//sweetalert2.js')}}"></script>
    <x-bulk-action.bulk-delete-js :url="route('admin.delete.bulk.action.page')"/>

    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                $('#content').summernote({});
                $('.DataTable_activation').DataTable();
            });
        }(jQuery));
    </script>
@endsection
