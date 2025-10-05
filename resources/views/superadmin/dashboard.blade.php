@extends('layouts.app')

@section('title', 'SuperAdmin Dashboard - SuperDaily')

@section('content')
<div class="container-fluid py-4">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-crown text-danger me-2"></i>SuperAdmin Dashboard
                    </h1>
                    <p class="text-muted mb-0">Complete system control and management</p>
                </div>
                <div>
                    <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Create User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                            <p class="mb-0">Total Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_bookings'] }}</h4>
                            <p class="mb-0">Total Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_services'] }}</h4>
                            <p class="mb-0">Total Services</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-concierge-bell fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_products'] }}</h4>
                            <p class="mb-0">Total Products</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">User Breakdown</h5>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-primary">
                                <h4>{{ $stats['total_customers'] }}</h4>
                                <small>Customers</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-info">
                                <h4>{{ $stats['total_maids'] }}</h4>
                                <small>Maids</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="text-success">
                                <h4>{{ $stats['total_admins'] }}</h4>
                                <small>Admins</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-danger">
                                <h4>1</h4>
                                <small>SuperAdmins</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Booking Status</h5>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-warning">
                                <h4>{{ $stats['pending_bookings'] }}</h4>
                                <small>Pending</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-success">
                                <h4>{{ $stats['total_bookings'] - $stats['pending_bookings'] }}</h4>
                                <small>Processed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Revenue</h5>
                    <div class="text-success">
                        <h4>₹{{ number_format($revenueStats['total_revenue'], 2) }}</h4>
                        <small>Total Revenue</small>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="text-info">
                                <h5>₹{{ number_format($revenueStats['monthly_revenue'], 2) }}</h5>
                                <small>This Month</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-primary">
                                <h5>₹{{ number_format($revenueStats['daily_revenue'], 2) }}</h5>
                                <small>Today</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recent Bookings
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentBookings->count() > 0)
                        <div class="table-responsive">
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
                                            <div class="d-flex align-items-center">
                                                @if($booking->user && $booking->user->profile_image)
                                                    <img src="{{ $booking->user->profile_image_url }}" 
                                                         alt="{{ $booking->user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $booking->user->name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $booking->user->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $booking->service->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $booking->booking_reference }}</small>
                                        </td>
                                        <td>
                                            <div>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $booking->booking_time }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'in_progress' => 'info',
                                                    'completed' => 'secondary',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusColor = $statusColors[$booking->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($booking->status) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">₹{{ number_format($booking->final_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>No Bookings Yet</h5>
                            <p class="text-muted">No bookings have been made yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Users -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Create User
                        </a>
                        <a href="{{ route('superadmin.users') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-concierge-bell me-2"></i>Manage Services
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-box me-2"></i>Manage Products
                        </a>
                        <a href="{{ route('superadmin.settings') }}" class="btn btn-outline-warning">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i>Recent Users
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentUsers->count() > 0)
                        @foreach($recentUsers->take(5) as $user)
                        <div class="d-flex align-items-center mb-3">
                            @if($user->profile_image)
                                <img src="{{ $user->profile_image_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle me-3" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->name }}</div>
                                <small class="text-muted">{{ ucfirst($user->role) }}</small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center">
                            <a href="{{ route('superadmin.users') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-list me-1"></i>View All Users
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No users yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection