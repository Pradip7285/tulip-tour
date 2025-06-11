<?php
$pageTitle = 'Manage Packages - TripBazaar';
$pageDescription = 'Manage all your travel packages';
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Page header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between lg:space-x-6">
                <!-- Breadcrumb Navigation -->
                <nav class="flex mb-4 lg:mb-0" aria-label="Breadcrumb">
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
                                <span class="text-sm font-medium text-gray-500">Packages</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <!-- Main Heading -->
                <div class="flex-1 min-w-0 mb-4 lg:mb-0 lg:text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold leading-7 text-gray-900">
                        <i class="fas fa-box text-blue-600 mr-3"></i>
                        Manage Packages
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Create and manage your travel packages
                    </p>
                </div>
                
                <!-- Action Button -->
                <div class="flex-shrink-0">
                    <a href="<?= app_url('/provider/packages/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add Package
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4">
                <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                               placeholder="Search packages..."
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">All Status</option>
                            <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                        <select name="sort" id="sort" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="latest" <?= ($_GET['sort'] ?? 'latest') === 'latest' ? 'selected' : '' ?>>Latest</option>
                            <option value="title" <?= ($_GET['sort'] ?? '') === 'title' ? 'selected' : '' ?>>Title</option>
                            <option value="bookings" <?= ($_GET['sort'] ?? '') === 'bookings' ? 'selected' : '' ?>>Most Bookings</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Packages Grid -->
        <?php if (!empty($packages)): ?>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($packages as $package): ?>
            <div class="bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-shadow duration-300">
                <!-- Package Image -->
                <div class="h-48 bg-gray-200">
                    <?php if ($package['featured_image']): ?>
                    <img class="w-full h-full object-cover" src="<?= htmlspecialchars($package['featured_image']) ?>" alt="<?= htmlspecialchars($package['title']) ?>">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-blue-100">
                        <i class="fas fa-image text-blue-400 text-3xl"></i>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Package Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $package['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $package['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                        <div class="flex space-x-1">
                            <a href="<?= app_url('/provider/packages/edit/' . $package['id']) ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deletePackage(<?= $package['id'] ?>)" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2"><?= htmlspecialchars($package['title']) ?></h3>
                    <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($package['destination']) ?></p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                        <span><i class="fas fa-calendar mr-1"></i><?= $package['duration_days'] ?> days</span>
                        <span><i class="fas fa-users mr-1"></i><?= $package['total_bookings'] ?? 0 ?> bookings</span>
                    </div>
                    
                    <div class="border-t pt-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-gray-900"><?= formatPrice($package['base_price']) ?></span>
                                <span class="text-sm text-gray-500">/person</span>
                            </div>
                            <a href="<?= app_url('/package/' . $package['slug']) ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <div class="mt-8 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <?= $pagination['offset'] + 1 ?> to <?= min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items']) ?> of <?= $pagination['total_items'] ?> results
            </div>
            <div class="flex-1 flex justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['prev_page']])) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium <?= $i === $pagination['current_page'] ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['has_next']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No packages yet</h3>
            <p class="text-gray-500 mb-6">Get started by creating your first travel package</p>
            <a href="<?= app_url('/provider/packages/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                Create Your First Package
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function deletePackage(packageId) {
    if (confirm('Are you sure you want to delete this package? This action cannot be undone.')) {
        fetch(`<?= app_url('/provider/packages/delete/') ?>${packageId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete package');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the package');
        });
    }
}
</script> 