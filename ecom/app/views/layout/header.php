<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP MVC Responsive Store</title>
    <link rel="stylesheet" href="/js/tailwind.css">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4">
            
            <!-- MASTER FLEX GRID ROW CONTAINER -->
            <div class="flex justify-between items-center h-16 w-full relative">
                
                <!-- Left Section: Corporate Branding Logo Link -->
                <a href="/" class="text-xl font-bold tracking-tight text-blue-600 flex-shrink-0 mr-4">MVC_SHOP</a>
                
                <!-- Middle Section: Main Interactive Navigation Links Group (Collapses on Mobile) -->
                <!-- Removed absolute restrictions to prevent overlapping elements completely -->
                <div id="navbar-nav-menu" class="hidden sm:flex flex-col sm:flex-row gap-y-4 gap-x-6 items-stretch sm:items-center bg-white sm:bg-transparent p-4 sm:p-0 absolute sm:relative top-16 sm:top-0 left-0 sm:left-auto w-full sm:w-auto border-b border-gray-100 sm:border-0 text-sm font-medium transition-all duration-300 z-50 shadow-md sm:shadow-none">
                    <a href="" class="text-gray-600 hover:text-gray-900 transition py-1 text-center sm:text-left">Products</a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/admin/dashboard" class="font-bold bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1.5 rounded-xl hover:bg-blue-100 transition flex items-center justify-center space-x-1 w-full sm:w-auto">
                            <span>🛠️ Admin Panel</span>
                        </a>

                        <a href="/customer/profile" class="text-gray-600 hover:text-gray-900 transition py-1 flex items-center justify-center space-x-1">
                            <span>👤 My Profile</span>
                        </a>

                        <a href="/customer/wishlist" class="relative inline-flex items-center justify-center text-gray-600 hover:text-gray-900 transition py-1">
                            <span>❤️ Wishlist</span>
                            <span id="wishlist-badge" class="hidden absolute -top-1 -right-2.5 bg-red-500 text-white text-[10px] font-black rounded-full h-4 w-4 flex items-center justify-center scale-90 transition-all duration-300">0</span>
                        </a>

                        <a href="/customer/orders" class="text-blue-600 hover:text-blue-700 transition py-1 text-center sm:text-left">📦 My Orders</a>
                        
                        <span class="hidden sm:inline text-gray-200 font-normal">|</span>
                        <span class="text-gray-500 text-center sm:text-left truncate max-w-[120px] block py-1" title="<?= htmlspecialchars($_SESSION['user_name']) ?>">
                            Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </span>
                        
                        <a href="/customer/logout" class="font-semibold text-red-500 hover:text-red-700 transition py-1 text-center sm:text-left">Logout</a>
                    <?php else: ?>
                        <a href="/customer/login-form" class="text-gray-600 hover:text-gray-900 transition py-1 text-center sm:text-left">Login</a>
                        <a href="/customer/register-form" class="font-semibold bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition text-center sm:text-left shadow-xs">Sign Up</a>
                    <?php endif; ?>
                </div>

                <!-- Right Section: Static Action Control Triggers (Always grouped together perfectly) -->
                <div class="flex items-center space-x-3 flex-shrink-0 ml-auto">
                    <!-- Standard Cart Link Button Wrapper with Counter Badge -->
                    <a href="/customer/cart" class="relative bg-gray-100 p-2 rounded-full hover:bg-gray-200 transition text-sm flex items-center justify-center">
                        <span>🛒</span> 
                        <?php 
                        $count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
                        if ($count > 0): 
                        ?>
                            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-xs"><?= $count ?></span>
                        <?php endif; ?>
                    </a>

                    <!-- Hamburger Button Mobile Trigger Element (Only shows on mobile screens) -->
                    <button id="mobile-menu-btn" type="button" class="sm:hidden text-gray-500 hover:text-gray-900 focus:outline-none p-1.5 border border-gray-100 rounded-xl bg-gray-50 text-xl cursor-pointer" aria-label="Toggle Navigation">
                        ☰
                    </button>
                </div>

            </div>
        </div>
    </nav>

    <!-- Main Dynamic Content Container Screen Wrapper Layout -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-8">
        <?php include $view; ?>
    </main>

<!-- Unified Script Area for Mobile Drawer and Wishlist Badges -->
<script>
    (function() {
        // --- 1. RESPONSIVE MOBILE MENU INTERCEPTOR TRIGGER ---
        var menuBtn = document.getElementById('mobile-menu-btn');
        var navMenu = document.getElementById('navbar-nav-menu');

        if (menuBtn && navMenu) {
            menuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (navMenu.classList.contains('hidden')) {
                    navMenu.classList.remove('hidden');
                    menuBtn.innerText = '✕';
                    menuBtn.className = "sm:hidden text-red-500 hover:text-red-700 focus:outline-none p-1.5 border border-red-100 rounded-xl bg-red-50 text-xl cursor-pointer";
                } else {
                    navMenu.classList.add('hidden');
                    menuBtn.innerText = '☰';
                    menuBtn.className = "sm:hidden text-gray-500 hover:text-gray-900 focus:outline-none p-1.5 border border-gray-100 rounded-xl bg-gray-50 text-xl cursor-pointer";
                }
            });

            document.addEventListener('click', function(e) {
                if (!navMenu.classList.contains('hidden') && !navMenu.contains(e.target) && e.target !== menuBtn) {
                    navMenu.classList.add('hidden');
                    menuBtn.innerText = '☰';
                    menuBtn.className = "sm:hidden text-gray-500 hover:text-gray-900 focus:outline-none p-1.5 border border-gray-100 rounded-xl bg-gray-50 text-xl cursor-pointer";
                }
            });
        }

        // --- 2. ASYNCHRONOUS AJAX WISHLIST BADGE COUNTER SYNC ---
        function updateWishlistCountBadge() {
            var badge = document.getElementById('wishlist-badge');
            if (!badge) return; // Exit gracefully if element is missing or user is signed out

            // Dispatch an async browser fetch handshake query directly to our API endpoint action
            fetch('/customer/wishlist-count')
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

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateWishlistCountBadge);
        } else {
            updateWishlistCountBadge();
        }
    })();
</script>
</body>
</html>
