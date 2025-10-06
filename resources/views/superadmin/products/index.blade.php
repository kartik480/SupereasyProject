@extends('layouts.superadmin')

@section('title', 'Products - SuperAdmin')

@section('page-icon')
<i class="fas fa-shopping-cart"></i>
@endsection
@section('page-title', 'Products')
@section('page-subtitle', 'Manage your product inventory')

@section('header-actions')
<a href="{{ route('superadmin.products.create') }}" class="action-btn">
    <i class="fas fa-plus"></i>Add Product
</a>
<a href="{{ route('superadmin.products.bulk-upload') }}" class="action-btn btn-outline">
    <i class="fas fa-upload"></i>Bulk Upload
</a>
@endsection

@section('content')
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-shopping-cart"></i>All Products
        </h5>
    </div>
    <div class="card-body p-0">
        @if($products->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $product->name }}</div>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-info">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-muted">No category</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">₹{{ number_format($product->price, 2) }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $product->stock_quantity < 10 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="status-badge active">Active</span>
                                @else
                                    <span class="status-badge inactive">Inactive</span>
                                @endif
                                @if($product->is_featured)
                                    <span class="status-badge featured">Featured</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('superadmin.products.show', $product) }}" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.products.edit', $product) }}" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('superadmin.products.destroy', $product) }}" 
                                          method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete">
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
        @else
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h5>No Products Found</h5>
                <p>Add your first product to start selling.</p>
                <a href="{{ route('superadmin.products.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>Add Product
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
