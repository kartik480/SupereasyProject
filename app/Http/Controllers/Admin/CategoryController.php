<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();
        $categoriesWithProducts = Category::whereHas('products')->count();
        $categoriesWithServices = Category::whereHas('services')->count();

        $categories = Category::withCount(['products', 'services'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'categoriesWithProducts',
            'categoriesWithServices'
        ));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = \Str::slug($data['name']);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('categories', $filename, 'public');
                $data['image'] = $path;
            }

            $category = Category::create($data);

            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating category: " . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to create category. Please try again.']);
        }
    }

    public function show(Category $category)
    {
        $category->load(['products', 'services']);
        
        $stats = [
            'total_products' => $category->products->count(),
            'active_products' => $category->products->where('is_active', true)->count(),
            'total_services' => $category->services->count(),
            'active_services' => $category->services->where('is_active', true)->count(),
        ];

        $recentProducts = $category->products()->latest()->limit(5)->get();
        $recentServices = $category->services()->latest()->limit(5)->get();

        return view('admin.categories.show', compact(
            'category',
            'stats',
            'recentProducts',
            'recentServices'
        ));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = \Str::slug($data['name']);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('categories', $filename, 'public');
                $data['image'] = $path;
            }

            $category->update($data);

            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating category: " . $e->getMessage(), [
                'category_id' => $category->id,
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update category. Please try again.']);
        }
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            // Check if category has products or services
            $productCount = $category->products()->count();
            $serviceCount = $category->services()->count();
            
            if ($productCount > 0 || $serviceCount > 0) {
                return back()->withErrors(['error' => "Cannot delete category. It has {$productCount} products and {$serviceCount} services. Please reassign them first."]);
            }

            // Delete image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting category: " . $e->getMessage(), [
                'category_id' => $category->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to delete category. Please try again.']);
        }
    }

    public function toggleStatus(Request $request, Category $category)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        try {
            $category->update(['is_active' => $request->is_active]);
            
            $status = $request->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Category {$status} successfully!");
        } catch (\Exception $e) {
            Log::error("Error toggling category status: " . $e->getMessage(), [
                'category_id' => $category->id,
                'error' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update category status.']);
        }
    }
}