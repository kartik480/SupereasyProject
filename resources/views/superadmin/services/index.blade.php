@extends('layouts.superadmin')

@section('title', 'Services Management - SuperAdmin')

@section('page-icon')
<i class="fas fa-concierge-bell"></i>
@endsection
@section('page-title', 'Services Management')
@section('page-subtitle', 'Manage your service offerings and bookings')

@section('header-actions')
<a href="{{ route('superadmin.services.create') }}" class="action-btn">
    <i class="fas fa-plus"></i>Add Service
</a>
@endsection

@section('content')
<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon danger">
                <i class="fas fa-concierge-bell"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $services->total() }}</div>
        <div class="stat-card-label">Total Services</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+15% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $services->where('is_active', true)->count() }}</div>
        <div class="stat-card-label">Active Services</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+10% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $services->where('is_featured', true)->count() }}</div>
        <div class="stat-card-label">Featured Services</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+7% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-tags"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $services->pluck('main_category')->unique()->count() }}</div>
        <div class="stat-card-label">Categories</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+3 new categories
        </div>
    </div>
</div>

<!-- Services Table -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-list"></i>All Services
        </h5>
    </div>
    <div class="card-body p-0">
        @if($services->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Service Details</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>
                                @if($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" 
                                         alt="{{ $service->name }}" 
                                         class="product-image">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-concierge-bell text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $service->name }}</div>
                                <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                <br><small class="text-info">ID: {{ $service->id }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $service->main_category ?? 'N/A')) }}</span>
                                @if($service->subcategory)
                                    <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $service->subcategory)) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">₹{{ number_format($service->price, 2) }}</div>
                                @if($service->discount_price && $service->discount_price < $service->price)
                                    <small class="text-success">₹{{ number_format($service->discount_price, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $service->duration ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if($service->is_active)
                                        <span class="status-badge active">Active</span>
                                    @else
                                        <span class="status-badge inactive">Inactive</span>
                                    @endif
                                    @if($service->is_featured)
                                        <span class="status-badge active">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('superadmin.services.show', $service) }}" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.services.edit', $service) }}" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('superadmin.services.destroy', $service) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this service?')">
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center p-4">
                {{ $services->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-concierge-bell"></i>
                <h5>No Services Found</h5>
                <p>Start by adding your first service to your offerings.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('superadmin.services.create') }}" class="action-btn">
                        <i class="fas fa-plus"></i>Add Your First Service
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection