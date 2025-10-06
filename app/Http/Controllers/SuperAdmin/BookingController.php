<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Maid;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        try {
            // Get bookings with safe loading
            $bookings = Booking::select([
                'id',
                'user_id', 
                'service_id',
                'maid_id',
                'booking_reference',
                'booking_date',
                'booking_time',
                'address',
                'phone',
                'special_instructions',
                'status',
                'total_amount',
                'final_amount',
                'created_at',
                'updated_at'
            ])
            ->with([
                'user:id,name,email,profile_image',
                'service:id,name,category,price,discount_price',
                'maid:id,name,profile_image,rating'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

            // Get statistics safely
            $stats = [
                'total' => Booking::count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'in_progress' => Booking::where('status', 'in_progress')->count(),
                'completed' => Booking::where('status', 'completed')->count(),
                'cancelled' => Booking::where('status', 'cancelled')->count(),
            ];

            return view('superadmin.bookings.index', compact('bookings', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Error loading bookings: ' . $e->getMessage());
            return view('superadmin.bookings.index', [
                'bookings' => collect(),
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'confirmed' => 0,
                    'in_progress' => 0,
                    'completed' => 0,
                    'cancelled' => 0,
                ]
            ]);
        }
    }

    public function create()
    {
        try {
            $services = Service::where('is_active', true)->get();
            $maids = Maid::where('is_active', true)->get();
            $users = \App\Models\User::where('is_active', true)->get();

            return view('superadmin.bookings.create', compact('services', 'maids', 'users'));
        } catch (\Exception $e) {
            \Log::error('Error loading booking create form: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to load booking creation form.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'maid_id' => 'nullable|exists:maids,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'special_instructions' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        try {
            // Generate booking reference
            $bookingReference = 'BK' . date('Ymd') . rand(1000, 9999);

            // Get service details for pricing
            $service = Service::findOrFail($request->service_id);
            $totalAmount = $service->discount_price ?: $service->price;

            $booking = Booking::create([
                'user_id' => $request->user_id,
                'service_id' => $request->service_id,
                'maid_id' => $request->maid_id,
                'booking_reference' => $bookingReference,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'address' => $request->address,
                'phone' => $request->phone,
                'special_instructions' => $request->special_instructions,
                'status' => $request->status,
                'total_amount' => $totalAmount,
                'final_amount' => $totalAmount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('superadmin.bookings.index')
                ->with('success', 'Booking created successfully!');
        } catch (\Exception $e) {
            \Log::error('Error creating booking: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create booking.']);
        }
    }

    public function show(Booking $booking)
    {
        try {
            $booking->load([
                'user:id,name,email,phone,address,profile_image',
                'service:id,name,category,price,discount_price,description',
                'maid:id,name,profile_image,rating,phone'
            ]);

            return view('superadmin.bookings.show', compact('booking'));
        } catch (\Exception $e) {
            \Log::error('Error loading booking: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to load booking details.']);
        }
    }

    public function edit(Booking $booking)
    {
        try {
            $booking->load(['user', 'service', 'maid']);
            $services = Service::where('is_active', true)->get();
            $maids = Maid::where('is_active', true)->get();

            return view('superadmin.bookings.edit', compact('booking', 'services', 'maids'));
        } catch (\Exception $e) {
            \Log::error('Error loading booking for edit: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to load booking for editing.']);
        }
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'maid_id' => 'nullable|exists:maids,id',
            'special_instructions' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $booking->update($request->only([
                'status',
                'maid_id',
                'special_instructions',
                'admin_notes'
            ]));

            return redirect()->route('superadmin.bookings.index')
                ->with('success', 'Booking updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating booking: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update booking.']);
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();
            return redirect()->route('superadmin.bookings.index')
                ->with('success', 'Booking deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting booking: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete booking.']);
        }
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        try {
            $booking->update(['status' => $request->status]);
            return back()->with('success', 'Booking status updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating booking status: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update booking status.']);
        }
    }

    public function assignMaid(Request $request, Booking $booking)
    {
        $request->validate([
            'maid_id' => 'required|exists:maids,id',
        ]);

        try {
            $booking->update([
                'maid_id' => $request->maid_id,
                'status' => 'confirmed',
                'allocated_at' => now()
            ]);

            return back()->with('success', 'Maid assigned successfully!');
        } catch (\Exception $e) {
            \Log::error('Error assigning maid: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to assign maid.']);
        }
    }

    public function confirm(Booking $booking)
    {
        try {
            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now()
            ]);

            return back()->with('success', 'Booking confirmed successfully!');
        } catch (\Exception $e) {
            \Log::error('Error confirming booking: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to confirm booking.']);
        }
    }

    public function start(Booking $booking)
    {
        try {
            $booking->update([
                'status' => 'in_progress',
                'started_at' => now()
            ]);

            return back()->with('success', 'Booking started successfully!');
        } catch (\Exception $e) {
            \Log::error('Error starting booking: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to start booking.']);
        }
    }

    public function complete(Booking $booking)
    {
        try {
            $booking->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return back()->with('success', 'Booking completed successfully!');
        } catch (\Exception $e) {
            \Log::error('Error completing booking: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to complete booking.']);
        }
    }

    public function cancel(Booking $booking)
    {
        try {
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            return back()->with('success', 'Booking cancelled successfully!');
        } catch (\Exception $e) {
            \Log::error('Error cancelling booking: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to cancel booking.']);
        }
    }
}
