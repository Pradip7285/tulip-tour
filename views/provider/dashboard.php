<?php
$pageTitle = 'Provider Dashboard - TripBazaar';
$pageDescription = 'Manage your travel packages and track your business performance';
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Page header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div class="flex-1 min-w-0 mb-4 sm:mb-0">
                <h2 class="text-2xl sm:text-3xl font-bold leading-7 text-gray-900 truncate">
                    <i class="fas fa-briefcase text-blue-600 mr-3"></i>
                    Provider Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>! Manage your packages and track your business performance
                </p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <a href="<?= app_url('/provider/packages/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 text-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add Package
                </a>
                <a href="<?= app_url('/provider/packages') ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 text-center">
                    <i class="fas fa-cog mr-2"></i>
                    Manage Packages
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- Total Packages -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Packages</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= $stats['total_packages'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium"><?= $stats['active_packages'] ?></span>
                        <span class="text-gray-500"> active packages</span>
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= $stats['total_bookings'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-orange-600 font-medium"><?= $stats['pending_bookings'] ?></span>
                        <span class="text-gray-500"> pending review</span>
                    </div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-rupee-sign text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Earnings</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= formatPrice($stats['total_earnings']) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-purple-600 font-medium"><?= formatPrice($stats['monthly_earnings']) ?></span>
                        <span class="text-gray-500"> this month</span>
                    </div>
                </div>
            </div>

            <!-- Profile Status -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-check text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Profile Status</dt>
                                <dd class="text-lg font-bold text-gray-900">
                                    <?php if ($providerProfile['is_verified']): ?>
                                        <span class="text-green-600">Verified</span>
                                    <?php else: ?>
                                        <span class="text-yellow-600">Pending</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <?php if (!$providerProfile['is_verified']): ?>
                            <a href="<?= app_url('/provider/profile') ?>" class="text-blue-600 hover:text-blue-800 font-medium">Complete profile</a>
                        <?php else: ?>
                            <span class="text-green-600 font-medium">✓ Profile verified</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Recent Bookings -->
            <div class="lg:col-span-2 bg-white shadow-lg rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            Recent Bookings
                        </h3>
                        <a href="<?= app_url('/provider/bookings') ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            View all →
                        </a>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <?php if (!empty($recentBookings)): ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($recentBookings as $booking): ?>
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                <?= htmlspecialchars($booking['package_title']) ?>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-user mr-1"></i>
                                                <?= htmlspecialchars($booking['customer_name']) ?>
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                <i class="fas fa-calendar mr-1"></i>
                                                <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                                            </p>
                                        </div>
                                    </div>
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
                                        switch ($booking['status']) {
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'confirmed':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'cancelled':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            case 'completed':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <div class="px-6 py-8 text-center">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h3>
                        <p class="text-gray-500 mb-4">Start by creating your first travel package</p>
                        <a href="<?= app_url('/provider/packages/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            Create Package
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Popular Packages -->
                <div class="bg-white shadow-lg rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                            Top Packages
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if (!empty($activePackages)): ?>
                        <div class="space-y-4">
                            <?php foreach ($activePackages as $package): ?>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden">
                                    <?php if ($package['featured_image']): ?>
                                    <img src="<?= $package['featured_image'] ?>" alt="<?= htmlspecialchars($package['title']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                    <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-image text-blue-400"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <?= htmlspecialchars($package['title']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-users mr-1"></i>
                                        <?= $package['total_bookings'] ?> bookings
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= formatPrice($package['base_price']) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-box-open text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500 text-sm">No packages created yet</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-lg rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="<?= app_url('/provider/packages/create') ?>" class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Add New Package
                            </a>
                            <a href="<?= app_url('/provider/packages') ?>" class="block w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-cog mr-2"></i>
                                Manage Packages
                            </a>
                            <a href="<?= app_url('/provider/bookings') ?>" class="block w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-list mr-2"></i>
                                View All Bookings
                            </a>
                            <a href="<?= app_url('/provider/profile') ?>" class="block w-full bg-orange-50 hover:bg-orange-100 text-orange-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-user-edit mr-2"></i>
                                Edit Profile
                            </a>
                            <a href="<?= app_url('/provider/earnings') ?>" class="block w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-4 py-3 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-chart-line mr-2"></i>
                                View Earnings
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Completion -->
                <?php if (!$providerProfile['is_verified']): ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Complete Your Profile
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>
                                    Complete your profile to get verified and start receiving more bookings.
                                </p>
                            </div>
                            <div class="mt-4">
                                <div class="-mx-2 -my-1.5 flex">
                                    <a href="<?= app_url('/provider/profile') ?>" class="bg-yellow-50 px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100">
                                        Complete now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 