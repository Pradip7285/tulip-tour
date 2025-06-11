<?php include_once 'includes/layout.php'; ?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-briefcase text-blue-600 mr-3"></i>
                    Provider Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your packages and track your business performance
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="/provider/packages/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Add Package
                </a>
                <a href="/provider/packages" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    <i class="fas fa-cog mr-2"></i>
                    Manage Packages
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
                                <i class="fas fa-box text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Packages</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['total_packages'] ?></dd>
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Packages</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['active_packages'] ?></dd>
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
                                <i class="fas fa-shopping-cart text-yellow-600"></i>
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
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-rupee-sign text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Earnings</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= formatPrice($stats['total_earnings']) ?></dd>
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
                                    <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h4>
                                <p class="text-gray-500 mb-4">Create your first package to start receiving bookings!</p>
                                <a href="/provider/packages/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                                    Add Package
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
                                                    <i class="fas fa-user mr-1"></i>
                                                    <?= htmlspecialchars($booking['customer_name']) ?>
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    Booking ID: <?= htmlspecialchars($booking['booking_id']) ?> • 
                                                    <?= formatDate($booking['booking_date']) ?>
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div class="text-right">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= formatPrice($booking['total_amount']) ?>
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Your share: <?= formatPrice($booking['provider_amount']) ?>
                                                    </div>
                                                </div>
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
                                    <a href="/provider/bookings" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                        View all bookings →
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- My Packages -->
                <div class="bg-white shadow rounded-lg mt-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-suitcase text-green-600 mr-2"></i>
                            My Packages
                        </h3>
                        
                        <?php if (empty($packages)): ?>
                            <div class="text-center py-6">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-box text-gray-400 text-lg"></i>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">No packages yet</h4>
                                <p class="text-xs text-gray-500 mb-3">Create your first travel package to start your business!</p>
                                <a href="/provider/packages/create" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-medium transition duration-200">
                                    Add Package
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach ($packages as $package): ?>
                                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($package['title']) ?></h4>
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    <?= htmlspecialchars($package['destination']) ?> • 
                                                    <?= $package['duration_days'] ?> days
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-medium text-gray-900">
                                                    <?= formatPrice($package['base_price']) ?>
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                    <?= $package['is_active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                                    <?= $package['is_active'] ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="text-center pt-3">
                                    <a href="/provider/packages" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                        Manage all packages →
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Earnings Summary -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 shadow rounded-lg text-white">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium mb-4">
                            <i class="fas fa-chart-line mr-2"></i>
                            Earnings Summary
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-green-100">This Month</div>
                                <div class="text-xl font-bold"><?= formatPrice($earnings['this_month']) ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-green-100">Total Earnings</div>
                                <div class="text-lg font-semibold"><?= formatPrice($earnings['total']) ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-green-100">Pending Payouts</div>
                                <div class="text-lg font-semibold"><?= formatPrice($earnings['pending_payouts']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="/provider/packages/create" class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Add New Package
                            </a>
                            <a href="/provider/packages" class="block w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-cog mr-2"></i>
                                Manage Packages
                            </a>
                            <a href="/provider/bookings" class="block w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-list mr-2"></i>
                                View All Bookings
                            </a>
                            <a href="/provider/profile" class="block w-full bg-orange-50 hover:bg-orange-100 text-orange-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-user-edit mr-2"></i>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Business Tip -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 shadow rounded-lg text-white">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium mb-3">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Business Tip
                        </h3>
                        <p class="text-sm text-indigo-100">
                            High-quality photos and detailed descriptions can increase your booking rates by up to 60%. Make your packages stand out!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 