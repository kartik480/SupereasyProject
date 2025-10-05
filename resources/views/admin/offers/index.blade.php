@extends('layouts.admin')

@section('page-icon')
<i class="fas fa-percentage"></i>
@endsection

@section('page-title', 'Offers Management')

@section('page-subtitle', 'Manage promotional offers and discounts')

@section('header-actions')
<a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Add New Offer
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
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $totalOffers }}</h4>
                <small>Total Offers</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $activeOffers }}</h4>
                <small>Active Offers</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $currentOffers }}</h4>
                <small>Current Offers</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $upcomingOffers }}</h4>
                <small>Upcoming</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $expiredOffers }}</h4>
                <small>Expired</small>
            </div>
        </div>
    </div>
</div>

<!-- Offers Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Offers</h5>
    </div>
    <div class="card-body">
        @if($offers->count() > 0)
            <div class="table-responsive">
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
                                             class="rounded" 
                                             width="50" 
                                             height="50">
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
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.offers.show', $offer) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.offers.toggle-status', $offer) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $offer->is_active ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $offer->is_active ? 'warning' : 'success' }}" 
                                                    title="{{ $offer->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this offer?')">
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
            
            <div class="d-flex justify-content-center">
                {{ $offers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-percentage fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Offers Found</h4>
                    <p class="text-muted">Start by creating your first promotional offer.</p>
                </div>
                <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Offer
                </a>
            </div>
        @endif
    </div>
</div>
@endsection