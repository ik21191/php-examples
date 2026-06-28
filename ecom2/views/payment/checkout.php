<div class="max-w-md mx-auto bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mt-10">
    <!-- Simulated Bank/Gateway Header Accent Section -->
    <div class="bg-blue-600 px-6 py-4 text-white flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <span class="text-xl font-black tracking-wider">SECURE_PAY</span>
            <span class="bg-blue-500 text-[10px] uppercase font-bold px-1.5 py-0.5 rounded">Test Mode</span>
        </div>
        <span class="text-sm font-medium">₹<?= number_format($totalAmountINR, 2); ?></span>
    </div>

    <div class="p-6">
        <p class="text-sm text-gray-500 mb-6 text-center">Select an Indian Payment Option to simulate terminal approval.</p>

        <!-- Simulated Interactive Form Wrapper pointing back directly to local verify logic -->
        <form action="index.php?controller=payment&action=verify" method="POST" class="space-y-4">
            <!-- Hidden dynamic value parsing parameters -->
            <input type="hidden" name="mock_payment_id" value="pay_mock_<?= bin2hex(random_bytes(6)); ?>">

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Shipping & Delivery Address (India Only)</label>
                <textarea name="shipping_address" rows="2" required placeholder="Enter house number, street name, city, state, pincode..." class="w-full text-sm px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50"></textarea>
            </div>

            <!-- Simulated UPI Payment Option -->
            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50 hover:border-blue-300 transition group">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="payment_method" value="upi" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="block font-semibold text-gray-800 text-sm">UPI / Instant Netbanking</span>
                        <span class="block text-xs text-gray-400">Google Pay, PhonePe, Paytm, BHIM</span>
                    </div>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded group-hover:bg-blue-100">Popular</span>
            </label>

            <!-- Simulated Debit/Credit Card Option -->
            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50 hover:border-blue-300 transition">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="payment_method" value="card" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="block font-semibold text-gray-800 text-sm">Cards (Domestic / International)</span>
                        <span class="block text-xs text-gray-400">RuPay, Visa, Mastercard, Maestro</span>
                    </div>
                </div>
            </label>

            <!-- Simulated Cash on Delivery Option -->
            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50/50 hover:border-blue-300 transition">
                <div class="flex items-center space-x-3">
                    <input type="radio" name="payment_method" value="cod" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="block font-semibold text-gray-800 text-sm">Cash on Delivery (COD)</span>
                        <span class="block text-xs text-gray-400">Pay physically upon safe shipment delivery</span>
                    </div>
                </div>
            </label>

            <!-- Action buttons configuration -->
            <div class="pt-4 flex flex-col space-y-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm tracking-wide text-sm">
                    Simulate Payment Completion
                </button>
                <a href="index.php?controller=cart&action=index" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl transition text-xs">
                    Cancel and Return to Cart
                </a>
            </div>
        </form>
    </div>
</div>