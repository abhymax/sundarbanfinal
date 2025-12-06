<?php
session_start();
require_once '../db_connect.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch Packages
try {
    $stmt = $pdo->query("SELECT * FROM packages ORDER BY id ASC");
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $packages = [];
    $error = "Error fetching packages: " . $e->getMessage();
}

include 'header.php'; 
?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Packages</h1>
            <p class="text-gray-500 mt-2">Manage your tour itineraries and pricing.</p>
        </div>
        <a href="edit_package.php"
            class="bg-safari-green hover:bg-green-800 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Add New Package
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-600">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">ID</th>
                        <th class="px-6 py-4 font-bold">Image</th>
                        <th class="px-6 py-4 font-bold">Details</th>
                        <th class="px-6 py-4 font-bold">Duration</th>
                        <th class="px-6 py-4 font-bold">Price</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($packages)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400 italic">
                                No packages found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($packages as $pkg): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                    #<?php echo $pkg['id']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                        <img src="../<?php echo htmlspecialchars($pkg['image_url']); ?>" alt="Pkg"
                                            class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-lg font-serif"><?php echo htmlspecialchars($pkg['title']); ?></div>
                                    <div class="text-sm text-gray-500 mt-1 line-clamp-1">
                                        <?php echo htmlspecialchars($pkg['subtitle'] ?? ''); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center gap-1 bg-gray-100 px-2 py-1 rounded text-gray-600">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        <?php echo htmlspecialchars($pkg['duration']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-safari-green">
                                    ₹<?php echo number_format($pkg['price']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <?php if ($pkg['is_popular']): ?>
                                            <span class="inline-block bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide w-fit">Popular</span>
                                        <?php endif; ?>
                                        <?php if ($pkg['is_bestseller']): ?>
                                            <span class="inline-block bg-tiger-yellow/20 text-orange-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide w-fit">Bestseller</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="edit_package.php?id=<?php echo $pkg['id']; ?>"
                                        class="text-safari-green hover:text-green-800 font-bold text-sm inline-flex items-center gap-1 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">
                                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</main>
</div>
</body>
</html>