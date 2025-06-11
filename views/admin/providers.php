<!-- Admin Providers View - Fixed Version -->
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
                                <span class="text-sm font-medium text-gray-500">Provider Management</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-store text-green-600 mr-3"></i>
                    Provider Management
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Monitor and manage travel package providers on your platform
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
                                <i class="fas fa-store text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Providers</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['total_providers'] ?? 0) ?></dd>
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Providers</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['active_providers'] ?? 0) ?></dd>
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
                                <i class="fas fa-pause-circle text-red-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Inactive Providers</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= number_format($stats['inactive_providers'] ?? 0) ?></dd>
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
                                <i class="fas fa-crown text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Top Earner</dt>
                                <dd class="text-sm font-medium text-gray-900"><?= htmlspecialchars($stats['top_provider'] ?? 'None') ?></dd>
                                <dd class="text-xs text-gray-500"><?= formatPrice($stats['top_provider_earnings'] ?? 0) ?> this month</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Providers Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-list text-gray-600 mr-2"></i>
                        All Providers
                    </h3>
                    <div class="flex space-x-2">
                        <button onclick="sortProviders('earnings')" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded">
                            Sort by Earnings
                        </button>
                        <button onclick="sortProviders('packages')" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded">
                            Sort by Packages
                        </button>
                        <button onclick="sortProviders('rating')" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded">
                            Sort by Rating
                        </button>
                    </div>
                </div>

                <?php if (empty($providers)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-store text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No providers found</h4>
                        <p class="text-gray-500">Providers will appear here once they register and create packages.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="providersTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Provider
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Performance
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Packages
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Earnings
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Commission
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rating
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
                                <?php foreach ($providers as $provider): ?>
                                    <tr class="provider-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                        <i class="fas fa-store text-green-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= htmlspecialchars($provider['email']) ?>
                                                    </div>
                                                    <?php if (!empty($provider['phone'])): ?>
                                                        <div class="text-xs text-gray-400">
                                                            <?= htmlspecialchars($provider['phone']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-1">
                                                    <?php 
                                                    $performance = 0;
                                                    if (($provider['total_packages'] ?? 0) > 0) {
                                                        $performance = min(100, (($provider['total_bookings'] ?? 0) / $provider['total_packages']) * 20);
                                                    }
                                                    ?>
                                                    <div class="flex items-center">
                                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                                            <div class="bg-<?= $performance > 70 ? 'green' : ($performance > 40 ? 'yellow' : 'red') ?>-600 h-2 rounded-full" 
                                                                 style="width: <?= $performance ?>%"></div>
                                                        </div>
                                                        <span class="ml-2 text-sm text-gray-600"><?= number_format($performance, 1) ?>%</span>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <?= $provider['total_bookings'] ?? 0 ?> bookings
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium"><?= number_format($provider['total_packages'] ?? 0) ?></div>
                                            <div class="text-xs text-gray-500">total packages</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium"><?= formatPrice($provider['total_earned'] ?? 0) ?></div>
                                            <div class="text-xs text-gray-500">total earned</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php $commissionRate = getProviderCommissionRate($provider['id']); ?>
                                            <div class="font-medium text-purple-600"><?= number_format($commissionRate, 1) ?>%</div>
                                            <button onclick="editCommission(<?= $provider['id'] ?>, <?= $commissionRate ?>, '<?= htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) ?>')" 
                                                    class="text-xs text-blue-600 hover:text-blue-800 underline">
                                                Edit Rate
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if (($provider['avg_rating'] ?? 0) > 0): ?>
                                                <div class="flex items-center">
                                                    <div class="flex items-center">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star text-<?= $i <= round($provider['avg_rating']) ? 'yellow' : 'gray' ?>-400 text-sm"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-600">
                                                        <?= number_format($provider['avg_rating'], 1) ?>
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?= $provider['total_reviews'] ?? 0 ?> reviews
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-500 text-sm">No ratings yet</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?= ($provider['status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= ucfirst($provider['status'] ?? 'active') ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button onclick="viewProviderDetails(<?= $provider['id'] ?>)" 
                                                        class="text-blue-600 hover:text-blue-900" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <button onclick="editCommission(<?= $provider['id'] ?>, <?= getProviderCommissionRate($provider['id']) ?>, '<?= htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) ?>')" 
                                                        class="text-purple-600 hover:text-purple-900" title="Edit Commission Rate">
                                                    <i class="fas fa-percentage"></i>
                                                </button>
                                                
                                                <button onclick="viewProviderPackages(<?= $provider['id'] ?>)" 
                                                        class="text-green-600 hover:text-green-900" title="View Packages">
                                                    <i class="fas fa-box"></i>
                                                </button>
                                                
                                                <?php if (($provider['status'] ?? 'active') === 'active'): ?>
                                                    <button onclick="confirmProviderAction('suspend', <?= $provider['id'] ?>, '<?= htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) ?>')"
                                                            class="text-red-600 hover:text-red-900" title="Suspend Provider">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="confirmProviderAction('activate', <?= $provider['id'] ?>, '<?= htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) ?>')"
                                                            class="text-green-600 hover:text-green-900" title="Activate Provider">
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

<!-- Provider Details Modal -->
<div id="providerModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeProviderModal()"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Provider Details
                        </h3>
                        <div class="mt-2" id="modalContent">
                            <!-- Content will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeProviderModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function sortProviders(sortBy) {
    const table = document.getElementById('providersTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Sort logic would be implemented here
    alert(`Sorting by ${sortBy} would be implemented here`);
}

function viewProviderDetails(providerId) {
    // Load provider details in modal
    document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
            <p class="text-gray-500 mt-2">Loading provider details...</p>
        </div>
    `;
    document.getElementById('providerModal').classList.remove('hidden');
    
    // Fetch real provider data
    fetch(`<?= app_url('/admin/providers/details') ?>?id=${providerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const provider = data.provider;
                const formatPrice = (amount) => '₹' + new Intl.NumberFormat('en-IN').format(amount);
                
                let recentPackagesHtml = '';
                if (data.recent_packages && data.recent_packages.length > 0) {
                    data.recent_packages.forEach(pkg => {
                        recentPackagesHtml += `<li>• ${pkg.title} - ${pkg.package_bookings} bookings, ${formatPrice(pkg.package_revenue)} revenue</li>`;
                    });
                } else {
                    recentPackagesHtml = '<li>• No packages created yet</li>';
                }
                
                let recentBookingsHtml = '';
                if (data.recent_bookings && data.recent_bookings.length > 0) {
                    data.recent_bookings.forEach(booking => {
                        recentBookingsHtml += `<li>• ${booking.customer_name} - ${booking.package_title} (${formatPrice(booking.total_amount)})</li>`;
                    });
                } else {
                    recentBookingsHtml = '<li>• No bookings yet</li>';
                }
                
                let recentReviewsHtml = '';
                if (data.recent_reviews && data.recent_reviews.length > 0) {
                    data.recent_reviews.forEach(review => {
                        recentReviewsHtml += `<li>• ${review.customer_name} - ${review.package_title}: ${review.rating}★</li>`;
                    });
                } else {
                    recentReviewsHtml = '<li>• No reviews yet</li>';
                }
                
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p><strong>Provider ID:</strong> ${provider.id}</p>
                                <p><strong>Name:</strong> ${provider.first_name} ${provider.last_name}</p>
                                <p><strong>Email:</strong> ${provider.email}</p>
                                <p><strong>Address:</strong> ${provider.address || 'Not provided'}</p>
                                <p><strong>Company:</strong> ${provider.company_name || 'Not specified'}</p>
                            </div>
                            <div>
                                <p><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${(provider.status === 'active' || !provider.status) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${(provider.status === 'active' || !provider.status) ? 'Active' : 'Suspended'}</span></p>
                                <p><strong>Join Date:</strong> ${provider.join_date_formatted || 'N/A'}</p>
                                <p><strong>Commission Rate:</strong> ${provider.commission_rate || '10'}%</p>
                                <p><strong>Average Rating:</strong> ${provider.avg_rating > 0 ? provider.avg_rating.toFixed(1) + '★ (' + provider.total_reviews + ' reviews)' : 'No ratings yet'}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p><strong>Total Packages:</strong> ${provider.total_packages || 0} (${provider.active_packages || 0} active)</p>
                                <p><strong>Total Bookings:</strong> ${provider.total_bookings || 0}</p>
                            </div>
                            <div>
                                <p><strong>Total Earnings:</strong> ${formatPrice(provider.total_earnings || 0)}</p>
                                <p><strong>Commission Paid:</strong> ${formatPrice(provider.total_commission_paid || 0)}</p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Recent Activity</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Last booking: ${provider.last_booking_formatted || 'No bookings yet'}</li>
                                <li>• Last review: ${provider.last_review_formatted || 'No reviews yet'}</li>
                                <li>• Last package created: ${provider.last_package_formatted || 'No packages yet'}</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Recent Packages</h4>
                            <ul class="text-sm text-gray-600 space-y-1 max-h-24 overflow-y-auto">
                                ${recentPackagesHtml}
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Recent Bookings</h4>
                            <ul class="text-sm text-gray-600 space-y-1 max-h-24 overflow-y-auto">
                                ${recentBookingsHtml}
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Recent Reviews</h4>
                            <ul class="text-sm text-gray-600 space-y-1 max-h-24 overflow-y-auto">
                                ${recentReviewsHtml}
                            </ul>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('modalContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                        <p class="text-red-500 mt-2">Error: ${data.error}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading provider details:', error);
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    <p class="text-red-500 mt-2">Failed to load provider details. Please try again.</p>
                </div>
            `;
        });
}

function viewProviderPackages(providerId) {
    window.location.href = `/tulip/admin/packages?provider=${providerId}`;
}

function closeProviderModal() {
    document.getElementById('providerModal').classList.add('hidden');
}

function confirmProviderAction(action, providerId, providerName) {
    const actionText = action === 'activate' ? 'activate' : 'suspend';
    if (confirm(`Are you sure you want to ${actionText} ${providerName}?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= app_url('/admin/providers/action') ?>';
        
        const providerIdInput = document.createElement('input');
        providerIdInput.type = 'hidden';
        providerIdInput.name = 'provider_id';
        providerIdInput.value = providerId;
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        
        form.appendChild(providerIdInput);
        form.appendChild(actionInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function editCommission(providerId, currentRate, providerName) {
    const newRate = prompt(`Enter new commission rate for ${providerName} (current: ${currentRate}%):`, currentRate);
    
    if (newRate !== null && newRate !== '') {
        const rate = parseFloat(newRate);
        if (isNaN(rate) || rate < 0 || rate > 50) {
            alert('Please enter a valid commission rate between 0% and 50%');
            return;
        }
        
        if (confirm(`Set commission rate to ${rate}% for ${providerName}?`)) {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= app_url('/admin/providers/update-commission') ?>';
            
            const providerIdInput = document.createElement('input');
            providerIdInput.type = 'hidden';
            providerIdInput.name = 'provider_id';
            providerIdInput.value = providerId;
            
            const rateInput = document.createElement('input');
            rateInput.type = 'hidden';
            rateInput.name = 'commission_rate';
            rateInput.value = rate;
            
            form.appendChild(providerIdInput);
            form.appendChild(rateInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Show commission rates in tooltips
document.addEventListener('DOMContentLoaded', function() {
    const providerRows = document.querySelectorAll('.provider-row');
    providerRows.forEach(row => {
        const viewButton = row.querySelector('[onclick*="viewProviderDetails"]');
        if (viewButton) {
            const match = viewButton.getAttribute('onclick').match(/\d+/);
            if (match) {
                const providerId = match[0];
                // Add commission info to provider row if needed
                const earningsCell = row.querySelector('td:nth-child(4)');
                if (earningsCell && !earningsCell.querySelector('.commission-info')) {
                    const commissionInfo = document.createElement('div');
                    commissionInfo.className = 'text-xs text-purple-600 mt-1 commission-info';
                    commissionInfo.innerHTML = `<i class="fas fa-percentage mr-1"></i>Commission tracking`;
                    earningsCell.appendChild(commissionInfo);
                }
            }
        }
    });
});
</script> 