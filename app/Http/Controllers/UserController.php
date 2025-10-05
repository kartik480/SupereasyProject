<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        try {
            $user = request()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to view your profile.');
            }
            
            return view('user.profile', compact('user'));
        } catch (\Exception $e) {
            \Log::error("Profile page error: " . $e->getMessage());
            return redirect()->route('home')->with('error', 'Unable to load profile page.');
        }
    }
}
