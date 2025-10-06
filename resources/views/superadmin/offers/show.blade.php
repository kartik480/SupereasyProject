@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-percentage"></i>
@endsection

@section('page-title', 'Offer Details')

@section('page-subtitle', 'View offer information and details')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.offers.edit', $offer) }}" class="btn btn-outline-danger">
        <i class="fas fa-edit me-2"></i>Edit Offer
    </a>
    <a href="{{ route('superadmin.offers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Offers
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Offer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-percentage me-2"></i>Offer Information
                </h5>
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
                            <p class="mb-0">{{ $offer->code ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Discount Type:</label>
                            <p class="mb-0">{{ ucfirst($offer->discount_type) }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Discount Value:</label>
                            <p class="mb-0 text-success fw-bold">
                                @if($offer->discount_type === 'percentage')
                                    {{ $offer->discount_value }}%
                                @else
                                    ₹{{ number_format($offer->discount_value, 2) }}
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Start Date:</label>
                            <p class="mb-0">{{ $offer->start_date->format('M d, Y') }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">End Date:</label>
                            <p class="mb-0">{{ $offer->end_date->format('M d, Y') }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Usage Limit:</label>
                            <p class="mb-0">{{ $offer->usage_limit ? $offer->usage_limit : 'Unlimited' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Usage Count:</label>
                            <p class="mb-0">{{ $offer->usage_count ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                @if($offer->description)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Description:</label>
                    <p class="mb-0">{{ $offer->description }}</p>
                </div>
                @endif
                
                @if($offer->min_order_amount)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Minimum Order Amount:</label>
                    <p class="mb-0">₹{{ number_format($offer->min_order_amount, 2) }}</p>
                </div>
                @endif
                
                @if($offer->max_discount_amount)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Maximum Discount Amount:</label>
                    <p class="mb-0">₹{{ number_format($offer->max_discount_amount, 2) }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Offer Image -->
        @if($offer->image)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-image me-2"></i>Offer Image
                </h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $offer->image) }}" 
                     alt="{{ $offer->title }}" 
                     class="img-fluid rounded shadow" 
                     style="max-height: 400px;">
            </div>
        </div>
        @endif
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
                        $statusConfig = ['icon' => 'fas fa-calendar-plus', 'color' => 'warning', 'text' => 'Upcoming'];
                    } elseif ($now > $endDate) {
                        $status = 'expired';
                        $statusConfig = ['icon' => 'fas fa-times-circle', 'color' => 'danger', 'text' => 'Expired'];
                    } else {
                        $status = 'active';
                        $statusConfig = ['icon' => 'fas fa-check-circle', 'color' => 'success', 'text' => 'Active'];
                    }
                @endphp
                
                <div class="status-indicator">
                    <i class="{{ $statusConfig['icon'] }} fa-3x text-{{ $statusConfig['color'] }} mb-3"></i>
                    <h6 class="mb-2">{{ $statusConfig['text'] }}</h6>
                    <span class="badge bg-{{ $statusConfig['color'] }}">{{ ucfirst($status) }}</span>
                </div>
                
                @if($offer->is_active)
                <div class="mt-3">
                    <span class="badge bg-success">Enabled</span>
                </div>
                @else
                <div class="mt-3">
                    <span class="badge bg-secondary">Disabled</span>
                </div>
                @endif
                
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Created:</strong> {{ $offer->created_at->format('M d, Y') }}<br>
                        <strong>Last Updated:</strong> {{ $offer->updated_at->format('M d, Y') }}
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
                    <a href="{{ route('superadmin.offers.edit', $offer) }}" class="btn btn-outline-danger">
                        <i class="fas fa-edit me-2"></i>Edit Offer
                    </a>
                    
                    <form method="POST" action="{{ route('superadmin.offers.toggle-status', $offer) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $offer->is_active ? 0 : 1 }}">
                        <button type="submit" class="btn btn-{{ $offer->is_active ? 'outline-warning' : 'outline-success' }} w-100">
                            <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $offer->is_active ? 'Disable' : 'Enable' }}
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete('{{ route('superadmin.offers.destroy', $offer) }}')">
                        <i class="fas fa-trash me-2"></i>Delete Offer
                    </button>
                </div>
            </div>
        </div>

        <!-- Offer Preview -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Offer Preview</h5>
            </div>
            <div class="card-body">
                <div class="offer-preview">
                    <div class="preview-image text-center mb-3">
                        @if($offer->image)
                            <img src="{{ asset('storage/' . $offer->image) }}" 
                                 alt="{{ $offer->title }}" 
                                 class="img-fluid rounded">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 150px;">
                                <i class="fas fa-percentage fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="preview-content text-center">
                        <h6 class="mb-1">{{ $offer->title }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($offer->description, 100) }}</p>
                        <div class="preview-discount mb-2">
                            <span class="badge bg-danger">
                                @if($offer->discount_type === 'percentage')
                                    {{ $offer->discount_value }}% OFF
                                @else
                                    ₹{{ number_format($offer->discount_value, 0) }} OFF
                                @endif
                            </span>
                        </div>
                        <div class="preview-dates">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $offer->start_date->format('M d') }} - {{ $offer->end_date->format('M d, Y') }}
                            </small>
                        </div>
                        @if($offer->code)
                        <div class="preview-code mt-2">
                            <small class="text-muted">
                                Code: <strong>{{ $offer->code }}</strong>
                            </small>
                        </div>
                        @endif
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
