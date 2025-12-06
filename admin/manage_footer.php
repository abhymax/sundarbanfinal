<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }

$message = '';

// --- DELETE ---
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM footer_sections WHERE id=?")->execute([$_GET['delete']]);
    header("Location: manage_footer.php"); exit;
}

// --- SAVE (ADD/EDIT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $type = $_POST['type'];
        $sort = $_POST['sort_order'];
        $id = $_POST['id'] ?? '';

        if ($id) {
            $stmt = $pdo->prepare("UPDATE footer_sections SET title=?, content=?, type=?, sort_order=? WHERE id=?");
            $stmt->execute([$title, $content, $type, $sort, $id]);
            $message = "Section updated!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO footer_sections (title, content, type, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $type, $sort]);
            $message = "Section created!";
        }
    } catch (Exception $e) { $message = "Error: " . $e->getMessage(); }
}

$sections = $pdo->query("SELECT * FROM footer_sections ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>

<div class="max-w-6xl mx-auto pb-20">
    <div class="flex justify-between items-end mb-8">
        <div><h1 class="text-4xl font-bold text-safari-dark font-serif">Footer Manager</h1><p class="text-gray-500 mt-2">Customize the footer columns.</p></div>
        <button onclick="openModal()" class="bg-safari-green text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:bg-green-800 transition"><span class="material-symbols-outlined">add</span> Add Section</button>
    </div>

    <?php if($message): ?><div class="p-4 mb-6 bg-green-100 text-green-700 rounded-xl border border-green-400 flex items-center gap-2"><span class="material-symbols-outlined">check_circle</span> <?php echo $message; ?></div><?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($sections as $section): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col h-full hover:shadow-md transition">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-xl text-gray-800"><?php echo htmlspecialchars($section['title']); ?></h3>
                    <span class="text-xs uppercase font-bold bg-gray-100 px-2 py-1 rounded text-gray-500"><?php echo htmlspecialchars($section['type']); ?></span>
                </div>
                <div class="flex-1 bg-gray-50 p-3 rounded-lg mb-4 overflow-hidden max-h-32 text-xs font-mono text-gray-600 relative group">
                    <?php echo htmlspecialchars(substr($section['content'], 0, 150)) . '...'; ?>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-4 mt-auto">
                    <span class="text-xs text-gray-400 font-bold">Order: <?php echo $section['sort_order']; ?></span>
                    <div class="flex gap-2">
                        <button onclick='editSection(<?php echo json_encode($section); ?>)' class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition"><span class="material-symbols-outlined text-sm">edit</span></button>
                        <a href="?delete=<?php echo $section['id']; ?>" onclick="return confirm('Delete?')" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition"><span class="material-symbols-outlined text-sm">delete</span></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all scale-95 opacity-0" id="modalContent">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 id="modalTitle" class="text-2xl font-bold text-safari-dark font-serif">Edit Section</h2>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="id" id="sectId">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Title</label>
                            <input type="text" name="title" id="sectTitle" class="w-full border rounded-lg p-3 font-bold focus:ring-2 focus:ring-safari-green outline-none" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Type</label>
                            <select name="type" id="sectType" class="w-full border rounded-lg p-3 bg-gray-50">
                                <option value="text">Brand/Text (Col 1)</option>
                                <option value="links">Links List (Col 2 & 3)</option>
                                <option value="contact">Contact/Map (Col 4)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Content (HTML Allowed)</label>
                        <textarea name="content" id="sectContent" rows="6" class="w-full border rounded-lg p-3 font-mono text-sm focus:ring-2 focus:ring-safari-green outline-none" required></textarea>
                        <p class="text-xs text-gray-400 mt-1">For links: &lt;ul&gt;&lt;li&gt;&lt;a href="#"&gt;Link Name&lt;/a&gt;&lt;/li&gt;&lt;/ul&gt;</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sectOrder" class="w-full border rounded-lg p-3 w-24">
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-safari-green text-white px-8 py-3 rounded-xl font-bold hover:bg-green-800 shadow-lg transition">Save Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const content = document.getElementById('modalContent');

        function openModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Reset
            document.getElementById('modalTitle').innerText = "Add Footer Section";
            document.getElementById('sectId').value = '';
            document.getElementById('sectTitle').value = '';
            document.getElementById('sectType').value = 'links';
            document.getElementById('sectContent').value = '';
            document.getElementById('sectOrder').value = '0';
        }

        function editSection(data) {
            openModal();
            document.getElementById('modalTitle').innerText = "Edit Footer Section";
            document.getElementById('sectId').value = data.id;
            document.getElementById('sectTitle').value = data.title;
            document.getElementById('sectType').value = data.type;
            document.getElementById('sectContent').value = data.content;
            document.getElementById('sectOrder').value = data.sort_order;
        }

        function closeModal() {
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    </script>
</div>
<?php echo "</main></div></body></html>"; ?>