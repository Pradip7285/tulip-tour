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
                    <i class="fas fa-shield-alt text-red-600 mr-3"></i>
                    Admin Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your marketplace and monitor platform performance
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="<?= app_url('/admin/users') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    <i class="fas fa-users mr-2"></i>
                    Manage Users
                </a>
                <a href="<?= app_url('/admin/providers') ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    <i class="fas fa-store mr-2"></i>
                    Manage Providers
                </a>
                <a href="<?= app_url('/admin/packages') ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    <i class="fas fa-box mr-2"></i>
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
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['total_users']) ?></dd>
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
                                <i class="fas fa-box text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Packages</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['total_packages']) ?></dd>
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
                                <i class="fas fa-suitcase-rolling text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['total_bookings']) ?></dd>
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= formatPrice($stats['total_revenue']) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue & Commission Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-chart-line text-green-600 mr-2"></i>
                        Revenue This Month
                    </h3>
                    <div class="text-2xl font-bold text-gray-900 mb-2">
                        <?= formatPrice($revenue['this_month']) ?>
                    </div>
                    <div class="flex items-center">
                        <?php if ($revenue['growth'] > 0): ?>
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-600 text-sm font-medium">
                                +<?= number_format($revenue['growth'], 1) ?>% from last month
                            </span>
                        <?php elseif ($revenue['growth'] < 0): ?>
                            <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                            <span class="text-red-600 text-sm font-medium">
                                <?= number_format($revenue['growth'], 1) ?>% from last month
                            </span>
                        <?php else: ?>
                            <i class="fas fa-minus text-gray-500 mr-1"></i>
                            <span class="text-gray-600 text-sm font-medium">No change from last month</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-percentage text-indigo-600 mr-2"></i>
                        Commission This Month
                    </h3>
                    <div class="text-2xl font-bold text-gray-900 mb-2">
                        <?= formatPrice($commission['this_month']) ?>
                    </div>
                    <div class="flex items-center">
                        <?php if ($commission['growth'] > 0): ?>
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-600 text-sm font-medium">
                                +<?= number_format($commission['growth'], 1) ?>% growth
                            </span>
                        <?php elseif ($commission['growth'] < 0): ?>
                            <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                            <span class="text-red-600 text-sm font-medium">
                                <?= number_format($commission['growth'], 1) ?>% decline
                            </span>
                        <?php else: ?>
                            <i class="fas fa-minus text-gray-500 mr-1"></i>
                            <span class="text-gray-600 text-sm font-medium">No change</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                        New Users This Month
                    </h3>
                    <div class="text-2xl font-bold text-gray-900">
                        <?= number_format($stats['new_users_month']) ?>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">New registrations</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-calendar-check text-orange-600 mr-2"></i>
                        Total Commission
                    </h3>
                    <div class="text-2xl font-bold text-gray-900">
                        <?= formatPrice($commission['total_earned']) ?>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">All-time earnings</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
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
                            <p class="text-gray-500">Bookings will appear here once customers start booking packages.</p>
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
                                                <?= formatDate($booking['created_at']) ?>
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
                                                    case 'completed':
                                                        echo 'bg-blue-100 text-blue-800';
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
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Users & Pending Payouts -->
            <div class="space-y-6">
                <!-- Recent Users -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-user-plus text-green-600 mr-2"></i>
                            Recent Users
                        </h3>
                        
                        <?php if (empty($recentUsers)): ?>
                            <p class="text-gray-500 text-sm">No new users yet.</p>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach ($recentUsers as $user): ?>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                            </h4>
                                            <p class="text-xs text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                <?php
                                                switch($user['role']) {
                                                    case 'customer':
                                                        echo 'bg-blue-100 text-blue-800';
                                                        break;
                                                    case 'provider':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'admin':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="text-center pt-2">
                                    <a href="<?= app_url('/admin/users') ?>" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                        View all users →
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pending Payouts -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                            Pending Payouts
                        </h3>
                        
                        <?php if (empty($pendingPayouts)): ?>
                            <div class="text-center py-4">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <p class="text-gray-500 text-sm">All payouts are up to date!</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach ($pendingPayouts as $payout): ?>
                                    <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars($payout['provider_name']) ?>
                                                </h4>
                                                <p class="text-xs text-gray-500"><?= htmlspecialchars($payout['provider_email']) ?></p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    Requested: <?= formatDate($payout['requested_at']) ?>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= formatPrice($payout['amount']) ?>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="text-center pt-2">
                                    <a href="<?= app_url('/admin/payouts') ?>" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                                        Review Payouts
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-tools text-gray-600 mr-2"></i>
                        Quick Actions
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="<?= app_url('/admin/users') ?>" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition duration-200">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <h4 class="text-sm font-medium text-blue-900">Manage Users</h4>
                            <p class="text-xs text-blue-700 mt-1">View and manage all platform users</p>
                        </a>
                        
                        <a href="<?= app_url('/admin/providers') ?>" class="block p-4 bg-emerald-50 hover:bg-emerald-100 rounded-lg text-center transition duration-200">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-store text-emerald-600 text-xl"></i>
                            </div>
                            <h4 class="text-sm font-medium text-emerald-900">Manage Providers</h4>
                            <p class="text-xs text-emerald-700 mt-1">Monitor provider performance and status</p>
                        </a>
                        
                        <a href="<?= app_url('/admin/packages') ?>" class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition duration-200">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-box text-green-600 text-xl"></i>
                            </div>
                            <h4 class="text-sm font-medium text-green-900">Manage Packages</h4>
                            <p class="text-xs text-green-700 mt-1">Review and moderate travel packages</p>
                        </a>
                        
                        <a href="<?= app_url('/admin/payouts') ?>" class="block p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg text-center transition duration-200">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-money-check-alt text-yellow-600 text-xl"></i>
                            </div>
                            <h4 class="text-sm font-medium text-yellow-900">Process Payouts</h4>
                            <p class="text-xs text-yellow-700 mt-1">Review and approve payout requests</p>
                        </a>
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