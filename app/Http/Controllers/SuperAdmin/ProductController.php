<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(20);
        return view('superadmin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('superadmin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_2' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_3' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_4' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle image uploads
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('image_2')) {
            $data['image_2'] = $request->file('image_2')->store('products', 'public');
        }
        if ($request->hasFile('image_3')) {
            $data['image_3'] = $request->file('image_3')->store('products', 'public');
        }
        if ($request->hasFile('image_4')) {
            $data['image_4'] = $request->file('image_4')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('superadmin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('superadmin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('superadmin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_2' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_3' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_4' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle image uploads
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('image_2')) {
            if ($product->image_2) {
                Storage::disk('public')->delete($product->image_2);
            }
            $data['image_2'] = $request->file('image_2')->store('products', 'public');
        }
        if ($request->hasFile('image_3')) {
            if ($product->image_3) {
                Storage::disk('public')->delete($product->image_3);
            }
            $data['image_3'] = $request->file('image_3')->store('products', 'public');
        }
        if ($request->hasFile('image_4')) {
            if ($product->image_4) {
                Storage::disk('public')->delete($product->image_4);
            }
            $data['image_4'] = $request->file('image_4')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('superadmin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete images
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->image_2) {
            Storage::disk('public')->delete($product->image_2);
        }
        if ($product->image_3) {
            Storage::disk('public')->delete($product->image_3);
        }
        if ($product->image_4) {
            Storage::disk('public')->delete($product->image_4);
        }

        $product->delete();

        return redirect()->route('superadmin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
