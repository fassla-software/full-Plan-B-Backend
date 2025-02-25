@extends('backend.layout.master')
@section('title', __('Project Details'))

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Equipment Details') }}</h4>

                        <!-- Equipment Information -->
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <td>{{ $equipment->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <td>{{ $equipment->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Category') }}</th>
                                <td>{{ $equipment->category->category ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Sub-Category Image') }}</th>
                                <td>
                                    @if(!empty($equipment->sub_category_image))
                                        <img width="150" height="150" src="{{ $equipment->sub_category_image }}" alt="Sub Category Image">
                                    @else
                                        <img width="150" height="150" src="{{ asset('assets/uploads/no-image.png') }}" alt="No Image">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Created At') }}</th>
                                <td>{{ $equipment->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>

                        <h4 class="customMarkup__single__title mt-4">{{ __('User Details') }}</h4>

                        <!-- User Information -->
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Email') }}</th>
                                <td>{{ $user->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Phone') }}</th>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Country') }}</th>
                                <td>{{ $user->user_country->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('State') }}</th>
                                <td>{{ $user->user_state->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('City') }}</th>
                                <td>{{ $user->user_city->name ?? 'N/A' }}</td>
                            </tr>
                        </table>

                        <a href="{{ route('admin.project') }}" class="btn btn-secondary">{{ __('Back to All Projects') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
