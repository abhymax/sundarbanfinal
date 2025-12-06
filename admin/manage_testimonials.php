<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: manage_testimonials.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $rating = $_POST['rating'] ?? 5;
    $content = $_POST['content'] ?? ''; // Changed 'text' to 'content'
    $id = $_POST['id'] ?? NULL;

    if ($id) {
        // Updated query to use 'content' column
        $stmt = $pdo->prepare("UPDATE testimonials SET name=?, location=?, rating=?, content=? WHERE id=?");
        $stmt->execute([$name, $location, $rating, $content, $id]);
    } else {
        // Updated query to use 'content' column
        $stmt = $pdo->prepare("INSERT INTO testimonials (name, location, rating, content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $location, $rating, $content]);
    }
    header("Location: manage_testimonials.php");
    exit;
}

$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Testimonials</h1>
            <p class="text-gray-500 mt-2">Manage guest reviews.</p>
        </div>
        <button onclick="openModal()" class="bg-safari-green text-white px-6 py-3 rounded-xl font-bold hover:bg-green-800 transition shadow-lg flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Add Review
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-bold">Name</th>
                    <th class="px-6 py-4 font-bold">Location</th>
                    <th class="px-6 py-4 font-bold">Rating</th>
                    <th class="px-6 py-4 font-bold">Review</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                <?php foreach ($testimonials as $t): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900"><?php echo htmlspecialchars($t['name'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-gray-500"><?php echo htmlspecialchars($t['location'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-yellow-500 font-bold"><?php echo $t['rating'] ?? 5; ?> ★</td>
                        <td class="px-6 py-4 text-gray-600 italic truncate max-w-xs"><?php echo htmlspecialchars($t['content'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button onclick='editTestimonial(<?php echo json_encode($t); ?>)' class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition mr-1"><span class="material-symbols-outlined text-sm">edit</span></button>
                            <a href="?delete=<?php echo $t['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition"><span class="material-symbols-outlined text-sm">delete</span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 transform transition-all">
        <h2 id="modalTitle" class="text-2xl font-bold text-safari-dark mb-6 font-serif">Add Testimonial</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="id" id="tId">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                <input type="text" name="name" id="tName" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-safari-green outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Location</label>
                <input type="text" name="location" id="tLocation" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-safari-green outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Rating</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="rating" id="tRating" min="1" max="5" value="5" class="w-20 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-safari-green outline-none" required>
                    <span class="text-yellow-500">★★★★★</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Review</label>
                <textarea name="content" id="tContent" rows="4" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-safari-green outline-none" required></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg transition">Cancel</button>
                <button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold hover:bg-green-800 transition shadow-md">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modal');
    function openModal() {
        modal.classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Add Testimonial';
        document.getElementById('tId').value = '';
        document.getElementById('tName').value = '';
        document.getElementById('tLocation').value = '';
        document.getElementById('tRating').value = '5';
        document.getElementById('tContent').value = '';
    }
    function closeModal() { modal.classList.add('hidden'); }
    function editTestimonial(t) {
        openModal();
        document.getElementById('modalTitle').innerText = 'Edit Testimonial';
        document.getElementById('tId').value = t.id;
        document.getElementById('tName').value = t.name;
        document.getElementById('tLocation').value = t.location || '';
        document.getElementById('tRating').value = t.rating;
        document.getElementById('tContent').value = t.content || ''; // Load 'content'
    }
</script>

<?php echo "</main></div></body></html>"; ?>