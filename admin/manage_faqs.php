<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
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
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $question = trim($_POST['question']);
        $answer = trim($_POST['answer']);
        $sort_order = (int) $_POST['sort_order'];

        if (!empty($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE faqs SET question=?, answer=?, sort_order=? WHERE id=?");
            $stmt->execute([$question, $answer, $sort_order, $_POST['id']]);
            $message = "FAQ updated successfully!";
        } else {
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

include 'header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-safari-dark font-serif">FAQ Management</h1>
        <p class="text-gray-500 mt-2">Help your customers with common questions.</p>
    </div>

    <?php if ($message): ?>
        <div class="p-4 mb-6 rounded-xl border <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400'; ?> flex items-center gap-2">
            <span class="material-symbols-outlined"><?php echo $messageType === 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h2 class="text-xl font-bold text-safari-dark mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-tiger-yellow">quiz</span> Add / Edit Question
                </h2>
                
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="id" id="faq_id">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Question</label>
                        <input type="text" name="question" id="question" required
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Answer</label>
                        <textarea name="answer" id="answer" rows="4" required
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="0"
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="resetForm()"
                            class="flex-1 px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition font-bold text-sm">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-safari-green text-white hover:bg-green-800 rounded-xl transition font-bold text-sm shadow-md">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <?php foreach ($faqs as $faq): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg mb-1"><?php echo htmlspecialchars($faq['question']); ?></h3>
                            <p class="text-gray-600 text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                            <span class="text-xs text-gray-400 mt-2 block">Order: <?php echo $faq['sort_order']; ?></span>
                        </div>
                        <div class="flex gap-1 shrink-0 ml-4">
                            <button onclick='editFaq(<?php echo json_encode($faq); ?>)'
                                class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition"><span class="material-symbols-outlined text-sm">edit</span></button>
                            <a href="?delete=<?php echo $faq['id']; ?>" onclick="return confirm('Are you sure?')"
                                class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition"><span class="material-symbols-outlined text-sm">delete</span></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
</div>

</main>
</div>
</body>
</html>