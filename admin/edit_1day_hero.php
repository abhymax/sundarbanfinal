<?php
session_start();
require_once '../db_connect.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';
$messageType = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title']);
        $subtitle = trim($_POST['subtitle']);
        $cta_text = trim($_POST['cta_text']);
        $cta_link = trim($_POST['cta_link']);
        $image_url = $_POST['current_image_url'];

        // Image Upload
        if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $newFile = '1day_hero_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $uploadDir . $newFile)) {
                    $image_url = 'uploads/' . $newFile;
                }
            }
        }

        $stmt = $pdo->prepare("UPDATE site_sections SET 
            title = ?, subtitle = ?, cta_text = ?, cta_link = ?, image_url = ?, overlay_opacity = ?
            WHERE section_key = '1_day_hero'");
        
        $stmt->execute([
            $title, $subtitle, $cta_text, $cta_link, $image_url, $_POST['overlay_opacity']
        ]);

        $message = "1-Day Tour Hero updated successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// Fetch Data
$stmt = $pdo->prepare("SELECT * FROM site_sections WHERE section_key = '1_day_hero'");
$stmt->execute();
$hero = $stmt->fetch(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">1-Day Tour Hero</h1>
            <p class="text-gray-500 mt-2">Edit the top banner of the 1-Day Tour page.</p>
        </div>
        <a href="../1-day-tour.php" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">
            Preview Page <span class="material-symbols-outlined text-sm">visibility</span>
        </a>
    </div>

    <?php if ($message): ?>
        <div class="p-4 mb-6 rounded-xl border <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400'; ?> flex items-center gap-2">
            <span class="material-symbols-outlined"><?php echo $messageType === 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <form method="POST" enctype="multipart/form-data" class="p-8 space-y-8">

            <section>
                <h3 class="text-xl font-bold text-safari-dark mb-6 border-b border-gray-100 pb-2">Headlines</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Main Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($hero['title']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition text-lg font-serif" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Subtitle</label>
                        <textarea name="subtitle" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required><?php echo htmlspecialchars($hero['subtitle']); ?></textarea>
                    </div>
                </div>
            </section>

            <section>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-bold text-safari-dark mb-4 border-b border-gray-100 pb-2">Primary Button</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Button Text</label>
                                <input type="text" name="cta_text" value="<?php echo htmlspecialchars($hero['cta_text']); ?>"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Button Link (e.g., #itinerary)</label>
                                <input type="text" name="cta_link" value="<?php echo htmlspecialchars($hero['cta_link']); ?>"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-safari-dark mb-4 border-b border-gray-100 pb-2">Background</h3>
                        <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-3">Cover Image</label>
                            <div class="mb-3 w-full h-32 bg-gray-200 rounded-lg overflow-hidden relative group">
                                <?php if (!empty($hero['image_url'])): ?>
                                    <img src="../<?php echo htmlspecialchars($hero['image_url']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="flex items-center justify-center h-full text-gray-400 text-xs">No Image</div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="hero_image" accept="image/*" class="text-sm w-full">
                            <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($hero['image_url']); ?>">
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Overlay Opacity: <span id="opVal"><?php echo $hero['overlay_opacity']; ?></span></label>
                            <input type="range" name="overlay_opacity" min="0" max="1" step="0.1" 
                                value="<?php echo htmlspecialchars($hero['overlay_opacity']); ?>" 
                                class="w-full accent-safari-green" oninput="document.getElementById('opVal').innerText = this.value">
                        </div>
                    </div>
                </div>
            </section>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit"
                    class="bg-safari-green hover:bg-green-800 text-white font-bold py-4 px-10 rounded-xl shadow-lg transition flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span> Save Update
                </button>
            </div>

        </form>
    </div>
</div>

</main>
</div>
</body>
</html>