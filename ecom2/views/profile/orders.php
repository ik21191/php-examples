<!-- views/profile/orders.php -->
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Purchase History</h1>
        <p class="text-sm text-gray-500 mt-1">Review, monitor, and audit your completed transaction statements.</p>
    </div>

    <?php if (empty($userOrders)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm max-w-md mx-auto">
            <div class="text-4xl mb-4">🛒</div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">No Orders Logged Yet</h3>
            <p class="text-sm text-gray-400 mb-6">Your transaction index dashboard registry is currently clean.</p>
            <a href="index.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition">
                Explore Products Catalog
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($userOrders as $order): ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition hover:shadow-md">
                    <!-- Master Card Top Banner Summary Block -->
                    <div class="bg-gray-50/70 px-6 py-4 border-b border-gray-100 flex flex-wrap gap-4 justify-between items-center text-xs text-gray-500">
                        <div class="flex gap-6">
                            <div>
                                <p class="uppercase font-bold tracking-wider text-gray-400 mb-0.5">Date Placed</p>
                                <p class="font-semibold text-gray-800 text-sm"><?= date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
                            </div>
                            <div>
                                <p class="uppercase font-bold tracking-wider text-gray-400 mb-0.5">Total Paid</p>
                                <p class="font-bold text-blue-600 text-sm">₹<?= number_format($order['total_amount'], 2); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="uppercase font-bold tracking-wider text-gray-400 mb-0.5">Receipt Reference</p>
                            <span class="font-mono bg-white border border-gray-200 text-gray-700 px-2 py-0.5 rounded text-[11px] font-semibold">
                                <?= htmlspecialchars($order['mock_payment_id']); ?>
                            </span>

                            <a href="index.php?controller=invoice&action=download&id=<?= $order['id']; ?>"
                                class="inline-flex items-center text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-100 hover:bg-blue-100 px-2 py-1 rounded transition tracking-wide shadow-xs">
                                📄 Download GST Invoice (PDF)
                            </a>
                        </div>
                    </div>

                    <!-- Relational Sub-items Matrix Display Layer -->
                    <div class="p-6 divide-y divide-gray-50">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 gap-4">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center font-bold text-gray-400 text-xs shadow-inner">
                                        📦
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-sm text-gray-800 line-clamp-1"><?= htmlspecialchars($item['product_name']); ?></h4>
                                        <p class="text-xs text-gray-400 mt-0.5">Quantity Order Rate: <span class="font-bold text-gray-600"><?= $item['quantity']; ?></span></p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm font-bold text-gray-800">₹<?= number_format($item['price'] * $item['quantity'], 2); ?></p>
                                    <p class="text-[11px] text-gray-400">₹<?= number_format($item['price'], 2); ?> each</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Operational Bottom Action Bar Layout Status Bar -->
                    <div class="bg-gray-50/30 px-6 py-3 border-t border-gray-50 flex justify-between items-center text-xs">
                        <div class="flex items-center space-x-2">
                            <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-gray-500 font-medium">Gateway Mode: <span class="uppercase font-bold text-gray-700"><?= htmlspecialchars($order['payment_method']); ?></span></span>
                        </div>
                        <span class="text-green-700 bg-green-50 font-semibold px-2 py-0.5 rounded border border-green-100 uppercase text-[10px] tracking-wide">
                            Processed & Confirmed
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>