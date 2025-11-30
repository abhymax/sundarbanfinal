<?php
session_start();

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../db_connect.php';

$id = $_GET['id'] ?? null;
$package = null;
$error = '';
$success = '';

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

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $subtitle = $_POST['subtitle'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $price = $_POST['price'] ?? 0;
    $features = $_POST['features'] ?? '';
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;

    // Image Upload
    $image_url = $package['image_url'] ?? ''; // Default to existing
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = 'package_' . time() . '.' . $fileExtension;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image_url = 'uploads/' . $fileName; // Store relative path for frontend use
        } else {
            $error = "Failed to upload image.";
        }
    }

    if (!$error) {
        try {
            if ($id) {
                // Update
                $sql = "UPDATE packages SET title = ?, subtitle = ?, slug = ?, duration = ?, price = ?, features = ?, is_popular = ?, is_bestseller = ?, image_url = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $subtitle, $slug, $duration, $price, $features, $is_popular, $is_bestseller, $image_url, $id]);
            } else {
                // Insert
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Package - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php echo $id ? 'Edit' : 'Add New'; ?> Package</h2>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($package['title'] ?? ''); ?>"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Subtitle</label>
                        <input type="text" name="subtitle"
                            value="<?php echo htmlspecialchars($package['subtitle'] ?? ''); ?>"
                            class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Slug (URL)</label>
                        <input type="text" name="slug" value="<?php echo htmlspecialchars($package['slug'] ?? ''); ?>"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Duration</label>
                        <input type="text" name="duration"
                            value="<?php echo htmlspecialchars($package['duration'] ?? ''); ?>"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Price (₹)</label>
                        <input type="number" name="price" step="0.01"
                            value="<?php echo htmlspecialchars($package['price'] ?? ''); ?>"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Features (Comma separated)</label>
                    <textarea name="features" rows="3"
                        class="w-full border rounded px-3 py-2"><?php echo htmlspecialchars($package['features'] ?? ''); ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">Example: Breakfast included, AC Room, Guide</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Package Image</label>
                    <?php if (!empty($package['image_url'])): ?>
                        <div class="mb-2">
                            <img src="../<?php echo htmlspecialchars($package['image_url']); ?>" alt="Current Image"
                                class="h-20 rounded">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>

                <div class="flex items-center gap-6 mb-8">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_popular" class="form-checkbox h-5 w-5 text-green-600" <?php echo ($package['is_popular'] ?? 0) ? 'checked' : ''; ?>>
                        <span class="ml-2 text-gray-700">Is Popular?</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_bestseller" class="form-checkbox h-5 w-5 text-green-600" <?php echo ($package['is_bestseller'] ?? 0) ? 'checked' : ''; ?>>
                        <span class="ml-2 text-gray-700">Is Bestseller?</span>
                    </label>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="manage_packages.php" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                        Save Package
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>