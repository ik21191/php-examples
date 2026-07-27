<!-- views/auth/reset_password.php -->
<div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mt-10">
    <h2 class="text-2xl font-black text-gray-900 mb-2 text-center tracking-tight">Set New Password</h2>
    <p class="text-xs text-center text-gray-400 mb-6">Your token authorization has been verified. Choose a strong fresh access key.</p>

    <?php if (!empty($message)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded-xl text-sm font-medium">
            <?= $message; ?>
        </div>
    <?php endif; ?>

    <?php if (!$isError): ?>
        <form action="/customer/reset-password" method="POST" class="space-y-4">
            <!-- Retain current active query state variables inside post forms -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
            
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">New Secure Password</label>
                <input type="password" name="password" required minlength="4" placeholder="••••••••" 
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm text-sm cursor-pointer">
                Update Account Password
            </button>
        </form>
    <?php endif; ?>
    <p class="text-sm text-center text-gray-500 mt-5"><a href="/customer/login-form" class="text-blue-600 font-bold hover:underline">Return to Login</a></p>
</div>
