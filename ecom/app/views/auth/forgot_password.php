<!-- views/auth/forgot_password.php -->
<div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mt-10">
    <h2 class="text-2xl font-black text-gray-900 mb-2 text-center tracking-tight">Recover Password</h2>

    <?php if (!empty($message)): ?>
        <div class="<?= $isError ? 'bg-red-50 border-red-500 text-red-700' : 'bg-emerald-50 border-emerald-500 text-emerald-800' ?> border-l-4 p-3 mb-4 rounded-xl text-sm font-medium">
            <?= $message; ?>
        </div>
    <?php endif; ?>

    <form action="/customer/generate-reset-password-link" method="POST" class="space-y-4">
        <div>
            <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Registered Email Address</label>
            <input type="email" name="email" required placeholder="name@company.com" 
                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm text-sm cursor-pointer">
            Generate Recovery Link
        </button>
    </form>
    <p class="text-sm text-center text-gray-500 mt-5"><a href="/customer/login-form" class="text-blue-600 font-bold hover:underline">← Back to Login</a></p>
</div>
