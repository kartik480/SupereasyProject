@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-concierge-bell"></i>
@endsection

@section('page-title', 'Service Details')

@section('page-subtitle', 'View service information and details')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.services.edit', $service) }}" class="btn btn-outline-danger">
        <i class="fas fa-edit me-2"></i>Edit Service
    </a>
    <a href="{{ route('superadmin.services.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Services
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Service Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-concierge-bell me-2"></i>Service Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Name:</label>
                            <p class="mb-0">{{ $service->name }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Main Category:</label>
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $service->main_category ?? 'N/A')) }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Subcategory:</label>
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $service->subcategory ?? 'N/A')) }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Duration:</label>
                            <p class="mb-0">{{ $service->duration ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Price:</label>
                            <p class="mb-0">₹{{ number_format($service->price, 2) }}</p>
                        </div>
                        
                        @if($service->discount_price && $service->discount_price < $service->price)
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Discount Price:</label>
                            <p class="mb-0 text-success">₹{{ number_format($service->discount_price, 2) }}</p>
                        </div>
                        @endif
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Booking Advance:</label>
                            <p class="mb-0">{{ $service->booking_advance_hours ?? 0 }} hours</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <p class="mb-0">
                                @if($service->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                                @if($service->is_featured)
                                    <span class="badge bg-warning">Featured</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($service->description)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Description:</label>
                    <p class="mb-0">{{ $service->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Service Images -->
        @if($service->image || $service->image_2 || $service->image_3 || $service->image_4)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-images me-2"></i>Service Images
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($service->image)
                    <div class="col-md-6 mb-3">
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             alt="{{ $service->name }}" 
                             class="img-fluid rounded shadow">
                    </div>
                    @endif
                    @if($service->image_2)
                    <div class="col-md-6 mb-3">
                        <img src="{{ asset('storage/' . $service->image_2) }}" 
                             alt="{{ $service->name }}" 
                             class="img-fluid rounded shadow">
                    </div>
                    @endif
                    @if($service->image_3)
                    <div class="col-md-6 mb-3">
                        <img src="{{ asset('storage/' . $service->image_3) }}" 
                             alt="{{ $service->name }}" 
                             class="img-fluid rounded shadow">
                    </div>
                    @endif
                    @if($service->image_4)
                    <div class="col-md-6 mb-3">
                        <img src="{{ asset('storage/' . $service->image_4) }}" 
                             alt="{{ $service->name }}" 
                             class="img-fluid rounded shadow">
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Additional Information -->
        @if($service->features || $service->requirements || $service->booking_requirements)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Additional Information
                </h5>
            </div>
            <div class="card-body">
                @if($service->features)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Features:</label>
                    <p class="mb-0">{{ $service->features }}</p>
                </div>
                @endif
                
                @if($service->requirements)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Requirements:</label>
                    <p class="mb-0">{{ $service->requirements }}</p>
                </div>
                @endif
                
                @if($service->booking_requirements)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Booking Requirements:</label>
                    <p class="mb-0">{{ $service->booking_requirements }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Service Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Service Status</h5>
            </div>
            <div class="card-body text-center">
                @if($service->is_active)
                    <div class="status-indicator">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6 class="mb-2">Active</h6>
                        <span class="badge bg-success">Active Service</span>
                    </div>
                @else
                    <div class="status-indicator">
                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                        <h6 class="mb-2">Inactive</h6>
                        <span class="badge bg-secondary">Inactive Service</span>
                    </div>
                @endif
                
                @if($service->is_featured)
                <div class="mt-3">
                    <span class="badge bg-warning">Featured Service</span>
                </div>
                @endif
                
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Created:</strong> {{ $service->created_at->format('M d, Y') }}<br>
                        <strong>Last Updated:</strong> {{ $service->updated_at->format('M d, Y') }}
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
                    <a href="{{ route('superadmin.services.edit', $service) }}" class="btn btn-outline-danger">
                        <i class="fas fa-edit me-2"></i>Edit Service
                    </a>
                    
                    <form method="POST" action="{{ route('superadmin.services.toggle-status', $service) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $service->is_active ? 0 : 1 }}">
                        <button type="submit" class="btn btn-{{ $service->is_active ? 'outline-warning' : 'outline-success' }} w-100">
                            <i class="fas fa-{{ $service->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete('{{ route('superadmin.services.destroy', $service) }}')">
                        <i class="fas fa-trash me-2"></i>Delete Service
                    </button>
                </div>
            </div>
        </div>

        <!-- Service Preview -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Service Preview</h5>
            </div>
            <div class="card-body">
                <div class="service-preview">
                    <div class="preview-image text-center mb-3">
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" 
                                 alt="{{ $service->name }}" 
                                 class="img-fluid rounded">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 150px;">
                                <i class="fas fa-concierge-bell fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="preview-content text-center">
                        <h6 class="mb-1">{{ $service->name }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($service->description, 100) }}</p>
                        <div class="preview-meta">
                            <small class="text-muted">
                                <strong>₹{{ number_format($service->discount_price ?: $service->price, 2) }}</strong>
                                @if($service->discount_price && $service->discount_price < $service->price)
                                    <span class="text-decoration-line-through text-muted">₹{{ number_format($service->price, 2) }}</span>
                                @endif
                            </small>
                        </div>
                    </div>
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
                Are you sure you want to delete this service? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Service</button>
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
