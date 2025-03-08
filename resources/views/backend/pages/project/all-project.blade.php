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
                            <div class="d-flex justify-content-end mb-3">
                                <a href="{{ route('admin.equipment.export') }}" class="btn btn-success">
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
    <th>{{ __('Name') }}</th>
    <th>{{ __('User Name') }}</th>
    <th>{{ __('Image') }}</th>
    <th>{{ __('Category') }}</th>
    <th>{{ __('Model') }}</th>
    <th>{{ __('Created At') }}</th>
    <th>{{ __('Actions') }}</th>
</tr>
</thead>
<tbody>
@foreach($paginated->items() as $equipment)
    <tr>
        <td>{{ $equipment['id'] }}</td>
        <td>{{ $equipment['name'] ?? 'N/A' }}</td>
        <td>{{ $equipment['user_name'] ?? 'N/A' }}</td>
        <td>
            @if(!empty($equipment['sub_category_image']))
                <img width="100" height="100" src="{{ $equipment['sub_category_image'] }}" alt="Equipment Image">
            @else
                <img width="100" height="100" src="{{ asset('assets/uploads/no-image.png') }}" alt="No Image">
            @endif
        </td>
        <td>{{ $equipment['category_name'] ?? 'N/A' }}</td>
        <td>{{ $equipment['model'] ?? 'N/A' }}</td>
        <td>{{ $equipment['created_at'] ?? 'N/A' }}</td>
        <td>
            <x-status.table.select-action :title="__('Select Action')" />
            <ul class="dropdown-menu status_dropdown__list">
                <li class="status_dropdown__item">
                    <a href="{{ route('admin.project.details', $equipment['id']) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('View Details') }}</a>
                </li>
                <li class="status_dropdown__item">
                    <button class="btn dropdown-item status_dropdown__list__link delete-equipment-button"
                            data-id="{{ $equipment['id'] }}">
                        {{ __('Delete Service') }}
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
        // Handle delete button click
        $(document).on('click', '.delete-equipment-button', function () {
            var id = $(this).data('id');
            var url = '{{ route("admin.project.delete", ":id") }}'.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success').then(() => {
                                    location.reload(); // Refresh the page after deletion
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endsection



