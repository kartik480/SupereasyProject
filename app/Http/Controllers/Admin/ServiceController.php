<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
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
            'booking_requirements' => 'nullable|string',
            'duration' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|file|max:2048',
            'image_2' => 'nullable|file|max:2048',
            'image_3' => 'nullable|file|max:2048',
            'image_4' => 'nullable|file|max:2048',
            'features' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'image.max' => 'The main image may not be greater than 2MB.',
            'image_2.max' => 'Additional image 1 may not be greater than 2MB.',
            'image_3.max' => 'Additional image 2 may not be greater than 2MB.',
            'image_4.max' => 'Additional image 3 may not be greater than 2MB.',
        ]);

        // Custom image type validation
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        foreach (['image', 'image_2', 'image_3', 'image_4'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    $validator->errors()->add($field, "The {$field} must be a file of type: jpeg, jpg, png, gif, webp.");
                }
            }
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle image uploads
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        if ($request->hasFile('image_2')) {
            $data['image_2'] = $request->file('image_2')->store('services', 'public');
        }
        if ($request->hasFile('image_3')) {
            $data['image_3'] = $request->file('image_3')->store('services', 'public');
        }
        if ($request->hasFile('image_4')) {
            $data['image_4'] = $request->file('image_4')->store('services', 'public');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        
        // Set category based on subcategory for backward compatibility
        $data['category'] = $data['subcategory'];
        
        // Process subscription plans if provided
        if ($request->has('subscription_plans') && $data['main_category'] === 'monthly_subscription') {
            $data['subscription_plans'] = $request->input('subscription_plans');
        }

        // Handle requirements field - convert string to array if needed
        if (isset($data['requirements']) && is_string($data['requirements'])) {
            $data['requirements'] = array_filter(array_map('trim', explode(',', $data['requirements'])));
        }

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully!');
    }

    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
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
            'booking_requirements' => 'nullable|string',
            'duration' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|file|max:2048',
            'image_2' => 'nullable|file|max:2048',
            'image_3' => 'nullable|file|max:2048',
            'image_4' => 'nullable|file|max:2048',
            'features' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'image.max' => 'The main image may not be greater than 2MB.',
            'image_2.max' => 'Additional image 1 may not be greater than 2MB.',
            'image_3.max' => 'Additional image 2 may not be greater than 2MB.',
            'image_4.max' => 'Additional image 3 may not be greater than 2MB.',
        ]);

        // Custom image type validation
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        foreach (['image', 'image_2', 'image_3', 'image_4'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    $validator->errors()->add($field, "The {$field} must be a file of type: jpeg, jpg, png, gif, webp.");
                }
            }
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle image uploads
        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        if ($request->hasFile('image_2')) {
            if ($service->image_2) {
                Storage::disk('public')->delete($service->image_2);
            }
            $data['image_2'] = $request->file('image_2')->store('services', 'public');
        }
        if ($request->hasFile('image_3')) {
            if ($service->image_3) {
                Storage::disk('public')->delete($service->image_3);
            }
            $data['image_3'] = $request->file('image_3')->store('services', 'public');
        }
        if ($request->hasFile('image_4')) {
            if ($service->image_4) {
                Storage::disk('public')->delete($service->image_4);
            }
            $data['image_4'] = $request->file('image_4')->store('services', 'public');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        
        // Set category based on subcategory for backward compatibility
        $data['category'] = $data['subcategory'];
        
        // Process subscription plans if provided
        if ($request->has('subscription_plans') && $data['main_category'] === 'monthly_subscription') {
            $data['subscription_plans'] = $request->input('subscription_plans');
        }

        // Handle requirements field - convert string to array if needed
        if (isset($data['requirements']) && is_string($data['requirements'])) {
            $data['requirements'] = array_filter(array_map('trim', explode(',', $data['requirements'])));
        }

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        // Delete images
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        if ($service->image_2) {
            Storage::disk('public')->delete($service->image_2);
        }
        if ($service->image_3) {
            Storage::disk('public')->delete($service->image_3);
        }
        if ($service->image_4) {
            Storage::disk('public')->delete($service->image_4);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully!');
    }
}