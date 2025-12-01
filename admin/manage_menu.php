<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_menu.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = $_POST['label'];
    $subtitle = $_POST['subtitle'] ?? ''; // New Field
    $link = $_POST['link'];
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : NULL;
    $sort_order = $_POST['sort_order'];
    $id = $_POST['id'] ?? NULL;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE menus SET label=?, subtitle=?, link=?, parent_id=?, sort_order=? WHERE id=?");
        $stmt->execute([$label, $subtitle, $link, $parent_id, $sort_order, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO menus (label, subtitle, link, parent_id, sort_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$label, $subtitle, $link, $parent_id, $sort_order]);
    }
    header("Location: manage_menu.php");
    exit;
}

// Fetch Menus
$stmt = $pdo->query("SELECT * FROM menus ORDER BY sort_order ASC");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get Parent Menus
$parent_menus = array_filter($menus, function ($m) { return $m['parent_id'] === NULL; });
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <?php include 'header.php'; ?> 

        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Manage Menu</h1>
                <button onclick="openModal()" class="bg-orange-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-orange-700">+ Add Menu Item</button>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="p-4 font-bold text-gray-600">Label</th>
                            <th class="p-4 font-bold text-gray-600">Subtitle</th> <th class="p-4 font-bold text-gray-600">Link</th>
                            <th class="p-4 font-bold text-gray-600">Parent</th>
                            <th class="p-4 font-bold text-gray-600">Order</th>
                            <th class="p-4 font-bold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $menu): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4 font-medium text-gray-800"><?php echo htmlspecialchars($menu['label']); ?></td>
                                <td class="p-4 text-sm text-gray-500"><?php echo htmlspecialchars($menu['subtitle'] ?? '-'); ?></td>
                                <td class="p-4 text-gray-600 text-sm"><?php echo htmlspecialchars($menu['link']); ?></td>
                                <td class="p-4 text-gray-600 text-sm">
                                    <?php
                                    if ($menu['parent_id']) {
                                        $key = array_search($menu['parent_id'], array_column($menus, 'id'));
                                        echo htmlspecialchars($menus[$key]['label']);
                                    } else { echo '-'; }
                                    ?>
                                </td>
                                <td class="p-4 text-gray-600"><?php echo $menu['sort_order']; ?></td>
                                <td class="p-4 text-right space-x-2">
                                    <button onclick='editMenu(<?php echo json_encode($menu); ?>)' class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                    <a href="?delete=<?php echo $menu['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 font-medium">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="menuModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
            <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-gray-800">Add Menu Item</h2>
            <form method="POST">
                <input type="hidden" name="id" id="menuId">
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Label</label>
                    <input type="text" name="label" id="menuLabel" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Subtitle (Optional)</label>
                    <input type="text" name="subtitle" id="menuSubtitle" placeholder="e.g. Express Day Trip" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Link</label>
                    <input type="text" name="link" id="menuLink" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Parent Menu</label>
                    <select name="parent_id" id="menuParent" class="w-full border rounded px-3 py-2">
                        <option value="">None (Top Level)</option>
                        <?php foreach ($parent_menus as $pm): ?>
                            <option value="<?php echo $pm['id']; ?>"><?php echo htmlspecialchars($pm['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="menuOrder" value="0" class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded font-bold hover:bg-orange-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('menuModal').classList.remove('hidden');
            document.getElementById('menuModal').classList.add('flex');
            document.getElementById('modalTitle').innerText = 'Add Menu Item';
            document.getElementById('menuId').value = '';
            document.getElementById('menuLabel').value = '';
            document.getElementById('menuSubtitle').value = ''; // Clear subtitle
            document.getElementById('menuLink').value = '';
            document.getElementById('menuParent').value = '';
            document.getElementById('menuOrder').value = '0';
        }

        function closeModal() {
            document.getElementById('menuModal').classList.add('hidden');
            document.getElementById('menuModal').classList.remove('flex');
        }

        function editMenu(menu) {
            openModal();
            document.getElementById('modalTitle').innerText = 'Edit Menu Item';
            document.getElementById('menuId').value = menu.id;
            document.getElementById('menuLabel').value = menu.label;
            document.getElementById('menuSubtitle').value = menu.subtitle || ''; // Set subtitle
            document.getElementById('menuLink').value = menu.link;
            document.getElementById('menuParent').value = menu.parent_id || '';
            document.getElementById('menuOrder').value = menu.sort_order;
        }
    </script>
</body>
</html>