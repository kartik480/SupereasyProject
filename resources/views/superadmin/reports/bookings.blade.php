@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-chart-line"></i>
@endsection

@section('page-title', 'Booking Reports')

@section('page-subtitle', 'Comprehensive booking analytics and insights')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.dashboard') }}" class="action-btn btn-outline">
        <i class="fas fa-arrow-left"></i>Back to Dashboard
    </a>
    <button class="action-btn btn-outline" onclick="exportReport()">
        <i class="fas fa-download"></i>Export Report
    </button>
</div>
@endsection

@section('content')
<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Filter Reports
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.bookings') }}" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                       value="{{ request('end_date', now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="action-btn w-100">
                    <i class="fas fa-search"></i>Filter Reports
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Statistics -->
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon danger">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $totalBookings ?? 0 }}</div>
        <div class="stat-card-label">Total Bookings</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+12% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $completedBookings ?? 0 }}</div>
        <div class="stat-card-label">Completed</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+8% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $pendingBookings ?? 0 }}</div>
        <div class="stat-card-label">Pending</div>
        <div class="stat-card-change neutral">
            <i class="fas fa-minus me-1"></i>No change
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
        <div class="stat-card-value">₹{{ number_format($totalRevenue ?? 0) }}</div>
        <div class="stat-card-label">Total Revenue</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+15% from last month
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Booking Status Distribution
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Daily Bookings Trend
                </h5>
            </div>
            <div class="card-body">
                <canvas id="trendChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Recent Bookings
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Maid</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings ?? [] as $booking)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-danger">#{{ $booking->booking_reference }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $booking->user->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $booking->user->phone ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $booking->service->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $booking->service->category->name ?? '' }}</small>
                                    </td>
                                    <td>
                                        @if($booking->maid)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    @if($booking->maid->profile_image)
                                                        <img src="{{ asset('storage/' . $booking->maid->profile_image) }}" 
                                                             alt="{{ $booking->maid->name }}" class="rounded-circle" width="32" height="32">
                                                    @else
                                                        <i class="fas fa-user text-muted"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $booking->maid->name }}</div>
                                                    <small class="text-muted">{{ $booking->maid->phone }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><strong>Date:</strong> {{ $booking->booking_date->format('M d, Y') }}</div>
                                            <div><strong>Time:</strong> {{ $booking->booking_time }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">₹{{ number_format($booking->total_amount, 0) }}</div>
                                        @if($booking->discount_amount > 0)
                                            <small class="text-muted">Disc: ₹{{ number_format($booking->discount_amount, 0) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'pending' => ['class' => 'warning', 'icon' => 'fas fa-clock'],
                                                'confirmed' => ['class' => 'info', 'icon' => 'fas fa-check'],
                                                'in_progress' => ['class' => 'primary', 'icon' => 'fas fa-play'],
                                                'completed' => ['class' => 'success', 'icon' => 'fas fa-check-circle'],
                                                'cancelled' => ['class' => 'danger', 'icon' => 'fas fa-times'],
                                            ];
                                            $config = $statusConfig[$booking->status] ?? ['class' => 'secondary', 'icon' => 'fas fa-question'];
                                        @endphp
                                        <span class="status-badge {{ $config['class'] }}">
                                            <i class="{{ $config['icon'] }} me-1"></i>{{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <h5>No Bookings Found</h5>
                                            <p class="text-muted">No bookings match your current filter criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Top Services -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>Top Services
                </h5>
            </div>
            <div class="card-body">
                @forelse($topServices ?? [] as $service)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-bold">{{ $service->name }}</div>
                            <small class="text-muted">{{ $service->category->name ?? 'Uncategorized' }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-danger">{{ $service->bookings_count }}</div>
                            <small class="text-muted">bookings</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No service data available</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Top Maids -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tie me-2"></i>Top Performing Maids
                </h5>
            </div>
            <div class="card-body">
                @forelse($topMaids ?? [] as $maid)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                @if($maid->profile_image)
                                    <img src="{{ asset('storage/' . $maid->profile_image) }}" 
                                         alt="{{ $maid->name }}" class="rounded-circle" width="32" height="32">
                                @else
                                    <i class="fas fa-user text-muted"></i>
                                @endif
                            </div>
                            <div>
                                <div class="fw-bold">{{ $maid->name }}</div>
                                <small class="text-muted">{{ $maid->phone }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-danger">{{ $maid->bookings_count }}</div>
                            <small class="text-muted">bookings</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i class="fas fa-user-tie fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No maid data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending', 'In Progress', 'Cancelled'],
            datasets: [{
                data: [{{ $completedBookings ?? 0 }}, {{ $pendingBookings ?? 0 }}, {{ $inProgressBookings ?? 0 }}, {{ $cancelledBookings ?? 0 }}],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#007bff',
                    '#dc3545'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Daily Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels ?? []) !!},
            datasets: [{
                label: 'Daily Bookings',
                data: {!! json_encode($trendData ?? []) !!},
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});

function exportReport() {
    // Add export functionality here
    alert('Export functionality will be implemented soon!');
}
</script>
@endsection
