<!-- views/profile/edit.php -->
<div class="max-w-2xl mx-auto flex flex-col md:flex-row gap-8">
    
    <!-- Left Navigation Shortcut Controls Box Widget -->
    <div class="w-full md:w-1/3 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm h-fit space-y-2">
        <h3 class="text-xs font-bold uppercase text-gray-400 tracking-wider px-3 mb-2">Account Hub</h3>
        <a href="/customer/profile" class="block text-sm font-bold bg-blue-50 text-blue-600 px-4 py-2.5 rounded-xl transition">
            👤 Profile Settings
        </a>
        <a href="/customer/orders" class="block text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-4 py-2.5 rounded-xl transition">
            📦 Purchase History
        </a>
        <a href="/customer/wishlist" class="block text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-4 py-2.5 rounded-xl transition">
            ❤️ My Wishlist
        </a>
    </div>

    <!-- Right Interaction Update Profile Form Container Grid Matrix -->
    <div class="w-full md:w-2/3 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
        <div class="mb-6">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Profile Settings</h2>
            <p class="text-xs text-gray-500 mt-1">Review, manage, and update your personal information or security metrics passwords.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="<?= $isError ? 'bg-red-50 border-red-500 text-red-700' : 'bg-emerald-50 border-emerald-500 text-emerald-800' ?> border-l-4 p-4 mb-5 rounded-r-xl text-sm font-medium">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <form action="/customer/update-profile" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Full Name</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($user['name'] ?? ''); ?>" 
                       class="w-full text-sm px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50 font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Mobile Number</label>
                <div class="relative shadow-sm rounded-xl flex">
                    <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-sm font-semibold">+91</span>
                    <input type="tel" name="phone" required placeholder="9876543210" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>" 
                           class="w-full text-sm px-3 py-2.5 border border-gray-200 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-blue-500 tracking-wide font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Email Address</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? ''); ?>" 
                       class="w-full text-sm px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50 font-medium">
            </div>

            <div class="pt-4 border-t border-gray-100">
                <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Change Password (Optional)</label>
                <input type="password" name="password" minlength="4" placeholder="••••••••" 
                       class="w-full text-sm px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-[10px] text-gray-400 mt-1">* Leave completely blank if you do not wish to overwrite your current operational password keys.</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm text-sm cursor-pointer tracking-wide">
                    Save Profile Modifications
                </button>
            </div>
        </form>
    </div>
</div>
