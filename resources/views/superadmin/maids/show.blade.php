@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-user"></i>
@endsection

@section('page-title', 'Maid Details')

@section('page-subtitle', 'View maid information and performance')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.maids.edit', $maid) }}" class="btn btn-outline-danger">
        <i class="fas fa-edit me-2"></i>Edit Maid
    </a>
    <a href="{{ route('superadmin.maids.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Maids
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Name:</label>
                            <p class="mb-0">{{ $maid->name }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <p class="mb-0">{{ $maid->email }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Phone:</label>
                            <p class="mb-0">{{ $maid->phone ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Date of Birth:</label>
                            <p class="mb-0">{{ $maid->date_of_birth ? \Carbon\Carbon::parse($maid->date_of_birth)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Gender:</label>
                            <p class="mb-0">{{ ucfirst($maid->gender ?? 'N/A') }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Hourly Rate:</label>
                            <p class="mb-0">₹{{ number_format($maid->hourly_rate ?? 0, 2) }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Experience:</label>
                            <p class="mb-0">{{ $maid->experience_years ?? 0 }} years</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Specialization:</label>
                            <p class="mb-0">{{ $maid->specialization ?? 'General' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Service Categories:</label>
                            <div class="mb-0">
                                @if($maid->service_categories && !empty($maid->service_categories))
                                    @php
                                        $serviceCategories = is_array($maid->service_categories) ? $maid->service_categories : json_decode($maid->service_categories, true);
                                    @endphp
                                    @if(is_array($serviceCategories) && !empty($serviceCategories))
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($serviceCategories as $category)
                                                <span class="badge bg-danger text-white">{{ $category }}</span>
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
                    </div>
                </div>
                
                @if($maid->address)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Address:</label>
                    <p class="mb-0">{{ $maid->address }}</p>
                </div>
                @endif
                
                @if($maid->bio)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Bio:</label>
                    <p class="mb-0">{{ $maid->bio }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Status Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Status Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="status-indicator">
                            @if($maid->is_active)
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h6 class="mb-1">Active</h6>
                                <span class="badge bg-success">Active Maid</span>
                            @else
                                <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                <h6 class="mb-1">Inactive</h6>
                                <span class="badge bg-secondary">Inactive Maid</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <div class="status-indicator">
                            @if($maid->is_available)
                                <i class="fas fa-clock fa-2x text-success mb-2"></i>
                                <h6 class="mb-1">Available</h6>
                                <span class="badge bg-success">Available Now</span>
                            @else
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h6 class="mb-1">Busy</h6>
                                <span class="badge bg-warning">Currently Busy</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <div class="status-indicator">
                            @if($maid->is_verified)
                                <i class="fas fa-shield-alt fa-2x text-info mb-2"></i>
                                <h6 class="mb-1">Verified</h6>
                                <span class="badge bg-info">Verified Maid</span>
                            @else
                                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                <h6 class="mb-1">Unverified</h6>
                                <span class="badge bg-warning">Not Verified</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>Performance Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="stat-item">
                            <h4 class="text-danger">{{ number_format($maid->rating ?? 0, 1) }}</h4>
                            <small class="text-muted">Average Rating</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stat-item">
                            <h4 class="text-success">{{ $maid->bookings_count ?? 0 }}</h4>
                            <small class="text-muted">Completed Bookings</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stat-item">
                            <h4 class="text-info">{{ $maid->experience_years ?? 0 }}</h4>
                            <small class="text-muted">Years Experience</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stat-item">
                            <h4 class="text-warning">₹{{ number_format(($maid->hourly_rate ?? 0) * 8, 2) }}</h4>
                            <small class="text-muted">Daily Rate (8hrs)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Profile Image -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Profile Image</h5>
            </div>
            <div class="card-body text-center">
                @if($maid->profile_image)
                    <img src="{{ asset('storage/' . $maid->profile_image) }}" 
                         alt="{{ $maid->name }}" 
                         class="img-fluid rounded shadow" 
                         style="max-height: 300px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-user fa-3x text-muted"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('superadmin.maids.edit', $maid) }}" class="btn btn-outline-danger">
                        <i class="fas fa-edit me-2"></i>Edit Maid
                    </a>
                    
                    <form method="POST" action="{{ route('superadmin.maids.toggle-availability', $maid) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_available" value="{{ $maid->is_available ? 0 : 1 }}">
                        <button type="submit" class="btn btn-{{ $maid->is_available ? 'outline-warning' : 'outline-success' }} w-100">
                            <i class="fas fa-{{ $maid->is_available ? 'pause' : 'play' }} me-2"></i>
                            {{ $maid->is_available ? 'Mark as Busy' : 'Mark as Available' }}
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete('{{ route('superadmin.maids.destroy', $maid) }}')">
                        <i class="fas fa-trash me-2"></i>Delete Maid
                    </button>
                </div>
            </div>
        </div>

        <!-- Maid Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Maid Information</h5>
            </div>
            <div class="card-body">
                <div class="info-item mb-2">
                    <small class="text-muted">
                        <strong>ID:</strong> {{ $maid->id }}<br>
                        <strong>Created:</strong> {{ $maid->created_at->format('M d, Y') }}<br>
                        <strong>Last Updated:</strong> {{ $maid->updated_at->format('M d, Y') }}
                    </small>
                </div>
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
                Are you sure you want to delete this maid? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Maid</button>
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
