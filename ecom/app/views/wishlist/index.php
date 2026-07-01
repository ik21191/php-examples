<!-- views/wishlist/index.php -->
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Your Favorites Wishlist</h1>
        <p class="text-sm text-gray-500 mt-1">Keep track of premium products you love and wish to purchase later.</p>
    </div>

    <?php if (empty($products)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm max-w-md mx-auto">
            <div class="text-4xl mb-4">❤️</div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Your Wishlist is Empty</h3>
            <p class="text-sm text-gray-400 mb-6">Tap the heart markers on the store catalog grid to track items here.</p>
            <a href="/" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition shadow">
                Browse Collection Catalog
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transition duration-200 hover:shadow-md relative">
                    
                    <!-- Rapid Deletion Hook directly from inside card block overlay -->
                    <a href="/customer/wishlist-toggle?id=<?= $product['id'] ?>" class="absolute top-3 right-3 bg-white/80 backdrop-blur shadow-sm p-1.5 rounded-full hover:bg-white transition text-sm">
                        ❤️
                    </a>

                    <div class="h-48 overflow-hidden bg-gray-100">
                        <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                    </div>
                    
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <h2 class="text-md font-semibold text-gray-800 line-clamp-2 mb-2"><?= htmlspecialchars($product['name']) ?></h2>
                            <p class="text-lg font-bold text-gray-900">₹<?= number_format($product['price'], 2) ?></p>
                        </div>
                        <div class="mt-4">
                            <a href="/customer/add-to-cart?id=<?= $product['id'] ?>" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-xl transition text-sm shadow-sm">
                                Move to Cart 🛒
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
