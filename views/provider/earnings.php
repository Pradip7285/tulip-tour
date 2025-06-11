<?php
$pageTitle = 'Earnings & Payouts - TripBazaar';
$pageDescription = 'Track your earnings and payout history';
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
                                <span class="text-sm font-medium text-gray-500">Earnings</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <!-- Main Heading -->
                <div class="flex-1 min-w-0 lg:text-right">
                    <h2 class="text-2xl sm:text-3xl font-bold leading-7 text-gray-900">
                        <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                        Earnings & Payouts
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Track your earnings and payout history
                    </p>
                </div>
            </div>
        </div>

        <!-- Earnings Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Earnings -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-rupee-sign text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Earnings</dt>
                                <dd class="text-2xl font-bold text-gray-900">₹<?= number_format($totalEarnings ?? 0, 2) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">All time</span>
                        <span class="text-gray-500"> from bookings</span>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">This Month</dt>
                                <dd class="text-2xl font-bold text-gray-900">₹<?= number_format($monthlyEarnings ?? 0, 2) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-blue-600 font-medium"><?= date('F Y') ?></span>
                        <span class="text-gray-500"> earnings</span>
                    </div>
                </div>
            </div>

            <!-- Pending Payouts -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Payouts</dt>
                                <dd class="text-2xl font-bold text-gray-900">₹<?= number_format($pendingPayoutRequests ?? 0, 2) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-yellow-600 font-medium">Processing</span>
                        <span class="text-gray-500"> within 7 days</span>
                    </div>
                </div>
            </div>

            <!-- Completed Payouts -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completed Payouts</dt>
                                <dd class="text-2xl font-bold text-gray-900">₹<?= number_format($completedPayouts ?? 0, 2) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-purple-600 font-medium">Paid out</span>
                        <span class="text-gray-500"> to your account</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payout Request Section -->
        <?php 
        // Calculate available amount for payout (completed bookings not yet paid out)
        // $availableForPayout is now calculated in the controller
        $minimumPayout = 500;
        $hasValidBankDetails = !empty($bankDetails ?? false);
        ?>
        
        <?php if ($availableForPayout >= $minimumPayout): ?>
        <div class="mb-6 bg-white shadow-lg rounded-xl">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">
                    <i class="fas fa-hand-holding-usd text-green-500 mr-2"></i>
                    Request Payout
                </h3>
            </div>
            <div class="p-4">
                <?php if ($hasValidBankDetails): ?>
                    <form action="<?= app_url('/provider/request-payout') ?>" method="POST" id="payoutRequestForm">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end">
                            <!-- Available Amount Display -->
                            <div class="lg:col-span-1">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <p class="text-xs text-green-700 font-medium">Available</p>
                                    <p class="text-lg font-bold text-green-600">₹<?= number_format($availableForPayout, 2) ?></p>
                                </div>
                            </div>
                            
                            <!-- Amount Input -->
                            <div class="lg:col-span-1">
                                <label for="payout_amount" class="block text-xs font-medium text-gray-700 mb-1">
                                    Payout Amount
                                </label>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                    <input type="number" 
                                           name="payout_amount" 
                                           id="payout_amount"
                                           min="<?= $minimumPayout ?>" 
                                           max="<?= $availableForPayout ?>"
                                           value="<?= $availableForPayout ?>"
                                           class="pl-6 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                           required>
                                </div>
                            </div>
                            
                            <!-- Notes Input -->
                            <div class="lg:col-span-1">
                                <label for="payout_notes" class="block text-xs font-medium text-gray-700 mb-1">
                                    Notes (Optional)
                                </label>
                                <input type="text" 
                                       name="payout_notes" 
                                       id="payout_notes"
                                       placeholder="Special instructions..."
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="lg:col-span-1">
                                <div class="flex space-x-2">
                                    <button type="button" 
                                            onclick="document.getElementById('payout_amount').value = <?= $availableForPayout ?>"
                                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-xs font-medium">
                                        Max
                                    </button>
                                    <button type="submit" 
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                                        <i class="fas fa-paper-plane mr-1"></i>
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Compact Info -->
                        <div class="mt-3 text-xs text-gray-500 flex flex-wrap gap-4">
                            <span>• Min: ₹<?= number_format($minimumPayout, 2) ?></span>
                            <span>• Processing: 2-5 days</span>
                            <span>• Transfer: NEFT/IMPS</span>
                            <span>• No fees</span>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-yellow-800">Complete Your Banking Details</h4>
                                <p class="text-sm text-yellow-700 mt-1">
                                    You have ₹<?= number_format($availableForPayout, 2) ?> available for payout, but your banking details are incomplete.
                                </p>
                                <div class="mt-3">
                                    <a href="<?= app_url('/provider/profile') ?>" 
                                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-edit mr-2"></i>
                                        Update Banking Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php elseif ($availableForPayout > 0): ?>
        <div class="mb-8 bg-white shadow-lg rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-hand-holding-usd text-gray-400 mr-2"></i>
                    Payout Request
                </h3>
            </div>
            <div class="p-6">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-gray-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-medium text-gray-700">Minimum Payout Amount Not Met</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                You have ₹<?= number_format($availableForPayout, 2) ?> available. 
                                Minimum payout amount is ₹<?= number_format($minimumPayout, 2) ?>.
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Complete more bookings to reach the minimum payout threshold.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Monthly Earnings Chart -->
            <div class="lg:col-span-2 bg-white shadow-lg rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                        Monthly Earnings (Last 12 Months)
                    </h3>
                </div>
                <div class="p-6">
                    <?php if (!empty($earningsData)): ?>
                    <div class="space-y-4">
                        <?php
                        $maxEarning = max(array_column($earningsData, 'earnings'));
                        foreach ($earningsData as $data):
                            $percentage = $maxEarning > 0 ? ($data['earnings'] / $maxEarning) * 100 : 0;
                        ?>
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-900 w-20">
                                <?= date('M Y', strtotime($data['month'] . '-01')) ?>
                            </div>
                            <div class="flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-gray-900 w-24 text-right">
                                ₹<?= number_format($data['earnings'], 2) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No earnings data yet</h3>
                        <p class="text-gray-500">Start getting bookings to see your earnings chart</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Payouts -->
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                            Recent Payouts
                        </h3>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <?php if (!empty($recentPayouts)): ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($recentPayouts as $payout): ?>
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        Booking #<?= htmlspecialchars($payout['booking_id']) ?>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <?= htmlspecialchars($payout['package_title']) ?>
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        <?= date('M j, Y', strtotime($payout['requested_at'])) ?>
                                    </p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <div class="text-sm font-medium text-gray-900">
                                        ₹<?= number_format($payout['amount'], 2) ?>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                        <?php
                                        switch ($payout['status']) {
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'processing':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'completed':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'failed':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst($payout['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <div class="px-6 py-8 text-center">
                        <i class="fas fa-money-bill-wave text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No payouts yet</h3>
                        <p class="text-gray-500 mb-4">Payouts are processed weekly for confirmed bookings</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Payout Information -->
        <div class="mt-8 bg-white shadow-lg rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Payout Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payout Schedule -->
                    <div>
                        <h4 class="text-base font-medium text-gray-900 mb-3">Payout Schedule</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Payouts are processed every Friday
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Minimum payout amount: ₹500
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Processing time: 2-5 business days
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Direct bank transfer (NEFT/IMPS)
                            </li>
                        </ul>
                    </div>

                    <!-- Commission Structure -->
                    <div>
                        <h4 class="text-base font-medium text-gray-900 mb-3">Your Commission Structure</h4>
                        <?php
                        // Get payment gateway fee rate
                        $gatewayFeeRate = getSetting('payment_gateway_fee', 2.5);
                        
                        // Calculate actual percentages
                        $netAfterGateway = 100 - $gatewayFeeRate; // e.g., 97.5%
                        $platformCommissionOnNet = ($netAfterGateway * $commissionRate) / 100; // e.g., 97.5 * 8.5% = 8.29%
                        $actualProviderPercentage = $netAfterGateway - $platformCommissionOnNet; // e.g., 97.5 - 8.29 = 89.21%
                        $totalSystemTake = $gatewayFeeRate + $platformCommissionOnNet; // e.g., 2.5 + 8.29 = 10.79%
                        ?>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center justify-between">
                                <span>Gross Booking Amount:</span>
                                <span class="font-medium">100%</span>
                            </li>
                            <li class="flex items-center justify-between text-orange-600">
                                <span>Payment Gateway Fee:</span>
                                <span class="font-medium">-<?= number_format($gatewayFeeRate, 1) ?>%</span>
                            </li>
                            <li class="flex items-center justify-between text-gray-500 text-xs pl-4">
                                <span>Net Amount After Gateway:</span>
                                <span class="font-medium"><?= number_format($netAfterGateway, 1) ?>%</span>
                            </li>
                            <li class="flex items-center justify-between text-red-600">
                                <span>TripBazaar Commission (<?= number_format($commissionRate, 1) ?>% of net):</span>
                                <span class="font-medium">-<?= number_format($platformCommissionOnNet, 1) ?>%</span>
                            </li>
                            <li class="flex items-center justify-between border-t pt-2 mt-2">
                                <span class="font-medium">Your Net Earnings:</span>
                                <span class="font-medium text-green-600"><?= number_format($actualProviderPercentage, 1) ?>%</span>
                            </li>
                        </ul>
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                Example: On a ₹10,000 booking, you earn ₹<?= number_format(10000 * ($actualProviderPercentage / 100), 0) ?>
                            </p>
                            <p class="text-xs text-blue-600 mt-1">
                                Breakdown: ₹10,000 - ₹<?= number_format(10000 * ($gatewayFeeRate / 100), 0) ?> (gateway) - ₹<?= number_format(10000 * ($platformCommissionOnNet / 100), 0) ?> (commission) = ₹<?= number_format(10000 * ($actualProviderPercentage / 100), 0) ?>
                            </p>
                        </div>
                        <?php if ($commissionRate != 10): ?>
                        <div class="mt-3 p-3 bg-green-50 rounded-lg">
                            <p class="text-xs text-green-700">
                                <i class="fas fa-handshake mr-1"></i>
                                You have a negotiated platform commission rate of <?= number_format($commissionRate, 1) ?>%
                            </p>
                        </div>
                        <?php endif; ?>
                        <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                            <p class="text-xs text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Note:</strong> Payment gateway fees (<?= number_format($gatewayFeeRate, 1) ?>%) cover transaction processing costs and are deducted from all bookings before commission calculation.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Update Banking Details -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h4 class="text-base font-medium text-gray-900">Banking Details</h4>
                            <p class="text-sm text-gray-600">Ensure your banking details are up to date for seamless payouts</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <a href="<?= app_url('/provider/profile') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                                <i class="fas fa-edit mr-2"></i>
                                Update Banking Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-question-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Need Help with Payouts?
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>
                            If you have questions about your earnings or payout schedule, our support team is here to help.
                        </p>
                    </div>
                    <div class="mt-4">
                        <div class="-mx-2 -my-1.5 flex">
                            <button class="bg-blue-50 px-2 py-1.5 rounded-md text-sm font-medium text-blue-800 hover:bg-blue-100">
                                <i class="fas fa-phone mr-1"></i>
                                Contact Support
                            </button>
                            <button class="ml-2 bg-blue-50 px-2 py-1.5 rounded-md text-sm font-medium text-blue-800 hover:bg-blue-100">
                                <i class="fas fa-file-alt mr-1"></i>
                                View FAQ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add interactive features for charts and data visualization
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    const progressBars = document.querySelectorAll('.bg-blue-600');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
    
    // Add tooltip functionality for detailed earnings data
    const earningsBars = document.querySelectorAll('[data-earnings]');
    earningsBars.forEach(bar => {
        bar.addEventListener('mouseenter', function() {
            // Could add tooltip showing detailed breakdown
        });
    });
});

// Format currency display
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 2
    }).format(amount);
}
</script> 