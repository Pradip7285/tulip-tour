<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' . AppConfig::get('app_name') : AppConfig::get('app_name') . ' - ' . AppConfig::get('app_description') ?></title>
    <meta name="description" content="<?= isset($pageDescription) ? $pageDescription : AppConfig::get('app_description') ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe', 
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?= app_url('/') ?>" class="flex-shrink-0 flex items-center">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plane text-white text-lg"></i>
                        </div>
                        <span class="font-bold text-xl text-gray-900"><?= AppConfig::get('app_name') ?></span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="<?= app_url('/') ?>" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">Home</a>
                    <a href="<?= app_url('/packages') ?>" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">Packages</a>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php $user = getCurrentUser(); ?>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">
                                <i class="fas fa-user mr-1"></i>
                                <?= htmlspecialchars($user['first_name']) ?>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <?php if ($user['role'] === 'customer'): ?>
                                    <a href="<?= app_url('/customer/dashboard') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Dashboard</a>
                                <?php elseif ($user['role'] === 'provider'): ?>
                                    <a href="<?= app_url('/provider/dashboard') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Provider Dashboard</a>
                                <?php elseif ($user['role'] === 'admin'): ?>
                                    <a href="<?= app_url('/admin/dashboard') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                                <?php endif; ?>
                                <a href="<?= app_url('/logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= app_url('/login') ?>" class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium">Login</a>
                        <a href="<?= app_url('/register') ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">Sign Up</a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-primary-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="md:hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="<?= app_url('/') ?>" class="block text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium">Home</a>
                <a href="<?= app_url('/packages') ?>" class="block text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium">Packages</a>
                
                <?php if (isLoggedIn()): ?>
                    <?php $user = getCurrentUser(); ?>
                    <div class="border-t border-gray-200 pt-2">
                        <div class="px-3 py-2 text-sm text-gray-500">Signed in as <?= htmlspecialchars($user['first_name']) ?></div>
                        <?php if ($user['role'] === 'customer'): ?>
                            <a href="<?= app_url('/customer/dashboard') ?>" class="block text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium">My Dashboard</a>
                        <?php elseif ($user['role'] === 'provider'): ?>
                            <a href="<?= app_url('/provider/dashboard') ?>" class="block text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium">Provider Dashboard</a>
                        <?php elseif ($user['role'] === 'admin'): ?>
                            <a href="<?= app_url('/admin/dashboard') ?>" class="block text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium">Admin Dashboard</a>
                        <?php endif; ?>
                        <a href="<?= app_url('/logout') ?>" class="block text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="border-t border-gray-200 pt-2">
                        <a href="<?= app_url('/login') ?>" class="block text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium">Login</a>
                        <a href="<?= app_url('/register') ?>" class="block bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 text-base font-medium">Sign Up</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($successMessage = getFlash('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-4 mt-4 rounded" x-data="{ show: true }" x-show="show">
            <span class="block sm:inline"><?= htmlspecialchars($successMessage) ?></span>
            <button @click="show = false" class="float-right">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>
    
    <?php if ($errorMessage = getFlash('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-4 mt-4 rounded" x-data="{ show: true }" x-show="show">
            <span class="block sm:inline"><?= htmlspecialchars($errorMessage) ?></span>
            <button @click="show = false" class="float-right">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plane text-white text-lg"></i>
                        </div>
                        <span class="font-bold text-xl"><?= AppConfig::get('app_name') ?></span>
                    </div>
                    <p class="text-gray-300 mb-4"><?= AppConfig::get('app_description') ?></p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition duration-200">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="<?= app_url('/') ?>" class="text-gray-300 hover:text-white transition duration-200">Home</a></li>
                        <li><a href="<?= app_url('/packages') ?>" class="text-gray-300 hover:text-white transition duration-200">Travel Packages</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">About Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Contact</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Blog</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Help Center</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Terms & Conditions</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; <?= date('Y') ?> <?= AppConfig::get('app_name') ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> 