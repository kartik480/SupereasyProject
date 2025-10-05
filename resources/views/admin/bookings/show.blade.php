@extends('layouts.admin')

@section('page-icon')
<i class="fas fa-eye"></i>
@endsection

@section('page-title', 'Booking Details')

@section('page-subtitle', 'View and manage booking information')

@section('header-actions')
<a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary">
    <i class="fas fa-arrow-left me-2"></i>Back to Bookings
</a>
@endsection

@section('content')
<div class="container-fluid">
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
                            <label class="form-label fw-bold">Booking ID</label>
                            <p class="mb-0">#{{ $booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $booking->status_color }} fs-6">{{ ucfirst($booking->status) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Customer Name</label>
                            <p class="mb-0">{{ $booking->user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Customer Email</label>
                            <p class="mb-0">{{ $booking->user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Service</label>
                            <p class="mb-0">{{ $booking->service->name }} ({{ $booking->service->category }})</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Booking Date</label>
                            <p class="mb-0">{{ $booking->booking_date->format('l, F d, Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Booking Time</label>
                            <p class="mb-0">{{ \Carbon\Carbon::createFromFormat('H:i:s', $booking->booking_time)->format('g:i A') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Total Amount</label>
                            <p class="mb-0 text-success fw-bold fs-5">₹{{ number_format($booking->total_amount, 2) }}</p>
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
                        @if($booking->admin_notes)
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Admin Notes</label>
                                <p class="mb-0">{{ $booking->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Maid Assignment & Actions -->
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
                        <p class="text-muted mb-2">{{ $booking->maid->specialization ?? 'General Service' }}</p>
                        
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
                                <p class="mb-0 fw-bold">{{ $booking->maid->experience_years ?? 0 }} years</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Completed</small>
                                <p class="mb-0 fw-bold">{{ $booking->maid->completed_bookings ?? 0 }}</p>
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
                            <i class="fas fa-user-clock me-2"></i>Maid Assignment
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($availableMaids->count() > 0)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Available Maids</label>
                                <form method="POST" action="{{ route('admin.bookings.assign-maid', $booking) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <select class="form-control" name="maid_id" required>
                                            <option value="">Select Maid</option>
                                            @foreach($availableMaids as $maid)
                                                <option value="{{ $maid->id }}">
                                                    {{ $maid->name }} - Rating: {{ $maid->rating }}/5 - Experience: {{ $maid->experience_years ?? 0 }} years
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-user-plus me-2"></i>Assign Maid
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center">
                                <i class="fas fa-user-times fa-3x text-warning mb-3"></i>
                                <h6 class="fw-bold">No Available Maids</h6>
                                <p class="text-muted mb-0">No maids are available for this service category at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Status Update -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Update Status
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ $booking->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                      placeholder="Add any notes about this booking">{{ $booking->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Actions</h6>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('services.show', $booking->service) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>View Service
                        </a>
                        
                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this booking?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Booking
                            </button>
                        </form>
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
