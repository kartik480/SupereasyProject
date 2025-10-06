<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('admin.login.show')->with('error', 'Please login to access the admin panel.');
        }

        $user = Auth::user();
        
        // Check if user has admin or superadmin role
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'You do not have permission to access the admin panel.');
        }

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('admin.login.show')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        return $next($request);
    }
}
