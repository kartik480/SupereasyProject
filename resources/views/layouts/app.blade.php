<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SuperDaily - Grocery & Services Delivery')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00d4aa;
            --secondary-color: #ff6b35;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), #00b894);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), #00b894);
            color: white;
            padding: 80px 0;
            margin-bottom: 50px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 15px 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .search-input {
            border: none;
            outline: none;
            font-size: 1.1rem;
            width: 100%;
        }
        
        .search-btn {
            background: var(--secondary-color);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: #e55a2b;
            transform: translateY(-2px);
        }
        
        .category-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .category-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .category-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .category-desc {
            color: #666;
            font-size: 0.9rem;
        }
        
        .feature-section {
            background: white;
            border-radius: 20px;
            padding: 50px;
            margin: 50px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--dark-color);
            margin-bottom: 15px;
        }
        
        .feature-desc {
            color: #666;
            line-height: 1.6;
        }
        
        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            border-radius: 50px;
            padding: 15px 30px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background: #00b894;
            transform: translateY(-2px);
        }
        
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 50px 0 30px;
            margin-top: 80px;
        }
        
        .footer-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-link:hover {
            color: var(--primary-color);
        }
        
        .admin-panel-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 25px;
            font-weight: bold;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .admin-panel-btn:hover {
            background: #e55a2b;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }
        
        /* Professional Services Section */
        .services-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            opacity: 0.3;
            z-index: -1;
        }
        
        .section-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #00d4aa, #00b894);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(0, 212, 170, 0.3);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .service-card-professional {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
        }
        
        .service-card-professional:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .service-image-wrapper {
            height: 220px;
            position: relative;
            overflow: hidden;
        }
        
        .service-main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        
        .service-card-professional:hover .service-main-image {
            transform: scale(1.08);
        }
        
        .service-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.3) 100%);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 15px;
        }
        
        .service-category-tag {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        .service-discount-tag {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }
        
        .service-content-wrapper {
            padding: 25px;
        }
        
        .service-info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .service-duration-info {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .service-rating-info {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .service-rating-info .stars {
            color: #ffc107;
            font-size: 0.8rem;
        }
        
        .rating-text {
            font-size: 0.8rem;
            color: #666;
            font-weight: 600;
        }
        
        .service-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        
        .service-description-preview {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .service-features-preview {
            margin-bottom: 20px;
        }
        
        .service-features-preview .feature-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 5px;
        }
        
        .service-pricing-section {
            border-top: 1px solid #f0f0f0;
            padding-top: 15px;
            margin-bottom: 20px;
        }
        
        .price-display {
            margin-bottom: 10px;
        }
        
        .current-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2c3e50;
            line-height: 1;
        }
        
        .original-price {
            font-size: 1.1rem;
            color: #999;
            text-decoration: line-through;
            margin-left: 8px;
        }
        
        .price-unit {
            display: block;
            font-size: 0.8rem;
            color: #666;
            margin-top: 2px;
        }
        
        .service-badges {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 12px;
        }
        
        .badge-insured {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .badge-certified {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }
        
        .badge-guarantee {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .btn-book-service {
            background: linear-gradient(135deg, #00d4aa, #00b894);
            border: none;
            border-radius: 12px;
            padding: 15px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 212, 170, 0.3);
            text-decoration: none;
            display: block;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .btn-book-service small {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .service-action-section {
            text-align: center;
        }

        .service-quick-info {
            font-size: 0.8rem;
            line-height: 1.4;
        }
        
        .btn-book-service:hover {
            background: linear-gradient(135deg, #00b894, #00a085);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 212, 170, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .empty-services-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-services-state .empty-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .empty-services-state h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .empty-services-state p {
            color: #6c757d;
            margin-bottom: 30px;
        }
        
        .empty-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .services-cta {
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 20px;
            padding: 40px;
            margin-top: 50px;
        }
        
        .services-cta h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .services-cta p {
            color: #6c757d;
            margin-bottom: 30px;
        }
        
        .cta-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        /* Service Modal Styles */
        .service-modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .service-modal-header {
            background: linear-gradient(135deg, #00d4aa, #00b894);
            color: white;
            border-radius: 20px 20px 0 0;
            border-bottom: none;
            padding: 20px 30px;
        }
        
        .service-modal-header .modal-title {
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .service-modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .service-modal-body {
            padding: 30px;
        }
        
        .service-images-gallery {
            margin-bottom: 30px;
        }
        
        .main-image-container {
            height: 300px;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .main-service-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .thumbnail-images {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .thumbnail-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .thumbnail-image:hover,
        .thumbnail-image.active {
            border-color: #00d4aa;
            transform: scale(1.05);
        }
        
        .service-details-content h6 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .service-modal-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .service-meta-info {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }
        
        .service-category-badge,
        .service-duration-badge {
            background: rgba(0, 212, 170, 0.1);
            color: #00d4aa;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .service-rating-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .service-rating-badge .stars {
            color: #ffc107;
        }
        
        .service-full-description {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .service-features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
            margin-bottom: 25px;
        }
        
        .service-features-list .feature-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: rgba(0, 212, 170, 0.05);
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        .pricing-details {
            background: rgba(0, 212, 170, 0.05);
            padding: 20px;
            border-radius: 15px;
        }
        
        .price-info {
            margin-bottom: 15px;
        }
        
        .modal-price {
            font-size: 2rem;
            font-weight: 800;
            color: #2c3e50;
        }
        
        .price-unit-text {
            color: #666;
            font-size: 0.9rem;
            margin-left: 10px;
        }
        
        .pricing-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .service-modal-footer {
            border-top: 1px solid #f0f0f0;
            padding: 20px 30px;
            border-radius: 0 0 20px 20px;
        }
        
        .btn-book-now {
            background: linear-gradient(135deg, #00d4aa, #00b894);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 212, 170, 0.3);
        }
        
        .btn-book-now:hover {
            background: linear-gradient(135deg, #00b894, #00a085);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 212, 170, 0.4);
            color: white;
        }

        /* Enhanced Product Cards */
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            position: relative;
            overflow: hidden;
            height: 200px;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }
        
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--secondary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .product-card:hover .product-overlay {
            opacity: 1;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-category {
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .product-name {
            color: var(--dark-color);
            font-weight: 600;
            margin: 10px 0;
        }
        
        .product-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .current-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .original-price {
            font-size: 1rem;
            color: #999;
            text-decoration: line-through;
        }
        
        .price-unit {
            font-size: 0.8rem;
            color: #666;
        }
        
        /* Enhanced Service Cards */
        .service-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .service-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .service-card:hover .service-image img {
            transform: scale(1.05);
        }
        
        .service-content {
            padding: 20px;
        }
        
        .service-duration {
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .service-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .service-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .service-features {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .service-features li {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .service-price {
            margin-bottom: 15px;
        }
        
        /* Enhanced Category Cards */
        .category-image {
            position: relative;
            height: 150px;
            overflow: hidden;
        }
        
        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .category-card:hover .category-image img {
            transform: scale(1.05);
        }
        
        .category-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 212, 170, 0.9);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .category-content {
            padding: 20px;
            text-align: center;
        }
        
        /* Hero Section Enhancements */
        .hero-image-container {
            position: relative;
        }
        
        .floating-card {
            position: absolute;
            bottom: -20px;
            right: -20px;
            background: white;
            color: var(--dark-color);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        /* Enhanced Search Box */
        .search-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 10px;
            margin: 2rem 0;
            backdrop-filter: blur(10px);
        }
        
        .search-input {
            background: transparent;
            border: none;
            color: white;
            padding: 15px 20px;
            font-size: 1.1rem;
        }
        
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .search-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
        }
        
        .search-btn {
            background: var(--secondary-color);
            border: none;
            border-radius: 25px;
            padding: 15px 25px;
            font-weight: 600;
        }
        
        .search-btn:hover {
            background: #e55a2b;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .category-card {
                height: 180px;
                padding: 20px;
            }
            
            .floating-card {
                position: static;
                margin-top: 20px;
            }
        }

        /* Enhanced Booking Form Styles */
        .service-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .service-image-container:hover {
            transform: scale(1.02);
        }

        .service-image-container img {
            transition: all 0.3s ease;
        }

        .service-image-container:hover img {
            transform: scale(1.05);
        }

        .image-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: white;
            padding: 8px 12px;
            font-size: 0.8rem;
            font-weight: 500;
            text-align: center;
        }

        .service-pricing-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #dee2e6;
        }

        .pricing-details {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .current-price {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .savings-badge {
            text-align: center;
        }

        .service-meta {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .meta-item i {
            width: 20px;
            text-align: center;
        }

        .feature-item {
            background: rgba(0, 123, 255, 0.05);
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 8px;
            border-left: 3px solid #007bff;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff, #0056b3) !important;
        }

        .service-main-image {
            border: 2px solid #e9ecef;
        }

        .service-additional-image {
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .service-additional-image:hover {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }

        /* Responsive adjustments for booking form */
        @media (max-width: 768px) {
            .service-image-container img {
                height: 200px !important;
            }
            
            .service-additional-image {
                height: 90px !important;
            }
            
            .service-pricing-card {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-shopping-cart me-2"></i>SuperDaily
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categories">
                            <i class="fas fa-th-large me-1"></i>Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">
                            <i class="fas fa-concierge-bell me-1"></i>Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-phone me-1"></i>Contact
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>{{ auth()->user() ? auth()->user()->name : 'User' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="fas fa-user me-2"></i>My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('change-password') }}">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </a>
                                </li>
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Admin Panel
                                    </a>
                                </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">
                        <i class="fas fa-shopping-cart me-2"></i>SuperDaily
                    </h5>
                    <p class="text-muted">
                        Your one-stop destination for groceries and home services. 
                        Fast delivery, quality products, and excellent service.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="footer-title">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                        <li><a href="#categories" class="footer-link">Categories</a></li>
                        <li><a href="#services" class="footer-link">Services</a></li>
                        <li><a href="#contact" class="footer-link">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="footer-title">Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">Grocery Delivery</a></li>
                        <li><a href="#" class="footer-link">Home Cleaning</a></li>
                        <li><a href="#" class="footer-link">Cooking</a></li>
                        <li><a href="#" class="footer-link">Laundry</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="footer-title">Contact Info</h6>
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-2"></i>+1 (555) 123-4567
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-2"></i>info@superdaily.com
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>123 Main St, City, State
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2024 SuperDaily. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="footer-link me-3">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Admin Panel Button -->
    <a href="{{ route('admin.dashboard') }}" class="admin-panel-btn">
        <i class="fas fa-cog me-2"></i>Admin Panel
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>