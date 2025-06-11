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
                    <i class="fas fa-suitcase-rolling text-blue-600 mr-3"></i>
                    My Bookings
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage and track all your travel bookings
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?= app_url('/packages') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Book New Trip
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php displayFlashMessage(); ?>

        <!-- Bookings List -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <?php if (empty($bookings)): ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-suitcase-rolling text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h3>
                        <p class="text-gray-500 mb-6">Start your journey by booking your first travel package!</p>
                        <a href="<?= app_url('/packages') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium transition duration-200">
                            Browse Packages
                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($bookings as $booking): ?>
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                <?= htmlspecialchars($booking['package_title']) ?>
                                            </h3>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
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
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                            <div>
                                                <span class="text-sm text-gray-500">Destination:</span>
                                                <p class="font-medium"><?= htmlspecialchars($booking['destination']) ?></p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500">Duration:</span>
                                                <p class="font-medium"><?= $booking['duration_days'] ?> days</p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500">Booking ID:</span>
                                                <p class="font-medium"><?= htmlspecialchars($booking['booking_id']) ?></p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500">Travel Date:</span>
                                                <p class="font-medium"><?= $booking['travel_date'] ? formatDate($booking['travel_date']) : 'Not set' ?></p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500">Guests:</span>
                                                <p class="font-medium"><?= $booking['adults_count'] ?> adults<?= $booking['children_count'] > 0 ? ', ' . $booking['children_count'] . ' children' : '' ?></p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500">Total Amount:</span>
                                                <p class="font-bold text-lg text-green-600"><?= formatPrice($booking['total_amount']) ?></p>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($booking['special_requirements'])): ?>
                                            <div class="mb-4">
                                                <span class="text-sm text-gray-500">Special Requirements:</span>
                                                <p class="text-sm bg-gray-50 p-3 rounded-md mt-1"><?= htmlspecialchars($booking['special_requirements']) ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="text-xs text-gray-400">
                                            Booked on <?= formatDateTime($booking['created_at']) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col space-y-2 ml-6">
                                        <a href="<?= app_url('/package/' . $booking['slug']) ?>" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-md text-sm font-medium transition duration-200 text-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            View Package
                                        </a>
                                        
                                        <?php if ($booking['status'] === 'completed'): ?>
                                            <a href="<?= app_url('/customer/reviews') ?>" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-4 py-2 rounded-md text-sm font-medium transition duration-200 text-center">
                                                <i class="fas fa-star mr-1"></i>
                                                Write Review
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($booking['status'] === 'pending'): ?>
                                            <button class="bg-red-50 hover:bg-red-100 text-red-700 px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                                                <i class="fas fa-times mr-1"></i>
                                                Cancel
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content and pass it to the layout
$content = ob_get_clean();
include_once 'includes/layout.php';
?> 