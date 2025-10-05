<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        try {
            // Auto-setup database if tables don't exist
            $this->autoSetupDatabase();
            
            // Get all active products (not just featured ones)
            $featuredProducts = Product::active()->with('category')->take(8)->get();
            $featuredServices = Service::active()->take(6)->get();
            $categories = Category::where('is_active', true)->take(8)->get();
            
            // If no products exist, create some sample data
            if ($featuredProducts->isEmpty()) {
                $this->createSampleData();
                $featuredProducts = Product::active()->with('category')->take(8)->get();
            }
            
            if ($featuredServices->isEmpty()) {
                $featuredServices = Service::active()->take(6)->get();
            }
            
            if ($categories->isEmpty()) {
                $categories = Category::where('is_active', true)->take(8)->get();
            }
            
        } catch (\Exception $e) {
            // If database tables don't exist, use sample data
            $featuredProducts = $this->getSampleProducts();
            $featuredServices = $this->getSampleServices();
            $categories = $this->getSampleCategories();
        }
        
        return view('home', compact('featuredProducts', 'featuredServices', 'categories'));
    }
    
    private function autoSetupDatabase()
    {
        try {
            // Create products table if it doesn't exist
            if (!\Illuminate\Support\Facades\Schema::hasTable('products')) {
                \Illuminate\Support\Facades\DB::statement('CREATE TABLE IF NOT EXISTS `products` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `description` text NOT NULL,
                    `price` decimal(10,2) NOT NULL,
                    `image` varchar(255) DEFAULT NULL,
                    `category_id` bigint(20) unsigned NOT NULL,
                    `is_featured` tinyint(1) NOT NULL DEFAULT 0,
                    `is_active` tinyint(1) NOT NULL DEFAULT 1,
                    `stock_quantity` int(11) NOT NULL DEFAULT 0,
                    `unit` varchar(255) NOT NULL,
                    `discount_price` decimal(10,2) DEFAULT NULL,
                    `sku` varchar(255) DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `products_sku_unique` (`sku`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            }
            
            // Create services table if it doesn't exist
            if (!\Illuminate\Support\Facades\Schema::hasTable('services')) {
                \Illuminate\Support\Facades\DB::statement('CREATE TABLE IF NOT EXISTS `services` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `description` text NOT NULL,
                    `price` decimal(10,2) NOT NULL,
                    `image` varchar(255) DEFAULT NULL,
                    `category` varchar(255) NOT NULL,
                    `is_featured` tinyint(1) NOT NULL DEFAULT 0,
                    `is_active` tinyint(1) NOT NULL DEFAULT 1,
                    `duration` varchar(255) NOT NULL,
                    `unit` varchar(255) NOT NULL,
                    `discount_price` decimal(10,2) DEFAULT NULL,
                    `features` text DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            }
            
            // Update categories table if it exists
            if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                try {
                    \Illuminate\Support\Facades\DB::statement('ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1');
                } catch (\Exception $e) {}
                try {
                    \Illuminate\Support\Facades\DB::statement('ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `image` varchar(255) DEFAULT NULL');
                } catch (\Exception $e) {}
                try {
                    \Illuminate\Support\Facades\DB::statement('ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `icon` varchar(255) DEFAULT NULL');
                } catch (\Exception $e) {}
            }
            
        } catch (\Exception $e) {
            // Ignore errors during auto-setup
        }
    }
    
    private function createSampleData()
    {
        try {
            // Create default categories if they don't exist
            $categories = [
                [
                    'name' => 'Fresh Groceries',
                    'description' => 'Fresh fruits, vegetables, dairy, and more',
                    'icon' => 'fas fa-apple-alt',
                    'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&h=200&fit=crop',
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Bakery & Snacks',
                    'description' => 'Fresh bread, pastries, and delicious snacks',
                    'icon' => 'fas fa-bread-slice',
                    'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&h=200&fit=crop',
                    'is_active' => true,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            
            foreach ($categories as $categoryData) {
                \App\Models\Category::firstOrCreate(
                    ['name' => $categoryData['name']],
                    $categoryData
                );
            }
            
            // Create sample products if none exist
            $freshGroceriesCategory = \App\Models\Category::where('name', 'Fresh Groceries')->first();
            $bakeryCategory = \App\Models\Category::where('name', 'Bakery & Snacks')->first();
            
            if ($freshGroceriesCategory) {
                $sampleProducts = [
                    [
                        'name' => 'Fresh Apples',
                        'description' => 'Crisp and juicy red apples, perfect for snacking or baking',
                        'price' => 2.99,
                        'discount_price' => 2.49,
                        'image' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=300&h=200&fit=crop',
                        'category_id' => $freshGroceriesCategory->id,
                        'stock_quantity' => 50,
                        'unit' => 'per kg',
                        'sku' => 'APP001',
                        'is_featured' => true,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'name' => 'Organic Milk',
                        'description' => 'Fresh organic whole milk from grass-fed cows',
                        'price' => 3.99,
                        'discount_price' => null,
                        'image' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300&h=200&fit=crop',
                        'category_id' => $freshGroceriesCategory->id,
                        'stock_quantity' => 30,
                        'unit' => 'per liter',
                        'sku' => 'MIL001',
                        'is_featured' => true,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ];
                
                foreach ($sampleProducts as $productData) {
                    \App\Models\Product::firstOrCreate(
                        ['sku' => $productData['sku']],
                        $productData
                    );
                }
            }
            
            // Create sample services if none exist
            $sampleServices = [
                [
                    'name' => 'Home Maid Service',
                    'description' => 'Professional housekeeping service for your home',
                    'price' => 2999.00,
                    'discount_price' => 2499.00,
                    'image' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop',
                    'image_2' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=200&fit=crop',
                    'image_3' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=200&fit=crop',
                    'image_4' => 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=300&h=200&fit=crop',
                    'category' => 'Home Maid',
                    'duration' => '4 hours',
                    'unit' => 'per session',
                    'features' => 'Daily cleaning, Kitchen maintenance, Living area upkeep',
                    'is_featured' => true,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'One Time Deep Cleaning',
                    'description' => 'Complete deep cleaning for special occasions',
                    'price' => 4999.00,
                    'discount_price' => 3999.00,
                    'image' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=200&fit=crop',
                    'image_2' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop',
                    'image_3' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=200&fit=crop',
                    'image_4' => 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=300&h=200&fit=crop',
                    'category' => 'One Time Cleaning',
                    'duration' => '6 hours',
                    'unit' => 'per session',
                    'features' => 'Deep cleaning, Eco-friendly products, Insured service',
                    'is_featured' => true,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Washroom Cleaning',
                    'description' => 'Specialized bathroom and toilet cleaning service',
                    'price' => 1999.00,
                    'discount_price' => 1499.00,
                    'image' => 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=300&h=200&fit=crop',
                    'image_2' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=200&fit=crop',
                    'image_3' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop',
                    'image_4' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=200&fit=crop',
                    'category' => 'Washroom Cleaning',
                    'duration' => '2 hours',
                    'unit' => 'per session',
                    'features' => 'Sanitization, Tile cleaning, Fixture polishing',
                    'is_featured' => true,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Car Cleaning Service',
                    'description' => 'Professional car wash and detailing service',
                    'price' => 999.00,
                    'discount_price' => 799.00,
                    'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=200&fit=crop',
                    'image_2' => 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=300&h=200&fit=crop',
                    'image_3' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=200&fit=crop',
                    'image_4' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop',
                    'category' => 'Car Cleaning',
                    'duration' => '1 hour',
                    'unit' => 'per session',
                    'features' => 'Exterior wash, Interior cleaning, Tire shine',
                    'is_featured' => true,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            
            foreach ($sampleServices as $serviceData) {
                \App\Models\Service::firstOrCreate(
                    ['name' => $serviceData['name']],
                    $serviceData
                );
            }
            
        } catch (\Exception $e) {
            // Ignore errors when creating sample data
        }
    }

    private function getSampleProducts()
    {
        return collect([
            (object) [
                'id' => 1,
                'name' => 'Fresh Apples',
                'description' => 'Crisp and juicy red apples',
                'price' => 2.99,
                'discount_price' => 2.49,
                'image_url' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=300&h=200&fit=crop',
                'category' => (object) ['name' => 'Fruits'],
                'unit' => 'per kg'
            ],
            (object) [
                'id' => 2,
                'name' => 'Organic Milk',
                'description' => 'Fresh organic whole milk',
                'price' => 3.99,
                'discount_price' => null,
                'image_url' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300&h=200&fit=crop',
                'category' => (object) ['name' => 'Dairy'],
                'unit' => 'per liter'
            ],
            (object) [
                'id' => 3,
                'name' => 'Whole Wheat Bread',
                'description' => 'Freshly baked whole wheat bread',
                'price' => 2.49,
                'discount_price' => 1.99,
                'image_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&h=200&fit=crop',
                'category' => (object) ['name' => 'Bakery'],
                'unit' => 'per loaf'
            ],
            (object) [
                'id' => 4,
                'name' => 'Fresh Spinach',
                'description' => 'Organic fresh spinach leaves',
                'price' => 1.99,
                'discount_price' => null,
                'image_url' => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=300&h=200&fit=crop',
                'category' => (object) ['name' => 'Vegetables'],
                'unit' => 'per bunch'
            ],
        ]);
    }

    private function getSampleServices()
    {
        return collect([
            (object) [
                'id' => 1,
                'name' => 'Home Maid Service',
                'description' => 'Professional housekeeping service for your home',
                'price' => 2999.00,
                'discount_price' => 2499.00,
                'image_url' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop',
                'image_2_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=200&fit=crop',
                'image_3_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=200&fit=crop',
                'image_4_url' => 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=300&h=200&fit=crop',
                'duration' => '4 hours',
                'features' => ['Daily cleaning', 'Kitchen maintenance', 'Living area upkeep']
            ],
            (object) [
                'id' => 2,
                'name' => 'One Time Deep Cleaning',
                'description' => 'Complete deep cleaning for special occasions',
                'price' => 4999.00,
                'discount_price' => 3999.00,
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=200&fit=crop',
                'image_2_url' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop',
                'image_3_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=200&fit=crop',
                'image_4_url' => 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=300&h=200&fit=crop',
                'duration' => '6 hours',
                'features' => ['Deep cleaning', 'Eco-friendly products', 'Insured service']
            ],
            (object) [
                'id' => 3,
                'name' => 'Washroom Cleaning',
                'description' => 'Specialized bathroom and toilet cleaning service',
                'price' => 1999.00,
                'discount_price' => 1499.00,
                'image_url' => 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=300&h=200&fit=crop',
                'image_2_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=200&fit=crop',
                'image_3_url' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop',
                'image_4_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=200&fit=crop',
                'duration' => '2 hours',
                'features' => ['Sanitization', 'Tile cleaning', 'Fixture polishing']
            ],
            (object) [
                'id' => 4,
                'name' => 'Car Cleaning Service',
                'description' => 'Professional car wash and detailing service',
                'price' => 999.00,
                'discount_price' => 799.00,
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=200&fit=crop',
                'image_2_url' => 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=300&h=200&fit=crop',
                'image_3_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=200&fit=crop',
                'image_4_url' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop',
                'duration' => '1 hour',
                'features' => ['Exterior wash', 'Interior cleaning', 'Tire shine']
            ],
        ]);
    }

    private function getSampleCategories()
    {
        return collect([
            (object) [
                'id' => 1,
                'name' => 'Fresh Groceries',
                'description' => 'Fresh fruits, vegetables, dairy, and more',
                'icon' => 'fas fa-apple-alt',
                'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&h=200&fit=crop'
            ],
            (object) [
                'id' => 2,
                'name' => 'Bakery & Snacks',
                'description' => 'Fresh bread, pastries, and delicious snacks',
                'icon' => 'fas fa-bread-slice',
                'image_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&h=200&fit=crop'
            ],
            (object) [
                'id' => 3,
                'name' => 'Beverages',
                'description' => 'Soft drinks, juices, and healthy beverages',
                'icon' => 'fas fa-wine-bottle',
                'image_url' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=300&h=200&fit=crop'
            ],
            (object) [
                'id' => 4,
                'name' => 'Home Essentials',
                'description' => 'Cleaning supplies, toiletries, and more',
                'icon' => 'fas fa-home',
                'image_url' => 'https://images.unsplash.com/photo-1581578731548-c6d0f3e63819?w=300&h=200&fit=crop'
            ],
        ]);
    }
}
