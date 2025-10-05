<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\Maid;
use App\Models\Offer;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@superdaily.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'phone' => '+1234567890',
            'is_active' => true,
        ]);

        // Create sample customer
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1234567891',
            'address' => '123 Main Street, City, State',
            'is_active' => true,
        ]);

        // Create categories
        $categories = [
            [
                'name' => 'Fresh Groceries',
                'description' => 'Fresh fruits, vegetables, dairy, and more',
                'icon' => 'fas fa-apple-alt',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&h=200&fit=crop',
                'sort_order' => 1,
            ],
            [
                'name' => 'Dairy & Eggs',
                'description' => 'Fresh dairy products and eggs',
                'icon' => 'fas fa-egg',
                'image' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300&h=200&fit=crop',
                'sort_order' => 2,
            ],
            [
                'name' => 'Bakery & Bread',
                'description' => 'Fresh bread and bakery items',
                'icon' => 'fas fa-bread-slice',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&h=200&fit=crop',
                'sort_order' => 3,
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drinks, juices, and beverages',
                'icon' => 'fas fa-glass-whiskey',
                'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=300&h=200&fit=crop',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create sample products
        $products = [
            [
                'name' => 'Fresh Bananas',
                'description' => 'Fresh yellow bananas, perfect for snacking',
                'price' => 40.00,
                'discount_price' => 35.00,
                'category_id' => 1,
                'unit' => 'kg',
                'stock_quantity' => 50,
                'sku' => 'BAN001',
                'is_featured' => true,
                'specifications' => ['Organic', 'Fresh', 'Sweet'],
            ],
            [
                'name' => 'Fresh Milk',
                'description' => 'Fresh cow milk, 1 liter pack',
                'price' => 60.00,
                'category_id' => 2,
                'unit' => 'liter',
                'stock_quantity' => 30,
                'sku' => 'MIL001',
                'is_featured' => true,
                'specifications' => ['Fresh', 'Pasteurized', 'Rich in Calcium'],
            ],
            [
                'name' => 'White Bread',
                'description' => 'Fresh white bread loaf',
                'price' => 25.00,
                'category_id' => 3,
                'unit' => 'loaf',
                'stock_quantity' => 20,
                'sku' => 'BRD001',
                'is_featured' => false,
                'specifications' => ['Fresh', 'Soft', 'White'],
            ],
            [
                'name' => 'Orange Juice',
                'description' => 'Fresh orange juice, 1 liter',
                'price' => 80.00,
                'discount_price' => 70.00,
                'category_id' => 4,
                'unit' => 'liter',
                'stock_quantity' => 25,
                'sku' => 'OJU001',
                'is_featured' => true,
                'specifications' => ['Fresh', 'No Added Sugar', 'Vitamin C'],
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Create sample services
        $services = [
            [
                'name' => 'House Cleaning',
                'description' => 'Complete house cleaning service including dusting, mopping, and sanitizing',
                'price' => 150.00,
                'discount_price' => 120.00,
                'category' => 'Cleaning',
                'duration' => '2 hours',
                'unit' => 'per session',
                'is_featured' => true,
                'features' => 'Deep cleaning, Eco-friendly products, Professional staff',
                'requirements' => ['Access to water', 'Basic cleaning supplies provided'],
            ],
            [
                'name' => 'Garden Maintenance',
                'description' => 'Professional garden care and maintenance service',
                'price' => 200.00,
                'category' => 'Gardening',
                'duration' => '3 hours',
                'unit' => 'per session',
                'is_featured' => false,
                'features' => 'Plant care, Pruning, Fertilizing, Pest control',
                'requirements' => ['Garden access', 'Water source'],
            ],
            [
                'name' => 'Plumbing Service',
                'description' => 'Emergency and regular plumbing repairs',
                'price' => 120.00,
                'category' => 'Plumbing',
                'duration' => '1 hour',
                'unit' => 'per hour',
                'is_featured' => true,
                'features' => '24/7 service, Licensed plumbers, Quality materials',
                'requirements' => ['Access to plumbing area'],
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }

        // Create sample maids
        $maids = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@superdaily.com',
                'phone' => '+1234567892',
                'address' => '456 Oak Street, City, State',
                'bio' => 'Professional cleaner with 5 years of experience',
                'skills' => ['House Cleaning', 'Deep Cleaning', 'Organization'],
                'languages' => ['English', 'Spanish'],
                'hourly_rate' => 25.00,
                'rating' => 4.8,
                'total_ratings' => 120,
                'status' => 'available',
                'is_verified' => true,
                'working_hours' => ['09:00-17:00', 'Monday-Friday'],
                'service_areas' => ['Downtown', 'Midtown', 'Uptown'],
            ],
            [
                'name' => 'Mike Rodriguez',
                'email' => 'mike@superdaily.com',
                'phone' => '+1234567893',
                'address' => '789 Pine Street, City, State',
                'bio' => 'Licensed plumber with 8 years of experience',
                'skills' => ['Plumbing', 'Pipe Repair', 'Installation'],
                'languages' => ['English', 'Portuguese'],
                'hourly_rate' => 35.00,
                'rating' => 4.9,
                'total_ratings' => 85,
                'status' => 'available',
                'is_verified' => true,
                'working_hours' => ['08:00-18:00', 'Monday-Saturday'],
                'service_areas' => ['All Areas'],
            ],
        ];

        foreach ($maids as $maidData) {
            Maid::create($maidData);
        }

        // Create sample offers
        $offers = [
            [
                'title' => 'First Time Customer Discount',
                'description' => 'Get 20% off on your first service booking',
                'type' => 'percentage',
                'discount_value' => 20.00,
                'minimum_order_amount' => 100.00,
                'max_usage_count' => 1000,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'applicable_services' => [1, 2, 3],
            ],
            [
                'title' => 'Fresh Groceries Sale',
                'description' => 'Buy 2 get 1 free on fresh groceries',
                'type' => 'buy_one_get_one',
                'minimum_order_amount' => 50.00,
                'max_usage_count' => 500,
                'valid_from' => now(),
                'valid_until' => now()->addWeeks(2),
                'applicable_categories' => [1, 2],
            ],
        ];

        foreach ($offers as $offerData) {
            Offer::create($offerData);
        }
    }
}