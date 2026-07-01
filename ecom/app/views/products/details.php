<!-- views/products/details.php -->
<?php
require_once __DIR__ . '/../../models/Wishlist.php';
$userId = $_SESSION['user_id'] ?? null;
$favoriteIds = Wishlist::getUserWishlistIds($userId);
$isWishlisted = in_array($product['id'], $favoriteIds);

// Unpack average metrics rating score trends logic parameters
$avgRating = isset($ratingStats['avg_rating']) ? round((float)$ratingStats['avg_rating'], 1) : 0;
$totalReviews = isset($ratingStats['total_count']) ? (int)$ratingStats['total_count'] : 0;
$productStock = isset($product['stock']) ? (int)$product['stock'] : 0;
?>

<div class="max-w-5xl mx-auto space-y-10">
    <!-- Breadcrumb row line link path -->
    <nav class="flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="/" class="hover:text-blue-600 transition">Catalog Collection</a>
        <span>/</span>
        <span class="text-gray-600 truncate max-w-[200px]"><?= htmlspecialchars($product['name']); ?></span>
    </nav>

    <!-- Main item detail description card container block layout grid matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
        <div class="relative bg-gray-50/50 rounded-2xl overflow-hidden border border-gray-100 p-4 flex items-center justify-center h-80 md:h-[400px]">
            <a href="/customer/wishlist-toggle?id=<?= $product['id'] ?>" class="absolute top-4 right-4 bg-white shadow p-3 rounded-full hover:scale-110 transition text-base z-10">
                <?= $isWishlisted ? '❤️' : '🤍'; ?>
            </a>
            <img src="/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="max-w-full max-h-full object-contain drop-shadow-md">
        </div>

        <div class="flex flex-col justify-between">
            <div class="space-y-4">
                <span class="bg-blue-50 text-blue-600 font-bold uppercase tracking-wider text-[10px] px-2.5 py-1 rounded-md">Premium Item</span>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight leading-tight"><?= htmlspecialchars($product['name']); ?></h1>

                <div class="flex items-center space-x-2 text-sm">
                    <span class="text-amber-500 font-bold text-base">
                        <?= $avgRating > 0 ? str_repeat('★', round($avgRating)) . str_repeat('☆', 5 - round($avgRating)) : '☆☆☆☆☆'; ?>
                    </span>
                    <span class="text-gray-500 font-semibold"><?= $avgRating > 0 ? $avgRating : 'No ratings yet'; ?> (<?= $totalReviews; ?> verified user testimonials)</span>
                </div>

                <div class="border-y border-gray-100 py-3 flex items-baseline space-x-3">
                    <span class="text-3xl font-black text-blue-600">₹<?= number_format($product['price'], 2); ?></span>
                    <div>
                        <?php if ($productStock <= 0): ?>
                            <span class="inline-block bg-red-50 text-red-600 border border-red-100 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider">Out of Stock 🚨</span>
                        <?php elseif ($productStock <= 3): ?>
                            <span class="inline-block bg-amber-50 text-amber-600 border border-amber-100 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider">Low Stock (<?= $productStock; ?> Left) ⚠️</span>
                        <?php else: ?>
                            <span class="inline-block bg-green-50 text-green-700 border border-green-100 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider">In Stock Available</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Product Overview</h3>
                    <p class="text-sm md:text-base text-gray-600 leading-relaxed font-normal"><?= htmlspecialchars($product['description'] ?? 'No overview data summary text logged.'); ?></p>
                </div>
            </div>

            <!--
            <div class="pt-6 border-t border-gray-100 mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="/customer/add-to-cart?id=<?= $product['id'] ?>" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition shadow-sm text-sm flex items-center justify-center space-x-2">
                    <span>Add to Shop Cart 🛒</span>
                </a>
                <a href="/" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3.5 px-4 rounded-xl transition text-sm">Back to Catalog</a>
            </div>
            -->

            <div class="pt-6 border-t border-gray-100 mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <?php if ($productStock <= 0): ?>
                    <!-- Disabled State if product reaches 0 quantity -->
                    <button disabled class="w-full text-center bg-gray-200 text-gray-400 font-bold py-3.5 px-4 rounded-xl cursor-not-allowed text-sm uppercase tracking-wide shadow-inner">
                        Sold Out 🚫
                    </button>
                <?php else: ?>
                    <a href="/customer/add-to-cart?id=<?= $product['id'] ?>" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition shadow-sm text-sm flex items-center justify-center space-x-2">
                        <span>Add to Shop Cart 🛒</span>
                    </a>
                <?php endif; ?>
                
                <a href="/" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3.5 px-4 rounded-xl transition text-sm flex items-center justify-center">Back to Catalog</a>
            </div>


        </div>
    </div>

    <!-- Relational Reviews Section Panel Blocks Stack Layout Row Area -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        <!-- Left Column: Add a testimonial form interface widget -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-gray-900 tracking-tight">Submit a Review</h3>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="/customer/add-product-review" method="POST" class="space-y-4">
                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Score Scale Star Rating</label>
                        <select name="rating" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50">
                            <option value="5">★★★★★ (5 - Excellent Quality)</option>
                            <option value="4">★★★★☆ (4 - Good Purchase)</option>
                            <option value="3">★★★☆☆ (3 - Standard Average)</option>
                            <option value="2">★★☆☆☆ (2 - Below Expectations)</option>
                            <option value="1">★☆☆☆☆ (1 - Poor Performance)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Your Feedback Comment</label>
                        <textarea name="comment" rows="3" required placeholder="Type your experience details metrics here..." class="w-full text-sm px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-2.5 rounded-xl transition text-xs cursor-pointer">Submit Testimonial</button>
                </form>
            <?php else: ?>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center text-xs text-gray-500">
                    <p class="mb-2">You must be logged in to submit a rating score tracking form entry.</p>
                    <a href="/customer/login-form" class="text-blue-600 font-bold hover:underline">Sign In Here →</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: List history reviews dataset table iteration loop (2/3 Width) -->
        <div class="md:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-gray-900 tracking-tight">Customer Testimonials Matrix Loop (<?= count($reviews); ?>)</h3>

            <?php if (empty($reviews)): ?>
                <p class="text-sm text-gray-400 text-center py-6">No historical user feedback comments logged yet for this model.</p>
            <?php else: ?>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto pr-2 space-y-4">
                    <?php foreach ($reviews as $rev): ?>
                        <div class="pt-4 first:pt-0 text-sm">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-gray-800"><?= htmlspecialchars($rev['user_name']); ?></span>
                                <span class="text-[11px] text-gray-400 font-medium"><?= date('d M Y', strtotime($rev['created_at'])); ?></span>
                            </div>
                            <div class="text-amber-500 text-xs font-bold mb-1.5"><?= str_repeat('★', $rev['rating']) . str_repeat('☆', 5 - $rev['rating']); ?></div>
                            <p class="text-gray-600 bg-gray-50/50 p-3 rounded-xl border border-gray-100 font-normal leading-relaxed"><?= htmlspecialchars($rev['comment']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>