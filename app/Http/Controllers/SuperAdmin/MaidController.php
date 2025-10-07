<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Maid;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MaidController extends Controller
{
    public function index()
    {
        try {
            $totalMaids = Maid::count();
            $activeMaids = Maid::where('is_active', true)->count();
            $availableMaids = Maid::where('is_available', true)->count();
            $busyMaids = Maid::where('is_available', false)->count();
            $verifiedMaids = Maid::where('is_verified', true)->count();
            $topRatedMaids = Maid::where('rating', '>=', 4.0)->count();
            
            // New verification stats
            $pendingMaids = Maid::where('verification_status', 'pending')->count();
            $approvedMaids = Maid::where('verification_status', 'approved')->count();
            $rejectedMaids = Maid::where('verification_status', 'rejected')->count();

            $maids = Maid::withCount(['bookings' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            $stats = [
                'total' => $totalMaids,
                'active' => $activeMaids,
                'available' => $availableMaids,
                'busy' => $busyMaids,
                'verified' => $verifiedMaids,
                'top_rated' => $topRatedMaids,
                'pending' => $pendingMaids,
                'approved' => $approvedMaids,
                'rejected' => $rejectedMaids,
            ];

            return view('superadmin.maids.index', compact('maids', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading maids: ' . $e->getMessage());
            return view('superadmin.maids.index', [
                'maids' => collect(),
                'stats' => [
                    'total' => 0,
                    'active' => 0,
                    'available' => 0,
                    'busy' => 0,
                    'verified' => 0,
                    'top_rated' => 0,
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ]
            ]);
        }
    }

    public function create()
    {
        // Get available service categories for dropdown
        $categories = \App\Models\Category::where('is_active', true)->get();
        
        return view('superadmin.maids.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:maids,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string',
            'languages' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'service_categories' => 'required|string|max:255',
            'service_areas' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'working_hours' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'is_verified' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Handle file uploads
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('maids/profiles', $filename, 'public');
                $data['profile_image'] = $path;
            }

            if ($request->hasFile('id_proof')) {
                $file = $request->file('id_proof');
                $filename = time() . '_id_' . $file->getClientOriginalName();
                $path = $file->storeAs('maids/documents', $filename, 'public');
                $data['id_proof'] = $path;
            }

            if ($request->hasFile('address_proof')) {
                $file = $request->file('address_proof');
                $filename = time() . '_address_' . $file->getClientOriginalName();
                $path = $file->storeAs('maids/documents', $filename, 'public');
                $data['address_proof'] = $path;
            }

            if ($request->hasFile('background_check')) {
                $file = $request->file('background_check');
                $filename = time() . '_bg_' . $file->getClientOriginalName();
                $path = $file->storeAs('maids/documents', $filename, 'public');
                $data['background_check'] = $path;
            }

            // Handle comma-separated strings to arrays for JSON fields
            if ($request->skills) {
                $data['skills'] = array_map('trim', explode(',', $request->skills));
            }
            if ($request->languages) {
                $data['languages'] = array_map('trim', explode(',', $request->languages));
            }
            if ($request->service_areas) {
                $data['service_areas'] = array_map('trim', explode(',', $request->service_areas));
            }
            // Handle service_categories as single string
            if ($request->service_categories) {
                $data['service_categories'] = $request->service_categories;
            }

            // Set default verification status to pending
            $data['verification_status'] = 'pending';

            $maid = Maid::create($data);

            DB::commit();
            return redirect()->route('superadmin.maids.index')
                ->with('success', 'Maid created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating maid: " . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to create maid. Please try again.']);
        }
    }

    public function show(Maid $maid)
    {
        try {
            $maid->load(['bookings' => function($query) {
                $query->latest()->limit(10);
            }]);

            $stats = [
                'total_bookings' => $maid->bookings()->count(),
                'completed_bookings' => $maid->bookings()->where('status', 'completed')->count(),
                'pending_bookings' => $maid->bookings()->where('status', 'pending')->count(),
                'in_progress_bookings' => $maid->bookings()->where('status', 'in_progress')->count(),
                'average_rating' => $maid->rating ?? 0,
                'total_earnings' => $maid->bookings()->where('status', 'completed')->sum('final_amount'),
            ];

            return view('superadmin.maids.show', compact('maid', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading maid: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to load maid details.']);
        }
    }

    public function edit(Maid $maid)
    {
        return view('superadmin.maids.edit', compact('maid'));
    }

    public function update(Request $request, Maid $maid)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:maids,email,' . $maid->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'husband_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string',
            'languages' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'service_categories' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'working_hours' => 'nullable|string',
            'service_areas' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Handle file uploads
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($maid->profile_image && Storage::disk('public')->exists($maid->profile_image)) {
                    Storage::disk('public')->delete($maid->profile_image);
                }
                
                $file = $request->file('profile_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('maids/profiles', $filename, 'public');
                $data['profile_image'] = $path;
            }

            if ($request->hasFile('id_proof')) {
                // Delete old file if exists
                if ($maid->id_proof && Storage::disk('public')->exists($maid->id_proof)) {
                    Storage::disk('public')->delete($maid->id_proof);
                }
                
                $file = $request->file('id_proof');
                $filename = time() . '_id_' . $file->getClientOriginalName();
                $path = $file->storeAs('maids/documents', $filename, 'public');
                $data['id_proof'] = $path;
            }

            if ($request->hasFile('address_proof')) {
                // Delete old file if exists
                if ($maid->address_proof && Storage::disk('public')->exists($maid->address_proof)) {
                    Storage::disk('public')->delete($maid->address_proof);
                }
                
                $file = $request->file('address_proof');
                $filename = time() . '_address_' . $file->getClientOriginalName();
                $path = $file->storeAs('maids/documents', $filename, 'public');
                $data['address_proof'] = $path;
            }

            if ($request->hasFile('background_check')) {
                // Delete old file if exists
                if ($maid->background_check && Storage::disk('public')->exists($maid->background_check)) {
                    Storage::disk('public')->delete($maid->background_check);
                }
                
                $file = $request->file('background_check');
                $filename = time() . '_bg_' . $file->getClientOriginalName();
                $path = $file->storeAs('maids/documents', $filename, 'public');
                $data['background_check'] = $path;
            }

            // Handle service categories
            if ($request->has('service_categories') && !empty($request->service_categories)) {
                $data['service_categories'] = $request->service_categories;
            } else {
                $data['service_categories'] = [];
            }

            // Convert comma-separated strings to arrays for JSON fields
            if ($request->skills) {
                $data['skills'] = array_map('trim', explode(',', $request->skills));
            }
            if ($request->languages) {
                $data['languages'] = array_map('trim', explode(',', $request->languages));
            }
            // Handle service_categories as single string
            if ($request->service_categories) {
                $data['service_categories'] = $request->service_categories;
            }

            $maid->update($data);

            DB::commit();
            return redirect()->route('superadmin.maids.index')
                ->with('success', 'Maid updated successfully! Changes are synchronized across all panels.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating maid: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update maid. Please try again.']);
        }
    }

    public function destroy(Maid $maid)
    {
        DB::beginTransaction();
        try {
            // Check if maid has bookings
            $bookingCount = $maid->bookings()->count();
            if ($bookingCount > 0) {
                return back()->withErrors(['error' => "Cannot delete maid. They have {$bookingCount} bookings. Please reassign them first."]);
            }

            // Delete files if exist
            $files = ['profile_image', 'id_proof', 'address_proof', 'background_check'];
            foreach ($files as $file) {
                if ($maid->$file && Storage::disk('public')->exists($maid->$file)) {
                    Storage::disk('public')->delete($maid->$file);
                }
            }

            $maid->delete();

            DB::commit();
            return redirect()->route('superadmin.maids.index')
                ->with('success', 'Maid deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting maid: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to delete maid. Please try again.']);
        }
    }

    public function toggleAvailability(Request $request, Maid $maid)
    {
        $request->validate([
            'is_available' => 'required|boolean',
        ]);

        try {
            $maid->update(['is_available' => $request->is_available]);
            
            $status = $request->is_available ? 'available' : 'unavailable';
            return back()->with('success', "Maid marked as {$status} successfully!");
        } catch (\Exception $e) {
            Log::error("Error toggling maid availability: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update maid availability.']);
        }
    }

    public function assignBooking(Request $request, Maid $maid)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        try {
            $booking = Booking::findOrFail($request->booking_id);
            
            // Check if booking is already assigned
            if ($booking->maid_id) {
                return back()->withErrors(['error' => 'This booking is already assigned to another maid.']);
            }

            $booking->update([
                'maid_id' => $maid->id,
                'status' => 'confirmed',
                'allocated_at' => now()
            ]);

            return back()->with('success', 'Booking assigned to maid successfully!');
        } catch (\Exception $e) {
            Log::error("Error assigning booking to maid: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'booking_id' => $request->booking_id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to assign booking to maid.']);
        }
    }

    public function approveMaid(Request $request, Maid $maid)
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $maid->update([
                'verification_status' => 'approved',
                'verified_at' => now(),
                'verification_notes' => $request->verification_notes,
                'is_verified' => true,
                'is_active' => true,
            ]);

            return back()->with('success', 'Maid approved successfully!');
        } catch (\Exception $e) {
            Log::error("Error approving maid: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to approve maid.']);
        }
    }

    public function rejectMaid(Request $request, Maid $maid)
    {
        $request->validate([
            'verification_notes' => 'required|string|max:1000',
        ]);

        try {
            $maid->update([
                'verification_status' => 'rejected',
                'verified_at' => now(),
                'verification_notes' => $request->verification_notes,
                'is_verified' => false,
                'is_active' => false,
            ]);

            return back()->with('success', 'Maid rejected successfully!');
        } catch (\Exception $e) {
            Log::error("Error rejecting maid: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to reject maid.']);
        }
    }

    public function resetVerification(Maid $maid)
    {
        try {
            $maid->update([
                'verification_status' => 'pending',
                'verified_at' => null,
                'verification_notes' => null,
                'is_verified' => false,
            ]);

            return back()->with('success', 'Maid verification reset successfully!');
        } catch (\Exception $e) {
            Log::error("Error resetting maid verification: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to reset maid verification.']);
        }
    }
}
