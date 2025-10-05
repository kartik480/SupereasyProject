@extends('layouts.admin')

@section('page-icon')
<i class="fas fa-edit"></i>
@endsection

@section('page-title', 'Edit Offer')

@section('page-subtitle', 'Update offer information')

@section('header-actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.offers.show', $offer) }}" class="btn btn-outline-info">
        <i class="fas fa-eye me-2"></i>View Offer
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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Offer Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.offers.update', $offer) }}" enctype="multipart/form-data">
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
                            <select class="form-select @error('discount_type') is-invalid @enderror" 
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
                                   id="discount_value" name="discount_value" 
                                   value="{{ old('discount_value', $offer->discount_value) }}" required>
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
                                   id="min_order_amount" name="min_order_amount" 
                                   value="{{ old('min_order_amount', $offer->min_order_amount) }}">
                            @error('min_order_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="max_discount_amount" class="form-label">Maximum Discount Amount</label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('max_discount_amount') is-invalid @enderror" 
                                   id="max_discount_amount" name="max_discount_amount" 
                                   value="{{ old('max_discount_amount', $offer->max_discount_amount) }}">
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
                                   id="usage_limit" name="usage_limit" 
                                   value="{{ old('usage_limit', $offer->usage_limit) }}">
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
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date', $offer->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" 
                                   value="{{ old('end_date', $offer->end_date->format('Y-m-d')) }}" required>
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

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Offer
                        </button>
                        <a href="{{ route('admin.offers.show', $offer) }}" class="btn btn-outline-info">
                            <i class="fas fa-eye me-2"></i>View Offer
                        </a>
                        <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Current Image -->
        @if($offer->image)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Image</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $offer->image) }}" 
                     alt="{{ $offer->title }}" 
                     class="img-fluid rounded shadow" 
                     style="max-height: 200px;">
            </div>
        </div>
        @endif

        <!-- Preview -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Preview</h5>
            </div>
            <div class="card-body">
                <div class="offer-preview">
                    <div class="preview-image">
                        <img id="preview-img" 
                             src="{{ $offer->image ? asset('storage/' . $offer->image) : 'https://via.placeholder.com/300x200/ffc107/ffffff?text=Offer+Image' }}" 
                             alt="Preview" class="img-fluid rounded">
                    </div>
                    <div class="preview-content mt-3">
                        <h6 id="preview-title">{{ $offer->title }}</h6>
                        <p id="preview-description" class="text-muted small">{{ Str::limit($offer->description, 100) }}</p>
                        <div class="preview-discount">
                            <span id="preview-discount-value" class="badge bg-success">
                                @if($offer->discount_type === 'percentage')
                                    {{ $offer->discount_value }}% OFF
                                @else
                                    ₹{{ $offer->discount_value }} OFF
                                @endif
                            </span>
                        </div>
                        <div class="preview-dates mt-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <span id="preview-dates">{{ $offer->start_date->format('M d') }} - {{ $offer->end_date->format('M d') }}</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('image');
    const previewImg = document.getElementById('preview-img');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Live preview updates
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const discountTypeInput = document.getElementById('discount_type');
    const discountValueInput = document.getElementById('discount_value');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    function updatePreview() {
        // Update title
        document.getElementById('preview-title').textContent = titleInput.value || 'Offer Title';
        
        // Update description
        document.getElementById('preview-description').textContent = descriptionInput.value || 'Offer description will appear here...';
        
        // Update discount
        const discountType = discountTypeInput.value;
        const discountValue = discountValueInput.value;
        if (discountType && discountValue) {
            const discountText = discountType === 'percentage' ? `${discountValue}% OFF` : `₹${discountValue} OFF`;
            document.getElementById('preview-discount-value').textContent = discountText;
        }
        
        // Update dates
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        if (startDate && endDate) {
            const start = new Date(startDate).toLocaleDateString();
            const end = new Date(endDate).toLocaleDateString();
            document.getElementById('preview-dates').textContent = `${start} - ${end}`;
        }
    }

    // Add event listeners for live preview
    [titleInput, descriptionInput, discountTypeInput, discountValueInput, startDateInput, endDateInput].forEach(input => {
        input.addEventListener('input', updatePreview);
    });

    // Set minimum end date to start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
});
</script>
@endsection
