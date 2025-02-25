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

                            <div class="d-flex justify-content-end mb-3">
                                <a href="{{ route('admin.job.export') }}" class="btn btn-success">
                                    <i class="fa fa-download"></i> {{ __('Export to Excel') }}
                                </a>
                            </div>

                            <!-- Table Start -->
                            <div class="custom_table style-04 search_result">
                                <x-validation.error />
                                <table class="DataTable_activation">
                                    <thead>
<tr>
    <th>{{ __('ID') }}</th>
{{--    <th>{{ __('Name') }}</th>--}}
    <th>{{ __('Equipment Name') }}</th>
    <th>{{ __('User Name') }}</th>
    <th>{{ __('Image') }}</th>
    <th>{{ __('Category') }}</th>
    <th>{{ __('Created At') }}</th>
    <th>{{ __('Max Arrival Date') }}</th>
    <th>{{ __('Max Offer Deadline') }}</th>
    <th>{{ __('Actions') }}</th>
</tr>
</thead>
<tbody>
@foreach($paginated->items() as $job)
    <tr>
        <td>{{ $job['id'] }}</td>
{{--        <td>{{ $job['name'] ?? 'N/A' }}</td>--}}
        <td>{{ $job['equipment_name'] ?? 'N/A' }}</td>
        <td>{{ $job['user_name'] ?? 'N/A' }}</td>
        <td>
            @if(!empty($job['sub_category_image']))
                <img width="60" height="60" src="{{ $job['sub_category_image'] }}" alt="Job Image">
            @else
                <img width="60" height="60" src="{{ asset('assets/uploads/no-image.png') }}" alt="No Image">
            @endif
        </td>
        <td>{{ $job['category_name'] ?? 'N/A' }}</td>
        <td>{{ $job['created_at'] ?? 'N/A' }}</td>
        <td>{{ $job['max_arrival_date'] ?? 'N/A' }}</td>
        <td>{{ $job['max_offer_deadline'] ?? 'N/A' }}</td>
        <td>
            <x-status.table.select-action :title="__('Select Action')" />
            <ul class="dropdown-menu status_dropdown__list">
                <li class="status_dropdown__item">
                    <a href="{{ route('admin.job.details', $job['id']) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('View Details') }}</a>
                </li>
                <li class="status_dropdown__item">
                    <button class="btn dropdown-item status_dropdown__list__link delete-job-button"
                            data-id="{{ $job['id'] }}">
                        {{ __('Delete Request') }}
                    </button>
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

@section('script')
    <x-sweet-alert.sweet-alert2-js />

    <script>
        $(document).on('click', '.delete-job-button', function (e) {
            e.preventDefault();
            let jobId = $(this).data('id');
            let deleteUrl = "{{ route('admin.job.delete', ':id') }}".replace(':id', jobId);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'There was an error deleting the job.', 'error');
                        }
                    });
                }
            });
        });
    </script>
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
