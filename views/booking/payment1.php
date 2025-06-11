<style>
    .payment-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .payment-method {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .payment-method:hover {
        border-color: #667eea;
        background: #f7fafc;
    }
    
    .payment-method.selected {
        border-color: #667eea;
        background: linear-gradient(145deg, #f7fafc 0%, #edf2f7 100%);
    }
    
    .btn-pay {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        padding: 16px 32px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(72, 187, 120, 0.3);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        padding: 12px 24px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-secondary:hover {
        background: #545b62;
        color: white;
        text-decoration: none;
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
    
    .summary-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
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
            <div class="step active">
                <div class="step-number">3</div>
                <span class="font-medium">Payment</span>
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="mb-6">
            <?php displayFlashMessage(); ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="payment-card p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Payment</h2>
                    <p class="text-gray-600 mb-8">Secure your booking by completing the payment below.</p>
                    
                    <form action="<?= app_url('/booking/payment?booking_id=' . urlencode($booking['booking_id'])) ?>" method="POST" id="paymentForm">
                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['booking_id'], ENT_QUOTES, 'UTF-8') ?>">
                        
                        <!-- Payment Methods -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Choose Payment Method</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="payment-method selected" data-method="razorpay">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" value="razorpay" checked class="mr-3">
                                        <div>
                                            <h4 class="font-semibold">Razorpay</h4>
                                            <p class="text-sm text-gray-600">Credit/Debit Card, UPI, Net Banking</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="payment-method" data-method="payu">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" value="payu" class="mr-3">
                                        <div>
                                            <h4 class="font-semibold">PayU</h4>
                                            <p class="text-sm text-gray-600">Multiple payment options</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="payment-method" data-method="bank_transfer">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                                        <div>
                                            <h4 class="font-semibold">Bank Transfer</h4>
                                            <p class="text-sm text-gray-600">Direct bank transfer</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="payment-method" data-method="wallet">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" value="wallet" class="mr-3">
                                        <div>
                                            <h4 class="font-semibold">Digital Wallet</h4>
                                            <p class="text-sm text-gray-600">Paytm, PhonePe, GPay</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-8">
                            <label class="flex items-start">
                                <input type="checkbox" id="agree_payment_terms" class="mt-1 mr-3" required>
                                <span class="text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">Payment Terms</a> 
                                    and <a href="#" class="text-blue-600 hover:underline">Refund Policy</a>. 
                                    I understand that this payment will be processed immediately.
                                </span>
                            </label>
                        </div>
                        
                        <!-- Security Notice -->
                        <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-shield-alt text-blue-500 text-xl mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-900 mb-1">Secure Payment</h4>
                                    <p class="text-blue-800 text-sm">Your payment information is protected with 256-bit SSL encryption. We never store your card details.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn-pay">
                            <i class="fas fa-lock mr-2"></i>
                            Pay Now - <?= formatPrice($booking['total_amount']) ?>
                        </button>
                        
                        <div class="mt-4 text-center">
                            <a href="<?= app_url('/booking/confirmation?booking_id=' . urlencode($booking['booking_id'])) ?>" class="btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Booking
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Booking Summary -->
            <div>
                <div class="summary-card sticky top-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Booking Summary</h3>
                    
                    <?php if (!empty($booking['featured_image'])): ?>
                        <img src="<?= htmlspecialchars($booking['featured_image'], ENT_QUOTES, 'UTF-8') ?>" 
                             alt="<?= htmlspecialchars($booking['package_title'], ENT_QUOTES, 'UTF-8') ?>"
                             class="w-full h-32 object-cover rounded-lg mb-4">
                    <?php endif; ?>
                    
                    <h4 class="font-semibold text-lg mb-2"><?= htmlspecialchars($booking['package_title'], ENT_QUOTES, 'UTF-8') ?></h4>
                    
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt w-4 mr-2 text-gray-400"></i>
                            <span><?= htmlspecialchars($booking['destination'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar w-4 mr-2 text-gray-400"></i>
                            <span><?= htmlspecialchars(date('F j, Y', strtotime($booking['travel_date'])), ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock w-4 mr-2 text-gray-400"></i>
                            <span><?= (int)$booking['duration_days'] ?> days</span>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking ID:</span>
                                <span class="font-mono text-sm"><?= htmlspecialchars($booking['booking_id'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Adults:</span>
                                <span><?= (int)$booking['adults_count'] ?></span>
                            </div>
                            <?php if ($booking['children_count'] > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Children:</span>
                                <span><?= (int)$booking['children_count'] ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($booking['extra_rooms'] > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Extra Rooms:</span>
                                <span><?= (int)$booking['extra_rooms'] ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="border-t pt-3 mt-3">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total Amount:</span>
                                    <span class="text-green-600"><?= formatPrice($booking['total_amount']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-1"></i>
                            <div>
                                <p class="text-yellow-800 text-sm">
                                    <strong>Payment Due:</strong> Complete payment within 24 hours to secure your booking.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Payment method selection
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        // Remove selected class from all
        document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
        
        // Add selected class to clicked method
        this.classList.add('selected');
        
        // Check the radio button
        this.querySelector('input[type="radio"]').checked = true;
    });
});

// Form validation and submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const bookingId = document.querySelector('input[name="booking_id"]').value;
    
    // Simulate payment processing
    const confirmMessage = 'Process payment with ' + paymentMethod + '?\n\nNote: This is a demo payment system. In production, this would integrate with actual payment gateways.';
    
    if (confirm(confirmMessage)) {
        // Show loading state
        const payButton = document.querySelector('.btn-pay');
        payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing Payment...';
        payButton.disabled = true;
        
        // Simulate payment success
        setTimeout(() => {
            window.location.href = '<?= app_url('/booking/confirmation?booking_id=' . urlencode($booking['booking_id']) . '&payment=success') ?>';
        }, 2000);
    }
});
</script>
