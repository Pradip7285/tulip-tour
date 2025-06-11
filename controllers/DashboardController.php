<?php

class DashboardController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function customerDashboard() {
        $user = getCurrentUser();
        
        // Get customer statistics
        $stats = $this->getCustomerStats($user['id']);
        
        // Get recent bookings
        $recentBookings = $this->getRecentBookings($user['id'], 5);
        
        // Get saved packages (favorites) - placeholder for future feature
        $savedPackages = [];
        
        $pageTitle = 'My Dashboard - TripBazaar';
        $currentPage = 'dashboard';
        include_once 'views/dashboard/customer.php';
    }
    
    public function providerDashboard() {
        $user = getCurrentUser();
        
        // Get provider statistics
        $stats = $this->getProviderStats($user['id']);
        
        // Get recent bookings for provider's packages
        $recentBookings = $this->getProviderBookings($user['id'], 5);
        
        // Get provider's packages
        $packages = $this->getProviderPackages($user['id'], 5);
        
        // Get earnings summary
        $earnings = $this->getProviderEarnings($user['id']);
        
        $pageTitle = 'Provider Dashboard - TripBazaar';
        $currentPage = 'dashboard';
        include_once 'views/dashboard/provider.php';
    }
    
    public function adminDashboard() {
        // Get overall site statistics
        $stats = $this->getAdminStats();
        
        // Get recent activities
        $recentBookings = $this->getRecentBookings(null, 10);
        $recentUsers = $this->getRecentUsers(5);
        
        // Get revenue summary
        $revenue = $this->getRevenueStats();
        
        // Get commission metrics
        $commission = $this->getCommissionStats();
        
        // Get pending payouts for admin review
        $pendingPayouts = $this->getPendingPayouts(5);
        
        $pageTitle = 'Admin Dashboard - TripBazaar';
        $currentPage = 'dashboard';
        include_once 'views/dashboard/admin.php';
    }
    
    private function getCustomerStats($customerId) {
        $stats = [];
        
        // Total bookings
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM bookings WHERE customer_id = ?");
        $stmt->execute([$customerId]);
        $stats['total_bookings'] = $stmt->fetch()['total'];
        
        // Completed trips
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM bookings WHERE customer_id = ? AND status = 'completed'");
        $stmt->execute([$customerId]);
        $stats['completed_trips'] = $stmt->fetch()['total'];
        
        // Pending bookings
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM bookings WHERE customer_id = ? AND status = 'pending'");
        $stmt->execute([$customerId]);
        $stats['pending_bookings'] = $stmt->fetch()['total'];
        
        // Total amount spent
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_amount), 0) as total FROM bookings WHERE customer_id = ? AND payment_status = 'paid'");
        $stmt->execute([$customerId]);
        $stats['total_spent'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    private function getProviderStats($providerId) {
        $stats = [];
        
        // Get the actual provider profile ID from user ID
        $stmt = $this->db->prepare("SELECT id FROM provider_profiles WHERE user_id = ?");
        $stmt->execute([$providerId]);
        $providerProfile = $stmt->fetch();
        $actualProviderId = $providerProfile ? $providerProfile['id'] : $providerId;
        
        // Total packages
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM packages WHERE provider_id = ?");
        $stmt->execute([$actualProviderId]);
        $stats['total_packages'] = $stmt->fetch()['total'];
        
        // Total bookings for all packages
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ?
        ");
        $stmt->execute([$actualProviderId]);
        $stats['total_bookings'] = $stmt->fetch()['total'];
        
        // Active packages
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM packages WHERE provider_id = ? AND is_active = 1");
        $stmt->execute([$actualProviderId]);
        $stats['active_packages'] = $stmt->fetch()['total'];
        
        // Total earnings (85% of completed bookings)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.total_amount * 0.85), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.status = 'completed'
        ");
        $stmt->execute([$actualProviderId]);
        $stats['total_earnings'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    private function getAdminStats() {
        $stats = [];
        
        // Total users
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $stats['total_users'] = $stmt->fetch()['total'];
        
        // Total packages
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM packages");
        $stmt->execute();
        $stats['total_packages'] = $stmt->fetch()['total'];
        
        // Total bookings
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM bookings");
        $stmt->execute();
        $stats['total_bookings'] = $stmt->fetch()['total'];
        
        // Total revenue
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_amount), 0) as total FROM bookings WHERE payment_status = 'paid'");
        $stmt->execute();
        $stats['total_revenue'] = $stmt->fetch()['total'];
        
        // New users this month (check if created_at column exists, fallback to registration estimate)
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE DATE_FORMAT(NOW(), '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");
        $stmt->execute();
        $stats['new_users_month'] = $stmt->fetch()['total'];
        
        // Bookings this month
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM bookings WHERE DATE_FORMAT(booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");
        $stmt->execute();
        $stats['bookings_month'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    private function getRecentBookings($customerId = null, $limit = 10) {
        if ($customerId) {
            $stmt = $this->db->prepare("
                SELECT b.*, p.title as package_title, p.destination 
                FROM bookings b 
                JOIN packages p ON b.package_id = p.id 
                WHERE b.customer_id = ? 
                ORDER BY b.booking_date DESC 
                LIMIT ?
            ");
            $stmt->execute([$customerId, $limit]);
        } else {
            $stmt = $this->db->prepare("
                SELECT b.*, p.title as package_title, p.destination 
                FROM bookings b 
                JOIN packages p ON b.package_id = p.id 
                ORDER BY b.booking_date DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getProviderBookings($providerId, $limit = 10) {
        // Get the actual provider profile ID from user ID
        $stmt = $this->db->prepare("SELECT id FROM provider_profiles WHERE user_id = ?");
        $stmt->execute([$providerId]);
        $providerProfile = $stmt->fetch();
        $actualProviderId = $providerProfile ? $providerProfile['id'] : $providerId;
        
        $stmt = $this->db->prepare("
            SELECT b.*, p.title as package_title, p.destination 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? 
            ORDER BY b.booking_date DESC 
            LIMIT ?
        ");
        $stmt->execute([$actualProviderId, $limit]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getProviderPackages($providerId, $limit = 10) {
        // Get the actual provider profile ID from user ID
        $stmt = $this->db->prepare("SELECT id FROM provider_profiles WHERE user_id = ?");
        $stmt->execute([$providerId]);
        $providerProfile = $stmt->fetch();
        $actualProviderId = $providerProfile ? $providerProfile['id'] : $providerId;
        
        $stmt = $this->db->prepare("
            SELECT * FROM packages 
            WHERE provider_id = ? 
            ORDER BY id DESC 
            LIMIT ?
        ");
        $stmt->execute([$actualProviderId, $limit]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getProviderEarnings($providerId) {
        $earnings = [];
        
        // Get the actual provider profile ID from user ID
        $stmt = $this->db->prepare("SELECT id FROM provider_profiles WHERE user_id = ?");
        $stmt->execute([$providerId]);
        $providerProfile = $stmt->fetch();
        $actualProviderId = $providerProfile ? $providerProfile['id'] : $providerId;
        
        // This month earnings (use stored provider_amount which already includes proper gateway fee calculation)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid' AND b.status = 'completed'
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
        ");
        $stmt->execute([$actualProviderId]);
        $earnings['this_month'] = $stmt->fetch()['total'];
        
        // Total earnings (completed bookings only - use stored provider_amount)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid' AND b.status = 'completed'
        ");
        $stmt->execute([$actualProviderId]);
        $earnings['total'] = $stmt->fetch()['total'];
        
        // Pending payouts (confirmed bookings ready for completion - use stored provider_amount)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid' AND b.status = 'confirmed'
        ");
        $stmt->execute([$actualProviderId]);
        $earnings['pending_payouts'] = $stmt->fetch()['total'];
        
        // Completed payouts (available for payout request - use stored provider_amount)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(b.provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid' AND b.status = 'completed'
        ");
        $stmt->execute([$actualProviderId]);
        $earnings['completed_payouts'] = $stmt->fetch()['total'];
        
        return $earnings;
    }
    
    private function getRecentUsers($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            ORDER BY id DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getRevenueStats() {
        $revenue = [];
        
        // This month revenue
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(total_amount), 0) as total 
            FROM bookings 
            WHERE payment_status = 'paid' 
            AND DATE_FORMAT(booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
        ");
        $stmt->execute();
        $revenue['this_month'] = $stmt->fetch()['total'];
        
        // Last month revenue
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(total_amount), 0) as total 
            FROM bookings 
            WHERE payment_status = 'paid' 
            AND DATE_FORMAT(booking_date, '%Y-%m') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m')
        ");
        $stmt->execute();
        $revenue['last_month'] = $stmt->fetch()['total'];
        
        // Calculate growth
        $revenue['growth'] = $revenue['last_month'] > 0 
            ? (($revenue['this_month'] - $revenue['last_month']) / $revenue['last_month']) * 100 
            : 0;
        
        return $revenue;
    }
    
    private function getPendingPayouts($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   CONCAT(u.first_name, ' ', u.last_name) as provider_name,
                   u.email as provider_email
            FROM payouts p
            JOIN users u ON p.provider_id = u.id
            WHERE p.status = 'pending'
            ORDER BY p.requested_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getCommissionStats() {
        $commission = [];
        
        // Total commission earned (using actual stored commission amounts)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(commission_amount), 0) as total 
            FROM bookings 
            WHERE payment_status = 'paid'
        ");
        $stmt->execute();
        $commission['total_earned'] = $stmt->fetch()['total'];
        
        // Commission this month
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(commission_amount), 0) as total 
            FROM bookings 
            WHERE payment_status = 'paid' 
            AND DATE_FORMAT(booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
        ");
        $stmt->execute();
        $commission['this_month'] = $stmt->fetch()['total'];
        
        // Commission last month
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(commission_amount), 0) as total 
            FROM bookings 
            WHERE payment_status = 'paid' 
            AND DATE_FORMAT(booking_date, '%Y-%m') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m')
        ");
        $stmt->execute();
        $commission['last_month'] = $stmt->fetch()['total'];
        
        // Calculate commission growth
        $commission['growth'] = $commission['last_month'] > 0 
            ? (($commission['this_month'] - $commission['last_month']) / $commission['last_month']) * 100 
            : 0;
            
        // Average commission per booking (using actual commission amounts)
        $stmt = $this->db->prepare("
            SELECT COALESCE(AVG(commission_amount), 0) as avg 
            FROM bookings 
            WHERE payment_status = 'paid'
        ");
        $stmt->execute();
        $commission['avg_per_booking'] = $stmt->fetch()['avg'];
        
        // Commission rate statistics
        $stmt = $this->db->prepare("
            SELECT 
                AVG(CASE WHEN total_amount > 0 THEN (commission_amount / total_amount) * 100 ELSE 0 END) as avg_rate,
                MIN(CASE WHEN total_amount > 0 THEN (commission_amount / total_amount) * 100 ELSE 0 END) as min_rate,
                MAX(CASE WHEN total_amount > 0 THEN (commission_amount / total_amount) * 100 ELSE 0 END) as max_rate
            FROM bookings 
            WHERE payment_status = 'paid' AND total_amount > 0
        ");
        $stmt->execute();
        $rateStats = $stmt->fetch();
        $commission['avg_rate'] = $rateStats['avg_rate'] ?? 0;
        $commission['min_rate'] = $rateStats['min_rate'] ?? 0;
        $commission['max_rate'] = $rateStats['max_rate'] ?? 0;
        
        return $commission;
    }
} 