@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-edit"></i>
@endsection

@section('page-title', 'Edit Offer')

@section('page-subtitle', 'Update offer information')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.offers.show', $offer) }}" class="btn btn-outline-info">
        <i class="fas fa-eye me-2"></i>View Details
    </a>
    <a href="{{ route('superadmin.offers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Offers
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Offer Information
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('superadmin.offers.update', $offer) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Offer Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $offer->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Promo Code</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $offer->code) }}">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description', $offer->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('discount_type') is-invalid @enderror" 
                                    id="discount_type" name="discount_type" required>
                                <option value="">Select Type</option>
                                <option value="percentage" {{ old('discount_type', $offer->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ old('discount_type', $offer->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('discount_value') is-invalid @enderror" 
                                   id="discount_value" name="discount_value" value="{{ old('discount_value', $offer->discount_value) }}" required>
                            @error('discount_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="min_order_amount" class="form-label">Minimum Order Amount</label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('min_order_amount') is-invalid @enderror" 
                                   id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount', $offer->min_order_amount) }}">
                            @error('min_order_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="max_discount_amount" class="form-label">Maximum Discount Amount</label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('max_discount_amount') is-invalid @enderror" 
                                   id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount', $offer->max_discount_amount) }}">
                            @error('max_discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="usage_limit" class="form-label">Usage Limit</label>
                            <input type="number" min="1" 
                                   class="form-control @error('usage_limit') is-invalid @enderror" 
                                   id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $offer->usage_limit) }}">
                            <div class="form-text">Leave empty for unlimited usage</div>
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Offer Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Leave empty to keep current image</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date', $offer->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date', $offer->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $offer->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Offer
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('superadmin.offers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>Update Offer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        @if($offer->image)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Image</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $offer->image) }}" 
                     alt="{{ $offer->title }}" 
                     class="img-fluid rounded" 
                     style="max-height: 200px;">
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('superadmin.offers.toggle-status', $offer) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $offer->is_active ? 0 : 1 }}">
                        <button type="submit" class="btn btn-{{ $offer->is_active ? 'outline-warning' : 'outline-success' }} w-100">
                            <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $offer->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete('{{ route('superadmin.offers.destroy', $offer) }}')">
                        <i class="fas fa-trash me-2"></i>Delete Offer
                    </button>
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
