<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Service;
use App\Models\MaidWorkHour;

class MaidDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Maid Statistics
        $stats = [
            'total_bookings' => Booking::where('maid_id', $user->id)->count(),
            'pending_bookings' => Booking::where('maid_id', $user->id)->where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('maid_id', $user->id)->where('status', 'confirmed')->count(),
            'in_progress_bookings' => Booking::where('maid_id', $user->id)->where('status', 'in_progress')->count(),
            'completed_bookings' => Booking::where('maid_id', $user->id)->where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('maid_id', $user->id)->where('status', 'cancelled')->count(),
        ];
        
        // Recent Bookings
        $recentBookings = Booking::where('maid_id', $user->id)
            ->with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Today's Schedule
        $todayBookings = Booking::where('maid_id', $user->id)
            ->whereDate('booking_date', today())
            ->with(['user', 'service'])
            ->orderBy('booking_time')
            ->get();
        
        // Work Hours Statistics
        $workHours = MaidWorkHour::where('maid_id', $user->id)
            ->whereMonth('work_date', now()->month)
            ->sum('hours_worked');
        
        $monthlyEarnings = Booking::where('maid_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('final_amount');
        
        return view('maid.dashboard', compact('user', 'stats', 'recentBookings', 'todayBookings', 'workHours', 'monthlyEarnings'));
    }
    
    public function bookings()
    {
        $user = Auth::user();
        
        $bookings = Booking::where('maid_id', $user->id)
            ->with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('maid.bookings', compact('bookings'));
    }
    
    public function schedule()
    {
        $user = Auth::user();
        
        // Get bookings for the next 7 days
        $upcomingBookings = Booking::where('maid_id', $user->id)
            ->where('booking_date', '>=', today())
            ->where('booking_date', '<=', today()->addDays(7))
            ->with(['user', 'service'])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();
        
        return view('maid.schedule', compact('upcomingBookings'));
    }
    
    public function earnings()
    {
        $user = Auth::user();
        
        // Monthly earnings for the last 12 months
        $monthlyEarnings = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $earnings = Booking::where('maid_id', $user->id)
                ->where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('final_amount');
            
            $monthlyEarnings[] = [
                'month' => $month->format('M Y'),
                'earnings' => $earnings,
            ];
        }
        
        // Total earnings
        $totalEarnings = Booking::where('maid_id', $user->id)
            ->where('status', 'completed')
            ->sum('final_amount');
        
        // This month's earnings
        $thisMonthEarnings = Booking::where('maid_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('final_amount');
        
        return view('maid.earnings', compact('monthlyEarnings', 'totalEarnings', 'thisMonthEarnings'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('maid.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        $data = $request->only(['name', 'phone', 'address']);
        
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                \Storage::delete('public/' . $user->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $imagePath;
        }
        
        $user->update($data);
        
        return redirect()->route('maid.profile')->with('success', 'Profile updated successfully!');
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        
        $user->update([
            'password' => \Hash::make($request->password),
        ]);
        
        return redirect()->route('maid.profile')->with('success', 'Password changed successfully!');
    }
    
    public function toggleAvailability(Request $request)
    {
        $user = Auth::user();
        
        // This would typically update a maid's availability status
        // For now, we'll just return a success message
        
        return redirect()->route('maid.dashboard')->with('success', 'Availability status updated successfully!');
    }
}