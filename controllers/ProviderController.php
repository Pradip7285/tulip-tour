<?php

class ProviderController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function dashboard() {
        $providerId = $_SESSION['user_id'];
        
        // Get provider stats
        $stats = $this->getProviderStats($providerId);
        
        // Get recent bookings
        $recentBookings = $this->getRecentBookings($providerId, 5);
        
        // Get active packages
        $activePackages = $this->getActivePackages($providerId, 3);
        
        // Get provider profile
        $providerProfile = $this->getProviderProfile($providerId);
        
        $pageTitle = 'Provider Dashboard - TripBazaar';
        $pageDescription = 'Manage your travel packages and track your business performance';
        
        ob_start();
        include 'views/provider/dashboard.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function packages() {
        $providerId = $_SESSION['user_id'];
        
        // Handle search and filters
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $category = $_GET['category'] ?? '';
        $sort = $_GET['sort'] ?? 'latest';
        
        // Pagination
        $page = max(1, (int)($_GET['page'] ?? 1));
        $itemsPerPage = 12;
        $offset = ($page - 1) * $itemsPerPage;
        
        // Build query
        $whereConditions = ["p.provider_id = ?"];
        $params = [$providerId];
        
        if (!empty($search)) {
            $whereConditions[] = "(p.title LIKE ? OR p.destination LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($status)) {
            $whereConditions[] = "p.is_active = ?";
            $params[] = $status === 'active' ? 1 : 0;
        }
        
        if (!empty($category)) {
            $whereConditions[] = "p.category_id = ?";
            $params[] = $category;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        // Sort options
        $sortOptions = [
            'latest' => 'p.created_at DESC',
            'oldest' => 'p.created_at ASC',
            'title' => 'p.title ASC',
            'bookings' => 'p.total_bookings DESC',
            'rating' => 'p.rating DESC'
        ];
        $orderBy = $sortOptions[$sort] ?? $sortOptions['latest'];
        
        // Get packages
        $sql = "SELECT p.*, c.name as category_name 
                FROM packages p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE $whereClause 
                ORDER BY $orderBy 
                LIMIT $itemsPerPage OFFSET $offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $packages = $stmt->fetchAll();
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM packages p WHERE $whereClause";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $totalItems = $stmt->fetch()['total'];
        
        // Calculate pagination
        $totalPages = ceil($totalItems / $itemsPerPage);
        $pagination = [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages,
            'prev_page' => $page - 1,
            'next_page' => $page + 1
        ];
        
        // Get categories for filter
        $categories = $this->getCategories();
        
        $pageTitle = 'Manage Packages - Provider Dashboard';
        $pageDescription = 'Manage all your travel packages';
        
        ob_start();
        include 'views/provider/packages.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function createPackage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleCreatePackage();
        }
        
        // Get categories for the form
        $categories = $this->getCategories();
        
        $pageTitle = 'Create New Package - Provider Dashboard';
        $pageDescription = 'Add a new travel package to your listings';
        
        ob_start();
        include 'views/provider/create-package.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function editPackage($packageId) {
        $providerId = $_SESSION['user_id'];
        
        // Verify package ownership
        $package = $this->getPackageByIdAndProvider($packageId, $providerId);
        if (!$package) {
            http_response_code(404);
            include 'views/404.php';
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleUpdatePackage($packageId);
        }
        
        // Get categories for the form
        $categories = $this->getCategories();
        
        // Get package images
        $packageImages = $this->getPackageImages($packageId);
        
        $pageTitle = 'Edit Package - Provider Dashboard';
        $pageDescription = 'Update your package details';
        
        ob_start();
        include 'views/provider/edit-package.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function deletePackage($packageId) {
        $providerId = $_SESSION['user_id'];
        
        // Verify package ownership
        $package = $this->getPackageByIdAndProvider($packageId, $providerId);
        if (!$package) {
            return json_response(['success' => false, 'message' => 'Package not found']);
        }
        
        // Check if package has active bookings
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM bookings WHERE package_id = ? AND status IN ('pending', 'confirmed')");
        $stmt->execute([$packageId]);
        $activeBookings = $stmt->fetch()['count'];
        
        if ($activeBookings > 0) {
            return json_response(['success' => false, 'message' => 'Cannot delete package with active bookings']);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Delete package images
            $stmt = $this->db->prepare("DELETE FROM package_images WHERE package_id = ?");
            $stmt->execute([$packageId]);
            
            // Delete package
            $stmt = $this->db->prepare("DELETE FROM packages WHERE id = ? AND provider_id = ?");
            $stmt->execute([$packageId, $providerId]);
            
            $this->db->commit();
            
            return json_response(['success' => true, 'message' => 'Package deleted successfully']);
        } catch (Exception $e) {
            $this->db->rollback();
            return json_response(['success' => false, 'message' => 'Failed to delete package']);
        }
    }
    
    public function bookings() {
        $providerId = $_SESSION['user_id'];
        
        // Handle filters
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        
        // Pagination
        $page = max(1, (int)($_GET['page'] ?? 1));
        $itemsPerPage = 20;
        $offset = ($page - 1) * $itemsPerPage;
        
        // Build query
        $whereConditions = ["p.provider_id = ?"];
        $params = [$providerId];
        
        if (!empty($status)) {
            $whereConditions[] = "b.status = ?";
            $params[] = $status;
        }
        
        if (!empty($search)) {
            $whereConditions[] = "(b.booking_id LIKE ? OR b.customer_name LIKE ? OR b.customer_email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($dateFrom)) {
            $whereConditions[] = "b.created_at >= ?";
            $params[] = $dateFrom . ' 00:00:00';
        }
        
        if (!empty($dateTo)) {
            $whereConditions[] = "b.created_at <= ?";
            $params[] = $dateTo . ' 23:59:59';
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        // Get bookings
        $sql = "SELECT b.*, p.title as package_title, p.destination 
                FROM bookings b 
                JOIN packages p ON b.package_id = p.id 
                WHERE $whereClause 
                ORDER BY b.created_at DESC 
                LIMIT $itemsPerPage OFFSET $offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $bookings = $stmt->fetchAll();
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM bookings b JOIN packages p ON b.package_id = p.id WHERE $whereClause";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $totalItems = $stmt->fetch()['total'];
        
        // Calculate pagination
        $totalPages = ceil($totalItems / $itemsPerPage);
        $pagination = [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages,
            'prev_page' => $page - 1,
            'next_page' => $page + 1
        ];
        
        $pageTitle = 'Bookings - Provider Dashboard';
        $pageDescription = 'Manage your package bookings';
        
        ob_start();
        include 'views/provider/bookings.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function updateBookingStatus($bookingId) {
        $providerId = $_SESSION['user_id'];
        $status = $_POST['status'] ?? '';
        
        // Validate status
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $validStatuses)) {
            return json_response(['success' => false, 'message' => 'Invalid status']);
        }
        
        // Verify booking belongs to provider
        $stmt = $this->db->prepare("SELECT b.id FROM bookings b JOIN packages p ON b.package_id = p.id WHERE b.id = ? AND p.provider_id = ?");
        $stmt->execute([$bookingId, $providerId]);
        
        if (!$stmt->fetch()) {
            return json_response(['success' => false, 'message' => 'Booking not found']);
        }
        
        // Update booking status
        $stmt = $this->db->prepare("UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?");
        $success = $stmt->execute([$status, $bookingId]);
        
        if ($success) {
            return json_response(['success' => true, 'message' => 'Booking status updated successfully']);
        } else {
            return json_response(['success' => false, 'message' => 'Failed to update booking status']);
        }
    }
    
    public function profile() {
        $providerId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleUpdateProfile();
        }
        
        // Get provider profile
        $profile = $this->getProviderProfile($providerId);
        
        $pageTitle = 'Provider Profile - Provider Dashboard';
        $pageDescription = 'Manage your provider profile and business information';
        
        ob_start();
        include 'views/provider/profile.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function earnings() {
        $providerId = $_SESSION['user_id'];
        
        // Get earnings summary
        $earningsData = $this->getEarningsData($providerId);
        
        // Calculate total earnings
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid'
        ");
        $stmt->execute([$providerId]);
        $totalEarnings = $stmt->fetch()['total'];
        
        // Calculate monthly earnings (current month)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid'
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
        ");
        $stmt->execute([$providerId]);
        $monthlyEarnings = $stmt->fetch()['total'];
        
        // Calculate pending payouts (confirmed bookings ready for payout)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid' AND b.status = 'confirmed'
        ");
        $stmt->execute([$providerId]);
        $pendingPayouts = $stmt->fetch()['total'];
        
        // Calculate completed payouts (actual payouts that have been processed and paid)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM payouts 
            WHERE provider_id = ? AND status = 'completed'
        ");
        $stmt->execute([$providerId]);
        $completedPayouts = $stmt->fetch()['total'];
        
        // Calculate earnings from completed bookings (available for payout)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid' AND b.status = 'completed'
        ");
        $stmt->execute([$providerId]);
        $completedBookingEarnings = $stmt->fetch()['total'];
        
        // Calculate total pending/processing payouts
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM payouts 
            WHERE provider_id = ? AND status IN ('pending', 'processing')
        ");
        $stmt->execute([$providerId]);
        $pendingPayoutRequests = $stmt->fetch()['total'];
        
        // Calculate available amount for payout (subtract pending requests)
        $availableForPayout = $completedBookingEarnings - $pendingPayoutRequests;
        
        // Get provider's negotiated commission rate
        $commissionRate = getProviderCommissionRate($providerId);
        $providerEarningRate = 100 - $commissionRate;
        
        // Get recent payouts
        $recentPayouts = $this->getRecentPayouts($providerId);
        
        // Get banking details for payout validation
        $bankDetails = $this->getProviderBankDetails($providerId);
        
        $pageTitle = 'Earnings - Provider Dashboard';
        $pageDescription = 'Track your earnings and payout history';
        
        ob_start();
        include 'views/provider/earnings.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function togglePackageStatus($packageId) {
        $providerId = $_SESSION['user_id'];
        
        // Verify package ownership
        $package = $this->getPackageByIdAndProvider($packageId, $providerId);
        if (!$package) {
            return json_response(['success' => false, 'message' => 'Package not found']);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $activate = $input['activate'] ?? false;
        
        try {
            $stmt = $this->db->prepare("UPDATE packages SET is_active = ?, updated_at = NOW() WHERE id = ? AND provider_id = ?");
            $success = $stmt->execute([$activate ? 1 : 0, $packageId, $providerId]);
            
            if ($success) {
                $action = $activate ? 'activated' : 'deactivated';
                return json_response(['success' => true, 'message' => "Package $action successfully"]);
            } else {
                return json_response(['success' => false, 'message' => 'Failed to update package status']);
            }
        } catch (Exception $e) {
            return json_response(['success' => false, 'message' => 'Database error occurred']);
        }
    }
    
    public function duplicatePackage($packageId) {
        $providerId = $_SESSION['user_id'];
        
        // Verify package ownership
        $package = $this->getPackageByIdAndProvider($packageId, $providerId);
        if (!$package) {
            return json_response(['success' => false, 'message' => 'Package not found']);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Generate new title and slug
            $newTitle = $package['title'] . ' (Copy)';
            $newSlug = generateSlug($newTitle);
            
            // Make sure slug is unique
            $counter = 1;
            $originalSlug = $newSlug;
            while ($this->packageSlugExists($newSlug)) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            // Insert duplicated package
            $stmt = $this->db->prepare("
                INSERT INTO packages (
                    provider_id, category_id, title, slug, description, short_description,
                    destination, duration_days, max_guests, max_guests_per_room, child_free_age,
                    base_price, child_price, extra_room_price,
                    price_tier_1_max, price_tier_1_rate,
                    price_tier_2_max, price_tier_2_rate,
                    price_tier_3_max, price_tier_3_rate,
                    price_tier_4_max, price_tier_4_rate,
                    inclusions, exclusions, terms_conditions,
                    featured_image, is_active, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $providerId,
                $package['category_id'],
                $newTitle,
                $newSlug,
                $package['description'],
                $package['short_description'],
                $package['destination'],
                $package['duration_days'],
                $package['max_guests'],
                $package['max_guests_per_room'] ?? 2,
                $package['child_free_age'] ?? 7,
                $package['base_price'],
                $package['child_price'],
                $package['extra_room_price'],
                $package['price_tier_1_max'] ?? 2,
                $package['price_tier_1_rate'] ?? 0,
                $package['price_tier_2_max'] ?? 4,
                $package['price_tier_2_rate'] ?? 0,
                $package['price_tier_3_max'] ?? 6,
                $package['price_tier_3_rate'] ?? 0,
                $package['price_tier_4_max'] ?? 8,
                $package['price_tier_4_rate'] ?? 0,
                $package['inclusions'],
                $package['exclusions'],
                $package['terms_conditions'],
                $package['featured_image'],
                0 // Set as inactive initially
            ]);
            
            $newPackageId = $this->db->lastInsertId();
            
            // Copy package images if any
            $stmt = $this->db->prepare("SELECT * FROM package_images WHERE package_id = ?");
            $stmt->execute([$packageId]);
            $images = $stmt->fetchAll();
            
            foreach ($images as $image) {
                $stmt = $this->db->prepare("INSERT INTO package_images (package_id, image_url, sort_order) VALUES (?, ?, ?)");
                $stmt->execute([$newPackageId, $image['image_url'], $image['sort_order']]);
            }
            
            $this->db->commit();
            
            return json_response([
                'success' => true, 
                'message' => 'Package duplicated successfully',
                'newPackageId' => $newPackageId
            ]);
            
        } catch (Exception $e) {
            $this->db->rollback();
            return json_response(['success' => false, 'message' => 'Failed to duplicate package']);
        }
    }
    
    // Private helper methods
    private function getProviderStats($providerId) {
        $stats = [];
        
        // Total packages
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM packages WHERE provider_id = ?");
        $stmt->execute([$providerId]);
        $stats['total_packages'] = $stmt->fetch()['total'];
        
        // Active packages
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM packages WHERE provider_id = ? AND is_active = 1");
        $stmt->execute([$providerId]);
        $stats['active_packages'] = $stmt->fetch()['total'];
        
        // Total bookings
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ?
        ");
        $stmt->execute([$providerId]);
        $stats['total_bookings'] = $stmt->fetch()['total'];
        
        // Total earnings
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid'
        ");
        $stmt->execute([$providerId]);
        $stats['total_earnings'] = $stmt->fetch()['total'];
        
        // Monthly earnings (current month)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid'
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
        ");
        $stmt->execute([$providerId]);
        $stats['monthly_earnings'] = $stmt->fetch()['total'];
        
        // Pending bookings
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.status = 'pending'
        ");
        $stmt->execute([$providerId]);
        $stats['pending_bookings'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    private function getRecentBookings($providerId, $limit = 5) {
        $stmt = $this->db->prepare("
            SELECT b.*, p.title as package_title, p.destination 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? 
            ORDER BY b.booking_date DESC 
            LIMIT ?
        ");
        $stmt->execute([$providerId, $limit]);
        return $stmt->fetchAll();
    }
    
    private function getActivePackages($providerId, $limit = 3) {
        $stmt = $this->db->prepare("
            SELECT * FROM packages 
            WHERE provider_id = ? AND is_active = 1 
            ORDER BY total_bookings DESC 
            LIMIT ?
        ");
        $stmt->execute([$providerId, $limit]);
        return $stmt->fetchAll();
    }
    
    private function getProviderProfile($providerId) {
        $stmt = $this->db->prepare("
            SELECT u.*, pp.* 
            FROM users u 
            LEFT JOIN provider_profiles pp ON u.id = pp.user_id 
            WHERE u.id = ?
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetch();
    }
    
    private function getCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function getPackageByIdAndProvider($packageId, $providerId) {
        $stmt = $this->db->prepare("SELECT * FROM packages WHERE id = ? AND provider_id = ?");
        $stmt->execute([$packageId, $providerId]);
        return $stmt->fetch();
    }
    
    private function getPackageImages($packageId) {
        $stmt = $this->db->prepare("SELECT * FROM package_images WHERE package_id = ? ORDER BY sort_order");
        $stmt->execute([$packageId]);
        return $stmt->fetchAll();
    }
    
    private function handleCreatePackage() {
        $providerId = $_SESSION['user_id'];
        
        // Validate required fields
        $required = ['title', 'description', 'destination', 'duration_days', 'base_price', 'child_price'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                set_flash_message('error', "Please fill in all required fields");
                return header('Location: ' . app_url('/provider/packages/create'));
            }
        }
        
        try {
            $this->db->beginTransaction();
            
            // Generate slug
            $slug = generateSlug($_POST['title']);
            
            // Insert package with tiered pricing support
            $stmt = $this->db->prepare("
                INSERT INTO packages (
                    provider_id, category_id, title, slug, description, short_description,
                    destination, duration_days, max_guests, max_guests_per_room, child_free_age,
                    base_price, child_price, extra_room_price,
                    price_tier_1_max, price_tier_1_rate,
                    price_tier_2_max, price_tier_2_rate,
                    price_tier_3_max, price_tier_3_rate,
                    price_tier_4_max, price_tier_4_rate,
                    inclusions, exclusions, terms_conditions,
                    featured_image, is_active, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $providerId,
                $_POST['category_id'] ?: null,
                $_POST['title'],
                $slug,
                $_POST['description'],
                $_POST['short_description'] ?? '',
                $_POST['destination'],
                $_POST['duration_days'],
                $_POST['max_guests'] ?? 20,
                $_POST['max_guests_per_room'] ?? 2,
                $_POST['child_free_age'] ?? 7,
                $_POST['base_price'],
                $_POST['child_price'],
                $_POST['extra_room_price'] ?? 0,
                // Tiered pricing
                $_POST['price_tier_1_max'] ?? 2,
                $_POST['price_tier_1_rate'] ?? 0,
                $_POST['price_tier_2_max'] ?? 4,
                $_POST['price_tier_2_rate'] ?? 0,
                $_POST['price_tier_3_max'] ?? 6,
                $_POST['price_tier_3_rate'] ?? 0,
                $_POST['price_tier_4_max'] ?? 8,
                $_POST['price_tier_4_rate'] ?? 0,
                $_POST['inclusions'] ?? '',
                $_POST['exclusions'] ?? '',
                $_POST['terms_conditions'] ?? '',
                $_POST['featured_image'] ?? '',
                isset($_POST['is_active']) ? 1 : 0
            ]);
            
            $packageId = $this->db->lastInsertId();
            
            // Handle additional images if provided
            if (!empty($_POST['additional_images'])) {
                $images = explode("\n", $_POST['additional_images']);
                $order = 1;
                foreach ($images as $image) {
                    $image = trim($image);
                    if ($image) {
                        $stmt = $this->db->prepare("INSERT INTO package_images (package_id, image_url, sort_order) VALUES (?, ?, ?)");
                        $stmt->execute([$packageId, $image, $order++]);
                    }
                }
            }
            
            $this->db->commit();
            
            set_flash_message('success', 'Package created successfully! Your package is now available for booking.');
            header('Location: ' . app_url('/provider/packages'));
            
        } catch (Exception $e) {
            $this->db->rollback();
            set_flash_message('error', 'Failed to create package: ' . $e->getMessage());
            header('Location: ' . app_url('/provider/packages/create'));
        }
    }
    
    private function handleUpdatePackage($packageId) {
        $providerId = $_SESSION['user_id'];
        
        // Verify package ownership
        $package = $this->getPackageByIdAndProvider($packageId, $providerId);
        if (!$package) {
            set_flash_message('error', 'Package not found');
            return header('Location: ' . app_url('/provider/packages'));
        }
        
        try {
            $this->db->beginTransaction();
            
            // Update package
            $stmt = $this->db->prepare("
                UPDATE packages SET 
                    category_id = ?, title = ?, description = ?, short_description = ?,
                    destination = ?, duration_days = ?, max_guests = ?, base_price = ?,
                    child_price = ?, extra_room_price = ?, inclusions = ?, exclusions = ?,
                    terms_conditions = ?, featured_image = ?, is_active = ?, updated_at = NOW()
                WHERE id = ? AND provider_id = ?
            ");
            
            $stmt->execute([
                $_POST['category_id'] ?: null,
                $_POST['title'],
                $_POST['description'],
                $_POST['short_description'] ?? '',
                $_POST['destination'],
                $_POST['duration_days'],
                $_POST['max_guests'] ?? 50,
                $_POST['base_price'],
                $_POST['child_price'],
                $_POST['extra_room_price'] ?? 0,
                $_POST['inclusions'] ?? '',
                $_POST['exclusions'] ?? '',
                $_POST['terms_conditions'] ?? '',
                $_POST['featured_image'] ?? '',
                isset($_POST['is_active']) ? 1 : 0,
                $packageId,
                $providerId
            ]);
            
            $this->db->commit();
            
            set_flash_message('success', 'Package updated successfully!');
            header('Location: ' . app_url('/provider/packages'));
            
        } catch (Exception $e) {
            $this->db->rollback();
            set_flash_message('error', 'Failed to update package. Please try again.');
            header('Location: ' . app_url('/provider/packages/edit/' . $packageId));
        }
    }
    
    private function handleUpdateProfile() {
        $providerId = $_SESSION['user_id'];
        
        try {
            $this->db->beginTransaction();
            
            // Update user table
            $stmt = $this->db->prepare("
                UPDATE users SET 
                    first_name = ?, last_name = ?, phone = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['phone'],
                $providerId
            ]);
            
            // Check if provider profile exists
            $stmt = $this->db->prepare("SELECT id FROM provider_profiles WHERE user_id = ?");
            $stmt->execute([$providerId]);
            $profileExists = $stmt->fetch();
            
            if ($profileExists) {
                // Update existing profile
                $stmt = $this->db->prepare("
                    UPDATE provider_profiles SET 
                        company_name = ?, description = ?, license_number = ?,
                        address = ?, city = ?, state = ?, country = ?,
                        bank_name = ?, account_number = ?, account_holder_name = ?, ifsc_code = ?
                    WHERE user_id = ?
                ");
                $stmt->execute([
                    $_POST['company_name'],
                    $_POST['description'],
                    $_POST['license_number'],
                    $_POST['address'],
                    $_POST['city'],
                    $_POST['state'],
                    $_POST['country'],
                    $_POST['bank_name'],
                    $_POST['account_number'],
                    $_POST['account_holder_name'],
                    $_POST['ifsc_code'],
                    $providerId
                ]);
            } else {
                // Create new profile
                $stmt = $this->db->prepare("
                    INSERT INTO provider_profiles (
                        user_id, company_name, description, license_number,
                        address, city, state, country,
                        bank_name, account_number, account_holder_name, ifsc_code
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $providerId,
                    $_POST['company_name'],
                    $_POST['description'],
                    $_POST['license_number'],
                    $_POST['address'],
                    $_POST['city'],
                    $_POST['state'],
                    $_POST['country'],
                    $_POST['bank_name'],
                    $_POST['account_number'],
                    $_POST['account_holder_name'],
                    $_POST['ifsc_code']
                ]);
            }
            
            $this->db->commit();
            
            set_flash_message('success', 'Profile updated successfully!');
            header('Location: ' . app_url('/provider/profile'));
            
        } catch (Exception $e) {
            $this->db->rollback();
            set_flash_message('error', 'Failed to update profile. Please try again.');
            header('Location: ' . app_url('/provider/profile'));
        }
    }
    
    private function getEarningsData($providerId) {
        // Get monthly earnings for the last 12 months
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(b.booking_date, '%Y-%m') as month,
                SUM(b.provider_amount) as earnings
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid'
            AND b.booking_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(b.booking_date, '%Y-%m')
            ORDER BY month ASC
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll();
    }
    
    private function getRecentPayouts($providerId) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   COALESCE(b.booking_id, 'N/A') as booking_id,
                   COALESCE(pk.title, 'General Payout') as package_title
            FROM payouts p
            LEFT JOIN bookings b ON p.booking_id = b.id
            LEFT JOIN packages pk ON b.package_id = pk.id
            WHERE p.provider_id = ?
            ORDER BY p.requested_at DESC
            LIMIT 10
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll();
    }
    
    private function getProviderBankDetails($providerId) {
        $stmt = $this->db->prepare("
            SELECT bank_name, account_number, account_holder_name, ifsc_code
            FROM provider_profiles 
            WHERE user_id = ?
        ");
        $stmt->execute([$providerId]);
        $details = $stmt->fetch();
        
        // Check if all required fields are filled
        if ($details && !empty($details['bank_name']) && !empty($details['account_number']) 
            && !empty($details['account_holder_name']) && !empty($details['ifsc_code'])) {
            return $details;
        }
        
        return false;
    }
    
    public function requestPayout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            set_flash_message('error', 'Invalid request method');
            return header('Location: ' . app_url('/provider/earnings'));
        }
        
        $providerId = $_SESSION['user_id'];
        
        // Validate input
        $payoutAmount = floatval($_POST['payout_amount'] ?? 0);
        $payoutNotes = trim($_POST['payout_notes'] ?? '');
        
        if ($payoutAmount < 500) {
            set_flash_message('error', 'Minimum payout amount is ₹500');
            return header('Location: ' . app_url('/provider/earnings'));
        }
        
        // Check available balance (same logic as earnings page)
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(provider_amount), 0) as total 
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.provider_id = ? AND b.payment_status = 'paid' AND b.status = 'completed'
        ");
        $stmt->execute([$providerId]);
        $completedAmount = $stmt->fetch()['total'];
        
        // Subtract pending/processing payouts
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM payouts 
            WHERE provider_id = ? AND status IN ('pending', 'processing')
        ");
        $stmt->execute([$providerId]);
        $pendingAmount = $stmt->fetch()['total'];
        
        $availableAmount = $completedAmount - $pendingAmount;
        
        if ($payoutAmount > $availableAmount) {
            set_flash_message('error', 'Insufficient available balance for payout. Available: ₹' . number_format($availableAmount, 2));
            return header('Location: ' . app_url('/provider/earnings'));
        }
        
        // Check banking details
        $bankDetails = $this->getProviderBankDetails($providerId);
        if (!$bankDetails) {
            set_flash_message('error', 'Please complete your banking details before requesting a payout');
            return header('Location: ' . app_url('/provider/profile'));
        }
        
        try {
            $this->db->beginTransaction();
            
            // Create payout request
            $stmt = $this->db->prepare("
                INSERT INTO payouts (provider_id, amount, status, notes, requested_at) 
                VALUES (?, ?, 'pending', ?, NOW())
            ");
            $stmt->execute([$providerId, $payoutAmount, $payoutNotes]);
            
            $this->db->commit();
            
            set_flash_message('success', "Payout request of ₹" . number_format($payoutAmount, 2) . " submitted successfully! You will receive an email confirmation shortly.");
            
        } catch (Exception $e) {
            $this->db->rollback();
            set_flash_message('error', 'Failed to submit payout request. Please try again.');
        }
        
        header('Location: ' . app_url('/provider/earnings'));
    }
    
    private function packageSlugExists($slug) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM packages WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetchColumn() > 0;
    }
}