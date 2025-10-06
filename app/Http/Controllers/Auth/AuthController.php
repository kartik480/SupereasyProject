<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended('/')->with('success', 'Welcome back! You have successfully logged in.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'role' => 'customer',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Account created successfully! Welcome to SuperDaily.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }


    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function showProfileUpdate()
    {
        $user = Auth::user();
        
        // If no user is authenticated, create a dummy user for testing
        if (!$user) {
            $user = new \App\Models\User();
            $user->id = 1;
            $user->name = 'Test User';
            $user->email = 'test@example.com';
            $user->role = 'user';
            $user->profile_image = null;
            
            // Check if there's a test profile image in session
            if (session('test_profile_image')) {
                $user->profile_image = session('test_profile_image');
            }
        }
        
        return view('auth.profile-update', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // If no user is authenticated, create a dummy user for testing
        if (!$user) {
            $user = new \App\Models\User();
            $user->id = 1;
            $user->name = 'Test User';
            $user->email = 'test@example.com';
            $user->role = 'user';
            $user->profile_image = session('test_profile_image'); // Get current image from session
        }

        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                // Delete old profile image if exists
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                
                // Store new profile image
                $file = $request->file('profile_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('profiles', $filename, 'public');
                
                // Update user's profile image in database
                if ($user->id) {
                    // If user exists in database, update it
                    \App\Models\User::where('id', $user->id)->update(['profile_image' => $path]);
                } else {
                    // For testing, create a session variable to store the image path
                    session(['test_profile_image' => $path]);
                }
                
                // Check if this is an AJAX request
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Profile picture uploaded successfully! File: ' . $filename,
                        'image_path' => asset('storage/' . $path)
                    ]);
                }
                
                return redirect()->route('profile.update.show')->with('success', 'Profile picture uploaded successfully! File: ' . $filename);
                
            } catch (\Exception $e) {
                return back()->withErrors(['profile_image' => 'Failed to upload image. Please try again.']);
            }
        }

        return back()->withErrors(['profile_image' => 'No image selected.']);
    }

    /**
     * Show admin login form
     */
    public function showAdminLoginForm()
    {
        // If user is already authenticated and has admin role, redirect to dashboard
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.admin-login');
    }

    /**
     * Handle admin login
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user has admin privileges
            if (!in_array($user->role, ['admin', 'superadmin'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'You do not have permission to access the admin panel.']);
            }

            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated. Please contact support.']);
            }

            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors(['email' => 'Invalid credentials. Please check your email and password.']);
    }

    /**
     * Handle admin logout
     */
    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login.show')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show admin registration form
     */
    public function showAdminRegisterForm()
    {
        // If user is already authenticated and has admin role, redirect to dashboard
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.admin-register');
    }

    /**
     * Handle admin registration
     */
    public function adminRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'role' => 'required|in:admin,superadmin',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ], [
            'terms.accepted' => 'You must agree to the Terms of Service and Privacy Policy.',
            'role.in' => 'Please select a valid admin role.',
        ]);

        try {
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $request->role,
                'password' => bcrypt($request->password),
                'is_active' => true, // Admin accounts are active by default
            ]);

            // Automatically log in the new admin user
            Auth::login($user);

            return redirect()->route('admin.dashboard')->with('success', 'Admin account created successfully! Welcome to the admin panel, ' . $user->name . '!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to create admin account. Please try again.'])->withInput();
        }
    }
}
