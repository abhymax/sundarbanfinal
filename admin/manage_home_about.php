<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$success_msg = '';
$error_msg = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tagline = $_POST['tagline'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $feature_1_icon = $_POST['feature_1_icon'];
    $feature_1_text = $_POST['feature_1_text'];
    $feature_2_icon = $_POST['feature_2_icon'];
    $feature_2_text = $_POST['feature_2_text'];
    $feature_3_icon = $_POST['feature_3_icon'];
    $feature_3_text = $_POST['feature_3_text'];

    // Handle Image Upload
    $image_url = $_POST['current_image_url'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileInfo = pathinfo($_FILES['image']['name']);
        $extension = strtolower($fileInfo['extension']);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $allowedExtensions)) {
            $newFileName = 'about_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image_url = 'uploads/' . $newFileName;
            } else {
                $error_msg = "Failed to move uploaded file.";
            }
        } else {
            $error_msg = "Invalid file type. Only JPG, PNG, and WEBP are allowed.";
        }
    }

    if (empty($error_msg)) {
        try {
            $stmt = $pdo->prepare("UPDATE home_about SET 
                tagline = ?, 
                title = ?, 
                description = ?, 
                image_url = ?, 
                feature_1_icon = ?, 
                feature_1_text = ?, 
                feature_2_icon = ?, 
                feature_2_text = ?, 
                feature_3_icon = ?, 
                feature_3_text = ? 
                WHERE id = 1");

            $stmt->execute([
                $tagline,
                $title,
                $description,
                $image_url,
                $feature_1_icon,
                $feature_1_text,
                $feature_2_icon,
                $feature_2_text,
                $feature_3_icon,
                $feature_3_text
            ]);

            $success_msg = "Content updated successfully!";
        } catch (PDOException $e) {
            $error_msg = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch Current Data
$stmt = $pdo->query("SELECT * FROM home_about WHERE id = 1");
$about = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Who We Are | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
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
                <a href="manage_packages.php" class="block py-3 px-6 hover:bg-gray-800">Packages</a>
                <a href="manage_home_about.php" class="block py-3 px-6 bg-gray-800 border-l-4 border-orange-500">Who We
                    Are</a>
                <a href="settings.php" class="block py-3 px-6 hover:bg-gray-800">General Settings</a>
                <a href="manage_menu.php" class="block py-3 px-6 hover:bg-gray-800">Manage Menu</a>
                <a href="logout.php" class="block py-3 px-6 hover:bg-red-600 mt-10">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Manage "Who We Are" Section</h1>
                <a href="../index.php" target="_blank" class="text-blue-600 hover:underline">View Site</a>
            </div>

            <?php if ($success_msg): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-md p-6">
                <form method="POST" enctype="multipart/form-data" class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Main Content -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Main Content</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                                <input type="text" name="tagline"
                                    value="<?php echo htmlspecialchars($about['tagline']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($about['title']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" rows="5"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500"><?php echo htmlspecialchars($about['description']); ?></textarea>
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Section Image</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-32 h-20 bg-gray-100 rounded overflow-hidden border">
                                        <?php if (strpos($about['image_url'], 'http') === 0): ?>
                                            <img src="<?php echo htmlspecialchars($about['image_url']); ?>"
                                                class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <img src="../<?php echo htmlspecialchars($about['image_url']); ?>"
                                                class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                    <input type="file" name="image" accept="image/*" class="text-sm text-gray-500">
                                    <input type="hidden" name="current_image_url"
                                        value="<?php echo htmlspecialchars($about['image_url']); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Features</h3>

                            <!-- Feature 1 -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-bold text-sm text-gray-600 mb-2">Feature 1</h4>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="col-span-1">
                                        <label class="block text-xs text-gray-500">Icon (Material Symbol)</label>
                                        <input type="text" name="feature_1_icon"
                                            value="<?php echo htmlspecialchars($about['feature_1_icon']); ?>"
                                            class="w-full border rounded px-2 py-1 text-sm">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs text-gray-500">Text</label>
                                        <input type="text" name="feature_1_text"
                                            value="<?php echo htmlspecialchars($about['feature_1_text']); ?>"
                                            class="w-full border rounded px-2 py-1 text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-bold text-sm text-gray-600 mb-2">Feature 2</h4>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="col-span-1">
                                        <label class="block text-xs text-gray-500">Icon (Material Symbol)</label>
                                        <input type="text" name="feature_2_icon"
                                            value="<?php echo htmlspecialchars($about['feature_2_icon']); ?>"
                                            class="w-full border rounded px-2 py-1 text-sm">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs text-gray-500">Text</label>
                                        <input type="text" name="feature_2_text"
                                            value="<?php echo htmlspecialchars($about['feature_2_text']); ?>"
                                            class="w-full border rounded px-2 py-1 text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Feature 3 -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-bold text-sm text-gray-600 mb-2">Feature 3</h4>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="col-span-1">
                                        <label class="block text-xs text-gray-500">Icon (Material Symbol)</label>
                                        <input type="text" name="feature_3_icon"
                                            value="<?php echo htmlspecialchars($about['feature_3_icon']); ?>"
                                            class="w-full border rounded px-2 py-1 text-sm">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs text-gray-500">Text</label>
                                        <input type="text" name="feature_3_text"
                                            value="<?php echo htmlspecialchars($about['feature_3_text']); ?>"
                                            class="w-full border rounded px-2 py-1 text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="text-xs text-gray-500">
                                Tip: Find icons at <a href="https://fonts.google.com/icons" target="_blank"
                                    class="text-blue-500 hover:underline">Google Fonts Icons</a>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700 transition shadow-lg">
                            Save Content
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>