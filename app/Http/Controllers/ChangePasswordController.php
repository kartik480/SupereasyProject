<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    /**
     * Show the change password form
     */
    public function showChangePasswordForm()
    {
        try {
            // Try to check if user is authenticated
            $isAuthenticated = Auth::check();
        } catch (\Exception $e) {
            // If database connection fails, show form anyway
            $isAuthenticated = false;
        }
        
        return view('change-password', ['isAuthenticated' => $isAuthenticated]);
    }

    /**
     * Handle the change password request
     */
    public function changePassword(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ], [
                'current_password.required' => 'Current password is required.',
                'new_password.required' => 'New password is required.',
                'new_password.min' => 'New password must be at least 6 characters.',
                'new_password.confirmed' => 'New password confirmation does not match.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Get the authenticated user
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->back()
                    ->with('error', 'You must be logged in to change your password.')
                    ->withInput();
            }
            
            // Check if current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Current password is incorrect.')
                    ->withInput();
            }

            // Update the password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->back()
                ->with('success', 'Password changed successfully!');
                
        } catch (\Exception $e) {
            // If database connection fails, show helpful message
            return redirect()->back()
                ->with('error', 'Database connection failed. Please check your database configuration and try again.')
                ->withInput();
        }
    }
}