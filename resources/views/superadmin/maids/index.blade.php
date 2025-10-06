@extends('layouts.superadmin')

@section('title', 'Maids Management - SuperAdmin')

@section('page-icon')
<i class="fas fa-users"></i>
@endsection
@section('page-title', 'Maids Management')
@section('page-subtitle', 'Manage your maid workforce and availability')

@section('header-actions')
<a href="{{ route('superadmin.maids.create') }}" class="action-btn">
    <i class="fas fa-user-plus"></i>Add Maid
</a>
@endsection

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon danger">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['total'] }}</div>
        <div class="stat-card-label">Total Maids</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+8% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['active'] }}</div>
        <div class="stat-card-label">Active Maids</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+12% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['available'] }}</div>
        <div class="stat-card-label">Available Now</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+5% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['top_rated'] }}</div>
        <div class="stat-card-label">Top Rated</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+2 from last month
        </div>
    </div>
</div>

<!-- Maids Table -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-list"></i>All Maids
        </h5>
    </div>
    <div class="card-body p-0">
        @if($maids->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Maid Details</th>
                            <th>Contact</th>
                            <th>Specialization</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maids as $maid)
                        <tr>
                            <td>
                                <img src="{{ $maid->profile_image ? asset('storage/' . $maid->profile_image) : 'https://via.placeholder.com/50x50/6c757d/ffffff?text=M' }}" 
                                     alt="{{ $maid->name }}" 
                                     class="product-image">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $maid->name }}</div>
                                <small class="text-muted">{{ $maid->bio ?? 'No bio available' }}</small>
                                <br><small class="text-info">ID: {{ $maid->id }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $maid->phone ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $maid->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $maid->specialization ?? 'General' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">
                                    <i class="fas fa-star text-warning"></i>
                                    {{ number_format($maid->rating ?? 0, 1) }}
                                </div>
                                <small class="text-muted">{{ $maid->bookings_count ?? 0 }} bookings</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if($maid->is_available)
                                        <span class="status-badge active">Available</span>
                                    @else
                                        <span class="status-badge inactive">Busy</span>
                                    @endif
                                    @if($maid->is_active)
                                        <span class="status-badge active">Active</span>
                                    @else
                                        <span class="status-badge inactive">Inactive</span>
                                    @endif
                                    @if($maid->is_verified)
                                        <span class="status-badge active">Verified</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('superadmin.maids.show', $maid) }}" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.maids.edit', $maid) }}" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('superadmin.maids.destroy', $maid) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this maid?')">
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
                {{ $maids->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h5>No Maids Found</h5>
                <p>Start by adding your first maid to the workforce.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('superadmin.maids.create') }}" class="action-btn">
                        <i class="fas fa-user-plus"></i>Add Your First Maid
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
