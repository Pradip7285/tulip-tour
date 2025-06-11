<?php

class PackageController {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function home() {
        // Get featured packages
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name 
            FROM packages p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.is_featured = 1 AND p.is_active = 1 
            ORDER BY p.created_at DESC 
            LIMIT 6
        ");
        $stmt->execute();
        $featuredPackages = $stmt->fetchAll();
        
        // Get testimonials
        $stmt = $this->db->prepare("
            SELECT * FROM testimonials 
            WHERE is_active = 1 
            ORDER BY sort_order ASC, created_at DESC 
            LIMIT 6
        ");
        $stmt->execute();
        $testimonials = $stmt->fetchAll();
        
        // Generate sample data if needed
        generateSamplePackages();
        
        $pageTitle = 'Home';
        $pageDescription = 'Discover amazing travel packages and adventures with ' . AppConfig::get('app_name') . '. Book your perfect trip today!';
        
        ob_start();
        include 'views/home.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function listing() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $itemsPerPage = AppConfig::get('per_page', 12);
        
        // Build WHERE clause based on filters
        $whereConditions = ['p.is_active = 1'];
        $params = [];
        
        // Destination filter
        if (!empty($_GET['destination'])) {
            $whereConditions[] = 'p.destination LIKE ?';
            $params[] = '%' . $_GET['destination'] . '%';
        }
        
        // Budget filter
        if (!empty($_GET['budget'])) {
            $budget = $_GET['budget'];
            if ($budget === '0-500') {
                $whereConditions[] = 'p.base_price <= 500';
            } elseif ($budget === '500-1000') {
                $whereConditions[] = 'p.base_price BETWEEN 500 AND 1000';
            } elseif ($budget === '1000-2500') {
                $whereConditions[] = 'p.base_price BETWEEN 1000 AND 2500';
            } elseif ($budget === '2500+') {
                $whereConditions[] = 'p.base_price >= 2500';
            }
        }
        
        // Duration filter
        if (!empty($_GET['duration'])) {
            $duration = $_GET['duration'];
            if ($duration === '1-3') {
                $whereConditions[] = 'p.duration_days BETWEEN 1 AND 3';
            } elseif ($duration === '4-7') {
                $whereConditions[] = 'p.duration_days BETWEEN 4 AND 7';
            } elseif ($duration === '8-14') {
                $whereConditions[] = 'p.duration_days BETWEEN 8 AND 14';
            } elseif ($duration === '15+') {
                $whereConditions[] = 'p.duration_days >= 15';
            }
        }
        
        // Category filter
        if (!empty($_GET['category'])) {
            $whereConditions[] = 'p.category_id = ?';
            $params[] = $_GET['category'];
        }
        
        // Build ORDER BY clause based on sort parameter
        $orderBy = 'p.created_at DESC';
        $sort = $_GET['sort'] ?? 'featured';
        
        switch ($sort) {
            case 'price_low':
                $orderBy = 'p.base_price ASC';
                break;
            case 'price_high':
                $orderBy = 'p.base_price DESC';
                break;
            case 'rating':
                $orderBy = 'p.rating DESC, p.total_reviews DESC';
                break;
            case 'duration':
                $orderBy = 'p.duration_days ASC';
                break;
            case 'newest':
                $orderBy = 'p.created_at DESC';
                break;
            case 'featured':
            default:
                $orderBy = 'p.is_featured DESC, p.rating DESC, p.total_bookings DESC';
                break;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        // Get total count for pagination
        $countSql = "
            SELECT COUNT(*) as total 
            FROM packages p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE $whereClause
        ";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $totalItems = $stmt->fetch()['total'];
        
        // Calculate pagination
        $totalPages = ceil($totalItems / $itemsPerPage);
        $currentPage = $page;
        $offset = ($currentPage - 1) * $itemsPerPage;
        
        // Build pagination array that matches view expectations
        $pagination = [
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset,
            'has_prev' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'prev_page' => $currentPage > 1 ? $currentPage - 1 : null,
            'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null
        ];
        
        // Get packages
        $sql = "
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   u.first_name as provider_first_name, u.last_name as provider_last_name
            FROM packages p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON p.provider_id = u.id
            WHERE $whereClause 
            ORDER BY $orderBy 
            LIMIT $itemsPerPage OFFSET $offset
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $packages = $stmt->fetchAll();
        
        // Get all categories for filter
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        $categories = $stmt->fetchAll();
        
        // Prepare filters array
        $filters = [
            'destination' => $_GET['destination'] ?? '',
            'budget' => $_GET['budget'] ?? '',
            'duration' => $_GET['duration'] ?? '',
            'category' => $_GET['category'] ?? '',
            'sort' => $sort
        ];
        
        $pageTitle = 'Travel Packages';
        $pageDescription = 'Browse our collection of amazing travel packages and find your perfect adventure.';
        
        ob_start();
        include 'views/packages/listing.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function details($slug) {
        // Get package details
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   u.first_name as provider_first_name, u.last_name as provider_last_name,
                   u.email as provider_email, u.phone as provider_phone,
                   pp.company_name, pp.description as provider_description,
                   pp.is_verified as provider_verified
            FROM packages p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON p.provider_id = u.id
            LEFT JOIN provider_profiles pp ON u.id = pp.user_id
            WHERE p.slug = ? AND p.is_active = 1
        ");
        $stmt->execute([$slug]);
        $package = $stmt->fetch();
        
        if (!$package) {
            http_response_code(404);
            include 'views/404.php';
            return;
        }
        
        // Get package images
        $stmt = $this->db->prepare("
            SELECT * FROM package_images 
            WHERE package_id = ? 
            ORDER BY sort_order ASC, created_at ASC
        ");
        $stmt->execute([$package['id']]);
        $images = $stmt->fetchAll();
        
        // Get package reviews
        $stmt = $this->db->prepare("
            SELECT r.*, u.first_name, u.last_name 
            FROM reviews r 
            JOIN users u ON r.customer_id = u.id 
            WHERE r.package_id = ? AND r.is_approved = 1 
            ORDER BY r.created_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$package['id']]);
        $reviews = $stmt->fetchAll();
        
        // Get similar packages
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name 
            FROM packages p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1 
            ORDER BY p.rating DESC, p.total_bookings DESC 
            LIMIT 4
        ");
        $stmt->execute([$package['category_id'], $package['id']]);
        $similarPackages = $stmt->fetchAll();
        
        $pageTitle = $package['title'];
        $pageDescription = $package['short_description'];
        
        ob_start();
        include 'views/packages/details.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
}

?> 