<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="min-w-0 flex-1">
                <h1 class="text-3xl font-bold leading-tight text-gray-900">Payout Management</h1>
                <p class="mt-1 text-sm text-gray-500">Review and process provider payout requests</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?= app_url('/admin/dashboard') ?>" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
                                <dd class="text-2xl font-bold text-gray-900"><?= $stats['pending_count'] ?? 0 ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-yellow-600 font-medium">₹<?= number_format($stats['pending_amount'] ?? 0, 2) ?></span>
                        <span class="text-gray-500"> total pending</span>
                    </div>
                </div>
            </div>

            <!-- Completed Payouts -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completed Payouts</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= $stats['completed_count'] ?? 0 ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">₹<?= number_format($stats['completed_amount'] ?? 0, 2) ?></span>
                        <span class="text-gray-500"> total paid</span>
                    </div>
                </div>
            </div>

            <!-- Total Processing -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Payouts</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= ($stats['pending_count'] ?? 0) + ($stats['completed_count'] ?? 0) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <span class="text-blue-600 font-medium">₹<?= number_format(($stats['pending_amount'] ?? 0) + ($stats['completed_amount'] ?? 0), 2) ?></span>
                        <span class="text-gray-500"> total value</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payouts Table -->
        <div class="bg-white shadow-lg rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-list text-gray-500 mr-2"></i>
                    All Payout Requests
                </h2>
            </div>
            <div class="overflow-hidden">
                <?php if (!empty($payouts)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Provider
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Requested
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($payouts as $payout): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    <?= strtoupper(substr($payout['provider_name'], 0, 1)) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($payout['provider_name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars($payout['provider_email']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-semibold text-gray-900">
                                        ₹<?= number_format($payout['amount'], 2) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch ($payout['status']) {
                                        case 'pending':
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'Pending';
                                            break;
                                        case 'processing':
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                            $statusText = 'Processing';
                                            break;
                                        case 'paid':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Completed';
                                            break;
                                        case 'failed':
                                            $statusClass = 'bg-red-100 text-red-800';
                                            $statusText = 'Rejected';
                                            break;
                                        default:
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            $statusText = ucfirst($payout['status']);
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M j, Y', strtotime($payout['requested_at'])) ?><br>
                                    <span class="text-xs text-gray-400"><?= date('g:i A', strtotime($payout['requested_at'])) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        <?= !empty($payout['notes']) ? htmlspecialchars($payout['notes']) : '<span class="text-gray-400 italic">No notes</span>' ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($payout['status'] === 'pending'): ?>
                                    <div class="flex space-x-2">
                                        <a href="<?= app_url('/admin/payouts/approve/' . $payout['id']) ?>" 
                                           onclick="return confirm('Are you sure you want to approve this payout?')"
                                           class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </a>
                                        <a href="<?= app_url('/admin/payouts/reject/' . $payout['id']) ?>" 
                                           onclick="return confirm('Are you sure you want to reject this payout?')"
                                           class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium">
                                            <i class="fas fa-times mr-1"></i>Reject
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <span class="text-gray-400 text-xs">
                                        <?= ucfirst($payout['status']) ?>
                                        <?php if (!empty($payout['processed_at'])): ?>
                                        <br><span class="text-xs"><?= date('M j, Y', strtotime($payout['processed_at'])) ?></span>
                                        <?php endif; ?>
                                    </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-money-bill-wave text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Payout Requests</h3>
                    <p class="text-gray-500">There are no payout requests to display.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>