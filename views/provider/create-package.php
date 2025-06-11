<?php
$pageTitle = 'Create New Package - TripBazaar';
$pageDescription = 'Add a new travel package to your listings';
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Page header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?= app_url('/provider/dashboard') ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="<?= app_url('/provider/packages') ?>" class="text-sm font-medium text-gray-700 hover:text-blue-600">Packages</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Create Package</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="mt-4">
                <h1 class="text-3xl font-bold leading-7 text-gray-900">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    Create New Package
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Add a new travel package to your listings
                </p>
            </div>
        </div>

        <!-- Package Form -->
        <form method="POST" class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Package Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" required
                                   placeholder="e.g., 5 Days Kashmir Valley Paradise Tour"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Choose a compelling title that describes your package</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Select Category</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="destination" class="block text-sm font-medium text-gray-700">Destination <span class="text-red-500">*</span></label>
                            <input type="text" name="destination" id="destination" required
                                   placeholder="e.g., Kashmir, India"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="duration_days" class="block text-sm font-medium text-gray-700">Duration (Days) <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_days" id="duration_days" required min="1" max="30"
                                   placeholder="5"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-6">
                            <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                            <textarea name="short_description" id="short_description" rows="2"
                                      placeholder="A brief overview of what makes this package special..."
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            <p class="mt-1 text-xs text-gray-500">This will appear in package listings (optional)</p>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Detailed Description <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="6" required
                                      placeholder="Provide a detailed description of your package including itinerary highlights, activities, and what travelers can expect..."
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Capacity -->
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
                        <i class="fas fa-rupee-sign text-blue-600 mr-2"></i>
                        Pricing & Capacity
                    </h3>
                    
                    <!-- Basic Pricing -->
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 mb-8">
                        <div class="sm:col-span-2">
                            <label for="base_price" class="block text-sm font-medium text-gray-700">Base Adult Price (₹) <span class="text-red-500">*</span></label>
                            <input type="number" name="base_price" id="base_price" required min="0" step="0.01"
                                   placeholder="15000"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Starting price for 1-2 adults</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="child_price" class="block text-sm font-medium text-gray-700">Child Price (₹) <span class="text-red-500">*</span></label>
                            <input type="number" name="child_price" id="child_price" required min="0" step="0.01"
                                   placeholder="8000"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Price per child (2-12 years)</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="extra_room_price" class="block text-sm font-medium text-gray-700">Extra Room Price (₹)</label>
                            <input type="number" name="extra_room_price" id="extra_room_price" min="0" step="0.01"
                                   placeholder="2000"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Additional cost for extra room</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="max_guests" class="block text-sm font-medium text-gray-700">Maximum Guests</label>
                            <input type="number" name="max_guests" id="max_guests" min="1" max="100"
                                   placeholder="20" value="20"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Maximum number of travelers</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="max_guests_per_room" class="block text-sm font-medium text-gray-700">Guests per Room</label>
                            <input type="number" name="max_guests_per_room" id="max_guests_per_room" min="1" max="6"
                                   placeholder="2" value="2"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Max guests per room/accommodation</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="child_free_age" class="block text-sm font-medium text-gray-700">Child Free Age</label>
                            <input type="number" name="child_free_age" id="child_free_age" min="0" max="12"
                                   placeholder="7" value="7"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Children under this age travel free</p>
                        </div>
                    </div>

                    <!-- Tiered Pricing -->
                    <div class="border-t pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-md font-medium text-gray-900">
                                <i class="fas fa-layer-group text-purple-600 mr-2"></i>
                                Tiered Pricing (Group Discounts)
                            </h4>
                            <label class="flex items-center">
                                <input type="checkbox" id="enable_tiered_pricing" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Enable Group Discounts</span>
                            </label>
                        </div>
                        
                        <div id="tiered_pricing_section" class="hidden">
                            <p class="text-sm text-gray-600 mb-4">Offer lower prices for larger groups to attract more bookings!</p>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <!-- Tier 1: 3-4 Adults -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">Tier 1</span>
                                        3-4 Adults
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input type="number" name="price_tier_1_max" placeholder="4" value="4" min="3" max="10"
                                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <p class="text-xs text-gray-500 mt-1">Max adults</p>
                                        </div>
                                        <div>
                                            <input type="number" name="price_tier_1_rate" placeholder="14000" min="0" step="0.01"
                                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <p class="text-xs text-gray-500 mt-1">Price per adult (₹)</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tier 2: 5-6 Adults -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">Tier 2</span>
                                        5-6 Adults
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input type="number" name="price_tier_2_max" placeholder="6" value="6" min="5" max="15"
                                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <p class="text-xs text-gray-500 mt-1">Max adults</p>
                                        </div>
                                        <div>
                                            <input type="number" name="price_tier_2_rate" placeholder="13000" min="0" step="0.01"
                                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <p class="text-xs text-gray-500 mt-1">Price per adult (₹)</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tier 3: 7-8 Adults -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mr-2">Tier 3</span>
                                        7-8 Adults
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input type="number" name="price_tier_3_max" placeholder="8" value="8" min="7" max="20"
                                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <p class="text-xs text-gray-500 mt-1">Max adults</p>
                                        </div>
                                        <div>
                                            <input type="number" name="price_tier_3_rate" placeholder="12000" min="0" step="0.01"
                                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <p class="text-xs text-gray-500 mt-1">Price per adult (₹)</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tier 4: 9+ Adults -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mr-2">Tier 4</span>
                                        9+ Adults (Groups)
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input type="number" name="price_tier_4_max" placeholder="20" value="20" min="9" max="100"
                                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <p class="text-xs text-gray-500 mt-1">Max adults</p>
                                        </div>
                                        <div>
                                            <input type="number" name="price_tier_4_rate" placeholder="11000" min="0" step="0.01"
                                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <p class="text-xs text-gray-500 mt-1">Price per adult (₹)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-amber-50 rounded-lg">
                                <p class="text-sm text-amber-700">
                                    <i class="fas fa-lightbulb mr-1"></i>
                                    <strong>Tip:</strong> Lower prices for larger groups often result in higher overall revenue and better customer satisfaction!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Details -->
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
                        <i class="fas fa-list text-blue-600 mr-2"></i>
                        Package Details
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div>
                            <label for="inclusions" class="block text-sm font-medium text-gray-700">Inclusions</label>
                            <textarea name="inclusions" id="inclusions" rows="6"
                                      placeholder="• Accommodation in 3-star hotels&#10;• All meals (breakfast, lunch, dinner)&#10;• Transportation by AC vehicle&#10;• Professional tour guide&#10;• Entry fees to monuments&#10;• Airport transfers"
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            <p class="mt-1 text-xs text-gray-500">List what's included in the package (one per line)</p>
                        </div>

                        <div>
                            <label for="exclusions" class="block text-sm font-medium text-gray-700">Exclusions</label>
                            <textarea name="exclusions" id="exclusions" rows="6"
                                      placeholder="• Personal expenses&#10;• Tips and gratuities&#10;• Travel insurance&#10;• Alcoholic beverages&#10;• Camera fees&#10;• Items of personal nature"
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            <p class="mt-1 text-xs text-gray-500">List what's not included in the package (one per line)</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="terms_conditions" class="block text-sm font-medium text-gray-700">Terms & Conditions</label>
                        <textarea name="terms_conditions" id="terms_conditions" rows="4"
                                  placeholder="• Booking confirmation required 48 hours in advance&#10;• 50% advance payment required&#10;• Cancellation charges apply as per policy&#10;• ID proof mandatory for all travelers"
                                  class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        <p class="mt-1 text-xs text-gray-500">Important terms and conditions for travelers</p>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
                        <i class="fas fa-images text-blue-600 mr-2"></i>
                        Package Images
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-y-6">
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image URL</label>
                            <input type="url" name="featured_image" id="featured_image"
                                   placeholder="https://example.com/images/package-main.jpg"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Main image that will be displayed prominently</p>
                        </div>

                        <div>
                            <label for="additional_images" class="block text-sm font-medium text-gray-700">Additional Images</label>
                            <textarea name="additional_images" id="additional_images" rows="4"
                                      placeholder="https://example.com/images/image1.jpg&#10;https://example.com/images/image2.jpg&#10;https://example.com/images/image3.jpg"
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            <p class="mt-1 text-xs text-gray-500">Additional images (one URL per line)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Status -->
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
                        <i class="fas fa-toggle-on text-blue-600 mr-2"></i>
                        Package Status
                    </h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Activate package immediately
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">You can change this setting later from the packages list</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="<?= app_url('/provider/packages') ?>" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Create Package
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate short description from title and destination
    const titleInput = document.getElementById('title');
    const destinationInput = document.getElementById('destination');
    const shortDescInput = document.getElementById('short_description');
    
    function updateShortDescription() {
        if (!shortDescInput.value && titleInput.value && destinationInput.value) {
            shortDescInput.placeholder = `Experience the best of ${destinationInput.value} with our ${titleInput.value.toLowerCase()}`;
        }
    }
    
    titleInput.addEventListener('blur', updateShortDescription);
    destinationInput.addEventListener('blur', updateShortDescription);
    
    // Validate form before submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const requiredFields = ['title', 'destination', 'duration_days', 'base_price', 'child_price', 'description'];
        let hasErrors = false;
        
        requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                hasErrors = true;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            alert('Please fill in all required fields marked with *');
        }
    });
});
</script> 