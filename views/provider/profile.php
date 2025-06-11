<?php
$pageTitle = 'Provider Profile - TripBazaar';
$pageDescription = 'Manage your provider profile and business information';
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
                            <span class="text-sm font-medium text-gray-500">Provider Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="mt-4">
                <h1 class="text-3xl font-bold leading-7 text-gray-900">
                    <i class="fas fa-user-cog text-blue-600 mr-3"></i>
                    Provider Profile
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your business information and account settings
                </p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Profile Form -->
            <form method="POST" class="bg-white shadow-lg rounded-xl">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Personal Information Section -->
                        <div class="sm:col-span-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                <i class="fas fa-user text-blue-600 mr-2"></i>
                                Personal Information
                            </h3>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" required
                                   value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="last_name" required
                                   value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" disabled
                                   value="<?= htmlspecialchars($profile['email'] ?? '') ?>"
                                   class="mt-1 bg-gray-50 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Email cannot be changed. Contact support if needed.</p>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" name="phone" id="phone"
                                   value="<?= htmlspecialchars($profile['phone'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Business Information Section -->
                        <div class="sm:col-span-6 mt-8">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                <i class="fas fa-building text-blue-600 mr-2"></i>
                                Business Information
                            </h3>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name <span class="text-red-500">*</span></label>
                            <input type="text" name="company_name" id="company_name" required
                                   value="<?= htmlspecialchars($profile['company_name'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="license_number" class="block text-sm font-medium text-gray-700">License Number</label>
                            <input type="text" name="license_number" id="license_number"
                                   value="<?= htmlspecialchars($profile['license_number'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Company Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                      placeholder="Tell customers about your travel company..."><?= htmlspecialchars($profile['description'] ?? '') ?></textarea>
                        </div>

                        <!-- Address Information -->
                        <div class="sm:col-span-6 mt-8">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                                Address Information
                            </h3>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="address" class="block text-sm font-medium text-gray-700">Street Address</label>
                            <input type="text" name="address" id="address"
                                   value="<?= htmlspecialchars($profile['address'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" id="city"
                                   value="<?= htmlspecialchars($profile['city'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                            <input type="text" name="state" id="state"
                                   value="<?= htmlspecialchars($profile['state'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" name="country" id="country"
                                   value="<?= htmlspecialchars($profile['country'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Banking Information -->
                        <div class="sm:col-span-6 mt-8">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                <i class="fas fa-university text-blue-600 mr-2"></i>
                                Banking Information
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">This information is required for receiving payments from bookings.</p>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name"
                                   value="<?= htmlspecialchars($profile['bank_name'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="account_holder_name" class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                            <input type="text" name="account_holder_name" id="account_holder_name"
                                   value="<?= htmlspecialchars($profile['account_holder_name'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                            <input type="text" name="account_number" id="account_number"
                                   value="<?= htmlspecialchars($profile['account_number'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="ifsc_code" class="block text-sm font-medium text-gray-700">IFSC Code</label>
                            <input type="text" name="ifsc_code" id="ifsc_code"
                                   value="<?= htmlspecialchars($profile['ifsc_code'] ?? '') ?>"
                                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-xl">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Complete your profile to get verified status
                        </div>
                        <div class="flex space-x-3">
                            <a href="<?= app_url('/provider/dashboard') ?>" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Save Profile
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Profile Status Card -->
            <?php if (isset($profile['is_verified'])): ?>
            <div class="bg-white shadow-lg rounded-xl p-6">
                <div class="flex items-center">
                    <?php if ($profile['is_verified']): ?>
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Verified Provider</h3>
                            <p class="text-sm text-gray-500">Your profile has been verified. You can now receive bookings.</p>
                        </div>
                    <?php else: ?>
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Verification Pending</h3>
                            <p class="text-sm text-gray-500">Complete all required fields to submit for verification.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div> 