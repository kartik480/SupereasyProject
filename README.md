# SuperDaily - Grocery & Services Platform

A comprehensive Laravel application similar to Blinkit and Urban Company, featuring grocery delivery and home services with multi-panel management system.

## Features

### 🏠 Frontend Website (Blinkit-style)
- Modern, responsive UI similar to Blinkit
- Category-based grocery browsing
- Featured items display
- Service banners (Pharmacy, Baby care, etc.)
- Contact inquiry system

### 👑 Super Admin Panel
- Complete system overview
- Category management with image upload
- Item management with pricing and stock
- User management
- System statistics

### 👨‍💼 Admin Panel
- Inquiry management
- Subscription tracking
- Maid management and productivity
- Work hours monitoring
- Today's productivity reports

### 👩‍💼 Maid Panel
- Work hours tracking (Check-in/Check-out)
- Personal productivity dashboard
- Work history viewing
- Profile management

## Installation

### Prerequisites
- PHP 8.0 or higher
- Composer
- MySQL/MariaDB
- Node.js & NPM (for frontend assets)

### Step 1: Clone and Setup
```bash
# Clone the repository
git clone <repository-url>
cd superdaily

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 2: Database Configuration
```bash
# Update .env file with your database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=superdaily
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### Step 3: Storage Setup
```bash
# Create storage link
php artisan storage:link
```

### Step 4: Start Development Server
```bash
# Start Laravel development server
php artisan serve
```

## Default Login Credentials

After running the seeder, you can login with:

### Super Admin
- Email: `superadmin@superdaily.com`
- Password: `password`

### Admin
- Email: `admin@superdaily.com`
- Password: `password`

### Maid Accounts
- Email: `maid1@superdaily.com` to `maid5@superdaily.com`
- Password: `password`

## Project Structure

```
superdaily/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── SuperAdmin/
│   │   │   ├── Maid/
│   │   │   └── ...
│   │   └── Middleware/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── admin/
│       ├── superadmin/
│       ├── maid/
│       └── ...
└── routes/
    └── web.php
```

## Key Features Explained

### 1. Multi-Panel System
- **Super Admin**: Full system control, category/item management
- **Admin**: Operational management, maid oversight
- **Maid**: Personal work tracking and profile management

### 2. Grocery Management
- Category creation with image upload
- Item management with pricing and stock
- Featured items system
- Discount pricing support

### 3. Maid Work Tracking
- Check-in/Check-out system
- Daily, weekly, and monthly hour tracking
- Productivity reports
- Work history viewing

### 4. Inquiry System
- Customer inquiry management
- Status tracking (new, in-progress, resolved, closed)
- Admin notes system

### 5. Subscription Management
- User subscription tracking
- Status monitoring
- Payment tracking

## Customization

### Adding New Categories
1. Login as Super Admin
2. Navigate to Categories Management
3. Click "Add New Category"
4. Upload image, set icon, and configure settings

### Managing Items
1. Login as Super Admin
2. Navigate to Items Management
3. Add items with pricing, stock, and images
4. Set featured items for homepage display

### Maid Management
1. Login as Admin
2. Navigate to Maids section
3. View maid work hours and productivity
4. Monitor daily performance

## Technology Stack

- **Backend**: Laravel 9
- **Frontend**: Bootstrap 5, Font Awesome
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Role Management**: Spatie Laravel Permission
- **Image Handling**: Intervention Image

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team or create an issue in the repository.
