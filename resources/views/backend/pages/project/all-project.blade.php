{{--@extends('backend.layout.master')--}}
{{--@section('title', __('All Projects'))--}}
{{--@section('style')--}}
{{--    <x-select2.select2-css/>--}}
{{--@endsection--}}
{{--@section('content')--}}
{{--    <div class="dashboard__body">--}}
{{--        <div class="row">--}}
{{--            <div class="col-lg-12">--}}
{{--                <div class="customMarkup__single">--}}
{{--                    <div class="customMarkup__single__item">--}}
{{--                        <div class="customMarkup__single__item__flex">--}}
{{--                            <h4 class="customMarkup__single__title">{{ __('All Projects') }}</h4>--}}
{{--                            <x-search.search-in-table :id="'string_search'" />--}}
{{--                        </div>--}}
{{--                        <div class="customMarkup__single__inner mt-4">--}}
{{--                            <!-- Table Start -->--}}
{{--                            <div class="custom_table style-04 search_result">--}}
{{--                                @include('backend.pages.project.search-result')--}}
{{--                            </div>--}}
{{--                            <!-- Table End -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@section('script')--}}
{{--    <x-sweet-alert.sweet-alert2-js/>--}}
{{--    <x-select2.select2-js/>--}}
{{--    @include('backend.pages.project.project-js')--}}

{{--@endsection--}}
@extends('backend.layout.master')
@section('title', __('All Services'))
@section('style')
    <x-select2.select2-css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Equipment') }}</h4>
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                <x-validation.error />
                                <table class="DataTable_activation">
                                    <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Location') }}</th>
                                        <th>{{ __('Category ID') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($paginated->items() as $equipment)
                                        <tr>
                                            <td>{{ $equipment['id'] }}</td>
                                            <td>{{ $equipment['name'] ?? 'N/A' }}</td>
                                            <td>
                                                @if(!empty($equipment['sub_category_image']))
                                                    <img width="100" height="100" src="{{ $equipment['sub_category_image'] }}" alt="Equipment Image">
                                                @else
                                                    <img width="100" height="100" src="{{ asset('assets/uploads/no-image.png') }}" alt="No Image">
                                                @endif
                                            </td>
                                            <td>{{ $equipment['current_location'] ?? 'N/A' }}</td>
                                            <td>{{ $equipment['category_id'] ?? 'N/A' }}</td>
                                            <td>
                                                <x-status.table.active-inactive :status="$equipment['status'] ?? 0"/>
                                            </td>
                                            <td>
                                                <x-status.table.select-action :title="__('Select Action')"/>
                                                <ul class="dropdown-menu status_dropdown__list">
                                                    <li class="status_dropdown__item">
                                                        <a href="{{ route('admin.project.details', $equipment['id']) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('View Details') }}</a>
                                                    </li>
                                                    <li class="status_dropdown__item">
                                                        <x-popup.delete-popup :title="__('Delete Equipment')" :url="route('admin.project.delete', $equipment['id'])"/>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <x-pagination.laravel-paginate :allData="$paginated"/>
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
    <x-sweet-alert.sweet-alert2-js/>
    <x-select2.select2-js/>
    @include('backend.pages.project.project-js')
@endsection


