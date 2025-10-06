@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-user-tie"></i>
@endsection

@section('page-title', 'Maid Reports')

@section('page-subtitle', 'Maid performance analytics and insights')

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
<!-- Filter Options -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Filter Reports
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.reports.maids') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="busy" {{ request('status') == 'busy' ? 'selected' : '' }}>Busy</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="verified" class="form-label">Verification</label>
                <select class="form-control" id="verified" name="verified">
                    <option value="">All</option>
                    <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="rating" class="form-label">Minimum Rating</label>
                <select class="form-control" id="rating" name="rating">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
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
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $totalMaids ?? 0 }}</div>
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
        <div class="stat-card-value">{{ $activeMaids ?? 0 }}</div>
        <div class="stat-card-label">Active Maids</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+12% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $verifiedMaids ?? 0 }}</div>
        <div class="stat-card-label">Verified</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+5% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ number_format($averageRating ?? 0, 1) }}</div>
        <div class="stat-card-label">Avg Rating</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+0.2 from last month
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Maid Status Distribution
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
                    <i class="fas fa-chart-bar me-2"></i>Rating Distribution
                </h5>
            </div>
            <div class="card-body">
                <canvas id="ratingChart" width="400" height="200"></canvas>
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
                    <i class="fas fa-list me-2"></i>Maid Performance Report
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Maid</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Rating</th>
                                <th>Bookings</th>
                                <th>Earnings</th>
                                <th>Join Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($maids ?? [] as $maid)
                                <tr>
                                    <td>
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
                                                <small class="text-muted">ID: {{ $maid->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><i class="fas fa-phone me-1"></i>{{ $maid->phone }}</div>
                                            <div><i class="fas fa-envelope me-1"></i>{{ $maid->email }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if($maid->is_active)
                                                <span class="status-badge active">Active</span>
                                            @else
                                                <span class="status-badge inactive">Inactive</span>
                                            @endif
                                            
                                            @if($maid->is_available)
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-warning">Busy</span>
                                            @endif
                                            
                                            @if($maid->is_verified)
                                                <span class="badge bg-info">Verified</span>
                                            @else
                                                <span class="badge bg-secondary">Unverified</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rating-stars me-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $maid->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="fw-bold">{{ number_format($maid->rating, 1) }}</span>
                                        </div>
                                        <small class="text-muted">{{ $maid->reviews_count ?? 0 }} reviews</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-danger">{{ $maid->bookings_count ?? 0 }}</div>
                                        <small class="text-muted">total bookings</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">₹{{ number_format($maid->total_earnings ?? 0) }}</div>
                                        <small class="text-muted">this month</small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>{{ $maid->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $maid->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                            <h5>No Maids Found</h5>
                                            <p class="text-muted">No maids match your current filter criteria.</p>
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
        <!-- Top Performers -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>Top Performers
                </h5>
            </div>
            <div class="card-body">
                @forelse($topPerformers ?? [] as $maid)
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
                                <small class="text-muted">{{ $maid->bookings_count }} bookings</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $maid->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">{{ number_format($maid->rating, 1) }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i class="fas fa-trophy fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No performance data available</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Reviews -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-comments me-2"></i>Recent Reviews
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentReviews ?? [] as $review)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="fw-bold">{{ $review->maid->name ?? 'Unknown Maid' }}</div>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="small text-muted mb-1">{{ Str::limit($review->comment, 100) }}</p>
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>{{ $review->user->name ?? 'Anonymous' }}
                            <i class="fas fa-calendar ms-2 me-1"></i>{{ $review->created_at->format('M d') }}
                        </small>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i class="fas fa-comments fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No recent reviews</p>
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
            labels: ['Active', 'Inactive', 'Available', 'Busy'],
            datasets: [{
                data: [{{ $activeMaids ?? 0 }}, {{ $inactiveMaids ?? 0 }}, {{ $availableMaids ?? 0 }}, {{ $busyMaids ?? 0 }}],
                backgroundColor: [
                    '#28a745',
                    '#6c757d',
                    '#17a2b8',
                    '#ffc107'
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

    // Rating Distribution Chart
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Maids',
                data: [{{ $ratingDistribution['1'] ?? 0 }}, {{ $ratingDistribution['2'] ?? 0 }}, {{ $ratingDistribution['3'] ?? 0 }}, {{ $ratingDistribution['4'] ?? 0 }}, {{ $ratingDistribution['5'] ?? 0 }}],
                backgroundColor: '#dc3545',
                borderColor: '#dc3545',
                borderWidth: 1
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
