<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get current page file name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sundarban Boat Safari</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🛡️</text></svg>">

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
        /* Sidebar Active State Styling */
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
    <aside class="w-72 bg-safari-dark text-gray-400 hidden md:flex flex-col border-r border-white/5 shadow-2xl z-20 h-screen sticky top-0">
        <div class="p-8 border-b border-white/10 flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 rounded-xl bg-tiger-yellow flex items-center justify-center text-safari-dark shadow-[0_0_15px_rgba(255,215,0,0.3)]">
                <span class="material-symbols-outlined">shield_person</span>
            </div>
            <div>
                <h2 class="text-lg font-bold font-serif text-white tracking-wide">Admin Panel</h2>
                <p class="text-[10px] uppercase tracking-widest text-tiger-yellow">Leads Centre</p>
            </div>
        </div>

        <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto">
            
            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-2 text-gray-600">Overview</p>
            <a href="dashboard.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            
            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-6 text-gray-600">Inquiries</p>
            <a href="view_messages.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'view_messages.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">mail</span> Inbox
            </a>
            <a href="manage_contact.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_contact.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">contact_page</span> Contact Page
            </a>

            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-6 text-gray-600">Tours & Content</p>
            <a href="manage_packages.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_packages.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">tour</span> All Packages
            </a>
            <a href="manage_1day_tour.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_1day_tour.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">edit_calendar</span> 1-Day Tour
            </a>
            <a href="manage_1n2d_tour.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_1n2d_tour.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">nights_stay</span> 1-Night 2-Days
            </a>
            <a href="manage_2n3d_tour.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_2n3d_tour.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">date_range</span> 2-Nights 3-Days
            </a>
            
            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-6 text-gray-600">Page Content</p>
            <a href="edit_home_hero.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'edit_home_hero.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">image</span> Home Hero
            </a>
            <a href="manage_home_about.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_home_about.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">storefront</span> Home "Who We Are"
            </a>
            <a href="manage_about.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_about.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">info</span> About Page
            </a>
            <a href="manage_gallery.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_gallery.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">perm_media</span> Gallery
            </a>

            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-6 text-gray-600">Site Modules</p>
            <a href="manage_menu.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_menu.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">menu</span> Menu Structure
            </a>
            <a href="manage_footer.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_footer.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">view_column</span> Footer Manager
            </a>
            <a href="manage_species.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_species.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">pets</span> Wildlife
            </a>
            <a href="manage_testimonials.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_testimonials.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">rate_review</span> Testimonials
            </a>
            <a href="manage_faqs.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'manage_faqs.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">quiz</span> FAQs
            </a>

            <p class="px-4 text-[10px] font-bold uppercase tracking-widest mb-2 mt-6 text-gray-600">System</p>
            <a href="settings.php" class="nav-item flex items-center gap-3 py-3 px-4 rounded-lg transition-all duration-200 <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">settings</span> General Settings
            </a>
        </nav>

        <div class="p-4 border-t border-white/10 bg-black/20 shrink-0">
            <a href="logout.php" class="flex items-center justify-center gap-2 w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 py-3 rounded-lg transition">
                <span class="material-symbols-outlined text-sm">logout</span> Logout
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="md:hidden bg-safari-dark text-white p-4 flex justify-between items-center shadow-md z-20">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-tiger-yellow flex items-center justify-center text-safari-dark">
                    <span class="material-symbols-outlined text-sm">shield_person</span>
                </div>
                <span class="font-bold font-serif">Admin</span>
            </div>
            <button onclick="document.querySelector('aside').classList.toggle('hidden'); document.querySelector('aside').classList.toggle('fixed'); document.querySelector('aside').classList.toggle('z-50');" class="text-white">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-10">