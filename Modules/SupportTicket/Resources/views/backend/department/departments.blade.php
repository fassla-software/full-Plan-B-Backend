@extends('backend.layout.master')
@section('title', __('All Departments'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Departments') }}</h4>
                            @can('department-add')
                            <x-btn.add-modal :title="__('Add Department')" />
                            @endcan
                        </div>
                        <div class="search_delete_wrapper">
                            <x-bulk-action.bulk-action />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :description="__('Notice: Department status inactive means the department will not show while create a ticket.')" />
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('supportticket::backend.department.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('supportticket::backend.department.add-modal')
    @include('supportticket::backend.department.edit-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.department.delete.bulk.action')"/>
    @include('supportticket::backend.department.department-js')
@endsection
