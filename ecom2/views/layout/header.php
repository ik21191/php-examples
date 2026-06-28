<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP MVC Responsive Store</title>
    <script src="/chart.umd.js"></script>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold tracking-tight text-blue-600">MVC_SHOP</a>

            <div class="flex space-x-6 items-center">
                <a href="index.php" class="text-gray-600 hover:text-gray-900 transition text-sm font-medium">Products</a>
                <a href="index.php?controller=cart&action=index" class="relative bg-gray-100 p-2 rounded-full hover:bg-gray-200 transition text-sm">
                    🛒
                    <?php
                    $count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
                    if ($count > 0):
                    ?>
                        <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"><?= $count ?></span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?controller=wishlist&action=index" class="relative inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition mr-2 py-1">
                        <span>❤️ Wishlist</span>
                        <!-- AJAX Dynamic Target Badge Box Element -->
                        <span id="wishlist-badge" class="hidden absolute -top-1.5 -right-2.5 bg-red-500 text-white text-[10px] font-black rounded-full h-4 w-4 flex items-center justify-center scale-90 transition-all duration-300">
                            0
                        </span>
                    </a>
                    <a href="index.php?controller=profile&action=orders" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition">📦 My Orders</a>
                    <span class="text-sm text-gray-500 font-medium">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <a href="index.php?controller=auth&action=logout" class="text-sm font-semibold text-red-500 hover:text-red-700 transition">Logout</a>
                <?php else: ?>
                    <a href="index.php?controller=auth&action=login" class="text-sm font-medium text-gray-600 hover:text-gray-900">Login</a>
                    <a href="index.php?controller=auth&action=register" class="text-sm font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-md hover:bg-blue-700 transition">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-8">
        <?php include $view; ?>
    </main>

    <!-- Asynchronous AJAX Wishlist Counter Sync Script Engine -->
<script>
    (function() {
        function updateWishlistCountBadge() {
            var badge = document.getElementById('wishlist-badge');
            if (!badge) return; // Exit gracefully if element is missing or user is signed out

            // Dispatch an async browser fetch handshake query directly to our API endpoint action
            fetch('index.php?controller=wishlist&action=count')
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response context parameter returned error flag.');
                    }
                    return response.json();
                })
                .then(function(data) {
                    var currentCount = parseInt(data.count) || 0;
                    
                    if (currentCount > 0) {
                        badge.innerText = currentCount;
                        badge.classList.remove('hidden'); // Expose the red badge indicator circle
                    } else {
                        badge.classList.add('hidden'); // Hide it safely if count equates to zero
                    }
                })
                .catch(function(err) {
                    console.error("Wishlist AJAX Sync Runtime Block Error: ", err);
                });
        }

        // Fire instantly when the global DOM structure completes loading sequence phases
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateWishlistCountBadge);
        } else {
            updateWishlistCountBadge();
        }
    })();
</script>

    <footer class="bg-white border-t border-gray-100 py-6 text-center text-sm text-gray-500 mt-12">
        <p>&copy; <?= date('Y') ?> Minimal PHP MVC E-Commerce Application.</p>
    </footer>
</body>

</html>