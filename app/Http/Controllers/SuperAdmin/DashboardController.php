<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;
use App\Models\Maid;
use App\Models\Booking;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Product Statistics
            $totalProducts = Product::count();
            $activeProducts = Product::where('is_active', true)->count();
            $featuredProducts = Product::where('is_featured', true)->count();
            $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();

            // Service Statistics
            $totalServices = Service::count();
            $activeServices = Service::where('is_active', true)->count();
            $featuredServices = Service::where('is_featured', true)->count();
            $serviceCategories = Service::distinct('category')->count();

            // User Statistics
            $totalUsers = User::count();
            $totalCustomers = User::where('role', 'customer')->count();
            $totalAdmins = User::whereIn('role', ['admin', 'superadmin'])->count();
            $totalMaids = Maid::count();

            // Booking Statistics
            $totalBookings = Booking::count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            $completedBookings = Booking::where('status', 'completed')->count();
            $inProgressBookings = Booking::where('status', 'in_progress')->count();

            // Recent Data
            $recentBookings = Booking::with(['user', 'service', 'maid'])
                                    ->latest()
                                    ->take(5)
                                    ->get();

            $recentCustomers = User::where('role', 'customer')
                                  ->latest()
                                  ->take(5)
                                  ->get();

            $recentActivities = [
                ['type' => 'new_user', 'description' => 'New user registered: John Doe', 'time' => '5 mins ago'],
                ['type' => 'new_booking', 'description' => 'New booking for House Cleaning', 'time' => '15 mins ago'],
                ['type' => 'product_update', 'description' => 'Product "Milk" stock updated', 'time' => '1 hour ago'],
                ['type' => 'service_completed', 'description' => 'Service "Plumbing Repair" completed', 'time' => '2 hours ago'],
            ];

            // Prepare stats array
            $stats = [
                'total_customers' => $totalCustomers,
                'total_maids' => $totalMaids,
                'total_bookings' => $totalBookings,
                'pending_bookings' => $pendingBookings,
                'active_services' => $activeServices,
                'total_services' => $totalServices,
                'active_products' => $activeProducts,
                'total_products' => $totalProducts,
                'total_categories' => Category::count(),
            ];

            return view('superadmin.dashboard', compact(
                'stats', 'recentBookings', 'recentCustomers', 'recentActivities'
            ));
        } catch (\Exception $e) {
            // Return default values in case of error
            $stats = [
                'total_customers' => 0,
                'total_maids' => 0,
                'total_bookings' => 0,
                'pending_bookings' => 0,
                'active_services' => 0,
                'total_services' => 0,
                'active_products' => 0,
                'total_products' => 0,
                'total_categories' => 0,
            ];
            
            return view('superadmin.dashboard', [
                'stats' => $stats,
                'recentBookings' => collect(),
                'recentCustomers' => collect(),
                'recentActivities' => []
            ]);
        }
    }

    public function bookingReports()
    {
        return view('superadmin.reports.bookings');
    }

    public function maidReports()
    {
        return view('superadmin.reports.maids');
    }

    public function productReports()
    {
        return view('superadmin.reports.products');
    }
}
