<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: manage_menu.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = $_POST['label'];
    $subtitle = $_POST['subtitle'] ?? '';
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

$stmt = $pdo->query("SELECT * FROM menus ORDER BY sort_order ASC");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
$parent_menus = array_filter($menus, function ($m) { return $m['parent_id'] === NULL; });

include 'header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Menu Structure</h1>
            <p class="text-gray-500 mt-2">Organize your website navigation.</p>
        </div>
        <button onclick="openModal()" class="bg-safari-green hover:bg-green-800 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Add Menu Item
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-bold">Label</th>
                    <th class="px-6 py-4 font-bold">Subtitle</th>
                    <th class="px-6 py-4 font-bold">Link</th>
                    <th class="px-6 py-4 font-bold">Parent</th>
                    <th class="px-6 py-4 font-bold">Order</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                <?php foreach ($menus as $menu): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-800"><?php echo htmlspecialchars($menu['label']); ?></td>
                        <td class="px-6 py-4 text-gray-500"><?php echo htmlspecialchars($menu['subtitle'] ?? '-'); ?></td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-xs"><?php echo htmlspecialchars($menu['link']); ?></td>
                        <td class="px-6 py-4 text-gray-600">
                            <?php
                            if ($menu['parent_id']) {
                                $key = array_search($menu['parent_id'], array_column($menus, 'id'));
                                echo '<span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold">' . htmlspecialchars($menus[$key]['label']) . '</span>';
                            } else { echo '<span class="text-gray-300">-</span>'; }
                            ?>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-mono"><?php echo $menu['sort_order']; ?></td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button onclick='editMenu(<?php echo json_encode($menu); ?>)' class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition mr-1"><span class="material-symbols-outlined text-sm">edit</span></button>
                            <a href="?delete=<?php echo $menu['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition"><span class="material-symbols-outlined text-sm">delete</span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="menuModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md transform transition-all scale-95 opacity-0" id="modalContent">
        <h2 id="modalTitle" class="text-2xl font-bold text-safari-dark mb-6 font-serif">Add Menu Item</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="id" id="menuId">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Label</label>
                <input type="text" name="label" id="menuLabel" required
                    class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Subtitle (Optional)</label>
                <input type="text" name="subtitle" id="menuSubtitle" placeholder="e.g. Express Day Trip"
                    class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Link</label>
                <input type="text" name="link" id="menuLink" required
                    class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Parent Menu</label>
                <select name="parent_id" id="menuParent" class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                    <option value="">None (Top Level)</option>
                    <?php foreach ($parent_menus as $pm): ?>
                        <option value="<?php echo $pm['id']; ?>"><?php echo htmlspecialchars($pm['label']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="menuOrder" value="0"
                    class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg transition">Cancel</button>
                <button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold hover:bg-green-800 transition shadow-md">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('menuModal');
    const modalContent = document.getElementById('modalContent');

    function openModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        document.getElementById('modalTitle').innerText = 'Add Menu Item';
        document.getElementById('menuId').value = '';
        document.getElementById('menuLabel').value = '';
        document.getElementById('menuSubtitle').value = '';
        document.getElementById('menuLink').value = '';
        document.getElementById('menuParent').value = '';
        document.getElementById('menuOrder').value = '0';
    }

    function closeModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function editMenu(menu) {
        openModal();
        document.getElementById('modalTitle').innerText = 'Edit Menu Item';
        document.getElementById('menuId').value = menu.id;
        document.getElementById('menuLabel').value = menu.label;
        document.getElementById('menuSubtitle').value = menu.subtitle || '';
        document.getElementById('menuLink').value = menu.link;
        document.getElementById('menuParent').value = menu.parent_id || '';
        document.getElementById('menuOrder').value = menu.sort_order;
    }
</script>

</main>
</div>
</body>
</html>