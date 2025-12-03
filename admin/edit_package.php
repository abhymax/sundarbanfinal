<?php
session_start();
require_once '../db_connect.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
$package = null;
$error = '';

// Fetch Package if ID is present
if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
        $stmt->execute([$id]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$package) {
            header('Location: manage_packages.php');
            exit;
        }
    } catch (PDOException $e) {
        $error = "Error fetching package: " . $e->getMessage();
    }
}

// Handle Form Submission (kept original logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $subtitle = $_POST['subtitle'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $price = $_POST['price'] ?? 0;
    $features = $_POST['features'] ?? '';
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;

    $image_url = $package['image_url'] ?? ''; 
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = 'package_' . time() . '.' . $fileExtension;
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image_url = 'uploads/' . $fileName;
        } else {
            $error = "Failed to upload image.";
        }
    }

    if (!$error) {
        try {
            if ($id) {
                $sql = "UPDATE packages SET title = ?, subtitle = ?, slug = ?, duration = ?, price = ?, features = ?, is_popular = ?, is_bestseller = ?, image_url = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $subtitle, $slug, $duration, $price, $features, $is_popular, $is_bestseller, $image_url, $id]);
            } else {
                $sql = "INSERT INTO packages (title, subtitle, slug, duration, price, features, is_popular, is_bestseller, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $subtitle, $slug, $duration, $price, $features, $is_popular, $is_bestseller, $image_url]);
            }
            header('Location: manage_packages.php');
            exit;
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

include 'header.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="manage_packages.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:text-safari-green hover:border-safari-green transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-3xl font-bold text-safari-dark font-serif"><?php echo $id ? 'Edit Package' : 'Create Package'; ?></h1>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <form method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Package Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($package['title'] ?? ''); ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">URL Slug</label>
                    <input type="text" name="slug" value="<?php echo htmlspecialchars($package['slug'] ?? ''); ?>"
                        placeholder="e.g. 1-day-tour"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subtitle</label>
                    <input type="text" name="subtitle"
                        value="<?php echo htmlspecialchars($package['subtitle'] ?? ''); ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Duration</label>
                    <input type="text" name="duration"
                        value="<?php echo htmlspecialchars($package['duration'] ?? ''); ?>"
                        placeholder="e.g. 2 Days 1 Night"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Price (₹)</label>
                    <input type="number" name="price" step="0.01"
                        value="<?php echo htmlspecialchars($package['price'] ?? ''); ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Features (Comma separated)</label>
                <textarea name="features" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition"><?php echo htmlspecialchars($package['features'] ?? ''); ?></textarea>
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1"><span class="material-symbols-outlined text-xs">info</span> Used for the bullet points on the card.</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 border-dashed">
                <label class="block text-sm font-bold text-gray-700 mb-4">Package Image</label>
                <div class="flex items-center gap-6">
                    <?php if (!empty($package['image_url'])): ?>
                        <div class="shrink-0">
                            <img src="../<?php echo htmlspecialchars($package['image_url']); ?>" alt="Current"
                                class="w-24 h-24 object-cover rounded-lg shadow-sm">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 cursor-pointer">
                </div>
            </div>

            <div class="flex gap-6 p-4 bg-gray-50 rounded-xl">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_popular" class="w-5 h-5 text-safari-green rounded focus:ring-safari-green border-gray-300" <?php echo ($package['is_popular'] ?? 0) ? 'checked' : ''; ?>>
                    <span class="ml-3 text-gray-700 font-medium">Mark as Popular</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_bestseller" class="w-5 h-5 text-safari-green rounded focus:ring-safari-green border-gray-300" <?php echo ($package['is_bestseller'] ?? 0) ? 'checked' : ''; ?>>
                    <span class="ml-3 text-gray-700 font-medium">Mark as Bestseller</span>
                </label>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="manage_packages.php" class="px-6 py-3 text-gray-600 font-bold hover:bg-gray-100 rounded-xl transition">Cancel</a>
                <button type="submit"
                    class="bg-safari-green hover:bg-green-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span> Save Package
                </button>
            </div>
        </form>
    </div>
</div>

</main>
</div>
</body>
</html>