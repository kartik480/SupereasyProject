@extends('layouts.admin')

@section('page-icon')
<i class="fas fa-tags"></i>
@endsection

@section('page-title', 'Categories Management')

@section('page-subtitle', 'Manage product and service categories')

@section('header-actions')
<a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Add New Category
</a>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $totalCategories }}</h4>
                <small>Total Categories</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $activeCategories }}</h4>
                <small>Active Categories</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $categoriesWithProducts }}</h4>
                <small>With Products</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $categoriesWithServices }}</h4>
                <small>With Services</small>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Categories</h5>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Services</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" 
                                             alt="{{ $category->name }}" 
                                             class="rounded" 
                                             width="50" 
                                             height="50">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            @if($category->icon)
                                                <i class="{{ $category->icon }} text-muted"></i>
                                            @else
                                                <i class="fas fa-tag text-muted"></i>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $category->name }}</div>
                                    @if($category->description)
                                        <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $category->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->products_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $category->services_count }}</span>
                                </td>
                                <td>
                                    {{ $category->sort_order ?? 0 }}
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $category->is_active ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $category->is_active ? 'warning' : 'success' }}" 
                                                    title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this category?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Categories Found</h4>
                    <p class="text-muted">Start by creating your first category.</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Category
                </a>
            </div>
        @endif
    </div>
</div>
@endsection