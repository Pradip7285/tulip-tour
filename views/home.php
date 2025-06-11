<!-- Hero Section -->
<section class="hero-gradient relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-purple-900/50 to-blue-900/50"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Hero Content -->
            <div class="text-white">
                <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                    Discover Your Next
                    <span class="text-yellow-300">Adventure</span>
                </h1>
                <p class="text-xl lg:text-2xl mb-8 text-gray-100">
                    Explore handpicked travel packages from trusted providers. 
                    From tropical getaways to mountain adventures, find your perfect escape.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?= app_url('/packages') ?>" 
                       class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-4 px-8 rounded-lg text-lg transition duration-200 transform hover:scale-105 text-center">
                        <i class="fas fa-search mr-2"></i>
                        Explore Packages
                    </a>
                    <a href="#featured" 
                       class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-gray-900 font-bold py-4 px-8 rounded-lg text-lg transition duration-200 text-center">
                        <i class="fas fa-play mr-2"></i>
                        Watch Video
                    </a>
                </div>
            </div>
            
            <!-- Hero Search Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Find Your Perfect Trip</h3>
                <form action="<?= app_url('/packages') ?>" method="GET" class="space-y-4">
                    <!-- Destination -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Where do you want to go?</label>
                        <div class="relative">
                            <input type="text" name="destination" placeholder="Enter destination..." 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Budget -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget</label>
                            <select name="budget" class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Any Budget</option>
                                <option value="0-500">₹0 - ₹500</option>
                                <option value="500-1000">₹500 - ₹1,000</option>
                                <option value="1000-2500">₹1,000 - ₹2,500</option>
                                <option value="2500+">₹2,500+</option>
                            </select>
                        </div>
                        
                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                            <select name="duration" class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Any Duration</option>
                                <option value="1-3">1-3 days</option>
                                <option value="4-7">4-7 days</option>
                                <option value="8-14">1-2 weeks</option>
                                <option value="15+">2+ weeks</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105">
                        <i class="fas fa-search mr-2"></i>
                        Search Adventures
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <i class="fas fa-chevron-down text-2xl"></i>
    </div>
</section>

<!-- Featured Packages Section -->
<section id="featured" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Adventures</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Handpicked travel experiences that offer the perfect blend of adventure, comfort, and value. 
                Start your journey with these popular destinations.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($featuredPackages)): ?>
                <?php foreach ($featuredPackages as $package): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                    <div class="relative">
                        <img src="<?= $package['featured_image'] ?: 'https://via.placeholder.com/400x250/667eea/ffffff?text=Travel+Package' ?>" 
                             alt="<?= htmlspecialchars($package['title']) ?>" 
                             class="w-full h-64 object-cover">
                        
                        <!-- Category Badge -->
                        <?php if ($package['category_name']): ?>
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <?= htmlspecialchars($package['category_name']) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Rating -->
                        <div class="absolute top-4 right-4">
                            <div class="bg-white rounded-lg px-2 py-1 shadow-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="font-bold text-sm"><?= number_format($package['rating'], 1) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Wishlist -->
                        <button class="absolute bottom-4 right-4 bg-white hover:bg-gray-100 text-gray-600 hover:text-red-500 p-2 rounded-full shadow-lg transition duration-200">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center text-gray-500 text-sm mb-2">
                            <i class="fas fa-map-marker-alt text-primary-500 mr-1"></i>
                            <span><?= htmlspecialchars($package['destination']) ?></span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar text-primary-500 mr-1"></i>
                            <span><?= $package['duration_days'] ?> days</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            <?= htmlspecialchars($package['title']) ?>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                            <?= htmlspecialchars(substr($package['short_description'], 0, 120)) ?>...
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold text-primary-600">
                                    <?= formatPrice($package['base_price']) ?>
                                </div>
                                <div class="text-sm text-gray-500">per person</div>
                            </div>
                            
                            <a href="<?= app_url('/package/' . $package['slug']) ?>" 
                               class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200 transform hover:scale-105">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-plane text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No packages available</h3>
                    <p class="text-gray-500">Check back soon for amazing travel deals!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?= app_url('/packages') ?>" 
               class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 transform hover:scale-105">
                <span>View All Packages</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose <?= AppConfig::get('app_name') ?>?</h2>
            <p class="text-xl text-gray-600">We're committed to making your travel dreams a reality</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Trusted Providers</h3>
                <p class="text-gray-600">All travel providers are verified and rated by real customers for your peace of mind.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-rupee-sign text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Best Prices</h3>
                <p class="text-gray-600">Compare prices from multiple providers and get the best deals on your favorite destinations.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h3>
                <p class="text-gray-600">Our dedicated support team is available around the clock to assist with your travel needs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Travelers Say</h2>
            <p class="text-xl text-gray-600">Real stories from real adventures</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                        <span class="font-bold text-primary-600">S</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Sarah Johnson</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"Amazing experience! The booking process was smooth and the trip exceeded all expectations. Highly recommended!"</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                        <span class="font-bold text-primary-600">M</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Mike Chen</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"Great value for money and excellent customer service. Will definitely book my next adventure through TripBazaar!"</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                        <span class="font-bold text-primary-600">E</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Emily Davis</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"The platform made it so easy to find and compare packages. Our family vacation was perfectly planned!"</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-600">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-white mb-4">Ready to Start Your Adventure?</h2>
        <p class="text-xl text-blue-100 mb-8">
            Join thousands of travelers who have discovered their perfect getaway through our platform.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= app_url('/packages') ?>" 
               class="bg-white hover:bg-gray-100 text-primary-600 font-bold py-4 px-8 rounded-lg transition duration-200 transform hover:scale-105">
                <i class="fas fa-search mr-2"></i>
                Browse Packages
            </a>
            <a href="#" 
               class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-4 px-8 rounded-lg transition duration-200 transform hover:scale-105">
                <i class="fas fa-user-plus mr-2"></i>
                Become a Provider
            </a>
        </div>
    </div>
</section> 