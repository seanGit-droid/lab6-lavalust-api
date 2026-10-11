<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Product System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-md border border-gray-700">
        <h2 class="text-2xl font-bold text-center mb-6 text-indigo-400">Account Login</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-400 p-3 rounded-lg text-sm mb-4">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('authenticate'); ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Username</label>
                <input type="text" name="username" required class="w-full bg-gray-700 border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-gray-700 border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 font-semibold py-2.5 rounded-lg transition">Login</button>
        </form>
    </div>
</body>
</html>