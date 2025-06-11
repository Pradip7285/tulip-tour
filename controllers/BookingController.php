<?php
require_once 'includes/database.php';
require_once 'includes/helpers.php';

class BookingController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function showForm() {
        // Get package details if package_id is provided
        $packageId = $_GET['package_id'] ?? null;
        $package = null;
        
        if ($packageId) {
            $stmt = $this->db->prepare("
                SELECT p.*, c.name as category_name,
                       u.first_name as provider_first_name, u.last_name as provider_last_name,
                       pp.company_name
                FROM packages p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.provider_id = u.id
                LEFT JOIN provider_profiles pp ON u.id = pp.user_id
                WHERE p.id = ? AND p.is_active = 1
            ");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch();
            
            if (!$package) {
                set_flash_message('error', 'Package not found or not available for booking.');
                redirect('/packages');
                return;
            }
        } else {
            set_flash_message('error', 'Please select a package to book.');
            redirect('/packages');
            return;
        }
        
        $pageTitle = 'Book Package - ' . $package['title'];
        $pageDescription = 'Complete your booking for ' . $package['title'];
        
        ob_start();
        include 'views/booking/form.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
    
    public function create() {
        try {
            $packageId = $_POST['package_id'] ?? null;
            $travelDate = $_POST['travel_date'] ?? null;
            $adultsCount = (int)($_POST['adults_count'] ?? 1);
            $childrenCount = (int)($_POST['children_count'] ?? 0);
            $extraRooms = (int)($_POST['extra_rooms'] ?? 0);
            $specialRequirements = $_POST['special_requirements'] ?? '';
            
            // Validation
            if (!$packageId || !$travelDate || $adultsCount < 1) {
                set_flash_message('error', 'Please fill in all required fields.');
                redirect('/booking?package_id=' . $packageId);
                return;
            }
            
            // Validate travel date (must be in future)
            if (strtotime($travelDate) <= time()) {
                set_flash_message('error', 'Travel date must be in the future.');
                redirect('/booking?package_id=' . $packageId);
                return;
            }
            
            // Get package details and pricing
            $stmt = $this->db->prepare("
                SELECT p.*, pp.commission_rate 
                FROM packages p 
                LEFT JOIN provider_profiles pp ON p.provider_id = pp.user_id
                WHERE p.id = ? AND p.is_active = 1
            ");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch();
            
            if (!$package) {
                set_flash_message('error', 'Package not found or not available.');
                redirect('/packages');
                return;
            }
            
            // Check if package has capacity for the requested number of guests
            $totalGuests = $adultsCount + $childrenCount;
            if ($totalGuests > $package['max_guests']) {
                set_flash_message('error', 'Package capacity exceeded. Maximum guests: ' . $package['max_guests']);
                redirect('/booking?package_id=' . $packageId);
                return;
            }
            
            // Calculate pricing
            $baseAmount = $package['base_price'] * $adultsCount;
            $childAmount = $package['child_price'] * $childrenCount;
            $extraRoomAmount = $package['extra_room_price'] * $extraRooms;
            $totalBeforeCommission = $baseAmount + $childAmount + $extraRoomAmount;
            
            // Apply payment gateway fee (2.5%)
            $gatewayFee = $totalBeforeCommission * 0.025;
            $netAfterGateway = $totalBeforeCommission - $gatewayFee;
            
            // Calculate commission (on net amount after gateway fee)
            $commissionRate = $package['commission_rate'] ?? 8.5;
            $commissionAmount = $netAfterGateway * ($commissionRate / 100);
            $providerAmount = $netAfterGateway - $commissionAmount;
            
            // Get current user details
            $user = getCurrentUser();
            
            // Generate unique booking ID
            $bookingId = 'TB' . date('Ymd') . strtoupper(substr(uniqid(), -6));
            
            // Insert booking
            $stmt = $this->db->prepare("
                INSERT INTO bookings (
                    booking_id, package_id, customer_id, customer_name, customer_email, customer_phone,
                    adults_count, children_count, extra_rooms, base_amount, extra_room_amount, total_amount,
                    commission_amount, provider_amount, booking_date, travel_date, special_requirements,
                    status, payment_status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, ?, 'pending', 'pending')
            ");
            
            $success = $stmt->execute([
                $bookingId,
                $packageId,
                $user['id'],
                $user['first_name'] . ' ' . $user['last_name'],
                $user['email'],
                $user['phone'] ?? '',
                $adultsCount,
                $childrenCount,
                $extraRooms,
                $baseAmount + $childAmount,
                $extraRoomAmount,
                $totalBeforeCommission,
                $commissionAmount,
                $providerAmount,
                $travelDate,
                $specialRequirements
            ]);
            
            if ($success) {
                // Update package booking count
                $stmt = $this->db->prepare("UPDATE packages SET total_bookings = total_bookings + 1 WHERE id = ?");
                $stmt->execute([$packageId]);
                
                // Redirect to confirmation page
                redirect('/booking/confirmation?booking_id=' . $bookingId);
            } else {
                set_flash_message('error', 'Failed to create booking. Please try again.');
                redirect('/booking?package_id=' . $packageId);
            }
            
        } catch (Exception $e) {
            error_log("Booking creation error: " . $e->getMessage());
            set_flash_message('error', 'An error occurred while creating your booking. Please try again.');
            redirect('/booking?package_id=' . ($packageId ?? ''));
        }
    }
    
    public function confirmation() {
        $bookingId = $_GET['booking_id'] ?? null;
        
        if (!$bookingId) {
            set_flash_message('error', 'Booking not found.');
            redirect('/customer/bookings');
            return;
        }
        
        // Get booking details
        $user = getCurrentUser();
        $stmt = $this->db->prepare("
            SELECT b.*, p.title as package_title, p.destination, p.duration_days,
                   p.featured_image, u.first_name as provider_first_name, 
                   u.last_name as provider_last_name, pp.company_name
            FROM bookings b
            JOIN packages p ON b.package_id = p.id
            JOIN users u ON p.provider_id = u.id
            LEFT JOIN provider_profiles pp ON u.id = pp.user_id
            WHERE b.booking_id = ? AND b.customer_id = ?
        ");
        $stmt->execute([$bookingId, $user['id']]);
        $booking = $stmt->fetch();
        
        if (!$booking) {
            set_flash_message('error', 'Booking not found.');
            redirect('/customer/bookings');
            return;
        }
        
        $pageTitle = 'Booking Confirmation';
        $pageDescription = 'Your booking has been confirmed.';
        
        ob_start();
        include 'views/booking/confirmation.php';
        $content = ob_get_clean();
        include 'includes/layout.php';
    }
}
?>
