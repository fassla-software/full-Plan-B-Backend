@extends('backend.layout.master')
@section('title', __('User Requests'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Requests of') }} {{ $user->first_name }} {{ $user->last_name }}</h4>
                    </div>
                    <div class="customMarkup__single__inner mt-4">
                        <table class="table DataTable_activation">
                            <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('max_arrival_date') }}</th>
                                <th>{{ __('max_offer_deadline') }}</th>
                                <th>{{ __('Created At') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->name ?? 'N/A' }}</td>
                                    <td>{{ $request->category->category ?? 'N/A' }}</td>
                                    <td>{{ $request->max_arrival_date ?? 'N/A' }}</td>
                                    <td>{{ $request->max_offer_deadline ?? 'N/A' }}</td>
                                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{-- Pagination --}}
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
