<?php
session_start();
require_once '../db_connect.php';

// Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$success_msg = '';
$error_msg = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Collect Text Inputs
    $site_name = $_POST['site_name'] ?? '';
    $logo_text = $_POST['logo_text'] ?? '';
    $sub_text = $_POST['sub_text'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $facebook_url = $_POST['facebook_url'] ?? '';
    $instagram_url = $_POST['instagram_url'] ?? '';
    $youtube_url = $_POST['youtube_url'] ?? '';
    
    // Floating Button Settings
    $whatsapp_number = $_POST['whatsapp_number'] ?? '';
    $whatsapp_label = $_POST['whatsapp_label'] ?? '';
    $call_button_number = $_POST['call_button_number'] ?? '';
    $call_label = $_POST['call_label'] ?? '';

    // 2. Handle Logo Upload
    $logo_url = $_POST['current_logo_url'] ?? null;
    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        $fileInfo = pathinfo($_FILES['logo_image']['name']);
        $extension = strtolower($fileInfo['extension']);
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
            $newFileName = 'logo_' . time() . '.' . $extension;
            if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $uploadDir . $newFileName)) {
                $logo_url = 'uploads/' . $newFileName;
            } else {
                $error_msg = "Failed to move uploaded logo file.";
            }
        } else {
            $error_msg = "Invalid logo format.";
        }
    }

    // 3. Handle Second Logo Upload
    $second_logo_url = $_POST['current_second_logo_url'] ?? null;
    if (isset($_FILES['second_logo_image']) && $_FILES['second_logo_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileInfo = pathinfo($_FILES['second_logo_image']['name']);
        $extension = strtolower($fileInfo['extension']);
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
            $newFileName = 'second_logo_' . time() . '.' . $extension;
            if (move_uploaded_file($_FILES['second_logo_image']['tmp_name'], $uploadDir . $newFileName)) {
                $second_logo_url = 'uploads/' . $newFileName;
            } else {
                $error_msg = "Failed to move second logo.";
            }
        }
    }

    // 4. Handle Favicon Upload
    $favicon_url = $_POST['current_favicon_url'] ?? null;
    if (isset($_FILES['favicon_image']) && $_FILES['favicon_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileInfo = pathinfo($_FILES['favicon_image']['name']);
        $extension = strtolower($fileInfo['extension']);
        if (in_array($extension, ['ico', 'png', 'jpg', 'jpeg', 'svg'])) {
            $newFileName = 'favicon_' . time() . '.' . $extension;
            if (move_uploaded_file($_FILES['favicon_image']['tmp_name'], $uploadDir . $newFileName)) {
                $favicon_url = 'uploads/' . $newFileName;
            }
        }
    }

    // 5. Update Database
    if (empty($error_msg)) {
        try {
            $sql = "UPDATE settings SET 
                site_name = ?, logo_text = ?, sub_text = ?, 
                logo_url = ?, second_logo_url = ?, favicon_url = ?,
                phone = ?, email = ?, address = ?, 
                facebook_url = ?, instagram_url = ?, youtube_url = ?, 
                whatsapp_number = ?, whatsapp_label = ?, 
                call_button_number = ?, call_label = ? 
                WHERE id = 1";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $site_name, $logo_text, $sub_text, 
                $logo_url, $second_logo_url, $favicon_url,
                $phone, $email, $address, 
                $facebook_url, $instagram_url, $youtube_url, 
                $whatsapp_number, $whatsapp_label, 
                $call_button_number, $call_label
            ]);

            $success_msg = "Settings updated successfully!";
        } catch (PDOException $e) {
            $error_msg = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch Current Settings
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">General Settings</h1>
            <p class="text-gray-500 mt-2">Configure branding, contact details, and floating buttons.</p>
        </div>
        <a href="../index.php" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">
            Open Website <span class="material-symbols-outlined text-sm">open_in_new</span>
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
            
            <section>
                <h3 class="text-xl font-bold text-safari-dark mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-tiger-yellow">branding_watermark</span> Branding
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Site Name</label>
                        <input type="text" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Logo Text</label>
                        <input type="text" name="logo_text" value="<?php echo htmlspecialchars($settings['logo_text']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Sub Text</label>
                        <input type="text" name="sub_text" value="<?php echo htmlspecialchars($settings['sub_text']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-3">Primary Logo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center border shadow-sm p-1">
                                <?php if (!empty($settings['logo_url'])): ?>
                                    <img src="../<?php echo htmlspecialchars($settings['logo_url']); ?>" class="max-w-full max-h-full object-contain">
                                <?php else: ?>
                                    <span class="text-xs text-gray-300">None</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="logo_image" class="text-sm text-gray-500 w-full mb-2">
                                <input type="hidden" name="current_logo_url" value="<?php echo htmlspecialchars($settings['logo_url'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-3">Partner Logo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center border shadow-sm p-1">
                                <?php if (!empty($settings['second_logo_url'])): ?>
                                    <img src="../<?php echo htmlspecialchars($settings['second_logo_url']); ?>" class="max-w-full max-h-full object-contain">
                                <?php else: ?>
                                    <span class="text-xs text-gray-300">None</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="second_logo_image" class="text-sm text-gray-500 w-full mb-2">
                                <input type="hidden" name="current_second_logo_url" value="<?php echo htmlspecialchars($settings['second_logo_url'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-3">Favicon</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center border shadow-sm p-1">
                                <?php if (!empty($settings['favicon_url'])): ?>
                                    <img src="../<?php echo htmlspecialchars($settings['favicon_url']); ?>" class="max-w-full max-h-full object-contain">
                                <?php else: ?>
                                    <span class="text-xs text-gray-300">None</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="favicon_image" class="text-sm text-gray-500 w-full mb-2">
                                <input type="hidden" name="current_favicon_url" value="<?php echo htmlspecialchars($settings['favicon_url'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="border-gray-100">

            <section>
                <h3 class="text-xl font-bold text-safari-dark mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-tiger-yellow">contact_phone</span> Contact & Social
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($settings['phone']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($settings['email']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                        <textarea name="address" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required><?php echo htmlspecialchars($settings['address']); ?></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Facebook URL</label>
                        <input type="url" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Instagram URL</label>
                        <input type="url" name="instagram_url" value="<?php echo htmlspecialchars($settings['instagram_url']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">YouTube URL</label>
                        <input type="url" name="youtube_url" value="<?php echo htmlspecialchars($settings['youtube_url']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>
                </div>
            </section>

            <hr class="border-gray-100">

            <section>
                <h3 class="text-xl font-bold text-safari-dark mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-tiger-yellow">chat</span> Floating Buttons
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                        <label class="block text-sm font-bold text-gray-700 mb-2">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number"
                            value="<?php echo htmlspecialchars($settings['whatsapp_number'] ?? ''); ?>"
                            placeholder="e.g. +919876543210"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                        <label class="block text-sm font-bold text-gray-700 mt-4 mb-2">Button Label</label>
                        <input type="text" name="whatsapp_label"
                            value="<?php echo htmlspecialchars($settings['whatsapp_label'] ?? 'Chat with us'); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>

                    <div class="bg-orange-50 p-4 rounded-xl border border-orange-100">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Call Button Number</label>
                        <input type="text" name="call_button_number"
                            value="<?php echo htmlspecialchars($settings['call_button_number'] ?? ''); ?>"
                            placeholder="e.g. +919876543210"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                        <label class="block text-sm font-bold text-gray-700 mt-4 mb-2">Button Label</label>
                        <input type="text" name="call_label"
                            value="<?php echo htmlspecialchars($settings['call_label'] ?? 'Call Now'); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                    </div>
                </div>
            </section>

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