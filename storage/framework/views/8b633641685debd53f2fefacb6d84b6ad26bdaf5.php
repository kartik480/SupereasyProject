

<?php $__env->startSection('title', 'Create Service - Admin'); ?>

<?php $__env->startSection('page-icon'); ?>
<i class="fas fa-plus"></i>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title', 'Create Service'); ?>
<?php $__env->startSection('page-subtitle', 'Add a new service to your offerings'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.services.index')); ?>" class="action-btn btn-outline">
    <i class="fas fa-arrow-left"></i>Back to Services
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Form -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-concierge-bell"></i>Service Information
        </h5>
    </div>
    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.services.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Service Name *</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="main_category" class="form-label">Main Category *</label>
                    <select class="form-control <?php $__errorArgs = ['main_category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="main_category" name="main_category" required onchange="toggleSubcategories()">
                        <option value="">Select Main Category</option>
                        <option value="one_time" <?php echo e(old('main_category') == 'one_time' ? 'selected' : ''); ?>>One-time Services</option>
                        <option value="monthly_subscription" <?php echo e(old('main_category') == 'monthly_subscription' ? 'selected' : ''); ?>>Monthly Subscription</option>
                    </select>
                    <?php $__errorArgs = ['main_category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="subcategory" class="form-label">Subcategory *</label>
                    <select class="form-control <?php $__errorArgs = ['subcategory'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="subcategory" name="subcategory" required>
                        <option value="">Select Subcategory</option>
                        <!-- One-time Services Subcategories -->
                        <option value="electrical" class="one-time-option" <?php echo e(old('subcategory') == 'electrical' ? 'selected' : ''); ?>>Electrical</option>
                        <option value="plumbing" class="one-time-option" <?php echo e(old('subcategory') == 'plumbing' ? 'selected' : ''); ?>>Plumbing</option>
                        <option value="washing" class="one-time-option" <?php echo e(old('subcategory') == 'washing' ? 'selected' : ''); ?>>Washing</option>
                        <option value="washroom" class="one-time-option" <?php echo e(old('subcategory') == 'washroom' ? 'selected' : ''); ?>>Washroom Cleaning</option>
                        <option value="cooking" class="one-time-option" <?php echo e(old('subcategory') == 'cooking' ? 'selected' : ''); ?>>Cooking</option>
                        <!-- Monthly Subscription Subcategories -->
                        <option value="home_maid" class="subscription-option" <?php echo e(old('subcategory') == 'home_maid' ? 'selected' : ''); ?>>Home Maid</option>
                        <option value="caretakers" class="subscription-option" <?php echo e(old('subcategory') == 'caretakers' ? 'selected' : ''); ?>>Caretakers</option>
                        <option value="cooking_subscription" class="subscription-option" <?php echo e(old('subcategory') == 'cooking_subscription' ? 'selected' : ''); ?>>Cooking</option>
                        <option value="car_cleaning" class="subscription-option" <?php echo e(old('subcategory') == 'car_cleaning' ? 'selected' : ''); ?>>Car Cleaning</option>
                        <option value="washroom_cleaning" class="subscription-option" <?php echo e(old('subcategory') == 'washroom_cleaning' ? 'selected' : ''); ?>>Washroom Cleaning</option>
                    </select>
                    <?php $__errorArgs = ['subcategory'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="booking_advance_hours" class="form-label">Booking Advance Notice (Hours) *</label>
                    <input type="number" class="form-control <?php $__errorArgs = ['booking_advance_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="booking_advance_hours" name="booking_advance_hours" 
                           value="<?php echo e(old('booking_advance_hours', 2)); ?>" min="1" max="24" required>
                    <small class="form-text text-muted">How many hours before service should user book?</small>
                    <?php $__errorArgs = ['booking_advance_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="description" name="description" rows="3" required><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Price (₹) *</label>
                    <input type="number" step="0.01" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="price" name="price" value="<?php echo e(old('price')); ?>" required>
                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="discount_price" class="form-label">Discount Price (₹)</label>
                    <input type="number" step="0.01" class="form-control <?php $__errorArgs = ['discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="discount_price" name="discount_price" value="<?php echo e(old('discount_price')); ?>">
                    <?php $__errorArgs = ['discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="duration" class="form-label">Duration *</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="duration" name="duration" value="<?php echo e(old('duration')); ?>" placeholder="e.g., 2 hours" required>
                    <?php $__errorArgs = ['duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Unit *</label>
                    <select class="form-control <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="unit" name="unit" required>
                        <option value="">Select Unit</option>
                        <option value="per session" <?php echo e(old('unit') == 'per session' ? 'selected' : ''); ?>>Per Session</option>
                        <option value="per hour" <?php echo e(old('unit') == 'per hour' ? 'selected' : ''); ?>>Per Hour</option>
                        <option value="per day" <?php echo e(old('unit') == 'per day' ? 'selected' : ''); ?>>Per Day</option>
                        <option value="per week" <?php echo e(old('unit') == 'per week' ? 'selected' : ''); ?>>Per Week</option>
                        <option value="per month" <?php echo e(old('unit') == 'per month' ? 'selected' : ''); ?>>Per Month</option>
                    </select>
                    <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_featured">
                            Featured Service
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="features" class="form-label">Features</label>
                <textarea class="form-control <?php $__errorArgs = ['features'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="features" name="features" rows="2" placeholder="Enter features separated by commas"><?php echo e(old('features')); ?></textarea>
                <small class="form-text text-muted">Separate multiple features with commas (e.g., Deep cleaning, Eco-friendly products, Professional staff)</small>
                <?php $__errorArgs = ['features'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label for="requirements" class="form-label">Requirements</label>
                <textarea class="form-control <?php $__errorArgs = ['requirements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="requirements" name="requirements" rows="2" placeholder="Enter requirements separated by commas"><?php echo e(old('requirements')); ?></textarea>
                <small class="form-text text-muted">Separate multiple requirements with commas (e.g., Access to water, Basic cleaning supplies provided)</small>
                <?php $__errorArgs = ['requirements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Subscription Plans Section (for Monthly Subscription services) -->
            <div id="subscription-plans-section" class="mb-4" style="display: none;">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="fas fa-calendar-alt me-2"></i>Subscription Plans
                </h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Weekly Plan</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" class="form-control" 
                                   name="subscription_plans[weekly][price]" 
                                   placeholder="Price per week" 
                                   value="<?php echo e(old('subscription_plans.weekly.price')); ?>">
                            <input type="number" class="form-control" 
                                   name="subscription_plans[weekly][visits]" 
                                   placeholder="Visits per week" 
                                   value="<?php echo e(old('subscription_plans.weekly.visits')); ?>">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bi-weekly Plan</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" class="form-control" 
                                   name="subscription_plans[biweekly][price]" 
                                   placeholder="Price per 2 weeks" 
                                   value="<?php echo e(old('subscription_plans.biweekly.price')); ?>">
                            <input type="number" class="form-control" 
                                   name="subscription_plans[biweekly][visits]" 
                                   placeholder="Visits per 2 weeks" 
                                   value="<?php echo e(old('subscription_plans.biweekly.visits')); ?>">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Monthly Plan</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" class="form-control" 
                                   name="subscription_plans[monthly][price]" 
                                   placeholder="Price per month" 
                                   value="<?php echo e(old('subscription_plans.monthly.price')); ?>">
                            <input type="number" class="form-control" 
                                   name="subscription_plans[monthly][visits]" 
                                   placeholder="Visits per month" 
                                   value="<?php echo e(old('subscription_plans.monthly.visits')); ?>">
                        </div>
                    </div>
                </div>
                <small class="form-text text-muted">Set pricing and visit frequency for different subscription plans</small>
            </div>

            <!-- Booking Requirements Section -->
            <div class="mb-3">
                <label for="booking_requirements" class="form-label">Booking Requirements</label>
                <textarea class="form-control <?php $__errorArgs = ['booking_requirements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="booking_requirements" name="booking_requirements" rows="2" 
                          placeholder="Enter booking requirements and advance notice details"><?php echo e(old('booking_requirements')); ?></textarea>
                <small class="form-text text-muted">Special requirements for booking this service (e.g., Advance booking required, Specific time slots available)</small>
                <?php $__errorArgs = ['booking_requirements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Image Upload Section -->
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="fas fa-images me-2"></i>Service Images
                </h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Main Image</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)</small>
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="image_2" class="form-label">Additional Image 1</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image_2" name="image_2" accept=".jpg,.jpeg,.png,.gif,.webp">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)</small>
                        <?php $__errorArgs = ['image_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="image_3" class="form-label">Additional Image 2</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image_3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image_3" name="image_3" accept=".jpg,.jpeg,.png,.gif,.webp">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)</small>
                        <?php $__errorArgs = ['image_3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="image_4" class="form-label">Additional Image 3</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image_4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image_4" name="image_4" accept=".jpg,.jpeg,.png,.gif,.webp">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max: 2MB)</small>
                        <?php $__errorArgs = ['image_4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="action-btn">
                    <i class="fas fa-save"></i>Create Service
                </button>
                <a href="<?php echo e(route('admin.services.index')); ?>" class="action-btn btn-outline">
                    <i class="fas fa-times"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tips Card -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-lightbulb"></i>Tips
        </h5>
    </div>
    <div class="card-body">
        <ul class="list-unstyled">
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Use clear, professional service images
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Set competitive pricing for your area
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Featured services appear on homepage
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                List all important features and requirements
            </li>
        </ul>
    </div>
</div>

<script>
function toggleSubcategories() {
    const mainCategory = document.getElementById('main_category').value;
    const subcategorySelect = document.getElementById('subcategory');
    const subscriptionSection = document.getElementById('subscription-plans-section');
    const bookingAdvanceHours = document.getElementById('booking_advance_hours');
    
    // Get all subcategory options
    const allOptions = subcategorySelect.querySelectorAll('option');
    
    // Hide all subcategory options first
    allOptions.forEach(option => {
        if (option.value !== '') {
            option.style.display = 'none';
            option.disabled = true;
        }
    });
    
    // Reset subcategory selection
    subcategorySelect.value = '';
    
    // Show relevant subcategory options based on main category
    if (mainCategory === 'one_time') {
        // Show One-time Services subcategories
        const oneTimeOptions = subcategorySelect.querySelectorAll('.one-time-option');
        oneTimeOptions.forEach(option => {
            option.style.display = 'block';
            option.disabled = false;
        });
    } else if (mainCategory === 'monthly_subscription') {
        // Show Monthly Subscription subcategories
        const subscriptionOptions = subcategorySelect.querySelectorAll('.subscription-option');
        subscriptionOptions.forEach(option => {
            option.style.display = 'block';
            option.disabled = false;
        });
    }
    
    // Show/hide subscription plans section
    if (mainCategory === 'monthly_subscription') {
        subscriptionSection.style.display = 'block';
    } else {
        subscriptionSection.style.display = 'none';
    }
}

// Function to set default booking advance hours based on subcategory
function setDefaultBookingHours() {
    const mainCategory = document.getElementById('main_category').value;
    const subcategory = document.getElementById('subcategory').value;
    const bookingAdvanceHours = document.getElementById('booking_advance_hours');
    
    let defaultHours = 2; // Default
    
    if (mainCategory === 'one_time') {
        switch(subcategory) {
            case 'electrical':
            case 'plumbing':
            case 'washing':
            case 'washroom':
                defaultHours = 2;
                break;
            case 'cooking':
                defaultHours = 12;
                break;
        }
    } else if (mainCategory === 'monthly_subscription') {
        defaultHours = 24; // 24 hours advance notice for subscriptions
    }
    
    bookingAdvanceHours.value = defaultHours;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners
    document.getElementById('main_category').addEventListener('change', toggleSubcategories);
    document.getElementById('subcategory').addEventListener('change', setDefaultBookingHours);
    
    // Initialize the form
    toggleSubcategories();
    
    // If there's an old value for main_category, trigger the change
    const mainCategoryValue = document.getElementById('main_category').value;
    if (mainCategoryValue) {
        toggleSubcategories();
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\superdaily\resources\views/admin/services/create.blade.php ENDPATH**/ ?>