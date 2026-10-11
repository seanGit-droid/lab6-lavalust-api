<?php /** @var array $product */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-lg border border-gray-700 transition-all duration-300 hover:border-indigo-500/50">
        <h2 class="text-2xl font-bold mb-6 text-indigo-400">Edit Product</h2>
        <form action="<?= site_url('products/update/' . $product['id']); ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Product Name</label>
                <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']); ?>" required class="w-full bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg p-2.5 text-white transition duration-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg p-2.5 text-white transition duration-200 outline-none"><?= htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" value="<?= $product['price']; ?>" required class="w-full bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg p-2.5 text-white transition duration-200 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Quantity</label>
                    <input type="number" name="quantity" value="<?= $product['quantity']; ?>" required class="w-full bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg p-2.5 text-white transition duration-200 outline-none">
                </div>
            </div>
            <div class="flex justify-between items-center pt-4">
                <a href="<?= site_url('products'); ?>" class="text-gray-400 hover:text-white transition">Cancel</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-6 py-2.5 rounded-lg transition duration-200 shadow-md hover:shadow-indigo-500/20">Update Product</button>
            </div>
        </form>
    </div>
</body>
</html>