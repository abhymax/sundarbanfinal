<?php
// Note: Session check is now in header.php
require_once '../db_connect.php';
include 'header.php'; // USES THE NEW SHARED HEADER

// Fetch Stats
try {
    $total_bookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    $total_packages = $pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
    $recent_bookings = $pdo->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $total_bookings = 0; $total_packages = 0; $recent_bookings = [];
}
?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Dashboard</h1>
            <p class="text-gray-500 mt-2">Welcome back to the Leads Centre.</p>
        </div>
        <div class="text-right">
            <span class="inline-block px-4 py-2 rounded-lg bg-green-100 text-safari-green font-bold text-sm">
                <?php echo date('l, d M Y'); ?>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-tiger-yellow/10 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Inquiries</p>
                <div class="flex items-end gap-3 mt-2">
                    <h2 class="text-4xl font-bold text-safari-dark"><?php echo $total_bookings; ?></h2>
                    <span class="mb-1 text-sm text-green-600 font-medium">+ New</span>
                </div>
            </div>
            <div class="mt-4 w-10 h-10 rounded-full bg-tiger-yellow flex items-center justify-center text-safari-dark shadow-sm">
                <span class="material-symbols-outlined">groups</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-safari-green/10 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Active Packages</p>
                <div class="flex items-end gap-3 mt-2">
                    <h2 class="text-4xl font-bold text-safari-dark"><?php echo $total_packages; ?></h2>
                    <span class="mb-1 text-sm text-gray-400 font-medium">Live</span>
                </div>
            </div>
            <div class="mt-4 w-10 h-10 rounded-full bg-safari-green flex items-center justify-center text-white shadow-sm">
                <span class="material-symbols-outlined">tour</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-safari-dark flex items-center gap-2">
                <span class="material-symbols-outlined text-tiger-yellow">list_alt</span> Recent Leads
            </h3>
            <button class="text-sm text-safari-green font-bold hover:underline">View All</button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Date</th>
                        <th class="px-6 py-4 font-bold">Customer</th>
                        <th class="px-6 py-4 font-bold">Package</th>
                        <th class="px-6 py-4 font-bold">Travelers</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php if (empty($recent_bookings)): ?>
                        <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">No inquiries received yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recent_bookings as $b): ?>
                            <tr class="hover:bg-yellow-50/50 transition-colors group">
                                <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                    <?php echo date('d M, Y', strtotime($b['travel_date'])); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900"><?php echo htmlspecialchars($b['name']); ?></div>
                                    <div class="text-xs text-gray-500 font-mono"><?php echo htmlspecialchars($b['phone']); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold border border-blue-100">
                                        <?php echo htmlspecialchars($b['package_name']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span class="font-bold"><?php echo $b['adults']; ?></span> Adults 
                                    <?php if($b['children'] > 0): ?> + <?php echo $b['children']; ?> Kids<?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($b['status'] == 'confirmed'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span> Confirmed
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span> Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?php if ($b['status'] != 'confirmed'): ?>
                                        <a href="update_status.php?id=<?php echo $b['id']; ?>&status=confirmed" 
                                           class="inline-block p-2 rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-green-600 hover:border-green-600 shadow-sm transition" title="Mark Confirmed">
                                            <span class="material-symbols-outlined text-lg">check</span>
                                        </a>
                                    <?php endif; ?>
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