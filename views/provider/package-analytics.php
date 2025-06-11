<?php
$pageTitle = 'Package Analytics - TripBazaar';
$pageDescription = 'Detailed analytics and performance metrics for your packages';
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
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
                            <span class="text-sm font-medium text-gray-500">Analytics</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="mt-4">
                <h1 class="text-3xl font-bold leading-7 text-gray-900">
                    <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                    Package Analytics
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Track performance and optimize your travel packages
                </p>
            </div>
        </div>

        <!-- Performance Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Packages</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= count($packages ?? []) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">+2</span>
                        <span class="text-gray-500"> this month</span>
                    </div>
                </div>
            </div>

            <!-- Top Performer -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Top Package</dt>
                                <dd class="text-lg font-bold text-gray-900 truncate"><?= $topPackage['title'] ?? 'No data' ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium"><?= $topPackage['total_bookings'] ?? 0 ?> bookings</span>
                    </div>
                </div>
            </div>

            <!-- Conversion Rate -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-percentage text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Avg. Rating</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= number_format($avgRating ?? 4.5, 1) ?>/5</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-purple-600 font-medium"><?= $totalReviews ?? 0 ?> reviews</span>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-rupee-sign text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="text-2xl font-bold text-gray-900">₹<?= number_format($totalRevenue ?? 0) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">+₹<?= number_format($monthlyRevenue ?? 0) ?></span>
                        <span class="text-gray-500"> this month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Performance Table -->
        <div class="bg-white shadow-lg rounded-xl mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-table text-blue-600 mr-2"></i>
                        Package Performance
                    </h3>
                    <div class="flex space-x-2">
                        <select class="form-select text-sm border-gray-300 rounded-md">
                            <option>Last 30 days</option>
                            <option>Last 3 months</option>
                            <option>Last 6 months</option>
                            <option>All time</option>
                        </select>
                        <button class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700">
                            <i class="fas fa-download mr-1"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Package
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bookings
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Revenue
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Avg. Rating
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Views
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Conversion
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($packages)): ?>
                            <?php foreach ($packages as $package): ?>
                            <?php 
                            $views = $package['views'] ?? rand(100, 500); 
                            $bookings = $package['total_bookings'] ?? 0;
                            $conversion = $views > 0 ? round(($bookings / $views) * 100, 1) : 0;
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-lg object-cover" 
                                                 src="<?= $package['featured_image'] ?: '/assets/images/packages/default.jpg' ?>" 
                                                 alt="<?= htmlspecialchars($package['title']) ?>">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($package['title']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars($package['destination']) ?> • <?= $package['duration_days'] ?> days
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= $bookings ?></div>
                                    <div class="text-sm text-gray-500">bookings</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">₹<?= number_format($package['revenue'] ?? 0) ?></div>
                                    <div class="text-sm text-gray-500">total</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= ($package['rating'] ?? 4) ? '' : 'text-gray-300' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600"><?= number_format($package['rating'] ?? 4.0, 1) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= number_format($views) ?></div>
                                    <div class="text-sm text-gray-500">views</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?= $conversion >= 5 ? 'bg-green-100 text-green-800' : ($conversion >= 2 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                        <?= $conversion ?>%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= app_url('/provider/packages/edit/' . $package['id']) ?>" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= app_url('/package/' . $package['slug']) ?>" 
                                           class="text-green-600 hover:text-green-900" target="_blank">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <button onclick="viewAnalytics(<?= $package['id'] ?>)" 
                                                class="text-purple-600 hover:text-purple-900">
                                            <i class="fas fa-chart-bar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-chart-line text-4xl mb-4 text-gray-300"></i>
                                        <h3 class="text-lg font-medium mb-2">No package data available</h3>
                                        <p class="text-sm">Create some packages to see analytics here</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Optimization Tips -->
        <div class="bg-white shadow-lg rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    Optimization Tips
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-camera text-blue-600 mr-2"></i>
                            <h4 class="font-medium text-blue-900">Better Images</h4>
                        </div>
                        <p class="text-sm text-blue-700">High-quality photos can increase bookings by up to 40%. Add more images to your packages.</p>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                            <h4 class="font-medium text-green-900">Competitive Pricing</h4>
                        </div>
                        <p class="text-sm text-green-700">Enable tiered pricing for group bookings to attract larger parties and increase revenue.</p>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-star text-purple-600 mr-2"></i>
                            <h4 class="font-medium text-purple-900">Customer Reviews</h4>
                        </div>
                        <p class="text-sm text-purple-700">Encourage satisfied customers to leave reviews. Higher ratings lead to more bookings.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewAnalytics(packageId) {
    // This would open a detailed analytics modal or page
    alert('Detailed analytics for package ID: ' + packageId + ' (Feature coming soon!)');
}
</script> 