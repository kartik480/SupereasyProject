@extends('layouts.app')

@section('title', 'My Bookings - SuperDaily')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-calendar-check me-2"></i>My Bookings
                </h2>
                <a href="{{ route('services.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus me-2"></i>Book New Service
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($bookings->count() > 0)
                <div class="row">
                    @foreach($bookings as $booking)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="card-title text-primary mb-0">{{ $booking->service->name ?? 'N/A' }}</h6>
                                        <span class="badge bg-{{ $booking->status_color ?? 'secondary' }}">{{ ucfirst($booking->status ?? 'unknown') }}</span>
                                    </div>

                                    <div class="booking-details mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            <small>{{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}</small>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-clock text-muted me-2"></i>
                                            <small>{{ $booking->booking_time ? \Carbon\Carbon::createFromFormat('H:i', $booking->booking_time)->format('g:i A') : 'N/A' }}</small>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                            <small class="text-truncate">{{ $booking->address ?? 'N/A' }}</small>
                                        </div>
                                        @if($booking->maid && $booking->maid->name)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-user text-muted me-2"></i>
                                                <small>{{ $booking->maid->name }}</small>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-bold text-success">₹{{ number_format($booking->total_amount ?? 0, 2) }}</span>
                                        <small class="text-muted">{{ $booking->created_at ? $booking->created_at->format('M d, Y') : 'N/A' }}</small>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Booking ID: #{{ $booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</small>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        @if(in_array($booking->status, ['pending', 'confirmed']))
                                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" 
                                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                    <i class="fas fa-times me-1"></i>Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No Bookings Found</h4>
                        <p class="text-muted">You haven't booked any services yet.</p>
                    </div>
                    <a href="{{ route('services.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Book Your First Service
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.6rem;
}

.bg-pending { background-color: #ffc107 !important; }
.bg-confirmed { background-color: #28a745 !important; }
.bg-in-progress { background-color: #17a2b8 !important; }
.bg-completed { background-color: #6c757d !important; }
.bg-cancelled { background-color: #dc3545 !important; }
</style>
@endsection
