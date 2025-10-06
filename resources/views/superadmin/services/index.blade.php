@extends('layouts.superadmin')

@section('title', 'Services - SuperAdmin')

@section('page-icon')
<i class="fas fa-concierge-bell"></i>
@endsection
@section('page-title', 'Services')
@section('page-subtitle', 'Manage your service offerings')

@section('header-actions')
<a href="{{ route('superadmin.services.create') }}" class="action-btn">
    <i class="fas fa-plus"></i>Add Service
</a>
@endsection

@section('content')
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-concierge-bell"></i>All Services
        </h5>
    </div>
    <div class="card-body p-0">
        @if($services->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
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
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
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
                            </td>
                            <td>
                                @if($service->category)
                                    <span class="badge bg-info">{{ $service->category }}</span>
                                @else
                                    <span class="text-muted">No category</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">₹{{ number_format($service->price, 2) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $service->duration ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($service->is_active)
                                    <span class="status-badge active">Active</span>
                                @else
                                    <span class="status-badge inactive">Inactive</span>
                                @endif
                                @if($service->is_featured)
                                    <span class="status-badge featured">Featured</span>
                                @endif
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
                                          method="POST" style="display: inline;" 
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
        @else
            <div class="empty-state">
                <i class="fas fa-concierge-bell"></i>
                <h5>No Services Found</h5>
                <p>Add your first service to start offering bookings.</p>
                <a href="{{ route('superadmin.services.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>Add Service
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
