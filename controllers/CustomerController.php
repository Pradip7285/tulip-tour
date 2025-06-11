<?php

class CustomerController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function bookings() {
        $user = getCurrentUser();
        
        // Get all bookings for this customer
        $stmt = $this->db->prepare("
            SELECT b.*, p.title as package_title, p.destination, p.duration_days, p.slug
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            WHERE b.customer_id = ? 
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$user['id']]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $pageTitle = 'My Bookings - TripBazaar';
        $currentPage = 'bookings';
        include_once 'views/customer/bookings.php';
    }
    
    public function profile() {
        $user = getCurrentUser();
        
        // Handle profile update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile($user['id']);
            return;
        }
        
        $pageTitle = 'My Profile - TripBazaar';
        $currentPage = 'profile';
        include_once 'views/customer/profile.php';
    }
    
    public function reviews() {
        $user = getCurrentUser();
        
        // Get reviews written by this customer
        $stmt = $this->db->prepare("
            SELECT r.*, p.title as package_title, p.slug as package_slug, p.destination
            FROM reviews r 
            JOIN packages p ON r.package_id = p.id 
            WHERE r.customer_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$user['id']]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get completed bookings that can be reviewed
        $stmt = $this->db->prepare("
            SELECT b.*, p.title as package_title, p.slug as package_slug, p.destination
            FROM bookings b 
            JOIN packages p ON b.package_id = p.id 
            LEFT JOIN reviews r ON r.booking_id = b.id AND r.customer_id = ?
            WHERE b.customer_id = ? AND b.status = 'completed' AND r.id IS NULL
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$user['id'], $user['id']]);
        $pendingReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $pageTitle = 'My Reviews - TripBazaar';
        $currentPage = 'reviews';
        include_once 'views/customer/reviews.php';
    }
    
    private function updateProfile($userId) {
        // Validate CSRF token
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Invalid security token', 'error');
            redirect('/customer/profile');
            return;
        }
        
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        // Validation
        $errors = [];
        
        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }
        
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (!empty($phone) && !preg_match('/^[+]?[0-9\s\-\(\)]{10,}$/', $phone)) {
            $errors[] = 'Invalid phone number format';
        }
        
        // Check if email is already taken by another user
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            $errors[] = 'Email address is already taken';
        }
        
        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'error');
            redirect('/customer/profile');
            return;
        }
        
        // Update profile
        try {
            $stmt = $this->db->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$firstName, $lastName, $email, $phone, $userId]);
            
            // Update session data
            $_SESSION['user_name'] = $firstName . ' ' . $lastName;
            $_SESSION['user_email'] = $email;
            
            setFlashMessage('Profile updated successfully!', 'success');
            redirect('/customer/profile');
            
        } catch (PDOException $e) {
            setFlashMessage('Failed to update profile. Please try again.', 'error');
            redirect('/customer/profile');
        }
    }
}
?> 