# 🚀 TripBazaar Startup Guide

## Current Issue: MySQL Connection Problem

### Step 1: Ensure XAMPP is Properly Started

1. **Open XAMPP Control Panel** (should already be open)
2. **Start Apache** - Click "Start" button next to Apache (should show green "Running")
3. **Start MySQL** - Click "Start" button next to MySQL 
   - If it fails, try clicking "Config" > "my.ini" and check port settings
   - Default port should be 3306

### Step 2: Alternative MySQL Startup

If MySQL won't start in Control Panel:

```bash
# Option 1: Start MySQL as service
net start mysql

# Option 2: Start MySQL manually 
C:\xampp\mysql\bin\mysqld --console

# Option 3: Use MariaDB instead
C:\xampp\mysql\bin\mariadbd --console
```

### Step 3: Test Database Connection

Once MySQL is running, test with:
```bash
php test_db_connection.php
```

Expected output:
```
✅ Database connection successful!
✅ Users table has X records
```

### Step 4: Access the Application

Once database is connected:

**🌐 Web Application URLs:**
- **Home Page:** http://localhost/tulip/
- **Admin Dashboard:** http://localhost/tulip/admin/dashboard
- **Provider Dashboard:** http://localhost/tulip/provider/dashboard
- **Browse Packages:** http://localhost/tulip/packages

**🔑 Demo Login Credentials:**
- **Admin:** admin@tripbazaar.com / password123
- **Provider (Sarah):** sarah.williams@example.com / password123
- **Customer:** customer@tripbazaar.com / password123

### Step 5: Verify System Status

**Test Web Interface:**
- Visit: http://localhost/tulip/test_web.php
- Should show database connection status and record counts

**Key Features to Test:**
1. ✅ **Provider Dashboard** - Package management, earnings tracking
2. ✅ **Admin Dashboard** - User management, commission tracking  
3. ✅ **Package Browse** - Search, filter, view packages
4. ✅ **Booking System** - Package booking workflow
5. ✅ **Commission System** - Provider earnings calculation

---

## 🛠️ Troubleshooting

### If MySQL Still Won't Start:

1. **Check Ports:** Another MySQL service might be running
   ```bash
   netstat -an | findstr :3306
   ```

2. **Stop Conflicting Services:**
   ```bash
   net stop MySQL80
   ```

3. **Check XAMPP Logs:**
   - Look at `C:\xampp\mysql\data\*.err` files

4. **Reset Password in Database Config:**
   - File: `config/database.php`
   - Change: `define('DB_PASS', '');` (empty for XAMPP)

### Alternative: Use Different Port

If MySQL runs on different port (e.g., 3307):
```php
// In config/database.php
define('DB_HOST', 'localhost:3307');
```

---

## 📊 Current System Status

### ✅ **What's Working:**
- Apache Web Server (HTTP 200 response)
- PHP Processing
- Application File Structure
- Demo Data Scripts

### ⚠️ **What Needs Fixing:**
- MySQL Database Connection
- Database Service Startup

### 🎯 **Next Steps After MySQL Fix:**
1. Verify demo data is loaded (8 providers, 18 packages)
2. Test commission system functionality
3. Complete customer booking workflow
4. Add payment gateway integration
5. Implement email notifications

---

**🔄 Status Check Command:**
```bash
php verify_commission_tracking.php
```

This will show complete system status once MySQL is running. 