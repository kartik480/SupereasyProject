@extends('layouts.app')

@section('title', $service->name . ' - SuperDaily')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Service Details -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body p-4">
                    <!-- Service Images -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <img src="{{ $service->image_url }}" alt="{{ $service->name }}" 
                                 class="img-fluid rounded service-main-image">
                        </div>
                        <div class="col-md-6">
                            @if($service->image_2_url)
                                <img src="{{ $service->image_2_url }}" alt="{{ $service->name }}" 
                                     class="img-fluid rounded mb-2 service-thumb-image">
                            @endif
                            @if($service->image_3_url)
                                <img src="{{ $service->image_3_url }}" alt="{{ $service->name }}" 
                                     class="img-fluid rounded service-thumb-image">
                            @endif
                        </div>
                    </div>

                    <!-- Service Information -->
                    <div class="service-info">
                        <h1 class="service-title mb-3">{{ $service->name }}</h1>
                        
                        <div class="service-meta mb-4">
                            <span class="badge bg-primary me-2">{{ $service->main_category_name ?? $service->category }}</span>
                            <span class="badge bg-info me-2">{{ ucfirst(str_replace('_', ' ', $service->subcategory ?? $service->category)) }}</span>
                            <span class="badge bg-success me-2">{{ $service->duration }}</span>
                            @if($service->is_featured)
                                <span class="badge bg-warning">Featured</span>
                            @endif
                        </div>

                        <!-- Booking Requirements Alert -->
                        @if($service->booking_advance_hours)
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-3 text-info"></i>
                                <div>
                                    <h6 class="mb-1">Booking Notice Required</h6>
                                    <p class="mb-0">
                                        Please book this service at least <strong>{{ $service->booking_advance_hours }} hours</strong> in advance.
                                        @if($service->booking_requirements)
                                            <br><small class="text-muted">{{ $service->booking_requirements }}</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Subscription Plans (for Monthly Subscription services) -->
                        @if($service->isMonthlySubscription() && $service->subscription_plans_array)
                        <div class="subscription-plans mb-4">
                            <h5>Subscription Plans</h5>
                            <div class="row">
                                @foreach($service->subscription_plans_array as $plan => $details)
                                    @if(isset($details['price']) && $details['price'])
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <h6 class="card-title text-capitalize">{{ $plan }} Plan</h6>
                                                <h4 class="text-primary">₹{{ number_format($details['price'], 2) }}</h4>
                                                @if(isset($details['visits']))
                                                    <small class="text-muted">{{ $details['visits'] }} visits per {{ $plan }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="service-pricing mb-4">
                            @if($service->discount_price)
                                <div class="d-flex align-items-center">
                                    <h3 class="text-primary mb-0 me-3">₹{{ number_format($service->discount_price, 2) }}</h3>
                                    <span class="text-muted text-decoration-line-through">₹{{ number_format($service->price, 2) }}</span>
                                    <span class="badge bg-danger ms-2">
                                        {{ round((($service->price - $service->discount_price) / $service->price) * 100) }}% OFF
                                    </span>
                                </div>
                            @else
                                <h3 class="text-primary mb-0">₹{{ number_format($service->price, 2) }}</h3>
                            @endif
                            <small class="text-muted">per {{ $service->unit }}</small>
                        </div>

                        <div class="service-description mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $service->description }}</p>
                        </div>

                        @if($service->features)
                        <div class="service-features mb-4">
                            <h5>Features</h5>
                            <ul class="list-unstyled">
                                @foreach($service->features_array as $feature)
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>{{ trim($feature) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if($service->requirements_array)
                        <div class="service-requirements mb-4">
                            <h5>Requirements</h5>
                            <ul class="list-unstyled">
                                @foreach($service->requirements_array as $requirement)
                                    <li class="mb-2">
                                        <i class="fas fa-info-circle text-info me-2"></i>{{ trim($requirement) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form / Service Information Panel -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 sticky-top" style="top: 20px;">
                @auth
                    <!-- Booking Form for Logged-in Users -->
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Book This Service
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Booking Failed:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('bookings.store') }}">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            
                            <!-- Service Summary -->
                            <div class="booking-summary mb-4 p-3 bg-light rounded">
                                <h6 class="mb-2">Service Summary</h6>
                                <div class="d-flex justify-content-between">
                                    <span>{{ $service->name }}</span>
                                    <strong>₹{{ number_format($service->discount_price ?? $service->price, 2) }}</strong>
                                </div>
                                <small class="text-muted">{{ $service->duration }} per {{ $service->unit }}</small>
                            </div>

                            <!-- Booking Details -->
                            <div class="mb-3">
                                <label for="booking_date" class="form-label">Preferred Date</label>
                                <input type="date" class="form-control @error('booking_date') is-invalid @enderror" 
                                       id="booking_date" name="booking_date" 
                                       value="{{ old('booking_date') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('booking_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="booking_time" class="form-label">Preferred Time</label>
                                <select class="form-control @error('booking_time') is-invalid @enderror" 
                                        id="booking_time" name="booking_time" required>
                                    <option value="">Select Time</option>
                                    <option value="09:00" {{ old('booking_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                    <option value="10:00" {{ old('booking_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                    <option value="11:00" {{ old('booking_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                    <option value="12:00" {{ old('booking_time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                    <option value="14:00" {{ old('booking_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                    <option value="15:00" {{ old('booking_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                    <option value="16:00" {{ old('booking_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                    <option value="17:00" {{ old('booking_time') == '17:00' ? 'selected' : '' }}>5:00 PM</option>
                                </select>
                                @error('booking_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Service Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" 
                                          placeholder="Enter your complete address" required>{{ old('address', auth()->user()->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" 
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                       placeholder="Enter your phone number" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="special_instructions" class="form-label">Special Instructions</label>
                                <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                                          id="special_instructions" name="special_instructions" rows="3" 
                                          placeholder="Any special requirements or instructions">{{ old('special_instructions') }}</textarea>
                                @error('special_instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="bookNowBtn">
                                    <i class="fas fa-calendar-check me-2"></i>Book Now
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Login Required for Non-logged-in Users -->
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-lock me-2"></i>Login Required
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                                <h6>Login Required to Book</h6>
                                <p class="text-muted">Please login to book this service</p>
                            </div>
                            
                            <!-- Service Summary -->
                            <div class="service-info-card mb-4 p-3 bg-light rounded">
                                <h6 class="mb-3">Service Details</h6>
                                <div class="service-detail-item mb-2">
                                    <strong>Service:</strong> {{ $service->name }}
                                </div>
                                <div class="service-detail-item mb-2">
                                    <strong>Category:</strong> {{ $service->category }}
                                </div>
                                <div class="service-detail-item mb-2">
                                    <strong>Duration:</strong> {{ $service->duration }}
                                </div>
                                <div class="service-detail-item mb-2">
                                    <strong>Unit:</strong> {{ $service->unit }}
                                </div>
                                <div class="service-detail-item mb-2">
                                    <strong>Price:</strong> 
                                    <span class="text-primary fw-bold">
                                        ₹{{ number_format($service->discount_price ?? $service->price, 2) }}
                                        @if($service->discount_price)
                                            <small class="text-success">({{ round((($service->price - $service->discount_price) / $service->price) * 100) }}% OFF)</small>
                                        @endif
                                    </span>
                                </div>
                                @if($service->is_featured)
                                <div class="service-detail-item mb-2">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star me-1"></i>Featured Service
                                    </span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Book
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Related Services -->
    @if(isset($relatedServices) && $relatedServices->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Services</h3>
            <div class="row">
                @foreach($relatedServices->take(3) as $relatedService)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ $relatedService->image_url }}" 
                             class="card-img-top" 
                             alt="{{ $relatedService->name }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $relatedService->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($relatedService->description, 100) }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-primary fw-bold">
                                        ₹{{ number_format($relatedService->discount_price ?? $relatedService->price, 2) }}
                                    </span>
                                    <span class="badge bg-secondary">{{ $relatedService->category }}</span>
                                </div>
                                <a href="{{ route('services.show', $relatedService) }}" 
                                   class="btn btn-outline-primary w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.service-main-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.service-thumb-image {
    width: 100%;
    height: 140px;
    object-fit: cover;
}

.service-title {
    color: #333;
    font-weight: 700;
}

.service-pricing h3 {
    font-size: 2rem;
    font-weight: 700;
}

.booking-summary {
    border-left: 4px solid #007bff;
}

.service-info-card {
    border-left: 4px solid #28a745;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
}

.badge {
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
}

.service-detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>

<!-- Booking Success Modal -->
<div class="modal fade" id="bookingSuccessModal" tabindex="-1" aria-labelledby="bookingSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="bookingSuccessModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Booking Successful!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-calendar-check fa-4x text-success mb-3"></i>
                    <h4 class="text-success">Congratulations!</h4>
                    <p class="lead">Your service has been booked successfully.</p>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>What's Next?</strong><br>
                    We will contact you soon to confirm the details and schedule your service.
                </div>
                <div class="mt-3">
                    <p class="text-muted">
                        <strong>Booking Reference:</strong> <span id="bookingReference"></span><br>
                        <strong>Service:</strong> {{ $service->name }}<br>
                        <strong>Amount:</strong> ₹{{ number_format($service->discount_price ?? $service->price, 2) }}
                    </p>
                </div>
            </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                   </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.querySelector('form[action="{{ route('bookings.store') }}"]');
    const bookNowBtn = document.getElementById('bookNowBtn');
    const bookingSuccessModal = new bootstrap.Modal(document.getElementById('bookingSuccessModal'));
    
    if (bookingForm && bookNowBtn) {
        console.log('Booking form found, adding event listener');
        bookingForm.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            e.preventDefault();
            
            // Clear previous error messages
            clearFormErrors();
            
            // Validate form before submission
            if (!validateForm()) {
                console.log('Form validation failed');
                return;
            }
            console.log('Form validation passed');
            
            // Disable the button and show loading state
            bookNowBtn.disabled = true;
            bookNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            
            // Get form data
            const formData = new FormData(bookingForm);
            
            // Add CSRF token
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            
                   // Submit form via AJAX
                   console.log('Submitting booking form...');
                   fetch(bookingForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success popup message
                    showSuccessMessage(data.message || 'Booking successful! We will contact you soon to confirm the details.');
                    
                    // Reset form
                    bookingForm.reset();
                    
                    // Redirect to home page after a short delay
                    setTimeout(() => {
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.href = '{{ route("home") }}';
                        }
                    }, 2000);
                } else {
                    // Show error messages
                    console.log('Booking failed with errors:', data.errors);
                    showFormErrors(data.errors || {});
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your booking. Please try again.');
            })
            .finally(() => {
                // Re-enable button
                bookNowBtn.disabled = false;
                bookNowBtn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Book Now';
            });
        });
    }
    
    function validateForm() {
        let isValid = true;
        const errors = {};
        
        // Required fields validation
        const requiredFields = {
            'booking_date': 'Please select a booking date',
            'booking_time': 'Please select a booking time',
            'address': 'Please enter your service address',
            'phone': 'Please enter your contact number'
        };
        
        Object.keys(requiredFields).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input && !input.value.trim()) {
                errors[field] = [requiredFields[field]];
                isValid = false;
            }
        });
        
        // Date validation
        const bookingDate = document.querySelector('[name="booking_date"]');
        if (bookingDate && bookingDate.value) {
            const selectedDate = new Date(bookingDate.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                errors['booking_date'] = ['Please select a date from today onwards'];
                isValid = false;
            }
        }
        
        // Phone validation
        const phone = document.querySelector('[name="phone"]');
        if (phone && phone.value) {
            const phoneRegex = /^[6-9]\d{9}$/;
            if (!phoneRegex.test(phone.value.replace(/\D/g, ''))) {
                errors['phone'] = ['Please enter a valid 10-digit phone number'];
                isValid = false;
            }
        }
        
        // Address length validation
        const address = document.querySelector('[name="address"]');
        if (address && address.value && address.value.length > 500) {
            errors['address'] = ['Address should not exceed 500 characters'];
            isValid = false;
        }
        
        // Special instructions length validation
        const specialInstructions = document.querySelector('[name="special_instructions"]');
        if (specialInstructions && specialInstructions.value && specialInstructions.value.length > 1000) {
            errors['special_instructions'] = ['Special instructions should not exceed 1000 characters'];
            isValid = false;
        }
        
        if (!isValid) {
            showFormErrors(errors);
        }
        
        return isValid;
    }
    
    function clearFormErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }
    
    function showFormErrors(errors) {
        // Clear previous error messages
        clearFormErrors();
        
        // Show new error messages
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errors[field][0];
                
                input.parentNode.appendChild(errorDiv);
            }
        });
    }
    
    function showSuccessMessage(message) {
        // Create success alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Add to body
        document.body.appendChild(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endsection