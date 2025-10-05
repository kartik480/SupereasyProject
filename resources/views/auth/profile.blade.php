@extends('layouts.app')

@section('title', 'My Profile - SuperDaily')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Profile Header Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <div class="profile-header">
                        <div class="profile-image-container mx-auto mb-3">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : 'https://via.placeholder.com/120x120/6c757d/ffffff?text=' . substr(auth()->user()->name, 0, 1) }}" 
                                 alt="Profile Picture" 
                                 id="profileImagePreview" 
                                 class="profile-image">
                            <div class="profile-image-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                            <input type="file" id="profileImageInput" name="profile_image" 
                                   accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
                                   style="display: none;">
                        </div>
                        <h3 class="mb-2">{{ auth()->user()->name }}</h3>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-envelope me-2"></i>{{ auth()->user()->email }}
                        </p>
                        <div class="mt-2">
                            <span class="badge bg-success me-2">
                                <i class="fas fa-user-tag me-1"></i>{{ ucfirst(auth()->user()->role ?? 'Customer') }}
                            </span>
                            @if(auth()->user()->is_active)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Active
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Profile Information
                    </h5>
                    <p class="text-muted mb-0">Update your personal details and profile image</p>
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
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Update Failed:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Profile Image Upload Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">
                                            <i class="fas fa-image me-2"></i>Profile Image
                                        </h6>
                                        <div class="profile-upload-area">
                                            <div class="upload-preview mx-auto mb-3">
                                                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : 'https://via.placeholder.com/100x100/6c757d/ffffff?text=' . substr(auth()->user()->name, 0, 1) }}" 
                                                     alt="Profile Preview" 
                                                     id="uploadPreview" 
                                                     class="upload-preview-image">
                                            </div>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('profileImageInput').click()">
                                                <i class="fas fa-camera me-2"></i>Choose New Image
                                            </button>
                                            <p class="text-muted small mt-2 mb-0">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Max file size: 2MB • Supported: JPG, PNG, GIF, WebP
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                           placeholder="Enter your full name" required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                           placeholder="Enter your email" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Phone Number
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-phone text-primary"></i>
                                    </span>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                           placeholder="Enter your phone number">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i>Account Type
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user-tag text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control" 
                                           value="{{ ucfirst(auth()->user()->role ?? 'customer') }}" readonly>
                                    <span class="input-group-text">
                                        @if(auth()->user()->is_active)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Inactive
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Address
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </span>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" 
                                          placeholder="Enter your complete address">{{ old('address', auth()->user()->address ?? '') }}</textarea>
                            </div>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Account Information Card -->
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Account Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <small class="text-muted">Account Type:</small>
                                            <div class="fw-bold">{{ ucfirst(auth()->user()->role ?? 'Customer') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <small class="text-muted">Status:</small>
                                            <div>
                                                @if(auth()->user()->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle me-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <small class="text-muted">Member Since:</small>
                                            <div class="fw-bold">{{ auth()->user()->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <small class="text-muted">Last Updated:</small>
                                            <div class="fw-bold">{{ auth()->user()->updated_at->format('M d, Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <a href="{{ route('change-password') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('home') }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.profile-image-container {
    position: relative;
    width: 120px;
    height: 120px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.profile-image-container:hover {
    transform: scale(1.05);
}

.profile-image {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.profile-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-image-container:hover .profile-image-overlay {
    opacity: 1;
}

.profile-image-container:hover .profile-image {
    border-color: rgba(255, 255, 255, 0.8);
}

.upload-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e9ecef;
    transition: all 0.3s ease;
}

.upload-preview:hover {
    border-color: #007bff;
    transform: scale(1.05);
}

.upload-preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.info-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    transition: all 0.3s ease;
}

.input-group:focus-within .input-group-text {
    background-color: #e3f2fd;
    border-color: #007bff;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
}

.btn-outline-primary {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(0, 123, 255, 0.2);
}

.btn-outline-secondary {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(108, 117, 125, 0.2);
}

.profile-header {
    text-align: center;
}

.profile-header h3 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.profile-header p {
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.alert {
    border-radius: 10px;
    border: none;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.text-danger {
    color: #dc3545 !important;
}

.text-muted {
    color: #6c757d !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

/* Loading animation */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .profile-image-container {
        width: 100px;
        height: 100px;
    }
    
    .upload-preview {
        width: 80px;
        height: 80px;
    }
    
    .profile-header h3 {
        font-size: 1.5rem;
    }
    
    .profile-header p {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileImageInput = document.getElementById('profileImageInput');
    const profileImagePreview = document.getElementById('profileImagePreview');
    const uploadPreview = document.getElementById('uploadPreview');
    const profileForm = document.getElementById('profileForm');
    const submitBtn = document.getElementById('submitBtn');

    // Profile image preview functionality
    profileImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, GIF, or WebP)');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImagePreview.src = e.target.result;
                uploadPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation and submission
    profileForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!name) {
            e.preventDefault();
            showAlert('Please enter your full name', 'danger');
            return false;
        }
        
        if (!email) {
            e.preventDefault();
            showAlert('Please enter your email address', 'danger');
            return false;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            showAlert('Please enter a valid email address', 'danger');
            return false;
        }
        
        // Show loading state
        submitBtn.classList.add('btn-loading');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    });

    // Real-time validation
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');

    nameInput.addEventListener('input', function() {
        validateField(this, this.value.trim().length >= 2, 'Name must be at least 2 characters');
    });

    emailInput.addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        validateField(this, emailRegex.test(this.value), 'Please enter a valid email address');
    });

    phoneInput.addEventListener('input', function() {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        validateField(this, this.value === '' || phoneRegex.test(this.value), 'Please enter a valid phone number');
    });

    function validateField(field, isValid, message) {
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            removeFieldError(field);
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            showFieldError(field, message);
        }
    }

    function showFieldError(field, message) {
        removeFieldError(field);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function removeFieldError(field) {
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'danger' ? 'exclamation-circle' : 'check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const form = document.getElementById('profileForm');
        form.insertBefore(alertDiv, form.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Auto-save draft functionality
    let saveTimeout;
    const inputs = [nameInput, emailInput, phoneInput, document.getElementById('address')];
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                // Save to localStorage as draft
                const formData = {
                    name: nameInput.value,
                    email: emailInput.value,
                    phone: phoneInput.value,
                    address: document.getElementById('address').value
                };
                localStorage.setItem('profileDraft', JSON.stringify(formData));
            }, 1000);
        });
    });

    // Load draft on page load
    const draft = localStorage.getItem('profileDraft');
    if (draft) {
        try {
            const formData = JSON.parse(draft);
            nameInput.value = formData.name || '';
            emailInput.value = formData.email || '';
            phoneInput.value = formData.phone || '';
            document.getElementById('address').value = formData.address || '';
        } catch (e) {
            console.log('Could not load draft');
        }
    }

    // Clear draft on successful submission
    profileForm.addEventListener('submit', function() {
        localStorage.removeItem('profileDraft');
    });
});
</script>
@endsection