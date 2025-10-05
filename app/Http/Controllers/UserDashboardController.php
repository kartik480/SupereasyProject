<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Product;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user's recent bookings
        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['service', 'maid'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get user's booking statistics
        $bookingStats = [
            'total' => Booking::where('user_id', $user->id)->count(),
            'pending' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'confirmed' => Booking::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];
        
        // Get featured services for quick booking
        $featuredServices = Service::where('is_active', true)
            ->where('is_featured', true)
            ->limit(6)
            ->get();
        
        // Get featured products
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->limit(6)
            ->get();
        
        return view('user.dashboard', compact('user', 'recentBookings', 'bookingStats', 'featuredServices', 'featuredProducts'));
    }
    
    public function bookings()
    {
        $user = Auth::user();
        
        $bookings = Booking::where('user_id', $user->id)
            ->with(['service', 'maid'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('user.bookings', compact('bookings'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
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
        
        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
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
        
        return redirect()->route('user.profile')->with('success', 'Password changed successfully!');
    }
}
