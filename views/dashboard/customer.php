<?php
// Capture the page content
ob_start();
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-tachometer-alt text-blue-600 mr-3"></i>
                    Welcome back, <?= htmlspecialchars($user['first_name']) ?>!
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your bookings and discover new adventures
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?= app_url('/packages') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Browse Packages
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-suitcase-rolling text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['total_bookings'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completed Trips</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['completed_trips'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Bookings</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['pending_bookings'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-rupee-sign text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Spent</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= formatPrice($stats['total_spent']) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Bookings -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-history text-blue-600 mr-2"></i>
                            Recent Bookings
                        </h3>
                        
                        <?php if (empty($recentBookings)): ?>
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-suitcase-rolling text-gray-400 text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h4>
                                <p class="text-gray-500 mb-4">Start your journey by booking your first travel package!</p>
                                <a href="<?= app_url('/packages') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                                    Browse Packages
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($recentBookings as $booking): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($booking['package_title']) ?></h4>
                                                <p class="text-sm text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    <?= htmlspecialchars($booking['destination']) ?>
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    Booking ID: <?= htmlspecialchars($booking['booking_id']) ?> • 
                                                    Booked on <?= formatDate($booking['created_at']) ?>
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <span class="text-sm font-medium text-gray-900">
                                                    <?= formatPrice($booking['total_amount']) ?>
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    <?php
                                                    switch($booking['status']) {
                                                        case 'confirmed':
                                                            echo 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'pending':
                                                            echo 'bg-yellow-100 text-yellow-800';
                                                            break;
                                                        case 'cancelled':
                                                            echo 'bg-red-100 text-red-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                    ?>">
                                                    <?= ucfirst($booking['status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="text-center pt-4">
                                    <a href="<?= app_url('/customer/bookings') ?>" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                        View all bookings →
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Profile -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="<?= app_url('/packages') ?>" class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Browse New Packages
                            </a>
                            <a href="<?= app_url('/customer/bookings') ?>" class="block w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-list mr-2"></i>
                                View All Bookings
                            </a>
                            <a href="<?= app_url('/customer/profile') ?>" class="block w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-user-edit mr-2"></i>
                                Edit Profile
                            </a>
                            <a href="<?= app_url('/customer/reviews') ?>" class="block w-full bg-orange-50 hover:bg-orange-100 text-orange-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-star mr-2"></i>
                                My Reviews
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-user-circle text-gray-600 mr-2"></i>
                            Account Information
                        </h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-sm text-gray-900"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900"><?= htmlspecialchars($user['email']) ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="text-sm text-gray-900"><?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                <dd class="text-sm text-gray-900"><?= formatDate($user['created_at']) ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Travel Tips -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 shadow rounded-lg text-white">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium mb-3">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Travel Tip
                        </h3>
                        <p class="text-sm text-blue-100">
                            Book your trips 2-3 months in advance to get the best deals and availability. Early birds get the best destinations!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content and pass it to the layout
$content = ob_get_clean();
include_once 'includes/layout.php';
?> 