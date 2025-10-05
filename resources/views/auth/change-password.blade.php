@extends('layouts.app')

@section('title', 'Change Password - SuperDaily')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card shadow-2xl border-0 rounded-4 overflow-hidden" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-5">
                    <!-- Header Section -->
                    <div class="text-center mb-5">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-gradient text-white" 
                                 style="width: 80px; height: 80px; box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);">
                                <i class="fas fa-shield-alt fa-2x"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-dark mb-2">Change Password</h2>
                        <p class="text-muted mb-0">Secure your account with a new password</p>
                    </div>

                    <!-- Success Alert -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>Success!</strong> {{ session('success') }}
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif

                    <!-- Error Alert -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>Password Change Failed:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif

                    <!-- Password Change Form -->
                    <form method="POST" action="{{ route('change-password') }}" id="changePasswordForm">
                        @csrf
                        
                        <!-- Current Password Field -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-semibold text-dark">
                                <i class="fas fa-key me-2 text-primary"></i>Current Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0 @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" 
                                       placeholder="Enter your current password" required
                                       style="border-radius: 0 8px 8px 0;">
                                <button class="btn btn-outline-secondary border-start-0" type="button" id="toggleCurrentPassword"
                                        style="border-radius: 0 8px 8px 0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- New Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-dark">
                                <i class="fas fa-lock me-2 text-primary"></i>New Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Enter your new password" required
                                       style="border-radius: 0 8px 8px 0;">
                                <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword"
                                        style="border-radius: 0 8px 8px 0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="password-strength">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <small class="text-muted" id="strengthText">Password strength</small>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark">
                                <i class="fas fa-lock me-2 text-primary"></i>Confirm New Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Confirm your new password" required
                                       style="border-radius: 0 8px 8px 0;">
                                <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePasswordConfirmation"
                                        style="border-radius: 0 8px 8px 0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted" id="passwordMatch"></small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-lg" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span class="btn-text">Update Password</span>
                                <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>

                        <!-- Back Link -->
                        <div class="text-center">
                            <a href="{{ route('user.profile') }}" class="text-decoration-none text-primary fw-semibold">
                                <i class="fas fa-arrow-left me-2"></i>Back to Profile
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.password-strength {
    margin-top: 8px;
}

.strength-bar {
    height: 4px;
    background-color: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 4px;
}

.strength-fill {
    height: 100%;
    width: 0%;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-weak { background-color: #dc3545; }
.strength-fair { background-color: #fd7e14; }
.strength-good { background-color: #ffc107; }
.strength-strong { background-color: #198754; }

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
}

.input-group-text {
    border-radius: 8px 0 0 8px;
}

.alert {
    border-radius: 12px;
}

.card {
    border-radius: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggles
    function togglePasswordVisibility(toggleId, inputId) {
        document.getElementById(toggleId).addEventListener('click', function() {
            const input = document.getElementById(inputId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    // Initialize password toggles
    togglePasswordVisibility('toggleCurrentPassword', 'current_password');
    togglePasswordVisibility('togglePassword', 'password');
    togglePasswordVisibility('togglePasswordConfirmation', 'password_confirmation');

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        let strengthText = '';
        let strengthClass = '';

        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]/)) strength += 1;
        if (password.match(/[A-Z]/)) strength += 1;
        if (password.match(/[0-9]/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

        const strengthFill = document.getElementById('strengthFill');
        const strengthTextEl = document.getElementById('strengthText');

        switch (strength) {
            case 0:
            case 1:
                strengthText = 'Very Weak';
                strengthClass = 'strength-weak';
                strengthFill.style.width = '20%';
                break;
            case 2:
                strengthText = 'Weak';
                strengthClass = 'strength-weak';
                strengthFill.style.width = '40%';
                break;
            case 3:
                strengthText = 'Fair';
                strengthClass = 'strength-fair';
                strengthFill.style.width = '60%';
                break;
            case 4:
                strengthText = 'Good';
                strengthClass = 'strength-good';
                strengthFill.style.width = '80%';
                break;
            case 5:
                strengthText = 'Strong';
                strengthClass = 'strength-strong';
                strengthFill.style.width = '100%';
                break;
        }

        strengthFill.className = `strength-fill ${strengthClass}`;
        strengthTextEl.textContent = `Password strength: ${strengthText}`;
    }

    // Password match checker
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const matchText = document.getElementById('passwordMatch');

        if (confirmPassword.length > 0) {
            if (password === confirmPassword) {
                matchText.textContent = '✓ Passwords match';
                matchText.className = 'text-success';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.className = 'text-danger';
            }
        } else {
            matchText.textContent = '';
        }
    }

    // Event listeners
    document.getElementById('password').addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });

    document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

    // Form submission with loading state
    document.getElementById('changePasswordForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.spinner-border');
        
        submitBtn.disabled = true;
        btnText.textContent = 'Updating...';
        spinner.classList.remove('d-none');
    });

    // Add smooth animations
    const card = document.querySelector('.card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
        card.style.transition = 'all 0.6s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 100);
});
</script>
@endsection