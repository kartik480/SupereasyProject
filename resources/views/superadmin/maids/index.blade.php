@extends('layouts.superadmin')

@section('title', 'Maids Management - SuperAdmin')

@section('page-icon')
<i class="fas fa-users"></i>
@endsection
@section('page-title', 'Maids Management')
@section('page-subtitle', 'Manage your maid workforce and availability')

@section('header-actions')
<a href="{{ route('superadmin.maids.create') }}" class="action-btn">
    <i class="fas fa-user-plus"></i>Add Maid
</a>
@endsection

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon danger">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['total'] }}</div>
        <div class="stat-card-label">Total Maids</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+8% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['pending'] }}</div>
        <div class="stat-card-label">Pending Verification</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>Needs Review
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['approved'] }}</div>
        <div class="stat-card-label">Approved</div>
        <div class="stat-card-change positive">
            <i class="fas fa-arrow-up me-1"></i>+12% from last month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon danger">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['rejected'] }}</div>
        <div class="stat-card-label">Rejected</div>
        <div class="stat-card-change negative">
            <i class="fas fa-arrow-down me-1"></i>Requires Action
        </div>
    </div>
</div>

<!-- Maids Table -->
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-list"></i>All Maids
        </h5>
    </div>
    <div class="card-body p-0">
        @if($maids->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Maid Details</th>
                            <th>Contact</th>
                            <th>Service Category</th>
                            <th>Specialization</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Verification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maids as $maid)
                        <tr>
                            <td>
                                <img src="{{ $maid->profile_image ? asset('storage/' . $maid->profile_image) : 'https://via.placeholder.com/50x50/6c757d/ffffff?text=M' }}" 
                                     alt="{{ $maid->name }}" 
                                     class="product-image">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $maid->name }}</div>
                                <small class="text-muted">{{ $maid->bio ?? 'No bio available' }}</small>
                                <br><small class="text-info">ID: {{ $maid->id }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $maid->phone ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $maid->email }}</small>
                            </td>
                            <td>
                                @php
                                    $serviceCategory = $maid->service_categories;
                                    // Handle both old array format and new string format
                                    if (is_string($serviceCategory)) {
                                        $decoded = json_decode($serviceCategory, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                            $serviceCategory = $decoded;
                                        }
                                    }
                                    
                                    // If it's still an array, take the first one (for backward compatibility)
                                    if (is_array($serviceCategory)) {
                                        $serviceCategory = !empty($serviceCategory) ? $serviceCategory[0] : '';
                                    }
                                @endphp
                                @if(!empty($serviceCategory))
                                    <span class="badge bg-danger text-white">{{ $serviceCategory }}</span>
                                @else
                                    <span class="text-muted small">No category</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $maid->specialization ?? 'General' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">
                                    <i class="fas fa-star text-warning"></i>
                                    {{ number_format($maid->rating ?? 0, 1) }}
                                </div>
                                <small class="text-muted">{{ $maid->bookings_count ?? 0 }} bookings</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if($maid->is_available)
                                        <span class="status-badge active">Available</span>
                                    @else
                                        <span class="status-badge inactive">Busy</span>
                                    @endif
                                    @if($maid->is_active)
                                        <span class="status-badge active">Active</span>
                                    @else
                                        <span class="status-badge inactive">Inactive</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if($maid->verification_status == 'pending')
                                        <span class="status-badge warning">Pending</span>
                                    @elseif($maid->verification_status == 'approved')
                                        <span class="status-badge success">Approved</span>
                                    @elseif($maid->verification_status == 'rejected')
                                        <span class="status-badge danger">Rejected</span>
                                    @else
                                        <span class="status-badge secondary">Unknown</span>
                                    @endif
                                    
                                    @if($maid->verified_at)
                                        <small class="text-muted">
                                            Verified: {{ $maid->verified_at->format('M d, Y') }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('superadmin.maids.show', $maid) }}" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.maids.edit', $maid) }}" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($maid->verification_status == 'pending')
                                        <!-- Approve Button -->
                                        <button type="button" class="btn-action btn-success" 
                                                title="Approve" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal{{ $maid->id }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <!-- Reject Button -->
                                        <button type="button" class="btn-action btn-danger" 
                                                title="Reject" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $maid->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($maid->verification_status == 'approved')
                                        <!-- Reset Verification Button -->
                                        <form action="{{ route('superadmin.maids.reset-verification', $maid) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to reset verification for this maid?')">
                                            @csrf
                                            <button type="submit" class="btn-action btn-warning" title="Reset Verification">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @elseif($maid->verification_status == 'rejected')
                                        <!-- Approve Button -->
                                        <button type="button" class="btn-action btn-success" 
                                                title="Approve" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal{{ $maid->id }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <!-- Reset Verification Button -->
                                        <form action="{{ route('superadmin.maids.reset-verification', $maid) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to reset verification for this maid?')">
                                            @csrf
                                            <button type="submit" class="btn-action btn-warning" title="Reset Verification">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('superadmin.maids.destroy', $maid) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this maid?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center p-4">
                {{ $maids->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h5>No Maids Found</h5>
                <p>Start by adding your first maid to the workforce.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('superadmin.maids.create') }}" class="action-btn">
                        <i class="fas fa-user-plus"></i>Add Your First Maid
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modals for Verification Actions -->
@foreach($maids as $maid)
<!-- Approve Modal -->
<div class="modal fade" id="approveModal{{ $maid->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $maid->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel{{ $maid->id }}">
                    <i class="fas fa-check-circle text-success me-2"></i>Approve Maid
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.maids.approve', $maid) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        You are about to approve <strong>{{ $maid->name }}</strong> for the platform.
                    </div>
                    <div class="mb-3">
                        <label for="verification_notes{{ $maid->id }}" class="form-label">Verification Notes (Optional)</label>
                        <textarea class="form-control" id="verification_notes{{ $maid->id }}" name="verification_notes" rows="3" placeholder="Add any notes about the approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Approve Maid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal{{ $maid->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $maid->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel{{ $maid->id }}">
                    <i class="fas fa-times-circle text-danger me-2"></i>Reject Maid
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.maids.reject', $maid) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        You are about to reject <strong>{{ $maid->name }}</strong> from the platform.
                    </div>
                    <div class="mb-3">
                        <label for="reject_notes{{ $maid->id }}" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_notes{{ $maid->id }}" name="verification_notes" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Reject Maid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
