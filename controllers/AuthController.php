<?php

class AuthController {
    private $db;
    
    public function __construct() {
        // Start output buffering to prevent headers already sent errors
        if (!ob_get_level()) {
            ob_start();
        }
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function showLogin() {
        // If already logged in, redirect to appropriate dashboard
        if (isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }
        
        $pageTitle = 'Login - TripBazaar';
        $currentPage = 'login';
        include_once 'views/auth/login.php';
    }
    
    public function showRegister() {
        // If already logged in, redirect to appropriate dashboard
        if (isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }
        
        $pageTitle = 'Register - TripBazaar';
        $currentPage = 'register';
        include_once 'views/auth/register.php';
    }
    
    public function login() {
        // Start output buffering to prevent headers already sent error
        if (!ob_get_level()) {
            ob_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (ob_get_level()) ob_end_clean();
            redirect('/login');
            return;
        }
        
        // Validate CSRF token
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            if (ob_get_level()) ob_end_clean();
            setFlashMessage('Invalid security token', 'error');
            redirect('/login');
            return;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validation
        $errors = [];
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($password)) {
            $errors[] = 'Password is required';
        }
        
        if (!empty($errors)) {
            if (ob_get_level()) ob_end_clean();
            setFlashMessage(implode('<br>', $errors), 'error');
            redirect('/login');
            return;
        }
        
        // Find user by email
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || !password_verify($password, $user['password'])) {
            if (ob_get_level()) ob_end_clean();
            setFlashMessage('Invalid email or password', 'error');
            redirect('/login');
            return;
        }
        
        // Update last login
        $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_email'] = $user['email'];
        
        // Handle remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + (30 * 24 * 60 * 60); // 30 days
            
            // Store token in database
            $stmt = $this->db->prepare("INSERT INTO user_tokens (user_id, token, type, expires_at) VALUES (?, ?, 'remember', ?)");
            $stmt->execute([$user['id'], hash('sha256', $token), date('Y-m-d H:i:s', $expires)]);
            
            // Set cookie
            setcookie('remember_token', $token, $expires, '/', '', true, true);
        }
        
        // Clean any output buffer before flash message and redirect
        if (ob_get_level()) ob_end_clean();
        
        setFlashMessage('Welcome back, ' . $user['first_name'] . '!', 'success');
        $this->redirectToDashboard();
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/register');
            return;
        }
        
        // Validate CSRF token
        if (!validateCSRF($_POST['csrf_token'] ?? '')) {
            setFlashMessage('Invalid security token', 'error');
            redirect('/register');
            return;
        }
        
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? 'customer';
        $agreeTerms = isset($_POST['agree_terms']);
        
        // Validation
        $errors = [];
        
        if (empty($firstName)) {
            $errors[] = 'First name is required';
        } elseif (strlen($firstName) < 2) {
            $errors[] = 'First name must be at least 2 characters';
        }
        
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        } elseif (strlen($lastName) < 2) {
            $errors[] = 'Last name must be at least 2 characters';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($phone)) {
            $errors[] = 'Phone number is required';
        } elseif (!preg_match('/^[+]?[0-9\s\-\(\)]{10,}$/', $phone)) {
            $errors[] = 'Invalid phone number format';
        }
        
        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }
        
        if (!in_array($role, ['customer', 'provider'])) {
            $errors[] = 'Invalid user role';
        }
        
        if (!$agreeTerms) {
            $errors[] = 'You must agree to the terms and conditions';
        }
        
        // Check if email already exists
        if (empty($errors)) {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Email address is already registered';
            }
        }
        
        if (!empty($errors)) {
            setFlashMessage(implode('<br>', $errors), 'error');
            redirect('/register');
            return;
        }
        
        // Create user
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())");
            $stmt->execute([$firstName, $lastName, $email, $phone, $hashedPassword, $role]);
            
            $userId = $this->db->lastInsertId();
            
            // Auto login after registration
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_role'] = $role;
            $_SESSION['user_name'] = $firstName . ' ' . $lastName;
            $_SESSION['user_email'] = $email;
            
            setFlashMessage('Account created successfully! Welcome to TripBazaar.', 'success');
            $this->redirectToDashboard();
            
        } catch (PDOException $e) {
            setFlashMessage('Registration failed. Please try again.', 'error');
            redirect('/register');
        }
    }
    
    public function logout() {
        // Remove remember token if exists
        if (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            $stmt = $this->db->prepare("DELETE FROM user_tokens WHERE token = ? AND type = 'remember'");
            $stmt->execute([hash('sha256', $token)]);
            
            // Clear cookie
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        // Clear session
        session_destroy();
        
        setFlashMessage('You have been logged out successfully', 'success');
        redirect('/');
    }
    
    private function redirectToDashboard() {
        $role = $_SESSION['user_role'] ?? 'customer';
        
        switch ($role) {
            case 'admin':
                redirect('/admin/dashboard');
                break;
            case 'provider':
                redirect('/provider/dashboard');
                break;
            default:
                redirect('/customer/dashboard');
        }
    }
    
    public function checkRememberToken() {
        if (isset($_COOKIE['remember_token']) && !isLoggedIn()) {
            $token = $_COOKIE['remember_token'];
            $hashedToken = hash('sha256', $token);
            
            $stmt = $this->db->prepare("
                SELECT u.* FROM users u 
                JOIN user_tokens ut ON u.id = ut.user_id 
                WHERE ut.token = ? AND ut.type = 'remember' AND ut.expires_at > NOW() AND u.status = 'active'
            ");
            $stmt->execute([$hashedToken]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Auto login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Update last login
                $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
            } else {
                // Invalid or expired token, clear cookie
                setcookie('remember_token', '', time() - 3600, '/', '', true, true);
            }
        }
    }
} 