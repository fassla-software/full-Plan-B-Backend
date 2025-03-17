<x-validation.error />
<table class="table_activation">
    <thead>
        <tr>
            <th class="no-sort">
                <div class="mark-all-checkbox">
                    <input type="checkbox" class="all-checkbox">
                </div>
            </th>
            <th>{{ __('ID') }}</th>
            <th>{{ __('Presenter\'s Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Request Name') }}</th>
            <th>{{ __('Sub Category') }}</th>
            <th>{{ __('Price') }}</th>
            <th>{{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if ($offres->total() >= 1)
            @foreach ($offres as $offer)
                <tr>
                    <td> <x-bulk-action.bulk-delete-checkbox :id="$offer->id" /> </td>
                    <td>{{ $offer->id }}</td>
                    <td>{{ $offer->user?->first_name . ' ' . $offer->user?->last_name }}</td>
                    <td>{{ $offer->user?->email }}</td>
                    <td>{{ $offer->request?->requestable?->name ?? __('N/A') }} </td>
                    <td>{{ $offer->request?->requestable?->subCategory?->getTranslatedName('en') ?? __('N/A') }} </td>
                    <td>{{ $offer->price . ' EGP / ' . $offer->per }}</td>
                    <td>
                        <x-status.table.select-action :title="__('Select Action')" />
                        <ul class="dropdown-menu status_dropdown__list">
                            @can('user-details')
                                <li class="status_dropdown__item">
                                    <a class="btn dropdown-item status_dropdown__list__link offer_details"
                                        data-bs-toggle="modal" data-bs-target="#offerDetailsModal"
                                        data-offer_id="{{ $offer->id }}"
                                        data-user_name="{{ $offer->user?->first_name . ' ' . $offer->user?->last_name }}"
                                        data-user_email="{{ $offer->user?->email }}"
                                        data-request_name="{{ $offer->request?->requestable?->name }}"
                                        data-subcategory="{{ $offer->request?->requestable?->subCategory?->getTranslatedName('en') }}"
                                        data-price="{{ $offer->price }}" data-ends_at="{{ $offer->offer_ends_at }}"
                                        data-other_terms="{{ $offer->other_terms }}" data-is_seen="{{ $offer->isSeen }}"
                                        data-per="{{ $offer->per }}"
                                        data-offer_location="{{ $offer->current_location }}">
                                        {{ __('View Offer Details') }}
                                    </a>
                                </li>
                            @endcan
                            @can('user-delete')
                                <li class="status_dropdown__item">
                                    <button class="btn dropdown-item status_dropdown__list__link delete-offer-button"
                                        data-id="{{ $offer->id }}">
                                        {{ __('Delete Offer') }}
                                    </button>
                                </li>
                            @endcan
                        </ul>
                    </td>
                </tr>
            @endforeach
        @else
            <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
        @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$offres" />

@section('script')
    <x-sweet-alert.sweet-alert2-js />

    <script>
        $(document).on('click', '.delete-offer-button', function(e) {
            e.preventDefault();
            let offerId = $(this).data('id');
            let deleteUrl = "{{ route('admin.offers.delete', ':id') }}".replace(':id', offerId);

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
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
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
                        error: function() {
                            Swal.fire('Error!', 'There was an error deleting the offer.',
                                'error');
                        }
                    });
                }
            });
        });
    </script>
@endsection
