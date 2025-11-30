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
        $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "FAQ deleted successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error deleting FAQ: " . $e->getMessage();
        $messageType = "error";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $question = trim($_POST['question']);
        $answer = trim($_POST['answer']);
        $sort_order = (int) $_POST['sort_order'];

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE faqs SET question=?, answer=?, sort_order=? WHERE id=?");
            $stmt->execute([$question, $answer, $sort_order, $_POST['id']]);
            $message = "FAQ updated successfully!";
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO faqs (question, answer, sort_order) VALUES (?, ?, ?)");
            $stmt->execute([$question, $answer, $sort_order]);
            $message = "FAQ added successfully!";
        }
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// Fetch All FAQs
$faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage FAQs - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-['Inter']">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Manage FAQs</h1>
            <a href="dashboard.php" class="text-blue-600 hover:underline">Back to Dashboard</a>
        </nav>

        <div class="flex-1 p-8 max-w-4xl mx-auto w-full">
            <?php if ($message): ?>
                <div
                    class="p-4 mb-6 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-bold mb-4">Add / Edit FAQ</h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="id" id="faq_id">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                        <input type="text" name="question" id="question" required
                            class="w-full rounded-lg border-gray-300 border p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                        <textarea name="answer" id="answer" rows="3" required
                            class="w-full rounded-lg border-gray-300 border p-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="0"
                            class="w-full rounded-lg border-gray-300 border p-2">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="resetForm()"
                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save
                            FAQ</button>
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="space-y-4">
                <?php foreach ($faqs as $faq): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-900"><?php echo htmlspecialchars($faq['question']); ?></h3>
                            <p class="text-gray-600 mt-1 text-sm"><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                            <span class="text-xs text-gray-400 mt-2 block">Order: <?php echo $faq['sort_order']; ?></span>
                        </div>
                        <div class="flex gap-2 shrink-0 ml-4">
                            <button onclick='editFaq(<?php echo json_encode($faq); ?>)'
                                class="text-blue-600 hover:bg-blue-50 p-2 rounded">Edit</button>
                            <a href="?delete=<?php echo $faq['id']; ?>" onclick="return confirm('Are you sure?')"
                                class="text-red-600 hover:bg-red-50 p-2 rounded">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        function editFaq(data) {
            document.getElementById('faq_id').value = data.id;
            document.getElementById('question').value = data.question;
            document.getElementById('answer').value = data.answer;
            document.getElementById('sort_order').value = data.sort_order;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            document.getElementById('faq_id').value = '';
            document.getElementById('question').value = '';
            document.getElementById('answer').value = '';
            document.getElementById('sort_order').value = '0';
        }
    </script>
</body>

</html>