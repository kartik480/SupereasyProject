@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-edit"></i>
@endsection

@section('page-title', 'Edit Booking')

@section('page-subtitle', 'Update booking information')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.bookings.show', $booking) }}" class="btn btn-outline-info">
        <i class="fas fa-eye me-2"></i>View Details
    </a>
    <a href="{{ route('superadmin.bookings.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Booking Information
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('superadmin.bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ old('status', $booking->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="maid_id" class="form-label">Assign Maid</label>
                            <select class="form-control @error('maid_id') is-invalid @enderror" 
                                    id="maid_id" name="maid_id">
                                <option value="">Select Maid (Optional)</option>
                                @foreach($maids as $maid)
                                    <option value="{{ $maid->id }}" {{ old('maid_id', $booking->maid_id) == $maid->id ? 'selected' : '' }}>
                                        {{ $maid->name }} (Rating: {{ $maid->rating ?? 'N/A' }}/5)
                                    </option>
                                @endforeach
                            </select>
                            @error('maid_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="special_instructions" class="form-label">Special Instructions</label>
                            <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                                      id="special_instructions" name="special_instructions" rows="3">{{ old('special_instructions', $booking->special_instructions) }}</textarea>
                            @error('special_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control @error('admin_notes') is-invalid @enderror" 
                                      id="admin_notes" name="admin_notes" rows="3" 
                                      placeholder="Add any admin notes about this booking">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('superadmin.bookings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>Update Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Booking Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Booking Information</h5>
            </div>
            <div class="card-body">
                <div class="info-item mb-2">
                    <strong>Booking ID:</strong> #{{ $booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                </div>
                <div class="info-item mb-2">
                    <strong>Customer:</strong> {{ $booking->user->name ?? 'N/A' }}
                </div>
                <div class="info-item mb-2">
                    <strong>Service:</strong> {{ $booking->service->name ?? 'N/A' }}
                </div>
                <div class="info-item mb-2">
                    <strong>Date:</strong> {{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}
                </div>
                <div class="info-item mb-2">
                    <strong>Time:</strong> {{ $booking->booking_time ?? 'N/A' }}
                </div>
                <div class="info-item mb-2">
                    <strong>Amount:</strong> ₹{{ number_format($booking->total_amount ?? 0, 2) }}
                </div>
                <div class="info-item mb-2">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $booking->status == 'pending' ? 'warning' : ($booking->status == 'confirmed' ? 'success' : ($booking->status == 'in_progress' ? 'info' : ($booking->status == 'completed' ? 'secondary' : 'danger'))) }}">
                        {{ ucfirst($booking->status ?? 'Unknown') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($booking->status == 'pending')
                        <form method="POST" action="{{ route('superadmin.bookings.confirm', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-check me-2"></i>Confirm Booking
                            </button>
                        </form>
                    @endif
                    
                    @if($booking->status == 'confirmed')
                        <form method="POST" action="{{ route('superadmin.bookings.start', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-info w-100">
                                <i class="fas fa-play me-2"></i>Start Service
                            </button>
                        </form>
                    @endif
                    
                    @if($booking->status == 'in_progress')
                        <form method="POST" action="{{ route('superadmin.bookings.complete', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-flag-checkered me-2"></i>Complete Service
                            </button>
                        </form>
                    @endif
                    
                    @if(!in_array($booking->status, ['completed', 'cancelled']))
                        <form method="POST" action="{{ route('superadmin.bookings.cancel', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('Are you sure you want to cancel this booking?')">
                                <i class="fas fa-times me-2"></i>Cancel Booking
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
