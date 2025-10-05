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
@endsection