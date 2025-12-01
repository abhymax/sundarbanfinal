<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

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
                        'safari-green': '#2E4622',
                        'safari-dark': '#051105',
                        'tiger-yellow': '#FFD700',
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
        /* Custom Scrollbar for Sidebar */
        aside::-webkit-scrollbar { width: 4px; }
        aside::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
        
        .nav-item.active {
            background-color: rgba(255, 215, 0, 0.15);
            color: #FFD700;
            border-left: 4px solid #FFD700;
        }
        .nav-item:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

<div class="flex min-h-screen">
    <aside class="w-72 bg-safari-dark text-gray-400 hidden md:flex flex-col border-r border-white/5 shadow-2xl z-20">
        <div class="p-8 border-b border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-tiger-yellow flex items-center justify-center text-safari-dark shadow-[0_0_15px_rgba(255,215,0,0.3)]">
                <span class="material-symbols-outlined">admin_panel_settings</span>
            </div>
            <div>
                <h2 class="text-lg font-bold font-serif text-white tracking-wide">Admin Panel</h2>
                <p class="text-[10px] uppercase tracking-widest text-tiger-yellow">Sundarban Safari</p>
            </div>
        </div>

        <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-2 text-gray-600">Overview</p>
            <a href="dashboard.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>

            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-6 text-gray-600">Tours & Content</p>
            <a href="manage_packages.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo strpos($current_page, 'package') !== false ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">tour</span> Packages
            </a>
            <a href="manage_home_about.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_home_about.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">storefront</span> Who We Are
            </a>
            <a href="edit_home_hero.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'edit_home_hero.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">image</span> Hero Banner
            </a>
            <a href="edit_1day_hero.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'edit_1day_hero.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">edit_calendar</span> 1-Day Hero
            </a>

            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-6 text-gray-600">Data & Settings</p>
            <a href="manage_species.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_species.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">pets</span> Wildlife
            </a>
            <a href="manage_testimonials.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_testimonials.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">reviews</span> Testimonials
            </a>
            <a href="manage_faqs.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_faqs.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">quiz</span> FAQs
            </a>
            <a href="settings.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">settings</span> Settings
            </a>
        </nav>

        <div class="p-4 border-t border-white/10 bg-black/20">
            <a href="logout.php" class="flex items-center justify-center gap-2 w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 py-3 rounded-lg transition">
                <span class="material-symbols-outlined">logout</span> Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 h-screen overflow-y-auto p-6 md:p-10 scroll-smooth">