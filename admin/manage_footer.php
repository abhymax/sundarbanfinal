<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';
$messageType = '';

// Handle Delete
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM footer_sections WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "Section deleted successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error deleting section: " . $e->getMessage();
        $messageType = "error";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $type = $_POST['type'];
        $sort_order = (int) $_POST['sort_order'];

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE footer_sections SET title=?, content=?, type=?, sort_order=? WHERE id=?");
            $stmt->execute([$title, $content, $type, $sort_order, $_POST['id']]);
            $message = "Section updated successfully!";
        } else {
            // Insert
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

// Fetch All Sections
$sections = $pdo->query("SELECT * FROM footer_sections ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Footer - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-['Inter']">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Manage Footer</h1>
            <a href="dashboard.php" class="text-blue-600 hover:underline">Back to Dashboard</a>
        </nav>

        <div class="flex-1 p-8 max-w-5xl mx-auto w-full">
            <?php if ($message): ?>
                <div
                    class="p-4 mb-6 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-bold mb-4">Add / Edit Footer Section</h2>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="id" id="section_id">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="title" required
                            class="w-full rounded-lg border-gray-300 border p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="type" class="w-full rounded-lg border-gray-300 border p-2">
                            <option value="text">Text / HTML</option>
                            <option value="links">Links List</option>
                            <option value="contact">Contact Info</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Content (HTML allowed)</label>
                        <textarea name="content" id="content" rows="4" required
                            class="w-full rounded-lg border-gray-300 border p-2 font-mono text-sm"></textarea>
                        <p class="text-xs text-gray-500 mt-1">For 'Links List', use &lt;ul&gt;&lt;li&gt;&lt;a
                            href="..."&gt;Link&lt;/a&gt;&lt;/li&gt;&lt;/ul&gt;</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="0"
                            class="w-full rounded-lg border-gray-300 border p-2">
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-3">
                        <button type="button" onclick="resetForm()"
                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save
                            Section</button>
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($sections as $section): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-900"><?php echo htmlspecialchars($section['title']); ?></h3>
                            <span
                                class="text-xs bg-gray-100 px-2 py-1 rounded uppercase"><?php echo $section['type']; ?></span>
                        </div>
                        <div class="flex-1 text-sm text-gray-600 overflow-hidden mb-4 relative">
                            <div class="max-h-24 overflow-y-auto">
                                <?php echo htmlspecialchars(substr(strip_tags($section['content']), 0, 100)) . '...'; ?>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100 mt-auto">
                            <span class="text-xs text-gray-400">Order: <?php echo $section['sort_order']; ?></span>
                            <div class="flex gap-2">
                                <button onclick='editSection(<?php echo json_encode($section); ?>)'
                                    class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                <a href="?delete=<?php echo $section['id']; ?>" onclick="return confirm('Are you sure?')"
                                    class="text-red-600 hover:text-red-800 text-sm">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
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
</body>

</html>