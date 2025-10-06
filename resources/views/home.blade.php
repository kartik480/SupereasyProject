@extends('layouts.app')

@section('title', 'SuperDaily - Grocery & Services Delivery')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Get Everything Delivered</h1>
                <p class="hero-subtitle">
                    From fresh groceries to home services, we deliver everything you need 
                    right to your doorstep in minutes.
                </p>
                
                <!-- Search Box -->
                <div class="search-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <input type="text" class="search-input" placeholder="Search for groceries, services, or anything you need...">
                        </div>
                        <div class="col-md-4">
                            <button class="btn search-btn w-100">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-3">
                    <button class="btn btn-primary-custom">
                        <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                    </button>
                    <button class="btn btn-outline-light">
                        <i class="fas fa-play me-2"></i>How It Works
                    </button>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image-container">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&h=400&fit=crop" 
                         alt="SuperDaily Delivery" class="img-fluid rounded-3 shadow-lg">
                    <div class="floating-card">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle p-2 me-3">
                                <i class="fas fa-truck text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Fast Delivery</div>
                                <div class="text-muted small">15-30 minutes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-dark">Featured Products</h2>
            <p class="lead text-muted">Fresh groceries delivered to your door</p>
        </div>
        
        <div class="row">
            @forelse($featuredProducts as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid">
                        @if($product->discount_price)
                        <div class="discount-badge">
                            {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                        </div>
                        @endif
                        <div class="product-overlay">
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-category">{{ $product->category->name }}</div>
                        <h5 class="product-name">{{ $product->name }}</h5>
                        <p class="product-description">{{ $product->description }}</p>
                            <div class="product-price">
                                @if($product->discount_price)
                                    <span class="current-price">₹{{ number_format($product->discount_price, 2) }}</span>
                                    <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="current-price">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                                <span class="price-unit">{{ $product->unit }}</span>
                            </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h5>No products available</h5>
                    <p class="text-muted">Products will appear here once they are added to the system.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-th-large me-2"></i>View All Products
            </a>
        </div>
    </div>
</section>

<!-- User Profile Section (if logged in) -->
@auth
<section class="user-profile-section py-4 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="user-welcome">
                    <h4 class="text-primary mb-1">
                        <i class="fas fa-user-circle me-2"></i>Welcome back, {{ auth()->user()->name ?: 'User' }}!
                    </h4>
                    <p class="text-muted mb-0">Ready to book your next service?</p>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="user-actions">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Admin Access Section -->
        @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'maid']))
        <div class="row mt-3">
            <div class="col-12">
                <div class="alert alert-info mb-0">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-1">
                                <i class="fas fa-crown me-2"></i>Admin Access Available
                            </h6>
                            <small class="text-muted">Quick access to your dashboard</small>
                        </div>
                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                            <!-- SuperAdmin Button - Always visible but protected by middleware -->
                            <a href="{{ route('superadmin.login.show') }}" class="btn btn-danger btn-sm me-2">
                                <i class="fas fa-crown me-1"></i>Super Admin
                            </a>
                            
                            <!-- Admin Panel Button -->
                            @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-tachometer-alt me-1"></i>Admin Panel
                                </a>
                            @else
                                <a href="{{ route('admin.login.show') }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-tachometer-alt me-1"></i>Admin Panel
                                </a>
                            @endif
                            @if(auth()->user()->role === 'maid')
                                <a href="{{ route('maid.dashboard') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-user-tie me-1"></i>Maid Panel
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endauth

<!-- Categories Section -->
<section id="categories" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-dark">Shop by Category</h2>
            <p class="lead text-muted">Everything you need, organized for your convenience</p>
        </div>
        
        <div class="row">
            @forelse($categories as $category)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="category-card">
                    <div class="category-image">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid">
                        <div class="category-overlay">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                    </div>
                    <div class="category-content">
                        <h4 class="category-title">{{ $category->name }}</h4>
                        <p class="category-desc">{{ $category->description }}</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Shop Now</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="fas fa-th-large fa-3x text-muted mb-3"></i>
                    <h5>No categories available</h5>
                    <p class="text-muted">Categories will appear here once they are added to the system.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Professional Services Section -->
<section id="services" class="py-5 position-relative">
    <!-- Background Elements -->
    <div class="services-bg-pattern"></div>
    
    <div class="container position-relative">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <div class="section-badge mb-3">
                <i class="fas fa-concierge-bell me-2"></i>
                <span>Professional Services</span>
            </div>
            <h2 class="section-title">Premium Home Services</h2>
            <p class="section-subtitle">Experience excellence with our certified professionals. Quality service delivered to your doorstep.</p>
            
            <!-- Call to Action -->
            <div class="mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-info border-0 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="mb-1"><i class="fas fa-star text-warning me-2"></i>Why Choose Our Services?</h6>
                                    <p class="mb-0 small">Certified professionals • Insured services • 100% satisfaction guarantee • Flexible scheduling</p>
                                </div>
                                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                    <a href="#services" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-arrow-down me-1"></i>View Services
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Services Grid -->
        <div class="row g-4">
            @forelse($featuredServices as $service)
            <div class="col-lg-4 col-md-6">
                <div class="service-card-professional" data-service-id="{{ $service->id }}">
                    <!-- Service Image -->
                    <div class="service-image-wrapper">
                        <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="service-main-image">
                        <div class="service-overlay">
                            <div class="service-category-tag">
                                <i class="fas fa-tag me-1"></i>{{ $service->category ?? 'Service' }}
                            </div>
                            @if($service->discount_price)
                            <div class="service-discount-tag">
                                Save ₹{{ number_format($service->price - $service->discount_price, 0) }}
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Service Content -->
                    <div class="service-content-wrapper">
                        <!-- Service Info -->
                        <div class="service-info-header">
                            <div class="service-duration-info">
                                <i class="fas fa-clock text-primary me-1"></i>
                                <span>{{ $service->duration }}</span>
                            </div>
                            <div class="service-rating-info">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="rating-text">4.9</span>
                            </div>
                        </div>
                        
                        <!-- Service Title & Description -->
                        <h4 class="service-name">{{ $service->name }}</h4>
                        <p class="service-description-preview">{{ Str::limit($service->description, 80) }}</p>
                        
                        <!-- Key Features -->
                        @if(isset($service->features) && !empty($service->features))
                        <div class="service-features-preview">
                            @php
                                $features = is_string($service->features) ? explode(',', $service->features) : $service->features;
                                $features = array_map('trim', $features);
                                $features = array_filter($features);
                            @endphp
                            @foreach(array_slice($features, 0, 2) as $feature)
                            <div class="feature-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>{{ $feature }}</span>
                            </div>
                            @endforeach
                            @if(count($features) > 2)
                            <div class="feature-item">
                                <i class="fas fa-plus-circle text-primary me-2"></i>
                                <span>+{{ count($features) - 2 }} more</span>
                            </div>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Pricing -->
                        <div class="service-pricing-section">
                            <div class="price-display">
                                @if($service->discount_price)
                                    <span class="current-price">₹{{ number_format($service->discount_price, 0) }}</span>
                                    <span class="original-price">₹{{ number_format($service->price, 0) }}</span>
                                @else
                                    <span class="current-price">₹{{ number_format($service->price, 0) }}</span>
                                @endif
                                <span class="price-unit">{{ $service->unit ?? 'per session' }}</span>
                            </div>
                            <div class="service-badges">
                                <span class="badge badge-insured">
                                    <i class="fas fa-shield-alt me-1"></i>Insured
                                </span>
                                <span class="badge badge-certified">
                                    <i class="fas fa-certificate me-1"></i>Certified
                                </span>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="service-action-section">
                            @auth
                                <a href="{{ route('services.show', $service) }}" class="btn btn-book-service">
                                    <i class="fas fa-calendar-plus me-2"></i>
                                    <span>Book Now</span>
                                    <small class="d-block mt-1">Starting at ₹{{ number_format($service->discount_price ?: $service->price, 0) }}</small>
                                </a>
                            @else
                                <button type="button" class="btn btn-book-service" data-bs-toggle="modal" data-bs-target="#loginRequiredModal">
                                    <i class="fas fa-calendar-plus me-2"></i>
                                    <span>Book Now</span>
                                    <small class="d-block mt-1">Starting at ₹{{ number_format($service->discount_price ?: $service->price, 0) }}</small>
                                </button>
                            @endauth
                            
                            <!-- Quick Info -->
                            <div class="service-quick-info mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $service->duration }} • 
                                    <i class="fas fa-star text-warning me-1"></i>4.9 Rating • 
                                    <i class="fas fa-shield-alt text-success me-1"></i>Insured
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-services-state">
                    <div class="empty-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h4>No Services Available</h4>
                    <p>We're working on adding amazing services for you. Check back soon!</p>
                    <div class="empty-actions">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-bell me-2"></i>Notify Me
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Contact Us
                        </button>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Call to Action -->
        <div class="services-cta">
            <h3>Ready to Experience Premium Service?</h3>
            <p>Join thousands of satisfied customers who trust us with their homes</p>
            <div class="cta-buttons">
                <a href="#" class="btn btn-primary btn-lg">
                    <i class="fas fa-concierge-bell me-2"></i>View All Services
                </a>
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-phone me-2"></i>Call Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Service Details Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content service-modal-content">
            <div class="modal-header service-modal-header">
                <h5 class="modal-title" id="serviceModalLabel">Service Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body service-modal-body">
                <!-- Service Images Gallery -->
                <div class="service-images-gallery mb-4">
                    <div class="main-image-container">
                        <img id="serviceMainImage" src="" alt="Service Image" class="main-service-image">
                    </div>
                    <div class="thumbnail-images">
                        <img class="thumbnail-image active" src="" alt="Service Image 1">
                        <img class="thumbnail-image" src="" alt="Service Image 2">
                        <img class="thumbnail-image" src="" alt="Service Image 3">
                        <img class="thumbnail-image" src="" alt="Service Image 4">
                    </div>
                </div>
                
                <!-- Service Details -->
                <div class="service-details-content">
                    <div class="service-header-info">
                        <h4 id="serviceModalName" class="service-modal-name"></h4>
                        <div class="service-meta-info">
                            <span class="service-category-badge" id="serviceModalCategory"></span>
                            <span class="service-duration-badge" id="serviceModalDuration"></span>
                            <div class="service-rating-badge">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span>4.9 (127 reviews)</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Service Description -->
                    <div class="service-description-section">
                        <h6>Description</h6>
                        <p id="serviceModalDescription" class="service-full-description"></p>
                    </div>
                    
                    <!-- Service Features -->
                    <div class="service-features-section">
                        <h6>What's Included</h6>
                        <div id="serviceModalFeatures" class="service-features-list"></div>
                    </div>
                    
                    <!-- Service Pricing -->
                    <div class="service-pricing-section">
                        <h6>Pricing</h6>
                        <div class="pricing-details">
                            <div class="price-info">
                                <span id="serviceModalPrice" class="modal-price"></span>
                                <span class="price-unit-text" id="serviceModalUnit"></span>
                            </div>
                            <div class="pricing-badges">
                                <span class="badge badge-insured">
                                    <i class="fas fa-shield-alt me-1"></i>Fully Insured
                                </span>
                                <span class="badge badge-certified">
                                    <i class="fas fa-certificate me-1"></i>Certified Professionals
                                </span>
                                <span class="badge badge-guarantee">
                                    <i class="fas fa-handshake me-1"></i>100% Satisfaction
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer service-modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-book-now">
                    <i class="fas fa-calendar-plus me-2"></i>Book This Service
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-dark">Why Choose SuperDaily?</h2>
            <p class="lead text-muted">We make your life easier with our exceptional service</p>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h5 class="feature-title">Fast Delivery</h5>
                    <p class="feature-desc">Get your orders delivered in 15-30 minutes</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="feature-title">Quality Guaranteed</h5>
                    <p class="feature-desc">Fresh products and professional service</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h5 class="feature-title">Easy Ordering</h5>
                    <p class="feature-desc">Simple app interface for quick ordering</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="feature-title">24/7 Support</h5>
                    <p class="feature-desc">Round-the-clock customer support</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-3">Ready to Get Started?</h2>
                <p class="lead mb-4">
                    Download our app or start ordering online. 
                    Experience the convenience of SuperDaily today!
                </p>
                <div class="d-flex gap-3">
                    <button class="btn btn-light btn-lg">
                        <i class="fab fa-apple me-2"></i>Download for iOS
                    </button>
                    <button class="btn btn-outline-light btn-lg">
                        <i class="fab fa-google-play me-2"></i>Download for Android
                    </button>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=300&h=200&fit=crop" 
                     alt="Mobile App" class="img-fluid rounded-3">
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            // Skip empty hash links
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Search functionality
    document.querySelector('.search-btn').addEventListener('click', function() {
        const searchTerm = document.querySelector('.search-input').value;
        if (searchTerm.trim()) {
            alert('Searching for: ' + searchTerm);
            // Here you would implement actual search functionality
        }
    });
    
    // Category card hover effects
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

        // Service Modal Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const serviceModal = document.getElementById('serviceModal');
            const serviceButtons = document.querySelectorAll('.btn-book-service');

        serviceButtons.forEach(button => {
            button.addEventListener('click', function() {
                const serviceData = JSON.parse(this.getAttribute('data-service'));
                populateServiceModal(serviceData);
            });
        });

        function populateServiceModal(service) {
            // Set service details
            document.getElementById('serviceModalName').textContent = service.name;
            document.getElementById('serviceModalCategory').textContent = service.category || 'Service';
            document.getElementById('serviceModalDuration').textContent = service.duration;
            document.getElementById('serviceModalDescription').textContent = service.description;
            document.getElementById('serviceModalUnit').textContent = service.unit || 'per session';
            
            // Set pricing
            const priceElement = document.getElementById('serviceModalPrice');
            if (service.discount_price) {
                priceElement.innerHTML = `₹${parseFloat(service.discount_price).toLocaleString()} <span style="text-decoration: line-through; color: #999; font-size: 1.2rem;">₹${parseFloat(service.price).toLocaleString()}</span>`;
            } else {
                priceElement.textContent = `₹${parseFloat(service.price).toLocaleString()}`;
            }

            // Set features
            const featuresContainer = document.getElementById('serviceModalFeatures');
            featuresContainer.innerHTML = '';
            
            if (service.features) {
                let features = service.features;
                if (typeof features === 'string') {
                    features = features.split(',').map(f => f.trim()).filter(f => f);
                }
                
                features.forEach(feature => {
                    const featureItem = document.createElement('div');
                    featureItem.className = 'feature-item';
                    featureItem.innerHTML = `
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>${feature}</span>
                    `;
                    featuresContainer.appendChild(featureItem);
                });
            }

            // Set images from service data
            const images = [
                service.image_url || 'https://via.placeholder.com/400x300/00d4aa/ffffff?text=' + encodeURIComponent(service.name),
                service.image_2_url || 'https://via.placeholder.com/400x300/ff6b35/ffffff?text=' + encodeURIComponent(service.name + ' 2'),
                service.image_3_url || 'https://via.placeholder.com/400x300/28a745/ffffff?text=' + encodeURIComponent(service.name + ' 3'),
                service.image_4_url || 'https://via.placeholder.com/400x300/007bff/ffffff?text=' + encodeURIComponent(service.name + ' 4')
            ];
            
            // Set main image
            document.getElementById('serviceMainImage').src = images[0];
            document.getElementById('serviceMainImage').alt = service.name;
            
            // Set thumbnails
            const thumbnails = document.querySelectorAll('.thumbnail-image');
            thumbnails.forEach((thumb, index) => {
                if (images[index]) {
                    thumb.src = images[index];
                    thumb.alt = `${service.name} - Image ${index + 1}`;
                    thumb.style.display = 'block';
                } else {
                    thumb.style.display = 'none';
                }
            });

            // Reset active thumbnail
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            if (thumbnails[0]) thumbnails[0].classList.add('active');
        }

        // Thumbnail click functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('thumbnail-image')) {
                // Remove active class from all thumbnails
                document.querySelectorAll('.thumbnail-image').forEach(thumb => {
                    thumb.classList.remove('active');
                });
                
                // Add active class to clicked thumbnail
                e.target.classList.add('active');
                
                // Update main image
                document.getElementById('serviceMainImage').src = e.target.src;
            }
        });

        // Book Now button in modal
        document.querySelector('.btn-book-now').addEventListener('click', function() {
            alert('Booking functionality will be implemented soon!');
        });
    });
</script>

<!-- Login Required Modal -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="loginRequiredModalLabel">
                    <i class="fas fa-lock me-2"></i>Login Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-user-lock fa-3x text-primary mb-3"></i>
                    <h5>Please Login to Book Services</h5>
                    <p class="text-muted">You need to be logged in to book our premium services. Create an account or login to continue.</p>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </div>
                </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt text-success me-1"></i>
                        Your information is secure and will only be used for booking purposes.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection