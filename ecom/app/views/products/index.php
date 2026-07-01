<?php

// Securely instantiate wishlist dependencies for authenticated user profiles
require_once __DIR__ . '/../../models/Wishlist.php';
$userId = $_SESSION['user_id'] ?? null;
$favoriteIds = Wishlist::getUserWishlistIds($userId);
?>

<div class="space-y-6">
    <!-- Header & Dynamic Search Bar Asset Container Block -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-100 pb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Our Products</h1>
            <p class="text-sm text-gray-500 mt-1">Explore our curation of premium, high-utility electronic accessories.</p>
        </div>

        <!-- Interactive Storefront Search Bar Component -->
        <div class="relative w-full md:w-80 shadow-sm rounded-2xl">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 text-sm pointer-events-none">🔍</span>
            <input type="text" id="storefront-search-bar" placeholder="Search catalog items by name..."
                class="w-full text-sm pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400 font-medium transition">
        </div>
    </div>

    <!-- Responsive CSS Grid Layout Container Card Blocks Matrix -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="product-grid-container">
        <?php foreach ($products as $product): ?>
            <!-- Injected custom dataset attribute tags to enable rapid Javascript evaluation matching -->
            <div class="product-catalog-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transition duration-200 hover:shadow-md"
                data-title="<?= htmlspecialchars(strtolower($product['name'])); ?>">

                <div class="h-48 overflow-hidden bg-gray-100 relative">
                    <!-- Dynamic Heart Toggle Shortcut Link Indicator -->
                    <a href="/customer/wishlist-toggle?id=<?= $product['id'] ?>"
                        class="absolute top-3 right-3 bg-white/80 backdrop-blur shadow-sm p-2 rounded-full hover:bg-white hover:scale-110 transition duration-150 text-sm z-10"
                        title="<?= in_array($product['id'], $favoriteIds) ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>">
                        <?= in_array($product['id'], $favoriteIds) ? '❤️' : '🤍'; ?>
                    </a>

                    <a href="/product/product-details?id=<?= $product['id'] ?>">
                        <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover transition duration-300 hover:scale-105">
                    </a>
                </div>

                <div class="p-5 flex-grow flex flex-col justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800 line-clamp-2 mb-2" title="<?= htmlspecialchars($product['name']) ?>">
                            <!-- WRAP THE TITLE IN A ROUTING LINK -->
                            <a href="/product/product-details?id=<?= $product['id'] ?>" class="hover:text-blue-600 transition">
                                <?= htmlspecialchars($product['name']) ?>
                            </a>
                        </h2>
                        <p class="text-xl font-bold text-gray-900">Rh. <?= number_format($product['price'], 2) ?></p>
                    </div>
                    <div class="mt-4">
                        <?php if (isset($product['stock']) && $product['stock'] <= 0): ?>
                            <!-- Disabled UI Element State for Sold Out models -->
                            <button disabled class="block w-full text-center bg-gray-200 text-gray-400 font-bold py-2.5 px-4 rounded-lg cursor-not-allowed text-sm uppercase tracking-wider">
                                Sold Out 🚫
                            </button>
                        <?php else: ?>
                            <a href="/customer/add-to-cart?id=<?= $product['id'] ?>" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition shadow-sm text-sm">
                                Add to Cart 🛒
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Interactive Empty Search Result Notification Overlay Sheet -->
    <div id="storefront-empty-state" class="hidden bg-white rounded-2xl p-12 text-center text-gray-400 border border-gray-100 shadow-sm max-w-sm mx-auto mt-6">
        <div class="text-4xl mb-3">🔎</div>
        <h4 class="font-bold text-gray-700 text-sm">No Matches Found</h4>
        <p class="text-xs text-gray-400 mt-1">We couldn't find any products that match your keywords. Please try a different search term.</p>
    </div>
</div>

<script>
    // Register the storefront keyword filter engine event listener
    document.getElementById('storefront-search-bar').addEventListener('input', function(e) {
        var keyword = e.target.value.toLowerCase().trim();
        var productCards = document.querySelectorAll('.product-catalog-card');
        var matchingCount = 0;

        productCards.forEach(function(card) {
            var itemTitle = card.getAttribute('data-title') || '';

            // Check if the lowercase item title text contains the user keyword input string
            if (itemTitle.indexOf(keyword) !== -1) {
                card.style.setProperty('display', '', 'important'); // Restore structural rendering visibility
                matchingCount++;
            } else {
                card.style.setProperty('display', 'none', 'important'); // Collapse item smoothly
            }
        });

        // Toggle layout configuration views if search yields completely zero parameters
        var emptyStatePanel = document.getElementById('storefront-empty-state');
        var gridContainerBlock = document.getElementById('product-grid-container');

        if (matchingCount === 0) {
            if (emptyStatePanel) emptyStatePanel.classList.remove('hidden');
            if (gridContainerBlock) gridContainerBlock.classList.add('hidden');
        } else {
            if (emptyStatePanel) emptyStatePanel.classList.add('hidden');
            if (gridContainerBlock) gridContainerBlock.classList.remove('hidden');
        }
    });
</script>