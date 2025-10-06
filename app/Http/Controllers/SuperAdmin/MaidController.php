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
                ]
            ]);
        }
    }

    public function create()
    {
        return view('superadmin.maids.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:maids,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'age' => 'required|integer|min:18|max:65',
            'experience_years' => 'required|integer|min:0|max:50',
            'hourly_rate' => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|max:2048',
            'id_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'address_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'background_check' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'skills' => 'nullable|string',
            'languages' => 'nullable|string',
            'availability_schedule' => 'nullable|string',
            'service_categories' => 'nullable|array',
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

            // Handle service categories
            if ($request->has('service_categories')) {
                $data['service_categories'] = json_encode($request->service_categories);
            }

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
            'email' => 'required|email|unique:maids,email,' . $maid->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'age' => 'required|integer|min:18|max:65',
            'experience_years' => 'required|integer|min:0|max:50',
            'hourly_rate' => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|max:2048',
            'id_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'address_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'background_check' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'skills' => 'nullable|string',
            'languages' => 'nullable|string',
            'availability_schedule' => 'nullable|string',
            'service_categories' => 'nullable|array',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'is_verified' => 'boolean',
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
            if ($request->has('service_categories')) {
                $data['service_categories'] = json_encode($request->service_categories);
            }

            $maid->update($data);

            DB::commit();
            return redirect()->route('superadmin.maids.index')
                ->with('success', 'Maid updated successfully!');
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
}
