<?php
session_start();

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../db_connect.php';

// Fetch Bookings
try {
    $stmt = $pdo->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $bookings = [];
    $error = "Error fetching bookings: " . $e->getMessage();
}

// Fetch Total Bookings Count
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM bookings");
    $total_bookings = $stmt->fetchColumn();
} catch (PDOException $e) {
    $total_bookings = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Sundarban Boat Safari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white min-h-screen hidden md:block">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-orange-500">Admin Panel</h2>
            </div>
            <nav class="mt-6">
                <a href="dashboard.php" class="block py-3 px-6 bg-gray-800 border-l-4 border-orange-500">Dashboard</a>
                <a href="manage_packages.php" class="block py-3 px-6 hover:bg-gray-800">Packages</a>
                <a href="manage_home_about.php" class="block py-3 px-6 hover:bg-gray-800">Who We Are</a>
                <a href="manage_species.php" class="block py-3 px-6 hover:bg-gray-800">Wildlife</a>
                <a href="manage_faqs.php" class="block py-3 px-6 hover:bg-gray-800">FAQs</a>
                <a href="manage_footer.php" class="block py-3 px-6 hover:bg-gray-800">Footer</a>
                <a href="settings.php" class="block py-3 px-6 hover:bg-gray-800">General Settings</a>
                <a href="manage_menu.php" class="block py-3 px-6 hover:bg-gray-800">Manage Menu</a>
                <a href="logout.php" class="block py-3 px-6 hover:bg-red-600 mt-10">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">Welcome, Admin</span>
                    <a href="../index.php" target="_blank" class="text-blue-600 hover:underline">View Site</a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                        <span class="material-symbols-outlined">book_online</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $total_bookings; ?></p>
                    </div>
                </div>
                <!-- Add more stats here if needed -->
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="manage_packages.php"
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-gray-800">Manage Packages</h3>
                        <span
                            class="material-symbols-outlined text-gray-400 group-hover:text-orange-500 transition">arrow_forward</span>
                    </div>
                    <p class="text-sm text-gray-500">Add, edit, or remove tour packages.</p>
                </a>

                <a href="manage_home_about.php"
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-gray-800">Who We Are</h3>
                        <span
                            class="material-symbols-outlined text-gray-400 group-hover:text-orange-500 transition">arrow_forward</span>
                    </div>
                    <p class="text-sm text-gray-500">Update the 'Who We Are' section.</p>
                </a>
                
                 <a href="manage_species.php"
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-gray-800">Manage Wildlife</h3>
                        <span
                            class="material-symbols-outlined text-gray-400 group-hover:text-orange-500 transition">arrow_forward</span>
                    </div>
                    <p class="text-sm text-gray-500">Add or edit featured species.</p>
                </a>
            </div>

            <!-- Recent Bookings Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">Recent Bookings</h3>
                    <a href="#" class="text-sm text-blue-600 hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Customer</th>
                                <th scope="col" class="px-6 py-3">Package</th>
                                <th scope="col" class="px-6 py-3">Pax</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookings)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No bookings found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <?php echo htmlspecialchars($booking['travel_date']); ?>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <?php echo htmlspecialchars($booking['name']); ?><br>
                                            <span
                                                class="text-xs text-gray-400"><?php echo htmlspecialchars($booking['phone']); ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo htmlspecialchars($booking['package_name'] ?? ''); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo htmlspecialchars($booking['adults']); ?> Ad,
                                            <?php echo htmlspecialchars($booking['children']); ?> Ch
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($booking['status'] == 'confirmed'): ?>
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Confirmed</span>
                                            <?php else: ?>
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($booking['status'] != 'confirmed'): ?>
                                                <a href="update_status.php?id=<?php echo $booking['id']; ?>&status=confirmed"
                                                    class="font-medium text-blue-600 hover:underline">Confirm</a>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>