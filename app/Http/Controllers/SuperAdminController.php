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
        // Statistics
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalServices = Service::where('is_active', true)->count();
        $totalRevenue = Booking::where('status', 'completed')->sum('total_amount');

        // Recent activities (simplified)
        $recentActivities = collect([
            (object)[
                'icon' => 'fas fa-user-plus text-success',
                'title' => 'New user registered',
                'description' => 'A new customer joined the platform',
                'created_at' => now()->subHours(1)
            ],
            (object)[
                'icon' => 'fas fa-shopping-cart text-info',
                'title' => 'New booking created',
                'description' => 'Customer booked a service',
                'created_at' => now()->subHours(2)
            ],
            (object)[
                'icon' => 'fas fa-check text-success',
                'title' => 'Booking completed',
                'description' => 'Service successfully delivered',
                'created_at' => now()->subHours(3)
            ],
            (object)[
                'icon' => 'fas fa-user-tie text-primary',
                'title' => 'Maid registered',
                'description' => 'New maid joined the platform',
                'created_at' => now()->subHours(4)
            ],
        ]);

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalBookings',
            'totalServices',
            'totalRevenue',
            'recentActivities'
        ));
    }
}
