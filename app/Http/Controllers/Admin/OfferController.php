<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function index()
    {
        $totalOffers = Offer::count();
        $activeOffers = Offer::where('is_active', true)->count();
        $expiredOffers = Offer::where('end_date', '<', now())->count();
        $upcomingOffers = Offer::where('start_date', '>', now())->count();
        $currentOffers = Offer::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_active', true)
            ->count();

        $offers = Offer::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.offers.index', compact(
            'offers',
            'totalOffers',
            'activeOffers',
            'expiredOffers',
            'upcomingOffers',
            'currentOffers'
        ));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'code' => 'nullable|string|max:50|unique:offers,code',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('offers', $filename, 'public');
                $data['image'] = $path;
            }

            // Generate code if not provided
            if (empty($data['code'])) {
                $data['code'] = strtoupper(substr($data['title'], 0, 3)) . rand(100, 999);
            }

            $offer = Offer::create($data);

            DB::commit();
            return redirect()->route('admin.offers.index')
                ->with('success', 'Offer created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating offer: " . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to create offer. Please try again.']);
        }
    }

    public function show(Offer $offer)
    {
        return view('admin.offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'code' => 'nullable|string|max:50|unique:offers,code,' . $offer->id,
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($offer->image && Storage::disk('public')->exists($offer->image)) {
                    Storage::disk('public')->delete($offer->image);
                }
                
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('offers', $filename, 'public');
                $data['image'] = $path;
            }

            $offer->update($data);

            DB::commit();
            return redirect()->route('admin.offers.index')
                ->with('success', 'Offer updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating offer: " . $e->getMessage(), [
                'offer_id' => $offer->id,
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update offer. Please try again.']);
        }
    }

    public function destroy(Offer $offer)
    {
        DB::beginTransaction();
        try {
            // Delete image if exists
            if ($offer->image && Storage::disk('public')->exists($offer->image)) {
                Storage::disk('public')->delete($offer->image);
            }

            $offer->delete();

            DB::commit();
            return redirect()->route('admin.offers.index')
                ->with('success', 'Offer deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting offer: " . $e->getMessage(), [
                'offer_id' => $offer->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to delete offer. Please try again.']);
        }
    }

    public function toggleStatus(Request $request, Offer $offer)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        try {
            $offer->update(['is_active' => $request->is_active]);
            
            $status = $request->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Offer {$status} successfully!");
        } catch (\Exception $e) {
            Log::error("Error toggling offer status: " . $e->getMessage(), [
                'offer_id' => $offer->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update offer status.']);
        }
    }
}