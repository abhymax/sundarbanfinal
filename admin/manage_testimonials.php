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
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Testimonials | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white min-h-screen hidden md:block">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-orange-500">Admin Panel</h2>
            </div>
            <nav class="mt-6">
                <a href="dashboard.php" class="block py-3 px-6 hover:bg-gray-800">Dashboard</a>
                <a href="manage_testimonials.php"
                    class="block py-3 px-6 bg-gray-800 border-l-4 border-orange-500">Testimonials</a>
                <a href="manage_packages.php" class="block py-3 px-6 hover:bg-gray-800">Packages</a>
                <a href="settings.php" class="block py-3 px-6 hover:bg-gray-800">General Settings</a>
                <a href="manage_menu.php" class="block py-3 px-6 hover:bg-gray-800">Manage Menu</a>
                <a href="logout.php" class="block py-3 px-6 hover:bg-red-600 mt-10">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Manage Testimonials</h1>
                <button onclick="openModal()"
                    class="bg-orange-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-orange-700">
                    + Add Testimonial
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="p-4 font-bold text-gray-600">Name</th>
                            <th class="p-4 font-bold text-gray-600">Location</th>
                            <th class="p-4 font-bold text-gray-600">Rating</th>
                            <th class="p-4 font-bold text-gray-600">Review</th>
                            <th class="p-4 font-bold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($testimonials as $t): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4 font-medium text-gray-800"><?php echo htmlspecialchars($t['name']); ?></td>
                                <td class="p-4 text-gray-600 text-sm"><?php echo htmlspecialchars($t['location']); ?></td>
                                <td class="p-4 text-yellow-500 font-bold"><?php echo $t['rating']; ?> ★</td>
                                <td class="p-4 text-gray-600 text-sm max-w-xs truncate">
                                    <?php echo htmlspecialchars($t['text']); ?></td>
                                <td class="p-4 text-right space-x-2">
                                    <button onclick='editTestimonial(<?php echo json_encode($t); ?>)'
                                        class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                    <a href="?delete=<?php echo $t['id']; ?>" onclick="return confirm('Are you sure?')"
                                        class="text-red-600 hover:text-red-800 font-medium">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
            <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-gray-800">Add Testimonial</h2>
            <form method="POST">
                <input type="hidden" name="id" id="tId">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="tName" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" id="tLocation" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Rating (1-5)</label>
                    <input type="number" name="rating" id="tRating" min="1" max="5"
                        class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Review Text</label>
                    <textarea name="text" id="tText" rows="4" class="w-full border rounded px-3 py-2"
                        required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit"
                        class="bg-orange-600 text-white px-4 py-2 rounded font-bold hover:bg-orange-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
            document.getElementById('modalTitle').innerText = 'Add Testimonial';
            document.getElementById('tId').value = '';
            document.getElementById('tName').value = '';
            document.getElementById('tLocation').value = '';
            document.getElementById('tRating').value = '5';
            document.getElementById('tText').value = '';
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('modal').classList.remove('flex');
        }

        function editTestimonial(t) {
            openModal();
            document.getElementById('modalTitle').innerText = 'Edit Testimonial';
            document.getElementById('tId').value = t.id;
            document.getElementById('tName').value = t.name;
            document.getElementById('tLocation').value = t.location;
            document.getElementById('tRating').value = t.rating;
            document.getElementById('tText').value = t.text;
        }
    </script>
</body>

</html>