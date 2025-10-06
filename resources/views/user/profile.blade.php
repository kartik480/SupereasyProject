@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>My Profile
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-image mb-3">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle img-fluid" 
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 150px; height: 150px;">
                                        <i class="fas fa-user fa-4x text-white"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Profile Picture Upload Form -->
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mb-3" id="profileUpdateForm">
                                @csrf
                                @method('PUT')
                                <div class="mb-2">
                                    <input type="file" name="profile_image" id="profile_image" 
                                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
                                           class="form-control form-control-sm" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-upload me-1"></i>Upload Picture
                                </button>
                            </form>
                            
                            <!-- Display uploaded image name -->
                            <div id="image-name-display" class="text-muted small" style="display: none;">
                                <i class="fas fa-image me-1"></i>
                                <span id="selected-image-name"></span>
                            </div>
                            
                            <h5 class="text-primary">{{ $user->name }}</h5>
                            <span class="badge bg-success">{{ ucfirst($user->role) }}</span>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <!-- ID -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">User ID</label>
                                    <p class="form-control-plaintext">{{ $user->id }}</p>
                                </div>
                                
                                <!-- Name -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Full Name</label>
                                    <p class="form-control-plaintext">{{ $user->name ?? 'Not provided' }}</p>
                                </div>
                                
                                <!-- Email -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Email Address</label>
                                    <p class="form-control-plaintext">{{ $user->email ?? 'Not provided' }}</p>
                                </div>
                                
                                <!-- Email Verified -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Email Verified</label>
                                    <p class="form-control-plaintext">
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Verified
                                            </span>
                                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($user->email_verified_at)->format('M d, Y g:i A') }}</small>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Not Verified
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                
                                <!-- Phone -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Phone Number</label>
                                    <p class="form-control-plaintext">{{ $user->phone ?? 'Not provided' }}</p>
                                </div>
                                
                                <!-- Address -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Address</label>
                                    <p class="form-control-plaintext">{{ $user->address ?? 'Not provided' }}</p>
                                </div>
                                
                                <!-- Role -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Role</label>
                                    <p class="form-control-plaintext">{{ ucfirst($user->role ?? 'N/A') }}</p>
                                </div>

                                <!-- Account Status -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Account Status</label>
                                    <p class="form-control-plaintext">
                                        @if($user->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Member Since -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Member Since</label>
                                    <p class="form-control-plaintext">{{ $user->created_at ? $user->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                                </div>

                                <!-- Last Updated -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Last Updated</label>
                                    <p class="form-control-plaintext">{{ $user->updated_at ? $user->updated_at->format('M d, Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('home') }}" class="btn btn-secondary">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileImageInput = document.getElementById('profile_image');
    const profileImage = document.querySelector('.profile-image img');
    const profileImageDiv = document.querySelector('.profile-image .rounded-circle.bg-secondary');
    const imageNameDisplay = document.getElementById('image-name-display');
    const selectedImageName = document.getElementById('selected-image-name');
    const profileUpdateForm = document.getElementById('profileUpdateForm');
    
    // Create notification container
    const notificationContainer = document.createElement('div');
    notificationContainer.id = 'notification-container';
    notificationContainer.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 350px;
    `;
    document.body.appendChild(notificationContainer);
    
    // Function to show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show`;
        notification.style.cssText = `
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
        `;
        
        const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
        notification.innerHTML = `
            <i class="${icon} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        notificationContainer.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
    
    // Handle file input change
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Display image name
                selectedImageName.textContent = file.name;
                imageNameDisplay.style.display = 'block';
                
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('File size must be less than 2MB', 'danger');
                    e.target.value = '';
                    imageNameDisplay.style.display = 'none';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    showNotification('Please select a valid image file (JPEG, PNG, JPG, GIF, WEBP)', 'danger');
                    e.target.value = '';
                    imageNameDisplay.style.display = 'none';
                    return;
                }
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (profileImage) {
                        profileImage.src = e.target.result;
                    } else if (profileImageDiv) {
                        profileImageDiv.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Handle form submission with AJAX
    if (profileUpdateForm) {
        profileUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading...';
            submitButton.disabled = true;
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    
                    // Update profile image if new image path provided
                    if (data.image_path && profileImage) {
                        profileImage.src = data.image_path;
                    }
                    
                    // Hide image name display
                    imageNameDisplay.style.display = 'none';
                    
                    // Reset form
                    this.reset();
                } else {
                    showNotification(data.message || 'Upload failed. Please try again.', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Upload failed. Please try again.', 'danger');
            })
            .finally(() => {
                // Reset button state
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            });
        });
    }
});
</script>