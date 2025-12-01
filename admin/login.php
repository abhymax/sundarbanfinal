<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php'); exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'sundarban123') {
        $_SESSION['admin_logged_in'] = true; header('Location: dashboard.php'); exit;
    } else { $error = 'Invalid credentials'; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#051105] h-screen flex items-center justify-center relative overflow-hidden">
    
    <div class="absolute top-0 left-0 w-96 h-96 bg-[#2E4622] rounded-full blur-[120px] opacity-30 -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#FFD700] rounded-full blur-[120px] opacity-10 translate-x-1/2 translate-y-1/2"></div>

    <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-10 rounded-2xl shadow-2xl w-full max-w-sm relative z-10">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#FFD700] rounded-full flex items-center justify-center text-[#051105] mx-auto mb-4 text-2xl font-bold shadow-lg shadow-yellow-500/20">
                <span class="font-serif">S</span>
            </div>
            <h1 class="text-3xl font-serif font-bold text-white">Welcome Back</h1>
            <p class="text-gray-400 text-sm mt-2">Sundarban Boat Safari Admin</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-200 px-4 py-3 rounded-lg mb-6 text-sm text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Username</label>
                <input class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-[#FFD700] transition" 
                       name="username" type="text" placeholder="admin" required>
            </div>
            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Password</label>
                <input class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-[#FFD700] transition" 
                       name="password" type="password" placeholder="••••••••" required>
            </div>
            <button class="w-full bg-[#FFD700] hover:bg-yellow-400 text-[#051105] font-bold py-3 rounded-lg transition-transform transform active:scale-95 shadow-lg">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>