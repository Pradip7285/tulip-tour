

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
                                <span class="text-sm font-medium text-gray-500">Package Management</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-box text-green-600 mr-3"></i>
                    Package Management
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Monitor, approve, and manage all travel packages on your platform
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Packages</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['active_packages']) ?></dd>
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
                                <i class="fas fa-star text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Most Popular</dt>
                                <dd class="text-sm font-medium text-gray-900"><?= htmlspecialchars($stats['most_popular_package']) ?></dd>
                                <dd class="text-xs text-gray-500"><?= $stats['most_popular_bookings'] ?> bookings</dd>
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Average Price</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= formatPrice($stats['avg_package_price']) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Packages Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-list text-gray-600 mr-2"></i>
                        All Packages
                    </h3>
                    <div class="flex space-x-2">
                        <select onchange="filterPackages(this.value)" class="text-sm border-gray-300 rounded-md">
                            <option value="all">All Packages</option>
                            <option value="active">Active Only</option>
                            <option value="inactive">Inactive Only</option>
                        </select>
                        <button onclick="sortPackages('revenue')" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded">
                            Sort by Revenue
                        </button>
                        <button onclick="sortPackages('bookings')" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded">
                            Sort by Bookings
                        </button>
                    </div>
                </div>

                <?php if (empty($packages)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No packages found</h4>
                        <p class="text-gray-500">Packages will appear here once providers create travel packages.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="packagesTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Package
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Provider
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Performance
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rating
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Revenue
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($packages as $package): ?>
                                    <tr class="package-row" data-status="<?= $package['is_active'] ? 'active' : 'inactive' ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    <?php if (!empty($package['image_url'])): ?>
                                                        <img class="h-12 w-12 rounded-lg object-cover" src="<?= htmlspecialchars($package['image_url']) ?>" alt="">
                                                    <?php else: ?>
                                                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($package['title']) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                        <?= htmlspecialchars($package['destination']) ?>
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        <?= $package['duration_days'] ?> days • <?= formatPrice($package['base_price']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($package['provider_name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars($package['provider_email']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <div class="font-medium"><?= number_format($package['total_bookings']) ?></div>
                                                <div class="text-xs text-gray-500">bookings</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($package['avg_rating'] > 0): ?>
                                                <div class="flex items-center">
                                                    <div class="flex items-center">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star text-<?= $i <= round($package['avg_rating']) ? 'yellow' : 'gray' ?>-400 text-sm"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-600">
                                                        <?= number_format($package['avg_rating'], 1) ?>
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?= $package['total_reviews'] ?> reviews
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-500 text-sm">No ratings yet</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= formatPrice($package['total_revenue']) ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                total revenue
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?= $package['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= $package['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="<?= app_url('/package/' . $package['slug']) ?>" target="_blank"
                                                   class="text-blue-600 hover:text-blue-900" title="View Package">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                
                                                <button onclick="viewPackageDetails(<?= $package['id'] ?>)" 
                                                        class="text-green-600 hover:text-green-900" title="Package Details">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                                
                                                <?php if ($package['is_active']): ?>
                                                    <button onclick="confirmPackageAction('deactivate', <?= $package['id'] ?>, '<?= htmlspecialchars($package['title']) ?>')"
                                                            class="text-red-600 hover:text-red-900" title="Deactivate Package">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="confirmPackageAction('activate', <?= $package['id'] ?>, '<?= htmlspecialchars($package['title']) ?>')"
                                                            class="text-green-600 hover:text-green-900" title="Activate Package">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <button onclick="confirmPackageAction('delete', <?= $package['id'] ?>, '<?= htmlspecialchars($package['title']) ?>')"
                                                        class="text-red-600 hover:text-red-900" title="Delete Package">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

<!-- Package Details Modal -->
<div id="packageModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closePackageModal()"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Package Details
                        </h3>
                        <div class="mt-2" id="packageModalContent">
                            <!-- Content will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closePackageModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function filterPackages(status) {
    const rows = document.querySelectorAll('.package-row');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function sortPackages(sortBy) {
    alert(`Sorting by ${sortBy} would be implemented here`);
}

function viewPackageDetails(packageId) {
    document.getElementById('packageModalContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
            <p class="text-gray-500 mt-2">Loading package details...</p>
        </div>
    `;
    document.getElementById('packageModal').classList.remove('hidden');
    
    // Fetch real package data
    fetch(`<?= app_url('/admin/packages/details') ?>?id=${packageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pkg = data.package;
                const formatPrice = (amount) => '₹' + new Intl.NumberFormat('en-IN').format(amount);
                
                let recentBookingsHtml = '';
                if (data.recent_bookings.length > 0) {
                    data.recent_bookings.forEach(booking => {
                        recentBookingsHtml += `<li>• ${booking.customer_name} - ${formatPrice(booking.total_amount)} (${booking.payment_status})</li>`;
                    });
                } else {
                    recentBookingsHtml = '<li>• No bookings yet</li>';
                }
                
                let recentReviewsHtml = '';
                if (data.recent_reviews.length > 0) {
                    data.recent_reviews.forEach(review => {
                        recentReviewsHtml += `<li>• ${review.customer_name} - ${review.rating}★: ${review.comment.substring(0, 50)}${review.comment.length > 50 ? '...' : ''}</li>`;
                    });
                } else {
                    recentReviewsHtml = '<li>• No reviews yet</li>';
                }
                
                document.getElementById('packageModalContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p><strong>Package ID:</strong> ${pkg.id}</p>
                                <p><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${pkg.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${pkg.is_active ? 'Active' : 'Inactive'}</span></p>
                                <p><strong>Created:</strong> ${pkg.created_date_formatted}</p>
                                <p><strong>Provider:</strong> ${pkg.provider_name}</p>
                            </div>
                            <div>
                                <p><strong>Total Bookings:</strong> ${pkg.total_bookings}</p>
                                <p><strong>Total Revenue:</strong> ${formatPrice(pkg.total_revenue)}</p>
                                <p><strong>Commission Earned:</strong> ${formatPrice(pkg.commission_earned)}</p>
                                <p><strong>Average Rating:</strong> ${pkg.avg_rating > 0 ? pkg.avg_rating.toFixed(1) + '★ (' + pkg.total_reviews + ' reviews)' : 'No ratings yet'}</p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Recent Activity</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Last booking: ${pkg.last_booking_formatted}</li>
                                <li>• Last review: ${pkg.last_review_formatted}</li>
                                <li>• Price last updated: ${pkg.last_update_formatted}</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Recent Bookings</h4>
                            <ul class="text-sm text-gray-600 space-y-1 max-h-32 overflow-y-auto">
                                ${recentBookingsHtml}
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Recent Reviews</h4>
                            <ul class="text-sm text-gray-600 space-y-1 max-h-32 overflow-y-auto">
                                ${recentReviewsHtml}
                            </ul>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('packageModalContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                        <p class="text-red-500 mt-2">Error: ${data.error}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading package details:', error);
            document.getElementById('packageModalContent').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    <p class="text-red-500 mt-2">Failed to load package details. Please try again.</p>
                </div>
            `;
        });
}

function closePackageModal() {
    document.getElementById('packageModal').classList.add('hidden');
}

function confirmPackageAction(action, packageId, packageTitle) {
    let actionText = action;
    let confirmText = `Are you sure you want to ${actionText} "${packageTitle}"?`;
    
    if (action === 'delete') {
        confirmText = `Are you sure you want to delete "${packageTitle}"? This action cannot be undone.`;
    }
    
    if (confirm(confirmText)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= app_url('/admin/packages/action') ?>';
        
        const packageIdInput = document.createElement('input');
        packageIdInput.type = 'hidden';
        packageIdInput.name = 'package_id';
        packageIdInput.value = packageId;
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        
        form.appendChild(packageIdInput);
        form.appendChild(actionInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

