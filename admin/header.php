<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sundarban Boat Safari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'safari-green': '#2E4622', // Primary Dark Green
                        'safari-dark': '#051105',  // Almost Black
                        'tiger-yellow': '#FFD700', // Accent Yellow
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['"DM Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .nav-item.active {
            background-color: rgba(255, 215, 0, 0.1); /* Yellow tint */
            color: #FFD700; /* Yellow text */
            border-left: 4px solid #FFD700;
        }
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #FFD700;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

<div class="flex min-h-screen">
    <aside class="w-72 bg-safari-dark text-white hidden md:flex flex-col border-r border-white/5">
        <div class="p-8 border-b border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-tiger-yellow flex items-center justify-center text-safari-dark shadow-lg shadow-yellow-500/20">
                <span class="material-symbols-outlined">shield_person</span>
            </div>
            <div>
                <h2 class="text-xl font-bold font-serif tracking-wide">Admin Panel</h2>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Leads Centre</p>
            </div>
        </div>

        <nav class="flex-1 py-6 space-y-1 px-4">
            
            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 mt-2">Main</p>
            
            <a href="dashboard.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-300 <?php echo $current_page == 'dashboard.php' ? 'active' : 'text-gray-300'; ?>">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>

            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 mt-6">Content</p>

            <a href="manage_packages.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-300 <?php echo $current_page == 'manage_packages.php' ? 'active' : 'text-gray-300'; ?>">
                <span class="material-symbols-outlined">tour</span>
                Packages
            </a>
            
            <a href="manage_home_about.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-300 <?php echo $current_page == 'manage_home_about.php' ? 'active' : 'text-gray-300'; ?>">
                <span class="material-symbols-outlined">info</span>
                Who We Are
            </a>

            <a href="edit_home_hero.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-300 <?php echo $current_page == 'edit_home_hero.php' ? 'active' : 'text-gray-300'; ?>">
                <span class="material-symbols-outlined">image</span>
                Hero Section
            </a>

            <a href="manage_species.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-300 <?php echo $current_page == 'manage_species.php' ? 'active' : 'text-gray-300'; ?>">
                <span class="material-symbols-outlined">pets</span>
                Wildlife
            </a>

            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 mt-6">System</p>

            <a href="settings.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-300 <?php echo $current_page == 'settings.php' ? 'active' : 'text-gray-300'; ?>">
                <span class="material-symbols-outlined">settings</span>
                Settings
            </a>
        </nav>

        <div class="p-6 border-t border-white/10 bg-black/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold">AD</div>
                    <div>
                        <p class="text-sm font-bold text-white">Admin</p>
                        <a href="../index.php" target="_blank" class="text-xs text-tiger-yellow hover:underline">View Website</a>
                    </div>
                </div>
                <a href="logout.php" class="text-gray-400 hover:text-red-400 transition">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="md:hidden bg-safari-dark text-white p-4 flex justify-between items-center shadow-md z-20">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-tiger-yellow flex items-center justify-center text-safari-dark">
                    <span class="material-symbols-outlined text-sm">shield_person</span>
                </div>
                <span class="font-bold font-serif">Admin Panel</span>
            </div>
            <button class="text-white">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-10">