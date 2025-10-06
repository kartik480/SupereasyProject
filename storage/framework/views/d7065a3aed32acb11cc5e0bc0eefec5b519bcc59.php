

<?php $__env->startSection('title', 'Maids Management - Admin'); ?>

<?php $__env->startSection('page-icon'); ?>
<i class="fas fa-users"></i>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title', 'Maids Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage your maid workforce and availability'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.maids.create')); ?>" class="action-btn">
    <i class="fas fa-user-plus"></i>Add Maid
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon primary">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($totalMaids); ?></div>
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
        <div class="stat-card-value"><?php echo e($activeMaids); ?></div>
        <div class="stat-card-label">Active Maids</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+12% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($availableMaids); ?></div>
        <div class="stat-card-label">Available Now</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+5% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($topRatedMaids); ?></div>
        <div class="stat-card-label">Top Rated</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+2 from last month
        </div>
    </div>
</div>

<!-- Maids Table -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-list"></i>All Maids
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if($maids->count() > 0): ?>
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Maid Details</th>
                            <th>Contact</th>
                            <th>Specialization</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $maids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <img src="<?php echo e($maid->profile_image ? asset('storage/' . $maid->profile_image) : 'https://via.placeholder.com/50x50/6c757d/ffffff?text=M'); ?>" 
                                     alt="<?php echo e($maid->name); ?>" 
                                     class="product-image">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($maid->name); ?></div>
                                <small class="text-muted"><?php echo e($maid->bio ?? 'No bio available'); ?></small>
                                <br><small class="text-info">ID: <?php echo e($maid->id); ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($maid->phone ?? 'N/A'); ?></div>
                                <small class="text-muted"><?php echo e($maid->email); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?php echo e($maid->specialization ?? 'General'); ?></span>
                            </td>
                            <td>
                                <div class="fw-bold">
                                    <i class="fas fa-star text-warning"></i>
                                    <?php echo e(number_format($maid->rating ?? 0, 1)); ?>

                                </div>
                                <small class="text-muted"><?php echo e($maid->bookings_count ?? 0); ?> bookings</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <?php if($maid->is_available): ?>
                                        <span class="status-badge active">Available</span>
                                    <?php else: ?>
                                        <span class="status-badge inactive">Busy</span>
                                    <?php endif; ?>
                                    <?php if($maid->is_active): ?>
                                        <span class="status-badge active">Active</span>
                                    <?php else: ?>
                                        <span class="status-badge inactive">Inactive</span>
                                    <?php endif; ?>
                                    <?php if($maid->is_verified): ?>
                                        <span class="status-badge active">Verified</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.maids.show', $maid)); ?>" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.maids.edit', $maid)); ?>" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.maids.destroy', $maid)); ?>" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this maid?')">
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
                <?php echo e($maids->links()); ?>

            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h5>No Maids Found</h5>
                <p>Start by adding your first maid to the workforce.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?php echo e(route('admin.maids.create')); ?>" class="action-btn">
                        <i class="fas fa-user-plus"></i>Add Your First Maid
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\superdaily\resources\views/admin/maids/index.blade.php ENDPATH**/ ?>