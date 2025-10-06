<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SuperAdmin Panel - SuperDaily')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* SuperAdmin Layout Styles */
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            display: flex;
            justify-content: between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .page-header {
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }

        .page-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 20px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 5px 0 0 0;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }

        .admin-content {
            padding: 30px;
        }

        /* Cards */
        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .card-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 25px;
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .stat-card-icon.primary { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
        .stat-card-icon.success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
        .stat-card-icon.warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
        .stat-card-icon.info { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }

        .stat-card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-card-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .stat-card-change {
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stat-card-change.positive { color: #28a745; }
        .stat-card-change.negative { color: #dc3545; }

        /* Action Buttons */
        .action-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .action-btn.btn-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .action-btn.btn-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: white;
            color: white;
        }

        /* Dropdown Styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 10px 0;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.15);
            color: #c82333;
        }

        .dropdown-divider {
            margin: 8px 0;
            border-color: #e9ecef;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-top: 1px solid #e9ecef;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(220, 53, 69, 0.05);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
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

        /* Button Actions */
        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .btn-view:hover {
            background: #bbdefb;
            color: #0d47a1;
        }

        .btn-edit {
            background: #fff3e0;
            color: #f57c00;
        }

        .btn-edit:hover {
            background: #ffe0b2;
            color: #e65100;
        }

        .btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        .btn-delete:hover {
            background: #ffcdd2;
            color: #b71c1c;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h5 {
            color: #495057;
            margin-bottom: 10px;
        }

        /* Activity List */
        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .activity-content {
            flex: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .admin-header {
                padding: 15px 20px;
            }

            .admin-content {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 1.4rem;
            }

            .header-actions {
                flex-direction: column;
                gap: 5px;
            }
        }

        /* Custom Scrollbar */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">
                    <i class="fas fa-crown"></i>
                    SuperAdmin
                </h1>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.categories.*') ? 'active' : '' }}" href="{{ route('superadmin.categories.index') }}">
                            <i class="fas fa-tags"></i>
                            Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.products.*') ? 'active' : '' }}" href="{{ route('superadmin.products.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.services.*') ? 'active' : '' }}" href="{{ route('superadmin.services.index') }}">
                            <i class="fas fa-concierge-bell"></i>
                            Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.bookings.*') ? 'active' : '' }}" href="{{ route('superadmin.bookings.index') }}">
                            <i class="fas fa-calendar-check"></i>
                            Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.offers.*') ? 'active' : '' }}" href="{{ route('superadmin.offers.index') }}">
                            <i class="fas fa-gift"></i>
                            Offers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.maids.*') ? 'active' : '' }}" href="{{ route('superadmin.maids.index') }}">
                            <i class="fas fa-user-tie"></i>
                            Maids
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.reports.*') ? 'active' : '' }}" href="{{ route('superadmin.reports.bookings') }}">
                            <i class="fas fa-chart-bar"></i>
                            Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </nav>
            <form id="logout-form" action="{{ route('superadmin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <div class="page-header">
                    <div class="page-icon">
                        @yield('page-icon', '<i class="fas fa-tachometer-alt"></i>')
                    </div>
                    <div>
                        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                        <p class="page-subtitle">@yield('page-subtitle', 'Welcome to your SuperAdmin dashboard')</p>
                    </div>
                </div>
                <div class="header-actions">
                    @yield('header-actions')
                </div>
            </div>

            <!-- Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
