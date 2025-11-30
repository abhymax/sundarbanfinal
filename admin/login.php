<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hardcoded credentials as requested
    if ($username === 'admin' && $password === 'sundarban123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Sundarban Boat Safari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Admin Login</h1>
            <p class="text-gray-500 text-sm mt-2">Sundarban Boat Safari</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Username
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500"
                    id="username" name="username" type="text" placeholder="Enter username" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500"
                    id="password" name="password" type="password" placeholder="******************" required>
            </div>
            <div class="flex items-center justify-between">
                <button
                    class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300"
                    type="submit">
                    Sign In
                </button>
            </div>
        </form>
        <div class="text-center mt-6">
            <a href="../index.php" class="text-sm text-gray-500 hover:text-gray-800">Back to Website</a>
        </div>
    </div>

</body>

</html>