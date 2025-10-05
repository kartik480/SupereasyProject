<?php

namespace App\Http\Controllers\Admin;

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

            return view('admin.bookings.index', compact('bookings', 'stats'));
            
        } catch (\Exception $e) {
            \Log::error("Admin bookings index error: " . $e->getMessage(), [
                'error' => $e->getTraceAsString()
            ]);
            
            // Return empty data on error
            return view('admin.bookings.index', [
                'bookings' => collect([]),
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'confirmed' => 0,
                    'in_progress' => 0,
                    'completed' => 0,
                    'cancelled' => 0,
                ]
            ])->with('error', 'Unable to load bookings. Please try again.');
        }
    }

    public function show(Booking $booking)
    {
        try {
            $booking->load(['user:id,name,email,phone,address', 'service:id,name,category,price', 'maid:id,name,phone,rating']);
            
            // Get available maids safely
            $availableMaids = Maid::select(['id', 'name', 'rating', 'is_available'])
                ->where('is_active', true)
                ->where('is_available', true)
                ->get();

            return view('admin.bookings.show', compact('booking', 'availableMaids'));
            
        } catch (\Exception $e) {
            \Log::error("Admin booking show error: " . $e->getMessage());
            return back()->withErrors(['error' => 'Unable to load booking details.']);
        }
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'maid_id' => 'nullable|exists:maids,id',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $oldStatus = $booking->status;
            $booking->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
            ]);

            // Handle maid allocation
            if ($request->status === 'confirmed' && $request->maid_id) {
                $maid = Maid::find($request->maid_id);
                
                if ($maid && $maid->is_available) {
                    // Free up old maid if exists
                    if ($booking->maid_id) {
                        $oldMaid = Maid::find($booking->maid_id);
                        if ($oldMaid) {
                            $oldMaid->update(['is_available' => true]);
                        }
                    }

                    // Assign new maid
                    $booking->update([
                        'maid_id' => $maid->id,
                        'allocated_at' => now(),
                    ]);

                    // Mark maid as unavailable
                    $maid->update(['is_available' => false]);
                }
            }

            // Handle status-specific actions
            if ($request->status === 'in_progress') {
                $booking->update(['started_at' => now()]);
            } elseif ($request->status === 'completed') {
                $booking->update(['completed_at' => now()]);
                
                // Free up maid
                if ($booking->maid_id) {
                    $maid = Maid::find($booking->maid_id);
                    if ($maid) {
                        $maid->update(['is_available' => true]);
                    }
                }
            } elseif ($request->status === 'cancelled') {
                $booking->update(['cancelled_at' => now()]);
                
                // Free up maid
                if ($booking->maid_id) {
                    $maid = Maid::find($booking->maid_id);
                    if ($maid) {
                        $maid->update(['is_available' => true]);
                    }
                }
            }

            return back()->with('success', 'Booking status updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error("Update booking status error: " . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update booking status.']);
        }
    }

    public function assignMaid(Request $request, Booking $booking)
    {
        $request->validate([
            'maid_id' => 'required|exists:maids,id',
        ]);

        try {
            $maid = Maid::find($request->maid_id);

            if (!$maid->is_available) {
                return back()->withErrors(['maid' => 'Selected maid is not available.']);
            }

            // Free up old maid if exists
            if ($booking->maid_id) {
                $oldMaid = Maid::find($booking->maid_id);
                if ($oldMaid) {
                    $oldMaid->update(['is_available' => true]);
                }
            }

            // Assign new maid
            $booking->update([
                'maid_id' => $maid->id,
                'status' => 'confirmed',
                'allocated_at' => now(),
            ]);

            // Mark maid as unavailable
            $maid->update(['is_available' => false]);

            return back()->with('success', 'Maid assigned successfully!');
            
        } catch (\Exception $e) {
            \Log::error("Assign maid error: " . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to assign maid.']);
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            // Free up maid if allocated
            if ($booking->maid_id) {
                $maid = Maid::find($booking->maid_id);
                if ($maid) {
                    $maid->update(['is_available' => true]);
                }
            }

            $booking->delete();

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking deleted successfully!');
                
        } catch (\Exception $e) {
            \Log::error("Delete booking error: " . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete booking.']);
        }
    }

    // Additional methods that routes expect
    public function create()
    {
        return redirect()->route('admin.bookings.index')
            ->with('info', 'Use the front-end booking system to create new bookings.');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.bookings.index')
            ->with('info', 'Use the front-end booking system to create new bookings.');
    }

    public function edit(Booking $booking)
    {
        return redirect()->route('admin.bookings.show', $booking)
            ->with('info', 'Use the status update option to modify bookings.');
    }

    public function update(Request $request, Booking $booking)
    {
        return redirect()->route('admin.bookings.index')
            ->with('info', 'Use the status update option to modify bookings.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        try {
            $booking->update(['status' => 'cancelled', 'cancelled_at' => now()]);
            
            // Free up maid if allocated
            if ($booking->maid_id) {
                $maid = Maid::find($booking->maid_id);
                if ($maid) {
                    $maid->update(['is_available' => true]);
                }
            }

            return back()->with('success', 'Booking cancelled successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to cancel booking.']);
        }
    }

    public function complete(Request $request, Booking $booking)
    {
        try {
            $booking->update(['status' => 'completed', 'completed_at' => now()]);
            
            // Free up maid if allocated
            if ($booking->maid_id) {
                $maid = Maid::find($booking->maid_id);
                if ($maid) {
                    $maid->update(['is_available' => true]);
                }
            }

            return back()->with('success', 'Booking completed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to complete booking.']);
        }
    }

    public function confirm(Request $request, Booking $booking)
    {
        try {
            $booking->update(['status' => 'confirmed']);
            return back()->with('success', 'Booking confirmed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to confirm booking.']);
        }
    }

    public function start(Request $request, Booking $booking)
    {
        try {
            $booking->update(['status' => 'in_progress', 'started_at' => now()]);
            return back()->with('success', 'Booking started successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to start booking.']);
        }
    }
}