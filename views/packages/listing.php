<?php
// This view is included by the controller which handles the layout
// Remove the output buffering and layout inclusion to avoid double headers/footers
?>

<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section with Search -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="text-center text-white mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Discover Your Next Adventure</h1>
                <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto px-4">Explore curated travel packages designed to create unforgettable memories around the world</p>
            </div>
            
            <!-- Quick Search Bar -->
            <div class="max-w-4xl mx-auto px-4">
                <form method="GET" action="<?= app_url('/packages') ?>" class="bg-white rounded-2xl shadow-2xl p-4 sm:p-6 md:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Where do you want to go?</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-3 sm:top-4 text-gray-400"></i>
                                <input type="text" 
                                       name="destination" 
                                       value="<?= htmlspecialchars($_GET['destination'] ?? '') ?>"
                                       placeholder="Search destinations..." 
                                       class="pl-12 w-full p-3 sm:p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base sm:text-lg">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>
                            <select name="budget" class="w-full p-3 sm:p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base sm:text-lg">
                                <option value="">Any Budget</option>
                                <option value="0-500" <?= ($_GET['budget'] ?? '') === '0-500' ? 'selected' : '' ?>>Under ₹500</option>
                                <option value="500-1000" <?= ($_GET['budget'] ?? '') === '500-1000' ? 'selected' : '' ?>>₹500 - ₹1,000</option>
                                <option value="1000-2500" <?= ($_GET['budget'] ?? '') === '1000-2500' ? 'selected' : '' ?>>₹1,000 - ₹2,500</option>
                                <option value="2500+" <?= ($_GET['budget'] ?? '') === '2500+' ? 'selected' : '' ?>>₹2,500+</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 sm:py-4 px-4 sm:px-6 rounded-xl transition duration-200 text-base sm:text-lg">
                                <i class="fas fa-search mr-2"></i>
                                Explore
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8" x-data="{ 
        showAdvancedFilters: false,
        viewMode: localStorage.getItem('packageViewMode') || 'grid',
        toggleView(mode) {
            this.viewMode = mode;
            localStorage.setItem('packageViewMode', mode);
        }
    }">
        <!-- Breadcrumb & Controls -->
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between mb-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="<?= app_url('/') ?>" class="hover:text-blue-600 transition duration-200">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Travel Packages</span>
                <?php if (!empty($_GET['destination'])): ?>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-blue-600 truncate max-w-32 sm:max-w-none"><?= htmlspecialchars($_GET['destination']) ?></span>
                <?php endif; ?>
            </nav>

            <!-- View Controls -->
            <div class="flex flex-col xs:flex-row items-stretch xs:items-center space-y-2 xs:space-y-0 xs:space-x-3">
                <!-- View Toggle -->
                <div class="flex items-center bg-gray-100 rounded-lg p-1 w-full xs:w-auto">
                    <button @click="toggleView('grid')" 
                            :class="viewMode === 'grid' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500'"
                            class="flex-1 xs:flex-none px-3 py-1.5 rounded-md transition duration-200 font-medium text-sm text-center">
                        <i class="fas fa-th mr-1"></i>Grid
                    </button>
                    <button @click="toggleView('list')" 
                            :class="viewMode === 'list' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500'"
                            class="flex-1 xs:flex-none px-3 py-1.5 rounded-md transition duration-200 font-medium text-sm text-center">
                        <i class="fas fa-list mr-1"></i>List
                    </button>
                </div>
                
                <div class="flex space-x-3">
                    <!-- Quick Sort -->
                    <select onchange="location.href='?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(location.search)), sort: this.value}).toString()" 
                            class="flex-1 xs:flex-none border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                        <option value="">Latest</option>
                        <option value="featured" <?= ($_GET['sort'] ?? '') === 'featured' ? 'selected' : '' ?>>Featured</option>
                        <option value="rating" <?= ($_GET['sort'] ?? '') === 'rating' ? 'selected' : '' ?>>Highest Rated</option>
                        <option value="price_low" <?= ($_GET['sort'] ?? '') === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_high" <?= ($_GET['sort'] ?? '') === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="duration" <?= ($_GET['sort'] ?? '') === 'duration' ? 'selected' : '' ?>>Duration</option>
                    </select>
                    
                    <!-- Advanced Filters Toggle -->
                    <button @click="showAdvancedFilters = !showAdvancedFilters" 
                            class="flex-1 xs:flex-none bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg font-medium transition duration-200 text-sm whitespace-nowrap">
                        <i class="fas fa-sliders-h mr-1"></i>
                        <span x-text="showAdvancedFilters ? 'Hide' : 'Filters'" class="hidden xs:inline"></span>
                        <span x-text="showAdvancedFilters ? 'Hide Filters' : 'More Filters'" class="xs:hidden"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Results Header -->
        <div class="bg-white rounded-xl shadow-lg mb-6 sm:mb-8">
            <div class="p-4 sm:p-6">
                <div class="mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">
                        <?php if (!empty($_GET['destination'])): ?>
                            Packages to <?= htmlspecialchars($_GET['destination']) ?>
                        <?php else: ?>
                            All Travel Packages
                        <?php endif; ?>
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600">
                        Showing <?= $pagination['offset'] + 1 ?>-<?= min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items']) ?> 
                        of <?= $pagination['total_items'] ?> packages
                    </p>
                </div>

                <!-- Advanced Filters -->
                <div x-show="showAdvancedFilters" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="border-t pt-4 sm:pt-6">
                    
                    <form method="GET" action="<?= app_url('/packages') ?>">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                            <!-- Destination -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                                <div class="relative">
                                    <i class="fas fa-map-marker-alt absolute left-3 top-3 text-gray-400"></i>
                                    <input type="text" 
                                           name="destination" 
                                           value="<?= htmlspecialchars($_GET['destination'] ?? '') ?>"
                                           placeholder="Search destinations..." 
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            
                            <!-- Duration -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                                <div class="relative">
                                    <i class="fas fa-calendar-alt absolute left-3 top-3 text-gray-400"></i>
                                    <select name="duration" class="pl-10 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none">
                                        <option value="">Any Duration</option>
                                        <option value="1-3" <?= ($_GET['duration'] ?? '') === '1-3' ? 'selected' : '' ?>>1-3 Days</option>
                                        <option value="4-7" <?= ($_GET['duration'] ?? '') === '4-7' ? 'selected' : '' ?>>4-7 Days</option>
                                        <option value="8-14" <?= ($_GET['duration'] ?? '') === '8-14' ? 'selected' : '' ?>>1-2 Weeks</option>
                                        <option value="15+" <?= ($_GET['duration'] ?? '') === '15+' ? 'selected' : '' ?>>2+ Weeks</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <div class="relative">
                                    <i class="fas fa-tags absolute left-3 top-3 text-gray-400"></i>
                                    <select name="category" class="pl-10 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none">
                                        <option value="">All Categories</option>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= ($_GET['category'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Budget Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>
                                <div class="relative">
                                    <i class="fas fa-rupee-sign absolute left-3 top-3 text-gray-400"></i>
                                    <select name="budget" class="pl-10 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none">
                                        <option value="">Any Budget</option>
                                        <option value="0-500" <?= ($_GET['budget'] ?? '') === '0-500' ? 'selected' : '' ?>>Under ₹500</option>
                                        <option value="500-1000" <?= ($_GET['budget'] ?? '') === '500-1000' ? 'selected' : '' ?>>₹500 - ₹1,000</option>
                                        <option value="1000-2500" <?= ($_GET['budget'] ?? '') === '1000-2500' ? 'selected' : '' ?>>₹1,000 - ₹2,500</option>
                                        <option value="2500+" <?= ($_GET['budget'] ?? '') === '2500+' ? 'selected' : '' ?>>₹2,500+</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Filter Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 mt-6">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Apply Filters
                            </button>
                            <a href="<?= app_url('/packages') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg text-center transition duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Clear All
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Active Filters Display -->
        <?php 
        $activeFilters = array_filter([
            'destination' => $_GET['destination'] ?? '',
            'budget' => $_GET['budget'] ?? '',
            'duration' => $_GET['duration'] ?? '',
            'category' => $_GET['category'] ?? ''
        ]);
        ?>
        <?php if (!empty($activeFilters)): ?>
        <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-gray-700">Active filters:</span>
                <?php foreach ($activeFilters as $key => $value): ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <?= ucfirst($key) ?>: <span class="truncate max-w-24 sm:max-w-none"><?= htmlspecialchars($value) ?></span>
                    <a href="?<?= http_build_query(array_diff_key($_GET, [$key => ''])) ?>" class="ml-2 text-blue-600 hover:text-blue-800 flex-shrink-0">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
                <?php endforeach; ?>
                <a href="<?= app_url('/packages') ?>" class="text-sm text-gray-500 hover:text-gray-700 underline">
                    Clear all filters
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Package Results -->
        <?php if (!empty($packages)): ?>
        <!-- Grid View -->
        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <?php foreach ($packages as $package): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1 sm:hover:-translate-y-2">
                <div class="relative group">
                    <img src="<?= $package['featured_image'] ?: 'https://via.placeholder.com/400x250/667eea/ffffff?text=' . urlencode($package['title']) ?>" 
                         alt="<?= htmlspecialchars($package['title']) ?>" 
                         class="w-full h-40 sm:h-48 object-cover group-hover:scale-105 transition duration-300">
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300"></div>
                    
                    <!-- Category Badge -->
                    <?php if ($package['category_name']): ?>
                    <div class="absolute top-2 sm:top-3 left-2 sm:left-3">
                        <span class="bg-blue-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                            <?= htmlspecialchars($package['category_name']) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Rating Badge -->
                    <div class="absolute top-2 sm:top-3 right-2 sm:right-3">
                        <div class="bg-white bg-opacity-95 backdrop-blur-sm rounded-lg px-2 py-1 shadow-lg">
                            <div class="flex items-center text-xs">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="font-bold"><?= number_format($package['rating'], 1) ?></span>
                                <span class="text-gray-500 ml-1 hidden sm:inline">(<?= $package['total_reviews'] ?>)</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Wishlist Button -->
                    <div class="absolute bottom-2 sm:bottom-3 right-2 sm:right-3 opacity-0 group-hover:opacity-100 transition duration-300">
                        <button class="bg-white hover:bg-red-50 text-gray-600 hover:text-red-500 w-8 h-8 sm:w-10 sm:h-10 rounded-full shadow-lg transition duration-200" title="Add to wishlist">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 sm:p-5">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-2 min-h-[2.5rem] sm:min-h-[3.5rem]">
                        <?= htmlspecialchars($package['title']) ?>
                    </h3>
                    
                    <div class="flex items-center text-gray-600 text-sm mb-2">
                        <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                        <span class="truncate"><?= htmlspecialchars($package['destination']) ?></span>
                    </div>
                    
                    <p class="text-gray-700 text-sm mb-4 line-clamp-2 min-h-[2.5rem]">
                        <?= htmlspecialchars($package['short_description']) ?>
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs sm:text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                            <?= $package['duration_days'] ?> Days
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-1 text-blue-500"></i>
                            Up to <?= $package['max_guests'] ?>
                        </div>
                        <?php if ($package['total_bookings'] > 0): ?>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-1 text-green-500"></i>
                            <?= $package['total_bookings'] ?> booked
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <?php 
                                $startingPrice = $package['base_price'];
                                if (isset($package['price_tier_1_rate']) && $package['price_tier_1_rate'] > 0) {
                                    $startingPrice = $package['price_tier_1_rate'];
                                }
                                ?>
                                <div class="text-lg sm:text-2xl font-bold text-blue-600"><?= formatPrice($startingPrice) ?></div>
                                <div class="text-xs text-gray-500">starting from / adult</div>
                            </div>
                            <?php if ($package['is_featured']): ?>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>
                                    Featured
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?= app_url('/package/' . $package['slug']) ?>" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2.5 sm:py-3 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- List View -->
        <div x-show="viewMode === 'list'" class="space-y-4 sm:space-y-6 mb-6 sm:mb-8">
            <?php foreach ($packages as $package): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-80 relative">
                        <img src="<?= $package['featured_image'] ?: 'https://via.placeholder.com/320x200/667eea/ffffff?text=' . urlencode($package['title']) ?>" 
                             alt="<?= htmlspecialchars($package['title']) ?>" 
                             class="w-full h-48 lg:h-full object-cover">
                        
                        <!-- Category Badge -->
                        <?php if ($package['category_name']): ?>
                        <div class="absolute top-2 sm:top-3 left-2 sm:left-3">
                            <span class="bg-blue-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs font-medium">
                                <?= htmlspecialchars($package['category_name']) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Rating Badge -->
                        <div class="absolute top-2 sm:top-3 right-2 sm:right-3">
                            <div class="bg-white bg-opacity-95 backdrop-blur-sm rounded-lg px-2 py-1 shadow-lg">
                                <div class="flex items-center text-xs">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="font-bold"><?= number_format($package['rating'], 1) ?></span>
                                    <span class="text-gray-500 ml-1">(<?= $package['total_reviews'] ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-1 p-4 sm:p-6 lg:p-8">
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4">
                            <div class="flex-1 mb-4 lg:mb-0">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">
                                    <?= htmlspecialchars($package['title']) ?>
                                </h3>
                                
                                <div class="flex items-center text-gray-600 text-sm mb-3">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                    <span><?= htmlspecialchars($package['destination']) ?></span>
                                </div>
                                
                                <p class="text-gray-700 mb-4 leading-relaxed text-sm sm:text-base">
                                    <?= htmlspecialchars($package['short_description']) ?>
                                </p>
                                
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                        <?= $package['duration_days'] ?> Days
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-2 text-blue-500"></i>
                                        Up to <?= $package['max_guests'] ?>
                                    </div>
                                    <?php if ($package['total_bookings'] > 0): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                        <?= $package['total_bookings'] ?> booked
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($package['is_featured']): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-star mr-2 text-yellow-500"></i>
                                        Featured
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="text-center lg:text-right lg:ml-6 border-t lg:border-t-0 pt-4 lg:pt-0">
                                <?php 
                                $startingPrice = $package['base_price'];
                                if (isset($package['price_tier_1_rate']) && $package['price_tier_1_rate'] > 0) {
                                    $startingPrice = $package['price_tier_1_rate'];
                                }
                                ?>
                                <div class="text-2xl sm:text-3xl font-bold text-blue-600 mb-1"><?= formatPrice($startingPrice) ?></div>
                                <div class="text-sm text-gray-500 mb-4">starting from / adult</div>
                                
                                <div class="flex flex-col sm:flex-row lg:flex-col space-y-2 sm:space-y-0 sm:space-x-2 lg:space-x-0 lg:space-y-2">
                                    <a href="<?= app_url('/package/' . $package['slug']) ?>" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 sm:px-6 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                                        View Details
                                    </a>
                                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-center py-2 px-4 sm:px-6 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                                        <i class="far fa-heart mr-1"></i>
                                        Wishlist
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Enhanced Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-xs sm:text-sm text-gray-700 mb-4 md:mb-0">
                    Showing <span class="font-medium"><?= $pagination['offset'] + 1 ?></span> to 
                    <span class="font-medium"><?= min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items']) ?></span> of 
                    <span class="font-medium"><?= $pagination['total_items'] ?></span> results
                </div>
                
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['prev_page']])) ?>" 
                       class="px-2 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 text-sm">
                        <i class="fas fa-chevron-left mr-1 sm:mr-2"></i><span class="hidden sm:inline">Previous</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php
                    $startPage = max(1, $pagination['current_page'] - 1);
                    $endPage = min($pagination['total_pages'], $pagination['current_page'] + 1);
                    
                    // Show fewer pages on mobile
                    if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone/', $_SERVER['HTTP_USER_AGENT'])) {
                        $startPage = max(1, $pagination['current_page'] - 1);
                        $endPage = min($pagination['total_pages'], $pagination['current_page'] + 1);
                    }
                    
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                       class="px-2 sm:px-4 py-2 border rounded-lg transition duration-200 text-sm <?= $i === $pagination['current_page'] ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-300 hover:bg-gray-50 text-gray-700' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['has_next']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])) ?>" 
                       class="px-2 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 text-sm">
                        <span class="hidden sm:inline">Next</span><i class="fas fa-chevron-right ml-1 sm:ml-2"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- Enhanced No Results -->
        <div class="bg-white rounded-xl shadow-lg text-center py-12 sm:py-16">
            <div class="max-w-md mx-auto px-4">
                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-3xl sm:text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">No packages found</h3>
                <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-base">
                    We couldn't find any packages matching your criteria. Try adjusting your filters or search terms.
                </p>
                
                <div class="space-y-3">
                    <a href="<?= app_url('/packages') ?>" 
                       class="block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-refresh mr-2"></i>
                        View All Packages
                    </a>
                    
                    <?php if (!empty($activeFilters)): ?>
                    <button onclick="history.back()" 
                            class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Go Back
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom breakpoint for extra small screens */
@media (min-width: 475px) {
    .xs\:flex-row {
        flex-direction: row;
    }
    .xs\:items-center {
        align-items: center;
    }
    .xs\:space-y-0 > :not([hidden]) ~ :not([hidden]) {
        --tw-space-y-reverse: 0;
        margin-top: calc(0px * calc(1 - var(--tw-space-y-reverse)));
        margin-bottom: calc(0px * var(--tw-space-y-reverse));
    }
    .xs\:space-x-3 > :not([hidden]) ~ :not([hidden]) {
        --tw-space-x-reverse: 0;
        margin-right: calc(0.75rem * var(--tw-space-x-reverse));
        margin-left: calc(0.75rem * calc(1 - var(--tw-space-x-reverse)));
    }
    .xs\:w-auto {
        width: auto;
    }
    .xs\:flex-none {
        flex: none;
    }
    .xs\:inline {
        display: inline;
    }
    .xs\:hidden {
        display: none;
    }
}
</style> 