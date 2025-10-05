@extends('layouts.app')

@section('title', 'User Dashboard - SuperDaily')

@section('content')
<div class="container py-5">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-user-circle text-primary me-2"></i>Welcome, {{ $user->name }}!
                    </h1>
                    <p class="text-muted mb-0">Manage your bookings and profile</p>
                </div>
                <div>
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $bookingStats['total'] }}</h4>
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
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $bookingStats['pending'] }}</h4>
                            <p class="mb-0">Pending</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
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
                            <h4 class="mb-0">{{ $bookingStats['completed'] }}</h4>
                            <p class="mb-0">Completed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
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
                            <h4 class="mb-0">{{ $bookingStats['confirmed'] }}</h4>
                            <p class="mb-0">Confirmed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-thumbs-up fa-2x"></i>
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
                                                @if($booking->service && $booking->service->image_url)
                                                    <img src="{{ $booking->service->image_url }}" 
                                                         alt="{{ $booking->service->name }}" 
                                                         class="rounded me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $booking->service->name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $booking->booking_reference }}</small>
                                                </div>
                                            </div>
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
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('user.bookings') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>View All Bookings
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>No Bookings Yet</h5>
                            <p class="text-muted">You haven't made any bookings yet. Start exploring our services!</p>
                            <a href="{{ route('services.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Browse Services
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Featured Services -->
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
                        <a href="{{ route('services.index') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Book a Service
                        </a>
                        <a href="{{ route('user.bookings') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>My Bookings
                        </a>
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('change-password') }}" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>Change Password
                        </a>
                    </div>
                </div>
            </div>

            <!-- Featured Services -->
            @if($featuredServices->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>Featured Services
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($featuredServices->take(3) as $service)
                    <div class="d-flex align-items-center mb-3">
                        @if($service->image_url)
                            <img src="{{ $service->image_url }}" 
                                 alt="{{ $service->name }}" 
                                 class="rounded me-3" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $service->name }}</h6>
                            <small class="text-muted">{{ $service->duration }}</small>
                            <div class="fw-bold text-primary">₹{{ number_format($service->discount_price ?? $service->price, 2) }}</div>
                        </div>
                        <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endforeach
                    <div class="text-center">
                        <a href="{{ route('services.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>View All Services
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
