<?php
// Capture the page content
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                <i class="fas fa-plane text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Welcome back to TripBazaar
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sign in to your account to continue your journey
            </p>
        </div>

        <!-- Flash Messages -->
        <?php displayFlashMessage(); ?>

        <form class="mt-8 space-y-6" action="<?php echo app_url('/auth/login'); ?>" method="POST" x-data="{ showPassword: false }">
            <?php echo generateCSRFToken(); ?>
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                        placeholder="Email address"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                </div>
                <div class="relative">
                    <label for="password" class="sr-only">Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        :type="showPassword ? 'text' : 'password'"
                        autocomplete="current-password" 
                        required 
                        class="relative block w-full px-3 py-3 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                        placeholder="Password"
                    >
                    <button 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        @click="showPassword = !showPassword"
                    >
                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="h-4 w-4 text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        name="remember" 
                        type="checkbox" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="<?php echo app_url('/forgot-password'); ?>" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105"
                >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Sign in
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="<?php echo app_url('/register'); ?>" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Sign up for free
                    </a>
                </p>
            </div>

            <!-- Demo Accounts -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Demo Accounts:</h3>
                <div class="space-y-2 text-xs text-gray-600">
                    <div>
                        <strong>Customer:</strong> customer@tripbazaar.com / password123
                    </div>
                    <div>
                        <strong>Provider:</strong> provider@tripbazaar.com / password123
                    </div>
                    <div>
                        <strong>Admin:</strong> admin@tripbazaar.com / password123
                    </div>
                </div>
            </div>
        </form>

        <!-- Social Login Section -->
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
    // Auto-focus on email field
    document.getElementById('email').focus();
    
    // Demo account quick login
    const demoButtons = document.querySelectorAll('[data-demo]');
    demoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const email = this.dataset.email;
            const password = this.dataset.password;
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        });
    });
});
</script>

<?php
// Get the content and pass it to the layout
$content = ob_get_clean();
include_once 'includes/layout.php';
?> 