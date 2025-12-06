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

include 'header.php';
?>

<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Who We Are</h1>
            <p class="text-gray-500 mt-2">Manage the main introduction section of the homepage.</p>
        </div>
        <a href="../index.php#about" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">
            View on Site <span class="material-symbols-outlined text-sm">open_in_new</span>
        </a>
    </div>

    <?php if ($success_msg): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span> <?php echo $success_msg; ?>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">error</span> <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <form method="POST" enctype="multipart/form-data" class="p-8 space-y-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-safari-dark border-b border-gray-100 pb-2">Main Content</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tagline</label>
                        <input type="text" name="tagline"
                            value="<?php echo htmlspecialchars($about['tagline']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($about['title']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="6"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition"><?php echo htmlspecialchars($about['description']); ?></textarea>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Section Image</label>
                        <div class="flex items-center gap-4">
                            <div class="w-32 h-20 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 shadow-sm">
                                <?php if (strpos($about['image_url'], 'http') === 0): ?>
                                    <img src="<?php echo htmlspecialchars($about['image_url']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <img src="../<?php echo htmlspecialchars($about['image_url']); ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="image" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-safari-green file:shadow-sm hover:file:bg-gray-50 cursor-pointer w-full">
                                <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($about['image_url']); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-safari-dark border-b border-gray-100 pb-2">Features</h3>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-sm text-gray-500 uppercase tracking-wider mb-3">Feature 1</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Icon Code</label>
                                <input type="text" name="feature_1_icon"
                                    value="<?php echo htmlspecialchars($about['feature_1_icon']); ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-safari-green outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Feature Text</label>
                                <input type="text" name="feature_1_text"
                                    value="<?php echo htmlspecialchars($about['feature_1_text']); ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-safari-green outline-none">
                            </div>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-safari-green">
                            <span class="material-symbols-outlined"><?php echo htmlspecialchars($about['feature_1_icon']); ?></span>
                            <span class="text-xs text-gray-400">Preview</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-sm text-gray-500 uppercase tracking-wider mb-3">Feature 2</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Icon Code</label>
                                <input type="text" name="feature_2_icon"
                                    value="<?php echo htmlspecialchars($about['feature_2_icon']); ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-safari-green outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Feature Text</label>
                                <input type="text" name="feature_2_text"
                                    value="<?php echo htmlspecialchars($about['feature_2_text']); ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-safari-green outline-none">
                            </div>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-safari-green">
                            <span class="material-symbols-outlined"><?php echo htmlspecialchars($about['feature_2_icon']); ?></span>
                            <span class="text-xs text-gray-400">Preview</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-sm text-gray-500 uppercase tracking-wider mb-3">Feature 3</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Icon Code</label>
                                <input type="text" name="feature_3_icon"
                                    value="<?php echo htmlspecialchars($about['feature_3_icon']); ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-safari-green outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Feature Text</label>
                                <input type="text" name="feature_3_text"
                                    value="<?php echo htmlspecialchars($about['feature_3_text']); ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-safari-green outline-none">
                            </div>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-safari-green">
                            <span class="material-symbols-outlined"><?php echo htmlspecialchars($about['feature_3_icon']); ?></span>
                            <span class="text-xs text-gray-400">Preview</span>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 bg-blue-50 p-3 rounded-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-500 text-sm">info</span>
                        Find icons at <a href="https://fonts.google.com/icons" target="_blank" class="text-blue-600 hover:underline font-bold">Google Fonts Icons</a>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit"
                    class="bg-safari-green hover:bg-green-800 text-white font-bold py-4 px-10 rounded-xl shadow-lg transition flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

</main>
</div>
</body>
</html>