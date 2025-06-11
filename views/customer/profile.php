<?php
// Capture the page content
ob_start();
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                My Profile
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Update your personal information and account settings
            </p>
        </div>

        <!-- Flash Messages -->
        <?php displayFlashMessage(); ?>

        <!-- Profile Form -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form method="POST" action="<?= app_url('/customer/profile') ?>">
                    <?= generateCSRFToken() ?>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Personal Information Section -->
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Personal Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">
                                        First Name *
                                    </label>
                                    <input 
                                        type="text" 
                                        name="first_name" 
                                        id="first_name" 
                                        required
                                        value="<?= htmlspecialchars($user['first_name']) ?>"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                </div>
                                
                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">
                                        Last Name *
                                    </label>
                                    <input 
                                        type="text" 
                                        name="last_name" 
                                        id="last_name" 
                                        required
                                        value="<?= htmlspecialchars($user['last_name']) ?>"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information Section -->
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Contact Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">
                                        Email Address *
                                    </label>
                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="email" 
                                        required
                                        value="<?= htmlspecialchars($user['email']) ?>"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                </div>
                                
                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">
                                        Phone Number
                                    </label>
                                    <input 
                                        type="tel" 
                                        name="phone" 
                                        id="phone"
                                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                        placeholder="+1 (555) 123-4567"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        <!-- Account Information (Read-only) -->
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Account Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Account Type
                                    </label>
                                    <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        <i class="fas fa-user mr-2 text-blue-600"></i>
                                        <?= ucfirst($user['role']) ?>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Member Since
                                    </label>
                                    <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        <i class="fas fa-calendar mr-2 text-blue-600"></i>
                                        <?= formatDate($user['created_at']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Section -->
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Password
                            </h3>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">
                                            Password Change
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>For security reasons, password changes require email verification. Contact support to change your password.</p>
                                        </div>
                                        <div class="mt-4">
                                            <a href="<?= app_url('/forgot-password') ?>" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                                                Request Password Change
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="<?= app_url('/customer/dashboard') ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Preferences Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-cog text-gray-600 mr-2"></i>
                    Preferences
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Customize your experience and notification settings.
                </p>
                <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    Manage Preferences
                </button>
            </div>
            
            <!-- Data & Privacy Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-shield-alt text-gray-600 mr-2"></i>
                    Data & Privacy
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Download your data or delete your account.
                </p>
                <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    Privacy Settings
                </button>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content and pass it to the layout
$content = ob_get_clean();
include_once 'includes/layout.php';
?> 