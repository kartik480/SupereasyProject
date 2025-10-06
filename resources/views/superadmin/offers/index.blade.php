@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-percentage"></i>
@endsection

@section('page-title', 'Offers Management')
@section('page-subtitle', 'Manage promotional offers and discounts')

@section('header-actions')
<a href="{{ route('superadmin.offers.create') }}" class="action-btn">
    <i class="fas fa-plus"></i>Add New Offer
</a>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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
                <i class="fas fa-percentage"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $totalOffers }}</div>
        <div class="stat-card-label">Total Offers</div>
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
        <div class="stat-card-value">{{ $activeOffers }}</div>
        <div class="stat-card-label">Active Offers</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+8% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $currentOffers }}</div>
        <div class="stat-card-label">Current Offers</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+15% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-calendar-plus"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $upcomingOffers }}</div>
        <div class="stat-card-label">Upcoming</div>
        <div class="stat-card-change neutral">
            <i class="fas fa-minus me-1"></i>Scheduled
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon danger">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $expiredOffers }}</div>
        <div class="stat-card-label">Expired</div>
        <div class="stat-card-change negative">
            <i class="fas fa-arrow-down me-1"></i>Needs cleanup
        </div>
    </div>
</div>

<!-- Offers Table -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-list"></i>All Offers
        </h5>
    </div>
    <div class="card-body p-0">
        @if($offers->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Discount</th>
                            <th>Period</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $offer)
                            <tr>
                                <td>
                                    @if($offer->image)
                                        <img src="{{ asset('storage/' . $offer->image) }}" 
                                             alt="{{ $offer->title }}" 
                                             class="product-image">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-percentage text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $offer->title }}</div>
                                    @if($offer->code)
                                        <small class="text-muted">Code: {{ $offer->code }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-success">
                                        @if($offer->discount_type === 'percentage')
                                            {{ $offer->discount_value }}%
                                        @else
                                            ₹{{ number_format($offer->discount_value, 0) }}
                                        @endif
                                    </div>
                                    @if($offer->min_order_amount)
                                        <small class="text-muted">Min: ₹{{ number_format($offer->min_order_amount, 0) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        <div><strong>Start:</strong> {{ $offer->start_date->format('M d, Y') }}</div>
                                        <div><strong>End:</strong> {{ $offer->end_date->format('M d, Y') }}</div>
                                    </div>
                                    @php
                                        $now = now();
                                        $startDate = $offer->start_date;
                                        $endDate = $offer->end_date;
                                        
                                        if ($now < $startDate) {
                                            $status = 'upcoming';
                                            $badgeClass = 'bg-warning';
                                        } elseif ($now > $endDate) {
                                            $status = 'expired';
                                            $badgeClass = 'bg-danger';
                                        } else {
                                            $status = 'active';
                                            $badgeClass = 'bg-success';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} mt-1">{{ ucfirst($status) }}</span>
                                </td>
                                <td>
                                    @if($offer->usage_limit)
                                        <div class="small">
                                            <div>Limit: {{ $offer->usage_limit }}</div>
                                            <div>Used: {{ $offer->usage_count ?? 0 }}</div>
                                        </div>
                                    @else
                                        <span class="badge bg-info">Unlimited</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($offer->is_active)
                                            <span class="status-badge active">Active</span>
                                        @else
                                            <span class="status-badge inactive">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('superadmin.offers.show', $offer) }}" 
                                           class="btn-action btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('superadmin.offers.edit', $offer) }}" 
                                           class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('superadmin.offers.toggle-status', $offer) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $offer->is_active ? 0 : 1 }}">
                                            <button type="submit" class="btn-action btn-{{ $offer->is_active ? 'warning' : 'success' }}" 
                                                    title="{{ $offer->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('superadmin.offers.destroy', $offer) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" 
                                                    onclick="return confirm('Are you sure you want to delete this offer?')" title="Delete">
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
                {{ $offers->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-percentage"></i>
                <h5>No Offers Found</h5>
                <p>Start by creating your first promotional offer.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('superadmin.offers.create') }}" class="action-btn">
                        <i class="fas fa-plus"></i>Create First Offer
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
