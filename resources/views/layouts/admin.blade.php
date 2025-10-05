<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - SuperDaily')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Admin Layout Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-title i {
            margin-right: 10px;
            font-size: 1.5rem;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 5px;
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 25px;
            border-radius: 0;
            margin: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: rgba(255,255,255,0.3);
            transform: translateX(5px);
        }

        .sidebar-nav .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.15);
            border-left-color: white;
        }

        .sidebar-nav .nav-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 280px;
            background: #f8f9fa;
        }

        .admin-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .page-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .page-title i {
            margin-right: 15px;
            color: #667eea;
        }

        .page-subtitle {
            color: #6c757d;
            margin: 8px 0 0 0;
            font-size: 1.1rem;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .admin-content {
            padding: 30px;
        }

        /* Action Buttons */
        .action-btn {
            background: linear-gradient(135deg, #00d4aa, #00b894);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #00b894, #00a085);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 170, 0.3);
            color: white;
            text-decoration: none;
        }

        .action-btn i {
            margin-right: 8px;
        }

        .action-btn.btn-outline {
            background: transparent;
            border: 2px solid #00d4aa;
            color: #00d4aa;
        }

        .action-btn.btn-outline:hover {
            background: #00d4aa;
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-card-icon.primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-card-icon.success {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .stat-card-icon.warning {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .stat-card-icon.info {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-card-value {
            font-size: 3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-card-label {
            color: #6c757d;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .stat-card-change {
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .stat-card-change.positive {
            color: #28a745;
        }

        .stat-card-change.negative {
            color: #dc3545;
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .content-card .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-card .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .content-card .card-title i {
            margin-right: 10px;
            color: #667eea;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .table {
            margin: 0;
            font-size: 14px;
        }

        .table thead th {
            background: #f8f9fa;
            border: none;
            padding: 20px 15px;
            font-weight: 600;
            color: #495057;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 20px 15px;
            border-top: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        /* Product Image */
        .product-image {
            width: 70px;
            height: 70px;
            border-radius: 10px;
            object-fit: cover;
            border: 3px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .product-image:hover {
            border-color: #667eea;
            transform: scale(1.1);
        }

        /* Status Badges */
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.active {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.featured {
            background: #fff3cd;
            color: #856404;
        }

        /* Action Buttons */
        .btn-action {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin: 0 3px;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .btn-action.btn-view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .btn-action.btn-edit {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .btn-action.btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 25px;
            color: #dee2e6;
        }

        .empty-state h5 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .empty-state p {
            margin-bottom: 35px;
            font-size: 1.1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #667eea;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Mobile Toggle -->
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        <div class="admin-sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3 class="sidebar-title">
                    <i class="fas fa-tachometer-alt"></i>Admin Panel
                </h3>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                            <i class="fas fa-shopping-cart"></i>Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
                            <i class="fas fa-concierge-bell"></i>Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.maids.*') ? 'active' : '' }}" href="{{ route('admin.maids.index') }}">
                            <i class="fas fa-users"></i>Maids
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                            <i class="fas fa-calendar-check"></i>Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}" href="{{ route('admin.offers.index') }}">
                            <i class="fas fa-percentage"></i>Offers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-tags"></i>Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home"></i>View Website
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-start w-100" style="border: none; background: none;">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title">
                            @yield('page-icon')
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p class="page-subtitle">@yield('page-subtitle', 'Welcome to your admin dashboard')</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="header-actions">
                            @yield('header-actions')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.style.transform = sidebar.style.transform === 'translateX(0px)' ? 'translateX(-100%)' : 'translateX(0px)';
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                    sidebar.style.transform = 'translateX(-100%)';
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.style.transform = 'translateX(0px)';
            } else {
                sidebar.style.transform = 'translateX(-100%)';
            }
        });
    </script>
</body>
</html>
