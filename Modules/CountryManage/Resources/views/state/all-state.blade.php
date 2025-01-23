@extends('backend.layout.master')
@section('title', __('All States'))
@section('style')
    <x-select2.select2-css />
    <x-data-table.data-table-css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All States') }}</h4>
                            @can('state-add')
                            <x-btn.add-modal :title="__('Add State')" />
                            @endcan
                        </div>
                        <div class="search_delete_wrapper">
                            @can('state-bulk-delete')
                            <x-bulk-action.bulk-action />
                            @endcan
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('countrymanage::state.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('countrymanage::state.add-modal')
    @include('countrymanage::state.edit-modal')
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-select2.select2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.state.delete.bulk.action')"/>
    @include('countrymanage::state.state-js')
@endsection
