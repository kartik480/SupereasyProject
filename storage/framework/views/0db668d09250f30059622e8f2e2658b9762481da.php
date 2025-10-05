

<?php $__env->startSection('title', 'Admin Dashboard - SuperDaily'); ?>

<?php $__env->startSection('page-icon'); ?>
<i class="fas fa-tachometer-alt"></i>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Welcome to your admin dashboard'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.maids.create')); ?>" class="action-btn">
    <i class="fas fa-user-plus"></i>Add Maid
</a>
<a href="<?php echo e(route('admin.services.create')); ?>" class="action-btn btn-outline">
    <i class="fas fa-plus"></i>Add Service
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon primary">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($stats['total_customers']); ?></div>
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
        <div class="stat-card-value"><?php echo e($stats['total_maids']); ?></div>
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
        <div class="stat-card-value"><?php echo e($stats['total_bookings']); ?></div>
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
        <div class="stat-card-value"><?php echo e($stats['active_services']); ?></div>
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
                <a href="<?php echo e(route('admin.maids.create')); ?>" class="action-btn w-100 text-center">
                    <i class="fas fa-user-plus"></i>Add Maid
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="<?php echo e(route('admin.services.create')); ?>" class="action-btn w-100 text-center">
                    <i class="fas fa-concierge-bell"></i>Add Service
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="<?php echo e(route('admin.products.create')); ?>" class="action-btn w-100 text-center">
                    <i class="fas fa-shopping-cart"></i>Add Product
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="<?php echo e(route('admin.bookings.index')); ?>" class="action-btn w-100 text-center">
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
        <a href="<?php echo e(route('admin.bookings.index')); ?>" class="action-btn btn-outline">
            <i class="fas fa-eye"></i>View All
        </a>
    </div>
    <div class="card-body p-0">
        <?php if($recentBookings->count() > 0): ?>
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
                        <?php $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo e($booking->customer_name ?? ($booking->user->name ?? 'N/A')); ?></div>
                                <small class="text-muted"><?php echo e($booking->customer_phone ?? ($booking->user->phone ?? 'N/A')); ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($booking->service->name ?? 'N/A'); ?></div>
                                <small class="text-muted"><?php echo e($booking->service->category ?? ''); ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A'); ?></div>
                                <small class="text-muted"><?php echo e($booking->booking_time ?? 'N/A'); ?></small>
                            </td>
                            <td>
                                <?php if($booking->status === 'pending'): ?>
                                    <span class="status-badge inactive">Pending</span>
                                <?php elseif($booking->status === 'confirmed'): ?>
                                    <span class="status-badge active">Confirmed</span>
                                <?php elseif($booking->status === 'completed'): ?>
                                    <span class="status-badge featured">Completed</span>
                                <?php else: ?>
                                    <span class="status-badge inactive"><?php echo e(ucfirst($booking->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold">₹<?php echo e(number_format($booking->final_amount ?? $booking->amount ?? 0, 2)); ?></div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.bookings.show', $booking)); ?>" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.bookings.edit', $booking)); ?>" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-calendar-alt"></i>
                <h5>No Recent Bookings</h5>
                <p>Bookings will appear here once customers start making reservations.</p>
            </div>
        <?php endif; ?>
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
        <?php if($recentCustomers->count() > 0): ?>
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
                        <?php $__currentLoopData = $recentCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo e($customer->name); ?></div>
                            </td>
                            <td>
                                <div><?php echo e($customer->email); ?></div>
                            </td>
                            <td>
                                <div><?php echo e($customer->phone ?? 'N/A'); ?></div>
                            </td>
                            <td>
                                <div><?php echo e($customer->created_at->format('M d, Y')); ?></div>
                            </td>
                            <td>
                                <?php if($customer->is_active): ?>
                                    <span class="status-badge active">Active</span>
                                <?php else: ?>
                                    <span class="status-badge inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-user-friends"></i>
                <h5>No Recent Customers</h5>
                <p>Customer registrations will appear here.</p>
            </div>
        <?php endif; ?>
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
                            <div class="fw-bold text-primary fs-4"><?php echo e($stats['total_services']); ?></div>
                            <small class="text-muted">Total Services</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fw-bold text-success fs-4"><?php echo e($stats['total_products']); ?></div>
                            <small class="text-muted">Total Products</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fw-bold text-warning fs-4"><?php echo e($stats['pending_bookings']); ?></div>
                            <small class="text-muted">Pending Bookings</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="fw-bold text-info fs-4"><?php echo e($stats['total_categories']); ?></div>
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
                <?php if(count($recentActivities) > 0): ?>
                    <div class="activity-list">
                        <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon me-3">
                                <?php if(isset($activity['type']) && $activity['type'] === 'new_user'): ?>
                                    <i class="fas fa-user-plus text-success"></i>
                                <?php elseif(isset($activity['type']) && $activity['type'] === 'new_booking'): ?>
                                    <i class="fas fa-calendar-plus text-primary"></i>
                                <?php elseif(isset($activity['type']) && $activity['type'] === 'product_update'): ?>
                                    <i class="fas fa-box text-warning"></i>
                                <?php elseif(isset($activity['type']) && $activity['type'] === 'service_completed'): ?>
                                    <i class="fas fa-check-circle text-success"></i>
                                <?php else: ?>
                                    <i class="fas fa-circle text-primary"></i>
                                <?php endif; ?>
                            </div>
                            <div class="activity-content">
                                <div class="fw-bold"><?php echo e($activity['description'] ?? 'Activity'); ?></div>
                                <small class="text-muted"><?php echo e($activity['time'] ?? 'Recently'); ?></small>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-history fa-2x mb-3"></i>
                        <p>No recent activity</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\superdaily\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>