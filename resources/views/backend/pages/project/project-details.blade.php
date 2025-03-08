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
                        <!-- Fixed Fields First -->
                        <table class="table table-bordered mt-3">
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
                                        <img class="mt-3" width="150" height="150" src="{{ $equipment->sub_category_image }}" alt="Sub Category Image">
                                    @else
                                        <img class="mt-3" width="150" height="150" src="{{ asset('assets/uploads/no-image.png') }}" alt="No Image">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Created At') }}</th>
                                <td>{{ $equipment->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>

                        <!-- Dynamic Fields -->
                        <h4 class="customMarkup__single__title mt-4">{{ __('Additional Details') }}</h4>

                        <table class="table table-bordered mt-3">
                            @foreach($uniqueFields as $field)
                                @continue(in_array($field, ['id', 'name', 'category_id', 'sub_category_id', 'user_id', 'sub_category_image', 'created_at'])) {{-- Skip already displayed fields --}}
                                <tr>
                                    <th>{{ __(ucwords(str_replace('_', ' ', $field))) }}</th>
                                    <td>
                                        @if(!empty($equipment->$field))
                                            @if(Str::contains($field, ['image', 'images']))
                                                @php
                                                    $images = is_array($equipment->$field) ? $equipment->$field : json_decode($equipment->$field, true);
                                                @endphp

                                                @if(is_array($images))
                                                    @foreach($images as $img)
                                                        <img width="100" height="100" src="{{ asset('assets/uploads/' . $img) }}" alt="Additional Image" class="m-2">
                                                    @endforeach
                                                @else
                                                    <img width="100" height="100" src="{{ asset('assets/uploads/' . $equipment->$field) }}" alt="Image">
                                                @endif
                                            @else
                                                {{ $equipment->$field }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
{{--                        <table class="table table-bordered mt-3">--}}
{{--                            <tr>--}}
{{--                                <th>{{ __('ID') }}</th>--}}
{{--                                <td>{{ $equipment->id }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th>{{ __('Name') }}</th>--}}
{{--                                <td>{{ $equipment->name ?? 'N/A' }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th>{{ __('Category') }}</th>--}}
{{--                                <td>{{ $equipment->category->category ?? 'N/A' }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th>{{ __('Sub-Category Image') }}</th>--}}
{{--                                <td>--}}
{{--                                    @if(!empty($equipment->sub_category_image))--}}
{{--                                        <img class="mt-3" width="150" height="150" src="{{ $equipment->sub_category_image }}" alt="Sub Category Image">--}}
{{--                                    @else--}}
{{--                                        <img class="mt-3" width="150" height="150" src="{{ asset('assets/uploads/no-image.png') }}" alt="No Image">--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th>{{ __('Created At') }}</th>--}}
{{--                                <td>{{ $equipment->created_at->format('Y-m-d H:i:s') }}</td>--}}
{{--                            </tr>--}}
{{--                        </table>--}}

                        <h4 class="customMarkup__single__title mt-4">{{ __('User Details') }}</h4>

                        <!-- User Information -->
                        <table class="table table-bordered mt-3">
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
                                <td>{{ $user->user_country?->country ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('State') }}</th>
                                <td>{{ $user->user_state?->state ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('City') }}</th>
                                <td>{{ $user->user_city?->city ?? 'N/A' }}</td>
                            </tr>
                        </table>

                        <a href="{{ route('admin.project') }}" class="btn btn-secondary">{{ __('Back to All Projects') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
