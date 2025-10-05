@extends('layouts.admin')

@section('page-icon')
<i class="fas fa-eye"></i>
@endsection

@section('page-title', 'Offer Details')

@section('page-subtitle', 'View offer information and statistics')

@section('header-actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-outline-primary">
        <i class="fas fa-edit me-2"></i>Edit Offer
    </a>
    <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Offers
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
    <div class="col-lg-8">
        <!-- Offer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Offer Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Title:</label>
                            <p class="mb-0">{{ $offer->title }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Promo Code:</label>
                            <p class="mb-0">
                                @if($offer->code)
                                    <code class="bg-light px-2 py-1 rounded">{{ $offer->code }}</code>
                                @else
                                    <span class="text-muted">No code</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Description:</label>
                            <p class="mb-0">{{ $offer->description }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Discount Type:</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ ucfirst($offer->discount_type) }}</span>
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Discount Value:</label>
                            <p class="mb-0">
                                @if($offer->discount_type === 'percentage')
                                    <span class="h5 text-success">{{ $offer->discount_value }}%</span>
                                @else
                                    <span class="h5 text-success">₹{{ number_format($offer->discount_value, 2) }}</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <p class="mb-0">
                                @if($offer->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offer Image -->
        @if($offer->image)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Offer Image</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $offer->image) }}" 
                     alt="{{ $offer->title }}" 
                     class="img-fluid rounded shadow" 
                     style="max-height: 400px;">
            </div>
        </div>
        @endif

        <!-- Terms & Conditions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Terms & Conditions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Minimum Order Amount:</label>
                            <p class="mb-0">
                                @if($offer->min_order_amount)
                                    ₹{{ number_format($offer->min_order_amount, 2) }}
                                @else
                                    <span class="text-muted">No minimum</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Maximum Discount:</label>
                            <p class="mb-0">
                                @if($offer->max_discount_amount)
                                    ₹{{ number_format($offer->max_discount_amount, 2) }}
                                @else
                                    <span class="text-muted">No limit</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Usage Limit:</label>
                            <p class="mb-0">
                                @if($offer->usage_limit)
                                    {{ $offer->usage_limit }} times
                                @else
                                    <span class="text-muted">Unlimited</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Times Used:</label>
                            <p class="mb-0">{{ $offer->usage_count ?? 0 }} times</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Offer Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Offer Status</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $now = now();
                    $startDate = $offer->start_date;
                    $endDate = $offer->end_date;
                    
                    if ($now < $startDate) {
                        $status = 'upcoming';
                        $badgeClass = 'bg-warning';
                        $icon = 'fas fa-clock';
                    } elseif ($now > $endDate) {
                        $status = 'expired';
                        $badgeClass = 'bg-danger';
                        $icon = 'fas fa-times-circle';
                    } else {
                        $status = 'active';
                        $badgeClass = 'bg-success';
                        $icon = 'fas fa-check-circle';
                    }
                @endphp
                
                <div class="status-indicator">
                    <i class="{{ $icon }} fa-3x text-{{ $status === 'upcoming' ? 'warning' : ($status === 'expired' ? 'danger' : 'success') }} mb-3"></i>
                    <h6 class="mb-2">{{ ucfirst($status) }}</h6>
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Start:</strong> {{ $startDate->format('M d, Y') }}<br>
                        <strong>End:</strong> {{ $endDate->format('M d, Y') }}
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
                    <form method="POST" action="{{ route('admin.offers.toggle-status', $offer) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $offer->is_active ? 0 : 1 }}">
                        <button type="submit" class="btn btn-{{ $offer->is_active ? 'outline-warning' : 'outline-success' }} w-100">
                            <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $offer->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Offer
                    </a>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete('{{ route('admin.offers.destroy', $offer) }}')">
                        <i class="fas fa-trash me-2"></i>Delete Offer
                    </button>
                </div>
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Usage Statistics</h5>
            </div>
            <div class="card-body">
                <div class="stat-item mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Times Used:</span>
                        <strong>{{ $offer->usage_count ?? 0 }}</strong>
                    </div>
                </div>
                
                @if($offer->usage_limit)
                <div class="stat-item mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Usage Limit:</span>
                        <strong>{{ $offer->usage_limit }}</strong>
                    </div>
                </div>
                
                <div class="stat-item mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Remaining:</span>
                        <strong>{{ max(0, $offer->usage_limit - ($offer->usage_count ?? 0)) }}</strong>
                    </div>
                </div>
                
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" 
                         style="width: {{ min(100, (($offer->usage_count ?? 0) / $offer->usage_limit) * 100) }}%">
                    </div>
                </div>
                @endif
                
                <div class="stat-item">
                    <div class="d-flex justify-content-between">
                        <span>Created:</span>
                        <strong>{{ $offer->created_at->format('M d, Y') }}</strong>
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
                Are you sure you want to delete this offer? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Offer</button>
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
