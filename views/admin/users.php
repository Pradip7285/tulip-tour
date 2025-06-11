

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?= app_url('/admin/dashboard') ?>" 
                               class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Admin Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-gray-500">User Management</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-users text-blue-600 mr-3"></i>
                    User Management
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage all platform users, roles, and access permissions
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Customers</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['customer_count']) ?></dd>
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
                                <i class="fas fa-store text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Providers</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['provider_count']) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-alt text-red-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Admins</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['admin_count']) ?></dd>
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
                                <i class="fas fa-user-plus text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">New This Month</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['new_users_month']) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-list text-gray-600 mr-2"></i>
                    All Users
                </h3>
                
                <!-- Filter tabs -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="#" class="border-blue-500 text-blue-600 border-b-2 py-2 px-1 text-sm font-medium" 
                           onclick="filterUsers('all')">
                            All Users
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 py-2 px-1 text-sm font-medium"
                           onclick="filterUsers('customer')">
                            Customers
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 py-2 px-1 text-sm font-medium"
                           onclick="filterUsers('provider')">
                            Providers
                        </a>
                        <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 py-2 px-1 text-sm font-medium"
                           onclick="filterUsers('admin')">
                            Admins
                        </a>
                    </nav>
                </div>

                <?php if (empty($users)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No users found</h4>
                        <p class="text-gray-500">Users will appear here once they register on the platform.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Activity
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Joined
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($users as $user): ?>
                                    <tr class="user-row" data-role="<?= htmlspecialchars($user['role']) ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-<?= $user['role'] == 'admin' ? 'red' : ($user['role'] == 'provider' ? 'green' : 'blue') ?>-100 flex items-center justify-center">
                                                        <i class="fas fa-<?= $user['role'] == 'admin' ? 'shield-alt' : ($user['role'] == 'provider' ? 'store' : 'user') ?> text-<?= $user['role'] == 'admin' ? 'red' : ($user['role'] == 'provider' ? 'green' : 'blue') ?>-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= htmlspecialchars($user['email']) ?>
                                                    </div>
                                                    <?php if (!empty($user['phone'])): ?>
                                                        <div class="text-xs text-gray-400">
                                                            <?= htmlspecialchars($user['phone']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php
                                                switch($user['role']) {
                                                    case 'admin':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    case 'provider':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'customer':
                                                        echo 'bg-blue-100 text-blue-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php if ($user['role'] == 'customer'): ?>
                                                <div>
                                                    <span class="font-medium"><?= number_format($user['total_bookings']) ?></span> bookings
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?= formatPrice($user['total_spent']) ?> spent
                                                </div>
                                            <?php elseif ($user['role'] == 'provider'): ?>
                                                <div>
                                                    <span class="font-medium"><?= number_format($user['total_packages']) ?></span> packages
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?= formatPrice($user['total_earned']) ?> earned
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-500">System Administrator</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?= $user['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= ucfirst($user['status'] ?? 'active') ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= formatDate($user['created_at']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <?php if ($user['role'] == 'provider'): ?>
                                                    <a href="<?= app_url('/admin/providers?view=' . $user['id']) ?>" 
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if ($user['status'] == 'active'): ?>
                                                    <button onclick="confirmUserAction('deactivate', <?= $user['id'] ?>, '<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>')"
                                                            class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="confirmUserAction('activate', <?= $user['id'] ?>, '<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>')"
                                                            class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function filterUsers(role) {
    const rows = document.querySelectorAll('.user-row');
    const tabs = document.querySelectorAll('nav a');
    
    // Reset tab styles
    tabs.forEach(tab => {
        tab.className = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 py-2 px-1 text-sm font-medium';
    });
    
    // Show/hide rows
    rows.forEach(row => {
        if (role === 'all' || row.dataset.role === role) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Highlight active tab
    event.target.className = 'border-blue-500 text-blue-600 border-b-2 py-2 px-1 text-sm font-medium';
}

function confirmUserAction(action, userId, userName) {
    const actionText = action === 'activate' ? 'activate' : 'deactivate';
    if (confirm(`Are you sure you want to ${actionText} ${userName}?`)) {
        // Implement user action here
        alert(`User ${actionText} functionality would be implemented here`);
    }
}
</script>
