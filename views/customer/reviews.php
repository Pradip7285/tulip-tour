<?php
// Capture the page content
ob_start();
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <i class="fas fa-star text-yellow-500 mr-3"></i>
                My Reviews
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Share your travel experiences and read your past reviews
            </p>
        </div>

        <!-- Flash Messages -->
        <?php displayFlashMessage(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Reviews Written -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
                            <i class="fas fa-comment-alt text-blue-600 mr-2"></i>
                            Reviews I've Written
                        </h3>
                        
                        <?php if (empty($reviews)): ?>
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-star-half-alt text-gray-400 text-3xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h4>
                                <p class="text-gray-500 mb-4">Complete a trip to share your experience with other travelers!</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-6">
                                <?php foreach ($reviews as $review): ?>
                                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-200">
                                        <!-- Review Header -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                                    <?= htmlspecialchars($review['package_title']) ?>
                                                </h4>
                                                <p class="text-sm text-gray-500 mb-2">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    <?= htmlspecialchars($review['destination']) ?>
                                                </p>
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex items-center">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="text-sm text-gray-600"><?= $review['rating'] ?>/5</span>
                                                    <span class="text-xs text-gray-400">•</span>
                                                    <span class="text-xs text-gray-400"><?= formatDate($review['created_at']) ?></span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <?php if ($review['is_approved']): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Published
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Pending Review
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Review Content -->
                                        <?php if (!empty($review['title'])): ?>
                                            <h5 class="font-medium text-gray-900 mb-2"><?= htmlspecialchars($review['title']) ?></h5>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($review['review_text'])): ?>
                                            <p class="text-gray-700 text-sm leading-relaxed mb-4"><?= htmlspecialchars($review['review_text']) ?></p>
                                        <?php endif; ?>
                                        
                                        <!-- Review Actions -->
                                        <div class="flex items-center space-x-3">
                                            <a href="<?= app_url('/package/' . $review['package_slug']) ?>" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                                View Package →
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Pending Reviews Sidebar -->
            <div>
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-edit text-orange-500 mr-2"></i>
                            Write Reviews
                        </h3>
                        
                        <?php if (empty($pendingReviews)): ?>
                            <div class="text-center py-6">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check-circle text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-500">No completed trips to review</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($pendingReviews as $booking): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                                        <h4 class="font-medium text-gray-900 text-sm mb-1">
                                            <?= htmlspecialchars($booking['package_title']) ?>
                                        </h4>
                                        <p class="text-xs text-gray-500 mb-3">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <?= htmlspecialchars($booking['destination']) ?>
                                        </p>
                                        <button 
                                            class="w-full bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-3 py-2 rounded-md text-sm font-medium transition duration-200"
                                            onclick="openReviewModal('<?= $booking['id'] ?>', '<?= htmlspecialchars($booking['package_title']) ?>', '<?= htmlspecialchars($booking['destination']) ?>')"
                                        >
                                            <i class="fas fa-star mr-1"></i>
                                            Write Review
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Review Tips -->
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 shadow rounded-lg text-white mt-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium mb-3">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Review Tips
                        </h3>
                        <ul class="text-sm text-yellow-100 space-y-2">
                            <li>• Be honest and constructive</li>
                            <li>• Mention specific highlights</li>
                            <li>• Help future travelers decide</li>
                            <li>• Include practical tips</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    Write Review
                </h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <form id="reviewForm" method="POST" action="<?= app_url('/customer/submit-review') ?>">
                <?= generateCSRFToken() ?>
                <input type="hidden" id="booking_id" name="booking_id">
                
                <div class="space-y-4">
                    <!-- Package Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 id="modal_package_title" class="font-medium text-gray-900"></h4>
                        <p id="modal_destination" class="text-sm text-gray-500"></p>
                    </div>
                    
                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Overall Rating *
                        </label>
                        <div class="flex items-center space-x-1" id="star-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <button type="button" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none" data-rating="<?= $i ?>">
                                    <i class="fas fa-star"></i>
                                </button>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="rating_input" required>
                    </div>
                    
                    <!-- Review Title -->
                    <div>
                        <label for="review_title" class="block text-sm font-medium text-gray-700">
                            Review Title
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="review_title" 
                            placeholder="Summarize your experience..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        >
                    </div>
                    
                    <!-- Review Text -->
                    <div>
                        <label for="review_text" class="block text-sm font-medium text-gray-700">
                            Your Review *
                        </label>
                        <textarea 
                            name="review_text" 
                            id="review_text" 
                            rows="4"
                            required
                            placeholder="Share your experience, what you liked, and any tips for future travelers..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        ></textarea>
                    </div>
                </div>
                
                <!-- Modal Actions -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeReviewModal()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReviewModal(bookingId, packageTitle, destination) {
    document.getElementById('booking_id').value = bookingId;
    document.getElementById('modal_package_title').textContent = packageTitle;
    document.getElementById('modal_destination').textContent = destination;
    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    document.getElementById('reviewForm').reset();
    // Reset star rating
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.classList.remove('text-yellow-400');
        btn.classList.add('text-gray-300');
    });
    document.getElementById('rating_input').value = '';
}

// Star rating functionality
document.querySelectorAll('.star-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.getElementById('rating_input').value = rating;
        
        // Update visual state
        document.querySelectorAll('.star-btn').forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    });
});
</script>

<?php
// Get the content and pass it to the layout
$content = ob_get_clean();
include_once 'includes/layout.php';
?> 