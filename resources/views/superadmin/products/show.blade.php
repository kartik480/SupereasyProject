@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-box"></i>
@endsection

@section('page-title', 'Product Details')

@section('page-subtitle', 'View product information and details')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.products.edit', $product) }}" class="btn btn-outline-danger">
        <i class="fas fa-edit me-2"></i>Edit Product
    </a>
    <a href="{{ route('superadmin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Products
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Product Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-box me-2"></i>Product Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Name:</label>
                            <p class="mb-0">{{ $product->name }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">SKU:</label>
                            <p class="mb-0">{{ $product->sku ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Category:</label>
                            <p class="mb-0">{{ $product->category->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Unit:</label>
                            <p class="mb-0">{{ ucfirst($product->unit ?? 'N/A') }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Price:</label>
                            <p class="mb-0">₹{{ number_format($product->price, 2) }}</p>
                        </div>
                        
                        @if($product->discount_price)
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Discount Price:</label>
                            <p class="mb-0 text-success">₹{{ number_format($product->discount_price, 2) }}</p>
                        </div>
                        @endif
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Stock Quantity:</label>
                            <p class="mb-0 {{ $product->stock_quantity < 10 ? 'text-danger' : '' }}">
                                {{ $product->stock_quantity }} {{ $product->unit }}
                                @if($product->stock_quantity < 10)
                                    <span class="badge bg-danger">Low Stock!</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <p class="mb-0">
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                                @if($product->is_featured)
                                    <span class="badge bg-warning">Featured</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($product->description)
                <div class="info-item mb-3">
                    <label class="form-label fw-bold">Description:</label>
                    <p class="mb-0">{{ $product->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Images -->
        @if($product->image || $product->image_2 || $product->image_3 || $product->image_4)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-images me-2"></i>Product Images
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($product->image)
                    <div class="col-md-6 mb-3">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow">
                    </div>
                    @endif
                    @if($product->image_2)
                    <div class="col-md-6 mb-3">
                        <img src="{{ asset('storage/' . $product->image_2) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow">
                    </div>
                    @endif
                    @if($product->image_3)
                    <div class="col-md-6 mb-3">
                        <img src="{{ asset('storage/' . $product->image_3) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow">
                    </div>
                    @endif
                    @if($product->image_4)
                    <div class="col-md-6 mb-3">
                        <img src="{{ asset('storage/' . $product->image_4) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow">
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Product Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Status</h5>
            </div>
            <div class="card-body text-center">
                @if($product->is_active)
                    <div class="status-indicator">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6 class="mb-2">Active</h6>
                        <span class="badge bg-success">Active Product</span>
                    </div>
                @else
                    <div class="status-indicator">
                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                        <h6 class="mb-2">Inactive</h6>
                        <span class="badge bg-secondary">Inactive Product</span>
                    </div>
                @endif
                
                @if($product->is_featured)
                <div class="mt-3">
                    <span class="badge bg-warning">Featured Product</span>
                </div>
                @endif
                
                @if($product->stock_quantity < 10)
                <div class="mt-3">
                    <span class="badge bg-danger">Low Stock Alert!</span>
                </div>
                @endif
                
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}<br>
                        <strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y') }}
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
                    <a href="{{ route('superadmin.products.edit', $product) }}" class="btn btn-outline-danger">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </a>
                    
                    <form method="POST" action="{{ route('superadmin.products.toggle-status', $product) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $product->is_active ? 0 : 1 }}">
                        <button type="submit" class="btn btn-{{ $product->is_active ? 'outline-warning' : 'outline-success' }} w-100">
                            <i class="fas fa-{{ $product->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete('{{ route('superadmin.products.destroy', $product) }}')">
                        <i class="fas fa-trash me-2"></i>Delete Product
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Preview -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Preview</h5>
            </div>
            <div class="card-body">
                <div class="product-preview">
                    <div class="preview-image text-center mb-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 150px;">
                                <i class="fas fa-box fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="preview-content text-center">
                        <h6 class="mb-1">{{ $product->name }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($product->description, 100) }}</p>
                        <div class="preview-meta">
                            <small class="text-muted">
                                <strong>₹{{ number_format($product->discount_price ?: $product->price, 2) }}</strong>
                                @if($product->discount_price && $product->discount_price < $product->price)
                                    <span class="text-decoration-line-through text-muted">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </small>
                        </div>
                        <div class="preview-stock mt-2">
                            <small class="text-muted">
                                Stock: <strong>{{ $product->stock_quantity }} {{ $product->unit }}</strong>
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
                Are you sure you want to delete this product? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Product</button>
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
