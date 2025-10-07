@extends('layouts.admin')

@section('page-icon')
<i class="fas fa-user"></i>
@endsection

@section('page-title', 'Maid Details')

@section('page-subtitle', 'View and manage maid information')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('admin.maids.edit', $maid) }}" class="btn btn-outline-primary">
        <i class="fas fa-edit me-2"></i>Edit Maid
    </a>
    <a href="{{ route('admin.maids.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Maids
    </a>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Maid Profile -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="{{ $maid->profile_image ? asset('storage/' . $maid->profile_image) : 'https://via.placeholder.com/150x150/6c757d/ffffff?text=M' }}" 
                         alt="{{ $maid->name }}" 
                         class="rounded-circle" 
                         width="150" 
                         height="150">
                </div>
                <h4 class="mb-1">{{ $maid->name }}</h4>
                <p class="text-muted mb-3">{{ $maid->specialization ?? 'General Maid' }}</p>
                
                <!-- Status Badges -->
                <div class="mb-3">
                    @if($maid->is_active)
                        <span class="badge bg-success me-1">Active</span>
                    @else
                        <span class="badge bg-danger me-1">Inactive</span>
                    @endif
                    
                    @if($maid->is_verified)
                        <span class="badge bg-primary me-1">Verified</span>
                    @endif
                    
                    @if($maid->is_available)
                        <span class="badge bg-info">Available</span>
                    @else
                        <span class="badge bg-warning">Busy</span>
                    @endif
                </div>

                <!-- Rating -->
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $maid->rating ? '' : '-o' }}"></i>
                            @endfor
                        </span>
                        <span class="fw-bold">{{ number_format($maid->rating, 1) }}</span>
                        <small class="text-muted ms-1">({{ $maid->total_ratings }} reviews)</small>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('admin.maids.toggle-availability', $maid) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_available" value="{{ $maid->is_available ? 0 : 1 }}">
                        <button type="submit" class="btn btn-{{ $maid->is_available ? 'warning' : 'success' }} btn-sm w-100">
                            <i class="fas fa-{{ $maid->is_available ? 'pause' : 'play' }} me-2"></i>
                            {{ $maid->is_available ? 'Mark as Busy' : 'Mark as Available' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-address-book me-2"></i>Contact Information
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <i class="fas fa-envelope text-muted me-2"></i>
                    <span>{{ $maid->email }}</span>
                </div>
                <div class="mb-2">
                    <i class="fas fa-phone text-muted me-2"></i>
                    <span>{{ $maid->phone }}</span>
                </div>
                @if($maid->address)
                    <div class="mb-2">
                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                        <span>{{ $maid->address }}</span>
                    </div>
                @endif
                @if($maid->date_of_birth)
                    <div class="mb-2">
                        <i class="fas fa-birthday-cake text-muted me-2"></i>
                        <span>{{ $maid->date_of_birth->format('M d, Y') }}</span>
                        <small class="text-muted">({{ $maid->date_of_birth->age }} years old)</small>
                    </div>
                @endif
                @if($maid->gender)
                    <div class="mb-2">
                        <i class="fas fa-{{ $maid->gender == 'female' ? 'venus' : ($maid->gender == 'male' ? 'mars' : 'genderless') }} text-muted me-2"></i>
                        <span>{{ ucfirst($maid->gender) }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Maid Details -->
    <div class="col-lg-8">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $stats['total_bookings'] }}</h4>
                        <small>Total Bookings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $stats['completed_bookings'] }}</h4>
                        <small>Completed</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $stats['pending_bookings'] + $stats['confirmed_bookings'] + $stats['in_progress_bookings'] }}</h4>
                        <small>Active</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">₹{{ number_format($maid->hourly_rate, 0) }}</h4>
                        <small>Hourly Rate</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-briefcase me-2"></i>Professional Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Specialization</label>
                        <p class="mb-0">{{ $maid->specialization ?? 'General Maid' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Experience</label>
                        <p class="mb-0">{{ $maid->experience_years ?? 0 }} years</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Service Categories</label>
                        <div class="mb-0">
                            @if($maid->service_categories && !empty($maid->service_categories))
                                @php
                                    $serviceCategories = is_array($maid->service_categories) ? $maid->service_categories : json_decode($maid->service_categories, true);
                                @endphp
                                @if(is_array($serviceCategories) && !empty($serviceCategories))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($serviceCategories as $category)
                                            <span class="badge bg-primary text-white">{{ $category }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Working Hours</label>
                        <p class="mb-0">
                            @if($maid->working_hours)
                                @if(is_array($maid->working_hours))
                                    {{ implode(', ', $maid->working_hours) }}
                                @else
                                    {{ $maid->working_hours }}
                                @endif
                            @else
                                Not specified
                            @endif
                        </p>
                    </div>
                    @if($maid->service_areas)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Service Areas</label>
                            <p class="mb-0">
                                @if(is_array($maid->service_areas))
                                    @foreach($maid->service_areas as $area)
                                        <span class="badge bg-light text-dark me-1">{{ trim($area) }}</span>
                                    @endforeach
                                @else
                                    {{ $maid->service_areas }}
                                @endif
                            </p>
                        </div>
                    @endif
                    @if($maid->bio)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Bio</label>
                            <p class="mb-0">{{ $maid->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Personal Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($maid->marital_status)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Marital Status</label>
                            <p class="mb-0">{{ ucfirst($maid->marital_status) }}</p>
                        </div>
                    @endif
                    
                    @if($maid->husband_name)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Husband Name</label>
                            <p class="mb-0">{{ $maid->husband_name }}</p>
                        </div>
                    @endif
                    
                    @if($maid->father_name)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Father Name</label>
                            <p class="mb-0">{{ $maid->father_name }}</p>
                        </div>
                    @endif
                    
                    @if($maid->reference_contact)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Reference Contact</label>
                            <p class="mb-0">{{ $maid->reference_contact }}</p>
                        </div>
                    @endif
                    
                    @if($maid->reference_phone)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Reference Phone</label>
                            <p class="mb-0">{{ $maid->reference_phone }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Document Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-id-card me-2"></i>Document Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($maid->aadhar_number)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Aadhar Number</label>
                            <p class="mb-0">{{ $maid->aadhar_number }}</p>
                        </div>
                    @endif
                    
                    @if($maid->pan_number)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">PAN Number</label>
                            <p class="mb-0">{{ $maid->pan_number }}</p>
                        </div>
                    @endif
                    
                    @if($maid->address_proof_type)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Address Proof Type</label>
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $maid->address_proof_type)) }}</p>
                        </div>
                    @endif
                    
                    @if($maid->latitude && $maid->longitude)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Location Coordinates</label>
                            <p class="mb-0">
                                {{ $maid->latitude }}, {{ $maid->longitude }}
                                @if($maid->google_maps_url)
                                    <a href="{{ $maid->google_maps_url }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>View on Map
                                    </a>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
                
                <!-- Document Status -->
                @php
                    $documentStatus = $maid->document_status;
                @endphp
                <div class="mt-3">
                    <label class="form-label fw-bold">Document Completion Status</label>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $documentStatus['percentage'] }}%">
                            {{ $documentStatus['completed'] }}/{{ $documentStatus['total'] }} Documents
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-{{ $documentStatus['documents']['aadhar_card'] ? 'check text-success' : 'times text-danger' }} me-1"></i>
                                Aadhar Card
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-{{ $documentStatus['documents']['pan_card'] ? 'check text-success' : 'times text-danger' }} me-1"></i>
                                PAN Card
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-{{ $documentStatus['documents']['address_proof_document'] ? 'check text-success' : 'times text-danger' }} me-1"></i>
                                Address Proof
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-{{ $documentStatus['documents']['police_verification'] ? 'check text-success' : 'times text-danger' }} me-1"></i>
                                Police Verification
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-{{ $documentStatus['documents']['medical_certificate'] ? 'check text-success' : 'times text-danger' }} me-1"></i>
                                Medical Certificate
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skills and Languages -->
        <div class="row mb-4">
            @if($maid->skills)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-tools me-2"></i>Skills
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $skills = is_string($maid->skills) ? json_decode($maid->skills, true) : $maid->skills;
                            @endphp
                            @if(is_array($skills))
                                @foreach($skills as $skill)
                                    <span class="badge bg-secondary me-1 mb-1">{{ trim($skill) }}</span>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No skills specified</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($maid->languages)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-language me-2"></i>Languages
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $languages = is_string($maid->languages) ? json_decode($maid->languages, true) : $maid->languages;
                            @endphp
                            @if(is_array($languages))
                                @foreach($languages as $language)
                                    <span class="badge bg-info me-1 mb-1">{{ trim($language) }}</span>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No languages specified</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Bookings -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-calendar-check me-2"></i>Recent Bookings
                </h6>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Service</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-decoration-none">
                                                #{{ $booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                                            </a>
                                        </td>
                                        <td>{{ $booking->service->name }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status_color }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>₹{{ number_format($booking->total_amount, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No bookings found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Booking Assignment Modal -->
<div class="modal fade" id="assignBookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Booking to {{ $maid->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.maids.assign-booking', $maid) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="booking_id" class="form-label">Select Booking</label>
                        <select class="form-select" id="booking_id" name="booking_id" required>
                            <option value="">Choose a booking...</option>
                            <!-- This would be populated with available bookings -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
