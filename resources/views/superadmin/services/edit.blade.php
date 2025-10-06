@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-edit"></i>
@endsection

@section('page-title', 'Edit Service')

@section('page-subtitle', 'Update service information')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.services.show', $service) }}" class="btn btn-outline-info">
        <i class="fas fa-eye me-2"></i>View Details
    </a>
    <a href="{{ route('superadmin.services.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Services
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Service Information
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

                <form method="POST" action="{{ route('superadmin.services.update', $service) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $service->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="main_category" class="form-label">Main Category <span class="text-danger">*</span></label>
                            <select class="form-control @error('main_category') is-invalid @enderror" 
                                    id="main_category" name="main_category" required>
                                <option value="">Select Main Category</option>
                                <option value="one_time" {{ old('main_category', $service->main_category) == 'one_time' ? 'selected' : '' }}>One-time Services</option>
                                <option value="monthly_subscription" {{ old('main_category', $service->main_category) == 'monthly_subscription' ? 'selected' : '' }}>Monthly Subscription</option>
                            </select>
                            @error('main_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="subcategory" class="form-label">Subcategory <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subcategory') is-invalid @enderror" 
                                   id="subcategory" name="subcategory" value="{{ old('subcategory', $service->subcategory) }}" required>
                            @error('subcategory')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="booking_advance_hours" class="form-label">Booking Advance Notice (Hours) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('booking_advance_hours') is-invalid @enderror" 
                                   id="booking_advance_hours" name="booking_advance_hours" 
                                   value="{{ old('booking_advance_hours', $service->booking_advance_hours) }}" min="1" max="24" required>
                            @error('booking_advance_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $service->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="discount_price" class="form-label">Discount Price (₹)</label>
                            <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" 
                                   id="discount_price" name="discount_price" value="{{ old('discount_price', $service->discount_price) }}">
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="duration" class="form-label">Duration <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('duration') is-invalid @enderror" 
                                   id="duration" name="duration" value="{{ old('duration', $service->duration) }}" placeholder="e.g., 2 hours" required>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Main Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Leave empty to keep current image</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Service
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('superadmin.services.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>Update Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        @if($service->image)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Main Image</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $service->image) }}" 
                     alt="{{ $service->name }}" 
                     class="img-fluid rounded" 
                     style="max-height: 200px;">
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
