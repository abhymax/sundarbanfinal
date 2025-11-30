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
    $site_name = $_POST['site_name'];
    $logo_text = $_POST['logo_text'];
    $sub_text = $_POST['sub_text'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $facebook_url = $_POST['facebook_url'];
    $instagram_url = $_POST['instagram_url'];
    $youtube_url = $_POST['youtube_url'];
    $whatsapp_number = $_POST['whatsapp_number'];
    $call_button_number = $_POST['call_button_number'];
    $whatsapp_label = $_POST['whatsapp_label'];
    $call_label = $_POST['call_label'];

    // Handle Logo Upload
    $logo_url = $_POST['current_logo_url'] ?? null;

    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileInfo = pathinfo($_FILES['logo_image']['name']);
        $extension = strtolower($fileInfo['extension']);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

        if (in_array($extension, $allowedExtensions)) {
            $newFileName = 'logo_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $targetPath)) {
                $logo_url = 'uploads/' . $newFileName;
            } else {
                $error_msg = "Failed to move uploaded logo file.";
            }
        } else {
            $error_msg = "Invalid logo file type. Only JPG, PNG, WEBP, and SVG are allowed.";
        }
    }

    // Handle Second Logo Upload
    $second_logo_url = $_POST['current_second_logo_url'] ?? null;

    if (isset($_FILES['second_logo_image']) && $_FILES['second_logo_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileInfo = pathinfo($_FILES['second_logo_image']['name']);
        $extension = strtolower($fileInfo['extension']);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

        if (in_array($extension, $allowedExtensions)) {
            $newFileName = 'second_logo_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['second_logo_image']['tmp_name'], $targetPath)) {
                $second_logo_url = 'uploads/' . $newFileName;
            } else {
                $error_msg = "Failed to move uploaded second logo file.";
            }
        } else {
            $error_msg = "Invalid second logo file type. Only JPG, PNG, WEBP, and SVG are allowed.";
        }
    }

    // Handle Favicon Upload
    $favicon_url = $_POST['current_favicon_url'] ?? null;

    if (isset($_FILES['favicon_image']) && $_FILES['favicon_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileInfo = pathinfo($_FILES['favicon_image']['name']);
        $extension = strtolower($fileInfo['extension']);
        $allowedExtensions = ['ico', 'png', 'jpg', 'jpeg', 'svg'];

        if (in_array($extension, $allowedExtensions)) {
            $newFileName = 'favicon_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['favicon_image']['tmp_name'], $targetPath)) {
                $favicon_url = 'uploads/' . $newFileName;
            } else {
                $error_msg = "Failed to move uploaded favicon file.";
            }
        } else {
            $error_msg = "Invalid favicon file type. Only ICO, PNG, JPG, and SVG are allowed.";
        }
    }

    // Update Database
    try {
        $stmt = $pdo->prepare("UPDATE settings SET 
            site_name = ?, 
            logo_text = ?, 
            sub_text = ?, 
            logo_url = ?, 
            second_logo_url = ?, 
            phone = ?, 
            email = ?, 
            address = ?, 
            facebook_url = ?, 
            instagram_url = ?, 
            youtube_url = ?, 
            whatsapp_number = ?,
            call_button_number = ?,
            whatsapp_label = ?,
            call_label = ?,
            favicon_url = ?
            WHERE id = 1");

        $stmt->execute([
            $site_name,
            $logo_text,
            $sub_text,
            $logo_url,
            $second_logo_url,
            $phone,
            $email,
            $address,
            $facebook_url,
            $instagram_url,
            $youtube_url,
            $whatsapp_number,
            $call_button_number,
            $whatsapp_label,
            $call_label,
            $favicon_url
        ]);

        $success_msg = "Settings updated successfully!";
    } catch (PDOException $e) {
        $error_msg = "Database error: " . $e->getMessage();
    }
}

// Fetch Current Settings
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Settings | Admin</title>
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
                <a href="manage_home_about.php" class="block py-3 px-6 hover:bg-gray-800">Who We Are</a>
                <a href="settings.php" class="block py-3 px-6 bg-gray-800 border-l-4 border-orange-500">General
                    Settings</a>
                <a href="manage_menu.php" class="block py-3 px-6 hover:bg-gray-800">Manage Menu</a>
                <a href="logout.php" class="block py-3 px-6 hover:bg-red-600 mt-10">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">General Settings</h1>
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
                    <!-- Branding -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Branding</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                                <input type="text" name="site_name"
                                    value="<?php echo htmlspecialchars($settings['site_name']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Text</label>
                                <input type="text" name="logo_text"
                                    value="<?php echo htmlspecialchars($settings['logo_text']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sub Text</label>
                                <input type="text" name="sub_text"
                                    value="<?php echo htmlspecialchars($settings['sub_text']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500"
                                    required>
                            </div>
                        </div>

                        <!-- Logo Upload -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo Image (Overrides
                                Text)</label>
                            <div class="flex items-center gap-6">
                                <div
                                    class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 overflow-hidden">
                                    <?php if (!empty($settings['logo_url'])): ?>
                                                <img src="../<?php echo htmlspecialchars($settings['logo_url']); ?>"
                                                    alt="Current Logo" class="w-full h-full object-contain">
                                    <?php else: ?>
                                                <span class="text-xs text-gray-400">No Logo</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo_image" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                                    <p class="text-xs text-gray-500 mt-1">Upload a PNG or SVG logo for best results.</p>
                                    <input type="hidden" name="current_logo_url"
                                        value="<?php echo htmlspecialchars($settings['logo_url'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Second Logo Upload -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Second Logo (Partner
                                Logo)</label>
                            <div class="flex items-center gap-6">
                                <div
                                    class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 overflow-hidden">
                                    <?php if (!empty($settings['second_logo_url'])): ?>
                                                <img src="../<?php echo htmlspecialchars($settings['second_logo_url']); ?>"
                                                    alt="Second Logo" class="w-full h-full object-contain">
                                    <?php else: ?>
                                                <span class="text-xs text-gray-400">No Logo</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="second_logo_image" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                                    <p class="text-xs text-gray-500 mt-1">Upload a PNG or SVG logo for best results.</p>
                                    <input type="hidden" name="current_second_logo_url"
                                        value="<?php echo htmlspecialchars($settings['second_logo_url'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Favicon Upload -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon (Browser Tab Icon)</label>
                            <div class="flex items-center gap-6">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 overflow-hidden">
                                    <?php if (!empty($settings['favicon_url'])): ?>
                                            <img src="../<?php echo htmlspecialchars($settings['favicon_url']); ?>"
                                                alt="Favicon" class="w-full h-full object-contain">
                                    <?php else: ?>
                                            <span class="text-xs text-gray-400">None</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="favicon_image" accept=".ico,.png,.jpg,.jpeg,.svg"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                                    <p class="text-xs text-gray-500 mt-1">Upload an ICO, PNG, or SVG file.</p>
                                    <input type="hidden" name="current_favicon_url"
                                        value="<?php echo htmlspecialchars($settings['favicon_url'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone"
                                    value="<?php echo htmlspecialchars($settings['phone']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email"
                                    value="<?php echo htmlspecialchars($settings['email']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500"
                                    required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea name="address" rows="3"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500"
                                    required><?php echo htmlspecialchars($settings['address']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Social Media Links</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                                <input type="url" name="facebook_url"
                                    value="<?php echo htmlspecialchars($settings['facebook_url']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                                <input type="url" name="instagram_url"
                                    value="<?php echo htmlspecialchars($settings['instagram_url']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL</label>
                                <input type="url" name="youtube_url"
                                    value="<?php echo htmlspecialchars($settings['youtube_url']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-700 mt-6 mb-4 border-b pb-2">Floating Action Buttons</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number (for Floating Icon)</label>
                                <input type="text" name="whatsapp_number"
                                    value="<?php echo htmlspecialchars($settings['whatsapp_number']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                                <p class="text-xs text-gray-500 mt-1">Include country code (e.g., +91)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Button Label</label>
                                <input type="text" name="whatsapp_label"
                                    value="<?php echo htmlspecialchars($settings['whatsapp_label'] ?? 'Chat with us'); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Call Button Number (for Floating Icon)</label>
                                <input type="text" name="call_button_number"
                                    value="<?php echo htmlspecialchars($settings['call_button_number'] ?? $settings['phone']); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                                <p class="text-xs text-gray-500 mt-1">Include country code (e.g., +91)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Call Button Label</label>
                                <input type="text" name="call_label"
                                    value="<?php echo htmlspecialchars($settings['call_label'] ?? 'Call Now'); ?>"
                                    class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700 transition shadow-lg">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>