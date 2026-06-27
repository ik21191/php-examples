<h1 class="text-3xl font-extrabold text-gray-900 mb-8">Your Shopping Cart</h1>

<?php if (empty($cartItems)): ?>
    <div class="bg-white p-8 rounded-xl text-center shadow-sm max-w-md mx-auto">
        <p class="text-gray-500 mb-4">Your cart is empty.</p>
        <a href="index.php" class="inline-block bg-blue-600 text-white font-medium px-6 py-2 rounded-lg hover:bg-blue-700">Go Shopping</a>
    </div>
<?php else: ?>
    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-2/3 bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
            <?php 
            $total = 0;
            foreach ($cartItems as $id => $item): 
                $itemTotal = $item['price'] * $item['quantity'];
                $total += $itemTotal;
            ?>
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                    <div class="flex items-center space-x-4">
                        <img src="<?= $item['image'] ?>" class="w-16 h-16 object-cover rounded-lg bg-gray-50">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm md:text-base"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-gray-500 text-sm">₹<?= number_format($item['price'], 2) ?> x <?= $item['quantity'] ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">₹<?= number_format($itemTotal, 2) ?></p>
                        <a href="index.php?controller=cart&action=remove&id=<?= $id ?>" class="text-xs text-red-500 hover:underline">Remove</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="w-full lg:w-1/3 bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
            <div class="flex justify-between border-b border-gray-100 pb-3 mb-4">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($total, 2) ?></span>
            </div>
            <div class="flex justify-between items-center mb-6">
                <span class="text-lg font-bold text-gray-800">Total</span>
                <span class="text-2xl font-black text-blue-600">₹<?= number_format($total, 2) ?></span>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="index.php?controller=payment&action=checkout" method="POST">
                    <button type="submit" class="w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-md">
                        Proceed to Checkout
                    </button>
                </form>
            <?php else: ?>
                <a href="index.php?controller=auth&action=login" class="block w-full text-center bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-4 rounded-lg transition shadow-md">
                    Login to Checkout
                </a>
            <?php endif; ?>
        </div>

    </div>
<?php endif; ?>
