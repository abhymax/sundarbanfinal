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
        $stmt = $pdo->prepare("DELETE FROM species WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "Species deleted successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error deleting species: " . $e->getMessage();
        $messageType = "error";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $sort_order = (int) $_POST['sort_order'];
        $image_url = $_POST['current_image_url'] ?? '';

        // Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0755, true);

            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowed)) {
                $newFile = 'species_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFile)) {
                    $image_url = 'uploads/' . $newFile;
                }
            }
        }

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Update
            $stmt = $pdo->prepare("UPDATE species SET name=?, description=?, image_url=?, is_featured_on_home=?, sort_order=? WHERE id=?");
            $stmt->execute([$name, $description, $image_url, $is_featured, $sort_order, $_POST['id']]);
            $message = "Species updated successfully!";
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO species (name, description, image_url, is_featured_on_home, sort_order) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $image_url, $is_featured, $sort_order]);
            $message = "Species added successfully!";
        }
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// Fetch All Species
$species_list = $pdo->query("SELECT * FROM species ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Wildlife - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-['Inter']">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Manage Wildlife</h1>
            <a href="dashboard.php" class="text-blue-600 hover:underline">Back to Dashboard</a>
        </nav>

        <div class="flex-1 p-8 max-w-6xl mx-auto w-full">
            <?php if ($message): ?>
                <div
                    class="p-4 mb-6 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-bold mb-4">Add / Edit Species</h2>
                <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="id" id="species_id">
                    <input type="hidden" name="current_image_url" id="current_image_url">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full rounded-lg border-gray-300 border p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="0"
                            class="w-full rounded-lg border-gray-300 border p-2">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="2"
                            class="w-full rounded-lg border-gray-300 border p-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full border border-gray-300 rounded-lg p-2">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to keep existing image</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="is_featured" class="ml-2 text-sm text-gray-700">Show on Homepage</label>
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-3">
                        <button type="button" onclick="resetForm()"
                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save
                            Species</button>
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Image</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Featured</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($species_list as $species): ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <?php if ($species['image_url']): ?>
                                        <img src="../<?php echo htmlspecialchars($species['image_url']); ?>"
                                            class="w-12 h-12 object-cover rounded-lg">
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($species['name']); ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($species['is_featured_on_home']): ?>
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Yes</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">No</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4"><?php echo $species['sort_order']; ?></td>
                                <td class="px-6 py-4">
                                    <button onclick='editSpecies(<?php echo json_encode($species); ?>)'
                                        class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                                    <a href="?delete=<?php echo $species['id']; ?>"
                                        onclick="return confirm('Are you sure?')"
                                        class="text-red-600 hover:text-red-800">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function editSpecies(data) {
            document.getElementById('species_id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('sort_order').value = data.sort_order;
            document.getElementById('current_image_url').value = data.image_url;
            document.getElementById('is_featured').checked = data.is_featured_on_home == 1;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            document.getElementById('species_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('description').value = '';
            document.getElementById('sort_order').value = '0';
            document.getElementById('current_image_url').value = '';
            document.getElementById('is_featured').checked = false;
        }
    </script>
</body>

</html>