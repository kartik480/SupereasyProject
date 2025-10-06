@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-plus"></i>
@endsection

@section('page-title', 'Create New Offer')
@section('page-subtitle', 'Add a new promotional offer')

@section('header-actions')
<a href="{{ route('superadmin.offers.index') }}" class="action-btn btn-outline">
    <i class="fas fa-arrow-left"></i>Back to Offers
</a>
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
                <h5 class="card-title mb-0">
                    <i class="fas fa-percentage me-2"></i>Offer Details
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('superadmin.offers.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Offer Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Promo Code</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" 
                                   placeholder="Leave empty for auto-generation">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
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
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('discount_value') is-invalid @enderror" 
                                   id="discount_value" name="discount_value" value="{{ old('discount_value') }}" required>
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
                                   id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}">
                            @error('min_order_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="max_discount_amount" class="form-label">Maximum Discount Amount</label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('max_discount_amount') is-invalid @enderror" 
                                   id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}">
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
                                   id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}">
                            <div class="form-text">Leave empty for unlimited usage</div>
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Offer Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Recommended size: 400x300px</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Offer
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="action-btn">
                            <i class="fas fa-save"></i>Create Offer
                        </button>
                        <a href="{{ route('superadmin.offers.index') }}" class="action-btn btn-outline">
                            <i class="fas fa-times"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-eye me-2"></i>Preview
                </h5>
            </div>
            <div class="card-body">
                <div class="offer-preview">
                    <div class="preview-image">
                        <img id="preview-img" src="https://via.placeholder.com/300x200/dc3545/ffffff?text=Offer+Image" 
                             alt="Preview" class="img-fluid rounded">
                    </div>
                    <div class="preview-content mt-3">
                        <h6 id="preview-title">Offer Title</h6>
                        <p id="preview-description" class="text-muted small">Offer description will appear here...</p>
                        <div class="preview-discount">
                            <span id="preview-discount-value" class="badge bg-danger">0% OFF</span>
                        </div>
                        <div class="preview-dates mt-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <span id="preview-dates">Start - End Date</span>
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
