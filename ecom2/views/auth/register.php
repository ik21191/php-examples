<div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100 mt-10">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Create Account</h2>
    
    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-sm"><?= $error ?></div>
    <?php endif; ?>

    <form action="index.php?controller=auth&action=register" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition">Register</button>
    </form>
    <p class="text-sm text-center text-gray-500 mt-4">Already have an account? <a href="index.php?controller=auth&action=login" class="text-blue-600 hover:underline">Log in</a></p>
</div>
