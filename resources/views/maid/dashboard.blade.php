@extends('layouts.app')

@section('title', 'Maid Dashboard - SuperDaily')

@section('content')
<div class="container-fluid py-4">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-user-tie text-info me-2"></i>Maid Dashboard
                    </h1>
                    <p class="text-muted mb-0">Manage your bookings and track your work</p>
                </div>
                <div>
                    <a href="{{ route('maid.profile') }}" class="btn btn-outline-primary">
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
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['pending_bookings'] }}</h4>
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
                            <h4 class="mb-0">{{ $stats['completed_bookings'] }}</h4>
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
                            <h4 class="mb-0">{{ $stats['in_progress_bookings'] }}</h4>
                            <p class="mb-0">In Progress</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-play-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Work Hours & Earnings -->
    <div class="row mb-4">
        <div class="col-lg-6 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">This Month's Work</h5>
                    <div class="text-primary">
                        <h3>{{ $workHours }} Hours</h3>
                        <small>Total Hours Worked</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">This Month's Earnings</h5>
                    <div class="text-success">
                        <h3>₹{{ number_format($monthlyEarnings, 2) }}</h3>
                        <small>Total Earnings</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Schedule -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Today's Schedule
                    </h5>
                </div>
                <div class="card-body">
                    @if($todayBookings->count() > 0)
                        @foreach($todayBookings as $booking)
                        <div class="d-flex align-items-center mb-3 p-3 border rounded">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $booking->service->name ?? 'N/A' }}</div>
                                <div class="text-muted">{{ $booking->user->name ?? 'N/A' }}</div>
                                <div class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $booking->booking_time }}
                                </div>
                            </div>
                            <div class="text-end">
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
                                <div class="fw-bold mt-1">₹{{ number_format($booking->final_amount, 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>No Bookings Today</h5>
                            <p class="text-muted">You have no bookings scheduled for today.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recent Bookings
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentBookings->count() > 0)
                        @foreach($recentBookings->take(5) as $booking)
                        <div class="d-flex align-items-center mb-3 p-2 border-bottom">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $booking->service->name ?? 'N/A' }}</div>
                                <div class="text-muted">{{ $booking->user->name ?? 'N/A' }}</div>
                                <div class="text-muted small">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }} at {{ $booking->booking_time }}
                                </div>
                            </div>
                            <div class="text-end">
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
                                <div class="fw-bold mt-1">₹{{ number_format($booking->final_amount, 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('maid.bookings') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-1"></i>View All Bookings
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>No Bookings Yet</h5>
                            <p class="text-muted">You haven't been assigned any bookings yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('maid.bookings') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-2"></i>My Bookings
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('maid.schedule') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar-alt me-2"></i>My Schedule
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('maid.earnings') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-chart-line me-2"></i>My Earnings
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="{{ route('maid.profile') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection