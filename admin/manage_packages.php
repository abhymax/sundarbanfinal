<?php
session_start();

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../db_connect.php';

// Fetch Packages
try {
    $stmt = $pdo->query("SELECT * FROM packages ORDER BY id ASC");
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $packages = [];
    $error = "Error fetching packages: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="dashboard.php" class="flex items-center text-gray-500 hover:text-gray-700 mr-4">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Manage Packages</h1>
                </div>
                <div class="flex items-center">
                    <a href="edit_package.php"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                        <span class="material-symbols-outlined text-sm mr-1">add</span> Add New
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Image</th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Duration</th>
                            <th scope="col" class="px-6 py-3">Price</th>
                            <th scope="col" class="px-6 py-3">Badges</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($packages)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No packages found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($packages as $pkg): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        #<?php echo $pkg['id']; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <img src="../<?php echo htmlspecialchars($pkg['image_url']); ?>" alt="Package Image"
                                            class="w-12 h-12 rounded object-cover">
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        <?php echo htmlspecialchars($pkg['title']); ?>
                                        <div class="text-xs text-gray-400">
                                            <?php echo htmlspecialchars($pkg['subtitle'] ?? ''); ?>
                                        </div>
                                    </td>
                                    <?php echo htmlspecialchars($pkg['duration']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        ₹<?php echo number_format($pkg['price']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($pkg['is_popular']): ?>
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-1">Popular</span>
                                        <?php endif; ?>
                                        <?php if ($pkg['is_bestseller']): ?>
                                            <span
                                                class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">Bestseller</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="edit_package.php?id=<?php echo $pkg['id']; ?>"
                                            class="font-medium text-blue-600 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>