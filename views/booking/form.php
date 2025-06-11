<?php
$user = getCurrentUser();
?>

<style>
    .booking-form {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .package-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        color: white;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.3s ease;
        padding: 12px 16px;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .price-summary {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }
    
    .btn-book {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        padding: 16px 32px;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-book:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        color: white;
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
    
    .step.active {
        color: #667eea;
    }
    
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
    
    .step.active .step-number {
        background: #667eea;
        color: white;
    }
    
    .step.completed .step-number {
        background: #48bb78;
        color: white;
    }
</style>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Step Indicator -->
        <div class="step-indicator mb-8">
            <div class="step active">
                <div class="step-number">1</div>
                <span class="font-medium">Booking Details</span>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <span class="font-medium">Confirmation</span>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <span class="font-medium">Payment</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Package Summary -->
            <div class="lg:col-span-1">
                <div class="package-summary p-6 sticky top-6">
                    <h3 class="text-2xl font-bold mb-4">Package Summary</h3>
                    
                    <?php if ($package['featured_image']): ?>
                        <img src="<?= htmlspecialchars($package['featured_image']) ?>" 
                             alt="<?= htmlspecialchars($package['title']) ?>"
                             class="w-full h-48 object-cover rounded-lg mb-4">
                    <?php endif; ?>
                    
                    <h4 class="text-xl font-semibold mb-2"><?= htmlspecialchars($package['title']) ?></h4>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span><?= htmlspecialchars($package['destination']) ?></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            <span><?= $package['duration_days'] ?> days</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>Max <?= $package['max_guests'] ?> guests</span>
                        </div>
                        <?php if ($package['company_name']): ?>
                        <div class="flex items-center">
                            <i class="fas fa-building mr-2"></i>
                            <span><?= htmlspecialchars($package['company_name']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="border-t border-white border-opacity-20 pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span>Adult Price:</span>
                            <span class="font-semibold"><?= formatPrice($package['base_price']) ?></span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span>Child Price:</span>
                            <span class="font-semibold"><?= formatPrice($package['child_price']) ?></span>
                        </div>
                        <?php if ($package['extra_room_price'] > 0): ?>
                        <div class="flex justify-between items-center">
                            <span>Extra Room:</span>
                            <span class="font-semibold"><?= formatPrice($package['extra_room_price']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <div class="booking-form p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Complete Your Booking</h2>
                    
                    <div class="mb-6">
                        <?php displayFlashMessage(); ?>
                    </div>
                    
                    <form action="<?= app_url('/booking') ?>" method="POST" id="bookingForm">
                        <input type="hidden" name="package_id" value="<?= $package['id'] ?>">
                        
                        <!-- Personal Information -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" id="customer_name" name="customer_name" 
                                           value="<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>"
                                           class="w-full bg-gray-100" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input type="email" id="customer_email" name="customer_email" 
                                           value="<?= htmlspecialchars($user['email']) ?>"
                                           class="w-full bg-gray-100" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" id="customer_phone" name="customer_phone" 
                                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                           class="w-full" placeholder="Enter your phone number">
                                </div>
                                <div class="form-group">
                                    <label for="travel_date" class="block text-sm font-medium text-gray-700 mb-2">Travel Date <span class="text-red-500">*</span></label>
                                    <input type="date" id="travel_date" name="travel_date" 
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                           class="w-full" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Guest Information -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Guest Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="form-group">
                                    <label for="adults_count" class="block text-sm font-medium text-gray-700 mb-2">Adults <span class="text-red-500">*</span></label>
                                    <select id="adults_count" name="adults_count" class="w-full" required onchange="calculateTotal()">
                                        <option value="1">1 Adult</option>
                                        <option value="2">2 Adults</option>
                                        <option value="3">3 Adults</option>
                                        <option value="4">4 Adults</option>
                                        <option value="5">5 Adults</option>
                                        <option value="6">6 Adults</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="children_count" class="block text-sm font-medium text-gray-700 mb-2">Children</label>
                                    <select id="children_count" name="children_count" class="w-full" onchange="calculateTotal()">
                                        <option value="0">0 Children</option>
                                        <option value="1">1 Child</option>
                                        <option value="2">2 Children</option>
                                        <option value="3">3 Children</option>
                                        <option value="4">4 Children</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="extra_rooms" class="block text-sm font-medium text-gray-700 mb-2">Extra Rooms</label>
                                    <select id="extra_rooms" name="extra_rooms" class="w-full" onchange="calculateTotal()">
                                        <option value="0">0 Extra Rooms</option>
                                        <option value="1">1 Extra Room</option>
                                        <option value="2">2 Extra Rooms</option>
                                        <option value="3">3 Extra Rooms</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Special Requirements -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Special Requirements</h3>
                            <div class="form-group">
                                <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-2">Any special requests or requirements?</label>
                                <textarea id="special_requirements" name="special_requirements" rows="4" 
                                          class="w-full" placeholder="Dietary restrictions, accessibility needs, special occasions, etc."></textarea>
                            </div>
                        </div>
                        
                        <!-- Price Summary -->
                        <div class="price-summary p-6 mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Price Summary</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span>Adults (<span id="adults-display">1</span> × <?= formatPrice($package['base_price']) ?>)</span>
                                    <span id="adults-total"><?= formatPrice($package['base_price']) ?></span>
                                </div>
                                <div class="flex justify-between" id="children-row" style="display: none;">
                                    <span>Children (<span id="children-display">0</span> × <?= formatPrice($package['child_price']) ?>)</span>
                                    <span id="children-total"><?= formatPrice(0) ?></span>
                                </div>
                                <div class="flex justify-between" id="extra-rooms-row" style="display: none;">
                                    <span>Extra Rooms (<span id="extra-rooms-display">0</span> × <?= formatPrice($package['extra_room_price']) ?>)</span>
                                    <span id="extra-rooms-total"><?= formatPrice(0) ?></span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span>Total Amount</span>
                                        <span id="grand-total"><?= formatPrice($package['base_price']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-8">
                            <label class="flex items-start">
                                <input type="checkbox" id="agree_terms" class="mt-1 mr-3" required>
                                <span class="text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> 
                                    and <a href="#" class="text-blue-600 hover:underline">Cancellation Policy</a>. 
                                    I understand that this booking is subject to availability and confirmation.
                                </span>
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn-book">
                            <i class="fas fa-credit-card mr-2"></i>
                            Proceed to Confirmation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Pricing calculation
const packagePrices = {
    adult: <?= $package['base_price'] ?>,
    child: <?= $package['child_price'] ?>,
    extraRoom: <?= $package['extra_room_price'] ?>
};

function formatPrice(amount) {
    return '₹' + amount.toLocaleString('en-IN');
}

function calculateTotal() {
    const adults = parseInt(document.getElementById('adults_count').value) || 0;
    const children = parseInt(document.getElementById('children_count').value) || 0;
    const extraRooms = parseInt(document.getElementById('extra_rooms').value) || 0;
    
    // Calculate amounts
    const adultsAmount = adults * packagePrices.adult;
    const childrenAmount = children * packagePrices.child;
    const extraRoomsAmount = extraRooms * packagePrices.extraRoom;
    const total = adultsAmount + childrenAmount + extraRoomsAmount;
    
    // Update display
    document.getElementById('adults-display').textContent = adults;
    document.getElementById('adults-total').textContent = formatPrice(adultsAmount);
    
    // Show/hide children row
    const childrenRow = document.getElementById('children-row');
    if (children > 0) {
        childrenRow.style.display = 'flex';
        document.getElementById('children-display').textContent = children;
        document.getElementById('children-total').textContent = formatPrice(childrenAmount);
    } else {
        childrenRow.style.display = 'none';
    }
    
    // Show/hide extra rooms row
    const extraRoomsRow = document.getElementById('extra-rooms-row');
    if (extraRooms > 0) {
        extraRoomsRow.style.display = 'flex';
        document.getElementById('extra-rooms-display').textContent = extraRooms;
        document.getElementById('extra-rooms-total').textContent = formatPrice(extraRoomsAmount);
    } else {
        extraRoomsRow.style.display = 'none';
    }
    
    // Update grand total
    document.getElementById('grand-total').textContent = formatPrice(total);
}

// Form validation
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const adults = parseInt(document.getElementById('adults_count').value) || 0;
    const children = parseInt(document.getElementById('children_count').value) || 0;
    const totalGuests = adults + children;
    const maxGuests = <?= $package['max_guests'] ?>;
    
    if (totalGuests > maxGuests) {
        e.preventDefault();
        alert(`Total guests (${totalGuests}) exceed package capacity (${maxGuests}). Please adjust your selection.`);
        return false;
    }
    
    if (!document.getElementById('agree_terms').checked) {
        e.preventDefault();
        alert('Please agree to the terms and conditions to proceed.');
        return false;
    }
    
    if (!document.getElementById('travel_date').value) {
        e.preventDefault();
        alert('Please select a travel date.');
        return false;
    }
});

// Initialize calculation
calculateTotal();
</script> 