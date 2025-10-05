<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Product;
use App\Models\Category;
use App\Models\Maid;
use App\Models\Offer;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Admin Statistics (limited scope)
        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'total_maids' => User::where('role', 'maid')->count(),
            'total_bookings' => Booking::count(),
            'total_services' => Service::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_offers' => Offer::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_services' => Service::where('is_active', true)->count(),
            'active_products' => Product::where('is_active', true)->count(),
        ];
        
        // Recent Activities
        $recentBookings = Booking::with(['user', 'service', 'maid'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $recentCustomers = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.dashboard', compact('user', 'stats', 'recentBookings', 'recentCustomers'));
    }
    
    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->withCount(['bookings'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.customers', compact('customers'));
    }
    
    public function maids()
    {
        $maids = User::where('role', 'maid')
            ->withCount(['bookings'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.maids', compact('maids'));
    }
    
    public function createMaid()
    {
        return view('admin.maids.create');
    }
    
    public function storeMaid(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'nullable|string|max:500',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Hash::make($request->password),
            'role' => 'maid',
            'address' => $request->address,
            'is_active' => true,
        ]);
        
        return redirect()->route('admin.maids')->with('success', 'Maid created successfully!');
    }
    
    public function editMaid(User $maid)
    {
        if ($maid->role !== 'maid') {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.maids.edit', compact('maid'));
    }
    
    public function updateMaid(Request $request, User $maid)
    {
        if ($maid->role !== 'maid') {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $maid->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        
        $maid->update($request->only(['name', 'email', 'phone', 'address', 'is_active']));
        
        return redirect()->route('admin.maids')->with('success', 'Maid updated successfully!');
    }
    
    public function deleteMaid(User $maid)
    {
        if ($maid->role !== 'maid') {
            abort(403, 'Unauthorized action.');
        }
        
        $maid->delete();
        
        return redirect()->route('admin.maids')->with('success', 'Maid deleted successfully!');
    }
    
    public function reports()
    {
        // Admin can view reports but with limited access
        $bookingStats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];
        
        $serviceStats = [
            'total' => Service::count(),
            'active' => Service::where('is_active', true)->count(),
            'featured' => Service::where('is_featured', true)->count(),
        ];
        
        $productStats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'featured' => Product::where('is_featured', true)->count(),
        ];
        
        return view('admin.reports', compact('bookingStats', 'serviceStats', 'productStats'));
    }
}
