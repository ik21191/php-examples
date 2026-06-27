<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP MVC Responsive Store</title>
    <!-- Alternate CDN bypassing local ORB block flags -->
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

                <!-- Locating user authentication state inside views/layout/header.php -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- NEW DASHBOARD LINK SHORTCUT HOOK -->
                        <a href="index.php?controller=wishlist&action=index" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">❤️ Wishlist</a>
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

<?php require_once __DIR__ . '/footer.php'; ?>
