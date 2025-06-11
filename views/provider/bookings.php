<?php
$pageTitle = 'Bookings Management - TripBazaar';
$pageDescription = 'Manage your package bookings';
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Page header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
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
                                <span class="text-sm font-medium text-gray-500">Bookings</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <!-- Main Heading -->
                <div class="flex-1 min-w-0 lg:text-right">
                    <h2 class="text-2xl sm:text-3xl font-bold leading-7 text-gray-900">
                        <i class="fas fa-calendar-check text-blue-600 mr-3"></i>
                        Bookings Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        View and manage all bookings for your packages
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4">
                <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                               placeholder="Booking ID, customer name..."
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">All Status</option>
                            <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                        <input type="date" name="date_from" id="date_from" 
                               value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="date" name="date_to" id="date_to" 
                               value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
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

        <!-- Bookings Table -->
        <?php if (!empty($bookings)): ?>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="min-w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Booking Details
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Package
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($bookings as $booking): ?>
                        <tr class="hover:bg-gray-50">
                            <!-- Booking Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-sm font-medium text-gray-900">
                                        #<?= htmlspecialchars($booking['booking_id']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                                    </div>
                                    <?php if (!empty($booking['travel_date'])): ?>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-plane mr-1"></i>
                                        Travel: <?= date('M j, Y', strtotime($booking['travel_date'])) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($booking['customer_name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= htmlspecialchars($booking['customer_email']) ?>
                                        </div>
                                        <?php if (!empty($booking['customer_phone'])): ?>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-phone mr-1"></i>
                                            <?= htmlspecialchars($booking['customer_phone']) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Package -->
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-sm font-medium text-gray-900 mb-1">
                                        <?= htmlspecialchars($booking['package_title']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-1">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <?= htmlspecialchars($booking['destination']) ?>
                                    </div>
                                    <?php if (!empty($booking['adults']) || !empty($booking['children'])): ?>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-users mr-1"></i>
                                        <?= $booking['adults'] ?? 0 ?> Adults
                                        <?php if (!empty($booking['children'])): ?>
                                            , <?= $booking['children'] ?> Children
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= formatPrice($booking['total_amount']) ?>
                                    </div>
                                    <?php if (!empty($booking['provider_amount'])): ?>
                                    <div class="text-sm text-green-600">
                                        Your share: <?= formatPrice($booking['provider_amount']) ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($booking['payment_status'])): ?>
                                    <div class="text-xs text-gray-500">
                                        Payment: <?= ucfirst($booking['payment_status']) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <?php if ($booking['status'] === 'pending'): ?>
                                    <button onclick="updateBookingStatus(<?= $booking['id'] ?>, 'confirmed')" 
                                            class="text-green-600 hover:text-green-900 transition-colors">
                                        <i class="fas fa-check" title="Confirm"></i>
                                    </button>
                                    <button onclick="updateBookingStatus(<?= $booking['id'] ?>, 'cancelled')" 
                                            class="text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-times" title="Cancel"></i>
                                    </button>
                                    <?php elseif ($booking['status'] === 'confirmed'): ?>
                                    <button onclick="updateBookingStatus(<?= $booking['id'] ?>, 'completed')" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-flag-checkered" title="Mark Complete"></i>
                                    </button>
                                    <?php endif; ?>
                                    
                                    <button onclick="viewBookingDetails(<?= $booking['id'] ?>)" 
                                            class="text-gray-600 hover:text-gray-900 transition-colors">
                                        <i class="fas fa-eye" title="View Details"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['prev_page']])) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <?php endif; ?>
                    <?php if ($pagination['has_next']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])) ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium"><?= $pagination['offset'] + 1 ?></span> to 
                            <span class="font-medium"><?= min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items']) ?></span> of 
                            <span class="font-medium"><?= $pagination['total_items'] ?></span> results
                        </p>
                    </div>
                    <div>
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
            </div>
            <?php endif; ?>
        </div>

        <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings found</h3>
            <p class="text-gray-500 mb-6">
                <?php if (!empty($_GET['search']) || !empty($_GET['status']) || !empty($_GET['date_from']) || !empty($_GET['date_to'])): ?>
                    Try adjusting your filters to see more results.
                <?php else: ?>
                    You don't have any bookings yet. Bookings will appear here once customers book your packages.
                <?php endif; ?>
            </p>
            <?php if (empty($_GET['search']) && empty($_GET['status']) && empty($_GET['date_from']) && empty($_GET['date_to'])): ?>
            <a href="<?= app_url('/provider/packages') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                View Your Packages
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModal()"></div>
        
        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Booking Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function updateBookingStatus(bookingId, status) {
    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
    
    if (confirm(`Are you sure you want to mark this booking as ${statusText}?`)) {
        fetch(`<?= app_url('/provider/bookings/update-status/') ?>${bookingId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update booking status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the booking status');
        });
    }
}

function viewBookingDetails(bookingId) {
    // This could be expanded to show more detailed booking information
    alert('Booking details view - Feature coming soon!');
}

function closeModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

// Auto-refresh every 30 seconds for new bookings
setInterval(() => {
    if (!document.hidden) {
        location.reload();
    }
}, 30000);
</script> 