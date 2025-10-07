@extends('layouts.app')

@section('title', 'Select Maid - SuperDaily')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Booking Summary -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Booking Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Service Details</h6>
                            <p class="mb-1"><strong>{{ $booking->service->name }}</strong></p>
                            <p class="mb-1 text-muted">{{ $booking->service->description }}</p>
                            <p class="mb-0"><strong>Duration:</strong> {{ $booking->service->duration }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Booking Details</h6>
                            <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>
                            <p class="mb-1"><strong>Time:</strong> {{ $booking->booking_time }}</p>
                            <p class="mb-1"><strong>Address:</strong> {{ $booking->address }}</p>
                            <p class="mb-0"><strong>Total Amount:</strong> ₹{{ number_format($booking->final_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maid Selection -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Select Your Maid
                        <span class="badge bg-light text-dark ms-2">{{ $availableMaids->count() }} Available</span>
                        @if($unavailableMaids->count() > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $unavailableMaids->count() }} Unavailable</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filter Options -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="btn-group" role="group" aria-label="Maid filter options">
                                <input type="radio" class="btn-check" name="maidFilter" id="filterAll" value="all" checked>
                                <label class="btn btn-outline-primary" for="filterAll">
                                    <i class="fas fa-list me-1"></i>All Maids ({{ $availableMaids->count() + $unavailableMaids->count() }})
                                </label>

                                <input type="radio" class="btn-check" name="maidFilter" id="filterAvailable" value="available">
                                <label class="btn btn-outline-success" for="filterAvailable">
                                    <i class="fas fa-check-circle me-1"></i>Available ({{ $availableMaids->count() }})
                                </label>

                                <input type="radio" class="btn-check" name="maidFilter" id="filterUnavailable" value="unavailable">
                                <label class="btn btn-outline-warning" for="filterUnavailable">
                                    <i class="fas fa-times-circle me-1"></i>Unavailable ({{ $unavailableMaids->count() }})
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($availableMaids->count() > 0 || $unavailableMaids->count() > 0)
                        <div class="row" id="maidsContainer">
                            <!-- Available Maids -->
                            @foreach($availableMaids as $maid)
                                <div class="col-md-6 col-lg-4 mb-4 maid-card-container" data-availability="available">
                                    <div class="card h-100 maid-card" data-maid-id="{{ $maid->id }}">
                                        <div class="card-body text-center">
                                            <!-- Availability Badge -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Available
                                                </span>
                                            </div>
                                            <!-- Maid Photo -->
                                            <div class="mb-3">
                                                <img src="{{ $maid->profile_image ? asset('storage/' . $maid->profile_image) : 'https://via.placeholder.com/100x100/6c757d/ffffff?text=M' }}" 
                                                     alt="{{ $maid->name }}" 
                                                     class="rounded-circle" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            </div>

                                            <!-- Maid Name -->
                                            <h6 class="card-title mb-2">{{ $maid->name }}</h6>

                                            <!-- Rating -->
                                            <div class="mb-2">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-star text-warning me-1"></i>
                                                    <span class="fw-bold">{{ number_format($maid->rating ?? 0, 1) }}</span>
                                                    <small class="text-muted ms-1">({{ $maid->total_ratings ?? 0 }} reviews)</small>
                                                </div>
                                            </div>

                                            <!-- Experience -->
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-briefcase me-1"></i>
                                                    {{ $maid->experience_years ?? 0 }} years experience
                                                </small>
                                            </div>

                                            <!-- Service Categories -->
                                            @php
                                                $serviceCategories = is_string($maid->service_categories) ? json_decode($maid->service_categories, true) : $maid->service_categories;
                                                $serviceCategories = is_array($serviceCategories) ? $serviceCategories : [];
                                            @endphp
                                            @if(!empty($serviceCategories))
                                                <div class="mb-3">
                                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                                        @foreach(array_slice($serviceCategories, 0, 3) as $category)
                                                            <span class="badge bg-primary text-white small">{{ $category }}</span>
                                                        @endforeach
                                                        @if(count($serviceCategories) > 3)
                                                            <span class="badge bg-secondary text-white small">+{{ count($serviceCategories) - 3 }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Hourly Rate -->
                                            <div class="mb-3">
                                                <strong class="text-success">₹{{ number_format($maid->hourly_rate ?? 0, 2) }}/hour</strong>
                                            </div>

                                            <!-- Bio -->
                                            @if($maid->bio)
                                                <p class="card-text small text-muted mb-3">
                                                    {{ Str::limit($maid->bio, 80) }}
                                                </p>
                                            @endif

                                            <!-- Select Button -->
                                            <button type="button" class="btn btn-outline-primary btn-sm select-maid-btn" 
                                                    data-maid-id="{{ $maid->id }}" 
                                                    data-maid-name="{{ $maid->name }}">
                                                <i class="fas fa-check me-1"></i>Select This Maid
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Unavailable Maids -->
                            @foreach($unavailableMaids as $maid)
                                <div class="col-md-6 col-lg-4 mb-4 maid-card-container" data-availability="unavailable">
                                    <div class="card h-100 maid-card unavailable" data-maid-id="{{ $maid->id }}">
                                        <div class="card-body text-center">
                                            <!-- Availability Badge -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-times-circle me-1"></i>Unavailable
                                                </span>
                                            </div>
                                            
                                            <!-- Maid Photo -->
                                            <div class="mb-3">
                                                <img src="{{ $maid->profile_image ? asset('storage/' . $maid->profile_image) : 'https://via.placeholder.com/100x100/6c757d/ffffff?text=M' }}" 
                                                     alt="{{ $maid->name }}" 
                                                     class="rounded-circle" 
                                                     style="width: 80px; height: 80px; object-fit: cover; opacity: 0.7;">
                                            </div>

                                            <!-- Maid Name -->
                                            <h6 class="card-title mb-2">{{ $maid->name }}</h6>

                                            <!-- Rating -->
                                            <div class="mb-2">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-star text-warning me-1"></i>
                                                    <span class="fw-bold">{{ number_format($maid->rating ?? 0, 1) }}</span>
                                                    <small class="text-muted ms-1">({{ $maid->total_ratings ?? 0 }} reviews)</small>
                                                </div>
                                            </div>

                                            <!-- Experience -->
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-briefcase me-1"></i>
                                                    {{ $maid->experience_years ?? 0 }} years experience
                                                </small>
                                            </div>

                                            <!-- Service Categories -->
                                            @php
                                                $serviceCategories = is_string($maid->service_categories) ? json_decode($maid->service_categories, true) : $maid->service_categories;
                                                $serviceCategories = is_array($serviceCategories) ? $serviceCategories : [];
                                            @endphp
                                            @if(!empty($serviceCategories))
                                                <div class="mb-3">
                                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                                        @foreach(array_slice($serviceCategories, 0, 3) as $category)
                                                            <span class="badge bg-primary text-white small">{{ $category }}</span>
                                                        @endforeach
                                                        @if(count($serviceCategories) > 3)
                                                            <span class="badge bg-secondary text-white small">+{{ count($serviceCategories) - 3 }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Hourly Rate -->
                                            <div class="mb-3">
                                                <strong class="text-success">₹{{ number_format($maid->hourly_rate ?? 0, 2) }}/hour</strong>
                                            </div>

                                            <!-- Bio -->
                                            @if($maid->bio)
                                                <p class="card-text small text-muted mb-3">
                                                    {{ Str::limit($maid->bio, 80) }}
                                                </p>
                                            @endif

                                            <!-- Unavailable Button -->
                                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                                <i class="fas fa-times me-1"></i>Currently Unavailable
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Confirmation Form (Hidden) -->
                        <form id="confirm-booking-form" method="POST" action="{{ route('bookings.confirm', $booking) }}" style="display: none;">
                            @csrf
                            <input type="hidden" name="maid_id" id="selected-maid-id">
                        </form>

                    @else
                        <!-- No Available Maids -->
                        <div class="text-center py-5">
                            <i class="fas fa-user-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Available Maids</h5>
                            <p class="text-muted mb-4">
                                We're sorry, but there are currently no available maids for your selected service and time slot.
                            </p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Go Back
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="fas fa-home me-2"></i>Browse Other Services
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-check-circle text-success me-2"></i>Confirm Maid Selection
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to select <strong id="selected-maid-name"></strong> for your service?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Once confirmed, this maid will be assigned to your booking and you will be redirected to the booking confirmation page.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirm-selection">
                    <i class="fas fa-check me-2"></i>Confirm Selection
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
.maid-card.unavailable {
    opacity: 0.7;
    background-color: #f8f9fa;
}

.maid-card.unavailable .card-body {
    position: relative;
}

.maid-card.unavailable::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 48%, #dee2e6 49%, #dee2e6 51%, transparent 52%);
    background-size: 20px 20px;
    opacity: 0.3;
    pointer-events: none;
    z-index: 1;
}

.maid-card.unavailable .card-body > * {
    position: relative;
    z-index: 2;
}

.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.btn-check:checked + .btn-outline-success {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-check:checked + .btn-outline-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectButtons = document.querySelectorAll('.select-maid-btn');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const selectedMaidIdInput = document.getElementById('selected-maid-id');
    const selectedMaidNameSpan = document.getElementById('selected-maid-name');
    const confirmForm = document.getElementById('confirm-booking-form');
    const confirmButton = document.getElementById('confirm-selection');
    
    // Filter functionality
    const filterRadios = document.querySelectorAll('input[name="maidFilter"]');
    const maidContainers = document.querySelectorAll('.maid-card-container');
    
    filterRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const filterValue = this.value;
            
            maidContainers.forEach(container => {
                const availability = container.getAttribute('data-availability');
                
                if (filterValue === 'all') {
                    container.style.display = 'block';
                } else if (filterValue === availability) {
                    container.style.display = 'block';
                } else {
                    container.style.display = 'none';
                }
            });
        });
    });

    selectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const maidId = this.dataset.maidId;
            const maidName = this.dataset.maidName;
            
            // Set the selected maid details
            selectedMaidIdInput.value = maidId;
            selectedMaidNameSpan.textContent = maidName;
            
            // Show confirmation modal
            confirmModal.show();
        });
    });

    confirmButton.addEventListener('click', function() {
        // Disable button to prevent double submission
        confirmButton.disabled = true;
        confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Confirming...';
        
        // Submit the form via AJAX
        fetch(confirmForm.action, {
            method: 'POST',
            body: new FormData(confirmForm),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showSuccessMessage(data.message || 'Booking confirmed successfully!');
                
                // Redirect to booking details after a short delay
                setTimeout(() => {
                    window.location.href = data.redirect_url || '{{ route("bookings.show", $booking) }}';
                }, 2000);
            } else {
                // Show error message
                showErrorMessage(data.message || 'Failed to confirm booking. Please try again.');
                
                // Re-enable button
                confirmButton.disabled = false;
                confirmButton.innerHTML = '<i class="fas fa-check me-2"></i>Confirm Booking';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred. Please try again.');
            
            // Re-enable button
            confirmButton.disabled = false;
            confirmButton.innerHTML = '<i class="fas fa-check me-2"></i>Confirm Booking';
        });
    });

    // Helper functions for showing messages
    function showSuccessMessage(message) {
        // Create success alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    function showErrorMessage(message) {
        // Create error alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Add hover effects to maid cards
    const maidCards = document.querySelectorAll('.maid-card');
    maidCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<style>
.maid-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.maid-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
}

.select-maid-btn {
    transition: all 0.3s ease;
}

.select-maid-btn:hover {
    transform: scale(1.05);
}

.card {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-bottom: none;
}

.badge {
    font-size: 0.75em;
}
</style>
@endsection
