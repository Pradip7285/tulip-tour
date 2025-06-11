<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Complete Your Payment</h1>
        
        <!-- Flash Messages -->
        <div class="mb-6">
            <?php displayFlashMessage(); ?>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Payment Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">Payment Details</h2>
                
                <form action="<?= app_url('/booking/payment?booking_id=' . urlencode($booking['booking_id'])) ?>" method="POST" id="paymentForm">
                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['booking_id']) ?>">
                    
                    <!-- Payment Methods -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Choose Payment Method</h3>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="razorpay" checked class="mr-3">
                                <div>
                                    <div class="font-semibold">Razorpay</div>
                                    <div class="text-sm text-gray-600">Credit/Debit Card, UPI, Net Banking</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="payu" class="mr-3">
                                <div>
                                    <div class="font-semibold">PayU</div>
                                    <div class="text-sm text-gray-600">Multiple payment options</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                                <div>
                                    <div class="font-semibold">Bank Transfer</div>
                                    <div class="text-sm text-gray-600">Direct bank transfer</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Terms -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" required class="mt-1 mr-3">
                            <span class="text-sm text-gray-600">
                                I agree to the Terms and Conditions and understand that this payment will be processed immediately.
                            </span>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition">
                        Pay Now - <?= formatPrice($booking['total_amount']) ?>
                    </button>
                    
                    <div class="mt-4 text-center">
                        <a href="<?= app_url('/booking/confirmation?booking_id=' . urlencode($booking['booking_id'])) ?>" 
                           class="text-gray-600 hover:text-gray-800">
                            Back to Booking
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Booking Summary -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Booking Summary</h3>
                
                <h4 class="font-semibold text-lg mb-2"><?= htmlspecialchars($booking['package_title']) ?></h4>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <div>Destination: <?= htmlspecialchars($booking['destination']) ?></div>
                    <div>Travel Date: <?= date('F j, Y', strtotime($booking['travel_date'])) ?></div>
                    <div>Duration: <?= (int)$booking['duration_days'] ?> days</div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Booking ID:</span>
                            <span class="font-mono text-sm"><?= htmlspecialchars($booking['booking_id']) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Adults:</span>
                            <span><?= (int)$booking['adults_count'] ?></span>
                        </div>
                        <?php if ($booking['children_count'] > 0): ?>
                        <div class="flex justify-between">
                            <span>Children:</span>
                            <span><?= (int)$booking['children_count'] ?></span>
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
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    if (confirm('Process payment with ' + paymentMethod + '?\n\nNote: This is a demo payment system.')) {
        // Show loading state
        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.innerHTML = 'Processing Payment...';
        submitButton.disabled = true;
        
        // Simulate payment processing
        setTimeout(() => {
            window.location.href = '<?= app_url('/booking/confirmation?booking_id=' . urlencode($booking['booking_id']) . '&payment=success') ?>';
        }, 2000);
    }
});
</script>
