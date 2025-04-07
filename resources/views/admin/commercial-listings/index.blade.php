@extends('backend.layout.master')

@section('title', 'Commercial Listings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Commercial Listings</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Business Name</th>
                                    <th>Logo</th>
                                    <th>Phone</th>
                                    <th>WhatsApp</th>
                                    <th>Email</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($listings as $listing)
                                <tr>
                                    <td>{{ $listing->id }}</td>
                                    <td>{{ $listing->business_name }}</td>
                                    <td>
                                        @if($listing->logo)
                                            @if(str_contains($listing->logo, 'storage/') || filter_var($listing->logo, FILTER_VALIDATE_URL))
                                                <img src="{{ $listing->logo }}" alt="Logo" class="img-thumbnail" style="max-width: 50px;">
                                            @else
                                                <img src="{{ Storage::url($listing->logo) }}" alt="Logo" class="img-thumbnail" style="max-width: 50px;">
                                            @endif
                                        @else
                                            No Logo
                                        @endif
                                    </td>
                                    <td>{{ $listing->phone }}</td>
                                    <td>{{ $listing->whatsapp }}</td>
                                    <td>{{ $listing->email }}</td>
                                    <td>
                                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $listing->description }}
                                        </div>
                                    </td>
                                    <td>{{ $listing->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $listing->id }}">
                                            View Full Description
                                        </button>
                                    </td>
                                </tr>

                                <!-- Description Modal -->
                                <div class="modal fade" id="descriptionModal{{ $listing->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $listing->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="descriptionModalLabel{{ $listing->id }}">{{ $listing->business_name }} - Full Description</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $listing->description }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No listings found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $listings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modals
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        new bootstrap.Modal(modal);
    });
});
</script>
@endpush
@endsection 