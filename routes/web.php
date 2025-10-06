<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MaidController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\BulkUploadController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\MaidDashboardController;
use App\Http\Controllers\ChangePasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::get('/register', [App\Http\Controllers\Auth\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

// Admin Authentication Routes
Route::get('/admin/login', [App\Http\Controllers\Auth\AuthController::class, 'showAdminLoginForm'])->name('admin.login.show');
Route::post('/admin/login', [App\Http\Controllers\Auth\AuthController::class, 'adminLogin'])->name('admin.login');
Route::get('/admin/register', [App\Http\Controllers\Auth\AuthController::class, 'showAdminRegisterForm'])->name('admin.register.show');
Route::post('/admin/register', [App\Http\Controllers\Auth\AuthController::class, 'adminRegister'])->name('admin.register');
Route::post('/admin/logout', [App\Http\Controllers\Auth\AuthController::class, 'adminLogout'])->name('admin.logout');

// SuperAdmin Authentication Routes
Route::get('/superadmin/login', [App\Http\Controllers\Auth\AuthController::class, 'showSuperAdminLoginForm'])->name('superadmin.login.show');
Route::post('/superadmin/login', [App\Http\Controllers\Auth\AuthController::class, 'superAdminLogin'])->name('superadmin.login');
Route::get('/superadmin/register', [App\Http\Controllers\Auth\AuthController::class, 'showSuperAdminRegisterForm'])->name('superadmin.register.show');
Route::post('/superadmin/register', [App\Http\Controllers\Auth\AuthController::class, 'superAdminRegister'])->name('superadmin.register');
Route::post('/superadmin/logout', [App\Http\Controllers\Auth\AuthController::class, 'superAdminLogout'])->name('superadmin.logout');

// Redirect old /profile to new /my-profile (outside auth middleware)
Route::get('/profile', function () {
    if (Auth::check()) {
        return redirect()->route('user.profile');
    } else {
        return redirect()->route('login');
    }
});

// Change Password Routes (Temporarily without auth to test)
Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('change-password');
Route::post('/change-password', [ChangePasswordController::class, 'changePassword']);

// Product browsing routes
Route::get('/products', [FrontendProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [FrontendProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [FrontendProductController::class, 'category'])->name('products.category');

// Service browsing routes
Route::get('/services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/category/{category}', [App\Http\Controllers\ServiceController::class, 'category'])->name('services.category');
Route::get('/services/{service}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');

// User profile routes
Route::get('/my-profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
Route::put('/profile/update', [App\Http\Controllers\Auth\AuthController::class, 'updateProfile'])->name('profile.update');

// Booking routes (Temporarily removing auth middleware for testing)
Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings', [App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}/cancel', [App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Admin Routes (Protected by admin middleware)
Route::prefix('admin')->middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Categories Management
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    
    // Products Management
    Route::resource('products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
    
    // Bulk Upload Routes
    Route::get('/products/bulk-upload', [BulkUploadController::class, 'show'])->name('admin.products.bulk-upload');
    Route::post('/products/bulk-upload', [BulkUploadController::class, 'upload'])->name('admin.products.bulk-upload.store');
    Route::get('/products/download-template', [BulkUploadController::class, 'downloadTemplate'])->name('admin.products.download-template');
    
    // Services Management
    Route::resource('services', ServiceController::class)->names([
        'index' => 'admin.services.index',
        'create' => 'admin.services.create',
        'store' => 'admin.services.store',
        'show' => 'admin.services.show',
        'edit' => 'admin.services.edit',
        'update' => 'admin.services.update',
        'destroy' => 'admin.services.destroy',
    ]);
    
    // Bookings Management
    Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class)->names([
        'index' => 'admin.bookings.index',
        'show' => 'admin.bookings.show',
        'destroy' => 'admin.bookings.destroy',
    ]);
    Route::patch('/bookings/{booking}/update-status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('admin.bookings.update-status');
    Route::post('/bookings/{booking}/assign-maid', [App\Http\Controllers\Admin\BookingController::class, 'assignMaid'])->name('admin.bookings.assign-maid');
    
    // Offers Management
    Route::resource('offers', App\Http\Controllers\Admin\OfferController::class)->names([
        'index' => 'admin.offers.index',
        'create' => 'admin.offers.create',
        'store' => 'admin.offers.store',
        'show' => 'admin.offers.show',
        'edit' => 'admin.offers.edit',
        'update' => 'admin.offers.update',
        'destroy' => 'admin.offers.destroy',
    ]);
    Route::patch('/offers/{offer}/toggle-status', [App\Http\Controllers\Admin\OfferController::class, 'toggleStatus'])->name('admin.offers.toggle-status');
    
    // Categories Management
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    Route::patch('/categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle-status');
    
    // Maids Management
    Route::resource('maids', App\Http\Controllers\Admin\MaidController::class)->names([
        'index' => 'admin.maids.index',
        'create' => 'admin.maids.create',
        'store' => 'admin.maids.store',
        'show' => 'admin.maids.show',
        'edit' => 'admin.maids.edit',
        'update' => 'admin.maids.update',
        'destroy' => 'admin.maids.destroy',
    ]);
    Route::patch('/maids/{maid}/toggle-availability', [App\Http\Controllers\Admin\MaidController::class, 'toggleAvailability'])->name('admin.maids.toggle-availability');
    Route::post('/maids/{maid}/assign-booking', [App\Http\Controllers\Admin\MaidController::class, 'assignBooking'])->name('admin.maids.assign-booking');
    
    // Bookings Management
    Route::resource('bookings', BookingController::class)->names([
        'index' => 'admin.bookings.index',
        'create' => 'admin.bookings.create',
        'store' => 'admin.bookings.store',
        'show' => 'admin.bookings.show',
        'edit' => 'admin.bookings.edit',
        'update' => 'admin.bookings.update',
        'destroy' => 'admin.bookings.destroy',
    ]);
    
    // Booking Status Updates
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('admin.bookings.confirm');
    Route::post('/bookings/{booking}/start', [BookingController::class, 'start'])->name('admin.bookings.start');
    Route::post('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('admin.bookings.complete');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('admin.bookings.cancel');
    
    
    // Reports and Analytics
    Route::get('/reports/bookings', [DashboardController::class, 'bookingReports'])->name('admin.reports.bookings');
    Route::get('/reports/maids', [DashboardController::class, 'maidReports'])->name('admin.reports.maids');
    Route::get('/reports/products', [DashboardController::class, 'productReports'])->name('admin.reports.products');
});

// SuperAdmin Routes (Protected by admin middleware)
Route::prefix('superadmin')->middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('superadmin.dashboard');
    
    // Categories Management
    Route::resource('categories', App\Http\Controllers\SuperAdmin\CategoryController::class)->names([
        'index' => 'superadmin.categories.index',
        'create' => 'superadmin.categories.create',
        'store' => 'superadmin.categories.store',
        'show' => 'superadmin.categories.show',
        'edit' => 'superadmin.categories.edit',
        'update' => 'superadmin.categories.update',
        'destroy' => 'superadmin.categories.destroy',
    ]);
    
    // Products Management
    Route::resource('products', App\Http\Controllers\SuperAdmin\ProductController::class)->names([
        'index' => 'superadmin.products.index',
        'create' => 'superadmin.products.create',
        'store' => 'superadmin.products.store',
        'show' => 'superadmin.products.show',
        'edit' => 'superadmin.products.edit',
        'update' => 'superadmin.products.update',
        'destroy' => 'superadmin.products.destroy',
    ]);
    
    // Bulk Upload for Products
    Route::get('/products/bulk-upload', [App\Http\Controllers\SuperAdmin\BulkUploadController::class, 'show'])->name('superadmin.products.bulk-upload');
    Route::post('/products/bulk-upload', [App\Http\Controllers\SuperAdmin\BulkUploadController::class, 'upload'])->name('superadmin.products.bulk-upload.store');
    Route::get('/products/download-template', [App\Http\Controllers\SuperAdmin\BulkUploadController::class, 'downloadTemplate'])->name('superadmin.products.download-template');
    
    // Services Management
    Route::resource('services', App\Http\Controllers\SuperAdmin\ServiceController::class)->names([
        'index' => 'superadmin.services.index',
        'create' => 'superadmin.services.create',
        'store' => 'superadmin.services.store',
        'show' => 'superadmin.services.show',
        'edit' => 'superadmin.services.edit',
        'update' => 'superadmin.services.update',
        'destroy' => 'superadmin.services.destroy',
    ]);
    
    // Bookings Management
    Route::resource('bookings', App\Http\Controllers\SuperAdmin\BookingController::class)->names([
        'index' => 'superadmin.bookings.index',
        'create' => 'superadmin.bookings.create',
        'store' => 'superadmin.bookings.store',
        'show' => 'superadmin.bookings.show',
        'edit' => 'superadmin.bookings.edit',
        'update' => 'superadmin.bookings.update',
        'destroy' => 'superadmin.bookings.destroy',
    ]);
    Route::patch('/bookings/{booking}/update-status', [App\Http\Controllers\SuperAdmin\BookingController::class, 'updateStatus'])->name('superadmin.bookings.update-status');
    Route::post('/bookings/{booking}/assign-maid', [App\Http\Controllers\SuperAdmin\BookingController::class, 'assignMaid'])->name('superadmin.bookings.assign-maid');
    
    // Offers Management
    Route::resource('offers', App\Http\Controllers\SuperAdmin\OfferController::class)->names([
        'index' => 'superadmin.offers.index',
        'create' => 'superadmin.offers.create',
        'store' => 'superadmin.offers.store',
        'show' => 'superadmin.offers.show',
        'edit' => 'superadmin.offers.edit',
        'update' => 'superadmin.offers.update',
        'destroy' => 'superadmin.offers.destroy',
    ]);
    Route::patch('/offers/{offer}/toggle-status', [App\Http\Controllers\SuperAdmin\OfferController::class, 'toggleStatus'])->name('superadmin.offers.toggle-status');
    
    // Maids Management
    Route::resource('maids', App\Http\Controllers\SuperAdmin\MaidController::class)->names([
        'index' => 'superadmin.maids.index',
        'create' => 'superadmin.maids.create',
        'store' => 'superadmin.maids.store',
        'show' => 'superadmin.maids.show',
        'edit' => 'superadmin.maids.edit',
        'update' => 'superadmin.maids.update',
        'destroy' => 'superadmin.maids.destroy',
    ]);
    Route::patch('/maids/{maid}/toggle-availability', [App\Http\Controllers\SuperAdmin\MaidController::class, 'toggleAvailability'])->name('superadmin.maids.toggle-availability');
    Route::post('/maids/{maid}/assign-booking', [App\Http\Controllers\SuperAdmin\MaidController::class, 'assignBooking'])->name('superadmin.maids.assign-booking');
    
    // Booking Status Management
    Route::post('/bookings/{booking}/confirm', [App\Http\Controllers\SuperAdmin\BookingController::class, 'confirm'])->name('superadmin.bookings.confirm');
    Route::post('/bookings/{booking}/start', [App\Http\Controllers\SuperAdmin\BookingController::class, 'start'])->name('superadmin.bookings.start');
    Route::post('/bookings/{booking}/complete', [App\Http\Controllers\SuperAdmin\BookingController::class, 'complete'])->name('superadmin.bookings.complete');
    Route::post('/bookings/{booking}/cancel', [App\Http\Controllers\SuperAdmin\BookingController::class, 'cancel'])->name('superadmin.bookings.cancel');
    
    // Reports
    Route::get('/reports/bookings', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'bookingReports'])->name('superadmin.reports.bookings');
    Route::get('/reports/maids', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'maidReports'])->name('superadmin.reports.maids');
    Route::get('/reports/products', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'productReports'])->name('superadmin.reports.products');
});

// SuperAdmin Dashboard (Outside admin group)
Route::middleware('auth')->group(function () {
    Route::get('/superadmin', [SuperAdminController::class, 'dashboard'])->name('superadmin.legacy');
    
    // Maid Dashboard
    Route::prefix('maid')->name('maid.')->group(function () {
        Route::get('/dashboard', [MaidDashboardController::class, 'dashboard'])->name('dashboard');
        Route::patch('/toggle-availability', [MaidDashboardController::class, 'toggleAvailability'])->name('toggle-availability');
        Route::get('/profile', [MaidDashboardController::class, 'profile'])->name('maid.profile');
        Route::get('/bookings', [MaidDashboardController::class, 'bookings'])->name('bookings');
        Route::get('/schedule', [MaidDashboardController::class, 'schedule'])->name('schedule');
        Route::get('/earnings', [MaidDashboardController::class, 'earnings'])->name('earnings');
    });
});