@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-calendar-check"></i>
@endsection

@section('page-title', 'Booking Details')

@section('page-subtitle', 'View booking information and details')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.bookings.edit', $booking) }}" class="btn btn-outline-danger">
        <i class="fas fa-edit me-2"></i>Edit Booking
    </a>
    <a href="{{ route('superadmin.bookings.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Booking Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-check me-2"></i>Booking Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Booking ID:</label>
                            <p class="mb-0">#{{ $booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Customer:</label>
                            <p class="mb-0">{{ $booking->user->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <p class="mb-0">{{ $booking->user->email ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Phone:</label>
                            <p class="mb-0">{{ $booking->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Service:</label>
                            <p class="mb-0">{{ $booking->service->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Booking Date:</label>
                            <p class="mb-0">{{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Booking Time:</label>
                            <p class="mb-0">{{ $booking->booking_time ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Total Amount:</label>
                            <p class="mb-0 text-success fw-bold">₹{{ number_format($booking->total_amount ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Address:</label>
                    <p class="mb-0">{{ $booking->address ?? 'N/A' }}</p>
                </div>
                
                @if($booking->special_instructions)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Special Instructions:</label>
                    <p class="mb-0">{{ $booking->special_instructions }}</p>
                </div>
                @endif
                
                @if($booking->admin_notes)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Admin Notes:</label>
                    <p class="mb-0">{{ $booking->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Maid Information -->
        @if($booking->maid)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tie me-2"></i>Assigned Maid
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        @if($booking->maid->profile_image)
                            <img src="{{ asset('storage/' . $booking->maid->profile_image) }}" 
                                 alt="{{ $booking->maid->name }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 150px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 150px;">
                                <i class="fas fa-user fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h6 class="fw-bold">{{ $booking->maid->name ?? 'N/A' }}</h6>
                        <p class="text-muted mb-2">{{ $booking->maid->phone ?? 'N/A' }}</p>
                        <div class="mb-2">
                            <span class="badge bg-info">Rating: {{ $booking->maid->rating ?? 'N/A' }}/5</span>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-{{ $booking->maid->is_active ? 'success' : 'secondary' }}">
                                {{ $booking->maid->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Booking Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Booking Status</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $statusConfig = [
                        'pending' => ['icon' => 'fas fa-clock', 'color' => 'warning', 'text' => 'Pending'],
                        'confirmed' => ['icon' => 'fas fa-check-circle', 'color' => 'success', 'text' => 'Confirmed'],
                        'in_progress' => ['icon' => 'fas fa-play-circle', 'color' => 'info', 'text' => 'In Progress'],
                        'completed' => ['icon' => 'fas fa-flag-checkered', 'color' => 'secondary', 'text' => 'Completed'],
                        'cancelled' => ['icon' => 'fas fa-times-circle', 'color' => 'danger', 'text' => 'Cancelled'],
                    ];
                    $currentStatus = $statusConfig[$booking->status] ?? $statusConfig['pending'];
                @endphp
                
                <div class="status-indicator">
                    <i class="{{ $currentStatus['icon'] }} fa-3x text-{{ $currentStatus['color'] }} mb-3"></i>
                    <h6 class="mb-2">{{ $currentStatus['text'] }}</h6>
                    <span class="badge bg-{{ $currentStatus['color'] }}">{{ ucfirst($booking->status ?? 'Unknown') }}</span>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Created:</strong> {{ $booking->created_at->format('M d, Y') }}<br>
                        <strong>Last Updated:</strong> {{ $booking->updated_at->format('M d, Y') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('superadmin.bookings.edit', $booking) }}" class="btn btn-outline-danger">
                        <i class="fas fa-edit me-2"></i>Edit Booking
                    </a>
                    
                    @if($booking->status == 'pending')
                        <form method="POST" action="{{ route('superadmin.bookings.confirm', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-check me-2"></i>Confirm Booking
                            </button>
                        </form>
                    @endif
                    
                    @if($booking->status == 'confirmed')
                        <form method="POST" action="{{ route('superadmin.bookings.start', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-info w-100">
                                <i class="fas fa-play me-2"></i>Start Service
                            </button>
                        </form>
                    @endif
                    
                    @if($booking->status == 'in_progress')
                        <form method="POST" action="{{ route('superadmin.bookings.complete', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-flag-checkered me-2"></i>Complete Service
                            </button>
                        </form>
                    @endif
                    
                    @if(!in_array($booking->status, ['completed', 'cancelled']))
                        <form method="POST" action="{{ route('superadmin.bookings.cancel', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('Are you sure you want to cancel this booking?')">
                                <i class="fas fa-times me-2"></i>Cancel Booking
                            </button>
                        </form>
                    @endif
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete('{{ route('superadmin.bookings.destroy', $booking) }}')">
                        <i class="fas fa-trash me-2"></i>Delete Booking
                    </button>
                </div>
            </div>
        </div>

        <!-- Service Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Service Details</h5>
            </div>
            <div class="card-body">
                @if($booking->service)
                    <div class="service-preview">
                        <h6 class="mb-1">{{ $booking->service->name }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($booking->service->description ?? 'No description available', 100) }}</p>
                        <div class="preview-meta">
                            <small class="text-muted">
                                <strong>Price: ₹{{ number_format($booking->service->discount_price ?: $booking->service->price, 2) }}</strong>
                                @if($booking->service->discount_price && $booking->service->discount_price < $booking->service->price)
                                    <span class="text-decoration-line-through text-muted">₹{{ number_format($booking->service->price, 2) }}</span>
                                @endif
                            </small>
                        </div>
                        <div class="preview-category mt-2">
                            <small class="text-muted">
                                Category: <strong>{{ ucfirst(str_replace('_', ' ', $booking->service->main_category ?? 'N/A')) }}</strong>
                            </small>
                        </div>
                    </div>
                @else
                    <p class="text-muted">No service information available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this booking? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(url) {
    document.getElementById('deleteForm').action = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
