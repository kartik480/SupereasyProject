<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Maid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['service', 'maid'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'service_id' => 'required|exists:services,id',
                'booking_date' => 'required|date|after_or_equal:today',
                'booking_time' => 'required|string',
                'address' => 'required|string|max:500',
                'phone' => 'required|string|max:20',
                'special_instructions' => 'nullable|string|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        try {

            $service = Service::findOrFail($request->service_id);

            // Check if service is active
            if (!$service->is_active) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['service' => ['This service is currently not available.']]
                    ], 422);
                }
                return back()->withErrors(['service' => 'This service is currently not available.']);
            }

            // Calculate duration and amounts
            $duration_hours = is_numeric($service->duration) ? (float)$service->duration : 1; // Default to 1 hour if not numeric
            $base_price = is_numeric($service->discount_price) ? (float)$service->discount_price : (is_numeric($service->price) ? (float)$service->price : 0);
            $total_amount = $base_price * $duration_hours;
            $discount_amount = 0; // No discount for now
            $final_amount = $total_amount - $discount_amount;

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id() ?? 1, // Use user ID 1 as fallback for testing
                'service_id' => $service->id,
                'booking_reference' => 'BK' . str_pad(Booking::count() + 1, 6, '0', STR_PAD_LEFT),
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'address' => $request->address,
                'phone' => $request->phone,
                'special_instructions' => $request->special_instructions,
                'duration_hours' => $duration_hours,
                'total_amount' => $total_amount,
                'discount_amount' => $discount_amount,
                'final_amount' => $final_amount,
                'status' => 'pending',
                'customer_notes' => $request->special_instructions, // Map special instructions to customer notes
            ]);

            // Auto-allocate maid based on availability and service requirements
            $this->allocateMaid($booking);

            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service booked successfully! We will contact you soon to confirm the details.',
                    'booking_reference' => $booking->booking_reference,
                    'booking_id' => $booking->id,
                    'redirect_url' => route('bookings.show', $booking)
                ]);
            }

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Service booked successfully! We will contact you soon to confirm the details.');
                
        } catch (\Exception $e) {
            \Log::error("Booking creation error: " . $e->getMessage(), [
                'request' => $request->all(),
                'user_id' => Auth::id(),
                'error' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['error' => ['Failed to create booking. Please try again.']]
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to create booking. Please try again.']);
        }
    }

    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking details.');
        }

        $booking->load(['service', 'maid']);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        // Ensure user can only cancel their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking details.');
        }

        // Only allow cancellation if booking is pending or confirmed
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['booking' => 'This booking cannot be cancelled.']);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Free up maid if allocated
        if ($booking->maid_id) {
            $booking->maid->update(['is_available' => true]);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }

    private function allocateMaid(Booking $booking)
    {
        try {
            // Reload the booking with service relationship
            $booking->load('service');
            
            // Find available maids for the service category
            $availableMaids = Maid::where('is_active', true)
                ->where('is_available', true)
                ->where(function($query) use ($booking) {
                    // Check if service_categories column exists and has data
                    if (Schema::hasColumn('maids', 'service_categories')) {
                        $serviceCategory = $booking->service->category ?? '';
                        if ($serviceCategory) {
                            $query->where('service_categories', 'like', '%' . $serviceCategory . '%')
                                  ->orWhereNull('service_categories')
                                  ->orWhere('service_categories', '');
                        } else {
                            $query->whereRaw('1=1');
                        }
                    } else {
                        // If column doesn't exist, just get any available maid
                        $query->whereRaw('1=1');
                    }
                })
                ->orderBy('rating', 'desc')
                ->orderBy('experience_years', 'desc')
                ->get();

        if ($availableMaids->isNotEmpty()) {
            // Allocate the best available maid
            $maid = $availableMaids->first();
            
            $booking->update([
                'maid_id' => $maid->id,
                'status' => 'confirmed',
                'allocated_at' => now(),
            ]);

            // Mark maid as unavailable
            $maid->update(['is_available' => false]);

            // Log the allocation
            \Log::info("Maid allocated to booking", [
                'booking_id' => $booking->id,
                'maid_id' => $maid->id,
                'service' => $booking->service->name ?? 'N/A',
                'date' => $booking->booking_date ? $booking->booking_date->format('Y-m-d') : 'N/A',
                'time' => $booking->booking_time ?? 'N/A',
            ]);
        } else {
            // No available maids - keep booking as pending
            \Log::info("No available maids for booking", [
                'booking_id' => $booking->id,
                'service_category' => $booking->service->category ?? 'N/A',
                'date' => $booking->booking_date ? $booking->booking_date->format('Y-m-d') : 'N/A',
                'time' => $booking->booking_time ?? 'N/A',
            ]);
        }
        } catch (\Exception $e) {
            // Log the error but don't fail the booking
            \Log::error("Error in maid allocation: " . $e->getMessage(), [
                'booking_id' => $booking->id,
                'error' => $e->getTraceAsString()
            ]);
        }
    }
}