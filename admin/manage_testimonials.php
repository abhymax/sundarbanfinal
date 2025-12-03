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
    $name = $_POST['name'];
    $location = $_POST['location'];
    $rating = $_POST['rating'];
    $text = $_POST['text'];
    $id = $_POST['id'] ?? NULL;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE testimonials SET name=?, location=?, rating=?, text=? WHERE id=?");
        $stmt->execute([$name, $location, $rating, $text, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO testimonials (name, location, rating, text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $location, $rating, $text]);
    }
    header("Location: manage_testimonials.php");
    exit;
}

// Fetch Testimonials
$stmt = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC");
$testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-safari-dark font-serif">Testimonials</h1>
        <p class="text-gray-500 mt-2">What your guests are saying.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h2 class="text-xl font-bold text-safari-dark mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-tiger-yellow">rate_review</span> Add Review
                </h2>
                
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="id" id="tId">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="tName" required
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" id="tLocation" required
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rating</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="rating" id="tRating" min="1" max="5" value="5" required
                                class="w-20 rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                            <span class="text-yellow-500 text-lg">★★★★★</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Review</label>
                        <textarea name="text" id="tText" rows="4" required
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition"></textarea>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="resetForm()"
                            class="flex-1 px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition font-bold text-sm">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-safari-green text-white hover:bg-green-800 rounded-xl transition font-bold text-sm shadow-md">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-bold">User</th>
                            <th class="px-6 py-3 font-bold">Review</th>
                            <th class="px-6 py-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <?php foreach ($testimonials as $t): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900"><?php echo htmlspecialchars($t['name']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($t['location']); ?></div>
                                    <div class="text-yellow-500 text-xs mt-1">
                                        <?php for($i=0; $i<$t['rating']; $i++) echo '★'; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-600 italic">"<?php echo htmlspecialchars($t['text']); ?>"</p>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button onclick='editTestimonial(<?php echo json_encode($t); ?>)'
                                        class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition mr-1"><span class="material-symbols-outlined text-sm">edit</span></button>
                                    <a href="?delete=<?php echo $t['id']; ?>" onclick="return confirm('Are you sure?')"
                                        class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition"><span class="material-symbols-outlined text-sm">delete</span></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function editTestimonial(t) {
            document.getElementById('tId').value = t.id;
            document.getElementById('tName').value = t.name;
            document.getElementById('tLocation').value = t.location;
            document.getElementById('tRating').value = t.rating;
            document.getElementById('tText').value = t.text;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        function resetForm() {
            document.getElementById('tId').value = '';
            document.getElementById('tName').value = '';
            document.getElementById('tLocation').value = '';
            document.getElementById('tRating').value = '5';
            document.getElementById('tText').value = '';
        }
    </script>
</div>

</main>
</div>
</body>
</html>