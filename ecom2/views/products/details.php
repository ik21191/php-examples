<!-- views/products/details.php -->
<?php
require_once __DIR__ . '/../../models/Wishlist.php';
$userId = $_SESSION['user_id'] ?? null;
$favoriteIds = Wishlist::getUserWishlistIds($userId);
$isWishlisted = in_array($product['id'], $favoriteIds);
?>

<div class="max-w-5xl mx-auto">
    <!-- Dynamic Breadcrumb Navigation link path row shortcut -->
    <nav class="flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-6">
        <a href="index.php" class="hover:text-blue-600 transition">Catalog Collection</a>
        <span>/</span>
        <span class="text-gray-600 truncate max-w-[200px]"><?= htmlspecialchars($product['name']); ?></span>
    </nav>

    <!-- Main Dynamic Layout Interface Container Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
        
        <!-- Left Side: Product Media Layout Presentation Container Block -->
        <div class="relative bg-gray-50/50 rounded-2xl overflow-hidden border border-gray-100 p-4 flex items-center justify-center h-80 md:h-[400px]">
            <!-- Wishlist Heart Button Link Element Anchor overlay -->
            <a href="index.php?controller=wishlist&action=toggle&id=<?= $product['id'] ?>" 
               class="absolute top-4 right-4 bg-white shadow p-3 rounded-full hover:scale-110 transition duration-150 text-base z-10">
                <?= $isWishlisted ? '❤️' : '🤍'; ?>
            </a>
            
            <img src="<?= htmlspecialchars($product['image']); ?>" 
                 alt="<?= htmlspecialchars($product['name']); ?>" 
                 class="max-w-full max-h-full object-contain rounded-xl drop-shadow-md">
        </div>

        <!-- Right Side: Content Overview Detail Metrics Mapping Section -->
        <div class="flex flex-col justify-between">
            <div class="space-y-4">
                <span class="bg-blue-50 text-blue-600 font-bold uppercase tracking-wider text-[10px] px-2.5 py-1 rounded-md">
                    Premium Accessory
                </span>
                
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight leading-tight">
                    <?= htmlspecialchars($product['name']); ?>
                </h1>

                <div class="flex items-center space-x-2 text-sm">
                    <span class="text-amber-400 font-bold text-base">★★★★★</span>
                    <span class="text-gray-400 font-medium">(4.9 out of 5 stars based on verified test logs)</span>
                </div>

                <div class="border-y border-gray-100 py-3 flex items-baseline space-x-3">
                    <span class="text-3xl font-black text-blue-600">₹<?= number_format($product['price'], 2); ?></span>
                    <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 font-bold uppercase">
                        In Stock & Ready
                    </span>
                </div>

                <!-- NEW Dynamic Product Content Description Block Paragraph -->
                <div class="space-y-1.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Product Overview</h3>
                    <p class="text-sm md:text-base text-gray-600 leading-relaxed font-normal">
                        <?= !empty($product['description']) ? htmlspecialchars($product['description']) : 'No descriptive text summary notation registered for this item in our collection catalog.'; ?>
                    </p>
                </div>
            </div>

            <!-- Checkout Order Fulfillment Action CTA Button Layout Component -->
            <div class="pt-6 border-t border-gray-100 mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="index.php?controller=cart&action=add&id=<?= $product['id'] ?>" 
                   class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition shadow-sm text-sm flex items-center justify-center space-x-2">
                    <span>Add to Shop Cart</span>
                    <span>🛒</span>
                </a>
                <a href="index.php" 
                   class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3.5 px-4 rounded-xl transition text-sm">
                    Back to Catalog
                </a>
            </div>
        </div>

    </div>
</div>
