<!-- views/admin/products.php -->
<div class="max-w-6xl mx-auto">

    <!-- Header Block Section -->
    <div class="flex flex-wrap items-center justify-between border-b border-gray-100 pb-5 mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Product Management</h1>
            <p class="text-sm text-gray-500 mt-1">Add, update, or remove dynamic inventory items directly from the catalog database.</p>
        </div>
        <a href="index.php?controller=admin&action=dashboard" class="text-xs font-semibold bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 transition">
            ← Analytics Panel
        </a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 mb-6 rounded-r-xl text-sm font-medium">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- 1. Interactive Form Box Area Container with File Upload Configuration -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm h-fit">
            <h3 id="form-title" class="text-lg font-bold text-gray-900 mb-4">Add New Product</h3>

            <form action="index.php?controller=admin&action=products" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" id="action_type" name="action_type" value="create">
                <input type="hidden" id="product_id" name="product_id" value="">
                <input type="hidden" id="existing_image" name="existing_image" value="">

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Product Title</label>
                    <input type="text" id="form-name" name="name" required placeholder="e.g., Wireless Mouse"
                        class="w-full text-sm px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Price Notation (₹)</label>
                    <input type="number" id="form-price" name="price" step="0.01" required placeholder="e.g., 1499.00"
                        class="w-full text-sm px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Product Description</label>
                    <textarea id="form-description" name="description" rows="3" required placeholder="Type rich technical overview features here..."
                        class="w-full text-sm px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-wider mb-1">Upload Product Image (.png, .jpg)</label>
                    <input type="file" id="form-image-file" name="product_image" accept="image/png, image/jpeg, image/jpg"
                        class="w-full text-sm px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p id="edit-img-note" class="hidden text-[11px] text-gray-400 mt-1">* Leave blank to retain existing file.</p>
                    <p id="js-upload-error" class="hidden text-xs font-bold text-red-500 mt-1.5"></p>
                </div>

                <div class="pt-2 space-y-2">
                    <button type="submit" id="form-submit-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition shadow-sm text-sm cursor-pointer">
                        Insert Product Row
                    </button>
                    <button type="button" id="form-cancel-btn" onclick="resetFormState()" class="hidden w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2 rounded-xl transition text-xs cursor-pointer">
                        Cancel Editing Mode
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Interactive Catalog List Table Matrix (2/3 Width) -->
        <div class="lg:col-span-2 flex flex-col space-y-4">

            <div class="relative w-full shadow-sm rounded-2xl">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 text-sm pointer-events-none">🔍</span>
                <input type="text" id="catalog-search-bar" placeholder="Type to filter inventory catalog items instantaneously by title..."
                    class="w-full text-sm pl-10 pr-4 py-3.5 bg-white border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400 font-medium transition">
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden relative">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="inventory-table">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                                <th class="px-6 py-3.5">Image</th>
                                <th class="px-6 py-3.5">Title Name</th>
                                <th class="px-6 py-3.5 max-w-[200px]">Description</th>
                                <th class="px-6 py-3.5">Price</th>
                                <th class="px-6 py-3.5 text-right">Actions Operations</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm" id="catalog-table-body">
                            <?php foreach ($products as $p): ?>
                                <tr class="product-row hover:bg-gray-50/50 transition" data-name="<?= htmlspecialchars(strtolower($p['name'] ?? '')); ?>">
                                    <td class="px-6 py-3">
                                        <img src="<?= htmlspecialchars($p['image']); ?>" class="w-10 h-10 object-cover rounded-lg bg-gray-50 shadow-inner border border-gray-100">
                                    </td>
                                    <td class="px-6 py-3 font-semibold text-gray-800">
                                        <?= htmlspecialchars($p['name']); ?>
                                    </td>
                                    <td class="px-6 py-3 text-xs text-gray-500 max-w-[200px] truncate" title="<?= htmlspecialchars($p['description'] ?? ''); ?>">
                                        <?= !empty($p['description']) ? htmlspecialchars($p['description']) : '<span class="text-gray-300 italic">No description logged</span>'; ?>
                                    </td>
                                    <td class="px-6 py-3 font-bold text-gray-900">
                                        ₹<?= number_format($p['price'], 2); ?>
                                    </td>
                                    <td class="px-6 py-3 text-right space-x-3 whitespace-nowrap">
                                        <button onclick="populateEditForm(<?= htmlspecialchars(json_encode($p)); ?>)"
                                            class="text-xs font-bold text-blue-600 hover:underline cursor-pointer">
                                            Edit Details
                                        </button>
                                        <a href="index.php?controller=admin&action=products&delete_id=<?= $p['id']; ?>"
                                            onclick="return confirm('Are you sure you want to delete this product?');"
                                            class="text-xs font-bold text-red-500 hover:underline">
                                            Delete Row
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div id="search-empty-state" class="hidden p-12 text-center text-gray-400">
                    <div class="text-3xl mb-2">🔎</div>
                    <h4 class="font-bold text-gray-700 text-sm">No Matching Inventory Items Found</h4>
                    <p class="text-xs text-gray-400 mt-1">Adjust your search parameters terms to find cached products.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Self-executing isolation block to handle search queries cleanly without errors
    (function() {
        function initSearchEngine() {
            var searchInput = document.getElementById('catalog-search-bar');
            if (!searchInput) return;

            searchInput.addEventListener('input', function(e) {
                var queryText = e.target.value.toLowerCase().trim();
                var productRows = document.querySelectorAll('.product-row');
                var visibleRowCount = 0;

                productRows.forEach(function(row) {
                    var productName = row.getAttribute('data-name') || '';
                    if (productName.indexOf(queryText) !== -1) {
                        row.style.setProperty('display', '', 'important');
                        visibleRowCount++;
                    } else {
                        row.style.setProperty('display', 'none', 'important');
                    }
                });
                var emptyStateContainer = document.getElementById('search-empty-state');
                var tableContainer = document.getElementById('inventory-table');
                if (visibleRowCount === 0) {
                    if (emptyStateContainer) emptyStateContainer.classList.remove('hidden');
                    if (tableContainer) tableContainer.classList.add('hidden');
                } else {
                    if (emptyStateContainer) emptyStateContainer.classList.add('hidden');
                    if (tableContainer) tableContainer.classList.remove('hidden');
                }
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSearchEngine);
        } else {
            initSearchEngine();
        }
    })();

    // Client-side local file size validator listener (2MB limit)
    document.getElementById('form-image-file').addEventListener('change', function(e) {
        var file = this.files[0];
        var errorDisplay = document.getElementById('js-upload-error');
        var submitButton = document.getElementById('form-submit-btn');
        if (file) {
            var maxBytes = 2 * 1024 * 1024;
            if (file.size > maxBytes) {
                errorDisplay.innerText = "❌ File too large (" + (file.size / (1024 * 1024)).toFixed(2) + "MB). Max allowed size is 2MB.";
                errorDisplay.classList.remove('hidden');
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                errorDisplay.innerText = "";
                errorDisplay.classList.add('hidden');
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    });

    // Global scopes functions for button interface interaction hooks
    function populateEditForm(product) {
        document.getElementById('form-title').innerText = 'Modify Existing Product';
        document.getElementById('action_type').value = 'update';
        document.getElementById('product_id').value = product.id;
        document.getElementById('existing_image').value = product.image;
        document.getElementById('form-name').value = product.name;
        document.getElementById('form-price').value = product.price;
        document.getElementById('form-description').value = product.description || '';
        document.getElementById('form-image-file').required = false;
        document.getElementById('edit-img-note').classList.remove('hidden');
        document.getElementById('form-submit-btn').innerText = 'Save Modifications';
        document.getElementById('form-cancel-btn').classList.remove('hidden');
        document.getElementById('form-title').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function resetFormState() {
        document.getElementById('form-title').innerText = 'Add New Product';
        document.getElementById('action_type').value = 'create';
        document.getElementById('product_id').value = '';
        document.getElementById('existing_image').value = '';
        document.getElementById('form-name').value = '';
        document.getElementById('form-price').value = '';
        document.getElementById('form-description').value = '';
        document.getElementById('form-image-file').value = '';
        document.getElementById('form-image-file').required = true;
        document.getElementById('edit-img-note').classList.add('hidden');
        document.getElementById('js-upload-error').classList.add('hidden');
        document.getElementById('form-submit-btn').innerText = 'Insert Product Row';
        document.getElementById('form-cancel-btn').classList.add('hidden');
        document.getElementById('form-submit-btn').disabled = false;
        document.getElementById('form-submit-btn').classList.remove('opacity-50', 'cursor-not-allowed');
    }
</script>