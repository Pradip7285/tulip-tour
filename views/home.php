<?php
$pageTitle = 'Discover Amazing Travel Experiences - TripBazaar';
$pageDescription = 'Find and book curated travel packages from trusted providers. Explore destinations, compare prices, and create unforgettable memories.';
?>

<!-- Custom Styles for Premium Landing Page -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap');
    
    .font-display { font-family: 'Playfair Display', serif; }
    .font-inter { font-family: 'Inter', sans-serif; }
    
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
    }
    
    .hero-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="0.5" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="0.3" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.4" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.2" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .card-hover {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .floating-animation {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .pulse-border {
        position: relative;
        overflow: hidden;
    }
    
    .pulse-border::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.8s;
    }
    
    .pulse-border:hover::before {
        left: 100%;
    }
    
    .scroll-indicator {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    
    .stats-counter {
        font-weight: 700;
        font-size: 2.5rem;
        color: #667eea;
    }
    
    .premium-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .premium-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
    
    .premium-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .premium-btn:hover::before {
        left: 100%;
    }
    
    .feature-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
        background-size: 200% 200%;
        animation: gradientShift 3s ease infinite;
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .testimonial-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(102, 126, 234, 0.1);
        transition: all 0.4s ease;
    }
    
    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        border-color: rgba(102, 126, 234, 0.3);
    }
    
    .destination-overlay {
        background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0) 100%);
    }
    
    .search-form {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .video-overlay {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }
</style>

<!-- Hero Section -->
<section class="hero-gradient relative min-h-screen flex items-center overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white opacity-5 rounded-full floating-animation"></div>
        <div class="absolute bottom-32 right-16 w-96 h-96 bg-white opacity-3 rounded-full floating-animation" style="animation-delay: -2s;"></div>
        <div class="absolute top-1/2 left-1/2 w-48 h-48 bg-white opacity-4 rounded-full floating-animation" style="animation-delay: -4s;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Hero Content -->
            <div class="text-white z-10">
                <div class="mb-6">
                    <span class="inline-block px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-medium backdrop-blur-sm border border-white border-opacity-30">
                        🌟 Trusted by 50,000+ travelers
                    </span>
                </div>
                
                <h1 class="font-display text-5xl lg:text-7xl font-bold mb-6 leading-tight">
                    Discover Your Next
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">
                        Adventure
                    </span>
                </h1>
                
                <p class="font-inter text-xl lg:text-2xl mb-8 text-gray-100 leading-relaxed max-w-2xl">
                    Explore handpicked travel packages from trusted providers. From tropical getaways to mountain adventures, find your perfect escape with premium experiences.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <button class="premium-btn text-white font-bold py-4 px-8 rounded-xl text-lg transition duration-300 transform hover:scale-105 pulse-border">
                        <i class="fas fa-search mr-2"></i>
                        Explore Packages
                    </button>
                    
                    <button onclick="openVideoModal()" class="glass-effect text-white hover:bg-white hover:bg-opacity-20 font-bold py-4 px-8 rounded-xl text-lg transition duration-300 border border-white border-opacity-30">
                        <i class="fas fa-play mr-2"></i>
                        Watch Our Story
                    </button>
                </div>
                
                <!-- Trust Indicators -->
                <div class="flex items-center space-x-8 text-sm text-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-300 mr-1"></i>
                        <span class="font-semibold">4.9/5</span>
                        <span class="ml-1">Rating</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-green-300 mr-1"></i>
                        <span class="font-semibold">100%</span>
                        <span class="ml-1">Secure</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-headset text-blue-300 mr-1"></i>
                        <span class="font-semibold">24/7</span>
                        <span class="ml-1">Support</span>
                    </div>
                </div>
            </div>
            
            <!-- Premium Search Form -->
            <div class="search-form rounded-3xl shadow-2xl p-8 lg:p-10">
                <div class="text-center mb-8">
                    <h3 class="font-display text-3xl font-bold text-gray-900 mb-2">Find Your Perfect Trip</h3>
                    <p class="text-gray-600">Discover curated experiences tailored just for you</p>
                </div>
                
                <form action="<?= app_url('/packages') ?>" method="GET" class="space-y-6">
                    <!-- Destination Search -->
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Where would you like to go?</label>
                        <div class="relative">
                            <input type="text" name="destination" 
                                   placeholder="Search destinations..." 
                                   class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 text-lg">
                            <i class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-purple-500 text-lg"></i>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Budget -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Budget Range</label>
                            <select name="budget" class="w-full py-4 px-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg appearance-none bg-white">
                                <option value="">Any Budget</option>
                                <option value="0-10000">₹0 - ₹10,000</option>
                                <option value="10000-25000">₹10,000 - ₹25,000</option>
                                <option value="25000-50000">₹25,000 - ₹50,000</option>
                                <option value="50000+">₹50,000+</option>
                            </select>
                        </div>
                        
                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Trip Duration</label>
                            <select name="duration" class="w-full py-4 px-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg appearance-none bg-white">
                                <option value="">Any Duration</option>
                                <option value="1-3">1-3 days</option>
                                <option value="4-7">4-7 days</option>
                                <option value="8-14">1-2 weeks</option>
                                <option value="15+">2+ weeks</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Advanced Options Toggle -->
                    <div class="border-t pt-4">
                        <button type="button" onclick="toggleAdvancedOptions()" class="text-purple-600 text-sm font-medium hover:text-purple-700 transition duration-200">
                            <span id="advancedToggleText">+ Show Advanced Options</span>
                        </button>
                        
                        <div id="advancedOptions" class="hidden mt-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Travelers</label>
                                    <select class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500">
                                        <option>1 Person</option>
                                        <option>2 People</option>
                                        <option>3-4 People</option>
                                        <option>5+ People</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Travel Style</label>
                                    <select class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500">
                                        <option>All Styles</option>
                                        <option>Adventure</option>
                                        <option>Luxury</option>
                                        <option>Cultural</option>
                                        <option>Relaxation</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full premium-btn text-white font-bold py-5 px-6 rounded-xl text-lg transition duration-300 transform hover:scale-105 pulse-border">
                        <i class="fas fa-search mr-2"></i>
                        Search Premium Adventures
                    </button>
                    
                    <p class="text-center text-xs text-gray-500 mt-4">
                        Free cancellation • Instant booking • Best price guarantee
                    </p>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white scroll-indicator">
        <div class="text-center">
            <div class="text-sm font-medium mb-2">Scroll to explore</div>
            <i class="fas fa-chevron-down text-2xl"></i>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="p-6">
                <div class="stats-counter font-display mb-2" data-target="50000">0</div>
                <div class="text-gray-600 font-medium">Happy Travelers</div>
            </div>
            <div class="p-6">
                <div class="stats-counter font-display mb-2" data-target="500">0</div>
                <div class="text-gray-600 font-medium">Destinations</div>
            </div>
            <div class="p-6">
                <div class="stats-counter font-display mb-2" data-target="1000">0</div>
                <div class="text-gray-600 font-medium">Travel Packages</div>
            </div>
            <div class="p-6">
                <div class="stats-counter font-display mb-2" data-target="99">0</div>
                <div class="text-gray-600 font-medium">Satisfaction Rate</div>
                <div class="text-xs text-gray-500 mt-1">%</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Packages Section -->
<section id="featured" class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-purple-100 text-purple-600 rounded-full text-sm font-semibold mb-4">
                HANDPICKED FOR YOU
            </span>
            <h2 class="font-display text-5xl font-bold text-gray-900 mb-6">Featured Adventures</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Curated travel experiences that offer the perfect blend of adventure, comfort, and value. 
                Start your journey with these premium destinations.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($featuredPackages)): ?>
                <?php foreach ($featuredPackages as $index => $package): ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover group" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <div class="relative overflow-hidden">
                        <img src="<?= $package['featured_image'] ?: 'https://via.placeholder.com/400x300/667eea/ffffff?text=Premium+Travel' ?>" 
                             alt="<?= htmlspecialchars($package['title']) ?>" 
                             class="w-full h-72 object-cover group-hover:scale-110 transition duration-500">
                        
                        <div class="destination-overlay absolute inset-0"></div>
                        
                        <!-- Premium Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                ⭐ PREMIUM
                            </span>
                        </div>
                        
                        <!-- Category Badge -->
                        <?php if ($package['category_name']): ?>
                        <div class="absolute top-4 right-4">
                            <span class="glass-effect text-white px-3 py-1 rounded-full text-sm font-medium">
                                <?= htmlspecialchars($package['category_name']) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Rating -->
                        <div class="absolute bottom-4 left-4">
                            <div class="glass-effect rounded-lg px-3 py-2">
                                <div class="flex items-center text-white">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="font-bold text-sm"><?= number_format($package['rating'], 1) ?></span>
                                    <span class="text-xs ml-1 opacity-75">(<?= rand(50, 300) ?>)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Wishlist -->
                        <button class="absolute bottom-4 right-4 bg-white hover:bg-red-50 text-gray-600 hover:text-red-500 p-3 rounded-full shadow-lg transition duration-300 transform hover:scale-110">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    
                    <div class="p-8">
                        <div class="flex items-center text-gray-500 text-sm mb-3 font-medium">
                            <i class="fas fa-map-marker-alt text-purple-500 mr-2"></i>
                            <span><?= htmlspecialchars($package['destination']) ?></span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar text-purple-500 mr-2"></i>
                            <span><?= $package['duration_days'] ?> days</span>
                        </div>
                        
                        <h3 class="font-display text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition duration-300">
                            <?= htmlspecialchars($package['title']) ?>
                        </h3>
                        
                        <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                            <?= htmlspecialchars(substr($package['short_description'] ?: $package['description'], 0, 120)) ?>...
                        </p>
                        
                        <!-- Features -->
                        <div class="flex items-center gap-4 mb-6 text-xs text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-wifi text-green-500 mr-1"></i>
                                <span>Free WiFi</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-utensils text-orange-500 mr-1"></i>
                                <span>Meals</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-car text-blue-500 mr-1"></i>
                                <span>Transport</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold gradient-text">
                                    <?= formatPrice($package['base_price']) ?>
                                </div>
                                <div class="text-sm text-gray-500">per person</div>
                            </div>
                            
                            <a href="<?= app_url('/package/' . $package['slug']) ?>" 
                               class="premium-btn text-white px-6 py-3 rounded-xl font-semibold transition duration-300 transform hover:scale-105 pulse-border">
                                View Details
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-plane text-purple-500 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-600 mb-3">Amazing packages coming soon!</h3>
                    <p class="text-gray-500">Our team is curating the best travel experiences for you.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-16">
            <a href="<?= app_url('/packages') ?>" 
               class="premium-btn text-white font-bold py-4 px-10 rounded-xl text-lg transition duration-300 transform hover:scale-105 pulse-border">
                <span>Explore All Packages</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-20 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">
                WHY CHOOSE US
            </span>
            <h2 class="font-display text-5xl font-bold text-gray-900 mb-6">Crafted for Excellence</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">We're committed to making your travel dreams a reality with unmatched service and attention to detail</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center group">
                <div class="w-20 h-20 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6 text-white text-2xl">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="font-display text-2xl font-bold text-gray-900 mb-4 group-hover:text-purple-600 transition duration-300">Trusted Excellence</h3>
                <p class="text-gray-600 leading-relaxed">All travel providers are meticulously verified and rated by real customers. Your peace of mind is our priority.</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6 text-white text-2xl">
                    <i class="fas fa-gem"></i>
                </div>
                <h3 class="font-display text-2xl font-bold text-gray-900 mb-4 group-hover:text-purple-600 transition duration-300">Premium Value</h3>
                <p class="text-gray-600 leading-relaxed">Compare prices from multiple providers and access exclusive deals on luxury destinations worldwide.</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6 text-white text-2xl">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <h3 class="font-display text-2xl font-bold text-gray-900 mb-4 group-hover:text-purple-600 transition duration-300">Concierge Support</h3>
                <p class="text-gray-600 leading-relaxed">Our dedicated travel concierge team is available 24/7 to assist with every detail of your journey.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gradient-to-br from-purple-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-green-100 text-green-600 rounded-full text-sm font-semibold mb-4">
                TESTIMONIALS
            </span>
            <h2 class="font-display text-5xl font-bold text-gray-900 mb-6">Stories from Adventurers</h2>
            <p class="text-xl text-gray-600">Real experiences from travelers who discovered their perfect escape</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="testimonial-card rounded-2xl p-8 relative">
                <div class="flex items-center mb-6">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=50&h=50&q=80" 
                         alt="Sarah Johnson" class="w-14 h-14 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Sarah Johnson</h4>
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 leading-relaxed mb-4">"The booking process was seamless and the trip exceeded all expectations. The attention to detail and personalized service made it truly unforgettable. Highly recommended!"</p>
                <div class="text-sm text-purple-600 font-medium">Bali Adventure Package</div>
            </div>
            
            <div class="testimonial-card rounded-2xl p-8 relative">
                <div class="flex items-center mb-6">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=50&h=50&q=80" 
                         alt="Mike Chen" class="w-14 h-14 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Mike Chen</h4>
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 leading-relaxed mb-4">"Outstanding value for money and exceptional customer service. The platform made it easy to find exactly what we were looking for. Will definitely book again!"</p>
                <div class="text-sm text-purple-600 font-medium">Swiss Alps Expedition</div>
            </div>
            
            <div class="testimonial-card rounded-2xl p-8 relative">
                <div class="flex items-center mb-6">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=50&h=50&q=80" 
                         alt="Emily Davis" class="w-14 h-14 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Emily Davis</h4>
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 leading-relaxed mb-4">"The platform made comparing packages effortless. Our family vacation was perfectly planned down to every detail. Absolutely wonderful experience!"</p>
                <div class="text-sm text-purple-600 font-medium">Thailand Family Adventure</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 relative overflow-hidden">
    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-5xl font-bold text-white mb-6">Ready to Create Memories?</h2>
        <p class="text-xl text-blue-100 mb-10 leading-relaxed">
            Join thousands of travelers who have discovered their perfect getaway through our premium platform. Your adventure awaits.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="<?= app_url('/packages') ?>" 
               class="bg-white hover:bg-gray-100 text-purple-600 font-bold py-5 px-10 rounded-xl text-lg transition duration-300 transform hover:scale-105 pulse-border">
                <i class="fas fa-search mr-2"></i>
                Start Exploring
            </a>
            <a href="<?= app_url('/auth/register?type=provider') ?>" 
               class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-5 px-10 rounded-xl text-lg transition duration-300 transform hover:scale-105">
                <i class="fas fa-handshake mr-2"></i>
                Become a Partner
            </a>
        </div>
        
        <div class="mt-12 flex justify-center items-center space-x-8 text-blue-100 text-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-300 mr-2"></i>
                <span>Free Cancellation</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-lock text-blue-300 mr-2"></i>
                <span>Secure Booking</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-medal text-yellow-300 mr-2"></i>
                <span>Best Price Guarantee</span>
            </div>
        </div>
    </div>
</section>

<!-- Video Modal -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center hidden">
    <div class="relative max-w-4xl w-full mx-4">
        <button onclick="closeVideoModal()" class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
        <div class="bg-white rounded-2xl overflow-hidden">
            <div class="aspect-video bg-gray-900 flex items-center justify-center">
                <div class="text-white text-center">
                    <i class="fas fa-play-circle text-6xl mb-4 opacity-50"></i>
                    <p class="text-xl">Video coming soon!</p>
                    <p class="text-sm opacity-75 mt-2">Experience the magic of travel with TripBazaar</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Premium Landing Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats counters
    animateCounters();
    
    // Add scroll animations
    addScrollAnimations();
    
    // Initialize form interactions
    initializeFormInteractions();
});

function animateCounters() {
    const counters = document.querySelectorAll('.stats-counter');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current).toLocaleString();
                }, 20);
                
                observer.unobserve(counter);
            }
        });
    });
    
    counters.forEach(counter => observer.observe(counter));
}

function addScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.card-hover, .testimonial-card').forEach(el => {
        observer.observe(el);
    });
}

function initializeFormInteractions() {
    // Auto-complete destination search
    const destinationInput = document.querySelector('input[name="destination"]');
    if (destinationInput) {
        const popularDestinations = ['Goa', 'Kerala', 'Rajasthan', 'Himachal Pradesh', 'Kashmir', 'Bali', 'Thailand', 'Dubai'];
        
        destinationInput.addEventListener('input', function() {
            // Add autocomplete functionality here
        });
    }
}

function toggleAdvancedOptions() {
    const options = document.getElementById('advancedOptions');
    const toggleText = document.getElementById('advancedToggleText');
    
    if (options.classList.contains('hidden')) {
        options.classList.remove('hidden');
        options.classList.add('animate-fade-in');
        toggleText.textContent = '- Hide Advanced Options';
    } else {
        options.classList.add('hidden');
        toggleText.textContent = '+ Show Advanced Options';
    }
}

function openVideoModal() {
    document.getElementById('videoModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    document.getElementById('videoModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Add smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<!-- Additional CSS for animations -->
<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style> 