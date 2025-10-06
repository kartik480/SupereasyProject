@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-eye"></i>
@endsection

@section('page-title', 'Category Details')

@section('page-subtitle', 'View category information and related items')

@section('header-actions')
<div class="d-flex gap-2">
    <a href="{{ route('superadmin.categories.edit', $category) }}" class="btn btn-outline-danger">
        <i class="fas fa-edit me-2"></i>Edit Category
    </a>
    <a href="{{ route('superadmin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Categories
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
        <!-- Category Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Name:</label>
                            <p class="mb-0">{{ $category->name }}</p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Slug:</label>
                            <p class="mb-0">
                                <code class="bg-light px-2 py-1 rounded">{{ $category->slug }}</code>
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Description:</label>
                            <p class="mb-0">{{ $category->description ?: 'No description provided' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Icon:</label>
                            <p class="mb-0">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} me-2"></i>{{ $category->icon }}
                                @else
                                    <span class="text-muted">No icon</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Color:</label>
                            <p class="mb-0">
                                @if($category->color)
                                    <span class="badge" style="background-color: {{ $category->color }}; color: white;">
                                        {{ $category->color }}
                                    </span>
                                @else
                                    <span class="text-muted">No color set</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <p class="mb-0">
                                @if($category->is_active)
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

        <!-- Category Image -->
        @if($category->image)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Image</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $category->image) }}" 
                     alt="{{ $category->name }}" 
                     class="img-fluid rounded shadow" 
                     style="max-height: 400px;">
            </div>
        </div>
        @endif

        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="stat-item">
                            <h4 class="text-danger">{{ $stats['total_products'] }}</h4>
                            <small class="text-muted">Total Products</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stat-item">
                            <h4 class="text-success">{{ $stats['active_products'] }}</h4>
                            <small class="text-muted">Active Products</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stat-item">
                            <h4 class="text-info">{{ $stats['total_services'] }}</h4>
                            <small class="text-muted">Total Services</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stat-item">
                            <h4 class="text-warning">{{ $stats['active_services'] }}</h4>
                            <small class="text-muted">Active Services</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        @if($recentProducts->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $product)
                                <tr>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded" 
                                                 width="40" 
                                                 height="40">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    </td>
                                    <td>₹{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Services -->
        @if($recentServices->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Services</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentServices as $service)
                                <tr>
                                    <td>
                                        @if($service->image)
                                            <img src="{{ asset('storage/' . $service->image) }}" 
                                                 alt="{{ $service->name }}" 
                                                 class="rounded" 
                                                 width="40" 
                                                 height="40">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-concierge-bell text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $service->name }}</div>
                                        <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                    </td>
                                    <td>₹{{ number_format($service->discounted_price, 2) }}</td>
                                    <td>
                                        @if($service->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $service->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Category Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Status</h5>
            </div>
            <div class="card-body text-center">
                @if($category->is_active)
                    <div class="status-indicator">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6 class="mb-2">Active</h6>
                        <span class="badge bg-success">Active Category</span>
                    </div>
                @else
                    <div class="status-indicator">
                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                        <h6 class="mb-2">Inactive</h6>
                        <span class="badge bg-secondary">Inactive Category</span>
                    </div>
                @endif
                
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Sort Order:</strong> {{ $category->sort_order ?? 0 }}<br>
                        <strong>Created:</strong> {{ $category->created_at->format('M d, Y') }}
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
                    <form method="POST" action="{{ route('superadmin.categories.toggle-status', $category) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $category->is_active ? 0 : 1 }}">
                        <button type="submit" class="btn btn-{{ $category->is_active ? 'outline-warning' : 'outline-success' }} w-100">
                            <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    <a href="{{ route('superadmin.categories.edit', $category) }}" class="btn btn-outline-danger">
                        <i class="fas fa-edit me-2"></i>Edit Category
                    </a>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete('{{ route('superadmin.categories.destroy', $category) }}')">
                        <i class="fas fa-trash me-2"></i>Delete Category
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Preview -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Preview</h5>
            </div>
            <div class="card-body">
                <div class="category-preview">
                    <div class="preview-image text-center mb-3">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="img-fluid rounded">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 150px;">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} fa-3x text-muted"></i>
                                @else
                                    <i class="fas fa-tag fa-3x text-muted"></i>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="preview-content text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            @if($category->icon)
                                <i class="{{ $category->icon }} me-2" 
                                   style="color: {{ $category->color ?: '#dc3545' }};"></i>
                            @endif
                            <h6 class="mb-0">{{ $category->name }}</h6>
                        </div>
                        <p class="text-muted small mb-2">{{ Str::limit($category->description, 100) }}</p>
                        <div class="preview-meta">
                            <small class="text-muted">
                                <strong>{{ $stats['total_products'] + $stats['total_services'] }}</strong> items
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
                Are you sure you want to delete this category? This action cannot be undone.
                @if($stats['total_products'] > 0 || $stats['total_services'] > 0)
                    <div class="alert alert-warning mt-2">
                        <strong>Warning:</strong> This category has {{ $stats['total_products'] + $stats['total_services'] }} items. 
                        You may need to reassign them to other categories first.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
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
