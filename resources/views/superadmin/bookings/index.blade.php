@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-calendar-check"></i>
@endsection

@section('page-title', 'Booking Management')
@section('page-subtitle', 'Manage all service bookings and maid allocations')

@section('header-actions')
<a href="{{ route('superadmin.bookings.create') }}" class="action-btn">
    <i class="fas fa-plus"></i>Add Booking
</a>
<a href="{{ route('superadmin.dashboard') }}" class="action-btn btn-outline">
    <i class="fas fa-arrow-left"></i>Back to Dashboard
</a>
@endsection

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon danger">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['total'] ?? 0 }}</div>
        <div class="stat-card-label">Total Bookings</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+15% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['pending'] ?? 0 }}</div>
        <div class="stat-card-label">Pending</div>
        <div class="stat-card-change neutral">
            <i class="fas fa-minus me-1"></i>Needs attention
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['confirmed'] ?? 0 }}</div>
        <div class="stat-card-label">Confirmed</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+8% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon info">
                <i class="fas fa-play-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['in_progress'] ?? 0 }}</div>
        <div class="stat-card-label">In Progress</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+12% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon secondary">
                <i class="fas fa-flag-checkered"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['completed'] ?? 0 }}</div>
        <div class="stat-card-label">Completed</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+20% from last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon danger">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['cancelled'] ?? 0 }}</div>
        <div class="stat-card-label">Cancelled</div>
        <div class="stat-card-change negative">
            <i class="fas fa-arrow-down me-1"></i>-5% from last month
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-list"></i>All Bookings
        </h5>
    </div>
    <div class="card-body p-0">
        @if(isset($bookings) && $bookings->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Maid</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-bold">#{{ $booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    <small class="text-muted">{{ $booking->created_at ? $booking->created_at->format('M d, Y') : 'N/A' }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            @if($booking->user && $booking->user->profile_image)
                                                <img src="{{ asset('storage/' . $booking->user->profile_image) }}" 
                                                     alt="{{ $booking->user->name ?? 'User' }}" 
                                                     class="rounded-circle" 
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white small"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $booking->user->name ?? 'Unknown User' }}</div>
                                            <small class="text-muted">{{ $booking->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $booking->service->name ?? 'Unknown Service' }}</div>
                                    <small class="text-muted">{{ $booking->service->category ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($booking->booking_date)
                                        <div class="fw-bold">{{ $booking->booking_date->format('M d, Y') }}</div>
                                        <small class="text-muted">
                                            @if($booking->booking_time)
                                                @php
                                                    try {
                                                        $timeFormats = ['H:i:s', 'H:i', 'g:i A', 'g:i:s A'];
                                                        $time = null;
                                                        foreach ($timeFormats as $format) {
                                                            try {
                                                                $time = \Carbon\Carbon::createFromFormat($format, $booking->booking_time);
                                                                break;
                                                            } catch (Exception $e) {
                                                                continue;
                                                            }
                                                        }
                                                        if ($time) {
                                                            echo $time->format('g:i A');
                                                        } else {
                                                            echo $booking->booking_time;
                                                        }
                                                    } catch (Exception $e) {
                                                        echo $booking->booking_time;
                                                    }
                                                @endphp
                                            @else
                                                N/A
                                            @endif
                                        </small>
                                    @else
                                        <div class="fw-bold">N/A</div>
                                        <small class="text-muted">N/A</small>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->maid)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                @if($booking->maid->profile_image)
                                                    <img src="{{ asset('storage/' . $booking->maid->profile_image) }}" 
                                                         alt="{{ $booking->maid->name ?? 'Maid' }}" 
                                                         class="rounded-circle" 
                                                         style="width: 24px; height: 24px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" 
                                                         style="width: 24px; height: 24px;">
                                                        <i class="fas fa-user text-white" style="font-size: 10px;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $booking->maid->name ?? 'Unknown Maid' }}</div>
                                                <small class="text-muted">Rating: {{ $booking->maid->rating ?? 'N/A' }}/5</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-user-clock me-1"></i>Not Assigned
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'success',
                                            'in_progress' => 'info',
                                            'completed' => 'secondary',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusColor = $statusColors[$booking->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ ucfirst($booking->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">₹{{ number_format($booking->total_amount ?? 0, 2) }}</div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('superadmin.bookings.show', $booking) }}" 
                                           class="btn-action btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('superadmin.bookings.edit', $booking) }}" 
                                           class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#statusModal{{ $booking->id }}" title="Update Status">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                        <form action="{{ route('superadmin.bookings.destroy', $booking) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Status Update Modal -->
                            <div class="modal fade" id="statusModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Booking Status</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('superadmin.bookings.update-status', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="status{{ $booking->id }}" class="form-label">Status</label>
                                                    <select class="form-control" id="status{{ $booking->id }}" name="status" required>
                                                        <option value="pending" {{ ($booking->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="confirmed" {{ ($booking->status ?? '') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                        <option value="in_progress" {{ ($booking->status ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                        <option value="completed" {{ ($booking->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                                                        <option value="cancelled" {{ ($booking->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="admin_notes{{ $booking->id }}" class="form-label">Admin Notes</label>
                                                    <textarea class="form-control" id="admin_notes{{ $booking->id }}" 
                                                              name="admin_notes" rows="3" 
                                                              placeholder="Add any notes about this booking">{{ $booking->admin_notes ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Update Status</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center p-4">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h5>No Bookings Found</h5>
                <p>No bookings have been made yet.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('superadmin.bookings.create') }}" class="action-btn">
                        <i class="fas fa-plus"></i>Create First Booking
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection