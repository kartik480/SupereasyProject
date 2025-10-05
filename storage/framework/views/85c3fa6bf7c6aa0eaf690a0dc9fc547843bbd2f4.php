

<?php $__env->startSection('title', 'Products Management - Admin'); ?>

<?php $__env->startSection('page-icon'); ?>
<i class="fas fa-shopping-cart"></i>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title', 'Products Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage your product inventory and catalog'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.products.bulk-upload')); ?>" class="action-btn btn-outline">
    <i class="fas fa-upload"></i>Bulk Upload
</a>
<a href="<?php echo e(route('admin.products.create')); ?>" class="action-btn">
    <i class="fas fa-plus"></i>Add Product
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon primary">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($products->total()); ?></div>
        <div class="stat-card-label">Total Products</div>
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
        <div class="stat-card-value"><?php echo e($products->where('is_active', true)->count()); ?></div>
        <div class="stat-card-label">Active Products</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+8% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($products->where('is_featured', true)->count()); ?></div>
        <div class="stat-card-label">Featured Products</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+5% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-card-value"><?php echo e($products->where('stock_quantity', '<', 10)->count()); ?></div>
        <div class="stat-card-label">Low Stock</div>
        <div class="stat-card-change negative">
            <i class="fas fa-arrow-down me-1"></i>Needs attention
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-list"></i>All Products
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if($products->count() > 0): ?>
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Details</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <img src="<?php echo e($product->image_url); ?>" 
                                     alt="<?php echo e($product->name); ?>" 
                                     class="product-image">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($product->name); ?></div>
                                <small class="text-muted"><?php echo e(Str::limit($product->description, 50)); ?></small>
                                <?php if($product->sku): ?>
                                    <br><small class="text-info">SKU: <?php echo e($product->sku); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?php echo e($product->category->name ?? 'N/A'); ?></span>
                            </td>
                            <td>
                                <div class="fw-bold">₹<?php echo e(number_format($product->price, 2)); ?></div>
                                <?php if($product->discount_price): ?>
                                    <small class="text-success">₹<?php echo e(number_format($product->discount_price, 2)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold <?php echo e($product->stock_quantity < 10 ? 'text-danger' : ''); ?>">
                                    <?php echo e($product->stock_quantity); ?> <?php echo e($product->unit); ?>

                                </div>
                                <?php if($product->stock_quantity < 10): ?>
                                    <small class="text-danger">Low Stock!</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <?php if($product->is_featured): ?>
                                        <span class="status-badge featured">Featured</span>
                                    <?php endif; ?>
                                    <?php if($product->is_active): ?>
                                        <span class="status-badge active">Active</span>
                                    <?php else: ?>
                                        <span class="status-badge inactive">Inactive</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.products.show', $product)); ?>" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.products.edit', $product)); ?>" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
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
                <?php echo e($products->links()); ?>

            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h5>No Products Found</h5>
                <p>Start by adding your first product to the inventory.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?php echo e(route('admin.products.create')); ?>" class="action-btn">
                        <i class="fas fa-plus"></i>Add Your First Product
                    </a>
                    <a href="<?php echo e(route('admin.products.bulk-upload')); ?>" class="action-btn btn-outline">
                        <i class="fas fa-upload"></i>Bulk Upload
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\superdaily\resources\views/admin/products/index.blade.php ENDPATH**/ ?>