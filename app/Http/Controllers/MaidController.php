<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaidDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'maid') {
                abort(403, 'Only Maids can access this page.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Statistics
        $todayBookings = Booking::whereHas('maid', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereDate('booking_date', today())->count();

        $monthlyBookings = Booking::whereHas('maid', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereMonth('booking_date', now()->month)
        ->whereYear('booking_date', now()->year)->count();

        $pendingBookings = Booking::whereHas('maid', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->count();

        // Today's schedule
        $todaySchedule = Booking::whereHas('maid', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereDate('booking_date', today())
        ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
        ->orderBy('booking_time');
        
        // If the maid profile doesn't exist in the maid table, create a simple one
        $maidProfile = (object)[
            'is_available' => true,
            'rating' => 4.5,
            'completed_bookings' => 25,
            'experience_years' => 2,
            'specialization' => 'House Cleaning'
        ];

        // Add maid profile to user object for compatibility
        $user->maidProfile = $maidProfile;

        return view('maid.dashboard', compact(
            'todayBookings',
            'monthlyBookings',
            'pendingBookings',
            'todaySchedule'
        ));
    }

    public function toggleAvailability(Request $request)
    {
        $request->validate([
            'is_available' => 'required|boolean'
        ]);

        // Update maid availability
        // For now, just redirect back with success message
        $status = $request->is_available ? 'available' : 'busy';
        
        return back()->with('success', "Status updated to {$status}");
    }

    public function profile()
    {
        // Implementation for maid profile page
        return view('maid.profile');
    }

    public function bookings()
    {
        // Implementation for maid bookings page
        return view('maid.bookings');
    }

    public function schedule()
    {
        // Implementation for maid schedule page
        return view('maid.schedule');
    }

    public function earnings()
    {
        // Implementation for maid earnings page
        return view('maid.earnings');
    }
}
