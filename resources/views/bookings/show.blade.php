@extends('layouts.app')

@section('title', 'Booking Details - SuperDaily')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-calendar-check me-2"></i>Booking Details
                </h2>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Booking Information -->
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Booking Information
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Service</label>
                                    <p class="mb-0">{{ $booking->service->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $booking->status_color }} fs-6">{{ ucfirst($booking->status) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Booking Date</label>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($booking->booking_date)->format('l, F d, Y') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Booking Time</label>
                                    <p class="mb-0">{{ \Carbon\Carbon::createFromFormat('H:i', $booking->booking_time)->format('g:i A') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Total Amount</label>
                                    <p class="mb-0 text-success fw-bold fs-5">₹{{ number_format($booking->total_amount, 2) }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Booking ID</label>
                                    <p class="mb-0 text-muted">#{{ $booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Service Address</label>
                                    <p class="mb-0">{{ $booking->address }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Contact Number</label>
                                    <p class="mb-0">{{ $booking->phone }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Booked On</label>
                                    <p class="mb-0">{{ $booking->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                                @if($booking->special_instructions)
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold">Special Instructions</label>
                                        <p class="mb-0">{{ $booking->special_instructions }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maid Information -->
                <div class="col-lg-4">
                    @if($booking->maid)
                        <div class="card shadow-lg border-0 mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>Assigned Maid
                                </h5>
                            </div>
                            <div class="card-body p-4 text-center">
                                <img src="{{ $booking->maid->profile_image_url }}" 
                                     alt="{{ $booking->maid->name }}" 
                                     class="rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                
                                <h6 class="fw-bold">{{ $booking->maid->name }}</h6>
                                <p class="text-muted mb-2">{{ $booking->maid->specialization }}</p>
                                
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <div class="rating me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $booking->maid->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-muted">({{ $booking->maid->rating }})</span>
                                </div>
                                
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted">Experience</small>
                                        <p class="mb-0 fw-bold">{{ $booking->maid->experience_years }} years</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Completed</small>
                                        <p class="mb-0 fw-bold">{{ $booking->maid->completed_bookings }}</p>
                                    </div>
                                </div>
                                
                                @if($booking->maid->phone)
                                    <div class="mt-3">
                                        <a href="tel:{{ $booking->maid->phone }}" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-phone me-1"></i>Call Maid
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="card shadow-lg border-0 mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>Maid Assignment
                                </h5>
                            </div>
                            <div class="card-body p-4 text-center">
                                <i class="fas fa-user-clock fa-3x text-warning mb-3"></i>
                                <h6 class="fw-bold">Awaiting Assignment</h6>
                                <p class="text-muted mb-0">We're finding the best maid for your service. You'll be notified once assigned.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Actions</h6>
                            
                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="mb-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" 
                                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        <i class="fas fa-times me-2"></i>Cancel Booking
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('services.show', $booking->service) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-eye me-2"></i>View Service
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.bg-pending { background-color: #ffc107 !important; }
.bg-confirmed { background-color: #28a745 !important; }
.bg-in-progress { background-color: #17a2b8 !important; }
.bg-completed { background-color: #6c757d !important; }
.bg-cancelled { background-color: #dc3545 !important; }

.rating i {
    font-size: 0.9rem;
}
</style>
@endsection
