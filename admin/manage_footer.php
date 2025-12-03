<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$messageType = '';

// Handle Delete/Edit logic kept same as original...
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM footer_sections WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "Section deleted successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $type = $_POST['type'];
        $sort_order = (int) $_POST['sort_order'];

        if (!empty($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE footer_sections SET title=?, content=?, type=?, sort_order=? WHERE id=?");
            $stmt->execute([$title, $content, $type, $sort_order, $_POST['id']]);
            $message = "Section updated successfully!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO footer_sections (title, content, type, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $type, $sort_order]);
            $message = "Section added successfully!";
        }
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

$sections = $pdo->query("SELECT * FROM footer_sections ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-safari-dark font-serif">Footer Manager</h1>
        <p class="text-gray-500 mt-2">Customize the footer columns.</p>
    </div>

    <?php if ($message): ?>
        <div class="p-4 mb-6 rounded-xl border <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400'; ?> flex items-center gap-2">
            <span class="material-symbols-outlined"><?php echo $messageType === 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-10">
        <h2 class="text-xl font-bold text-safari-dark mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-tiger-yellow">view_column</span> Add / Edit Section
        </h2>
        
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="id" id="section_id">

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Title</label>
                <input type="text" name="title" id="title" required
                    class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Type</label>
                <select name="type" id="type" class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                    <option value="text">Text / HTML</option>
                    <option value="links">Links List</option>
                    <option value="contact">Contact Info</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-1">Content (HTML)</label>
                <textarea name="content" id="content" rows="4" required
                    class="w-full rounded-lg border-gray-300 border p-2 font-mono text-sm focus:ring-2 focus:ring-safari-green outline-none transition"></textarea>
                <p class="text-xs text-gray-500 mt-1">For Links: &lt;ul&gt;&lt;li&gt;&lt;a href="..."&gt;Link&lt;/a&gt;&lt;/li&gt;&lt;/ul&gt;</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="0"
                    class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
            </div>

            <div class="md:col-span-2 flex justify-end gap-3 border-t pt-4 border-gray-100">
                <button type="button" onclick="resetForm()"
                    class="px-6 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition font-bold">Cancel</button>
                <button type="submit" class="px-8 py-2 bg-safari-green text-white hover:bg-green-800 rounded-xl transition font-bold shadow-md">Save Section</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($sections as $section): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 flex flex-col h-full hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-bold text-gray-900 text-lg"><?php echo htmlspecialchars($section['title']); ?></h3>
                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-1 rounded uppercase tracking-wider font-bold"><?php echo $section['type']; ?></span>
                </div>
                <div class="flex-1 text-sm text-gray-600 overflow-hidden mb-4 relative bg-gray-50 p-3 rounded-lg font-mono text-xs">
                    <div class="line-clamp-4">
                        <?php echo htmlspecialchars(substr(strip_tags($section['content']), 0, 150)); ?>...
                    </div>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-gray-100 mt-auto">
                    <span class="text-xs text-gray-400">Order: <?php echo $section['sort_order']; ?></span>
                    <div class="flex gap-2">
                        <button onclick='editSection(<?php echo json_encode($section); ?>)'
                            class="text-blue-600 hover:text-blue-800 text-sm font-bold">Edit</button>
                        <a href="?delete=<?php echo $section['id']; ?>" onclick="return confirm('Are you sure?')"
                            class="text-red-600 hover:text-red-800 text-sm font-bold">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function editSection(data) {
            document.getElementById('section_id').value = data.id;
            document.getElementById('title').value = data.title;
            document.getElementById('content').value = data.content;
            document.getElementById('type').value = data.type;
            document.getElementById('sort_order').value = data.sort_order;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        function resetForm() {
            document.getElementById('section_id').value = '';
            document.getElementById('title').value = '';
            document.getElementById('content').value = '';
            document.getElementById('type').value = 'text';
            document.getElementById('sort_order').value = '0';
        }
    </script>
</div>

</main>
</div>
</body>
</html>