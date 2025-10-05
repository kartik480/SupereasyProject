@extends('layouts.admin')

@section('title', 'Admin Dashboard - SuperDaily')

@section('page-icon')
<i class="fas fa-tachometer-alt"></i>
@endsection
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome to your admin dashboard')

@section('header-actions')
<a href="{{ route('admin.maids.create') }}" class="action-btn">
    <i class="fas fa-user-plus"></i>Add Maid
</a>
<a href="{{ route('admin.services.create') }}" class="action-btn btn-outline">
    <i class="fas fa-plus"></i>Add Service
</a>
@endsection

@section('content')
<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon primary">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['total_customers'] }}</div>
        <div class="stat-card-label">Total Customers</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+15% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon success">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['total_maids'] }}</div>
        <div class="stat-card-label">Total Maids</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+8% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['total_bookings'] }}</div>
        <div class="stat-card-label">Total Bookings</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+22% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-concierge-bell"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['active_services'] }}</div>
        <div class="stat-card-label">Active Services</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+12% from last month
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-bolt"></i>Quick Actions
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.maids.create') }}" class="action-btn w-100 text-center">
                    <i class="fas fa-user-plus"></i>Add Maid
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.services.create') }}" class="action-btn w-100 text-center">
                    <i class="fas fa-concierge-bell"></i>Add Service
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.products.create') }}" class="action-btn w-100 text-center">
                    <i class="fas fa-shopping-cart"></i>Add Product
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.bookings.index') }}" class="action-btn w-100 text-center">
                    <i class="fas fa-calendar-check"></i>Manage Bookings
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-calendar-alt"></i>Recent Bookings
        </h5>
        <a href="{{ route('admin.bookings.index') }}" class="action-btn btn-outline">
            <i class="fas fa-eye"></i>View All
        </a>
    </div>
    <div class="card-body p-0">
        @if($recentBookings->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $booking->customer_name ?? ($booking->user->name ?? 'N/A') }}</div>
                                <small class="text-muted">{{ $booking->customer_phone ?? ($booking->user->phone ?? 'N/A') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->service->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $booking->service->category ?? '' }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}</div>
                                <small class="text-muted">{{ $booking->booking_time ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="status-badge inactive">Pending</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="status-badge active">Confirmed</span>
                                @elseif($booking->status === 'completed')
                                    <span class="status-badge featured">Completed</span>
                                @else
                                    <span class="status-badge inactive">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">₹{{ number_format($booking->final_amount ?? $booking->amount ?? 0, 2) }}</div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-alt"></i>
                <h5>No Recent Bookings</h5>
                <p>Bookings will appear here once customers start making reservations.</p>
            </div>
        @endif
    </div>
</div>

<!-- Recent Customers -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-user-friends"></i>Recent Customers
        </h5>
    </div>
    <div class="card-body p-0">
        @if($recentCustomers->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Joined</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCustomers as $customer)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $customer->name }}</div>
                            </td>
                            <td>
                                <div>{{ $customer->email }}</div>
                            </td>
                            <td>
                                <div>{{ $customer->phone ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div>{{ $customer->created_at->format('M d, Y') }}</div>
                            </td>
                            <td>
                                @if($customer->is_active)
                                    <span class="status-badge active">Active</span>
                                @else
                                    <span class="status-badge inactive">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-user-friends"></i>
                <h5>No Recent Customers</h5>
                <p>Customer registrations will appear here.</p>
            </div>
        @endif
    </div>
</div>

<!-- System Overview -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie"></i>System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fw-bold text-primary fs-4">{{ $stats['total_services'] }}</div>
                            <small class="text-muted">Total Services</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fw-bold text-success fs-4">{{ $stats['total_products'] }}</div>
                            <small class="text-muted">Total Products</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fw-bold text-warning fs-4">{{ $stats['pending_bookings'] }}</div>
                            <small class="text-muted">Pending Bookings</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fw-bold text-info fs-4">{{ $stats['total_categories'] }}</div>
                            <small class="text-muted">Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-clock"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body">
                @if(count($recentActivities) > 0)
                    <div class="activity-list">
                        @foreach($recentActivities as $activity)
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon me-3">
                                @if(isset($activity['type']) && $activity['type'] === 'new_user')
                                    <i class="fas fa-user-plus text-success"></i>
                                @elseif(isset($activity['type']) && $activity['type'] === 'new_booking')
                                    <i class="fas fa-calendar-plus text-primary"></i>
                                @elseif(isset($activity['type']) && $activity['type'] === 'product_update')
                                    <i class="fas fa-box text-warning"></i>
                                @elseif(isset($activity['type']) && $activity['type'] === 'service_completed')
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-circle text-primary"></i>
                                @endif
                            </div>
                            <div class="activity-content">
                                <div class="fw-bold">{{ $activity['description'] ?? 'Activity' }}</div>
                                <small class="text-muted">{{ $activity['time'] ?? 'Recently' }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-history fa-2x mb-3"></i>
                        <p>No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection