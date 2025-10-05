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

class SuperAdminDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // System Statistics
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_admins' => User::whereIn('role', ['admin', 'superadmin'])->count(),
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
        
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Revenue Statistics (if applicable)
        $revenueStats = [
            'total_revenue' => Booking::where('status', 'completed')->sum('final_amount'),
            'monthly_revenue' => Booking::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('final_amount'),
            'daily_revenue' => Booking::where('status', 'completed')
                ->whereDate('created_at', now()->toDateString())
                ->sum('final_amount'),
        ];
        
        return view('superadmin.dashboard', compact('user', 'stats', 'recentBookings', 'recentUsers', 'revenueStats'));
    }
    
    public function users()
    {
        $users = User::withCount(['bookings'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('superadmin.users', compact('users'));
    }
    
    public function createUser()
    {
        return view('superadmin.users.create');
    }
    
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,admin,superadmin,maid',
            'address' => 'nullable|string|max:500',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Hash::make($request->password),
            'role' => $request->role,
            'address' => $request->address,
            'is_active' => true,
        ]);
        
        return redirect()->route('superadmin.users')->with('success', 'User created successfully!');
    }
    
    public function editUser(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:customer,admin,superadmin,maid',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        
        $user->update($request->only(['name', 'email', 'phone', 'role', 'address', 'is_active']));
        
        return redirect()->route('superadmin.users')->with('success', 'User updated successfully!');
    }
    
    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }
        
        $user->delete();
        
        return redirect()->route('superadmin.users')->with('success', 'User deleted successfully!');
    }
    
    public function systemSettings()
    {
        return view('superadmin.settings');
    }
    
    public function updateSystemSettings(Request $request)
    {
        // This would typically update system-wide settings
        // For now, we'll just return a success message
        
        return redirect()->route('superadmin.settings')->with('success', 'System settings updated successfully!');
    }
}
