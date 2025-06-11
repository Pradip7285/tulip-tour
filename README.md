# TripBazaar - Travel Marketplace

A comprehensive travel marketplace web application built with Core PHP, Tailwind CSS, and Alpine.js. TripBazaar allows customers to browse and book travel packages while enabling providers to list and manage their offerings.

## Features

### For Customers
- 🏠 **Homepage** with hero section, featured packages, and search functionality
- 🔍 **Package Search & Filtering** by destination, budget, duration, and category  
- 📦 **Package Details** with interactive price calculator
- 💳 **Booking System** with secure payment processing
- 📄 **Receipt Generation** and download capabilities
- 👤 **Customer Dashboard** to manage bookings and profile

### For Providers
- 📊 **Provider Dashboard** with analytics and performance metrics
- ➕ **Package Management** - add, edit, delete travel packages
- 📈 **Booking Management** - view customer bookings and details
- 💰 **Payout System** - request withdrawals and track earnings
- ✅ **Verification System** for trusted providers

### For Administrators
- 🎛️ **Admin Dashboard** with comprehensive site statistics
- 👥 **User Management** - customers, providers, and admins
- 📦 **Package Oversight** - approve, edit, or remove packages
- 💳 **Payment Management** - track transactions and refunds
- 🏷️ **Commission Control** - set and manage commission rates
- 🎨 **Content Management** - homepage banners, testimonials, SEO

## Tech Stack

- **Backend**: Core PHP with custom MVC architecture
- **Frontend**: HTML5, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Icons**: Font Awesome 6
- **Design**: Mobile-first responsive design

## Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or XAMPP/WAMP
- Composer (optional, for future dependencies)

### Step 1: Clone/Download the Project
```bash
# If using Git
git clone <repository-url> tripbazaar
cd tripbazaar

# Or download and extract the ZIP file to your web directory
```

### Step 2: Database Setup
1. Create a new MySQL database named `tripbazaar`
2. Update database credentials in `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tripbazaar');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

3. Import the database schema:
```bash
mysql -u your_username -p tripbazaar < database/schema.sql
```

### Step 3: Web Server Configuration

#### For XAMPP/WAMP
1. Place the project folder in your `htdocs` or `www` directory
2. Start Apache and MySQL services
3. Access via `http://localhost/tripbazaar`

#### For Apache Virtual Host
```apache
<VirtualHost *:80>
    ServerName tripbazaar.local
    DocumentRoot /path/to/tripbazaar
    DirectoryIndex index.php
    
    <Directory /path/to/tripbazaar>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### URL Rewriting (Apache)
Create `.htaccess` file in the root directory:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

### Step 4: File Permissions
Ensure web server has read/write access to:
- `uploads/` directory (create if not exists)
- Log files (if implementing logging)

## Default Login Credentials

### Admin Account
- **Email**: admin@tripbazaar.com
- **Password**: password (change after first login)

### Test Provider Account
- **Email**: provider@example.com  
- **Password**: password

## Project Structure

```
tripbazaar/
├── config/
│   └── database.php          # Database configuration
├── controllers/
│   ├── PackageController.php # Package listing & details
│   ├── AuthController.php    # Authentication (to be added)
│   ├── BookingController.php # Booking & payments (to be added)
│   ├── CustomerController.php# Customer dashboard (to be added)
│   ├── ProviderController.php# Provider dashboard (to be added)
│   └── AdminController.php   # Admin dashboard (to be added)
├── database/
│   └── schema.sql           # Database structure & sample data
├── includes/
│   ├── functions.php        # Utility functions
│   └── layout.php          # Main layout template
├── views/
│   ├── home.php            # Homepage
│   ├── packages/
│   │   ├── listing.php     # Package listing page
│   │   └── details.php     # Package details with price calculator
│   └── 404.php             # Error page
├── uploads/                 # File upload directory
├── index.php               # Main router
└── README.md               # This file
```

## Key Features Implemented

### ✅ Completed
- Homepage with hero section and featured packages
- Package listing with advanced filtering and pagination
- Package details with interactive price calculator using Alpine.js
- Database schema with all required tables
- MVC architecture with clean routing
- Responsive design with Tailwind CSS
- Sample data generation

### 🚧 To Be Implemented
- Authentication system (login/register)
- Booking and payment processing
- Customer dashboard
- Provider dashboard  
- Admin dashboard
- Email notifications
- PDF receipt generation
- Image upload functionality
- Review and rating system

## Usage

### Browsing Packages
1. Visit the homepage to see featured packages
2. Use the search form to filter by destination, budget, duration, or category
3. Browse the packages listing page with sorting options
4. Click on any package to view detailed information

### Price Calculator
On package details pages, use the interactive calculator to:
- Adjust number of adults and children
- Add extra rooms if available
- See real-time price updates
- View detailed price breakdown

### Navigation
- Responsive navigation with mobile menu
- Breadcrumb navigation on package pages
- Flash message system for user feedback

## Customization

### Styling
- Colors and themes can be customized in `includes/layout.php` (Tailwind config)
- Custom CSS can be added to the `<style>` section in layout.php

### Configuration
- Site settings stored in `site_settings` database table
- Commission rates configurable per provider
- Category management through database

## Security Features

- SQL injection prevention using prepared statements
- XSS protection with input sanitization
- CSRF token system (framework ready)
- Password hashing with PHP's built-in functions
- Role-based access control system

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile responsive design
- Progressive enhancement with Alpine.js

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support or questions, please contact the development team or create an issue in the repository.

---

**TripBazaar** - Your Gateway to Amazing Adventures 🌍✈️ 