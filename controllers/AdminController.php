<?php

class AdminController {
    private $db;
    
    public function __construct() {
        // Set proper content type for all admin pages
        header('Content-Type: text/html; charset=UTF-8');
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Payout management methods
    public function payouts() {
        // Get all payouts with provider info
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   CONCAT(u.first_name, ' ', u.last_name) as provider_name,
                   u.email as provider_email
            FROM payouts p
            JOIN users u ON p.provider_id = u.id
            ORDER BY p.requested_at DESC
        ");
        $stmt->execute();
        $payouts = $stmt->fetchAll();
        
        // Get summary stats
        $stats = $this->getPayoutStats();
        
        $pageTitle = 'Payout Management - Admin';
        $pageDescription = 'Manage provider payout requests';
        
        ob_start();
        include 'views/admin/payouts.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function approvePayout($payoutId) {
        try {
            $this->db->beginTransaction();
            
            // Get payout details
            $stmt = $this->db->prepare("
                SELECT p.*, u.first_name, u.last_name 
                FROM payouts p 
                JOIN users u ON p.provider_id = u.id 
                WHERE p.id = ? AND p.status = 'pending'
            ");
            $stmt->execute([$payoutId]);
            $payout = $stmt->fetch();
            
            if (!$payout) {
                throw new Exception('Payout not found or already processed');
            }
            
            // Update payout status
            $stmt = $this->db->prepare("
                UPDATE payouts 
                SET status = 'paid', 
                    processed_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$payoutId]);
            
            $this->db->commit();
            
            set_flash_message('success', "Payout of ₹" . number_format($payout['amount'], 2) . " approved successfully!");
            
        } catch (Exception $e) {
            $this->db->rollback();
            set_flash_message('error', 'Failed to approve payout: ' . $e->getMessage());
        }
        
        header('Location: ' . app_url('/admin/payouts'));
    }
    
    public function rejectPayout($payoutId) {
        try {
            $this->db->beginTransaction();
            
            // Get payout details
            $stmt = $this->db->prepare("
                SELECT p.*, u.first_name, u.last_name 
                FROM payouts p 
                JOIN users u ON p.provider_id = u.id 
                WHERE p.id = ? AND p.status = 'pending'
            ");
            $stmt->execute([$payoutId]);
            $payout = $stmt->fetch();
            
            if (!$payout) {
                throw new Exception('Payout not found or already processed');
            }
            
            // Update payout status
            $stmt = $this->db->prepare("
                UPDATE payouts 
                SET status = 'failed', 
                    processed_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$payoutId]);
            
            $this->db->commit();
            
            set_flash_message('success', "Payout of ₹" . number_format($payout['amount'], 2) . " rejected successfully!");
            
        } catch (Exception $e) {
            $this->db->rollback();
            set_flash_message('error', 'Failed to reject payout: ' . $e->getMessage());
        }
        
        header('Location: ' . app_url('/admin/payouts'));
    }
    
    public function handlePayoutAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            set_flash_message('error', 'Invalid request method');
            return header('Location: ' . app_url('/admin/payouts'));
        }
        
        $payoutId = intval($_POST['payout_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        
        if (!$payoutId || !in_array($action, ['approve', 'reject'])) {
            set_flash_message('error', 'Invalid action or payout ID');
            return header('Location: ' . app_url('/admin/payouts'));
        }
        
        if ($action === 'approve') {
            $this->approvePayout($payoutId);
        } else {
            $this->rejectPayout($payoutId);
        }
    }
    
    // User management methods
    public function users() {
        // Get all users with statistics
        $stmt = $this->db->prepare("
            SELECT u.*, 
                   COUNT(DISTINCT CASE WHEN u.role = 'customer' THEN b.id END) as total_bookings,
                   COUNT(DISTINCT CASE WHEN u.role = 'provider' THEN p.id END) as total_packages,
                   COALESCE(SUM(CASE WHEN u.role = 'customer' AND b.payment_status = 'paid' THEN b.total_amount END), 0) as total_spent,
                   COALESCE(SUM(CASE WHEN u.role = 'provider' AND b.payment_status = 'paid' THEN b.provider_amount END), 0) as total_earned
            FROM users u
            LEFT JOIN bookings b ON u.id = b.customer_id
            LEFT JOIN packages p ON u.id = p.provider_id
            GROUP BY u.id
            ORDER BY u.id DESC
        ");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get user statistics by role
        $stats = $this->getUserStats();
        
        $pageTitle = 'User Management - Admin';
        $currentPage = 'users';
        
        ob_start();
        include 'views/admin/users.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    // Provider management methods
    public function providers() {
        // Get all providers with their performance metrics
        $stmt = $this->db->prepare("
            SELECT u.*, 
                   COUNT(DISTINCT p.id) as total_packages,
                   COUNT(DISTINCT b.id) as total_bookings,
                   COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.provider_amount END), 0) as total_earned,
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(DISTINCT r.id) as total_reviews
            FROM users u
            LEFT JOIN packages p ON u.id = p.provider_id
            LEFT JOIN bookings b ON p.id = b.package_id
            LEFT JOIN reviews r ON p.id = r.package_id
            WHERE u.role = 'provider'
            GROUP BY u.id
            ORDER BY total_earned DESC, u.id DESC
        ");
        $stmt->execute();
        $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get provider statistics
        $stats = $this->getProviderStats();
        
        $pageTitle = 'Provider Management - Admin';
        $currentPage = 'providers';
        
        ob_start();
        include 'views/admin/providers.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    // Package management methods  
    public function packages() {
        // Get all packages with provider info and performance metrics
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   CONCAT(u.first_name, ' ', u.last_name) as provider_name,
                   u.email as provider_email,
                   COUNT(DISTINCT b.id) as total_bookings,
                   COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.total_amount END), 0) as total_revenue,
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(DISTINCT r.id) as total_reviews
            FROM packages p
            JOIN users u ON p.provider_id = u.id
            LEFT JOIN bookings b ON p.id = b.package_id
            LEFT JOIN reviews r ON p.id = r.package_id
            GROUP BY p.id
            ORDER BY p.id DESC
        ");
        $stmt->execute();
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get package statistics
        $stats = $this->getPackageStats();
        
        $pageTitle = 'Package Management - Admin';
        $currentPage = 'packages';
        
        ob_start();
        include 'views/admin/packages.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
        public function updateProviderCommission() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            set_flash_message('error', 'Invalid request method');
            return header('Location: ' . app_url('/admin/providers'));
        }

        $providerId = intval($_POST['provider_id'] ?? 0);
        $commissionRate = floatval($_POST['commission_rate'] ?? 0);

        if (!$providerId || $commissionRate < 0 || $commissionRate > 50) {
            set_flash_message('error', 'Invalid provider ID or commission rate (must be between 0-50%)');
            return header('Location: ' . app_url('/admin/providers'));
        }

        try {
            // Check if provider profile exists
            $stmt = $this->db->prepare("SELECT id FROM provider_profiles WHERE user_id = ?");
            $stmt->execute([$providerId]);
            $profileExists = $stmt->fetch();

            if ($profileExists) {
                // Update existing profile
                $stmt = $this->db->prepare("
                    UPDATE provider_profiles 
                    SET commission_rate = ? 
                    WHERE user_id = ?
                ");
                $stmt->execute([$commissionRate, $providerId]);
            } else {
                // Create basic profile with commission rate
                $stmt = $this->db->prepare("
                    INSERT INTO provider_profiles (user_id, company_name, commission_rate) 
                    VALUES (?, 'Provider Business', ?)
                ");
                $stmt->execute([$providerId, $commissionRate]);
            }

            set_flash_message('success', "Commission rate updated to {$commissionRate}% successfully!");

        } catch (Exception $e) {
            set_flash_message('error', 'Failed to update commission rate: ' . $e->getMessage());
        }

        header('Location: ' . app_url('/admin/providers'));
    }

    public function getPackageDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $packageId = intval($_GET['id'] ?? 0);
        
        if (!$packageId) {
            http_response_code(400);
            echo json_encode(['error' => 'Package ID is required']);
            return;
        }

        try {
            // Get detailed package information
            $stmt = $this->db->prepare("
                SELECT p.*, 
                       CONCAT(u.first_name, ' ', u.last_name) as provider_name,
                       u.email as provider_email,
                       COUNT(DISTINCT b.id) as total_bookings,
                       COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.total_amount END), 0) as total_revenue,
                       COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.commission_amount END), 0) as commission_earned,
                       COALESCE(AVG(r.rating), 0) as avg_rating,
                       COUNT(DISTINCT r.id) as total_reviews,
                       MAX(b.created_at) as last_booking_date,
                       MAX(r.created_at) as last_review_date,
                       MAX(p.updated_at) as last_price_update
                FROM packages p
                JOIN users u ON p.provider_id = u.id
                LEFT JOIN bookings b ON p.id = b.package_id
                LEFT JOIN reviews r ON p.id = r.package_id
                WHERE p.id = ?
                GROUP BY p.id
            ");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$package) {
                http_response_code(404);
                echo json_encode(['error' => 'Package not found']);
                return;
            }

            // Get recent bookings for this package
            $stmt = $this->db->prepare("
                SELECT b.*, CONCAT(u.first_name, ' ', u.last_name) as customer_name
                FROM bookings b
                JOIN users u ON b.customer_id = u.id
                WHERE b.package_id = ?
                ORDER BY b.created_at DESC
                LIMIT 5
            ");
            $stmt->execute([$packageId]);
            $recentBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get recent reviews for this package
            $stmt = $this->db->prepare("
                SELECT r.*, CONCAT(u.first_name, ' ', u.last_name) as customer_name
                FROM reviews r
                JOIN users u ON r.customer_id = u.id
                WHERE r.package_id = ?
                ORDER BY r.created_at DESC
                LIMIT 5
            ");
            $stmt->execute([$packageId]);
            $recentReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format dates for display
            $package['created_date_formatted'] = date('F Y', strtotime($package['created_at']));
            $package['last_booking_formatted'] = $package['last_booking_date'] ? 
                $this->timeAgo($package['last_booking_date']) : 'No bookings yet';
            $package['last_review_formatted'] = $package['last_review_date'] ? 
                $this->timeAgo($package['last_review_date']) : 'No reviews yet';
            $package['last_update_formatted'] = $package['last_price_update'] ? 
                $this->timeAgo($package['last_price_update']) : 'Never updated';

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'package' => $package,
                'recent_bookings' => $recentBookings,
                'recent_reviews' => $recentReviews
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
        }
    }

    private function timeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'Just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        return floor($time/31536000) . ' years ago';
    }

    public function packageAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            set_flash_message('error', 'Invalid request method');
            return header('Location: ' . app_url('/admin/packages'));
        }

        $packageId = intval($_POST['package_id'] ?? 0);
        $action = $_POST['action'] ?? '';

        if (!$packageId || !in_array($action, ['activate', 'deactivate', 'delete'])) {
            set_flash_message('error', 'Invalid action or package ID');
            return header('Location: ' . app_url('/admin/packages'));
        }

        try {
            switch ($action) {
                case 'activate':
                    $stmt = $this->db->prepare("UPDATE packages SET is_active = 1 WHERE id = ?");
                    $stmt->execute([$packageId]);
                    set_flash_message('success', 'Package activated successfully!');
                    break;

                case 'deactivate':
                    $stmt = $this->db->prepare("UPDATE packages SET is_active = 0 WHERE id = ?");
                    $stmt->execute([$packageId]);
                    set_flash_message('success', 'Package deactivated successfully!');
                    break;

                case 'delete':
                    // Check if package has bookings
                    $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM bookings WHERE package_id = ?");
                    $stmt->execute([$packageId]);
                    $bookingCount = $stmt->fetch()['count'];

                    if ($bookingCount > 0) {
                        set_flash_message('error', 'Cannot delete package with existing bookings. Deactivate instead.');
                    } else {
                        $this->db->beginTransaction();
                        
                        // Delete related reviews first
                        $stmt = $this->db->prepare("DELETE FROM reviews WHERE package_id = ?");
                        $stmt->execute([$packageId]);
                        
                        // Delete the package
                        $stmt = $this->db->prepare("DELETE FROM packages WHERE id = ?");
                        $stmt->execute([$packageId]);
                        
                        $this->db->commit();
                        set_flash_message('success', 'Package deleted successfully!');
                    }
                    break;
            }
        } catch (Exception $e) {
            if ($action === 'delete') {
                $this->db->rollback();
            }
            set_flash_message('error', 'Failed to ' . $action . ' package: ' . $e->getMessage());
        }

        header('Location: ' . app_url('/admin/packages'));
    }

    public function getProviderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $providerId = intval($_GET['id'] ?? 0);
        
        if (!$providerId) {
            http_response_code(400);
            echo json_encode(['error' => 'Provider ID is required']);
            return;
        }

        try {
            // Get detailed provider information
            $stmt = $this->db->prepare("
                SELECT u.id, u.first_name, u.last_name, u.email, u.role, u.status, u.created_at, u.updated_at,
                       pp.company_name, pp.commission_rate, pp.description, pp.address, pp.city, pp.state, pp.country,
                       COUNT(DISTINCT p.id) as total_packages,
                       COUNT(DISTINCT CASE WHEN p.is_active = 1 THEN p.id END) as active_packages,
                       COUNT(DISTINCT b.id) as total_bookings,
                       COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.provider_amount END), 0) as total_earnings,
                       COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.commission_amount END), 0) as total_commission_paid,
                       COALESCE(AVG(r.rating), 0) as avg_rating,
                       COUNT(DISTINCT r.id) as total_reviews,
                       MAX(b.created_at) as last_booking_date,
                       MAX(r.created_at) as last_review_date,
                       MAX(p.created_at) as last_package_created
                FROM users u
                LEFT JOIN provider_profiles pp ON u.id = pp.user_id
                LEFT JOIN packages p ON u.id = p.provider_id
                LEFT JOIN bookings b ON p.id = b.package_id
                LEFT JOIN reviews r ON p.id = r.package_id
                WHERE u.id = ? AND u.role = 'provider'
                GROUP BY u.id, u.first_name, u.last_name, u.email, u.role, u.status, u.created_at, u.updated_at,
                         pp.company_name, pp.commission_rate, pp.description, pp.address, pp.city, pp.state, pp.country
            ");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$provider) {
                http_response_code(404);
                echo json_encode(['error' => 'Provider not found']);
                return;
            }

            // Get recent packages by this provider
            $stmt = $this->db->prepare("
                SELECT p.id, p.title, p.slug, p.destination, p.duration_days, p.base_price, p.is_active, p.created_at,
                       COUNT(DISTINCT b.id) as package_bookings,
                       COALESCE(SUM(CASE WHEN b.payment_status = 'paid' THEN b.total_amount END), 0) as package_revenue
                FROM packages p
                LEFT JOIN bookings b ON p.id = b.package_id
                WHERE p.provider_id = ?
                GROUP BY p.id, p.title, p.slug, p.destination, p.duration_days, p.base_price, p.is_active, p.created_at
                ORDER BY p.created_at DESC
                LIMIT 5
            ");
            $stmt->execute([$providerId]);
            $recentPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get recent bookings for this provider
            $stmt = $this->db->prepare("
                SELECT b.*, p.title as package_title, CONCAT(u.first_name, ' ', u.last_name) as customer_name
                FROM bookings b
                JOIN packages p ON b.package_id = p.id
                JOIN users u ON b.customer_id = u.id
                WHERE p.provider_id = ?
                ORDER BY b.created_at DESC
                LIMIT 5
            ");
            $stmt->execute([$providerId]);
            $recentBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get recent reviews for this provider's packages
            $stmt = $this->db->prepare("
                SELECT r.*, p.title as package_title, CONCAT(u.first_name, ' ', u.last_name) as customer_name
                FROM reviews r
                JOIN packages p ON r.package_id = p.id
                JOIN users u ON r.customer_id = u.id
                WHERE p.provider_id = ?
                ORDER BY r.created_at DESC
                LIMIT 5
            ");
            $stmt->execute([$providerId]);
            $recentReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format dates for display
            $provider['join_date_formatted'] = date('F Y', strtotime($provider['created_at']));
            $provider['last_booking_formatted'] = $provider['last_booking_date'] ? 
                $this->timeAgo($provider['last_booking_date']) : 'No bookings yet';
            $provider['last_review_formatted'] = $provider['last_review_date'] ? 
                $this->timeAgo($provider['last_review_date']) : 'No reviews yet';
            $provider['last_package_formatted'] = $provider['last_package_created'] ? 
                $this->timeAgo($provider['last_package_created']) : 'No packages created';

            // Get commission rate (fallback to default if not set)
            $provider['commission_rate'] = $provider['commission_rate'] ?? 10.0;

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'provider' => $provider,
                'recent_packages' => $recentPackages,
                'recent_bookings' => $recentBookings,
                'recent_reviews' => $recentReviews
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
        }
    }

    public function providerAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            set_flash_message('error', 'Invalid request method');
            return header('Location: ' . app_url('/admin/providers'));
        }

        $providerId = intval($_POST['provider_id'] ?? 0);
        $action = $_POST['action'] ?? '';

        if (!$providerId || !in_array($action, ['activate', 'suspend'])) {
            set_flash_message('error', 'Invalid action or provider ID');
            return header('Location: ' . app_url('/admin/providers'));
        }

        try {
            switch ($action) {
                case 'activate':
                    $stmt = $this->db->prepare("UPDATE users SET status = 'active' WHERE id = ? AND role = 'provider'");
                    $stmt->execute([$providerId]);
                    set_flash_message('success', 'Provider activated successfully!');
                    break;

                case 'suspend':
                    $stmt = $this->db->prepare("UPDATE users SET status = 'suspended' WHERE id = ? AND role = 'provider'");
                    $stmt->execute([$providerId]);
                    
                    // Also deactivate all their packages
                    $stmt = $this->db->prepare("UPDATE packages SET is_active = 0 WHERE provider_id = ?");
                    $stmt->execute([$providerId]);
                    
                    set_flash_message('success', 'Provider suspended successfully! All their packages have been deactivated.');
                    break;
            }
        } catch (Exception $e) {
            set_flash_message('error', 'Failed to ' . $action . ' provider: ' . $e->getMessage());
        }

        header('Location: ' . app_url('/admin/providers'));
    }
    
    // Private helper methods
    private function getPayoutStats() {
        $stats = [];
        
        // Total pending payouts
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total 
            FROM payouts WHERE status = 'pending'
        ");
        $stmt->execute();
        $pending = $stmt->fetch();
        $stats['pending_count'] = $pending['count'];
        $stats['pending_amount'] = $pending['total'];
        
        // Total completed payouts (use 'paid' status)
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total 
            FROM payouts WHERE status = 'paid'
        ");
        $stmt->execute();
        $completed = $stmt->fetch();
        $stats['completed_count'] = $completed['count'];
        $stats['completed_amount'] = $completed['total'];
        
        return $stats;
    }
    
    private function getUserStats() {
        $stats = [];
        
        // Count by role
        $stmt = $this->db->prepare("
            SELECT role, COUNT(*) as count
            FROM users 
            GROUP BY role
        ");
        $stmt->execute();
        $roleStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($roleStats as $stat) {
            $stats[$stat['role'] . '_count'] = $stat['count'];
        }
        
        // Set defaults for missing roles
        $stats['customer_count'] = $stats['customer_count'] ?? 0;
        $stats['provider_count'] = $stats['provider_count'] ?? 0;
        $stats['admin_count'] = $stats['admin_count'] ?? 0;
        
        // New users this month
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM users 
            WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
        ");
        $stmt->execute();
        $stats['new_users_month'] = $stmt->fetch()['count'] ?? 0;
        
        return $stats;
    }
    
    private function getProviderStats() {
        $stats = [];
        
        // Active vs inactive providers
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_providers,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_providers,
                SUM(CASE WHEN status != 'active' THEN 1 ELSE 0 END) as inactive_providers
            FROM users 
            WHERE role = 'provider'
        ");
        $stmt->execute();
        $providerStats = $stmt->fetch();
        
        $stats['total_providers'] = $providerStats['total_providers'] ?? 0;
        $stats['active_providers'] = $providerStats['active_providers'] ?? 0;
        $stats['inactive_providers'] = $providerStats['inactive_providers'] ?? 0;
        
        // Top earning provider this month
        $stmt = $this->db->prepare("
            SELECT u.first_name, u.last_name, COALESCE(SUM(b.provider_amount), 0) as earnings
            FROM users u
            LEFT JOIN packages p ON u.id = p.provider_id
            LEFT JOIN bookings b ON p.id = b.package_id AND b.payment_status = 'paid'
                AND DATE_FORMAT(b.booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
            WHERE u.role = 'provider'
            GROUP BY u.id
            ORDER BY earnings DESC
            LIMIT 1
        ");
        $stmt->execute();
        $topProvider = $stmt->fetch();
        $stats['top_provider'] = $topProvider ? $topProvider['first_name'] . ' ' . $topProvider['last_name'] : 'N/A';
        $stats['top_provider_earnings'] = $topProvider['earnings'] ?? 0;
        
        return $stats;
    }
    
    private function getPackageStats() {
        $stats = [];
        
        // Package status counts
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_packages,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_packages,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_packages
            FROM packages
        ");
        $stmt->execute();
        $packageStats = $stmt->fetch();
        
        $stats['total_packages'] = $packageStats['total_packages'] ?? 0;
        $stats['active_packages'] = $packageStats['active_packages'] ?? 0;
        $stats['inactive_packages'] = $packageStats['inactive_packages'] ?? 0;
        
        // Most popular package
        $stmt = $this->db->prepare("
            SELECT p.title, COUNT(b.id) as booking_count
            FROM packages p
            LEFT JOIN bookings b ON p.id = b.package_id
            GROUP BY p.id
            ORDER BY booking_count DESC
            LIMIT 1
        ");
        $stmt->execute();
        $popular = $stmt->fetch();
        $stats['most_popular_package'] = $popular['title'] ?? 'N/A';
        $stats['most_popular_bookings'] = $popular['booking_count'] ?? 0;
        
        // Average package price
        $stmt = $this->db->prepare("SELECT COALESCE(AVG(base_price), 0) as avg_price FROM packages WHERE is_active = 1");
        $stmt->execute();
        $stats['avg_package_price'] = $stmt->fetch()['avg_price'] ?? 0;
        
        return $stats;
    }
} 