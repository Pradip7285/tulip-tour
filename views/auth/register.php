<?php
// Capture the page content
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Join TripBazaar
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Create your account and start your journey with us
            </p>
        </div>

        <!-- Flash Messages -->
        <?php displayFlashMessage(); ?>

        <form class="mt-8 space-y-6" action="<?php echo app_url('/auth/register'); ?>" method="POST" x-data="{ 
            showPassword: false, 
            showConfirmPassword: false,
            selectedRole: 'customer',
            password: '',
            confirmPassword: '',
            passwordsMatch: true,
            checkPasswords() {
                this.passwordsMatch = this.password === this.confirmPassword;
            }
        }">
            <?php echo generateCSRFToken(); ?>
            
            <!-- Role Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Account Type</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input 
                            type="radio" 
                            name="role" 
                            value="customer" 
                            x-model="selectedRole"
                            class="sr-only"
                        >
                        <div class="border-2 rounded-lg p-4 text-center transition-all"
                             :class="selectedRole === 'customer' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'">
                            <i class="fas fa-user text-2xl mb-2" :class="selectedRole === 'customer' ? 'text-blue-600' : 'text-gray-400'"></i>
                            <div class="font-medium text-sm" :class="selectedRole === 'customer' ? 'text-blue-900' : 'text-gray-700'">Customer</div>
                            <div class="text-xs text-gray-500 mt-1">Book amazing trips</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input 
                            type="radio" 
                            name="role" 
                            value="provider" 
                            x-model="selectedRole"
                            class="sr-only"
                        >
                        <div class="border-2 rounded-lg p-4 text-center transition-all"
                             :class="selectedRole === 'provider' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'">
                            <i class="fas fa-briefcase text-2xl mb-2" :class="selectedRole === 'provider' ? 'text-blue-600' : 'text-gray-400'"></i>
                            <div class="font-medium text-sm" :class="selectedRole === 'provider' ? 'text-blue-900' : 'text-gray-700'">Provider</div>
                            <div class="text-xs text-gray-500 mt-1">Offer travel packages</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Name Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="sr-only">First Name</label>
                        <input 
                            id="first_name" 
                            name="first_name" 
                            type="text" 
                            required 
                            class="relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="First Name"
                            value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                        >
                    </div>
                    <div>
                        <label for="last_name" class="sr-only">Last Name</label>
                        <input 
                            id="last_name" 
                            name="last_name" 
                            type="text" 
                            required 
                            class="relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="Last Name"
                            value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Email address"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="sr-only">Phone Number</label>
                    <input 
                        id="phone" 
                        name="phone" 
                        type="tel" 
                        required 
                        class="relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Phone Number"
                        value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                    >
                </div>

                <!-- Password -->
                <div class="relative">
                    <label for="password" class="sr-only">Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        :type="showPassword ? 'text' : 'password'"
                        autocomplete="new-password" 
                        required 
                        x-model="password"
                        @input="checkPasswords()"
                        class="relative block w-full px-3 py-3 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Password (min. 8 characters)"
                    >
                    <button 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        @click="showPassword = !showPassword"
                    >
                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="h-4 w-4 text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <label for="confirm_password" class="sr-only">Confirm Password</label>
                    <input 
                        id="confirm_password" 
                        name="confirm_password" 
                        :type="showConfirmPassword ? 'text' : 'password'"
                        autocomplete="new-password" 
                        required 
                        x-model="confirmPassword"
                        @input="checkPasswords()"
                        class="relative block w-full px-3 py-3 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Confirm Password"
                        :class="!passwordsMatch && confirmPassword ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''"
                    >
                    <button 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        @click="showConfirmPassword = !showConfirmPassword"
                    >
                        <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="h-4 w-4 text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
                <div x-show="!passwordsMatch && confirmPassword" class="text-red-500 text-xs mt-1">
                    Passwords do not match
                </div>

                <!-- Password Requirements -->
                <div class="text-xs text-gray-500 space-y-1">
                    <div>Password must contain:</div>
                    <div class="grid grid-cols-1 gap-1 ml-2">
                        <div>• At least 8 characters</div>
                        <div>• One uppercase letter</div>
                        <div>• One lowercase letter</div>
                        <div>• One number</div>
                    </div>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="flex items-center">
                <input 
                    id="agree_terms" 
                    name="agree_terms" 
                    type="checkbox" 
                    required
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <label for="agree_terms" class="ml-2 block text-sm text-gray-900">
                    I agree to the 
                    <a href="<?php echo app_url('/terms'); ?>" class="text-blue-600 hover:text-blue-500" target="_blank">Terms of Service</a> 
                    and 
                    <a href="<?php echo app_url('/privacy'); ?>" class="text-blue-600 hover:text-blue-500" target="_blank">Privacy Policy</a>
                </label>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="!passwordsMatch || !password || !confirmPassword"
                >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Create Account
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="<?php echo app_url('/login'); ?>" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
        </form>

        <!-- Social Registration Section -->
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-50 text-gray-500">Or continue with</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <i class="fab fa-google text-red-500"></i>
                    <span class="ml-2">Google</span>
                </button>
                <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <i class="fab fa-facebook text-blue-600"></i>
                    <span class="ml-2">Facebook</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on first name field
    document.getElementById('first_name').focus();
});
</script>

<?php
// Get the content and pass it to the layout
$content = ob_get_clean();
include_once 'includes/layout.php';
?> 