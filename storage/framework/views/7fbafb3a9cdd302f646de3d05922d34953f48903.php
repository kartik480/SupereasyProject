

<?php $__env->startSection('page-icon'); ?>
<i class="fas fa-tags"></i>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-title', 'Categories Management'); ?>

<?php $__env->startSection('page-subtitle', 'Manage product and service categories'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Add New Category
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4 class="mb-0"><?php echo e($totalCategories); ?></h4>
                <small>Total Categories</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4 class="mb-0"><?php echo e($activeCategories); ?></h4>
                <small>Active Categories</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4 class="mb-0"><?php echo e($categoriesWithProducts); ?></h4>
                <small>With Products</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4 class="mb-0"><?php echo e($categoriesWithServices); ?></h4>
                <small>With Services</small>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Categories</h5>
    </div>
    <div class="card-body">
        <?php if($categories->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Services</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <?php if($category->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $category->image)); ?>" 
                                             alt="<?php echo e($category->name); ?>" 
                                             class="rounded" 
                                             width="50" 
                                             height="50">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <?php if($category->icon): ?>
                                                <i class="<?php echo e($category->icon); ?> text-muted"></i>
                                            <?php else: ?>
                                                <i class="fas fa-tag text-muted"></i>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo e($category->name); ?></div>
                                    <?php if($category->description): ?>
                                        <small class="text-muted"><?php echo e(Str::limit($category->description, 50)); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code><?php echo e($category->slug); ?></code>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($category->products_count); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-warning"><?php echo e($category->services_count); ?></span>
                                </td>
                                <td>
                                    <?php echo e($category->sort_order ?? 0); ?>

                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <?php if($category->is_active): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.categories.show', $category)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.categories.toggle-status', $category)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="is_active" value="<?php echo e($category->is_active ? 0 : 1); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-<?php echo e($category->is_active ? 'warning' : 'success'); ?>" 
                                                    title="<?php echo e($category->is_active ? 'Deactivate' : 'Activate'); ?>">
                                                <i class="fas fa-<?php echo e($category->is_active ? 'pause' : 'play'); ?>"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this category?')">
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
            
            <div class="d-flex justify-content-center">
                <?php echo e($categories->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Categories Found</h4>
                    <p class="text-muted">Start by creating your first category.</p>
                </div>
                <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Category
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\superdaily\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>