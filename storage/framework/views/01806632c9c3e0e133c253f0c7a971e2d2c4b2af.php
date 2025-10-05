

<?php $__env->startSection('title', 'Services Management - Admin'); ?>

<?php $__env->startSection('page-icon'); ?>
<i class="fas fa-concierge-bell"></i>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title', 'Services Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage your service offerings and bookings'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.services.create')); ?>" class="action-btn">
    <i class="fas fa-plus"></i>Add Service
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon primary">
                <i class="fas fa-concierge-bell"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($services->total()); ?></div>
        <div class="stat-card-label">Total Services</div>
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
        <div class="stat-card-value"><?php echo e($services->where('is_active', true)->count()); ?></div>
        <div class="stat-card-label">Active Services</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+10% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($services->where('is_featured', true)->count()); ?></div>
        <div class="stat-card-label">Featured Services</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+7% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-tags"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($services->pluck('category')->unique()->count()); ?></div>
        <div class="stat-card-label">Categories</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+3 new categories
        </div>
    </div>
</div>

<!-- Services Table -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-list"></i>All Services
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if($services->count() > 0): ?>
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Service Details</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <img src="<?php echo e($service->image_url); ?>" 
                                     alt="<?php echo e($service->name); ?>" 
                                     class="product-image">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($service->name); ?></div>
                                <small class="text-muted"><?php echo e(Str::limit($service->description, 50)); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?php echo e($service->category); ?></span>
                            </td>
                            <td>
                                <div class="fw-bold">₹<?php echo e(number_format($service->price, 2)); ?></div>
                                <?php if($service->discount_price): ?>
                                    <small class="text-success">₹<?php echo e(number_format($service->discount_price, 2)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($service->duration ?? 'N/A'); ?></div>
                                <?php if($service->duration): ?>
                                    <small class="text-muted"><?php echo e($service->duration); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <?php if($service->is_featured): ?>
                                        <span class="status-badge featured">Featured</span>
                                    <?php endif; ?>
                                    <?php if($service->is_active): ?>
                                        <span class="status-badge active">Active</span>
                                    <?php else: ?>
                                        <span class="status-badge inactive">Inactive</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.services.show', $service)); ?>" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.services.edit', $service)); ?>" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.services.destroy', $service)); ?>" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this service?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center p-4">
                <?php echo e($services->links()); ?>

            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-concierge-bell"></i>
                <h5>No Services Found</h5>
                <p>Start by adding your first service to the catalog.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?php echo e(route('admin.services.create')); ?>" class="action-btn">
                        <i class="fas fa-plus"></i>Add Your First Service
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\superdaily\resources\views/admin/services/index.blade.php ENDPATH**/ ?>