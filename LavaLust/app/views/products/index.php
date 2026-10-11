<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-indigo-400">Products List</h1>
            <div class="space-x-2">
                <a href="<?= site_url('products/create'); ?>" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium transition duration-200 shadow-md hover:shadow-indigo-500/20">+ Add Product</a>
                <a href="<?= site_url('logout'); ?>" onclick="return confirm('Are you sure you want to logout?');" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg font-medium transition duration-200 shadow-md hover:shadow-red-500/20">Logout</a>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl shadow-xl overflow-hidden border border-gray-700">
            <table class="w-full text-left">
                <thead class="bg-gray-700/50 text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="p-4">Name</th>
                        <th class="p-4">Description</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Qty</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php if(!empty($products)): foreach($products as $p): ?>
                        <tr class="hover:bg-gray-700/30 transition duration-150">
                            <td class="p-4 font-semibold text-white"><?= htmlspecialchars($p['product_name']); ?></td>
                            <td class="p-4 text-gray-400"><?= htmlspecialchars($p['description']); ?></td>
                            <td class="p-4 text-emerald-400 font-medium">₱<?= number_format($p['price'], 2); ?></td>
                            <td class="p-4"><?= $p['quantity']; ?></td>
                            <td class="p-4 text-center space-x-3">
                                <a href="<?= site_url('products/edit/' . $p['id']); ?>" class="text-indigo-400 hover:text-indigo-300 font-medium transition">Edit</a>
                                <a href="<?= site_url('products/delete/' . $p['id']); ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="text-red-400 hover:text-red-300 font-medium transition">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="p-4 text-center text-gray-500">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>