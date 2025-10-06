@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-edit"></i>
@endsection

@section('page-title', 'Edit Category')

@section('page-subtitle', 'Update category information')

@section('header-actions')
<div class="d-flex gap-2">
    <a href="{{ route('superadmin.categories.show', $category) }}" class="btn btn-outline-info">
        <i class="fas fa-eye me-2"></i>View Category
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
                <h5 class="card-title mb-0">Edit Category Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('superadmin.categories.update', $category) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="icon" class="form-label">Icon Class</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                   id="icon" name="icon" value="{{ old('icon', $category->icon) }}" 
                                   placeholder="e.g., fas fa-shopping-basket">
                            <div class="form-text">FontAwesome icon class</div>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                   id="color" name="color" value="{{ old('color', $category->color ?: '#dc3545') }}">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" min="0" 
                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" name="sort_order" 
                                   value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Leave empty to keep current image</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Category
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>Update Category
                        </button>
                        <a href="{{ route('superadmin.categories.show', $category) }}" class="btn btn-outline-info">
                            <i class="fas fa-eye me-2"></i>View Category
                        </a>
                        <a href="{{ route('superadmin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Current Image -->
        @if($category->image)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Image</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $category->image) }}" 
                     alt="{{ $category->name }}" 
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
                <div class="category-preview">
                    <div class="preview-image text-center mb-3">
                        <img id="preview-img" 
                             src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/300x200/6c757d/ffffff?text=Category+Image' }}" 
                             alt="Preview" class="img-fluid rounded">
                    </div>
                    <div class="preview-content">
                        <div class="d-flex align-items-center mb-2">
                            <i id="preview-icon" class="{{ $category->icon ?: 'fas fa-tag' }} me-2" 
                               style="color: {{ $category->color ?: '#dc3545' }};"></i>
                            <h6 id="preview-name" class="mb-0">{{ $category->name }}</h6>
                        </div>
                        <p id="preview-description" class="text-muted small mb-2">{{ Str::limit($category->description, 100) }}</p>
                        <div class="preview-meta">
                            <small class="text-muted">
                                <strong>Slug:</strong> <code id="preview-slug">{{ $category->slug }}</code>
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

    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        if (!slugInput.value) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });

    // Live preview updates
    const nameInputPreview = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const iconInput = document.getElementById('icon');
    const colorInput = document.getElementById('color');
    const slugInputPreview = document.getElementById('slug');

    function updatePreview() {
        // Update name
        document.getElementById('preview-name').textContent = nameInputPreview.value || 'Category Name';
        
        // Update description
        document.getElementById('preview-description').textContent = descriptionInput.value || 'Category description will appear here...';
        
        // Update slug
        document.getElementById('preview-slug').textContent = slugInputPreview.value || 'category-slug';
        
        // Update icon
        const iconClass = iconInput.value || 'fas fa-tag';
        const iconElement = document.getElementById('preview-icon');
        iconElement.className = iconClass + ' me-2';
        
        // Update color
        const color = colorInput.value || '#dc3545';
        iconElement.style.color = color;
    }

    // Add event listeners for live preview
    [nameInputPreview, descriptionInput, iconInput, colorInput, slugInputPreview].forEach(input => {
        input.addEventListener('input', updatePreview);
    });
});
</script>
@endsection
