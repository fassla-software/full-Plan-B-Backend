@extends('backend.layout.master')
@section('title', __('All Requests'))
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
                            <h4 class="customMarkup__single__title">{{ __('All Requests') }}</h4>
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
    <th>{{ __('User Name') }}</th>
    <th>{{ __('Image') }}</th>
    <th>{{ __('Category') }}</th>
    <th>{{ __('Created At') }}</th>
    <th>{{ __('Actions') }}</th>
</tr>
</thead>
<tbody>
@foreach($paginated->items() as $job)
    <tr>
        <td>{{ $job['id'] }}</td>
        <td>{{ $job['name'] ?? 'N/A' }}</td>
        <td>{{ $job['user_name'] ?? 'N/A' }}</td>
        <td>
            @if(!empty($job['sub_category_image']))
                <img width="100" height="100" src="{{ $job['sub_category_image'] }}" alt="Job Image">
            @else
                <img width="100" height="100" src="{{ asset('assets/uploads/no-image.png') }}" alt="No Image">
            @endif
        </td>
        <td>{{ $job['category_name'] ?? 'N/A' }}</td>
        <td>{{ $job['created_at'] ?? 'N/A' }}</td>
        <td>
            <x-status.table.select-action :title="__('Select Action')" />
            <ul class="dropdown-menu status_dropdown__list">
                <li class="status_dropdown__item">
                    <a href="{{ route('admin.job.details', $job['id']) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('View Details') }}</a>
                </li>
                <li class="status_dropdown__item">
                    <x-popup.delete-popup :title="__('Delete Job')" :url="route('admin.job.delete', $job['id'])"/>
                </li>
            </ul>
        </td>
    </tr>
@endforeach
</tbody>
                                </table>
                                {{-- Table Pagination Links --}}
                                @if ($paginated->hasPages())
                                    <div class="pagination-wrapper">
                                        {{ $paginated->links() }}
                                    </div>
                                @endif

                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


{{--@extends('backend.layout.master')--}}
{{--@section('title', __('All Jobs'))--}}
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
{{--                            <h4 class="customMarkup__single__title">{{ __('All Jobs') }}</h4>--}}
{{--                            <div class="search_delete_wrapper">--}}
{{--                                <x-search.search-in-table :id="'string_search'" />--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="customMarkup__single__inner mt-4">--}}
{{--                            <!-- Table Start -->--}}
{{--                            <div class="custom_table style-04 search_result">--}}
{{--                                @include('backend.pages.job.search-result')--}}
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
{{--    @include('backend.pages.job.job-js')--}}

{{--@endsection--}}
