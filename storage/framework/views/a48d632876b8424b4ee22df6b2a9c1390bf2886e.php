

<?php $__env->startSection('page-icon'); ?>
<i class="fas fa-calendar-check"></i>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-title', 'Booking Management'); ?>

<?php $__env->startSection('page-subtitle', 'Manage all service bookings and maid allocations'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-primary">
    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Total Bookings</div>
                            <div class="h4 mb-0"><?php echo e($stats['total'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Pending</div>
                            <div class="h4 mb-0"><?php echo e($stats['pending'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Confirmed</div>
                            <div class="h4 mb-0"><?php echo e($stats['confirmed'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">In Progress</div>
                            <div class="h4 mb-0"><?php echo e($stats['in_progress'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-play-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Completed</div>
                            <div class="h4 mb-0"><?php echo e($stats['completed'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-flag-checkered fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Cancelled</div>
                            <div class="h4 mb-0"><?php echo e($stats['cancelled'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>All Bookings
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if(isset($bookings) && $bookings->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Date & Time</th>
                                <th>Maid</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold">#<?php echo e($booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT)); ?></div>
                                        <small class="text-muted"><?php echo e($booking->created_at ? $booking->created_at->format('M d, Y') : 'N/A'); ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <?php if($booking->user && $booking->user->profile_image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $booking->user->profile_image)); ?>" 
                                                         alt="<?php echo e($booking->user->name ?? 'User'); ?>" 
                                                         class="rounded-circle" 
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                         style="width: 32px; height: 32px;">
                                                        <i class="fas fa-user text-white small"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo e($booking->user->name ?? 'Unknown User'); ?></div>
                                                <small class="text-muted"><?php echo e($booking->user->email ?? 'N/A'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo e($booking->service->name ?? 'Unknown Service'); ?></div>
                                        <small class="text-muted"><?php echo e($booking->service->category ?? 'N/A'); ?></small>
                                    </td>
                                    <td>
                                        <?php if($booking->booking_date): ?>
                                            <div class="fw-bold"><?php echo e($booking->booking_date->format('M d, Y')); ?></div>
                                            <small class="text-muted">
                                                <?php if($booking->booking_time): ?>
                                                    <?php
                                                        try {
                                                            // Try different time formats
                                                            $timeFormats = ['H:i:s', 'H:i', 'g:i A', 'g:i:s A'];
                                                            $time = null;
                                                            foreach ($timeFormats as $format) {
                                                                try {
                                                                    $time = \Carbon\Carbon::createFromFormat($format, $booking->booking_time);
                                                                    break;
                                                                } catch (Exception $e) {
                                                                    continue;
                                                                }
                                                            }
                                                            if ($time) {
                                                                echo $time->format('g:i A');
                                                            } else {
                                                                echo $booking->booking_time;
                                                            }
                                                        } catch (Exception $e) {
                                                            echo $booking->booking_time;
                                                        }
                                                    ?>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </small>
                                        <?php else: ?>
                                            <div class="fw-bold">N/A</div>
                                            <small class="text-muted">N/A</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($booking->maid): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <?php if($booking->maid->profile_image): ?>
                                                        <img src="<?php echo e(asset('storage/' . $booking->maid->profile_image)); ?>" 
                                                             alt="<?php echo e($booking->maid->name ?? 'Maid'); ?>" 
                                                             class="rounded-circle" 
                                                             style="width: 24px; height: 24px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" 
                                                             style="width: 24px; height: 24px;">
                                                            <i class="fas fa-user text-white" style="font-size: 10px;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo e($booking->maid->name ?? 'Unknown Maid'); ?></div>
                                                    <small class="text-muted">Rating: <?php echo e($booking->maid->rating ?? 'N/A'); ?>/5</small>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-user-clock me-1"></i>Not Assigned
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'in_progress' => 'info',
                                                'completed' => 'secondary',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusColor = $statusColors[$booking->status] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColor); ?> fs-6">
                                            <?php echo e(ucfirst($booking->status ?? 'Unknown')); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">₹<?php echo e(number_format($booking->total_amount ?? 0, 2)); ?></div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.bookings.show', $booking)); ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#statusModal<?php echo e($booking->id); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="<?php echo e(route('admin.bookings.destroy', $booking)); ?>" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Status Update Modal -->
                                <div class="modal fade" id="statusModal<?php echo e($booking->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Booking Status</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="<?php echo e(route('admin.bookings.update-status', $booking)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="status<?php echo e($booking->id); ?>" class="form-label">Status</label>
                                                        <select class="form-control" id="status<?php echo e($booking->id); ?>" name="status" required>
                                                            <option value="pending" <?php echo e(($booking->status ?? '') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                                            <option value="confirmed" <?php echo e(($booking->status ?? '') === 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                                                            <option value="in_progress" <?php echo e(($booking->status ?? '') === 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                                                            <option value="completed" <?php echo e(($booking->status ?? '') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                                            <option value="cancelled" <?php echo e(($booking->status ?? '') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="admin_notes<?php echo e($booking->id); ?>" class="form-label">Admin Notes</label>
                                                        <textarea class="form-control" id="admin_notes<?php echo e($booking->id); ?>" 
                                                                  name="admin_notes" rows="3" 
                                                                  placeholder="Add any notes about this booking"><?php echo e($booking->admin_notes ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing <?php echo e($bookings->firstItem()); ?> to <?php echo e($bookings->lastItem()); ?> of <?php echo e($bookings->total()); ?> bookings
                        </div>
                        <?php echo e($bookings->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No Bookings Found</h4>
                        <p class="text-muted">No bookings have been made yet.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.avatar-sm img {
    border: 2px solid #e9ecef;
}

.card {
    border-radius: 15px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.6rem;
}

.bg-pending { background-color: #ffc107 !important; }
.bg-confirmed { background-color: #28a745 !important; }
.bg-in-progress { background-color: #17a2b8 !important; }
.bg-completed { background-color: #6c757d !important; }
.bg-cancelled { background-color: #dc3545 !important; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\superdaily\resources\views/admin/bookings/index.blade.php ENDPATH**/ ?>