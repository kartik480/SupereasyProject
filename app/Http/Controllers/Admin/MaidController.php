<?php

namespace App\Http\Controllers\Admin;

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

            $maids = Maid::withCount(['bookings' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            return view('admin.maids.index', compact(
                'maids',
                'totalMaids',
                'activeMaids',
                'availableMaids',
                'busyMaids',
                'verifiedMaids',
                'topRatedMaids'
            ));
        } catch (\Exception $e) {
            Log::error("Error loading maids index: " . $e->getMessage(), [
                'error' => $e->getTraceAsString()
            ]);
            
            // Return default values if there's a database error
            return view('admin.maids.index', [
                'maids' => collect([]),
                'totalMaids' => 0,
                'activeMaids' => 0,
                'availableMaids' => 0,
                'busyMaids' => 0,
                'verifiedMaids' => 0,
                'topRatedMaids' => 0
            ])->with('error', 'Unable to load maid data. Please try again.');
        }
    }

    public function create()
    {
        return view('admin.maids.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:maids,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string',
            'languages' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'service_categories' => 'nullable|string',
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
            $fileFields = ['profile_image'];
            
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('maids/documents', $filename, 'public');
                    $data[$field] = $path;
                }
            }

            // Convert comma-separated strings to arrays for JSON fields
            if ($request->skills) {
                $data['skills'] = array_map('trim', explode(',', $request->skills));
            }
            if ($request->languages) {
                $data['languages'] = array_map('trim', explode(',', $request->languages));
            }
            if ($request->service_areas) {
                $data['service_areas'] = array_map('trim', explode(',', $request->service_areas));
            }

            $maid = Maid::create($data);

            DB::commit();
            return redirect()->route('admin.maids.index')
                ->with('success', 'Maid created successfully with all documents!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating maid: " . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to create maid. Please try again.'])->withInput();
        }
    }

    public function show(Maid $maid)
    {
        $maid->load(['bookings' => function($query) {
            $query->with('service')->orderBy('created_at', 'desc');
        }]);

        $stats = [
            'total_bookings' => $maid->bookings->count(),
            'completed_bookings' => $maid->bookings->where('status', 'completed')->count(),
            'pending_bookings' => $maid->bookings->where('status', 'pending')->count(),
            'confirmed_bookings' => $maid->bookings->where('status', 'confirmed')->count(),
            'in_progress_bookings' => $maid->bookings->where('status', 'in_progress')->count(),
            'cancelled_bookings' => $maid->bookings->where('status', 'cancelled')->count(),
        ];

        $recentBookings = $maid->bookings()->with(['service', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.maids.show', compact('maid', 'stats', 'recentBookings'));
    }

    public function edit(Maid $maid)
    {
        return view('admin.maids.edit', compact('maid'));
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
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string',
            'languages' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'service_categories' => 'nullable|string',
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
            $fileFields = ['profile_image'];
            
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($maid->$field && Storage::disk('public')->exists($maid->$field)) {
                        Storage::disk('public')->delete($maid->$field);
                    }
                    
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('maids/documents', $filename, 'public');
                    $data[$field] = $path;
                }
            }

            // Convert comma-separated strings to arrays for JSON fields
            if ($request->skills) {
                $data['skills'] = array_map('trim', explode(',', $request->skills));
            }
            if ($request->languages) {
                $data['languages'] = array_map('trim', explode(',', $request->languages));
            }
            if ($request->service_areas) {
                $data['service_areas'] = array_map('trim', explode(',', $request->service_areas));
            }

            $maid->update($data);

            DB::commit();
            return redirect()->route('admin.maids.show', $maid)
                ->with('success', 'Maid updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating maid: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update maid. Please try again.'])->withInput();
        }
    }

    public function destroy(Maid $maid)
    {
        DB::beginTransaction();
        try {
            // Check if maid has active bookings
            $activeBookings = $maid->bookings()->whereIn('status', ['pending', 'confirmed', 'in_progress'])->count();
            
            if ($activeBookings > 0) {
                return back()->withErrors(['error' => 'Cannot delete maid with active bookings. Please reassign or complete bookings first.']);
            }

            // Delete profile image if exists
            if ($maid->profile_image && Storage::disk('public')->exists($maid->profile_image)) {
                Storage::disk('public')->delete($maid->profile_image);
            }

            $maid->delete();

            DB::commit();
            return redirect()->route('admin.maids.index')
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

        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($request->booking_id);
            
            // Check if booking is already assigned
            if ($booking->maid_id) {
                return back()->withErrors(['error' => 'Booking is already assigned to another maid.']);
            }

            // Check if maid is available
            if (!$maid->is_available) {
                return back()->withErrors(['error' => 'Maid is not available for new assignments.']);
            }

            // Assign maid to booking
            $booking->update([
                'maid_id' => $maid->id,
                'status' => 'confirmed',
                'allocated_at' => now(),
                'confirmed_at' => now(),
            ]);

            // Mark maid as unavailable
            $maid->update(['is_available' => false]);

            DB::commit();
            return back()->with('success', 'Maid assigned to booking successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error assigning maid to booking: " . $e->getMessage(), [
                'maid_id' => $maid->id,
                'booking_id' => $request->booking_id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to assign maid to booking.']);
        }
    }
}