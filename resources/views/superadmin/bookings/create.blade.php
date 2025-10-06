@extends('layouts.superadmin')

@section('title', 'Create Booking - SuperAdmin')

@section('page-icon')
<i class="fas fa-plus"></i>
@endsection
@section('page-title', 'Create Booking')
@section('page-subtitle', 'Add a new booking manually')

@section('header-actions')
<a href="{{ route('superadmin.bookings.index') }}" class="action-btn btn-outline">
    <i class="fas fa-arrow-left"></i>Back to Bookings
</a>
@endsection

@section('content')
<!-- Form -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-calendar-plus"></i>Booking Information
        </h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('superadmin.bookings.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">Customer *</label>
                    <select class="form-control @error('user_id') is-invalid @enderror" 
                            id="user_id" name="user_id" required>
                        <option value="">Select Customer</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="service_id" class="form-label">Service *</label>
                    <select class="form-control @error('service_id') is-invalid @enderror" 
                            id="service_id" name="service_id" required>
                        <option value="">Select Service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} - ₹{{ number_format($service->discount_price ?: $service->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="maid_id" class="form-label">Assign Maid</label>
                    <select class="form-control @error('maid_id') is-invalid @enderror" 
                            id="maid_id" name="maid_id">
                        <option value="">Select Maid (Optional)</option>
                        @foreach($maids as $maid)
                            <option value="{{ $maid->id }}" {{ old('maid_id') == $maid->id ? 'selected' : '' }}>
                                {{ $maid->name }} (Rating: {{ $maid->rating ?? 'N/A' }}/5)
                            </option>
                        @endforeach
                    </select>
                    @error('maid_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-control @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="booking_date" class="form-label">Booking Date *</label>
                    <input type="date" class="form-control @error('booking_date') is-invalid @enderror" 
                           id="booking_date" name="booking_date" value="{{ old('booking_date') }}" required>
                    @error('booking_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="booking_time" class="form-label">Booking Time *</label>
                    <input type="time" class="form-control @error('booking_time') is-invalid @enderror" 
                           id="booking_time" name="booking_time" value="{{ old('booking_time') }}" required>
                    @error('booking_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Address *</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="special_instructions" class="form-label">Special Instructions</label>
                <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                          id="special_instructions" name="special_instructions" rows="3" 
                          placeholder="Any special instructions for this booking">{{ old('special_instructions') }}</textarea>
                @error('special_instructions')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="action-btn">
                    <i class="fas fa-save"></i>Create Booking
                </button>
                <a href="{{ route('superadmin.bookings.index') }}" class="action-btn btn-outline">
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
                Select the appropriate customer from the dropdown
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Choose the service and maid (if available)
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Set the booking date and time carefully
            </li>
            <li class="mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                Provide accurate contact information
            </li>
        </ul>
    </div>
</div>
@endsection
