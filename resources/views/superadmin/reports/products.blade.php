@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-box"></i>
@endsection

@section('page-title', 'Product Reports')

@section('page-subtitle', 'Product sales analytics and inventory insights')

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
        <form method="GET" action="{{ route('superadmin.reports.products') }}" class="row g-3">
            <div class="col-md-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="price_range" class="form-label">Price Range</label>
                <select class="form-control" id="price_range" name="price_range">
                    <option value="">All Prices</option>
                    <option value="0-100" {{ request('price_range') == '0-100' ? 'selected' : '' }}>₹0 - ₹100</option>
                    <option value="100-500" {{ request('price_range') == '100-500' ? 'selected' : '' }}>₹100 - ₹500</option>
                    <option value="500-1000" {{ request('price_range') == '500-1000' ? 'selected' : '' }}>₹500 - ₹1000</option>
                    <option value="1000+" {{ request('price_range') == '1000+' ? 'selected' : '' }}>₹1000+</option>
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
                <i class="fas fa-box"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $totalProducts ?? 0 }}</div>
        <div class="stat-card-label">Total Products</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+15% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $activeProducts ?? 0 }}</div>
        <div class="stat-card-label">Active Products</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+8% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $lowStockProducts ?? 0 }}</div>
        <div class="stat-card-label">Low Stock</div>
        <div class="stat-card-change negative">
            <i class="fas fa-arrow-down me-1"></i>Need restocking
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
        <div class="stat-card-value">₹{{ number_format($totalInventoryValue ?? 0) }}</div>
        <div class="stat-card-label">Inventory Value</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+12% from last month
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Category Distribution
                </h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Top Selling Products
                </h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" width="400" height="200"></canvas>
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
                    <i class="fas fa-list me-2"></i>Product Inventory Report
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Sales</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products ?? [] as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="product-image me-2">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                                         alt="{{ $product->name }}" class="rounded" width="40" height="40">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $product->name }}</div>
                                                <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-danger">₹{{ number_format($product->price, 0) }}</div>
                                        @if($product->discount_price)
                                            <small class="text-muted">Disc: ₹{{ number_format($product->discount_price, 0) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $product->stock_quantity }}</div>
                                        <small class="text-muted">{{ $product->unit }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if($product->is_active)
                                                <span class="status-badge active">Active</span>
                                            @else
                                                <span class="status-badge inactive">Inactive</span>
                                            @endif
                                            
                                            @if($product->stock_quantity <= 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($product->stock_quantity <= 10)
                                                <span class="badge bg-warning">Low Stock</span>
                                            @else
                                                <span class="badge bg-success">In Stock</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-danger">{{ $product->sales_count ?? 0 }}</div>
                                        <small class="text-muted">units sold</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">₹{{ number_format($product->revenue ?? 0) }}</div>
                                        <small class="text-muted">total revenue</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <h5>No Products Found</h5>
                                            <p class="text-muted">No products match your current filter criteria.</p>
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
        <!-- Top Categories -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tags me-2"></i>Top Categories
                </h5>
            </div>
            <div class="card-body">
                @forelse($topCategories ?? [] as $category)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-bold">{{ $category->name }}</div>
                            <small class="text-muted">{{ $category->products_count }} products</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-danger">₹{{ number_format($category->total_revenue ?? 0) }}</div>
                            <small class="text-muted">revenue</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i class="fas fa-tags fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No category data available</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Low Stock Alert -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                </h5>
            </div>
            <div class="card-body">
                @forelse($lowStockProducts ?? [] as $product)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-bold">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-warning">{{ $product->stock_quantity }}</div>
                            <small class="text-muted">left</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p class="text-muted mb-0">All products are well stocked</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Additions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Recent Additions
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentProducts ?? [] as $product)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="product-image me-2">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" class="rounded" width="32" height="32">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px;">
                                        <i class="fas fa-box text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="fw-bold">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-danger">₹{{ number_format($product->price, 0) }}</div>
                            <small class="text-muted">{{ $product->stock_quantity }} in stock</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i class="fas fa-plus-circle fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No recent products</p>
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
    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($categoryData ?? []) !!},
                backgroundColor: [
                    '#dc3545',
                    '#28a745',
                    '#007bff',
                    '#ffc107',
                    '#17a2b8',
                    '#6c757d',
                    '#fd7e14',
                    '#e83e8c'
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

    // Top Selling Products Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProductsLabels ?? []) !!},
            datasets: [{
                label: 'Units Sold',
                data: {!! json_encode($topProductsData ?? []) !!},
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
