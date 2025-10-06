@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Admin Registration
                    </h4>
                    <small class="opacity-75">Create Admin Account</small>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Registration Failed:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.register') }}" method="POST" id="adminRegisterForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-user me-2"></i>Full Name
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter your full name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2"></i>Email Address
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" 
                                           placeholder="admin@superdaily.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="phone" class="form-label fw-bold">
                                        <i class="fas fa-phone me-2"></i>Phone Number
                                    </label>
                                    <input type="tel" 
                                           name="phone" 
                                           id="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}" 
                                           placeholder="+1 (555) 123-4567"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="role" class="form-label fw-bold">
                                        <i class="fas fa-user-tag me-2"></i>Admin Role
                                    </label>
                                    <select name="role" 
                                            id="role" 
                                            class="form-select @error('role') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Admin Role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt me-2"></i>Address
                            </label>
                            <textarea name="address" 
                                      id="address" 
                                      class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Enter your complete address"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="fas fa-lock me-2"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               placeholder="Enter password"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Password must be at least 8 characters long
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-bold">
                                        <i class="fas fa-lock me-2"></i>Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               placeholder="Confirm password"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                            <i class="fas fa-eye" id="toggleIconConfirm"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="terms" id="terms" class="form-check-input" required>
                                <label for="terms" class="form-check-label">
                                    I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="registerBtn">
                                <i class="fas fa-user-plus me-2"></i>Create Admin Account
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">Already have an admin account?</p>
                        <a href="{{ route('admin.login.show') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-1"></i>Login to Admin Panel
                        </a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Back to Home
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Admin accounts have access to the management panel
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const toggleIconConfirm = document.getElementById('toggleIconConfirm');
    
    const registerForm = document.getElementById('adminRegisterForm');
    const registerBtn = document.getElementById('registerBtn');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'text') {
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });
    
    // Toggle confirm password visibility
    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        
        if (type === 'text') {
            toggleIconConfirm.classList.remove('fa-eye');
            toggleIconConfirm.classList.add('fa-eye-slash');
        } else {
            toggleIconConfirm.classList.remove('fa-eye-slash');
            toggleIconConfirm.classList.add('fa-eye');
        }
    });
    
    // Form submission with loading state
    registerForm.addEventListener('submit', function() {
        registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
        registerBtn.disabled = true;
    });
    
    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = getPasswordStrength(password);
        
        // Remove existing strength classes
        this.classList.remove('is-weak', 'is-medium', 'is-strong');
        
        if (password.length > 0) {
            this.classList.add(strength);
        }
    });
    
    function getPasswordStrength(password) {
        if (password.length < 6) return 'is-weak';
        if (password.length < 8) return 'is-medium';
        if (password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password)) return 'is-strong';
        return 'is-medium';
    }
});
</script>

<style>
.is-weak {
    border-color: #dc3545 !important;
}

.is-medium {
    border-color: #ffc107 !important;
}

.is-strong {
    border-color: #198754 !important;
}
</style>
@endsection
