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
        // Redirect to the new SuperAdmin dashboard
        return redirect()->route('superadmin.dashboard');
    }
}
