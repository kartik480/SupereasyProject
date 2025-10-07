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

            // Generate unique booking reference
            $bookingReference = $this->generateUniqueBookingReference();

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id() ?? 1, // Use user ID 1 as fallback for testing
                'service_id' => $service->id,
                'booking_reference' => $bookingReference,
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

            // Handle AJAX requests differently
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking successful! We will contact you soon to confirm the details.',
                    'booking_id' => $booking->id,
                    'redirect_url' => route('home') // Redirect to home page
                ]);
            }
            
            // Redirect to home page with success message
            return redirect()->route('home')
                ->with('success', 'Booking successful! We will contact you soon to confirm the details.');
                
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

    public function selectMaid(Booking $booking)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                \Log::warning("Unauthenticated user tried to access maid selection", [
                    'booking_id' => $booking->id,
                    'url' => request()->url()
                ]);
                return redirect()->route('login')->withErrors(['error' => 'Please log in to continue with your booking.']);
            }

            // Ensure user can only access their own bookings
            if ($booking->user_id !== Auth::id()) {
                \Log::warning("User tried to access another user's booking", [
                    'booking_id' => $booking->id,
                    'user_id' => Auth::id(),
                    'booking_user_id' => $booking->user_id
                ]);
                abort(403, 'Unauthorized access to booking details.');
            }

            // Load service relationship with error handling
            $booking->load('service');
            
            // Debug logging
            \Log::info("Booking loaded for maid selection", [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id,
                'service_loaded' => $booking->service ? 'yes' : 'no',
                'service_name' => $booking->service->name ?? 'null'
            ]);
            
            if (!$booking->service) {
                \Log::error("Service not found for booking", ['booking_id' => $booking->id, 'service_id' => $booking->service_id]);
                return redirect()->route('bookings.index')->withErrors(['error' => 'Service not found for this booking.']);
            }

            // Find available maids based on service category and other criteria
            $availableMaids = $this->getAvailableMaids($booking);
            
            // Find unavailable maids for display purposes
            $unavailableMaids = $this->getUnavailableMaids($booking);

            return view('bookings.select-maid', compact('booking', 'availableMaids', 'unavailableMaids'));
            
        } catch (\Exception $e) {
            \Log::error("Error in selectMaid method", [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('bookings.index')->withErrors(['error' => 'Error loading booking details. Please try again.']);
        }
    }

    public function confirmBooking(Request $request, Booking $booking)
    {
        // Ensure user can only confirm their own bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking details.');
        }

        $request->validate([
            'maid_id' => 'required|exists:maids,id',
        ]);

        try {
            $maid = Maid::findOrFail($request->maid_id);

            // Check if maid is still available
            if (!$maid->is_available || !$maid->is_active) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected maid is no longer available. Please choose another maid.'
                    ], 400);
                }
                return back()->withErrors(['maid' => 'Selected maid is no longer available. Please choose another maid.']);
            }

            // Update booking with selected maid
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

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service booked successfully! We will contact you soon to confirm the details.',
                    'redirect_url' => route('bookings.show', $booking)
                ]);
            }

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Service booked successfully! We will contact you soon to confirm the details.');

        } catch (\Exception $e) {
            \Log::error("Booking confirmation error: " . $e->getMessage(), [
                'booking_id' => $booking->id,
                'maid_id' => $request->maid_id,
                'error' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to confirm booking. Please try again.'
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to confirm booking. Please try again.']);
        }
    }

    private function generateUniqueBookingReference()
    {
        do {
            // Generate a booking reference with timestamp to ensure uniqueness
            $reference = 'BK' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Booking::where('booking_reference', $reference)->exists());

        return $reference;
    }

    private function getAvailableMaids(Booking $booking)
    {
        try {
            // Ensure service is loaded
            if (!$booking->service) {
                \Log::error("Booking service not found", ['booking_id' => $booking->id]);
                return collect();
            }

            // Get the service category/subcategory to match with maid service categories
            $serviceCategory = $booking->service->subcategory ?? $booking->service->category ?? '';
            
            \Log::info("Service details for maid matching", [
                'booking_id' => $booking->id,
                'service_id' => $booking->service->id,
                'service_name' => $booking->service->name,
                'service_category' => $booking->service->category,
                'service_subcategory' => $booking->service->subcategory,
                'final_service_category' => $serviceCategory
            ]);
            
            // Parse booking time to check against maid working hours
            $bookingTime = $booking->booking_time; // Format: "09:00", "14:00", etc.
            $bookingHour = (int) substr($bookingTime, 0, 2); // Extract hour as integer
            $bookingMinute = (int) substr($bookingTime, 3, 2); // Extract minute as integer
            $bookingTimeDecimal = $bookingHour + ($bookingMinute / 60); // Convert to decimal hours
            
            // Get service address for area matching
            $serviceAddress = $booking->address ?? '';
            
            \Log::info("Service category, time, and address for maid matching", [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'service_category' => $serviceCategory,
                'service_subcategory' => $booking->service->subcategory,
                'service_main_category' => $booking->service->category,
                'booking_time' => $bookingTime,
                'booking_hour' => $bookingHour,
                'booking_minute' => $bookingMinute,
                'booking_time_decimal' => $bookingTimeDecimal,
                'service_address' => $serviceAddress,
                'booking_address_field' => $booking->address,
                'booking_address_details_field' => $booking->address_details ?? 'null'
            ]);

            // Find available maids that match the service category and working hours
            $availableMaids = Maid::where('is_active', true)
                ->where('is_available', true)
                ->where('verification_status', 'approved')
                ->where(function($query) use ($serviceCategory) {
                    if ($serviceCategory) {
                        // Check if maid's service_categories contains the service category
                        $query->where('service_categories', 'like', '%' . $serviceCategory . '%')
                              ->orWhere('service_categories', 'like', '%' . ucfirst($serviceCategory) . '%')
                              ->orWhere('service_categories', 'like', '%' . strtolower($serviceCategory) . '%');
                    } else {
                        // If no specific category, get all available maids
                        $query->whereRaw('1=1');
                    }
                })
                ->get();
                
            \Log::info("Initial maid query results", [
                'booking_id' => $booking->id,
                'service_category' => $serviceCategory,
                'total_maids_found' => $availableMaids->count(),
                'maid_ids' => $availableMaids->pluck('id')->toArray()
            ]);
            
            // Apply additional filtering
            $availableMaids = $availableMaids->filter(function($maid) use ($bookingHour, $bookingTimeDecimal, $serviceAddress) {
                    // Filter by working hours
                    $isWithinHours = false; // Default to false, must prove availability
                    if ($maid->working_hours) {
                        $workingHours = $maid->working_hours;
                        $startHour = null;
                        $endHour = null;
                        
                        \Log::info("Working hours parsing", [
                            'maid_id' => $maid->id,
                            'working_hours_raw' => $workingHours,
                            'working_hours_type' => gettype($workingHours),
                            'is_string' => is_string($workingHours),
                            'is_array' => is_array($workingHours)
                        ]);
                        
                        // Handle different working hours formats
                        if (is_string($workingHours)) {
                            // Parse string format like "09:00-17:00", "9-17", or "9AM - 1PM"
                            if (strpos($workingHours, ':') !== false) {
                                // Format: "09:00-17:00" - parse with minutes
                                preg_match('/(\d{1,2}):(\d{2})-(\d{1,2}):(\d{2})/', $workingHours, $matches);
                                if (count($matches) >= 5) {
                                    $startHour = (int) $matches[1];
                                    $startMinute = (int) $matches[2];
                                    $endHour = (int) $matches[3];
                                    $endMinute = (int) $matches[4];
                                    
                                    // Convert to decimal hours for more precise matching
                                    $startTimeDecimal = $startHour + ($startMinute / 60);
                                    $endTimeDecimal = $endHour + ($endMinute / 60);
                                }
                            } elseif (preg_match('/(\d{1,2})\s*(AM|PM)?\s*-\s*(\d{1,2})\s*(AM|PM)?/i', $workingHours, $matches)) {
                                // Format: "9AM - 1PM" or "9 - 1"
                                $startHour = (int) $matches[1];
                                $endHour = (int) $matches[3];
                                
                                // Handle AM/PM conversion
                                if (isset($matches[2]) && strtoupper($matches[2]) === 'PM' && $startHour < 12) {
                                    $startHour += 12;
                                }
                                if (isset($matches[4]) && strtoupper($matches[4]) === 'PM' && $endHour < 12) {
                                    $endHour += 12;
                                }
                                
                                $startTimeDecimal = $startHour;
                                $endTimeDecimal = $endHour;
                            } else {
                                // Format: "9-17" or "09-17" - parse as hours only
                                preg_match('/(\d{1,2})-(\d{1,2})/', $workingHours, $matches);
                                if (count($matches) >= 3) {
                                    $startHour = (int) $matches[1];
                                    $endHour = (int) $matches[2];
                                    $startTimeDecimal = $startHour;
                                    $endTimeDecimal = $endHour;
                                }
                            }
                        } elseif (is_array($workingHours)) {
                            // Handle array format
                            if (isset($workingHours['start']) && isset($workingHours['end'])) {
                                $startHour = (int) $workingHours['start'];
                                $endHour = (int) $workingHours['end'];
                                $startTimeDecimal = $startHour;
                                $endTimeDecimal = $endHour;
                            }
                        }
                        
                        // Check if booking time falls within working hours
                        if ($startHour !== null && $endHour !== null) {
                            // Use decimal time for more precise matching if available
                            if (isset($startTimeDecimal) && isset($endTimeDecimal)) {
                                // Handle normal working hours (e.g., 9.5-17.5)
                                if ($startTimeDecimal < $endTimeDecimal) {
                                    $isWithinHours = $bookingTimeDecimal >= $startTimeDecimal && $bookingTimeDecimal < $endTimeDecimal;
                                } 
                                // Handle overnight working hours (e.g., 22.5-6.5)
                                else {
                                    $isWithinHours = $bookingTimeDecimal >= $startTimeDecimal || $bookingTimeDecimal < $endTimeDecimal;
                                }
                            } else {
                                // Fallback to hour-based matching
                                // Handle normal working hours (e.g., 9-17)
                                if ($startHour < $endHour) {
                                    $isWithinHours = $bookingHour >= $startHour && $bookingHour < $endHour;
                                } 
                                // Handle overnight working hours (e.g., 22-6)
                                else {
                                    $isWithinHours = $bookingHour >= $startHour || $bookingHour < $endHour;
                                }
                            }
                            
                            \Log::info("Working hours time check", [
                                'maid_id' => $maid->id,
                                'start_hour' => $startHour,
                                'end_hour' => $endHour,
                                'start_time_decimal' => $startTimeDecimal ?? 'null',
                                'end_time_decimal' => $endTimeDecimal ?? 'null',
                                'booking_hour' => $bookingHour,
                                'booking_time_decimal' => $bookingTimeDecimal,
                                'is_normal_hours' => ($startTimeDecimal ?? $startHour) < ($endTimeDecimal ?? $endHour),
                                'is_within_hours' => $isWithinHours
                            ]);
                        }
                    } else {
                        // If no working hours specified, consider available 24/7
                        $isWithinHours = true;
                    }
                    
                    // Filter by service area
                    $isInServiceArea = false; // Default to false, must prove area match
                    if ($maid->service_areas && !empty($serviceAddress)) {
                        $serviceAreas = $maid->service_areas;
                        
                        \Log::info("Service area matching", [
                            'maid_id' => $maid->id,
                            'service_areas_raw' => $serviceAreas,
                            'service_areas_type' => gettype($serviceAreas),
                            'service_address' => $serviceAddress,
                            'service_address_empty' => empty($serviceAddress)
                        ]);
                        
                        // Convert service areas to lowercase for case-insensitive matching
                        $serviceAreasLower = array_map('strtolower', $serviceAreas);
                        $serviceAddressLower = strtolower($serviceAddress);
                        
                        // Check if any service area matches the service address
                        foreach ($serviceAreasLower as $area) {
                            // Check if the area is contained in the address or vice versa
                            if (strpos($serviceAddressLower, $area) !== false || strpos($area, $serviceAddressLower) !== false) {
                                $isInServiceArea = true;
                                break;
                            }
                        }
                    } else {
                        // If no service areas specified, consider available everywhere
                        $isInServiceArea = true;
                    }
                    
                    \Log::info("Maid filtering check", [
                        'maid_id' => $maid->id,
                        'maid_name' => $maid->name,
                        'working_hours_raw' => $maid->working_hours,
                        'start_hour' => $startHour ?? 'null',
                        'end_hour' => $endHour ?? 'null',
                        'start_time_decimal' => $startTimeDecimal ?? 'null',
                        'end_time_decimal' => $endTimeDecimal ?? 'null',
                        'booking_hour' => $bookingHour,
                        'booking_time_decimal' => $bookingTimeDecimal,
                        'is_within_hours' => $isWithinHours,
                        'service_areas' => $maid->service_areas,
                        'service_address' => $serviceAddress,
                        'is_in_service_area' => $isInServiceArea,
                        'final_match' => $isWithinHours && $isInServiceArea
                    ]);
                    
                    return $isWithinHours && $isInServiceArea;
                })
                ->sortByDesc('rating')
                ->sortByDesc('experience_years')
                ->values();

            \Log::info("Found available maids matching service category, working hours, and service area", [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'service_category' => $serviceCategory,
                'booking_time' => $bookingTime,
                'booking_hour' => $bookingHour,
                'booking_time_decimal' => $bookingTimeDecimal,
                'service_address' => $serviceAddress,
                'maids_count' => $availableMaids->count(),
                'maid_ids' => $availableMaids->pluck('id')->toArray()
            ]);

            return $availableMaids;
        } catch (\Exception $e) {
            \Log::error("Error getting available maids: " . $e->getMessage(), [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id ?? 'null',
                'error' => $e->getTraceAsString()
            ]);
            return collect(); // Return empty collection on error
        }
    }

    private function getUnavailableMaids(Booking $booking)
    {
        try {
            // Ensure service is loaded
            if (!$booking->service) {
                \Log::error("Booking service not found", ['booking_id' => $booking->id]);
                return collect();
            }

            // Get the service category/subcategory to match with maid service categories
            $serviceCategory = $booking->service->subcategory ?? $booking->service->category ?? '';
            
            \Log::info("Service details for unavailable maid matching", [
                'booking_id' => $booking->id,
                'service_id' => $booking->service->id,
                'service_name' => $booking->service->name,
                'service_category' => $booking->service->category,
                'service_subcategory' => $booking->service->subcategory,
                'final_service_category' => $serviceCategory
            ]);

            // Find unavailable maids that match the service category
            $unavailableMaids = Maid::where('is_active', true)
                ->where('is_available', false) // Only unavailable maids
                ->where('verification_status', 'approved')
                ->where(function($query) use ($serviceCategory) {
                    if ($serviceCategory) {
                        // Check if maid's service_categories contains the service category
                        $query->where('service_categories', 'like', '%' . $serviceCategory . '%')
                              ->orWhere('service_categories', 'like', '%' . ucfirst($serviceCategory) . '%')
                              ->orWhere('service_categories', 'like', '%' . strtolower($serviceCategory) . '%');
                    } else {
                        // If no specific category, get all unavailable maids
                        $query->whereRaw('1=1');
                    }
                })
                ->get()
                ->sortByDesc('rating')
                ->sortByDesc('experience_years')
                ->values();

            \Log::info("Found unavailable maids matching service category", [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->name,
                'service_category' => $serviceCategory,
                'maids_count' => $unavailableMaids->count(),
                'maid_ids' => $unavailableMaids->pluck('id')->toArray()
            ]);

            return $unavailableMaids;
        } catch (\Exception $e) {
            \Log::error("Error getting unavailable maids: " . $e->getMessage(), [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id ?? 'null',
                'error' => $e->getTraceAsString()
            ]);
            return collect(); // Return empty collection on error
        }
    }

    public function show(Booking $booking)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            \Log::warning("Unauthenticated user tried to access booking details", [
                'booking_id' => $booking->id,
                'url' => request()->url()
            ]);
            return redirect()->route('login')->withErrors(['error' => 'Please log in to view your booking details.']);
        }

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