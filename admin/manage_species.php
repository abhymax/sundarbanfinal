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

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($ext, $allowed)) {
                $newFile = 'species_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFile)) {
                    $image_url = 'uploads/' . $newFile; 
                } else {
                    throw new Exception("Failed to move uploaded file.");
                }
            } else {
                throw new Exception("Invalid file type. Only JPG, PNG, and WEBP are allowed.");
            }
        }

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE species SET name=?, description=?, image_url=?, is_featured_on_home=?, sort_order=? WHERE id=?");
            $stmt->execute([$name, $description, $image_url, $is_featured, $sort_order, $_POST['id']]);
            $message = "Species updated successfully!";
        } else {
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

include 'header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-safari-dark font-serif">Wildlife Checklist</h1>
        <p class="text-gray-500 mt-2">Manage the animals and plants displayed on the website.</p>
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
                    <span class="material-symbols-outlined text-tiger-yellow">add_circle</span> Add / Edit
                </h2>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="id" id="species_id">
                    <input type="hidden" name="current_image_url" id="current_image_url">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="0"
                            class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to keep existing.</p>
                    </div>

                    <div class="flex items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                            class="w-5 h-5 text-safari-green rounded focus:ring-safari-green border-gray-300">
                        <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Featured on Home</label>
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
                            <th class="px-6 py-3 font-bold">Image</th>
                            <th class="px-6 py-3 font-bold">Details</th>
                            <th class="px-6 py-3 font-bold">Status</th>
                            <th class="px-6 py-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <?php foreach ($species_list as $species): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <?php 
                                    $imgSrc = $species['image_url'];
                                    if (!empty($imgSrc)) {
                                        if (strpos($imgSrc, 'http') !== 0) $imgSrc = "../" . $imgSrc;
                                        echo '<img src="' . htmlspecialchars($imgSrc) . '" class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm">';
                                    } else {
                                        echo '<div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400"><span class="material-symbols-outlined">image</span></div>';
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-base font-serif"><?php echo htmlspecialchars($species['name']); ?></div>
                                    <div class="text-xs text-gray-500 mt-1 line-clamp-2"><?php echo htmlspecialchars($species['description']); ?></div>
                                    <span class="text-xs text-gray-400 mt-1 block">Order: <?php echo $species['sort_order']; ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($species['is_featured_on_home']): ?>
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full font-bold border border-green-200">Featured</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full border border-gray-200">Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button onclick='editSpecies(<?php echo json_encode($species); ?>)'
                                        class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition mr-1"><span class="material-symbols-outlined text-sm">edit</span></button>
                                    <a href="?delete=<?php echo $species['id']; ?>"
                                        onclick="return confirm('Are you sure?')"
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
</div>

</main>
</div>
</body>
</html>