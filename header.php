<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connect.php';

// Fetch Settings
try {
    $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$settings) {
        throw new Exception("No settings found");
    }
} catch (Exception $e) {
    // Fallback settings if DB fails
    $settings = [
        'site_name' => 'Sundarban Boat Safari',
        'logo_text' => 'Sundarban',
        'sub_text' => 'Boat Safari',
        'logo_url' => '',
        'second_logo_url' => '',
        'phone' => '+91 97756 06350',
        'email' => 'info@sundarbanboatsafari.com',
        'address' => '123, Canning Road, Kolkata - 700001, WB',
        'facebook_url' => '#',
        'instagram_url' => '#',
        'youtube_url' => '#',
        'whatsapp_number' => '+91 97756 06350'
    ];
}

// Fetch Menus
try {
    $stmt = $pdo->query("SELECT * FROM menus ORDER BY sort_order ASC");
    $all_menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize into tree
    $menu_tree = [];
    foreach ($all_menus as $menu) {
        if ($menu['parent_id'] === NULL) {
            $menu['children'] = [];
            $menu_tree[$menu['id']] = $menu;
        }
    }
    foreach ($all_menus as $menu) {
        if ($menu['parent_id'] !== NULL && isset($menu_tree[$menu['parent_id']])) {
            $menu_tree[$menu['parent_id']]['children'][] = $menu;
        }
    }
} catch (PDOException $e) {
    $menu_tree = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo htmlspecialchars($settings['site_name']); ?> | Home</title>
    <?php if (!empty($settings['favicon_url'])): ?>
        <link rel="icon" href="<?php echo htmlspecialchars($settings['favicon_url']); ?>" type="image/x-icon">
    <?php endif; ?>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'tiger-orange': '#FFD700', // Bright Yellow
                        'safari-green': '#2E4622', // Deep Forest Green
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <link rel="stylesheet" href="style.css">
    <style>
        /* Custom Social Icon Hover Effects */
        .social-icon:hover path {
            fill: #f97316;
            /* tiger-orange */
            transition: fill 0.3s ease;
        }
    </style>
</head>

<body class="antialiased">

    <!-- Top Bar -->
    <div class="bg-safari-green text-white py-2 border-b border-white/10 hidden md:block relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-sm">
            <div class="flex items-center gap-6">
                <a href="tel:<?php echo htmlspecialchars($settings['phone']); ?>"
                    class="flex items-center gap-2 hover:text-tiger-orange transition-colors">
                    <span class="material-symbols-outlined text-base text-tiger-orange">call</span>
                    <span class="tracking-wide"><?php echo htmlspecialchars($settings['phone']); ?></span>
                </a>
                <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>"
                    class="flex items-center gap-2 hover:text-tiger-orange transition-colors">
                    <span class="material-symbols-outlined text-base text-tiger-orange">mail</span>
                    <span class="tracking-wide"><?php echo htmlspecialchars($settings['email']); ?></span>
                </a>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-gray-300 text-xs uppercase tracking-widest">Follow Us:</span>

                <!-- Facebook -->
                <?php if (!empty($settings['facebook_url']) && $settings['facebook_url'] != '#'): ?>
                    <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank"
                        class="social-icon group">
                        <svg class="w-4 h-4 fill-white group-hover:fill-tiger-orange transition-colors" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z" />
                        </svg>
                    </a>
                <?php endif; ?>

                <!-- Instagram -->
                <?php if (!empty($settings['instagram_url']) && $settings['instagram_url'] != '#'): ?>
                    <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank"
                        class="social-icon group">
                        <svg class="w-4 h-4 fill-white group-hover:fill-tiger-orange transition-colors" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z" />
                        </svg>
                    </a>
                <?php endif; ?>

                <!-- YouTube -->
                <?php if (!empty($settings['youtube_url']) && $settings['youtube_url'] != '#'): ?>
                    <a href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank"
                        class="social-icon group">
                        <svg class="w-5 h-5 fill-white group-hover:fill-tiger-orange transition-colors" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10,15L15.19,12L10,9V15M21.56,7.17C21.69,7.64 21.78,8.27 21.84,9.07C21.91,9.87 21.94,10.56 21.94,11.16L22,12C22,14.19 21.84,15.8 21.56,16.83C21.31,17.73 20.73,18.31 19.83,18.56C19.36,18.69 18.5,18.78 17.18,18.84C15.88,18.91 14.69,18.94 13.59,18.94L12,19C7.81,19 5.2,18.84 4.17,18.56C3.27,18.31 2.69,17.73 2.44,16.83C2.31,16.36 2.22,15.73 2.16,14.93C2.09,14.13 2.06,13.44 2.06,12.84L2,12C2,9.81 2.16,8.2 2.44,7.17C2.69,6.27 3.27,5.69 4.17,5.44C4.64,5.31 5.5,5.22 6.82,5.16C8.12,5.09 9.31,5.06 10.41,5.06L12,5C16.19,5 18.8,5.16 19.83,5.44C20.73,5.69 21.31,6.27 21.56,7.17Z" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <nav class="fixed w-full z-40 transition-all duration-300 border-b bg-white/10 backdrop-blur-md border-white/10 top-0 md:top-[40px]"
        id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <!-- Logo -->
                <div class="flex items-center gap-2 group cursor-pointer" onclick="window.location.href='index.php'">
                    <?php if (!empty($settings['logo_url'])): ?>
                        <img src="<?php echo htmlspecialchars($settings['logo_url']); ?>"
                            alt="<?php echo htmlspecialchars($settings['site_name']); ?>"
                            class="h-16 w-auto object-contain transition-transform group-hover:scale-105">
                    <?php else: ?>
                        <div
                            class="w-10 h-10 bg-tiger-orange rounded-full flex items-center justify-center text-black shadow-lg group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">directions_boat</span>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="font-serif text-2xl font-bold leading-none tracking-wide text-white group-hover:text-tiger-orange transition-colors"
                                id="nav-logo-text"><?php echo htmlspecialchars($settings['logo_text']); ?></span>
                            <span class="text-xs uppercase tracking-[0.2em] font-bold text-gray-200"
                                id="nav-sub-text"><?php echo htmlspecialchars($settings['sub_text']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden md:flex space-x-8 items-center">
                    <?php foreach ($menu_tree as $menu): ?>
                        <?php if (empty($menu['children'])): ?>
                            <a class="nav-link text-white hover:text-tiger-orange transition font-medium tracking-wide text-sm uppercase"
                                href="<?php echo htmlspecialchars($menu['link']); ?>"><?php echo htmlspecialchars($menu['label']); ?></a>
                        <?php else: ?>
                            <div class="relative group h-full flex items-center">
                                <button
                                    class="nav-link text-white group-hover:text-tiger-orange transition font-medium tracking-wide text-sm uppercase flex items-center gap-1 focus:outline-none">
                                    <?php echo htmlspecialchars($menu['label']); ?>
                                    <span class="material-symbols-outlined text-sm">expand_more</span>
                                </button>
                                <div class="nav-dropdown absolute top-full left-0 pt-4 w-72">
                                    <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100">
                                        <?php foreach ($menu['children'] as $child): ?>
                                            <a class="block px-6 py-4 text-sm text-gray-800 hover:bg-orange-50 hover:text-tiger-orange border-b border-gray-100 transition"
                                                href="<?php echo htmlspecialchars($child['link']); ?>">
                                                <span
                                                    class="font-bold block"><?php echo htmlspecialchars($child['label']); ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Right Side Logo (Dynamic) -->
                <div class="hidden md:block ml-4">
                    <?php if (!empty($settings['second_logo_url'])): ?>
                        <img src="<?php echo htmlspecialchars($settings['second_logo_url']); ?>" alt="Partner Logo"
                            class="h-16 w-auto object-contain transition-transform group-hover:scale-105">
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button class="text-safari-green"
                        onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <span class="material-symbols-outlined text-3xl">menu</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="hidden md:hidden bg-white absolute w-full border-t border-gray-100 shadow-xl h-screen overflow-y-auto"
            id="mobile-menu">
            <div class="px-4 py-6 space-y-4">
                <?php foreach ($menu_tree as $menu): ?>
                    <?php if (empty($menu['children'])): ?>
                        <a class="block text-safari-green font-medium hover:text-tiger-orange text-lg"
                            href="<?php echo htmlspecialchars($menu['link']); ?>"><?php echo htmlspecialchars($menu['label']); ?></a>
                    <?php else: ?>
                        <div class="border-l-2 border-gray-200 pl-4 space-y-3">
                            <p class="text-tiger-orange font-bold uppercase text-xs tracking-wider mb-2">
                                <?php echo htmlspecialchars($menu['label']); ?>
                            </p>
                            <?php foreach ($menu['children'] as $child): ?>
                                <a class="block text-gray-600 hover:text-safari-green"
                                    href="<?php echo htmlspecialchars($child['link']); ?>"><?php echo htmlspecialchars($child['label']); ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <button class="w-full bg-tiger-orange text-black py-4 rounded-lg font-bold mt-4"
                    onclick="openBooking()">Book Your Trip</button>
            </div>
        </div>
    </nav>