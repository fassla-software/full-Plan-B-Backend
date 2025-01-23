@extends('backend.layout.master')
@section('title', __('All Templates'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Templates') }}</h4>
                        </div>
{{--                        <div class="search_delete_wrapper">--}}
{{--                            @can('email-template-delete')--}}
{{--                            <x-bulk-action.bulk-action/>--}}
{{--                            @endcan--}}
{{--                            <x-search.search-in-table :id="'string_search'"/>--}}
{{--                        </div>--}}
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                @include('emailtemplate::template.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
