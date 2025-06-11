<style>
    .confirmation-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .success-header {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        border-radius: 16px 16px 0 0;
        color: white;
    }
    
    .booking-details {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        padding: 12px 24px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-outline-primary {
        border: 2px solid #667eea;
        color: #667eea;
        background: white;
        border-radius: 12px;
        font-weight: 600;
        padding: 10px 22px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
        text-decoration: none;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-pending {
        background: #fef3cd;
        color: #856404;
    }
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    
    .step {
        display: flex;
        align-items: center;
        color: #cbd5e0;
    }
    
    .step.active,
    .step.completed {
        color: #48bb78;
    }
    
    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        font-weight: 600;
        font-size: 14px;
    }
    
    .step.active .step-number,
    .step.completed .step-number {
        background: #48bb78;
        color: white;
    }
</style>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Step Indicator -->
        <div class="step-indicator mb-8">
            <div class="step completed">
                <div class="step-number"><i class="fas fa-check"></i></div>
                <span class="font-medium">Booking Details</span>
            </div>
            <div class="step completed">
                <div class="step-number"><i class="fas fa-check"></i></div>
                <span class="font-medium">Confirmation</span>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <span class="font-medium">Payment</span>
            </div>
        </div>

        <!-- Success Message -->
        <div class="confirmation-card mb-8">
            <div class="success-header p-8 text-center">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-4xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">Booking Confirmed!</h1>
                <p class="text-lg opacity-90">Your booking request has been submitted successfully.</p>
                <div class="mt-4 text-sm opacity-75">
                    Booking ID: <span class="font-mono font-bold"><?= $booking['booking_id'] ?></span>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Package Details -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Package Details</h3>
                        
                        <?php if ($booking['featured_image']): ?>
                            <img src="<?= htmlspecialchars($booking['featured_image']) ?>" 
                                 alt="<?= htmlspecialchars($booking['package_title']) ?>"
                                 class="w-full h-48 object-cover rounded-lg mb-4">
                        <?php endif; ?>
                        
                        <h4 class="text-lg font-semibold mb-2"><?= htmlspecialchars($booking['package_title']) ?></h4>
                        
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt w-4 mr-2 text-gray-400"></i>
                                <span><?= htmlspecialchars($booking['destination']) ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar w-4 mr-2 text-gray-400"></i>
                                <span><?= $booking['duration_days'] ?> days</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-plane w-4 mr-2 text-gray-400"></i>
                                <span>Travel Date: <?= date('F j, Y', strtotime($booking['travel_date'])) ?></span>
                            </div>
                            <?php if ($booking['company_name']): ?>
                            <div class="flex items-center">
                                <i class="fas fa-building w-4 mr-2 text-gray-400"></i>
                                <span><?= htmlspecialchars($booking['company_name']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Booking Information -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Booking Information</h3>
                        
                        <div class="booking-details p-4">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Booking ID:</span>
                                    <span class="font-mono font-semibold"><?= $booking['booking_id'] ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="status-badge status-pending"><?= ucfirst($booking['status']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Guest Name:</span>
                                    <span class="font-semibold"><?= htmlspecialchars($booking['customer_name']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span><?= htmlspecialchars($booking['customer_email']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phone:</span>
                                    <span><?= htmlspecialchars($booking['customer_phone']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Adults:</span>
                                    <span><?= $booking['adults_count'] ?></span>
                                </div>
                                <?php if ($booking['children_count'] > 0): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Children:</span>
                                    <span><?= $booking['children_count'] ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ($booking['extra_rooms'] > 0): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Extra Rooms:</span>
                                    <span><?= $booking['extra_rooms'] ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span>Total Amount:</span>
                                        <span class="text-green-600"><?= formatPrice($booking['total_amount']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($booking['special_requirements']): ?>
                        <div class="mt-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Special Requirements:</h4>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                <?= htmlspecialchars($booking['special_requirements']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Next Steps -->
        <div class="confirmation-card p-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">What's Next?</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <h4 class="font-semibold mb-2">1. Confirmation</h4>
                    <p class="text-sm text-gray-600">Your booking is being reviewed by the provider. You'll receive confirmation within 24 hours.</p>
                </div>
                
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-credit-card text-green-600"></i>
                    </div>
                    <h4 class="font-semibold mb-2">2. Payment</h4>
                    <p class="text-sm text-gray-600">Once confirmed, you'll receive payment instructions to secure your booking.</p>
                </div>
                
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-plane text-purple-600"></i>
                    </div>
                    <h4 class="font-semibold mb-2">3. Travel</h4>
                    <p class="text-sm text-gray-600">Get ready for your amazing adventure! We'll send you all travel details closer to your date.</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= app_url('/customer/bookings') ?>" class="btn-primary text-center">
                    <i class="fas fa-list mr-2"></i>
                    View All Bookings
                </a>
                <a href="<?= app_url('/packages') ?>" class="btn-outline-primary text-center">
                    <i class="fas fa-search mr-2"></i>
                    Browse More Packages
                </a>
            </div>
        </div>
        
        <!-- Important Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Important Information</h3>
                    <div class="text-blue-800 space-y-2">
                        <p>• A confirmation email has been sent to <strong><?= htmlspecialchars($booking['customer_email']) ?></strong></p>
                        <p>• Your booking is subject to availability and provider confirmation</p>
                        <p>• Payment is required within 24 hours of confirmation to secure your booking</p>
                        <p>• For any questions, contact our support team or the travel provider directly</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh status every 30 seconds
setInterval(function() {
    // You can add AJAX call here to check booking status updates
}, 30000);

// Print booking details
function printBooking() {
    window.print();
}
</script> 