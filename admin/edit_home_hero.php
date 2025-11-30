<?php
session_start();
require_once '../db_connect.php';

// 1. Authentication Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';
$messageType = '';

// 2. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title']);
        $subtitle = trim($_POST['subtitle']);
        $cta_text = trim($_POST['cta_text']);
        $cta_link = trim($_POST['cta_link']);
        $sub_heading_font_size = $_POST['sub_heading_font_size'] ?? 'text-xl';

        // Handle Image Upload
        $image_url = $_POST['current_image_url']; // Default to existing

        if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileInfo = pathinfo($_FILES['hero_image']['name']);
            $extension = strtolower($fileInfo['extension']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($extension, $allowedExtensions)) {
                $newFileName = 'home_hero_' . time() . '.' . $extension;
                $targetPath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $targetPath)) {
                    $image_url = 'uploads/' . $newFileName;
                } else {
                    throw new Exception("Failed to move uploaded file.");
                }
            } else {
                throw new Exception("Invalid file type. Only JPG, PNG, and WEBP are allowed.");
            }
        }

        // Handle Video Upload or URL
        $video_url = $_POST['video_url'] ?? ''; // Default to text input

        if (isset($_FILES['hero_video']) && $_FILES['hero_video']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileInfo = pathinfo($_FILES['hero_video']['name']);
            $extension = strtolower($fileInfo['extension']);
            $allowedExtensions = ['mp4', 'webm'];

            if (in_array($extension, $allowedExtensions)) {
                $newFileName = 'hero_video_' . time() . '.' . $extension;
                $targetPath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['hero_video']['tmp_name'], $targetPath)) {
                    $video_url = 'uploads/' . $newFileName;
                } else {
                    throw new Exception("Failed to move uploaded video.");
                }
            } else {
                throw new Exception("Invalid video type. Only MP4 and WebM are allowed.");
            }
        }

        // Update Database
        $sql = "UPDATE site_sections SET 
                title = :title, 
                subtitle = :subtitle, 
                cta_text = :cta_text, 
                cta_link = :cta_link, 
                image_url = :image_url,
                video_url = :video_url,
                overlay_opacity = :overlay_opacity,
                sub_heading_font_size = :sub_heading_font_size
                WHERE section_key = 'home_hero'";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':subtitle' => $subtitle,
            ':cta_text' => $cta_text,
            ':cta_link' => $cta_link,
            ':image_url' => $image_url,
            ':video_url' => $video_url,
            ':overlay_opacity' => $_POST['overlay_opacity'] ?? 0.6,
            ':sub_heading_font_size' => $sub_heading_font_size
        ]);

        $message = "Hero section updated successfully!";
        $messageType = "success";

    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// 3. Fetch Current Data
$stmt = $pdo->prepare("SELECT * FROM site_sections WHERE section_key = 'home_hero'");
$stmt->execute();
$hero = $stmt->fetch();

if (!$hero) {
    // Fallback if seed data is missing
    $hero = [
        'title' => '',
        'subtitle' => '',
        'cta_text' => '',
        'cta_link' => '',
        'image_url' => '',
        'video_url' => '',
        'overlay_opacity' => 0.6,
        'sub_heading_font_size' => 'text-xl'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Home Hero - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="dashboard.php" class="flex items-center text-gray-500 hover:text-gray-900 transition">
                        <span class="material-symbols-outlined mr-1">arrow_back</span>
                        Back to Dashboard
                    </a>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-500 mr-4 text-sm">Welcome, Admin</span>
                    <a href="logout.php"
                        class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                        <span class="material-symbols-outlined text-sm mr-1">logout</span> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-10">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Home Page Hero</h1>
        </div>

        <?php if ($message): ?>
            <div class="<?php echo $messageType === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form method="POST" enctype="multipart/form-data" class="p-8 space-y-6">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hero Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($hero['title']); ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                        required>
                </div>

                <!-- Subtitle -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subtitle</label>
                    <textarea name="subtitle" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                        required><?php echo htmlspecialchars($hero['subtitle']); ?></textarea>
                </div>

                <!-- Subtitle Font Size -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subtitle Font Size</label>
                    <select name="sub_heading_font_size"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none">
                        <?php
                        $sizes = [
                            'text-base' => 'Normal',
                            'text-lg' => 'Large',
                            'text-xl' => 'Extra Large',
                            'text-2xl' => '2x Large',
                            'text-3xl' => '3x Large'
                        ];
                        $currentSize = $hero['sub_heading_font_size'] ?? 'text-xl';
                        foreach ($sizes as $value => $label) {
                            $selected = $value === $currentSize ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CTA Text -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">CTA Button Text</label>
                        <input type="text" name="cta_text" value="<?php echo htmlspecialchars($hero['cta_text']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                            required>
                    </div>

                    <!-- CTA Link -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">CTA Link</label>
                        <input type="text" name="cta_link" value="<?php echo htmlspecialchars($hero['cta_link']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                            required>
                    </div>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Background Image</label>

                    <div class="flex items-start gap-6">
                        <div class="w-1/3">
                            <p class="text-xs text-gray-500 mb-2">Current Image:</p>
                            <?php if (!empty($hero['image_url'])): ?>
                                <img src="../<?php echo htmlspecialchars($hero['image_url']); ?>" alt="Current Hero"
                                    class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            <?php else: ?>
                                <div
                                    class="w-full h-32 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 text-xs">
                                    No Image</div>
                            <?php endif; ?>
                            <input type="hidden" name="current_image_url"
                                value="<?php echo htmlspecialchars($hero['image_url']); ?>">
                        </div>

                        <div class="w-2/3">
                            <input type="file" name="hero_image" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-2">Upload a new image to replace the current one.
                                Recommended size: 1920x1080px.</p>
                        </div>
                    </div>
                </div>

                <!-- Video Settings -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Video Background Settings</h3>

                    <!-- Video URL -->
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Video URL (YouTube or Direct
                            Link)</label>
                        <input type="text" name="video_url"
                            value="<?php echo htmlspecialchars($hero['video_url'] ?? ''); ?>"
                            placeholder="https://www.youtube.com/watch?v=..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none">
                        <p class="text-xs text-gray-500 mt-1">Paste a YouTube link or a direct URL to an MP4 file.</p>
                    </div>

                    <!-- Video Upload -->
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">OR Upload Video File</label>
                        <input type="file" name="hero_video" accept="video/mp4,video/webm"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                        <p class="text-xs text-gray-500 mt-1">Upload an MP4 or WebM file (Max 10MB recommended). This
                            will override the URL above.</p>
                    </div>

                    <!-- Overlay Opacity -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Overlay Opacity (0.0 to 1.0)</label>
                        <div class="flex items-center gap-4">
                            <input type="range" name="overlay_opacity" min="0" max="1" step="0.1"
                                value="<?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.6'); ?>"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                oninput="document.getElementById('opacityValue').innerText = this.value">
                            <span id="opacityValue"
                                class="font-bold text-gray-700 w-10"><?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.6'); ?></span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Adjust the darkness of the overlay on top of the
                            video/image.</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit"
                        class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-300 flex items-center">
                        <span class="material-symbols-outlined mr-2">save</span> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>

</html>