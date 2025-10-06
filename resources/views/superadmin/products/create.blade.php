@extends('layouts.superadmin')

@section('title', 'Create Product - SuperAdmin')

@section('page-icon')
<i class="fas fa-plus"></i>
@endsection
@section('page-title', 'Create Product')
@section('page-subtitle', 'Add a new product to your inventory')

@section('header-actions')
<a href="{{ route('superadmin.products.index') }}" class="action-btn btn-outline">
    <i class="fas fa-arrow-left"></i>Back to Products
</a>
@endsection

@section('content')
<!-- Form -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-box"></i>Product Information
        </h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('superadmin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Product Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="sku" class="form-label">SKU</label>
                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                           id="sku" name="sku" value="{{ old('sku') }}">
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="category_id" class="form-label">Category *</label>
                    <select class="form-control @error('category_id') is-invalid @enderror" 
                            id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Price (₹) *</label>
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="discount_price" class="form-label">Discount Price (₹)</label>
                    <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" 
                           id="discount_price" name="discount_price" value="{{ old('discount_price') }}">
                    @error('discount_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="unit" class="form-label">Unit *</label>
                    <select class="form-control @error('unit') is-invalid @enderror" 
                            id="unit" name="unit" required>
                        <option value="">Select Unit</option>
                        <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                        <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                        <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                        <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                    </select>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" required>
                    @error('stock_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            Featured Product
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <!-- Image Upload Section -->
            <div class="mb-4">
                <h6 class="fw-bold text-danger mb-3">
                    <i class="fas fa-images me-2"></i>Product Images
                </h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Main Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="image_2" class="form-label">Additional Image 1</label>
                        <input type="file" class="form-control @error('image_2') is-invalid @enderror" 
                               id="image_2" name="image_2" accept="image/*">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)</small>
                        @error('image_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="image_3" class="form-label">Additional Image 2</label>
                        <input type="file" class="form-control @error('image_3') is-invalid @enderror" 
                               id="image_3" name="image_3" accept="image/*">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)</small>
                        @error('image_3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="image_4" class="form-label">Additional Image 3</label>
                        <input type="file" class="form-control @error('image_4') is-invalid @enderror" 
                               id="image_4" name="image_4" accept="image/*">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)</small>
                        @error('image_4')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="action-btn">
                    <i class="fas fa-save"></i>Create Product
                </button>
                <a href="{{ route('superadmin.products.index') }}" class="action-btn btn-outline">
                    <i class="fas fa-times"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tips Card -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-lightbulb"></i>Tips
        </h5>
    </div>
    <div class="card-body">
        <ul class="list-unstyled">
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Use clear, high-quality product images
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Set competitive pricing for better sales
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Featured products appear on homepage
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Keep stock quantities updated
            </li>
        </ul>
    </div>
</div>
@endsection
