<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(20);
        return view('superadmin.services.index', compact('services'));
    }

    public function create()
    {
        return view('superadmin.services.create');
    }

    public function store(Request $request)
    {
        // Debug: Log all uploaded files info
        foreach (['image', 'image_2', 'image_3', 'image_4'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                \Log::info("{$field} Debug:", [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError()
                ]);
            }
        }

        // Custom validation for image files
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'main_category' => 'required|in:one_time,monthly_subscription',
            'subcategory' => 'required|string|max:100',
            'booking_advance_hours' => 'required|integer|min:1|max:24',
            'subscription_plans' => 'nullable|array',
            'subscription_plans.*.name' => 'required_with:subscription_plans|string|max:100',
            'subscription_plans.*.price' => 'required_with:subscription_plans|numeric|min:0',
            'subscription_plans.*.duration_days' => 'required_with:subscription_plans|integer|min:1',
            'subscription_plans.*.features' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Add custom image validation
        foreach (['image', 'image_2', 'image_3', 'image_4'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $validator->after(function ($validator) use ($file, $field) {
                    if (!$file->isValid()) {
                        $validator->errors()->add($field, "The {$field} file is invalid.");
                    }
                    if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])) {
                        $validator->errors()->add($field, "The {$field} must be a valid image file (JPEG, PNG, JPG, GIF, WEBP).");
                    }
                    if ($file->getSize() > 2048 * 1024) { // 2MB
                        $validator->errors()->add($field, "The {$field} must not be larger than 2MB.");
                    }
                });
            }
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle image uploads
        foreach (['image', 'image_2', 'image_3', 'image_4'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $file->getClientOriginalName();
                $data[$field] = $file->storeAs('services', $filename, 'public');
            }
        }

        // Handle subscription plans
        if ($request->has('subscription_plans')) {
            $data['subscription_plans'] = json_encode($request->subscription_plans);
        }

        Service::create($data);

        return redirect()->route('superadmin.services.index')
            ->with('success', 'Service created successfully!');
    }

    public function show(Service $service)
    {
        return view('superadmin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('superadmin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'main_category' => 'required|in:one_time,monthly_subscription',
            'subcategory' => 'required|string|max:100',
            'booking_advance_hours' => 'required|integer|min:1|max:24',
            'subscription_plans' => 'nullable|array',
            'subscription_plans.*.name' => 'required_with:subscription_plans|string|max:100',
            'subscription_plans.*.price' => 'required_with:subscription_plans|numeric|min:0',
            'subscription_plans.*.duration_days' => 'required_with:subscription_plans|integer|min:1',
            'subscription_plans.*.features' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Add custom image validation
        foreach (['image', 'image_2', 'image_3', 'image_4'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $validator->after(function ($validator) use ($file, $field) {
                    if (!$file->isValid()) {
                        $validator->errors()->add($field, "The {$field} file is invalid.");
                    }
                    if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])) {
                        $validator->errors()->add($field, "The {$field} must be a valid image file (JPEG, PNG, JPG, GIF, WEBP).");
                    }
                    if ($file->getSize() > 2048 * 1024) { // 2MB
                        $validator->errors()->add($field, "The {$field} must not be larger than 2MB.");
                    }
                });
            }
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle image uploads
        foreach (['image', 'image_2', 'image_3', 'image_4'] as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                if ($service->$field && Storage::disk('public')->exists($service->$field)) {
                    Storage::disk('public')->delete($service->$field);
                }
                
                $file = $request->file($field);
                $filename = time() . '_' . $file->getClientOriginalName();
                $data[$field] = $file->storeAs('services', $filename, 'public');
            }
        }

        // Handle subscription plans
        if ($request->has('subscription_plans')) {
            $data['subscription_plans'] = json_encode($request->subscription_plans);
        }

        $service->update($data);

        return redirect()->route('superadmin.services.index')
            ->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        // Delete images
        foreach (['image', 'image_2', 'image_3', 'image_4'] as $field) {
            if ($service->$field && Storage::disk('public')->exists($service->$field)) {
                Storage::disk('public')->delete($service->$field);
            }
        }

        $service->delete();

        return redirect()->route('superadmin.services.index')
            ->with('success', 'Service deleted successfully!');
    }
}
