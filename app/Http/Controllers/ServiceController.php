<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        return view('services.index', compact('services', 'categories'));
    }

    public function show(Service $service)
    {
        // Ensure service is active
        if (!$service->is_active) {
            abort(404, 'Service not found or not available.');
        }

        // Load related services
        $relatedServices = Service::where('category', $service->category)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('services.details', compact('service', 'relatedServices'));
    }

    public function category($category)
    {
        $services = Service::where('category', $category)
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        return view('services.category', compact('services', 'categories', 'category'));
    }
}
