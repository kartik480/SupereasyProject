<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Product;
use App\Models\Category;
use App\Models\Maid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'superadmin') {
                abort(403, 'Only SuperAdmin can access this page.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Basic Statistics
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalServices = Service::where('is_active', true)->count();
        $totalProducts = Product::where('is_active', true)->count();
        
        // User breakdown
        $totalCustomers = User::where('role', 'user')->count();
        $totalMaids = User::where('role', 'maid')->count();
        $totalAdmins = User::whereIn('role', ['admin', 'superadmin'])->count();
        
        // Booking statistics
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        // Revenue statistics
        $totalRevenue = Booking::where('status', 'completed')->sum('total_amount') ?? 0;
        $monthlyRevenue = Booking::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount') ?? 0;
        $dailyRevenue = Booking::where('status', 'completed')
            ->whereDate('created_at', now()->toDateString())
            ->sum('total_amount') ?? 0;

        // Recent data
        $recentBookings = Booking::with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Prepare data arrays
        $stats = [
            'total_users' => $totalUsers,
            'total_bookings' => $totalBookings,
            'total_services' => $totalServices,
            'total_products' => $totalProducts,
            'total_customers' => $totalCustomers,
            'total_maids' => $totalMaids,
            'total_admins' => $totalAdmins,
            'pending_bookings' => $pendingBookings,
        ];

        $revenueStats = [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'daily_revenue' => $dailyRevenue,
        ];

        return view('superadmin.dashboard', compact(
            'stats',
            'revenueStats',
            'recentBookings',
            'recentUsers'
        ));
    }
}
