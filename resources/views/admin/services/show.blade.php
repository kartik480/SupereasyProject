@extends('layouts.admin')

@section('title', 'Service Details - Admin')

@section('page-icon')
<i class="fas fa-eye"></i>
@endsection
@section('page-title', 'Service Details')
@section('page-subtitle', 'View service information and statistics')

@section('header-actions')
<a href="{{ route('admin.services.index') }}" class="action-btn btn-outline">
    <i class="fas fa-arrow-left"></i>Back to Services
</a>
@endsection

@section('content')
<!-- Service Overview -->
<div class="row">
    <div class="col-lg-8">
        <!-- Service Information Card -->
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-concierge-bell"></i>Service Information
                </h5>
                <div class="card-actions">
                    @if($service->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                    @if($service->is_featured)
                        <span class="badge bg-warning">Featured</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Service Name</label>
                        <p class="mb-0">{{ $service->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <p class="mb-0">{{ $service->category }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Main Category</label>
                        <p class="mb-0">{{ $service->main_category_name }}</p>
                    </div>
                    @if($service->subcategory)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Subcategory</label>
                        <p class="mb-0">{{ $service->subcategory }}</p>
                    </div>
                    @endif
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Price</label>
                        <p class="mb-0 text-success fw-bold fs-5">₹{{ number_format($service->price, 2) }}</p>
                    </div>
                    @if($service->discount_price)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Discount Price</label>
                        <p class="mb-0 text-primary fw-bold fs-5">₹{{ number_format($service->discount_price, 2) }}</p>
                    </div>
                    @endif
                    @if($service->duration)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Duration</label>
                        <p class="mb-0">{{ $service->duration }} {{ $service->unit ?? 'hours' }}</p>
                    </div>
                    @endif
                    @if($service->booking_advance_hours)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Booking Advance Notice</label>
                        <p class="mb-0">{{ $service->booking_advance_notice }}</p>
                    </div>
                    @endif
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <p class="mb-0">{{ $service->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Images -->
        @if($service->image || $service->image_2 || $service->image_3 || $service->image_4)
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-images"></i>Service Images
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($service->image)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Primary Image</label>
                        <div class="service-image-container">
                            <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="img-fluid rounded">
                        </div>
                    </div>
                    @endif
                    @if($service->image_2)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Secondary Image</label>
                        <div class="service-image-container">
                            <img src="{{ $service->image2_url }}" alt="{{ $service->name }}" class="img-fluid rounded">
                        </div>
                    </div>
                    @endif
                    @if($service->image_3)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Third Image</label>
                        <div class="service-image-container">
                            <img src="{{ $service->image3_url }}" alt="{{ $service->name }}" class="img-fluid rounded">
                        </div>
                    </div>
                    @endif
                    @if($service->image_4)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fourth Image</label>
                        <div class="service-image-container">
                            <img src="{{ $service->image4_url }}" alt="{{ $service->name }}" class="img-fluid rounded">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Service Features -->
        @if($service->features_array && count($service->features_array) > 0)
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-star"></i>Service Features
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    @foreach($service->features_array as $feature)
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>{{ $feature }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Service Requirements -->
        @if($service->requirements_array && count($service->requirements_array) > 0)
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-list-check"></i>Service Requirements
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    @foreach($service->requirements_array as $requirement)
                    <li class="mb-2">
                        <i class="fas fa-arrow-right text-primary me-2"></i>{{ $requirement }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Booking Requirements -->
        @if($service->booking_requirements)
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-calendar-check"></i>Booking Requirements
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $service->booking_requirements }}</p>
            </div>
        </div>
        @endif

        <!-- Subscription Plans (if applicable) -->
        @if($service->is_monthly_subscription && $service->subscription_plans_array && count($service->subscription_plans_array) > 0)
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-calendar-alt"></i>Subscription Plans
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($service->subscription_plans_array as $plan)
                    <div class="col-md-6 mb-3">
                        <div class="subscription-plan-card">
                            <h6 class="plan-name">{{ $plan['name'] ?? 'Plan' }}</h6>
                            <p class="plan-price">₹{{ number_format($plan['price'] ?? 0, 2) }}</p>
                            @if(isset($plan['description']))
                            <p class="plan-description">{{ $plan['description'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Service Statistics -->
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar"></i>Service Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="stat-item">
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-value">{{ $service->bookings->count() }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Active Bookings</div>
                    <div class="stat-value">{{ $service->bookings->whereIn('status', ['pending', 'confirmed', 'in_progress'])->count() }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Completed Bookings</div>
                    <div class="stat-value">{{ $service->bookings->where('status', 'completed')->count() }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Cancelled Bookings</div>
                    <div class="stat-value">{{ $service->bookings->where('status', 'cancelled')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Service Actions -->
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-cogs"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list me-2"></i>All Services
                    </a>
                    <a href="{{ route('admin.bookings.index', ['service' => $service->id]) }}" class="btn btn-info">
                        <i class="fas fa-calendar me-2"></i>View Bookings
                    </a>
                </div>
            </div>
        </div>

        <!-- Service Meta Information -->
        <div class="content-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-info-circle"></i>Meta Information
                </h5>
            </div>
            <div class="card-body">
                <div class="meta-item">
                    <label class="meta-label">Created</label>
                    <span class="meta-value">{{ $service->created_at->format('M d, Y \a\t g:i A') }}</span>
                </div>
                <div class="meta-item">
                    <label class="meta-label">Last Updated</label>
                    <span class="meta-value">{{ $service->updated_at->format('M d, Y \a\t g:i A') }}</span>
                </div>
                <div class="meta-item">
                    <label class="meta-label">Service ID</label>
                    <span class="meta-value">#{{ str_pad($service->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.service-image-container {
    max-height: 200px;
    overflow: hidden;
    border-radius: 8px;
}

.service-image-container img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-weight: 500;
    color: #666;
}

.stat-value {
    font-weight: 600;
    font-size: 1.1em;
    color: #333;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.meta-item:last-child {
    border-bottom: none;
}

.meta-label {
    font-weight: 500;
    color: #666;
    font-size: 0.9em;
}

.meta-value {
    font-weight: 500;
    color: #333;
    font-size: 0.9em;
}

.subscription-plan-card {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.plan-name {
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.plan-price {
    font-size: 1.2em;
    font-weight: 600;
    color: #28a745;
    margin-bottom: 5px;
}

.plan-description {
    font-size: 0.9em;
    color: #666;
    margin-bottom: 0;
}
</style>
@endsection
