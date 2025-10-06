@extends('layouts.superadmin')

@section('title', 'Bookings - SuperAdmin')

@section('page-icon')
<i class="fas fa-calendar-check"></i>
@endsection
@section('page-title', 'Bookings')
@section('page-subtitle', 'Manage customer bookings and appointments')

@section('header-actions')
<a href="{{ route('superadmin.bookings.create') }}" class="action-btn">
    <i class="fas fa-plus"></i>Add Booking
</a>
@endsection

@section('content')
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-calendar-check"></i>All Bookings
        </h5>
    </div>
    <div class="card-body p-0">
        @if($bookings->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Maid</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $booking->customer_name ?? ($booking->user->name ?? 'N/A') }}</div>
                                <small class="text-muted">{{ $booking->customer_phone ?? ($booking->user->phone ?? 'N/A') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->service->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $booking->service->category ?? '' }}</small>
                            </td>
                            <td>
                                @if($booking->maid)
                                    <div class="fw-bold">{{ $booking->maid->name }}</div>
                                    <small class="text-muted">{{ $booking->maid->phone }}</small>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}</div>
                                <small class="text-muted">{{ $booking->booking_time ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="status-badge inactive">Pending</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="status-badge active">Confirmed</span>
                                @elseif($booking->status === 'in_progress')
                                    <span class="status-badge featured">In Progress</span>
                                @elseif($booking->status === 'completed')
                                    <span class="status-badge active">Completed</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="status-badge inactive">Cancelled</span>
                                @else
                                    <span class="status-badge inactive">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">₹{{ number_format($booking->final_amount ?? $booking->amount ?? 0, 2) }}</div>
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
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('superadmin.bookings.confirm', $booking) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-view" title="Confirm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-check"></i>
                <h5>No Bookings Found</h5>
                <p>Customer bookings will appear here once they start making reservations.</p>
                <a href="{{ route('superadmin.bookings.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>Add Booking
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
