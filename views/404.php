<?php
$pageTitle = '404 - Page Not Found';
$pageDescription = 'The page you are looking for could not be found.';

ob_start();
?>

<div class="min-h-screen bg-gray-50 flex flex-col justify-center items-center">
    <div class="max-w-md w-full text-center">
        <!-- 404 Illustration -->
        <div class="mb-8">
            <div class="text-9xl font-bold text-primary-600 mb-4">404</div>
            <div class="text-6xl mb-4">
                <i class="fas fa-plane-slash text-gray-300"></i>
            </div>
        </div>
        
        <!-- Error Message -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Oops! Flight Not Found</h1>
            <p class="text-xl text-gray-600 mb-2">The page you're looking for seems to have taken a different route.</p>
            <p class="text-gray-500">Don't worry, let's get you back on track!</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="<?= app_url('/') ?>" 
               class="block w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105">
                <i class="fas fa-home mr-2"></i>
                Return Home
            </a>
            
            <a href="<?= app_url('/packages') ?>" 
               class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
                <i class="fas fa-search mr-2"></i>
                Browse Packages
            </a>
        </div>
        
        <!-- Help Text -->
        <div class="mt-8 text-sm text-gray-500">
            <p>If you believe this is an error, please <a href="#" class="text-primary-600 hover:text-primary-700">contact support</a></p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?> 