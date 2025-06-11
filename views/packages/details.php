<!-- Package Details Page -->
<?php
// Debug tiered pricing (remove in production)
if (AppConfig::isDebug()) {
    $tiers = getPricingTiers($package);
    echo "<!-- DEBUG: Tiers count: " . count($tiers) . " -->\n";
    echo "<!-- DEBUG: JSON: " . htmlspecialchars(json_encode($tiers)) . " -->\n";
}
?>
<div class="bg-gray-50 min-h-screen">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="<?= app_url('/') ?>" class="hover:text-primary-600">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?= app_url('/packages') ?>" class="hover:text-primary-600">Packages</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900"><?= htmlspecialchars($package['title']) ?></span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Package Info -->
            <div class="lg:col-span-2">
                <!-- Featured Image -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="relative h-96 overflow-hidden">
                        <img src="<?= $package['featured_image'] ?: 'https://via.placeholder.com/800x400/667eea/ffffff?text=Travel+Package' ?>" 
                             alt="<?= htmlspecialchars($package['title']) ?>" 
                             class="w-full h-full object-cover">
                        
                        <!-- Category Badge -->
                        <?php if ($package['category_name']): ?>
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary-600 text-white px-4 py-2 rounded-full text-sm font-medium">
                                <?= htmlspecialchars($package['category_name']) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Rating Badge -->
                        <div class="absolute top-4 right-4">
                            <div class="bg-white rounded-lg px-3 py-2 shadow-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="font-bold"><?= number_format($package['rating'], 1) ?></span>
                                    <span class="text-gray-500 text-sm ml-1">(<?= $package['total_reviews'] ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Information -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($package['title']) ?></h1>
                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i>
                                <span class="text-lg"><?= htmlspecialchars($package['destination']) ?></span>
                            </div>
                        </div>
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-red-500 p-3 rounded-full transition duration-200">
                            <i class="far fa-heart text-xl"></i>
                        </button>
                    </div>

                    <!-- Package Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-calendar-alt text-primary-500 text-xl mb-2"></i>
                            <div class="font-semibold text-gray-900"><?= $package['duration_days'] ?> Days</div>
                            <div class="text-sm text-gray-500">Duration</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-users text-primary-500 text-xl mb-2"></i>
                            <div class="font-semibold text-gray-900">Max <?= $package['max_guests'] ?></div>
                            <div class="text-sm text-gray-500">Total Guests</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-star text-primary-500 text-xl mb-2"></i>
                            <div class="font-semibold text-gray-900"><?= number_format($package['rating'], 1) ?>/5</div>
                            <div class="text-sm text-gray-500"><?= $package['total_reviews'] ?> Reviews</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-check-circle text-primary-500 text-xl mb-2"></i>
                            <div class="font-semibold text-gray-900"><?= $package['total_bookings'] ?></div>
                            <div class="text-sm text-gray-500">Bookings</div>
                        </div>
                    </div>

                    <!-- Accommodation & Package Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-bed text-blue-500 text-lg mr-3"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Room Capacity</div>
                                    <div class="text-sm text-gray-600">Up to <?= $package['max_guests_per_room'] ?: 2 ?> guests per room</div>
                                </div>
                            </div>
                        </div>
                        <?php if ($package['extra_room_price'] > 0): ?>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-home text-yellow-500 text-lg mr-3"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Extra Rooms</div>
                                    <div class="text-sm text-gray-600"><?= formatPrice($package['extra_room_price']) ?> per additional room</div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-500 text-lg mr-3"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Trusted Provider</div>
                                    <div class="text-sm text-gray-600"><?= $package['total_bookings'] ?> successful bookings</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">About This Package</h3>
                        <p class="text-gray-700 leading-relaxed">
                            <?= $package['description'] ?: $package['short_description'] ?>
                        </p>
                    </div>

                    <!-- Inclusions & Exclusions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <?php if ($package['inclusions']): ?>
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                What's Included
                            </h4>
                            <div class="space-y-2">
                                <?php 
                                $inclusions = explode("\n", $package['inclusions']);
                                foreach ($inclusions as $inclusion): 
                                    $inclusion = trim($inclusion);
                                    if (!empty($inclusion)):
                                ?>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-check text-green-500 mt-1 text-sm"></i>
                                    <span class="text-gray-700 text-sm"><?= htmlspecialchars(ltrim($inclusion, '•')) ?></span>
                                </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($package['exclusions']): ?>
                        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                What's Not Included
                            </h4>
                            <div class="space-y-2">
                                <?php 
                                $exclusions = explode("\n", $package['exclusions']);
                                foreach ($exclusions as $exclusion): 
                                    $exclusion = trim($exclusion);
                                    if (!empty($exclusion)):
                                ?>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-times text-red-500 mt-1 text-sm"></i>
                                    <span class="text-gray-700 text-sm"><?= htmlspecialchars(ltrim($exclusion, '•')) ?></span>
                                </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Terms & Conditions -->
                    <?php if ($package['terms_conditions']): ?>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-file-contract text-gray-500 mr-2"></i>
                            Terms & Conditions
                        </h4>
                        <div class="text-gray-700 text-sm leading-relaxed">
                            <?= nl2br(htmlspecialchars($package['terms_conditions'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Provider Information -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Travel Provider</h3>
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-primary-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">
                                    <?= htmlspecialchars($package['company_name'] ?: $package['provider_first_name'] . ' ' . $package['provider_last_name']) ?>
                                </h4>
                                <?php if ($package['provider_verified']): ?>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Verified
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php if ($package['provider_description']): ?>
                            <p class="text-gray-600 text-sm"><?= htmlspecialchars($package['provider_description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <?php if (!empty($reviews)): ?>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Customer Reviews</h3>
                    <div class="space-y-6">
                        <?php foreach ($reviews as $review): ?>
                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="font-bold text-primary-600">
                                        <?= strtoupper(substr($review['first_name'], 0, 1)) ?>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h5 class="font-semibold text-gray-900">
                                                <?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?>
                                            </h5>
                                            <div class="flex items-center space-x-1">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' ?> text-sm"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500"><?= formatDate($review['created_at']) ?></span>
                                    </div>
                                    <?php if ($review['title']): ?>
                                    <h6 class="font-medium text-gray-900 mb-1"><?= htmlspecialchars($review['title']) ?></h6>
                                    <?php endif; ?>
                                    <p class="text-gray-700"><?= htmlspecialchars($review['review_text']) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column - Price Calculator -->
            <div class="lg:col-span-1">
                <?php 
                // Prepare the pricing tiers data for JavaScript
                $tiersData = getPricingTiers($package) ?: [];
                $jsData = [
                    'adults' => 2,
                    'children' => 0, 
                    'extraRooms' => 0,
                    'extraRoomPrice' => (float)($package['extra_room_price'] ?: 0),
                    'pricingTiers' => $tiersData,
                    'basePrice' => (float)($package['base_price'] ?: 0)
                ];
                ?>
                <script>
                    window.packageData = <?= json_encode($jsData) ?>;
                </script>
                
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24" x-data="{
                    adults: 2,
                    children: 0,
                    extraRooms: 0,
                    extraRoomPrice: 0,
                    pricingTiers: [],
                    basePrice: 0,
                    
                    init() {
                        // Load data from window object to avoid HTML encoding issues
                        const data = window.packageData;
                        this.extraRoomPrice = data.extraRoomPrice;
                        this.basePrice = data.basePrice;
                        
                        // Convert string numbers to actual numbers for calculations
                        this.pricingTiers = data.pricingTiers.map(tier => ({
                            ...tier,
                            rate_per_adult: parseFloat(tier.rate_per_adult),
                            tier: parseInt(tier.tier),
                            min_guests: parseInt(tier.min_guests),
                            max_guests: parseInt(tier.max_guests)
                        }));
                    },
                    
                    get currentTier() {
                        if (!this.pricingTiers || this.pricingTiers.length === 0) {
                            return null;
                        }
                        
                        let selectedTier = null;
                        for (let tier of this.pricingTiers) {
                            if (this.adults >= tier.min_guests && this.adults <= tier.max_guests) {
                                selectedTier = tier;
                                break;
                            }
                        }
                        // If no exact match, use the highest tier available
                        if (!selectedTier && this.pricingTiers.length > 0) {
                            selectedTier = this.pricingTiers[this.pricingTiers.length - 1];
                        }
                        return selectedTier;
                    },
                    
                    get currentRate() {
                        const tier = this.currentTier;
                        return tier ? tier.rate_per_adult : this.basePrice;
                    },
                    
                    get totalGuests() {
                        return this.adults + this.children;
                    },
                    
                    get baseAmount() {
                        // Adults pay tiered rate, children under specified age are free
                        return this.adults * this.currentRate;
                    },
                    
                    get extraRoomAmount() {
                        return this.extraRooms * this.extraRoomPrice;
                    },
                    
                    get totalAmount() {
                        return this.baseAmount + this.extraRoomAmount;
                    }
                }">
                    <div class="text-center mb-6">
                        <div class="text-3xl font-bold text-primary-600" x-text="'₹' + (totalAmount || 0).toLocaleString()"></div>
                        <div class="text-sm text-gray-500">Total Price</div>
                        <div class="text-xs text-blue-600 mt-1" x-show="currentTier">
                            <span x-text="'Tier ' + (currentTier ? currentTier.tier : '') + ': ₹' + (currentRate ? currentRate.toLocaleString() : '0') + ' per adult'"></span>
                        </div>
                    </div>

                    <!-- Pricing Tiers Display -->
                    <?php $tiers = getPricingTiers($package); ?>
                    <?php if (!empty($tiers)): ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-layer-group text-blue-500 mr-2"></i>
                            Group Pricing Tiers
                        </h4>
                        <div class="space-y-2">
                            <?php foreach ($tiers as $tier): ?>
                            <div class="flex items-center justify-between text-xs" 
                                 :class="currentTier && currentTier.tier === <?= $tier['tier'] ?> ? 'bg-blue-200 rounded px-2 py-1 font-semibold' : ''">
                                <span><?= $tier['description'] ?></span>
                                <span class="font-medium"><?= formatPrice($tier['rate_per_adult']) ?>/adult</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-xs text-gray-600 mt-2">
                            <i class="fas fa-gift text-green-500 mr-1"></i>
                            Children under <?= $package['child_free_age'] ?: 7 ?> are FREE!
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Price Calculator -->
                    <div class="space-y-6">
                        <!-- Adults -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Adults (<?= $package['child_free_age'] ?: 7 ?>+ years) - <span x-text="'₹' + (currentRate ? currentRate.toLocaleString() : '0') + ' each'"></span>
                            </label>
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <button @click="adults = Math.max(1, adults - 1)" 
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition duration-200">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <span class="font-semibold text-lg" x-text="adults"></span>
                                <button @click="adults = Math.min(<?= $package['max_guests'] ?: 50 ?>, adults + 1)" 
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition duration-200">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Children -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Children (under <?= $package['child_free_age'] ?: 7 ?>) - <span class="text-green-600 font-semibold">FREE</span>
                            </label>
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <button @click="children = Math.max(0, children - 1)" 
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition duration-200">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <span class="font-semibold text-lg" x-text="children"></span>
                                <button @click="children = Math.min(<?= $package['max_guests'] ?: 50 ?> - adults, children + 1)" 
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition duration-200">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Extra Rooms -->
                        <?php if ($package['extra_room_price'] > 0): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Extra Rooms - <?= formatPrice($package['extra_room_price']) ?> each
                            </label>
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <button @click="extraRooms = Math.max(0, extraRooms - 1)" 
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition duration-200">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <span class="font-semibold text-lg" x-text="extraRooms"></span>
                                <button @click="extraRooms = extraRooms + 1" 
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition duration-200">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Price Breakdown -->
                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span x-text="adults + ' Adults × ₹' + (currentRate ? currentRate.toLocaleString() : '0')"></span>
                                <span x-text="'₹' + baseAmount.toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between text-sm text-green-600">
                                <span x-text="children + ' Children × FREE'"></span>
                                <span>₹0</span>
                            </div>
                            <div x-show="extraRooms > 0" class="flex justify-between text-sm">
                                <span x-text="extraRooms + ' Extra Rooms'"></span>
                                <span x-text="'₹' + extraRoomAmount.toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between font-semibold text-lg border-t pt-2">
                                <span>Total</span>
                                <span x-text="'₹' + totalAmount.toLocaleString()"></span>
                            </div>
                        </div>

                        <!-- Guest Summary -->
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="text-sm text-blue-800 space-y-1">
                                <div class="flex justify-between">
                                    <span>Total Guests:</span>
                                    <span x-text="totalGuests"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Available Spots:</span>
                                    <span x-text="<?= $package['max_guests'] ?: 50 ?> - totalGuests"></span>
                                </div>
                                <div class="flex justify-between border-t border-blue-200 pt-1">
                                    <span>Max per Room:</span>
                                    <span><?= $package['max_guests_per_room'] ?: 2 ?> guests</span>
                                </div>
                                <div class="flex justify-between text-xs text-blue-600">
                                    <span>Rooms needed:</span>
                                    <span x-text="Math.ceil(totalGuests / <?= $package['max_guests_per_room'] ?: 2 ?>) + extraRooms"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Book Now Button -->
                        <form action="<?= app_url('/booking') ?>" method="POST" class="space-y-3">
                            <input type="hidden" name="package_id" value="<?= $package['id'] ?>">
                            <input type="hidden" name="adults" x-model="adults">
                            <input type="hidden" name="children" x-model="children">
                            <input type="hidden" name="extra_rooms" x-model="extraRooms">
                            <input type="hidden" name="total_amount" x-model="totalAmount">
                            <input type="hidden" name="applied_tier" x-model="currentTier ? currentTier.tier : ''">
                            
                            <button type="submit" 
                                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105">
                                <i class="fas fa-credit-card mr-2"></i>
                                Book Now
                            </button>
                        </form>

                        <!-- Contact Provider -->
                        <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
                            <i class="fas fa-phone mr-2"></i>
                            Contact Provider
                        </button>

                        <!-- Security Badge -->
                        <div class="text-center text-xs text-gray-500 mt-4">
                            <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                            Secure Payment · Money Back Guarantee
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Packages -->
        <?php if (!empty($similarPackages)): ?>
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Similar Packages</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($similarPackages as $similar): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                    <div class="relative">
                        <img src="<?= $similar['featured_image'] ?: 'https://via.placeholder.com/300x200/667eea/ffffff?text=Travel+Package' ?>" 
                             alt="<?= htmlspecialchars($similar['title']) ?>" 
                             class="w-full h-48 object-cover">
                        <div class="absolute top-3 right-3">
                            <div class="bg-white rounded-lg px-2 py-1 shadow-lg">
                                <div class="flex items-center text-xs">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="font-bold"><?= number_format($similar['rating'], 1) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2"><?= htmlspecialchars($similar['title']) ?></h3>
                        <div class="flex items-center text-gray-600 text-sm mb-3">
                            <i class="fas fa-map-marker-alt text-primary-500 mr-1"></i>
                            <span><?= htmlspecialchars($similar['destination']) ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xl font-bold text-primary-600"><?= formatPrice($similar['base_price']) ?></div>
                                <div class="text-xs text-gray-500"><?= $similar['duration_days'] ?> days</div>
                            </div>
                            <a href="<?= app_url('/package/' . $similar['slug']) ?>" 
                               class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition duration-200">
                                View
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div> 